<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileAdapter;

$logger = new FileAdapter(__DIR__ . '/../../log/' . date("Y-m-d") . '.log');