<?php


namespace App\Http\Controllers\Api\Mudofa;


use App\Http\Controllers\Controller;
use Telegram\Bot\Api;

class BotController extends Controller
{
    public function index() {
        $telegram = new Api('1216457008:AAHeOtrqaEpe-vGn6ddCn5C0CtjkGFfRP_U');

        $response = $telegram->getMe();

        $botId = $response->getId();
        $firstName = $response->getFirstName();
        $username = $response->getUsername();
    }

}
