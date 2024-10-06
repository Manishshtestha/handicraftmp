<?php

function generateRandomString($length = 4): string{
    $characters = '0123456789';
    $charactersLength = strlen(string: $characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(min: 0, max: $charactersLength - 1)];
    }
    return $randomString;
}
$t_uuid = "TXN-" . generateRandomString();
$message = "total_amount=$total,transaction_uuid=$t_uuid,product_code=EPAYTEST";
$secretKey = "8gBm/:&EnhH.1/q";
$sig = hash_hmac(algo: 'sha256', data: $message, key: $secretKey, binary: true);
