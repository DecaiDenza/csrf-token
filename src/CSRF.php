<?php

namespace Codeflow;

use Exception;

/**
 * Class to manage the CSRF token
 *
 * @version 1.0x
 */
class CSRF
{
    /**
     * Version of lib
     */
    public const VERSION = 1.0;


    /**
     * Method to generate the token
     *
     * @return string
     */
    public function generateToken(): string
    {
        if (!empty($_SESSION['csrf_token'])) {
            return json_encode([
                'code' => 200,
                'message' => 'Token already defined'
            ]);
        }

        $token = null;
        try {
            $token = bin2hex(random_bytes(32));
        } catch (Exception $exception) {
            return json_encode([
                'code' => 400,
                'message' => $exception->getMessage()
            ]);
        }

        return $this->setTokenInSession($token);
    }

    /**
     * Method to set token on user session
     *
     * @param string $token
     * @return string
     */
    private function setTokenInSession(string $token): string
    {
        $_SESSION['csrf_token'] = $token;
        return json_encode([
            'code' => 200,
            'message' => 'Defined token'
        ]);
    }

    /**
     * Method to validate userform token
     *
     * @param string $token_form
     * @return string
     */
    public function checkToken(string $token_form): string
    {
        if (empty($token_form)) {
            return json_encode([
                'code' => 400,
                'message' => 'Token cannot be empty'
            ]);
        }

        if (empty($_SESSION['csrf_token'])) {
            return json_encode([
                'code' => 400,
                'message' => 'You need to generate and set the token first'
            ]);
        }

        if (!hash_equals($_SESSION['csrf_token'], $token_form)) {
            return json_encode([
                'code' => 400,
                'message' => 'Not authorized'
            ]);
        }

        return json_encode([
            'code' => 200,
            'message' => 'Authorized'
        ]);
    }
}
