<?php
/**
 * @project gdt-cashier
 * @author hoepjhsha
 * @email hiepnguyen3624@gmail.com
 * @date 24/01/2025
 * @time 21:27
 */

namespace App\Services\TrackingMore;

trait Request
{
    /**
     * @var string
     */
    private string $apiBaseUrl = 'api.trackingmore.com';

    /**
     * @var int
     */
    private int $apiPort = 443;

    /**
     * @var string
     */
    private string $apiVersion = 'v4';

    /**
     * @var string
     */
    private string $apiPath;

    /**
     * @var string
     */
    private string $headerKey = 'Tracking-Api-Key';

    /**
     * @var string
     */
    private string $apiKey;

    /**
     * @var string
     */
    private string $url;

    /**
     * @var array|false|string
     */
    private array|false|string $params = [];

    /**
     * @var array
     */
    private array $header = [];

    /**
     * @var int
     */
    private int $timeout = 60;

    /**
     * @var boolean
     */
    private bool $isHeader = false;

    /**
     * @throws TrackingMoreException
     */
    public function __construct()
    {
        $apiKey = config('services.trackingmore.key');
        if (empty($apiKey)) {
            throw new TrackingMoreException(ErrorMessages::ErrEmptyAPIKey);
        }
        $this->setApiKey($apiKey);
    }

    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     */
    private function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * gets the header to be used for requests.
     *
     * @return array $header.
     */
    private function getRequestHeader(): array
    {
        return [
            'Accept: application/json',
            'Content-Type: application/json',
            $this->headerKey . ': ' . $this->apiKey,
        ];
    }

    /**
     * get the BaseUrl
     *
     * @param string $path
     * @return string The complete url.
     */
    private function getBaseUrl(string $path = ''): string
    {
        $port = $this->apiPort === 443 ? 'https' : 'http';
        $this->apiModule = $this->apiModule ? $this->apiModule . '/' : '';
        return $port . '://' . $this->apiBaseUrl . '/' . $this->apiVersion . '/' . $this->apiModule  . $path;
    }

    /**
     * send api request.
     *
     * @param array|null $params
     * @param string $method
     * @return mixed
     * @throws TrackingMoreException
     */
    public function sendApiRequest(array|null $params = [], string $method = 'GET'): mixed
    {
        $this->url = $this->getBaseUrl($this->apiPath);
        $this->params = $params;
        $this->header = $this->getRequestHeader();
        return $this->send($method);
    }

    /**
     * send api request.
     *
     * @param string $method
     * @return mixed $response.
     * @throws TrackingMoreException
     */
    private function send(string $method): mixed
    {
        $method = strtoupper($method);
        if (!empty($this->params)) $this->params = json_encode($this->params);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        switch ($method) {
            case 'GET' :
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                break;
            case 'POST' :
                curl_setopt($curl, CURLOPT_POST, true);
                break;
            default :
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
                break;
        }
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, $this->isHeader);
        if (!empty($this->params)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params);
            $this->header[] = 'Content-Length: ' . strlen($this->params);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        if ($err) {
            curl_close($curl);
            throw new TrackingMoreException("failed to request: $err");
        }
        curl_close($curl);
        return json_decode($response, true);
    }
}
