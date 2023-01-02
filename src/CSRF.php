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

    public function generateToken(): void
    {
        if ($this->token != null) {
            echo "Token already generated and defined in session";
            return;
        }

        // Generate token
        $this->token = bin2hex(random_bytes(32));
        $this->setTokenInSession(); // Set token in session
    }

    /**
     * Method to set token on user session
     *
     * @return void
     */
    private function setTokenInSession(): void
    {
        if (isset($_SESSION['csrf_token']) && !empty($_SESSION['csrf_token'])) {
            echo "Token already defined in session";
            return;
        }
        $_SESSION['csrf_token'] = $this->token;
    }

    /**
     * Method to validate userform token
     *
     * @param string $token_form
     * @return void
     */
    public function checkToken(string $token_form): void
    {
        if (empty($token_form)) {
            echo "Token não pode estar vazio";
            return;
        }

        if (!isset($_SESSION['csrf_token']) && empty($_SESSION['csrf_token'])) {
            echo "You need to generate and set the token first";
            return;
        }

        if ($token_form != $_SESSION['csrf_token']) {
            echo json_encode(["message" => "Not authorized"]);
            return;
        }
    }
}