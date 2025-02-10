<?php
namespace App\Paygate;

use App\Models\Paygate;
use Exception;
use Illuminate\Http\RedirectResponse;
use InvalidArgumentException;

class PayPalAPI
{

    private $clientId, $clientSecret, $apiUrl, $accessToken;

    /**
     * Khởi tạo PayPalAPI.
     *
     * @param $paygate
     *
     * @throws Exception
     */
    public function __construct(Paygate $paygate)
    {
        $api_data = json_decode($paygate->api_data, true) ?? [];
        $this->clientId = $api_data['client_key'] ?? '';
        $this->clientSecret = $api_data['secret_key'] ?? '';
        $this->apiUrl = $paygate->mode === 0 ? "https://api-m.sandbox.paypal.com" : "https://api-m.paypal.com";
        $this->accessToken = $this->getAccessToken();
    }

    /**
     * Lấy Access Token từ PayPal API.
     *
     * @return string
     * @throws Exception
     */
    private function getAccessToken()
    {
        $response = $this->makeRequest("POST", "/v1/oauth2/token", "grant_type=client_credentials", true);
        return $response['access_token'] ?? throw new Exception("Không thể lấy Access Token.");
    }

    /**
     * Tạo một thanh toán mới.
     *
     * @param float $amount
     * @param string $currency
     * @param string $returnUrl
     * @param string $cancelUrl
     *
     * @return array
     */
    public function createPayment($amount, $currency, $returnUrl, $cancelUrl)
    {
        return $this->makeRequest("POST", "/v1/payments/payment", [
            "intent" => "sale",
            "payer" => ["payment_method" => "paypal"],
            "transactions" => [
                [
                    "amount" => [
                        "total" => $amount,
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
     * Lấy chi tiết của một thanh toán.
     *
     * @param string $paymentId
     *
     * @return array
     */
    public function getPaymentDetails($paymentId)
    {
        return $this->makeRequest("GET", "/v1/payments/payment/{$paymentId}");
    }

    /**
     * Hoàn tiền cho một thanh toán.
     *
     * @param string $saleId
     * @param float $amount
     * @param string $currency
     *
     * @return array
     */
    public function refundPayment($saleId, $amount, $currency)
    {
        return $this->makeRequest("POST", "/v1/payments/sale/{$saleId}/refund", [
            "amount" => [
                "total" => $amount,
                "currency" => $currency,
            ],
        ]);
    }

    /**
     * Liệt kê các giao dịch trong khoảng thời gian nhất định.
     *
     * @param string $startDate
     * @param string $endDate
     * @param string|null $transactionId
     * @param string $fields
     *
     * @return array
     */
    public function listTransaction($startDate, $endDate, $transactionId = null, $fields = "all")
    {
        $query = http_build_query(array_filter([
            "start_date" => $startDate,
            "end_date" => $endDate,
            "fields" => $fields,
            "transaction_id" => $transactionId,
        ]));
        return $this->makeRequest("GET", "/v1/reporting/transactions?{$query}");
    }

    public function getTransactionDetail()
    {

    }

    /**
     * Thực hiện yêu cầu HTTP tới PayPal API.
     *
     * @param string $method
     * @param string $endpoint
     * @param array|string $data
     * @param bool $isAuth
     *
     * @return array
     * @throws Exception
     */
    private function makeRequest($method, $endpoint, $data = null, $isAuth = false)
    {
        $headers = ["Content-Type: application/json"];
        if ($isAuth) {
            $auth = base64_encode("{$this->clientId}:{$this->clientSecret}");
            $headers[] = "Authorization: Basic {$auth}";
        } else {
            $headers[] = "Authorization: Bearer {$this->accessToken}";
        }
        $ch = curl_init($this->apiUrl . $endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => $method,
        ]);
        if ($method === "POST") {
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? json_encode($data) : $data);
        }
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    private function makeRequestReturnCode($method, $endpoint, $data = null, $isAuth = false)
    {
        $headers = ["Content-Type: application/json"];
        if ($isAuth) {
            $auth = base64_encode("{$this->clientId}:{$this->clientSecret}");
            $headers[] = "Authorization: Basic {$auth}";
        } else {
            $headers[] = "Authorization: Bearer {$this->accessToken}";
        }
        $ch = curl_init($this->apiUrl . $endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => $method,
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
     * Liệt kê đơn hàng với các tùy chọn phân trang.
     *
     * @param int $page
     * @param int $page_size
     * @param bool $total_required
     * @param string $fields
     *
     * @return array
     */
    public function listOrder($page = 1, $page_size = 20, $total_required = true, $fields = "all")
    {
        $query = http_build_query(array_filter([
            "page" => $page,
            "page_size" => $page_size,
            'total_required' => $total_required,
            "fields" => $fields,
        ]));
        return $this->makeRequest("GET", "/v1/invoicing/invoices?{$query}");
    }

    /**
     * Lấy danh sách các giao dịch tranh chấp của người dùng.
     * Có thể lọc theo thời gian tranh chấp (start_time) hoặc theo mã giao dịch tranh chấp (disputed_transaction_id).
     * Không được chọn cả hai tham số này.
     *
     * @param string|null $start_time Thời gian tranh chấp (YYYY-MM-DDTHH:MM:SSZ)
     * @param string|null $disputed_transaction_id Mã giao dịch tranh chấp
     * @param int $page_size Số lượng bản ghi trên mỗi trang
     *
     * @return array Danh sách các giao dịch tranh chấp
     * @throws Exception Nếu chọn cả hai tham số start_time và disputed_transaction_id
     */
    public function listDispute($start_time = null, $disputed_transaction_id = null, $page_size = 20)
    {
        // Kiểm tra điều kiện: Chỉ được chọn start_time hoặc disputed_transaction_id, không được chọn cả hai
        if ($start_time && $disputed_transaction_id) {
            throw new Exception("Chỉ có thể chọn start_time hoặc disputed_transaction_id, không thể chọn cả hai.");
        }
        // Tạo query string từ các tham số đầu vào
        $query = http_build_query(array_filter([
            "start_time" => $start_time,
            "disputed_transaction_id" => $disputed_transaction_id,
            "page_size" => $page_size,
        ]));
        // Gửi yêu cầu GET đến PayPal API
        return $this->makeRequest("GET", "/v1/customer/disputes?{$query}");
    }

    public function provideEvidence($dispute_id, $evidences, $return_shipping_address = null): array
    {
        // Kiểm tra dispute_id hợp lệ
        if (empty($dispute_id)) {
            throw new Exception("Dispute ID is required.");
        }
        // Tạo payload JSON theo tài liệu PayPal
        $payload = [
            'evidences' => $evidences,
        ];
        // Nếu có địa chỉ trả hàng, thêm vào payload
        if (!empty($return_shipping_address)) {
            $payload['return_shipping_address'] = $return_shipping_address;
        }
        // Gửi yêu cầu POST tới PayPal API
        return $this->makeRequest("POST", "/v1/customer/disputes/{$dispute_id}/provide-evidence", $payload);
    }

    /**
     * Send a message about a dispute to the other party.
     * Determines whether the sender is the buyer or the seller based on the logged-in access token.
     * After send message auto change dispute status.
     *
     * @param string $dispute_id The dispute ID
     * @param string $message The message content
     *
     * @return array The response from PayPal API
     * @throws Exception If the dispute_id or message is empty
     */
    public function sendDisputeMessage($dispute_id, $message)
    {
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
     * @param string $action The action to perform ('BUYER_EVIDENCE' or 'SELLER_EVIDENCE')
     *
     * @return array The response from PayPal API
     * @throws Exception If the input data is invalid or the API does not support the request.
     */
    public function updateDisputeStatus($dispute_id, $action)
    {
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
    public function getDisputeDetails($dispute_id)
    {
        if (empty($dispute_id)) {
            throw new Exception("Dispute ID is required.");
        }
        return $this->makeRequest("GET", "/v1/customer/disputes/{$dispute_id}");
    }

    /**
     * Provides supporting information for a dispute.
     *
     * @param string $dispute_id The dispute ID.
     * @param string $notes A note describing the supporting information.
     *
     * @return array The result from the PayPal API.
     * @throws Exception If the dispute ID or notes are empty, or if the API does not support the request.
     */
    public function provideSupportingInfo($dispute_id, $notes)
    {
        if (empty($dispute_id) || empty($notes)) {
            throw new Exception("Dispute ID and supporting notes are required.");
        }
        $disputeDetails = $this->getDisputeDetails($dispute_id);
        $allowedStates = [
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
     * @param string $dispute_id The dispute ID.
     * @param string $offer_type The type of offer (REFUND, REFUND_WITH_RETURN, etc.).
     * @param string $note A note describing the offer.
     * @param float|null $amount The refund amount (required for some offer types).
     * @param string|null $currency The currency code (e.g., USD, EUR).
     * @param string|null $invoice_id The optional invoice ID related to the refund.
     *
     * @return array The response from PayPal API.
     * @throws Exception If input data is invalid.
     */
    public function makeOfferToResolveDispute(string $dispute_id, string $offer_type, string $note, float $amount = null, string $currency = null, string $invoice_id = null, array $returnAddress = []): array
    {
        if (empty($dispute_id) || empty($offer_type) || empty($note)) {
            throw new Exception("Dispute ID, offer type, and note are required.");
        }
        $disputeDetails = $this->getDisputeDetails($dispute_id);
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
            "note" => $note,
        ];
        if (!empty($returnAddress)) {
            $payload['return_shipping_address'] = [
                'address_line_1' => $returnAddress['address_line_1'],
                'address_line_2' => $returnAddress['address_line_2'] ?? null,
                'address_line_3' => $returnAddress['address_line_3'] ?? null,
                'admin_area_4' => $returnAddress['admin_area_4'] ?? null,
                'admin_area_3' => $returnAddress['admin_area_3'] ?? null,
                'admin_area_2' => $returnAddress['admin_area_2'] ?? null,
                'admin_area_1' => $returnAddress['admin_area_1'] ?? null,
                'postal_code' => $returnAddress['postal_code'] ?? null,
                'country_code' => $returnAddress['country_code'],
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
                        "value" => (string)$amount,
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
     * @param string $dispute_id The ID of the dispute.
     * @param string $note Merchant-provided note (max 2000 characters).
     * @param array $evidences List of supporting evidence (max 100 items).
     *
     * @return array Response from PayPal API.
     * @throws Exception If invalid data is provided or the dispute type is not eligible.
     */
    public function acknowledgeReturnedItem(string $dispute_id, string $note, array $evidences = []): array
    {
        if (empty($dispute_id)) {
            throw new Exception("Dispute ID are required.");
        }
        $disputeDetails = $this->getDisputeDetails($dispute_id);
        if ($disputeDetails['reason'] !== "MERCHANDISE_OR_SERVICE_NOT_AS_DESCRIBED") {
            throw new Exception("Acknowledging item return is only allowed for disputes with reason 'MERCHANDISE_OR_SERVICE_NOT_AS_DESCRIBED'.");
        }
        if (!empty($note)) {
            $payload["note"] = substr($note, 0, 2000);
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
            $formattedEvidences = [];
            foreach (array_slice($evidences, 0, 100) as $evidence) {
                if (!isset($evidence['evidence_type'], $evidence['documents'])) {
                    throw new Exception("Each evidence entry must include 'evidence_type' and 'documents'.");
                }
                if (!in_array($evidence['evidence_type'], $allowedEvidenceTypes, true)) {
                    throw new Exception("Invalid evidence type: {$evidence['evidence_type']}");
                }
                $documents = array_slice($evidence['documents'], 0, 100);
                foreach ($documents as $doc) {
                    if (!isset($doc['name'], $doc['url'])) {
                        throw new Exception("Each document must include 'name' and 'url'.");
                    }
                }
                $formattedEvidences[] = [
                    "evidence_type" => $evidence['evidence_type'],
                    "documents" => $documents,
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
    public function escalate($dispute_id, $note = ''): array
    {
        $payload = [
            'dispute_id' => $dispute_id,
            'note' => $note,
        ];
        return $this->makeRequest("POST", "/v1/customer/disputes/{$dispute_id}/escalate", $payload);
    }

    /**
     * @param        $dispute_id
     * @param string $note
     *
     * @return array
     * @throws Exception
     */
    public function acceptClaim($dispute_id, string $note): array
    {
        $payload = [
            'note' => $note,
        ];
        return $this->makeRequest("POST", "/v1/customer/disputes/{$dispute_id}/accept-claim", $payload);
    }

    /**
     * Get tracking info.
     *
     * @param string $transactionId
     * @param string|null $trackingNumber
     * @param string|null $accountId
     * @return array
     * @throws Exception
     */
    public function getTrackingInfo(string $transactionId, ?string $trackingNumber = null, ?string $accountId = null): array
    {
        $endpoint = "/v1/shipping/trackers";

        $params = [
            "transaction_id" => $transactionId,
            "tracking_number" => $trackingNumber,
            "account_id" => $accountId
        ];

        $queryString = http_build_query(array_filter($params, fn($v) => $v !== null));
        $url = $endpoint . '?' . $queryString;

        return $this->makeRequest('GET', $url);
    }

    /**
     * Action to manage tracking info for transaction.
     *
     * action: 'add', 'update', 'cancel'
     *
     * @throws Exception
     */
    public function addTrackingInfo(
        string $transaction_id,
        string $status,
        string $trackingNumber,
        string $carrier
    ): int|array|RedirectResponse
    {
        $url = "/v1/shipping/trackers";

        if (empty($trackingNumber) || empty($carrier)) {
            flash()->error("Tracking number or tracking carrier is required.");
            return redirect()->route("app.tracking.index");
        }
        $data = [
            "trackers" => [[
                "transaction_id" => $transaction_id,
                "tracking_number" => $trackingNumber,
                "status" => $status,
                "carrier" => "OTHER",
                "carrier_name_other" => $carrier,
            ]]
        ];

        return $this->makeRequestReturnCode('POST', $url, $data);
    }
}


