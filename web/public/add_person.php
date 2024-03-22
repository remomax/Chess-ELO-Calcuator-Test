<?php
declare(strict_types=1);
require '../vendor/autoload.php'; // Pfad zur autoload.php entsprechend deiner Installation
require '../app/classes/Connection.php';
require '../app/classes/Person.php';
use Praktikant\Praktikum\classes\Person;
use Praktikant\Praktikum\classes\Connection;

$_POST["elo"] = 0;
$_POST["games"] = 0;
$mail123 = new Person();
$connection = new Connection();
$die = function () {
    echo "<h1><a href='register.php'>Zurück</a></h1>";
    die();
};




//Checken ob der Username schon Vergeben ist
$username = $_POST["username"];
$sql = "SELECT * FROM person WHERE username='$username'";
$result = $connection->getConnection()->query($sql);

if ($result->num_rows > 0) {
    echo "<h1>Benutzername Schon vergeben</h1>";
    echo "<h1><a href='register.php'>Zurück</a></h1>";
    die();
}
if ($_POST["username"] == '') {
    echo "<h1>Du must ein Username haben</h1>";
    echo "<h1><a href='register.php'>Zurück</a></h1>";
    die();
}

if ($_POST["password"] == '') {
    echo "<h1>Du must ein Password haben</h1>";
    echo "<h1><a href='register.php'>Zurück</a></h1>";
    die();
}

//Checken ob Alt Genug
$age = $_POST["age"];
if ($age <= 12) {
    echo "<h1>Du must 13 Jahre alt sein</h1>";
    echo "<h1><a href='register.php'>Zurück</a></h1>";
    die();
}


if ($_POST["lname"] == '') {
    echo "Du must einen Nachnamen haben";
    echo "<h1><a href='register.php'>Zurück</a></h1>";
    die();
}
if ($_POST["fname"] == '') {
    echo "Du must einen Vornamen haben";
    echo "<h1><a href='register.php'>Zurück</a></h1>";
    die();
}
if ($_POST["plz"] == '') {
    echo "Du must eine PLZ haben";
    echo "<h1><a href='register.php'>Zurück</a></h1>";
    die();
}

if ($_POST["hausnummer"] == '') {
    echo "Du must eine Hausnummer haben";
    echo "<h1><a href='register.php'>Zurück</a></h1>";
    die();
}
if ($_POST["street"] == '') {
    echo "Du must eine Straße haben";
    echo "<h1><a href='register.php'>Zurück</a></h1>";
    die();
}



//Email setzten
$email = $_POST["email"];

//Checken ob Email vergeben ist
$sql = "SELECT * FROM person WHERE email='$email'";
$result = $connection->getConnection()->query($sql);

if ($result->num_rows > 0) {
    echo "<h1>Email Schon vergeben</h1>";
    echo "<h1><a href='register.php'>Zurück</a></h1>";
    die();
}

//Checken ob email @ hat
if (strpos($email, "@") !== false) {

} else {
    echo "<h1>In einer Email muss ein @ Sein!</h1>";
    echo "<h1><a href='register.php'>Zurück</a></h1>";
    die();
}

// Checken ob Email exestirt
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

} else {
    echo "<h1>Die Email muss exstiren</h1>";
    echo "<h1><a href='register.php'>Zurück</a></h1>";
    die();
}
//Checken ob Domain Exestirt
if (filter_var($email, FILTER_VALIDATE_DOMAIN)) {

} else {
    echo "<h1>Die Email Domain muss exstiren</h1>";
    echo "<h1><a href='register.php'>Zurück</a></h1>";
    die();
}
//Checken ob Domain Exestirt über DNS
$domain = substr(strrchr($email, "@"), 1);
if (checkdnsrr($domain, "MX")) {

} else {
    echo "Die Domain existiert nicht.";
    echo "<h1><a href='register.php'>Zurück</a></h1>";
    die();
}

// Funktion, um einen zufälligen String zu generieren
function generateRandomString($length = 10)
{
    // Zeichen, die im zufälligen String enthalten sein sollen
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_+=';
    $randomString = '';
    // Generiere den zufälligen String
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// Aufruf der Funktion, um den zufälligen String zu generieren
$verify_id = generateRandomString();
$verify_id_hash = password_hash($verify_id, PASSWORD_DEFAULT);

// Password Verschlüsseln
$_password = $_POST["password"];
$_hash = password_hash($_password, PASSWORD_DEFAULT);
$verify = password_verify($_password, $_hash);
// Verifyen ob das Password richtig gespeichert wurde

if ($verify = true) {
    // Benutzer Abspeichern
    $connection = $connection->getConnection();
    $statement = $connection->prepare('INSERT INTO person (age, lastname, firstname, elo, plz, hausnummer, street, email, games, username, password, verify_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $statement->bind_param(
        'dssdssssdsss',
        $_POST["age"],
        $_POST["lname"],
        $_POST["fname"],
        $_POST["elo"],
        $_POST["plz"],
        $_POST["hausnummer"],
        $_POST["street"],
        $_POST["email"],
        $_POST["games"],
        $_POST["username"],
        $_hash,
        $verify_id_hash
    );
    $statement->execute();
}


$connection = new Connection;
$redirect = function () {
    header('Location: http://localhost:8000/index.php', true, 301);
    exit();
};

// Überprüfen, ob die POST-Variablen gesetzt und nicht leer sind
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$username = $_POST['username'];





// Konfiguration
$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->isSMTP(); // SMTP verwenden
$mail->Host = 'smtp.office365.com'; // SMTP-Server für Microsoft 365
$mail->SMTPAuth = true; // SMTP-Authentifizierung aktivieren
$mail->Username = 'maximilian.schwarz@igs-edigheim.de'; // SMTP-Benutzername (deine Microsoft 365 E-Mail-Adresse)
$mail->Password = 'Max2010!'; // SMTP-Passwort (dein Microsoft 365 Passwort)
$mail->SMTPSecure = 'tls'; // TLS-Verschlüsselung verwenden
$mail->Port = 587; // Port des SMTP-Servers für Microsoft 365

// Empfänger
$mail->setFrom('maximilian.schwarz@igs-edigheim.de', 'Maximilian Schwarz'); // Sender
$mail->addAddress($email, $lname . ", " . $fname); // Empfänger

// Inhalt
$mail->isHTML(true); // E-Mail als HTML formatieren
$mail->Subject = 'Chess Calculator Verification';
$mail->Body = 'Guten Tag' . $fname . ", "  . $username  . '<br>Gehen sie auf: <a href="verify.php">Hier</a> und verifiziren sie sich mit ihrem Verifikations Code: ' . $verify_id .
    '<br>Wenn sie sich nicht Regestirt haben wennen sie sich bitte an <a href=mailto:"chesscalculatorhelp@outlook.de"></a>' .
    '<br>(Link: http://localhost:8000/verify.php)';

// E-Mail senden
if (!$mail->send()) {
    echo 'E-Mail konnte nicht gesendet werden.';
    echo 'Fehler: ' . $mail->ErrorInfo;
} else {
    echo 'E-Mail wurde gesendet.';
    echo '<br>';
    echo 'Du hast 1 Woche zeit deine Email zu verifiziren!';
    echo "<h1><a href='index.php'>Homepage</a></h1>";
}



