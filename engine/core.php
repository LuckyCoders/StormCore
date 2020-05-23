<?php
@session_start();
define('MAIN_DIR',str_replace('/engine','',__DIR__));
require MAIN_DIR.'/app/settings.php';

if((isset($_SESSION['group']) AND $_SESSION['group'] == dev_group ) OR Debug_mode == true) ini_set('error_reporting', E_ALL);
ini_set('display_errors',         ((isset($_SESSION['group']) AND $_SESSION['group'] == dev_group) OR Debug_mode == true ? 1 : 0));
ini_set('display_startup_errors', ((isset($_SESSION['group']) AND $_SESSION['group'] == dev_group) OR Debug_mode == true ? 1 : 0));


require 'vendor/autoload.php';

$libs_dir = MAIN_DIR."/engine/libs";
$libs = scandir($libs_dir);
if($libs) foreach ($libs as $lib){
    if(stripos($lib,'.php') > 0)
    if(file_exists("$libs_dir/".$lib))
        require_once "$libs_dir/".$lib;
}

if($config_db['password'] != 'Password') {
    $db = new \Buki\Pdox($config_db);
    unset($config_db);
}

require_once 'framework.php';