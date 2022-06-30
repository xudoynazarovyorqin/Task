<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Iodev\Whois\Exceptions\ServerMismatchException;
use Iodev\Whois\Exceptions\WhoisException;
use Iodev\Whois\Factory;

class DomainsController extends Controller
{
    public function index()
    {
        //get array urls
        $urls = \request('url');

        try {
            $whois = Factory::get()->createWhois();
            $data = [];
            foreach ($urls as $key => $url) {

                $info = $whois->loadDomainInfo($url);
                if (!$info) {
                    $data[$key] = [
                        'url'    => $url,
                        'status' => 'free',
                        'info'   => 'Domain empty'
                    ];
                } else
                    $data[$key] = [
                        'url'    => $url,
                        'status' => 'busy',
                        'info'   => $info->domainName . " expires at: " . date("d.m.Y H:i:s", $info->expirationDate)
                    ];
            }
            return $data;
        } catch (ConnectionException $e) {
            return "Disconnect or connection timeout";
        } catch (ServerMismatchException $e) {
            return "TLD server (.com for google.com) not found in current server hosts";
        } catch (WhoisException $e) {
            return "Whois server responded with error '{$e->getMessage()}'";
        }
    }
}
