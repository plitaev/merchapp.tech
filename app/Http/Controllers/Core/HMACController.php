<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;

class HMACController extends Controller {
    public function create($data, $key, $algo = 'sha256') {
        if (!in_array($algo, hash_algos()))
            return false;
        $data = (array) $data;

        array_walk_recursive($data, function(&$v) {
            $v = strval($v);
        });

        $this->_sort($data);
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        return hash_hmac($algo, $data, $key);
    }

    static function verify($data, $key, $sign, $algo = 'sha256') {
        $_sign = $this->create($data, $key, $algo);
        return ($_sign && (strtolower($_sign) == strtolower($sign)));
    }

    static private function _sort(&$data) {
        ksort($data, SORT_REGULAR);
        foreach ($data as &$arr)
            is_array($arr) && self::_sort($arr);
    }
}
