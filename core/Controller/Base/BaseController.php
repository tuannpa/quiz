<?php
/**
 * @author: Tuan Nguyen
 */

require_once HELPER_DIR . 'QueryHelper.php';

/**
 * Class BaseController
 */
class BaseController
{
    /**
     * @var QueryHelper
     */
    protected $queryHelper;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        if (!$this->queryHelper instanceof QueryHelper) {
            $this->queryHelper = new QueryHelper();
        }
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
     * $_GET wrapper, use this function if you prefer accessing data of object type.
     * @return mixed
     */
    public function getUrlParams()
    {
        return $this->toObject($_GET);
    }

    /**
     * Rewrite Json method to use the method as non-static.
     * @param array $data
     * @return mixed
     */
    public function jsonResponse($data)
    {
        return self::Json($data);
    }

    /**
     * Json encode the given data.
     * @param $data
     * @return int
     */
    public static function Json($data) {
        return print json_encode($data);
    }

    /**
     * Set the http status code.
     * @param int $code
     * @return int
     */
    public static function setHttpResponseCode($code)
    {
        return http_response_code($code);
    }

    /**
     * Show the time in this format: HH:mm:ss.
     * @param $time
     * @return string
     */
    public function formatTime($time)
    {
        return sprintf('%02d:%02d:%02d', ($time / 3600), ($time / 60 % 60), ($time % 60));
    }

    /**
     * Return response.
     * @param int $statusCode
     * @param string $message
     * @return int
     */
    public static function response($statusCode, $message)
    {
        self::setHttpResponseCode($statusCode);
        return self::Json([
            'statusCode' => $statusCode,
            'message' => $message

        ]);
    }
}