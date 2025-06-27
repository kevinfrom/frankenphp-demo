<?php
declare(strict_types=1);

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

define('ROOT_DIR', dirname(__DIR__));
const CONFIG_DIR = ROOT_DIR . DS . 'config';
const VENDOR_DIR = ROOT_DIR . DS . 'vendor';
