<?php
require_once __DIR__ . '/site-info.php';
require_once __DIR__ . '/navigation-object.php';

$navigation = new get_main_navigation();
print_r($navigation->full_navigaiton);
