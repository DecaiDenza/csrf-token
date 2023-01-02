<?php

namespace Codeflow;

/**
 * Class to manage the CSRF token
 */
class CSRF
{
    /**
     * Version of lib
     */
    public const VERSION = 1.0;

    /**
     * Token CSRF generated
     *
     * @var string|null
     */
    private ?string $token = null;

    /**
     * Method to generate the token
     *
     * @return string
     */
    public function generateToken(): string
    {
        if ($this->token != null) {
            return json_encode([
                'code' => 405,
                'message' => 'Token already generated and defined in session'
            ]);
        }

        // Generate token
        $this->token = bin2hex(random_bytes(24));
        $this->setTokenInSession(); // Set token in session
    }

    /**
     * Method to set token on user session
     *
     * @return string
     */
    private function setTokenInSession(): string
    {
        if (isset($_SESSION['csrf_token']) && !empty($_SESSION['csrf_token'])) {
            return json_encode([
                'code' => 405,
                'message' => 'Token already defined in session'
            ]);
        }
        $_SESSION['csrf_token'] = $this->token;
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
                'code' => 405,
                'message' => 'Token cannot be empty'
            ]);
        }

        if (!isset($_SESSION['csrf_token']) && empty($_SESSION['csrf_token'])) {
            return json_encode([
                'code' => 405,
                'message' => 'You need to generate and set the token first'
            ]);
        }

        if ($token_form != $_SESSION['csrf_token']) {
            return json_encode([
                'code' => 405,
                'message' => 'Not authorized'
            ]);
        }

        return json_encode([
            'code' => 200,
            'message' => 'authorized'
        ]);
    }
}
