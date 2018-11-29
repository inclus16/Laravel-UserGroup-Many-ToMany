<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 28.11.2018
 * Time: 20:53
 */

namespace App\Services;


use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggerBuilder
{

    public static function getApiLogger()
    {
        $log= new Logger('UGapi');
        $log->pushHandler(new RotatingFileHandler(storage_path('/logs/UGapi/all/'.php_sapi_name()),10,Logger::API));
        $log->pushHandler(new RotatingFileHandler(storage_path('/logs/UGapi/warning/'.php_sapi_name()),10,Logger::WARNING));
        $log->pushHandler(new RotatingFileHandler(storage_path('/logs/UGapi/critical/'.php_sapi_name()),10,Logger::CRITICAL));
        return $log;
    }

}