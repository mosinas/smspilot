<?php

namespace Mosinas\SmsPilot;

use GuzzleHttp\Client as GuzzleClient;
use Mosinas\SmsPilot\Exceptions\SmsPilotException;

class SmsPilot
{
    /** @var $gateway GuzzleClient */
    protected $gateway;

    /**
     *
     * @var
     */
    protected $test;

    /**
     * @var
     */
    protected $cost;

    protected $apiKey;

    protected $apiUrl;

    protected $senderName;


    protected $useWebhook;

    public function __construct()
    {
        if(config('smspilot') && config('smspilot.format') != 'json') {
            throw new SmsPilotException('Incorrect config format smspilot.format');
        }

        $this->test = false;
        $this->cost = false;

        $this->apiKey = config('smspilot.apiKey');
        $this->apiUrl = config('smspilot.apiUrl');
        $this->senderName = config('smspilot.senderName');
        $this->withWebhook  = config('smspilot.withWebhook');
        $this->webhookUrl = config('smspilot.webhookUrl');
        $this->webhookMethod = config('smspilot.webhookMethod');

        $this->gateway = new GuzzleClient([
             'timeout'         => 5,
             'allow_redirects' => false,
        ]);
    }

    /**
     *  Setting the test method of work
     *
     * @param $mode
     */
    public function setTestMode($mode) : void
    {
        $this->test = $mode;
    }

    /**
     * Include calculation of the cost of the shipment
     *
     * @param $mode
     */
    public function setCalculateMode($mode) : void
    {
        $this->cost = $mode;
    }

    /**
     * Get balance service
     *
     * @return float
     * @throws SmsPilotException
     */
    public function getBalance() : float
    {
        $params = [
            'apikey' => $this->apiKey,
            'balance' => 'rur',
        ];

        $response = $this->post($params);

        return floatval($response['balance']);
    }

    /**
     * Check balance is greater than zero
     *
     * @return bool
     */
    protected function checkBalance() : bool
    {
        try {
            $balance = $this->getBalance();
            if($balance <= 0.0)
                return false;

            return true;

        } catch (SmsPilotException $e) {
            return false;
        }
    }

    /**
     * Send message
     *
     * @param $messages
     * @param int $sendDatetime
     * @param bool $saveMsg
     * @throws SmsPilotException
     */
    public function sendMessage($messages, int $sendDatetime = 0, $saveMsg = false)
    {
        $params = [
            'apikey' => $this->apiKey,
            'cost' => $this->cost ? 1 : 0,
            'test' => $this->test ? 1 : 0,
            'send' => [],
        ];

        foreach ($messages as $message) {
            $msg = $message->transform($saveMsg);

            if($sendDatetime) {
                $msg['send_datetime'] = $sendDatetime;
            }

            if($this->useWebhook) {
                $msg['callback'] = $this->webhookUrl;
                $msg['callback_method'] = $this->webhookMethod;
            }

            $params['send'][] = $msg;
        }

        return $this->post($params);
    }

    /**
     *  Wrapping gateway
     *
     * @param array $params
     * @return array
     * @throws SmsPilotException
     */
    private function post(array $params) : array
    {
        $body = json_encode($params);
        $response = $this->gateway->request('post', $this->apiUrl, ['body' => $body]);

        if($response->getStatusCode() != 200 ) {
            $msgError = SmsPilot::class. ': Failed to get service balance';
            throw new SmsPilotException($msgError);
        }

        $response = json_decode($response->getBody()->getContents(), true);

        if(isset($response['error'])) {
            $msgError = SmsPilot::class. ':' . $response['error']['description'];
            throw new SmsPilotException($msgError, $response['error']['code']);
        }

        return $response;
    }

}