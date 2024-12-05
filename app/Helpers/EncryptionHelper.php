<?php

namespace App\Helpers;

class EncryptionHelper
{
    public static function encrypt($data, $key)
    {
        return openssl_encrypt($data, 'aes-256-cbc', $key, 0, str_repeat('0', 16));
    }

    public static function decrypt($data, $key)
    {
        // fix strange bug that url replaces + with spaces
        $data = str_replace(' ', '+', $data);

        return openssl_decrypt($data, 'aes-256-cbc', $key, 0, str_repeat('0', 16));
    }
}
