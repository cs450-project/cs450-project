<?php

namespace CS450\Service\Jwt;

use Firebase\JWT\JWT;
use CS450\Service\JwtService;

class FirebaseJwt implements JwtService {
    public function encode($payload, $key, $alg = 'HS256', $head = array()) {
        return JWT::encode($payload, $key, $alg, $head);
    }

    public function decode($jwt, $key, array $allowed_algs = array('hs264')) {
        return JWT::decode($jwt, $key, $allowed_algs);
    }
}
