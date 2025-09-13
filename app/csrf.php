<?php

final class Csrf
{
    //return token (genere si pas de token)
    public static function getToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    //check le token de session avec le post du user
    public static function checkToken(): void
    {
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        $formToken = $_POST['_csrf'] ?? '';

        $isValid = $formToken !== '' && hash_equals($sessionToken, (string)$formToken);

        if (!$isValid) {
            http_response_code(403);
            exit('invalid csrf token');
        }
    }
}
