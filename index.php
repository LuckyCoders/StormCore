<?php
require 'engine/core.php';
$requested_page = (isset($_GET['page']) ? $_GET['page'] : 'main');
$page = new page();
require 'app/router.php';
$page->build($template,(isset($meta) ? $meta : null));
?>