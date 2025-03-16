<?php

namespace App\Paygate;

use App\Helpers\Logs;
use App\Models\Paygate;
use Exception;
use finfo;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\RedirectResponse;

class PayPalAPI {

    private $clientId, $clientSecret, $apiUrl, $accessToken;

    /**
     * Constructor for initializing PayPal API credentials and base URL.
     *
     * @param Paygate $paygate The Paygate model containing API credentials and mode.
     *
     * This constructor:
     * - Decodes API credentials from the Paygate model.
     * - Sets the client ID and secret key from the API data.
     * - Determines the API URL based on the mode (sandbox or live).
     * - Retrieves and sets the access token for authentication.
     */
    public function __construct(Paygate $paygate) {
        $api_data           = json_decode($paygate->api_data, true) ?? [];
        $this->clientId     = $api_data['client_key'] ?? '';
        $this->clientSecret = $api_data['secret_key'] ?? '';
        $this->apiUrl       = $paygate->mode === 0 ? "https://api-m.sandbox.paypal.com" : "https://api-m.paypal.com";
        $this->accessToken  = $this->getAccessToken();
    }

    /**
     * Retrieves an access token for authenticating API requests.
     *
     * This function sends a request to PayPal's OAuth2 API to obtain an access token
     * using client credentials authentication.
     *
     * @return string The access token for API authentication.
     * @throws Exception If the access token cannot be retrieved.
     *
     */
    public function getAccessToken() {
        $response = $this->makeRequest("POST", "/v1/oauth2/token", "grant_type=client_credentials", true);
        return $response['access_token'] ?? throw new Exception("Không thể lấy Access Token.");
    }

    /**
     * Creates a new payment.
     *
     * @param float  $amount    The amount to be paid.
     * @param string $currency  The currency of the payment (e.g., "USD", "EUR").
     * @param string $returnUrl The URL to redirect to after a successful payment.
     * @param string $cancelUrl The URL to redirect to if the payment is canceled.
     *
     * @return array The API response containing payment details.
     */
    public function createPayment($amount, $currency, $returnUrl, $cancelUrl) {
        return $this->makeRequest("POST", "/v1/payments/payment", [
            "intent"        => "sale",
            "payer"         => ["payment_method" => "paypal"],
            "transactions"  => [
                [
                    "amount" => [
                        "total"    => $amount,
                        "currency" => $currency,
                    ],
                ],
            ],
            "redirect_urls" => [
                "return_url" => $returnUrl,
                "cancel_url" => $cancelUrl,
            ],
        ]);
    }

    /**
     * Retrieves the details of a payment.
     *
     * @param string $paymentId The ID of the payment to retrieve details for.
     *
     * @return array The API response containing payment details.
     */
    public function getPaymentDetails($paymentId) {
        return $this->makeRequest("GET", "/v1/payments/payment/{$paymentId}");
    }

    /**
     * Processes a refund for a payment.
     *
     * @param string $saleId   The ID of the sale transaction to be refunded.
     * @param float  $amount   The amount to be refunded.
     * @param string $currency The currency of the refund (e.g., "USD", "EUR").
     *
     * @return array The API response containing refund details.
     */
    public function refundPayment($saleId, $amount, $currency) {
        return $this->makeRequest("POST", "/v1/payments/sale/{$saleId}/refund", [
            "amount" => [
                "total"    => $amount,
                "currency" => $currency,
            ],
        ]);
    }

    /**
     * Lists transactions within a specified time range.
     *
     * @param string      $startDate     The start date of the transaction period.
     * @param string      $endDate       The end date of the transaction period.
     * @param string|null $transactionId (Optional) The ID of a specific transaction to filter.
     * @param string      $fields        Specifies which fields to include in the response.
     *
     * @return array The API response containing the list of transactions.
     */
    public function listTransaction($startDate, $endDate, $transactionId = null, $fields = "all") {
        $query = http_build_query(array_filter([
            "start_date"     => $startDate,
            "end_date"       => $endDate,
            "fields"         => $fields,
            "transaction_id" => $transactionId,
        ]));
        return $this->makeRequest("GET", "/v1/reporting/transactions?{$query}");
    }

    public function getTransactionDetail() {

    }

