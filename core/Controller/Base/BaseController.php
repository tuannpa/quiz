<?php
/**
 * @author: Tuan Nguyen
 */

require_once DRIVER_DIR . 'QueryHelper.php';

/**
 * Class BaseController
 */
class BaseController
{
    /**
     * @var QueryHelper
     */
    public $queryHelper;

    /**
     * BaseController constructor.
     * @param QueryHelper $queryHelper
     */
    public function __construct(QueryHelper $queryHelper)
    {
        $this->queryHelper = $queryHelper;
    }

    /**
     * Get the data sent to server-side from Angular.
     * @param bool $getArray
     * @return mixed
     */
    public static function getRequestPayload($getArray = false)
    {
        $jsonData = file_get_contents("php://input");
        return json_decode($jsonData, $getArray);
    }

    /**
     * Return an array of constants from the given class name.
     * @param $className
     * @return array
     */
    public static function getClassConstants($className)
    {
        if (class_exists($className)) {
            $reflectionClass = new \ReflectionClass($className);
            return $reflectionClass->getConstants();
        }

        return [];
    }

    /**
     * Cast an array type to object type.
     * @param $arr
     * @return mixed
     */
    public function toObject($arr)
    {
        return json_decode(json_encode($arr));
    }

    /**
     * $_GET wrapper, use this function if you prefer access data of object type.
     * @return mixed
     */
    public function getUrlParams()
    {
        return $this->toObject($_GET);
    }

    /**
     * Json encode the given data.
     * @param array $data
     * @return mixed
     */
    public function jsonResponse($data)
    {
        return print json_encode($data);
    }

    /**
     * Show the time in this format: HH:mm:ss.
     * @param $time
     * @return string
     */
    public function formatTime($time) {
        return sprintf('%02d:%02d:%02d', ($time / 3600), ($time / 60 % 60), ($time % 60));
    }
}