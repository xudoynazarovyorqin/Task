<?php

// set WebHook https://api.telegram.org/bot1384021091:AAFoCqZol5dDSev8XcvXE3wMNLfkdt6b-5g/setWebhook?url=https://mudofabot.goodone.uz/telegram/1384021091:AAFoCqZol5dDSev8XcvXE3wMNLfkdt6b-5g/webhook
// get Info https://api.telegram.org/bot1384021091:AAFoCqZol5dDSev8XcvXE3wMNLfkdt6b-5g/getWebhookInfo

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use App\botUser;
Route::get('/', function () {

    // $res = Telegram::setWebhook([
    //     'url' => 'https://mudofabot.goodone.uz/1216457008:AAHeOtrqaEpe-vGn6ddCn5C0CtjkGFfRP_U/webhook'
    // ]);
    dd('dwadw');
});
Route::get('/webhook/info', function () {
    $updates = \Telegram::handleGetUpdates();
    dd($updates);
});

Route::post('/1384021091:AAFoCqZol5dDSev8XcvXE3wMNLfkdt6b-5g/webhook', function () {
    $output_actions_main = "Выберите";
    $output_view_balance = "Просмотр баланс";
    $output_view_personal_data = "Просмотр личный данные";
    $output_back_to_main = "Главный";
    $output_enter_console_number = "Введите пультовой номер";
    $output_application_not_found_enter_again = "Не найдена заявка. Повторно введите";
    $output_confirm_console_number = "Подтвердить пультовой номер";
    $output_confirm_console_number_contract_number = "Введите номер договора чтобы подтвердить";
    $output_confirm_console_number_contract_number_no = "Неверный номер договора!!!";
    $output_confirm_console_number_no = "Повторно ввести пультовой номер";
    $output_enter_again_console_number = "Повторно ввести пультовой номер";
    $output_success_save_console_number = "Успешно сохранен пультовой номер";

    $updates = [\Telegram::getWebhookUpdates()];

    foreach ($updates as $update) {
        $fromChatId = $update->getMessage()->getChat()->getId();
        $receivedMessage = $update->getMessage()->getText();

        $chat_id_data_before = DB::table('telegram_bot_storages')->where('chat_id', $fromChatId)->first();
        if( $chat_id_data_before ) {
            // return 1;
            $chat_id_update = DB::table('telegram_bot_storages')
                ->where('chat_id', $fromChatId)
                ->update(['last_input' => $receivedMessage]);

            $chat_id_data_after = DB::table('telegram_bot_storages')->where('chat_id', $fromChatId)->first();
        }
        else {
            // return 1
            $chat_id_create = DB::table('telegram_bot_storages')->insert(
                ['chat_id' => $fromChatId, 'last_input' => $receivedMessage]
            );

            $chat_id_data_after = DB::table('telegram_bot_storages')->where('chat_id', $fromChatId)->first();
        }

        // /start ni bosganda
        if( $receivedMessage == "/start" || $receivedMessage == $output_back_to_main ){
            if( $chat_id_data_after->console_number ) {

                $application = DB::table('applications')->where('console_number', $chat_id_data_after->console_number)->first();
                $text = "";
                if( $application ) {
//                    $client = DB::table('clients')->find($application->client_id);
//                    $contract_client = DB::table('contract_clients')->find($application->contract_client_id);
//
//                    $client_name = ($client) ? strval($client->name) : "";
//                    $client_phone = ($client) ? strval($client->phone) : "";
//                    $contract_client_number_date = ($contract_client) ? "№ " . strval($contract_client->number) . " от " . date("d.m.Y", strtotime($contract_client->begin_date)) : "";

//                    $text .= "Ваши данные\n";
//                    $text .= "Пультовой номер: <b>" . $chat_id_data_after->console_number . "</b>\n";
//                    $text .= "Ф.И.О: <b>" . $client_name . "</b>\n";
//                    $text .= "Телефон: <b>" . $client_phone . "</b>\n";
//                    $text .= "Договор: <b>" . $contract_client_number_date . "</b>";

                    $btn = Keyboard::button([
                        'text' => $output_view_balance,
                    ]);
                    $btn2 = Keyboard::button([
                        'text' => $output_view_personal_data,
                    ]);
                    $btn3 = Keyboard::button([
                        'text' => $output_enter_again_console_number,
                    ]);
                    $keyboard = Keyboard::make([
                        'keyboard' => [[$btn],[$btn2],[$btn3]],
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true,
                    ]);
                    $chat_id_update = DB::table('telegram_bot_storages')
                        ->where('chat_id', $fromChatId)
                        ->update(['last_output' => $output_actions_main]);

                    Telegram::sendMessage([
                        'reply_markup' => $keyboard,
                        'chat_id' => $fromChatId,
                        'text' => $output_actions_main,
                        'parse_mode'=>'html',
                    ]);
                }
                else {
                    $chat_id_update = DB::table('telegram_bot_storages')
                        ->where('chat_id', $fromChatId)
                        ->update(['last_output' => $output_application_not_found_enter_again, 'console_number' => null ]);

                    Telegram::sendMessage([
                        'chat_id' => $fromChatId,
                        'text' => $output_application_not_found_enter_again,
                    ]);
                }
            }
            else {
                $chat_id_update = DB::table('telegram_bot_storages')
                    ->where('chat_id', $fromChatId)
                    ->update(['last_output' => $output_enter_console_number]);

                Telegram::sendMessage([
                    'chat_id' => $fromChatId,
                    'text' => $output_enter_console_number,
                ]);
            }
        }

        // povtorna kiritishni bosganda $output_confirm_console_number_no
        else if( $receivedMessage == $output_confirm_console_number_no || $receivedMessage == $output_enter_again_console_number ) {
            $chat_id_update = DB::table('telegram_bot_storages')
                ->where('chat_id', $fromChatId)
                ->update(['last_output' => $output_enter_console_number, 'console_number' => null]);

            Telegram::sendMessage([
                'chat_id' => $fromChatId,
                'text' => $output_enter_console_number,
            ]);
        }

        // Pultovoy nomer kiritsh soralganda
        else if( $chat_id_data_before && !$chat_id_data_before->console_number
            && ($chat_id_data_before->last_output == $output_enter_console_number
                || $chat_id_data_before->last_output == $output_application_not_found_enter_again) ) {

            $application = DB::table('applications')->where('console_number', $receivedMessage)->first();
            $text = "";
            if( $application ) {
                $client = DB::table('clients')->find($application->client_id);
                //$contract_client = DB::table('contract_clients')->find($application->contract_client_id);

                $client_name = ($client) ? strval($client->name) : "";
                $client_phone = ($client) ? strval($client->phone) : "";
                //$contract_client_number_date = ($contract_client) ? "№ " . strval($contract_client->number) . " от " . date("d.m.Y", strtotime($contract_client->begin_date)) : "";

                $text .= "Ваши данные\n";
                $text .= "Ф.И.О: <b>" . $client_name . "</b>\n";
                $text .= "Телефон: <b>" . $client_phone . "</b>\n\n";
                $text .= $output_confirm_console_number_contract_number;
                //$text .= "Договор: " . $contract_client_number_date;

                $btn = Keyboard::button([
                    'text' => $output_confirm_console_number_no,
                ]);

                $keyboard = Keyboard::make([
                    'keyboard' => [[$btn]],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ]);
                $chat_id_update = DB::table('telegram_bot_storages')
                    ->where('chat_id', $fromChatId)
                    ->update(['last_output' => $output_confirm_console_number_contract_number]);

                Telegram::sendMessage([
                    'reply_markup' => $keyboard,
                    'chat_id' => $fromChatId,
                    'text' => $text,
                    'parse_mode'=>'html',
                ]);
            }
            else {
                $chat_id_update = DB::table('telegram_bot_storages')
                    ->where('chat_id', $fromChatId)
                    ->update(['last_output' => $output_application_not_found_enter_again]);

                Telegram::sendMessage([
                    'chat_id' => $fromChatId,
                    'text' => $output_application_not_found_enter_again,
                ]);

            }
        }

        // Pultovoy nomer bilan dogovor nomerni tekshirish
        else if( $chat_id_data_before && !$chat_id_data_before->console_number && $chat_id_data_before->last_output == $output_confirm_console_number_contract_number ) {
            $contract_client = DB::table('contract_clients')->where('number', $receivedMessage)->first();
            $application = DB::table('applications')->where('console_number', $chat_id_data_before->last_input)->first();

            // solishtirish togri bolsa uspeshna boladi
            if( $contract_client && $application && ($contract_client->id == $application->contract_client_id) ) {
                // undan oldingi kiritilgan pult nomerni yozish
                $chat_id_update = DB::table('telegram_bot_storages')
                    ->where('chat_id', $fromChatId)
                    ->update(['console_number' => $chat_id_data_before->last_input]);


                /*** Kanalga xabar yuborish ***/
                $registered_users = DB::table('telegram_bot_storages')
                    ->select('console_number', DB::raw('count(*) as total'))
                    ->whereNotNull('console_number')
                    ->groupBy('console_number')
                    ->get();
                $registered_users_count = count($registered_users);

                $client = DB::table('clients')->find($application->client_id);
                $contract_client = DB::table('contract_clients')->find($application->contract_client_id);
                $district = DB::table('districts')->find($application->district_id);
                $quarter = DB::table('quarters')->find($application->quarter_id);

                $client_name = ($client) ? strval($client->name) : "";
                $client_phone = ($client) ? strval($client->phone) : "";
                $contract_client_number_date = ($contract_client) ? "№ " . strval($contract_client->number) . " от " . date("d.m.Y", strtotime($contract_client->begin_date)) . " г." : "";
                $contract_client_conclusion_date = ($contract_client) ? date("d.m.Y", strtotime($contract_client->conclusion_date)) : "";
                $district_name = ($district) ? strval($district->name) : "";
                $quarter_name = ($quarter) ? strval($quarter->name) : "";
                $first_name = $update->getMessage()->getChat()->getFirstName();
                $username = "@" . $update->getMessage()->getChat()->getUsername();

                $text = "";
                $text .= "Зарегистрировал нового пользователя. № <b>" . $registered_users_count . "</b>\n";
                $text .= "Имя: <b>" . $first_name . "</b>\n";
                $text .= "Имя пользователя: <b>" . $username . "</b>\n";
                $text .= "Пультовой номер: <b>" . $application->console_number . "</b>\n";
                $text .= "Ф.И.О: <b>" . $client_name . "</b>\n";
                $text .= "Телефон: <b>" . $client_phone . "</b>\n";
                $text .= "Договор: <b>" . $contract_client_number_date . "</b>\n";
                $text .= "Дата заключения: <b>" . $contract_client_conclusion_date . "</b>\n";
                $text .= "Ежемесячная сумма: <b>" . number_format(floatval($application->amount), 2, ".", " ") . "</b>\n";
                $text .= "Наименование объекта: <b>" . $application->object_name . "</b>\n";
                $text .= "Район: <b>" . $district_name . "</b>\n";
                $text .= "Квартал: <b>" . $quarter_name . "</b>\n";
                $text .= "Улица: <b>" . $application->object_street . "</b>\n";
                $text .= "Дом: <b>" . $application->object_home . "</b>\n";
                $text .= "Корпус: <b>" . $application->object_corps . "</b>\n";
                $text .= "Квартира: <b>" . $application->object_flat . "</b>\n";




                // boshqa bot @MGQBKanalBot
                $botToken="1342419773:AAFam_9aTSoXNqCiwXRvS3vM7-ZzZkFEm4w";

                $website="https://api.telegram.org/bot".$botToken;
                $chatId='-1001407669198';
                $params=[
                    'chat_id'=>$chatId,
                    'text'=>$text,
                    'parse_mode'=>'html',
                ];
                $ch = curl_init($website . '/sendMessage');
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $result = curl_exec($ch);
                curl_close($ch);
                /**** End Kanalga xabar yuborish ****/


                // balansni korish button ni yuborish
                $btn = Keyboard::button([
                    'text' => $output_view_balance,
                ]);
                $btn1 = Keyboard::button([
                    'text' => $output_view_personal_data,
                ]);
                $keyboard = Keyboard::make([
                    'keyboard' => [[$btn],[$btn1]],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ]);
                $chat_id_update = DB::table('telegram_bot_storages')
                    ->where('chat_id', $fromChatId)
                    ->update(['last_output' => $output_actions_main]);

                Telegram::sendMessage([
                    'reply_markup' => $keyboard,
                    'chat_id' => $fromChatId,
                    'text' => $output_success_save_console_number,
                ]);
            }
            // podverdit togri kemadi boshqattan boshlash
            else {
                $btn = Keyboard::button([
                    'text' => $output_enter_again_console_number,
                ]);
                $keyboard = Keyboard::make([
                    'keyboard' => [[$btn]],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ]);
                $chat_id_update = DB::table('telegram_bot_storages')
                    ->where('chat_id', $fromChatId)
                    ->update(['last_output' => $output_enter_again_console_number, 'console_number' => null]);

                Telegram::sendMessage([
                    'reply_markup' => $keyboard,
                    'chat_id' => $fromChatId,
                    'text' => $output_confirm_console_number_contract_number_no,
                ]);
            }
        }

        // ozini danniylarini korishni bosganda $output_view_personal_data
        else if( $receivedMessage == $output_view_personal_data ) {
            // agar pult nomeri bor va pult nomeriga mos zayavka boladigan bolsa
            $application = DB::table('applications')->where('console_number', $chat_id_data_before->console_number)->first();
            $text = "";
            if( $chat_id_data_before->console_number && $application ) {
                $client = DB::table('clients')->find($application->client_id);
                $contract_client = DB::table('contract_clients')->find($application->contract_client_id);
                $district = DB::table('districts')->find($application->district_id);
                $quarter = DB::table('quarters')->find($application->quarter_id);

                $client_name = ($client) ? strval($client->name) : "";
                $client_phone = ($client) ? strval($client->phone) : "";
                $contract_client_number_date = ($contract_client) ? "№ " . strval($contract_client->number) . " от " . date("d.m.Y", strtotime($contract_client->begin_date)) . " г." : "";
                $contract_client_conclusion_date = ($contract_client) ? date("d.m.Y", strtotime($contract_client->conclusion_date)) : "";
                $district_name = ($district) ? strval($district->name) : "";
                $quarter_name = ($quarter) ? strval($quarter->name) : "";

                $text .= "Ваши данные\n";
                $text .= "Пультовой номер: <b>" . $chat_id_data_after->console_number . "</b>\n";
                $text .= "Ф.И.О: <b>" . $client_name . "</b>\n";
                $text .= "Телефон: <b>" . $client_phone . "</b>\n";
                $text .= "Договор: <b>" . $contract_client_number_date . "</b>\n";
                $text .= "Дата заключения: <b>" . $contract_client_conclusion_date . "</b>\n";
                $text .= "Ежемесячная сумма: <b>" . number_format(floatval($application->amount), 2, ".", " ") . "</b>\n";
                $text .= "Наименование объекта: <b>" . $application->object_name . "</b>\n";
                $text .= "Район: <b>" . $district_name . "</b>\n";
                $text .= "Квартал: <b>" . $quarter_name . "</b>\n";
                $text .= "Улица: <b>" . $application->object_street . "</b>\n";
                $text .= "Дом: <b>" . $application->object_home . "</b>\n";
                $text .= "Корпус: <b>" . $application->object_corps . "</b>\n";
                $text .= "Квартира: <b>" . $application->object_flat . "</b>\n";

                $btn = Keyboard::button([
                    'text' => $output_back_to_main,
                ]);
                $keyboard = Keyboard::make([
                    'keyboard' => [[$btn]],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ]);
                $chat_id_update = DB::table('telegram_bot_storages')
                    ->where('chat_id', $fromChatId)
                    ->update(['last_output' => $output_view_personal_data]);

                Telegram::sendMessage([
                    'reply_markup' => $keyboard,
                    'chat_id' => $fromChatId,
                    'text' => $text,
                    'parse_mode'=>'html',
                ]);
            }
            else {
                $chat_id_update = DB::table('telegram_bot_storages')
                    ->where('chat_id', $fromChatId)
                    ->update(['last_output' => $output_enter_console_number, 'console_number' => null]);

                Telegram::sendMessage([
                    'chat_id' => $fromChatId,
                    'text' => $output_enter_console_number,
                ]);
            }
        }

        // balansni korishni bosganda $output_view_balance
        else if( $receivedMessage == $output_view_balance ) {
            // agar pult nomeri bor va pult nomeriga mos zayavka boladigan bolsa
            $application = DB::table('applications')->where('console_number', $chat_id_data_before->console_number)->first();
            $text = "";
            if( $chat_id_data_before->console_number && $application ) {
                $contract_client = DB::table('contract_clients')->find($application->contract_client_id);

                $total_amount = DB::table('application_parts')
                    ->where('application_id', $application->id)
                    ->sum('amount');
                $total_paid = DB::table('application_parts')
                    ->where('application_id', $application->id)
                    ->sum('paid');
                $total_not_paid = $total_amount - $total_paid;

                $contract_client_remainder = ($contract_client) ? floatval($contract_client->remainder) : 0;

                $text .= "Суммы\n";
                $text .= "Общая сумма: <b>" . number_format($total_amount, 2, ".", " ") . "</b>\n";
                $text .= "Оплачено: <b>" . number_format($total_paid, 2, ".", " ") . "</b>\n";
                $text .= "Не оплачено: <b>" . number_format($total_not_paid, 2, ".", " ") . "</b>\n";
                $text .= "Баланс в договоре: <b>" . number_format($contract_client_remainder, 2, ".", " ") . "</b>";

                $btn = Keyboard::button([
                    'text' => $output_back_to_main,
                ]);
                $keyboard = Keyboard::make([
                    'keyboard' => [[$btn]],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ]);
                $chat_id_update = DB::table('telegram_bot_storages')
                    ->where('chat_id', $fromChatId)
                    ->update(['last_output' => $output_view_balance]);

                Telegram::sendMessage([
                    'reply_markup' => $keyboard,
                    'chat_id' => $fromChatId,
                    'text' => $text,
                    'parse_mode'=>'html',
                ]);
            }
            else {
                $chat_id_update = DB::table('telegram_bot_storages')
                    ->where('chat_id', $fromChatId)
                    ->update(['last_output' => $output_enter_console_number, 'console_number' => null]);

                Telegram::sendMessage([
                    'chat_id' => $fromChatId,
                    'text' => $output_enter_console_number,
                ]);
            }
        }
    }

    return 'ok';
});

Route::post('send/message/aboutTopUpBalance',function(Request $r){
    $request_body  = file_get_contents('php://input');
    $request = json_decode($request_body, true);
    $secret_key = '168fd4ed98f55c0e116cd2608f82cc23';
    $time = $request['time'];

    if (md5($time .$secret_key .$request['message']) != $request['access_token'])
        return response()->json(['code'=> -2, 'message' => 'error access_token'],201);

    $chat_ids = DB::table('telegram_bot_storages')->where('console_number', $request['console_number'])->get();

    foreach($chat_ids as $chat_id) {
        Telegram::sendMessage([
            'chat_id' => $chat_id->chat_id,
            'parse_mode' => 'HTML',
            'text' => $request['message'],
        ]);
    }
    return response()->json(['code' => 0, 'message' => 'ok'],200);

});
