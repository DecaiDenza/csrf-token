# csrf-token
CSRF Token Manager.

## How to use

> Generating the token

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Codeflow\CSRF;

session_start();

$csrf = new CSRF(); 

// Generate token for current session $_SESSION['csrf_token']
$csrf->generateToken(); // return json encoded (error or success)

?>
```
> Validate the token

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Codeflow\CSRF;

session_start();

$csrf = new CSRF();

// User form token
$token_form = filter_input(INPUT_POST, '__csrf', FILTER_DEFAULT);

// Checks if the received token hash is the same as the session hash
// If valid, it returns a json with "success", otherwise a json with the error
$csrf->checkToken($token_form);
```

## Changelogs

- Can now change session variable name in class constructor (optional).
- Added validation for session variable name.
Validation takes place at the time of class instantiation.

NOTE: The Constructor may throw an exception in case of unsuccessful validation
