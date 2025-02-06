<?php
namespace App\Paygate;

use Exception;
use InvalidArgumentException;

class PayPalAPI {

    private $clientId, $clientSecret, $apiUrl, $accessToken;

    /**
     * Khởi tạo PayPalAPI.
     *
     * @throws Exception
     */
    public function __construct($clientId, $clientSecret, $isSandbox = true) {
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->apiUrl       = $isSandbox ? "https://api-m.sandbox.paypal.com" : "https://api-m.paypal.com";
        $this->accessToken  = $this->getAccessToken();
    }

    /**
     * Lấy Access Token từ PayPal API.
     *
     * @return string
     * @throws Exception
     */
    private function getAccessToken() {
        $response = $this->makeRequest("POST", "/v1/oauth2/token", "grant_type=client_credentials", true);
        return $response['access_token'] ?? throw new Exception("Không thể lấy Access Token.");
    }

    /**
     * Tạo một thanh toán mới.
     *
     * @param float  $amount
     * @param string $currency
     * @param string $returnUrl
     * @param string $cancelUrl
     * @return array
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
     * Lấy chi tiết của một thanh toán.
     *
     * @param string $paymentId
     * @return array
     */
    public function getPaymentDetails($paymentId) {
        return $this->makeRequest("GET", "/v1/payments/payment/{$paymentId}");
    }

    /**
     * Hoàn tiền cho một thanh toán.
     *
     * @param string $saleId
     * @param float  $amount
     * @param string $currency
     * @return array
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
     * Liệt kê các giao dịch trong khoảng thời gian nhất định.
     *
     * @param string      $startDate
     * @param string      $endDate
     * @param string|null $transactionId
     * @param string      $fields
     * @return array
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

    /**
     * Thực hiện yêu cầu HTTP tới PayPal API.
     *
     * @param string       $method
     * @param string       $endpoint
     * @param array|string $data
     * @param bool         $isAuth
     * @return array
     * @throws Exception
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
     * Liệt kê đơn hàng với các tùy chọn phân trang.
     *
     * @param int    $page
     * @param int    $page_size
     * @param bool   $total_required
     * @param string $fields
     * @return array
     */
    public function listOrder($page = 1, $page_size = 20, $total_required = true, $fields = "all") {
        $query = http_build_query(array_filter([
            "page"           => $page,
            "page_size"      => $page_size,
            'total_required' => $total_required,
            "fields"         => $fields,
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
     * @return array Danh sách các giao dịch tranh chấp
     * @throws Exception Nếu chọn cả hai tham số start_time và disputed_transaction_id
     */
    public function listDispute($start_time = null, $disputed_transaction_id = null, $page_size = 20) {
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

    public function provideEvidence($dispute_id, $evidences, $return_shipping_address = null): array {
        // Kiểm tra dispute_id hợp lệ
        if (empty($dispute_id)) {
            throw new Exception("Dispute ID is required.");
        }

        // Tạo payload JSON theo tài liệu PayPal
        $payload = [
            'evidences' => $evidences
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
     * @return array The response from PayPal API
     * @throws Exception If the dispute_id or message is empty
     */
    public function sendDisputeMessage($dispute_id, $message) {
        if (empty($dispute_id) || empty($message)) {
            throw new Exception("Dispute ID và nội dung tin nhắn là bắt buộc.");
        }

        $payload = [
            "message" => $message
        ];

        return $this->makeRequest("POST", "/v1/customer/disputes/{$dispute_id}/send-message", $payload);
    }

    /**
     * Update the dispute status from UNDER_REVIEW to either WAITING_FOR_BUYER_RESPONSE or WAITING_FOR_SELLER_RESPONSE.
     *
     * @param string $dispute_id The dispute ID
     * @param string $action The action to perform ('BUYER_EVIDENCE' or 'SELLER_EVIDENCE')
     * @return array The response from PayPal API
     * @throws Exception If the input data is invalid or the API does not support the request.
     */
    public function updateDisputeStatus($dispute_id, $action) {
        if (empty($dispute_id) || !in_array($action, ['BUYER_EVIDENCE', 'SELLER_EVIDENCE'])) {
            throw new Exception("Invalid dispute ID or action. Allowed actions: BUYER_EVIDENCE, SELLER_EVIDENCE.");
        }

        $payload = ["action" => $action];

        return $this->makeRequest("POST", "/v1/customer/disputes/{$dispute_id}/require-evidence", $payload);
    }

}

?>