    /**
     * Sends an HTTP request to the PayPal API.
     *
     * @param string       $method   The HTTP method (e.g., GET, POST, PUT, DELETE).
     * @param string       $endpoint The API endpoint to send the request to.
     * @param array|string $data     The request payload, either as an array or a JSON string.
     * @param bool         $isAuth   Whether to include authentication headers (default: false).
     *
     * @return array The API response as an associative array.
     * @throws Exception If an error occurs during the request.
     */
    private function makeRequest($method, $endpoint, $data = null, $isAuth = false) {
        $headers = ["Content-Type: application/json"];
        if ($isAuth) {
            $auth      = base64_encode("{$this->clientId}:{$this->clientSecret}");
            $headers[] = "Authorization: Basic {$auth}";
        } else {
            $headers[] = "Authorization: Bearer {$this->accessToken}";
        }
        $ch = curl_init($this->apiUrl . $endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_CUSTOMREQUEST  => $method,
        ]);
        if ($method === "POST") {
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? json_encode($data) : $data);
        }
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    /**
     * Make an HTTP request to the PayPal API.
     *
     * @param string            $method   HTTP method (GET, POST, PUT, DELETE, etc.)
     * @param string            $endpoint API endpoint
     * @param array|string|null $data     Data to send with the request
     * @param bool              $isAuth   Whether to use basic authentication
     *
     * @return array Contains 'statusCode' and 'response' from the API
     * @throws \JsonException if JSON encoding fails
     */
    private function makeHttpRequest($method, $endpoint, $data = null, $isAuth = false): array {
        $headers = ["Content-Type: application/json"];
        if ($isAuth) {
            $auth      = base64_encode("{$this->clientId}:{$this->clientSecret}");
            $headers[] = "Authorization: Basic {$auth}";
        } else {
            $headers[] = "Authorization: Bearer {$this->accessToken}";
        }
        $ch = curl_init($this->apiUrl . $endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_CUSTOMREQUEST  => $method,
        ]);
        if ($method === "POST") {
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? json_encode($data, JSON_THROW_ON_ERROR) : $data);
        }
        $response   = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Lấy mã trạng thái HTTP
        curl_close($ch);
        return [
            'statusCode' => $statusCode,
            // Trả về status code
            'response'   => json_decode($response, true),
            // Trả về phản hồi đã giải mã
        ];
    }

    /**
     * Sends an HTTP request with an attached file.
     * This request is used for APIs that require file uploads.
     *
     * @param string $method   HTTP method (GET, POST, PUT, DELETE, etc.)
     * @param string $endpoint API endpoint
     * @param array  $data     Data to be sent
     * @param string $fileName Name of the file
     * @param string $filePath Path to the file
     * @param bool   $isAuth   Whether to include the Authorization header
     *
     * @return array Returns the response as an array
     *              - 'statusCode': HTTP status code
     *              - 'response': Decoded response
     *              - 'error': Error message (if any)
     * @throws \JsonException
     */
    private function makeHttpRequestWithFile($method, $endpoint, $data, $fileName, $filePath, $isAuth = false): array {

        Logs::create('[makeHttpRequestWithFile][data]: ' . json_encode($data, JSON_THROW_ON_ERROR));
        $client   = new Client([
            'base_uri' => $this->apiUrl,
            'timeout'  => 5.0,
        ]);

        Logs::create('[makeHttpRequestWithFile][filePath]: ' . $filePath);
        // Xác định loại MIME dựa vào phần mở rộng của file
        $headersFile = get_headers($filePath, 1);
        $mimeType    = $headersFile["Content-Type"] ?? 'application/octet-stream';
        Logs::create('[makeHttpRequestWithFile][$mimeType]: ' . $mimeType);
        // Headers


        // Chuẩn bị dữ liệu multipart
        $multipart = new MultipartStream([
            [
                'name'     => 'input',
                'contents' => json_encode($data, JSON_THROW_ON_ERROR),
                'headers'  => ['Content-Type' => 'application/json'],
            ],
            [
                'name'     => 'file1',
                'contents' => fopen($filePath, 'r'),
                'filename' => $fileName,
                'headers'  => ['Content-Type' => $mimeType],
            ],
        ]);

        $request = new Request($method, $endpoint, [
            'Authorization' => "Bearer {$this->accessToken}",
            'Content-Type'  => "multipart/related; boundary={$multipart->getBoundary()}",
        ], $multipart);

        try {
            $response = $client->send($request);
            return [
                'statusCode' => $response->getStatusCode(),
                'response'   => json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR),
            ];
        } catch (RequestException $e) {
            return [
                'statusCode' => $e->getResponse() ? $e->getResponse()->getStatusCode() : 500,
                'error'      => $e->getMessage(),
            ];
        }
    }

    /**
     * Sends an HTTP request and returns the HTTP status code.
     * This function supports sending JSON data and authentication headers.
     *
     * @param string            $method   HTTP method (e.g., GET, POST, PUT, DELETE)
     * @param string            $endpoint API endpoint to send the request to
     * @param array|string|null $data     Data to be sent in the request body (optional)
     * @param bool              $isAuth   Whether to include an Authorization header (default: false)
     *
     * @return int HTTP status code of the response
     */
    private function makeRequestReturnCode($method, $endpoint, $data = null, $isAuth = false) {
        $headers = ["Content-Type: application/json"];
        if ($isAuth) {
            $auth      = base64_encode("{$this->clientId}:{$this->clientSecret}");
            $headers[] = "Authorization: Basic {$auth}";
        } else {
            $headers[] = "Authorization: Bearer {$this->accessToken}";
        }
        $ch = curl_init($this->apiUrl . $endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_CUSTOMREQUEST  => $method,
        ]);
        if ($method === "POST") {
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? json_encode($data) : $data);
        }
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpCode;
    }

    /**
     * Retrieves a list of orders (invoices) from the API.
     *
     * @param int    $page           The page number for pagination (default: 1)
     * @param int    $page_size      The number of records per page (default: 20)
     * @param bool   $total_required Whether to include the total count of records (default: true)
     * @param string $fields         Specifies which fields to include in the response (default: "all")
     *
     * @return array The API response containing the list of orders
     */
    public function listOrder($page = 1, $page_size = 20, $total_required = true, $fields = "all"): array {
        $query = http_build_query(array_filter([
            "page"           => $page,
            "page_size"      => $page_size,
            'total_required' => $total_required,
            "fields"         => $fields,
        ]));
        return $this->makeRequest("GET", "/v1/invoicing/invoices?{$query}");
    }

    /**
     * Retrieves a list of disputes from the PayPal API.
     *
     * @param string|null $start_time              The start time for filtering disputes (optional, cannot be used with $disputed_transaction_id)
     * @param string|null $disputed_transaction_id The transaction ID to filter disputes by (optional, cannot be used with $start_time)
     * @param int         $page_size               The number of disputes to retrieve per page (default: 20)
     *
     * @return array The API response containing the list of disputes
     * @throws Exception If both $start_time and $disputed_transaction_id are provided
     *
     */
    public function listDispute($start_time = null, $disputed_transaction_id = null, $page_size = 20): array {
        // Kiểm tra điều kiện: Chỉ được chọn start_time hoặc disputed_transaction_id, không được chọn cả hai
        if ($start_time && $disputed_transaction_id) {
            throw new Exception("Chỉ có thể chọn start_time hoặc disputed_transaction_id, không thể chọn cả hai.");
        }
        // Tạo query string từ các tham số đầu vào
        $query = http_build_query(array_filter([
            "start_time"              => $start_time,
            "disputed_transaction_id" => $disputed_transaction_id,
            "page_size"               => $page_size,
        ]));
        // Gửi yêu cầu GET đến PayPal API
        return $this->makeRequest("GET", "/v1/customer/disputes?{$query}");
    }

    /**
     * Provide evidence for a dispute.
     * The payload should contain evidence information.
     * If return shipping address is provided, it will be added to the payload.
     *
     * @param string $dispute_id              The dispute ID
     * @param array  $payload                 The payload containing evidence information
     * @param array  $return_shipping_address The return shipping address (optional)
     *
     * @return array The response from PayPal API
     * @throws Exception If the dispute_id is empty
     */
    public function provideEvidence($dispute_id, $payload, $return_shipping_address = null): array {
        // Kiểm tra dispute_id hợp lệ
        if (empty($dispute_id)) {
            throw new Exception("Dispute ID is required.");
        }
        // Nếu có địa chỉ trả hàng, thêm vào payload
        if (!empty($return_shipping_address)) {
            $payload['return_shipping_address'] = $return_shipping_address;
        }
        // Gửi yêu cầu POST tới PayPal API
        return $this->makeHttpRequest("POST", "/v1/customer/disputes/{$dispute_id}/provide-evidence", $payload);
    }

    /**
     * Provide evidence for a dispute.
     * The payload should contain evidence information.
     * If return shipping address is provided, it will be added to the payload.
     *
     * @param string $dispute_id              The dispute ID
     * @param array  $payload                 The payload containing evidence information
     * @param array  $return_shipping_address The return shipping address (optional)
     *
     * @return array The response from PayPal API
     * @throws Exception If the dispute_id is empty
     */
    public function provideEvidenceWithFile($dispute_id, $payload, $fileInfo, $return_shipping_address = null): array {
        // Kiểm tra dispute_id hợp lệ
        if (empty($dispute_id)) {
            throw new Exception("Dispute ID is required.");
        }
        // Nếu có địa chỉ trả hàng, thêm vào payload
        if (!empty($return_shipping_address)) {
            $payload['return_shipping_address'] = $return_shipping_address;
        }
        $endPoint = "/v1/customer/disputes/{$dispute_id}/provide-evidence";
        echo '<pre>';
        print_r($fileInfo);
        die;
        // Gửi yêu cầu POST tới PayPal API
        return $this->makeHttpRequestWithFile("POST", $endPoint, $payload, $fileInfo['name'], $fileInfo['path']);
    }

    /**
     * Send a message about a dispute to the other party.
     * Determines whether the sender is the buyer or the seller based on the logged-in access token.
     * After send message auto change dispute status.
     *
     * @param string $dispute_id The dispute ID
     * @param string $message    The message content
     *
     * @return array The response from PayPal API
     * @throws Exception If the dispute_id or message is empty
     */
    public function sendDisputeMessage($dispute_id, $message): array {
        if (empty($dispute_id) || empty($message)) {
            throw new Exception("Dispute ID và nội dung tin nhắn là bắt buộc.");
        }
        $payload = [
            "message" => $message,
        ];
        return $this->makeRequest("POST", "/v1/customer/disputes/{$dispute_id}/send-message", $payload);
    }

    /**
     * Update the dispute status from UNDER_REVIEW to either WAITING_FOR_BUYER_RESPONSE or WAITING_FOR_SELLER_RESPONSE.
     *
     * @param string $dispute_id The dispute ID
     * @param string $action     The action to perform ('BUYER_EVIDENCE' or 'SELLER_EVIDENCE')
     *
     * @return array The response from PayPal API
     * @throws Exception If the input data is invalid or the API does not support the request.
     */
    public function updateDisputeStatus($dispute_id, $action) {
        if (empty($dispute_id) || !in_array($action, [
                'BUYER_EVIDENCE',
                'SELLER_EVIDENCE',
            ])) {
            throw new Exception("Invalid dispute ID or action. Allowed actions: BUYER_EVIDENCE, SELLER_EVIDENCE.");
        }
        $payload = ["action" => $action];
        return $this->makeRequest("POST", "/v1/customer/disputes/{$dispute_id}/require-evidence", $payload);
    }

    /**
     * Retrieve the details of a dispute by its ID.
     *
     * @param string $dispute_id The dispute ID
     *
     * @return array The response from PayPal API
     * @throws Exception If the dispute_id is empty or the request fails.
     */
    public function getDisputeDetails($dispute_id) {
        if (empty($dispute_id)) {
            throw new Exception("Dispute ID is required.");
        }
        return $this->makeRequest("GET", "/v1/customer/disputes/{$dispute_id}");
    }

    /**
     * Provides supporting information for a dispute.
     *
     * @param string $dispute_id The dispute ID.
     * @param string $notes      A note describing the supporting information.
     *
     * @return array The result from the PayPal API.
     * @throws Exception If the dispute ID or notes are empty, or if the API does not support the request.
     */
    public function provideSupportingInfo($dispute_id, $notes) {
        if (empty($dispute_id) || empty($notes)) {
            throw new Exception("Dispute ID and supporting notes are required.");
        }
        $disputeDetails = $this->getDisputeDetails($dispute_id);
        $allowedStates  = [
            "CHARGEBACK",
            "PRE_ARBITRATION",
            "ARBITRATION",
        ];
        if (!in_array($disputeDetails['dispute_life_cycle_stage'], $allowedStates)) {
            throw new Exception("Action not allowed in the current dispute state: " . $disputeDetails['dispute_life_cycle_stage']);
        }
        // Kiểm tra liên kết HATEOAS có "provide-supporting-info"
        $allowed = false;
        foreach ($disputeDetails['links'] as $link) {
            if ($link['rel'] === "provide-supporting-info") {
                $allowed = true;
                break;
            }
        }
        if (!$allowed) {
            throw new Exception("The dispute does not allow providing supporting information at this stage.");
        }
        $payload = ["notes" => $notes];
        return $this->makeRequest("POST", "/v1/customer/disputes/{$dispute_id}/provide-supporting-info", $payload);
    }

    /**
     * Make an offer to resolve a dispute.
     * Only allow when the dispute status is "INQUIRY".
     *
     * @param string      $dispute_id The dispute ID.
     * @param string      $offer_type The type of offer (REFUND, REFUND_WITH_RETURN, etc.).
     * @param string      $note       A note describing the offer.
     * @param float|null  $amount     The refund amount (required for some offer types).
     * @param string|null $currency   The currency code (e.g., USD, EUR).
     * @param string|null $invoice_id The optional invoice ID related to the refund.
     *
     * @return array The response from PayPal API.
     * @throws Exception If input data is invalid.
     */
    public function makeOfferToResolveDispute(string $dispute_id, string $offer_type, string $note, float $amount = null, string $currency = null, string $invoice_id = null, array $returnAddress = []): array {
        if (empty($dispute_id) || empty($offer_type) || empty($note)) {
            throw new Exception("Dispute ID, offer type, and note are required.");
        }
        $disputeDetails        = $this->getDisputeDetails($dispute_id);
        $disputeLifeCycleState = $disputeDetails['dispute_life_cycle_stage'] ?? null;
        if ($disputeLifeCycleState !== "INQUIRY") {
            flash()->error('You can only make an offer when the dispute is in INQUIRY state. Current state: {$disputeLifeCycleState}');
            throw new Exception("You can only make an offer when the dispute is in INQUIRY state. Current state: {$disputeLifeCycleState}");
        }
        if ($disputeDetails['dispute_state'] == "REQUIRED_OTHER_PARTY_ACTION") {
            flash()->error('You cannot make an offer to resolve a dispute. Need Seller response!');
            throw new Exception("You cannot make an offer to resolve a dispute. Need Buyer response!");
        }
        $allowedOfferTypes = [
            "REFUND",
            "REFUND_WITH_RETURN",
            "REFUND_WITH_REPLACEMENT",
            "REPLACEMENT_WITHOUT_REFUND",
        ];
        if (!in_array($offer_type, $allowedOfferTypes)) {
            flash()->error('Invalid offer type. Allowed values: ' . implode(", ", $allowedOfferTypes));
            throw new Exception("Invalid offer type. Allowed values: " . implode(", ", $allowedOfferTypes));
        }
        $payload = [
            "offer_type" => $offer_type,
            "note"       => $note,
        ];
        if (!empty($returnAddress)) {
            $payload['return_shipping_address'] = [
                'address_line_1' => $returnAddress['address_line_1'],
                'address_line_2' => $returnAddress['address_line_2'] ?? null,
                'address_line_3' => $returnAddress['address_line_3'] ?? null,
                'admin_area_4'   => $returnAddress['admin_area_4'] ?? null,
                'admin_area_3'   => $returnAddress['admin_area_3'] ?? null,
                'admin_area_2'   => $returnAddress['admin_area_2'] ?? null,
                'admin_area_1'   => $returnAddress['admin_area_1'] ?? null,
                'postal_code'    => $returnAddress['postal_code'] ?? null,
                'country_code'   => $returnAddress['country_code'],
            ];
        }
        if (!empty($amount) && !empty($currency)) {
            $disputeAmount = $disputeDetails['dispute_amount'];
            if ($amount <= 0) {
                flash()->error('Enter invalid refund amount.');
                throw new Exception("Enter invalid refund amount.");
            }
            if ($disputeAmount["currency_code"] === $currency) {
                if ($disputeAmount["value"] >= $amount) {
                    $payload["offer_amount"] = [
                        "value"         => (string) $amount,
                        "currency_code" => $currency,
                    ];
                } else {
                    flash()->error("The refund amount cannot exceed the transaction amount.");
                    throw new Exception("The refund amount cannot exceed the transaction amount.");
                }
            } else {
                flash()->error("The currency code does not match the dispute currency.");
                throw new Exception("Use the correct type of currency for this transaction.");
            }
        }
        if (!empty($invoice_id)) {
            $payload["invoice_id"] = $invoice_id;
        }
        return $this->makeRequest("POST", "/v1/customer/disputes/{$dispute_id}/make-offer", $payload);
    }

    /**
     * Acknowledge that the customer has returned an item for a dispute.
     *
     * @param string      $dispute_id The ID of the dispute.
     * @param string|null $note       Merchant-provided note (max 2000 characters).
     * @param array       $evidences  List of supporting evidence (max 100 items).
     *
     * @return array Response from PayPal API.
     * @throws Exception If invalid data is provided or the dispute type is not eligible.
     */
    public function acknowledgeReturnedItem(string $dispute_id, string $note = null, array $evidences = []): array {
        if (empty($dispute_id)) {
            throw new Exception("Dispute ID are required.");
        }
        $disputeDetails = $this->getDisputeDetails($dispute_id);
        if ($disputeDetails['reason'] !== "MERCHANDISE_OR_SERVICE_NOT_AS_DESCRIBED") {
            flash()->error('Acknowledging item return is only allowed for disputes with reason "MERCHANDISE_OR_SERVICE_NOT_AS_DESCRIBED".');
            throw new Exception("Acknowledging item return is only allowed for disputes with reason 'MERCHANDISE_OR_SERVICE_NOT_AS_DESCRIBED'.");
        }
        if ($disputeDetails['dispute_state'] == "REQUIRED_OTHER_PARTY_ACTION") {
            flash()->error('You cannot make an offer to resolve a dispute. Need Seller response!');
            throw new Exception("You cannot make an offer to resolve a dispute. Need Buyer response!");
        }
        if (!empty($note)) {
            $payload["note"] = $note;
        }
        if (!empty($evidences)) {
            $allowedEvidenceTypes = [
                "PROOF_OF_DAMAGE",
                "THIRDPARTY_PROOF_FOR_DAMAGE_OR_SIGNIFICANT_DIFFERENCE",
                "DECLARATION",
                "PROOF_OF_MISSING_ITEMS",
                "PROOF_OF_EMPTY_PACKAGE_OR_DIFFERENT_ITEM",
                "PROOF_OF_ITEM_NOT_RECEIVED",
            ];
            $formattedEvidences   = [];
            foreach ($evidences as $evidence) {
                if (empty($evidence['evidence_type']) || empty($evidence['documents'])) {
                    throw new Exception("Each evidence entry must include 'evidence_type' and 'documents'.");
                }
                if (!in_array($evidence['evidence_type'], $allowedEvidenceTypes, true)) {
                    throw new Exception("Invalid evidence type: {$evidence['evidence_type']}");
                }
                foreach ($evidence['documents'] as $doc) {
                    if (empty($doc['name']) || empty($doc['url'])) {
                        throw new Exception("Each document must include 'name' and 'url'.");
                    }
                }
                $formattedEvidences[] = [
                    "evidence_type" => $evidence['evidence_type'],
                    "documents"     => $evidence['documents'],
                ];
            }
            $payload["evidences"] = $formattedEvidences;
        } else {
            $payload["acknowledgement_type"] = "ITEM_RECEIVED";
        }
        return $this->makeRequest("POST", "/v1/customer/disputes/{$dispute_id}/acknowledge-return-item", $payload);
    }

    /**
     * @throws Exception
     */
    public function escalate($dispute_id, $note = ''): array {
        $payload = [
            'dispute_id' => $dispute_id,
            'note'       => $note,
        ];
        return $this->makeRequest("POST", "/v1/customer/disputes/{$dispute_id}/escalate", $payload);
    }

    /**
     * Accepts a claim for a given dispute ID.
     *
     * @param string      $dispute_id              The ID of the dispute to accept the claim for.
     * @param string      $note                    A note about the claim acceptance.
     * @param string|null $accept_claim_reason     The reason for accepting the claim (optional).
     * @param string|null $accept_claim_type       The type of refund proposed by the merchant (optional).
     * @param array|null  $refund_amount           The refund amount details if applicable (optional).
     * @param string|null $invoice_id              The invoice ID related to the refund (optional).
     * @param array|null  $return_shipment_info    Shipment details if applicable (optional).
     * @param array|null  $return_shipping_address The return address details if applicable (optional).
     *
     * @return array The response from PayPal API.
     * @throws Exception If the request fails.
     */
    public function acceptClaim(string $dispute_id, string $note, ?string $accept_claim_reason = null, ?string $accept_claim_type = null, ?array $refund_amount = null, ?string $invoice_id = null, ?array $return_shipment_info = null, ?array $return_shipping_address = null): array {
        if (!empty($refund_amount)) {
            $disputeDetails = $this->getDisputeDetails($dispute_id);
            $disputeAmount  = $disputeDetails['dispute_amount'];
            if ($refund_amount['value'] <= 0) {
                flash()->error('Enter invalid refund amount.');
                throw new Exception("Enter invalid refund amount.");
            }
            if ($disputeAmount["currency_code"] === $refund_amount['currency_code']) {
                if ($disputeAmount["value"] < $refund_amount['value']) {
                    flash()->error("The refund amount cannot exceed the transaction amount.");
                    throw new Exception("The refund amount cannot exceed the transaction amount.");
                }
            } else {
                flash()->error("The currency code does not match the dispute currency.");
                throw new Exception("Use the correct type of currency for this transaction.");
            }
        }
        $payload = array_filter([
            'note'                    => $note,
            'accept_claim_reason'     => $accept_claim_reason,
            'accept_claim_type'       => $accept_claim_type,
            'refund_amount'           => $refund_amount,
            'invoice_id'              => $invoice_id,
            'return_shipment_info'    => $return_shipment_info,
            'return_shipping_address' => $return_shipping_address,
        ]);
        return $this->makeRequest("POST", "/v1/customer/disputes/{$dispute_id}/accept-claim", $payload);
    }

    /**
     * Get tracking info.
     *
     * @param string      $transactionId
     * @param string|null $trackingNumber
     * @param string|null $accountId
     *
     * @return array
     * @throws Exception
     */
    public function getTrackingInfo(string $transactionId, ?string $trackingNumber = null, ?string $accountId = null): array|null {
        $endpoint    = "/v1/shipping/trackers";
        $params      = [
            "transaction_id"  => $transactionId,
            "tracking_number" => $trackingNumber,
            "account_id"      => $accountId,
        ];
        $queryString = http_build_query(array_filter($params, fn($v) => $v !== null));
        $url         = $endpoint . '?' . $queryString;
        return $this->makeRequest('GET', $url);
    }

    /**
     * Adds tracking information for a specific transaction.
     *
     * @param string $transaction_id The PayPal transaction ID to associate with the tracking info.
     * @param string $status         The status of the shipment (e.g., "SHIPPED", "DELIVERED").
     * @param string $trackingNumber The tracking number provided by the carrier.
     * @param string $carrier        The name of the shipping carrier.
     *
     * @return int|array|RedirectResponse
     *         - HTTP status code if the request is successful.
     *         - API response as an array if applicable.
     *         - RedirectResponse if tracking number or carrier is missing, with an error message.
     */
    public function addTrackingInfo(string $transaction_id, string $status, string $trackingNumber, string $carrier): int|array|RedirectResponse {
        $url = "/v1/shipping/trackers";
        if (empty($trackingNumber) || empty($carrier)) {
            flash()->error("Tracking number or tracking carrier is required.");
            return redirect()->route("app.tracking.index");
        }
        $data = [
            "trackers" => [
                [
                    "transaction_id"     => $transaction_id,
                    "tracking_number"    => $trackingNumber,
                    "status"             => $status,
                    "carrier"            => "OTHER",
                    "carrier_name_other" => $carrier,
                ],
            ],
        ];
        return $this->makeRequestReturnCode('POST', $url, $data);
    }

    /**
     * @throws \JsonException
     */
    public function issueRefund($capture_id): array {
        $url    = "/v2/payments/captures/{$capture_id}/refund";
        $params = [
            "amount"        => [
                "currency_code" => "USD",
                "value"         => "1.00",
            ],
            "invoice_id"    => "INV-12345",
            "note_to_payer" => "Refund for order #12345",
        ];
        return $this->makeHttpRequest('POST', $url,$params);
    }
}
