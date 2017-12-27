<?php

return  [

    /**
     * Sender name
     */
    'senderName' => env('SMSPILOT_SENDERNAME', ''),

    /**
     * API address server
     */
    'apiUrl' => env('SMSPILOT_URL', 'http://smspilot.ru/api2.php'),

    /**
     * API key service.
     * Get your API key in personal cabinet https://smspilot.ru/my-settings.php#api
     */
    'apiKey' => env('SMSPILOT_APIKEY', ''),

    /**
     * Format request/response
     */
    'format' => 'json',

    /**
     * Use Webhook to get the status of messages
     */
    'withWebhook' => env('SMSPILOT_WITHWEBHOOK', false),

    /**
     *  Url callback
     */
    'webhookUrl' => env('SMSPILOT_WEBHOOK', 'http://yourdomain.com/sms-status/'),

    /**
     * Method callback request
     */
    'webhookMethod' => 'post',



];