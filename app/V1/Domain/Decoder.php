<?php

namespace App\V1\Domain;

class Decoder
{
    public static function decode(string $token): array
    {
        return json_decode(base64_decode($token), true);
    }

    public static function encode(array $values): string
    {
        return base64_encode(json_encode($values));
    }
}
