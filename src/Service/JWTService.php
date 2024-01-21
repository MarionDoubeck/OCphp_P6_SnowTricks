<?php
namespace App\Service;

use DateTimeImmutable;

// Generate the token (see jwt.io).
class JWTService
{

    /**
     * Generate JWT
     * @param array $header
     * @param array $payload
     * @param string $secret
     * @param int $validity
     * @return string
     */
    public function generate(array $header, array $payload, string $secret, int $validity = 10800): string
    {
        if ($validity > 0) {
            $now = new DateTimeImmutable();
            $exp = $now->getTimestamp() + $validity;

            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $exp;
        }

        // Encode in base64.
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        // "Clean" the encoded values (remove +, /, and =).
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        // Generate the signature.
        $secret = base64_encode($secret);

        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);

        $base64Signature = base64_encode($signature);

        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        // Create the token.
        $jwt = $base64Header . '.' . $base64Payload . '.' . $base64Signature;

        return $jwt;
    }


    // Check if the token is valid (correctly formed).
    public function isValid(string $token): bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $token
        ) === 1;
    }


    // Get the Payload.
    public function getPayload(string $token): array
    {
        // Split the token.
        $array = explode('.', $token);

        // Decode the Payload.
        $payload = json_decode(base64_decode($array[1]), true);

        return $payload;
    }


    // Get the Header.
    public function getHeader(string $token): array
    {
        // Split the token.
        $array = explode('.', $token);

        // Decode the Header.
        $header = json_decode(base64_decode($array[0]), true);

        return $header;
    }


    // Check if the token has expired.
    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);

        $now = new DateTimeImmutable();

        return $payload['exp'] < $now->getTimestamp();
    }


    // Verify the Token's Signature.
    public function check(string $token, string $secret)
    {
        // Get the header and payload.
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        // Regenerate a token.
        $verifToken = $this->generate($header, $payload, $secret, 0);

        return $token === $verifToken;
    }


}
