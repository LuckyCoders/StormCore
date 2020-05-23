<?php
function RandomString($length = 32) {
    $randstr='';
    srand((double) microtime(TRUE) * 1000000);
    $chars = array(
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p',
        'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5',
        '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
        'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

    for ($rand = 0; $rand <= $length; $rand++) {
        $random = rand(0, count($chars) - 1);
        $randstr .= $chars[$random];
    }
    return $randstr;
}
function Encryptor($action = "encrypt", $string, $encryption_key = NULL){
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_iv = ($encryption_key == NULL ? EncryptKey : $encryption_key);
    $key = hash('sha256', EncryptKey);
    $iv = substr(hash('sha256', $secret_iv), 0, 12);

    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}
function redirect($url, $permanent = false)
{
    if ($permanent) {
        header('HTTP/1.1 301 Moved Permanently');
    }
    header('Location: ' . $url);
    exit();
}
function file_download($file){
    if (file_exists($file)) {
        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        return [
            'status' => 'success',
            'message' => 'Файл успешно отдан'
        ];
    } else {
        return [
            'status' => 'error',
            'message' => 'Файл не найден'
        ];
    }
}
function file_remove($file){
    if (file_exists($file)) {
        $s = unlink($file);
        if($s)
            return [
                'status' => 'success',
                'message' => 'Файл успешно удален'
            ];
        else
            return [
                'status' => 'error',
                'message' => 'Не удалось удалить файл'
            ];
    } else {
        return [
            'status' => 'error',
            'message' => 'Файл не найден'
        ];
    }
}

function error($code, $text = NULL, $end = NULL)
{
    global $db;
    $log = 0;
    $notif = 0;
    $end = ($end == null ? 0 : 1);

    require 'error_codes.php';

    if($log == 1) {
        in_log($code);
        //update_platform_dayly_stat('errors_in_log', '+1');
    }
    if($notif == 1)
        DevLog($text,(Telegram_API == 'on' ? 1 : 0));
    if($end) exit("Error code: $code".($text ? " $text" : ""));
    return $text;
}
function in_log($code,$type = 'errors')
{
    $ar = json_encode(debug_backtrace());
    $fp = fopen(MAIN_DIR."/app/logs/".$type."_" . date("d_m_Y") . '.txt', "a");
    fwrite($fp, "------\nTime: " . date("d-m-Y H:i:s") . "\nCode: $code \n" . (isset($_SESSION['uid']) ? "Пользователь: ".$_SESSION['uid']. " \n" : "") ."Route: ". $ar . "\n------");
    fclose($fp);
    return true;
}

function date_differ($date1, $date2, $abs = "no")
{
    /**
     * date_differ - функция вычисляет разницу между двумя датами в секундах
     *
     * @param string date1 - дата 1
     * @param string date2 - дата 2
     *
     * @return int - разница в секундах
     *
     * Дата должна быть определенного формата,
     * советую ознакомится с функций strtotime()
     * http://docs.php.net/manual/ru/function.strtotime.php
     *
     */
    $diff = strtotime($date2) - strtotime($date1);
    return ($abs == 'no' ? $diff : abs($diff));
}
function number2word($num, $form_for_1, $form_for_2, $form_for_5){
    /*
* $num число, от которого будет зависеть форма слова
* $form_for_1 первая форма слова, например Товар
* $form_for_2 вторая форма слова - Товара
* $form_for_5 третья форма множественного числа слова - Товаров
*/
    $num = abs($num) % 100; // берем число по модулю и сбрасываем сотни (делим на 100, а остаток присваиваем переменной $num)
    $num_x = $num % 10; // сбрасываем десятки и записываем в новую переменную
    if ($num > 10 && $num < 20) // если число принадлежит отрезку [11;19]
        return $form_for_5;
    if ($num_x > 1 && $num_x < 5) // иначе если число оканчивается на 2,3,4
        return $form_for_2;
    if ($num_x == 1) // иначе если оканчивается на 1
        return $form_for_1;
    return $form_for_5;
}
function declension($digit,$expr,$onlyword=false){
    /**
     * Функция склонения слов
     *
     * @param mixed $digit
     * @param mixed $expr
     * @param bool $onlyword
     * @return
     */
    if (!is_array($expr)) $expr = array_filter(explode(' ', $expr));
    if (empty($expr[2])) $expr[2]=$expr[1];
    $i=preg_replace('/[^0-9]+/s','',$digit)%100;
    if ($onlyword) $digit='';
    if($i>=5 && $i<=20) {
        $res=$digit.' '.$expr[2];
    } else {
        $i%=10;
        if($i==1) {
            $res=$digit.' '.$expr[0];
        } elseif($i>=2 && $i<=4) {
            $res=$digit.' '.$expr[1];
        } else {
            $res=$digit.' '.$expr[2];
        }
    }
    return trim($res);
}
function downcounter($date){
    /**
     * Счетчик обратного отсчета
     *
     * @param mixed $date
     * @return
     */
    $check_time = strtotime($date) - time();
    if($check_time <= 0){
        return false;
    }

    $days = floor($check_time/86400);
    $hours = floor(($check_time%86400)/3600);
    $minutes = floor(($check_time%3600)/60);
    $seconds = $check_time%60;

    $str = '';
    if($days > 0) $str .= declension($days,array('день','дня','дней')).' ';
    if($hours > 0) $str .= declension($hours,array('час','часа','часов')).' ';
    if($minutes > 0) $str .= declension($minutes,array('минута','минуты','минут')).' ';
    if($seconds > 0) $str .= declension($seconds,array('секунда','секунды','секунд'));

    return $str;
}
function output_text_converter($data,$output_format){
    $text = $data;
    if($output_format == "markdown") {
        $text = str_replace("{br}", "\n", $text);
        $text = str_replace("<br>", "\n", $text);
        $text = str_replace("<li>", "🔸", $text);
        $text = str_replace("</li>", "", $text);
        $text = str_replace("<b>", "**", $text);
        $text = str_replace("</b>", "**", $text);
        $text = str_replace("</ul>", "", $text);
        $text = str_replace("<ul>", "", $text);
        $text = str_replace("<hr>", "\n〰〰〰〰〰〰〰〰\n", $text);
        $text = str_replace("!!", "‼️", $text);
    }
    return $text;
}
function isJSON($string)
{
    return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
}
function vardump($var,$console = false){
    echo ($console ? "\n" : '<pre>');
    if($console) exit(var_dump($var)."\n"); else var_dump($var);
    echo '<pre>';
}

function get_browser_language( $uppercase = true, $default = 'EN', $available = ['EN','FR','DE','UK','zh-CN','RU'] ) {
    /**
     * Get browser language, given an array of avalaible languages.
     *
     * @param  [array]   $availableLanguages  Avalaible languages for the site
     * @param  [string]  $default             Default language for the site
     * @return [string]                       Language code/prefix
     */
    if ( isset( $_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ] ) ) {
        $langs = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
        if ( empty( $available ) ) {
            if($uppercase == true)
                $langs[ 0 ] = mb_strtoupper($langs[ 0 ]);
            else
                $langs[ 0 ] = mb_strtolower($langs[ 0 ]);
            return explode("-",$langs[ 0 ])[0];
        }
        foreach ( $langs as $lang ){
            $lang = substr( $lang, 0, 2 );
            if( in_array( mb_strtoupper($lang), $available ) ) {
                if($uppercase == true)
                    $lang = mb_strtoupper($lang);
                else
                    $lang = mb_strtolower($lang);
                return $lang;
            }
        }
    }
    if($uppercase == true)
        $default = mb_strtoupper($default);
    else
        $default = mb_strtolower($default);
    return $default;
}
function translate_text($str, $from , $to){
    $query_data = array(
        'client' => 'x',
        'q' => $str,
        'sl' => $from,
        'tl' => $to
    );
    $filename = 'http://translate.google.ru/translate_a/t';
    $options = array(
        'http' => array(
            'user_agent' => 'Mozilla/5.0 (Windows NT 6.0; rv:26.0) Gecko/20100101 Firefox/26.0',
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($query_data)
        )
    );
    $context = stream_context_create($options);
    $response = file_get_contents($filename, false, $context);
    return json_decode($response);
}
function translate($str, $lang_from, $lang_to) {
    global $db;

    $file = 'assets/lang.json';
    $dictionary = file_get_contents($file);
    if(!isset($dictionary)) return false;

    $local = $db->table('lang')->where('str',$str)->get();
    if(!$local) {
        $out = translate_text($str, $lang_from, $lang_to);
        $tr = json_encode(array("$lang_to"=>$out));
        $db->table('lang')->insert(array('str'=>$str,'tr'=>$tr));
        return $out;
    } else {
        $qu_c = ($local->qu_c ? $local->qu_c : 0) + 1;
        $db->table('lang')->where('str',$str)->update(array('last_get'=>date('Y-m-d H:i:s'),'qu_c'=>$qu_c));
    }

    $local = json_decode($local->tr);
    if(isset($local->$lang_to)){
        $out = $local->$lang_to;
    } else {
        $out = translate_text($str, $lang_from, $lang_to);
        @$local->$lang_to = $out;
        $db->table('lang')->where('str',$str)->update(array('tr'=>json_encode($local)));
    }

    return $out;
}
function email_validation($email){
    @$req = file_get_contents("http://api.skynes.ru/email/?mail=$email");
    if($req){
        $req = json_decode($req);
        return $req->score;
    } else return false;
}
function keywords_selector($string){
    $avoid = array('i','a','about','an','and','are','as','at','be','by','com','de','en','for','from','how','in','is','it','la','of','on','or','that','the','this','to','was','what','when','where','who','will','with','und','the','www','и','на','короче','однако');
    $min_word_length = 3;
        $strip_arr = ["," ,"." ,";" ,":", "\"", "'", "“","”","(",")", "!","?"];
        $str_clean = str_replace( $strip_arr, "", mb_convert_encoding($string, "UTF-8"));
        $str_arr = explode(' ', $str_clean);
        $clean_arr = [];
        foreach($str_arr as $word)
        {
            if(strlen($word) > $min_word_length)
            {
                $word = mb_strtolower($word);
                if(!in_array($word, $avoid)) {
                    $clean_arr[] = $word;
                }
            }
        }
        return implode(', ', $clean_arr);
}

