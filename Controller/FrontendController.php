<?php
/**
 * This file is part of OXID Maxpay module.
 */
namespace Maxpay\MaxpayModule\Controller;

/**
 * Main Maxpay Frontend controller
 */
class FrontendController extends MaxpayFrontendController
{
    public function __construct(): null {
        parent::__construct();
        $this->logger->setTitle('Postback request');
    }
    
    /**
     * Postback request processing.
     * @return void
     * @throws \Exception
     */
    public function postback(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit('Incorrect request type');
        }
        
        $headers = $this->getHeaders();
        $dataJson = file_get_contents('php://input');
        
        if ($dataJson && $headers) {
            $this->maxpayService->processPostback($dataJson, $headers);
        }
    }
    
    /**
     * Retrieve headers from request.
     * @return array
     */
    private function getHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}
