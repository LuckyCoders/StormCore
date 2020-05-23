<?php
    require "../core.php";
    $dir_to_save_sql = realpath ( '' );
    $date = date("d_m_Y");
    $i=0;
    exec("rm -rf dump_*");
    exec("mysqldump --user=$config_db[username] --password=$config_db[password] --host=$config_db[host] $config_db[database] > $dir_to_save_sql/$config_db[database].sql");
    exec("zip -r dump_$date.zip *");
    exec("rm $config_db[database].sql");
    exit("\n|********************|\nComplited!\n|********************|\n");
?>