<?php

namespace Codeflow;

use Exception;

/**
 * Class to manage the CSRF token
 *
 * @version 2.0x
 */
class CSRF
{
    /**
     * Version of lib
     */
    public const VERSION = 2.0;

    /**
     * @var string
     */
    private string $csrf_var_name;

    /**
     * Launcher
     *
     * @param string $csrf_var_name
     * @throws Exception
     */
    public function __construct(string $csrf_var_name = 'csrf_token')
    {
        if (!$this->validateCSRFVarName($csrf_var_name)) {
            throw new Exception('Invalid CSRF var name', 400);
        }
    }


    /**
     * Method to generate the token
     *
     * @return string
     */
    public function generateToken(): string
    {
        if (!empty($_SESSION[$this->csrf_var_name])) {
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
     * Method to validate CSRF var name
     *
     * @param string $csrf_var_name
     * @return bool
     */
    private function validateCSRFVarName(string $csrf_var_name): bool
    {
        $options = [
            'options' => [
                'regexp' => '/[^a-zA-Z0-9\_]{1}/'
            ]
        ];

        if (filter_var($csrf_var_name, FILTER_VALIDATE_REGEXP, $options)) {
            return false;
        }

        $this->csrf_var_name = $csrf_var_name;

        return true;
    }

    /**
     * Method to set token on user session
     *
     * @param string $token
     * @return string
     */
    private function setTokenInSession(string $token): string
    {
        $_SESSION[$this->csrf_var_name] = $token;
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

        if (empty($_SESSION[$this->csrf_var_name])) {
            return json_encode([
                'code' => 400,
                'message' => 'You need to generate and set the token first'
            ]);
        }

        if (!hash_equals($_SESSION[$this->csrf_var_name], $token_form)) {
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
