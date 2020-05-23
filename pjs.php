 <?php
  require "engine/core.php";

  function secondsToTime($seconds)
        {
            // extract hours
            $hours = floor($seconds / (60 * 60));

            // extract minutes
            $divisor_for_minutes = $seconds % (60 * 60);
            $minutes = floor($divisor_for_minutes / 60);

            // extract the remaining seconds
            $divisor_for_seconds = $divisor_for_minutes % 60;
            $seconds = ceil($divisor_for_seconds);

            // return the final array
            $obj = array(
                "h" => (int) $hours,
                "m" => (int) $minutes,
                "s" => (int) $seconds,
            );
            return $obj;
        }
  function check_timer($name,$rerurn_sec = NULL){
            if(isset($_SESSION['time_pjs'][$name])) {
                $now = date("Y-m-d H:i:s");
                $timeFirst  = strtotime($now);
                $timeSecond = strtotime($_SESSION['time_pjs'][$name]);
                $seconds = $timeSecond - $timeFirst;
                if($seconds > 0) return ($rerurn_sec ? $seconds : false);
            }
            return true;
        }
  function start_timer($name,$seconds = 120){
            $_SESSION['time_pjs'][$name] = date("Y-m-d H:i:s", strtotime("+$seconds sec"));;
        }
  function check_cooldown($name,$seconds = 120,$repeats = NULL,$alert = NULL){
            $output = 'Вы не можете совершать это действие слишком часто, необходимо подождать {s}';
            $alert = (!isset($alert) ? $output : $alert);
            if(check_timer($name) == false) {
                $sec = check_timer($name,'on');
                $time = secondsToTime($sec);
                if($time['h'] > 0) $sec = $time['h']." ".number2word($time['h'],"час","часа","часов"); else
                    if($time['m'] > 0) $sec = $time['m']." ".number2word($time['m'],"минуту", "минуты", "минут"); else
                        if($time['s']) $sec = $time['s']." ".    number2word($time['s'],"секунда","секунды","секунд");
                answer(array('status'=>5,'text'=>str_replace("{s}", $sec, $alert)));
            } else {
                $count_of_try = NULL;
                if(is_numeric($repeats)){
                    $count_of_try = (isset($_SESSION['time_r_pjs'][$name]) ? $_SESSION['time_r_pjs'][$name] : 1);
                    $count_of_try++;
                    $_SESSION['time_r_pjs'][$name] = $count_of_try;
                }
                start_timer($name,($count_of_try ? ($count_of_try > $repeats ? $count_of_try * $seconds : $seconds) : $seconds));
            }
        }

  function answer($ans)
        {
            echo((is_object($ans) OR is_array($ans)) ? json_encode($ans) : json_encode(array('status'=>5,'text'=>($ans == "ERROR" ? "Произошла ошибка" : $ans))));
            exit();
        }

  if(isset($_GET['sys'])){
      check_cooldown('sys',10,'Необходимо подождать перед совершением этого действия повторно.');
            $sys = $_GET['sys'];
            switch ($sys){
                case "sms":
                    answer(array('status'=> 1));
                    break;
            }
        }

   if((isset($_SESSION['group']) AND $_SESSION['group'] == dev_group ) OR Debug_mode == true) answer(vardump($_POST)); else answer("ERROR");
