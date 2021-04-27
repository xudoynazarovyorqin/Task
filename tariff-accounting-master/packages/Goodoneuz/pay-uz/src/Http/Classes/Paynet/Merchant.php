<?php

namespace Goodoneuz\PayUz\Http\Classes\Paynet;

use Goodoneuz\PayUz\Http\Classes\DataFormat;
use Illuminate\Support\Facades\Log;

class Merchant
{
    public $config;
    public $request;

    public function __construct($config, $request, $response)
    {
        $this->config = $config;
        $this->request = $request;
        $this->response = $response;
    }

    public function Authorize()
    {


        if ($this->config['login'] != $this->request->params['account']['login'] || $this->config['password'] != $this->request->params['account']['password'] || $this->config['password'] != $this->request->params['account']['password']) {
            $body = '';
            switch ($this->request->params['method']) {
                case Request::METHOD_CheckTransaction:
                    $body = Response::makeResponse("<ns2:CheckTransactionResult xmlns:ns2=\"http://uws.provider.com/\">".
                        "<errorMsg>Недостаточно прав для выполнения этого метода</errorMsg>".
                        "<status>412</status>".
                        "</ns2:CheckTransactionResult>");
                    break;
                case Request::METHOD_PerformTransaction:
                    $body = Response::makeResponse("<ns2:PerformTransactionResult xmlns:ns2=\"http://uws.provider.com/\">".
                        "<errorMsg>Недостаточно прав для выполнения этого метода</errorMsg>".
                        "<status>412</status>".
                        "</ns2:PerformTransactionResult>");
                    break;
                case Request::METHOD_CancelTransaction:
                    $body = Response::makeResponse("<ns2:CancelTransactionResult xmlns:ns2=\"http://uws.provider.com/\">".
                        "<errorMsg>Недостаточно прав для выполнения этого метода</errorMsg>".
                        "<status>412</status>".
                        "</ns2:CancelTransactionResult>");
                    break;
                case Request::METHOD_GetStatement:
                    $body = Response::makeResponse("<ns2:GetStatementResult xmlns:ns2=\"http://uws.provider.com/\">".
                        "<errorMsg>Недостаточно прав для выполнения этого метода</errorMsg>".
                        "<status>412</status>".
                        "</ns2:GetStatementResult>");
                    break;
                case Request::METHOD_GetInformation:
                    $body = Response::makeResponse("<ns2:GetInformationResult xmlns:ns2=\"http://uws.provider.com/\">".
                        "<errorMsg>Недостаточно прав для выполнения этого метода</errorMsg>".
                        "<status>412</status>".
                        "</ns2:GetInformationResult>");
                    break;
            }

            //$this->response->response($this->request, 'Insufficient privilege to perform this method.', Response::ERROR_INSUFFICIENT_PRIVILEGE);
            $this->response->response($this->request, $body, Response::ERROR_INSUFFICIENT_PRIVILEGE);
        }
        return true;
    }
}
