<?php
// set WebHook https://api.telegram.org/bot1216457008:AAHeOtrqaEpe-vGn6ddCn5C0CtjkGFfRP_U/setWebhook?url=https://mudofabot.goodone.uz/index.php
// set WebHook https://api.telegram.org/bot1216457008:AAHeOtrqaEpe-vGn6ddCn5C0CtjkGFfRP_U/setWebhook?url=https://mudofabot.goodone.uz/index.php
// get Info https://api.telegram.org/bot1216457008:AAHeOtrqaEpe-vGn6ddCn5C0CtjkGFfRP_U/getWebhookInfo

define('API_KEY','1216457008:AAHeOtrqaEpe-vGn6ddCn5C0CtjkGFfRP_U');
define('DATABASE_USERNAME','gomax');
define('DATABASE_PASSWORD','gomax_erp_!!!');
define('DATABASE_NAME','mudofa');
define('DATABASE_PORT','5432');

//$conn_string = "host=149.154.71.209 port=". DATABASE_PORT . " dbname=" . DATABASE_NAME . " user=" . DATABASE_USERNAME . " password=" . DATABASE_PASSWORD;
//$dbconn = pg_connect($conn_string);

function bot($method,$datas=array()){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

$update = json_decode(file_get_contents('php://input'));

$message = $update->message;
$mid = $message->message_id;
$text = $message->text;
$chat_id = $message->chat->id;
//$chat_id = 159628621;
$username = $message->from->username;
$first_name = $message->from->first_name;
$last_name = $message->from->last_name;
$text = $message->text;
$fromid = $message->from->id;
$data=$update->callback_query->data;
$call=$update->callback_query;
$callid=$update->callback_query->id;
$replyto=$message->reply_to_message->from;
$chat=$message->chat;
$mid = $update->callback_query->message->message_id;
$cbuser = $call->from->username;

$lastId = (int)$text;

if( $text == "/start" )
{
//    $conn_string = "host=149.154.71.209 port=". DATABASE_PORT . " dbname=" . DATABASE_NAME . " user=" . DATABASE_USERNAME . " password=" . DATABASE_PASSWORD;
//    $dbconn4 = pg_connect($conn_string);
//    $table_name = array("applications");
//    $selectfields = array("id" => "1000");
//    $records = pg_select($dbconn4, $table_name, $selectfields);
//    $records = json_encode($records);

    $conn_string = "host=149.154.71.209 port=". DATABASE_PORT . " dbname=" . DATABASE_NAME . " user=" . DATABASE_USERNAME . " password=" . DATABASE_PASSWORD;
    $dbconn = pg_connect($conn_string);

    bot('sendMessage',array('chat_id'=>$chat_id,
        //'text'=>$records,
        'text'=>$conn_string,
        'disable_web_page_preview'=>true,
        'parse_mode'=>'html'
    ));
}

if ( is_integer($lastId) && $lastId > 0 )
{
    $db = new mysqli("149.154.71.209","dms","6Z2m1N1g","dms_base");
    $db->query("SET NAMES utf8");
    // oldin tablitsani tozalash
    //$db->query("TRUNCATE TABLE `SetPhoto`");
    $result = $db->query("DELETE FROM `SetPhoto` WHERE chat_id='".$chat_id."'");
    $haveOrder = $db->query("SELECT id, image FROM `OrderSecond` WHERE id='".$lastId."'");
    $have = $haveOrder->fetch_array(MYSQLI_ASSOC);
    $order_id = $have['id'];
    $have_image = $have['image'];

    if($order_id)
    {
        // agar rasm joylashtirilib bolgan bolsa
        if( $have_image != '' )
        {
            bot('sendMessage',array(
                'chat_id'=>$chat_id,
                'text'=>$order_id . ' ga rasm yuklanib bo\'lingan. Qayta yuklashning iloji yo\'q.',
                'disable_web_page_preview'=>true,
                'parse_mode'=>'html'
            ));

            die();
        }

        $db->query("INSERT INTO SetPhoto(id, order_id, chat_id) VALUES (null," . $lastId . "," . $chat_id . ")");

        bot('sendMessage',array(
            'chat_id'=>$chat_id,
            'text'=>'Rasmni yuboring',
            'disable_web_page_preview'=>true,
            'parse_mode'=>'html'
        ));
    }
    else
    {
        $haveOrder = $db->query("SELECT coupon_id, image FROM `OrderSecond` WHERE coupon_id='".$lastId."'");
        $have = $haveOrder->fetch_array(MYSQLI_ASSOC);
        $coupon = $have['coupon_id'];
        $have_image = $have['image'];

        if( $coupon )
        {
            // agar rasm joylashtirilib bolgan bolsa
            if( $have_image != '' )
            {
                bot('sendMessage',array(
                    'chat_id'=>$chat_id,
                    'text'=>$coupon . ' ga rasm yuklanib bo\'lingan. Qayta rasm yuklashning iloji yo\'q.',
                    'disable_web_page_preview'=>true,
                    'parse_mode'=>'html'
                ));

                die();
            }

            $db->query("INSERT INTO SetPhoto(id, order_id, is_coupon, chat_id) VALUES (null," . $lastId . ", 1," . $chat_id . " )");

            bot('sendMessage',array(
                'chat_id'=>$chat_id,
                'text'=>'Rasmni yuboring.',
                'disable_web_page_preview'=>true,
                'parse_mode'=>'html'
            ));
        }
        else
        {
            bot('sendMessage',array(
                'chat_id'=>$chat_id,
                'text'=>'Bunday Id yoki Kupon nomeri mavjud emas. Qayta kiriting.',
                'disable_web_page_preview'=>true,
                'parse_mode'=>'html'
            ));
        }

    }
}
else
{
    if( isset($message->photo) )
    {
        //1-order_id ni olish
        $db = new mysqli("149.154.71.209","dms","6Z2m1N1g","dms_base");
        $result = $db->query("SELECT order_id, is_coupon FROM `SetPhoto` WHERE chat_id='".$chat_id."'");
        $ids = $result->fetch_array(MYSQLI_ASSOC);
        $order_id = $ids['order_id'];
        $is_coupon = $ids['is_coupon'];

        if( !$order_id )
        {
            bot('sendMessage',array(
                'chat_id'=>$chat_id,
                'text'=>'Avval Id yoki Kupon nomerini kiriting.',
                'disable_web_page_preview'=>true,
                'parse_mode'=>'html'
            ));
            die();
        }

        // rasmni file_id sini olish
        $lastPhotoFileId = $message->photo[2]->file_id;
        $ch = curl_init('https://api.telegram.org/bot' . API_KEY . '/getFile');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('file_id' => $lastPhotoFileId));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($res, true);

        if ($res['ok'])
        {
            $src  = 'https://api.telegram.org/file/bot' . API_KEY . '/' . $res['result']['file_path'];

            // ramsni files/photo digan papkaga upload qilish
            //$download = file_put_contents("../files/photo/". $order_id ."_photo.jpg", file_get_contents($src));

            $data=array(
                'id_coupon' => $order_id,
                'src' => $src,
            );

            if( $curl = curl_init() ) {
                curl_setopt($curl, CURLOPT_URL, 'https://dms.bek771.fvds.ru/bot/response.php?order_id=' . $order_id . '&src=' . $src);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                $result = curl_exec($curl);
                curl_close($curl);

                if( $result == 'yes' )
                {
                    // Orderga rasmini prikrepit qilish
                    $image = $order_id . "_photo.jpg";
                    $db = new mysqli("149.154.71.209","dms","6Z2m1N1g","dms_base");

                    // id bilan prikripit qilish
                    if( !$is_coupon )
                    {
                        $photoSucces1 = $db->query("UPDATE `OrderSecond` SET image='".$image."' WHERE id='".$order_id."'");
                        $photoSucces2 = $db->query("UPDATE `Order` SET image='".$image."' WHERE id='".$order_id."'");
                    }

                    // coupon bilan prikripit qilish
                    else
                    {
                        $photoSucces1 = $db->query("UPDATE `OrderSecond` SET image='".$image."' WHERE coupon_id='".$order_id."'");
                        $photoSucces2 = $db->query("UPDATE `Order` SET image='".$image."' WHERE coupon_id='".$order_id."'");
                    }

                    // tablitsani tozalash
                    //$db->query("TRUNCATE TABLE `SetPhoto`");
                    $result = $db->query("DELETE FROM `SetPhoto` WHERE chat_id='".$chat_id."'");

                    bot('sendMessage',array(
                        'chat_id'=>$chat_id,
                        'text'=>$order_id . ' ga rasm joylashdi.',
                        //'text'=>$photoSucces1 .'|'.$photoSucces2,
                        'disable_web_page_preview'=>true,
                        'parse_mode'=>'html'
                    ));
                }
                else
                {
                    bot('sendMessage',array(
                        'chat_id'=>$chat_id,
                        'text'=>'Rasm joylashda xatolik!!!',
                        'disable_web_page_preview'=>true,
                        'parse_mode'=>'html'
                    ));
                }
            }
        }
    }
    else if($text != "/start")
    {
        bot('sendMessage',array(
            'chat_id'=>$chat_id,
            'text'=>'Avval Id yoki Kupon nomerini kiriting.',
            'disable_web_page_preview'=>true,
            'parse_mode'=>'html'
        ));
    }

}


?>
