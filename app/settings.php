<?php
/** ProjectName for Logs/Mails etc.. */
define('ProjectName','StormCore');
define('EmailSendler',"mail@".ProjectName.".ru");
define('DeveloperEmail',"dev@".ProjectName.".ru");

/** See Debug Messages */
define('Debug_mode',true);

/** You can set group to always see debug messages */
define('dev_group',5);


/** Hash string to generate passwords and crypto hashes (You should change it for yourself!) */
define('EncryptKey','KEY_STORM-CORE00237');


/** Additional libs */
define('Telegram_API','off',true);
define('LocalisationLib','off',true);

/** Database connection */
$config_db = [
    'host' => 'localhost',
    'driver' => 'mysql',
    'database' => 'DB_name',
    'username' => 'DB_username',
    'password' => 'Password',
    'charset' => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix' => ''
];