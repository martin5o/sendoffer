<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

function getFirstName($fullName) {
    $parts = explode(" ", trim($fullName));
    return ucfirst($parts[0]);
}

$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$vorname = getFirstName($fullname);

// Angebote sammeln
$angeboteHTML = '';
for ($i = 1; $i <= 3; $i++) {
    $variante = $_POST["variant$i"] ?? '';
    $ergebnis = $_POST["result$i"] ?? '';
    $zusatz = $_POST["zusatz$i"] ?? '';

    if (!empty($variante) && !empty($ergebnis)) {
        if (empty($zusatz)) {
            $zusatz = '...';
        }

        $angeboteHTML .= <<<HTML
<p>
    Bei einer monatlichen Sparrate von <strong>{$variante}€</strong> erreichst du eine 
    <strong>Einmalauszahlung von {$ergebnis}€</strong> oder alternativ eine lebenslange 
    <strong>Zusatzrente von {$zusatz}€</strong>.
</p>
HTML;
    }
}

// E-Mail-Inhalt vorbereiten
$jahr = date('Y');

$body = <<<EOD
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>RentenDoc Angebot</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color:#f7f7f7;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f7f7; padding:30px 0;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
          
          <!-- Logo -->
          <tr>
            <td align="center" style="padding-top:20px;">
              <img src="https://rentendoc.de/images/logo.png" 
                   alt="RentenDoc Logo" style="display:block; max-width:200px;">
            </td>
          </tr>

          <!-- Inhalt -->
          <tr>
            <td style="padding:30px; font-size:16px; color:#333333; line-height:1.6;">

              <p>Hallo <strong>$vorname</strong>,</p>

              <p>vielen Dank für das angenehme Gespräch mit dir! 🙌</p>
              <p>Anbei findest du <strong>deinen persönlichen Lösungsvorschlag</strong>:</p>

              $angeboteHTML

              <p><strong>Deine Vorteile auf einen Blick:</strong></p>

              <ul style="padding-left:20px; margin:24px 0;">
                <li style="margin-bottom:12px;">Flexible Laufzeit – Auszahlung oder Rente, wann immer du willst</li>
                <li style="margin-bottom:12px;">Teilentnahmen jederzeit möglich</li>
                <li style="margin-bottom:12px;">Kostenfreie Änderung deiner Fondsanlage</li>
                <li style="margin-bottom:12px;">Sparrate jederzeit anpassbar</li>
                <li style="margin-bottom:12px;">Beitragspause möglich</li>
                <li style="margin-bottom:12px;">Sondereinzahlungen kostenfrei möglich</li>
                <li>und vieles mehr</li>
              </ul>

              <p>Wir freuen uns schon auf unser nächstes Gespräch! 🚀<br>
              Du wirst begeistert sein! 😉</p>

              <p>Viele Grüße<br>  
              Dein <strong>RentenDoc-Team</strong></p>

            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td align="center" style="background:#f0f0f0; padding:20px; font-size:12px; color:#666; line-height:1.5;">
              © $jahr RentenDoc – Alle Rechte vorbehalten.<br>
              Du kannst jederzeit direkt auf diese E-Mail antworten.
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
EOD;



// E-Mail senden
$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';

try {
    // SMTP-Einstellungen für One.com
    $mail->isSMTP();
    $mail->Host = 'mail.praedurion.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'info@rentendoc.de';
    $mail->Password = 'Rentendoc123!';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('info@rentendoc.de', 'rentendoc');
    $mail->addAddress($email);
    $mail->addBCC('info@rentendoc.de');

    $mail->isHTML(true);
    $mail->Subject = 'Dein persönliches RentenDoc Angebot 🫶🏼';
    $mail->Body    = $body;

    // Angebot-PDFs anhängen
    for ($i = 1; $i <= 3; $i++) {
        if (isset($_FILES["offer$i"]) && $_FILES["offer$i"]['error'] === UPLOAD_ERR_OK) {
            $mail->addAttachment($_FILES["offer$i"]['tmp_name'], $_FILES["offer$i"]['name']);
        }
    }

    // Feste Broschüre anhängen
    $broschuerePath = __DIR__ . '/infobroschuere.pdf';
    if (file_exists($broschuerePath)) {
        $mail->addAttachment($broschuerePath);
    } else {
        throw new Exception("Die Datei infobroschuere.pdf wurde nicht gefunden.");
    }

    $mail->send();
   
    header('Location: fertig.html');
    exit(); 
} catch (Exception $e) {
    echo "Fehler beim Senden der E-Mail: {$mail->ErrorInfo}";
}

?>
