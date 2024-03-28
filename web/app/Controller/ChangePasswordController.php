<?php

namespace Praktikant\Praktikum\Controller;

use PHPMailer\PHPMailer\PHPMailer;
use Praktikant\Praktikum\classes\Connection;
use Praktikant\Praktikum\classes\Html;
use Praktikant\Praktikum\classes\Logedin;

class ChangePasswordController
{
    public function Index(): void
    {
        $Status = $_GET["Status"] ?? '';
        if ($Status == 'Succses')
        {
            $error = '<h2 class="h3 mb-3 font-weight-normal" style="color: greenyellow">Password Geändert!</h2>';
        } elseif ($Status == 'ERROR'){
            $error = '<h2 class="h3 mb-3 font-weight-normal" style="color: red">Falsches Password!</h2>';
        } elseif ($Status == 'E_ERROR'){
            $error = '<h2 class="h3 mb-3 font-weight-normal" style="color: red">E-Mail Konnte Nicht Gesendet Werden.</h2>';
        }
        else {
            $error = '';
        }
        $logedin = new Logedin();
        $logedin->Logedin();
        $html = new Html();
        $html->setTitle('Password Ändern');
        $content = '<br><h2 class="h3 mb-3 font-weight-normal">Password Ändern</h2> ' . $error .  '
<body class="text-center"
<main class="form-signin">
<form class="form-signin" method="post" action="PasswordChangeController">

    
    <input minlength="8" maxlength="100" class="form-control" type="password" name="password" id="password" placeholder="Altes Password" required><br>
    <input minlength="8" maxlength="100" class="form-control" type="password" name="password2" id="password2" placeholder="Neues Password" required><br><br>
    <button class="btn btn-lg btn-primary btn-block" type="submit" value="send">Senden</button>
</form>
</main> </body>'; $html->render(['content' => $content, 'body_class' => 'text-center']);


    }
}