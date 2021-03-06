<?php namespace Usulix\NetSuite\Services;

class ConfigApiService
{

    protected $booConfigOk;
    protected $logger;
    protected $arrTokenFields = [
        'NETSUITE_RESTLET_HOST',
        'NETSUITE_ACCOUNT',
        'NETSUITE_CONSUMER_KEY',
        'NETSUITE_CONSUMER_SECRET',
        'NETSUITE_TOKEN',
        'NETSUITE_TOKEN_SECRET'
    ];
    protected $arrNlAuthFields = [
        'NETSUITE_RESTLET_HOST',
        'NETSUITE_ACCOUNT',
        'NETSUITE_EMAIL',
        'NETSUITE_PASSWORD',
        'NETSUITE_ROLE'
    ];

    public function __construct($log)
    {
        $this->booConfigOk = false;
        $this->logger = $log;

        if ($this->checkFields('arrTokenFields') || $this->checkFields('arrNlAuthFields')) {
            $this->booConfigOk = true;
        }

        return $this->booConfigOk;
    }

    public function checkFields($strType)
    {
        foreach ($this->$strType as $strField) {
            if (!env($strField)) {
                return false;
            }
        }
        return true;
    }

    public function getConfig()
    {
        if (!$this->booConfigOk) {
            $this->logger->info('Config settings for netsuite service in .env appear to be incomplete');

            return false;
        }
        if (env('NETSUITE_PASSWORD')) {
            $arrConfig = [
                'host' => env('NETSUITE_RESTLET_HOST'),
                'account' => env('NETSUITE_ACCOUNT'),
                'email' => env('NETSUITE_EMAIL'),
                'password' => env('NETSUITE_PASSWORD'),
                'role' => env('NETSUITE_ROLE'),
            ];
        } else {

            $arrConfig = [
                'host' => env('NETSUITE_RESTLET_HOST'),
                'account' => env('NETSUITE_ACCOUNT'),
                'consumerKey' => env('NETSUITE_CONSUMER_KEY'),
                'consumerSecret' => env('NETSUITE_CONSUMER_SECRET'),
                'token' => env('NETSUITE_TOKEN'),
                'tokenSecret' => env('NETSUITE_TOKEN_SECRET')
            ];
            if (env('NETSUITE_SIGNATURE_ALGORITHM')) {
                $arrConfig['signatureAlgorithm'] = env('NETSUITE_SIGNATURE_ALGORITHM');
            } else {
                $arrConfig['signatureAlgorithm'] = 'HMAC-SHA256';
            }
        }
        if (env('NETSUITE_LOGGING')) {
            $arrConfig['logging'] = env('NETSUITE_LOGGING');
        }
        if (env('NETSUITE_LOG_PATH')) {
            $arrConfig['log_path'] = env('NETSUITE_LOG_PATH');
        }
        return $arrConfig;
    }
}
