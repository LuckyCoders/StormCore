<?php

/** Turn on using telegram API in system */
define('Telegram_API','on');

/** You must fill developer telegram chat id (for Log function and system notifications) */
define('dev_chat_tg',NULL);

/** Your bot telegram token (from BotFather) */
define('telegram_token','***************');


function send_Telegram($chat, $text, $ending = NULL, $buttons = NULL, $markdown = "Markdown", $get_msid = "off", $disable_notification = NULL)
{
    $token = telegram_token;
    $text = urlencode($text);
    if(($markdown == "true") OR ($markdown === true) OR ($markdown == NULL)) $markdown = "Markdown";
    if($buttons == NULL) $api_req = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat&text=$text"; else {
        $buttons = json_encode($buttons);
        $api_req = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat&text=$text&reply_markup=$buttons";
    }
    $api_req .= "&parse_mode=$markdown";
    if($disable_notification) $api_req .= "&disable_notification=true";


    $s = file_get_contents($api_req);
    if(!$s) {
        $er = "Ошибка при отправке сообщения через API телеграма";
        in_log($api_req);
    }
    if($ending) exit("ok");
    return ($s ? ($get_msid == 'off' ? true : $s) : false);
}

function edit_Telegram_Message($chat, $message_id, $text, $ending = NULL, $buttons = NULL)
{
    global $user;
    if(isset($user->lang) AND $user->lang != "RU"){
        $text = translate($text,"RU",$user->lang);
        $buttons = json_encode($buttons);
        $buttons = preg_replace_callback('|\"text\"\:\"(.+)\"\,\"|isU', function($matches){
            global $user;
            $s = json_decode('{"a":"'.$matches[1].'"}');
            return '"text":"'.translate($s->a,"RU",$user->lang).'","';
        }, $buttons,-1);
        $buttons = json_decode($buttons);

    }
    $token = telegram_token;
    $text = urlencode($text);
    if($buttons == NULL) $api_req = "https://api.telegram.org/bot$token/editMessageText?chat_id=$chat&message_id=$message_id&text=$text&parse_mode=Markdown"; else {
        $buttons = json_encode($buttons);
        $api_req = "https://api.telegram.org/bot$token/editMessageText?chat_id=$chat&message_id=$message_id&text=$text&parse_mode=Markdown&reply_markup=$buttons";
    }


    $s = file_get_contents($api_req);


    if($ending) exit("ok");
    return ($s ? true : false);
}

function delete_Telegram_Message($chat, $message_id, $ending = NULL)
{
    $token = telegram_token;
    $api_req = "https://api.telegram.org/bot$token/deleteMessage?chat_id=$chat&message_id=$message_id";

    $s = file_get_contents($api_req);

    if($ending) exit("ok");
    return ($s ? true : false);
}
