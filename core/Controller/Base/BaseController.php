<?php
/**
 * @author: Tuan Nguyen
 */

require_once DRIVER_DIR . '/QueryHelper.php';

class BaseController
{
    public $queryHelper;
    public $currentUser;

    public function __construct(QueryHelper $queryHelper)
    {
        $this->queryHelper = $queryHelper;
    }

    public static function getRequestParams($getArray = false)
    {
        $jsonData = file_get_contents("php://input");
        return json_decode($jsonData, $getArray);
    }

    public static function getClassConstants($className)
    {
        if (class_exists($className)) {
            $reflectionClass = new \ReflectionClass($className);
            return $reflectionClass->getConstants();
        }

        return [];
    }


    public function toInteger($value)
    {
        return (int)$value;
    }

    public function toObject($arr)
    {
        return json_decode(json_encode($arr));
    }

    public function getUrlParams()
    {
        return $this->toObject($_GET);
    }

    public function jsonResponse($data = [])
    {
        return print json_encode($data);
    }

    public function formatTime($time) {
        return sprintf('%02d:%02d:%02d', ($time / 3600), ($time / 60 % 60), ($time % 60));
    }
}