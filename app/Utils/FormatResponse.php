<?php
namespace App\Utils;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class FormatResponse implements Arrayable, Jsonable
{
    private $statusCode;
    private $message;
    private $data;

    function __construct($statusCode, $message, $data) {
        $this->setStatusCode($statusCode);
        $this->setMessage($message);
        $this->setData($data);
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function toArray() {
        return [
            "statusCode" => $this->statusCode,
            "message" => $this->message,
            "data" => $this->data,
        ];
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