function update_platform_dayly_stat($colum, $val, $date = 'now'){
    global $db;
    if($date == 'now') $date = date("Y-m-d");
    $q = $db->table('platform_stats')->where('date',$date)->get();
    if(!$q) {
        $db->table('platform_stats')->insert(array('date'=>$date));
        $q = $db->table('platform_stats')->where('date',$date)->get();
    }

    if(substr($val,0,1) == "+"){
        $val = str_replace("+","",trim($val));
        $s = $db->table('platform_stats')->where('date',$date)->update(array("$colum"=>($q->$colum + $val)));
        return ($s ? true : false);
    }

    if(substr($val,0,1) == "-"){
        $val = str_replace("-","",trim($val));
        $s = $db->table('platform_stats')->where('date',$date)->update(array("$colum"=>($q->$colum - $val)));
        return ($s ? true : false);
    }

    $val = trim($val);
    $s = $db->table('platform_stats')->where('date',$date)->update(array("$colum" => $val));
    return ($s ? true : false);
}

function sendmail($email, $subject, $text)
{

    $headers = "From: " . strip_tags(EmailSendler) . "\r\n";
    $headers .= "Reply-To: " . strip_tags($email) . "\r\n";
    $headers .= "CC: " . strip_tags(EmailSendler) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    $status = mail($email, $subject, $text, $headers);
    return ($status ? true : false);
}
function DevLog($string,$type = 1){
    if($type == 1 AND Telegram_API == 'on')
        send_Telegram(dev_chat_tg,$string);
    else
        sendmail(DeveloperEmail,"DevLog Request [".ProjectName."]",$string);
}
class page
{
    public $page = null;

