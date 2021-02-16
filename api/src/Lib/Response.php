<?php

namespace App\Lib;

class Response
{
    private $status;

    private function __construct($code) {
        $this->status = $code;
    }

    public static function withCode($code): self {
        return new Response($code);
    }

    public static function ok(): self {
        return self::withCode(200);
    }

    public static function error(): self {
        return self::withCode(400);
    }
    
    public function toJSON($data = [])
    {
        http_response_code($this->status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
