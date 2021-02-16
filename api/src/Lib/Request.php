<?php namespace App\Lib;

class Request
{
    public $params;
    public $reqMethod;
    public $contentType;

    private $dataInputFile;

    public function __construct($params = [], $input = "php://input")
    {
        $this->dataInputFile = $input;

        $this->params = $params;
        $this->reqMethod = trim($_SERVER['REQUEST_METHOD']);
        $this->contentType = trim($_SERVER["CONTENT_TYPE"] ?? "");
    }

    public function getBody()
    {
        if ($this->reqMethod !== 'POST') {
            return '';
        }

        $body = filter_var_array($_POST, FILTER_SANITIZE_STRING, true);

        return $body;
    }

    public function getJSON()
    {
        if ($this->reqMethod !== 'POST') {
            return [];
        }

        if (strcasecmp($this->contentType, 'application/json') !== 0) {
            return [];
        }

        // Receive the RAW post data.
        $content = trim(file_get_contents($this->dataInputFile));
        return json_decode($content);
    }
}