    function replacer($tag, $content)
    {
        if (stripos($this->page, $tag) > 0) $this->page = str_replace($tag, $content, $this->page);
    }

    public function build($name,$meta_array = null)
    {

        $page_title = (isset($meta_array['title']) ? $meta_array['title'] . " — " . ProjectName : ProjectName);
        $page_desc = (isset($meta_array['desc']) ? $meta_array['desc'] : NULL);

        $name_e = explode("_", $name);
        $module = $name_e[0];
        $page   = $name_e[1];
        $this->page = file_get_contents(MAIN_DIR . "/templates/$module/index.tpl");

        $content = file_get_contents(MAIN_DIR . "/templates/$module/$page.tpl");

        if ($page_desc != NULL) {
            $desc_tmp  = '<meta name="description" content="' . $page_desc . '">';
            $desc_tmp .= (isset($meta_array['keywords']) ? '<meta name="Keywords" content="' .(is_array($meta_array['keywords']) ? implode(', ',$meta_array['keywords']) : $meta_array['keywords']). '">' : '<meta name="Keywords" content="' . keywords_selector($page_desc) . '">');
            $page_desc = $desc_tmp;
        }

        $this->replacer("{title}", "<title>" . $page_title . "</title>" . $page_desc);
        $this->replacer("{content}", $content);
        $this->replacer("{res}", "assets/$module");

        $this->replacer("{project_name}", ProjectName);

        /**
         * Here You can write your own data mutation and personal function for page content generation.
         */

        require MAIN_DIR.'/app/app.php';

        if (isset($_SESSION['lang']) AND $_SESSION['lang'] != "RU" AND LocalisationLib == true) {

            $html = str_get_html($this->page, $lowercase = true, $forceTagsClosed = true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN = true, $defaultBRText = DEFAULT_BR_TEXT, $defaultSpanText = DEFAULT_SPAN_TEXT);
            $i = 0;
            foreach ($html->find('text') as $text) {
                if (trim($text->plaintext) != "") {
                    if (stripos("--" . $text->plaintext, "function ") > 0) {
                        $i++;
                        continue;
                    }
                    $text_in = $text->innertext;
                    $text_pl = $text->plaintext;
                    $text_ou = $text->outertext;
                    $text_new = translate($text_ou, "RU", $_SESSION['lang']);
                    $page_tr[$i] = $text_new;
                }
                $i++;
            }
            foreach ($page_tr as $key => $text) {
                $o = $html->find('text')[$key]->outertext;
                if (substr($o, 0, 1) == ' ') {
                    $html->find('text')[$key]->outertext = ' ' . $text;
                } else
                    if (substr($o, -1) == ' ') {
                        $html->find('text')[$key]->outertext = $text . ' ';
                    } else
                        $html->find('text')[$key]->outertext = $text;

            }

            $i = 0;
            foreach ($html->find('input,textarea') as $input) {
                if (trim($input->placeholder) != "") {
                    $text_new = translate($input->placeholder, "RU", $_SESSION['lang']);
                    $pholders[$i] = $text_new;
                }
                $i++;
            }
            if ($i > 0)
                foreach ($pholders as $key => $text)
                    $html->find('input,textarea')[$key]->placeholder = $text;

            $this->page = $html->save();
        }
        print $this->page;
    }
}
