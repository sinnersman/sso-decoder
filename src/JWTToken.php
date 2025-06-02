<?php

namespace RiskySetiawan\SSODecoder;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    // Encrypt payload with AES-256-CBC
    public static function encryptPayload($data, $secret)
    {
        return base64_encode(openssl_encrypt(
            $data,
            'AES-256-CBC',
            $secret,
            0,
            substr($secret, 0, 16)
        ));
    }

    // Decrypt payload
    public static function decryptPayload($data, $secret)
    {
        return openssl_decrypt(
            base64_decode($data),
            'AES-256-CBC',
            $secret,
            0,
            substr($secret, 0, 16)
        );
    }

    // Encode JWT
    public static function encodeData($payload, $secret)
    {
        return JWT::encode($payload, $secret, 'HS256');
    }

    // Decode JWT
    public static function decodeData($jwt, $secret)
    {
        return JWT::decode($jwt, new Key($secret, 'HS256'));
    }

    // Encrypt data as encrypted-JWT
    public static function encrypt($payload, $secret)
    {
        if (is_object($payload)) {
            $payload = method_exists($payload, 'toArray') ? $payload->toArray() : (array) $payload;
        }

        $jwt = self::encodeData($payload, $secret); // format JWT: x.y.z
        return self::encryptPayload($jwt, $secret); // AES encrypt the JWT string
    }

    // Decrypt encrypted-JWT
    public static function decrypt($encrypted, $secret)
    {
        $jwt = self::decryptPayload($encrypted, $secret);

        if (substr_count($jwt, '.') !== 2) {
            throw new \Exception("Invalid JWT format: $jwt");
        }

        return (array) self::decodeData($jwt, $secret);
    }
}