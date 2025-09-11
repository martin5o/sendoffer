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
    Bei einer monatlichen Sparrate von <strong>{$variante}€</strong> erreicht dein Kind eine 
    <strong>Einmalauszahlung von {$ergebnis}€</strong> oder alternativ eine lebenslange 
    <strong>Zusatzrente von {$zusatz}€</strong>.
</p>
HTML;
    }
}

// E-Mail-Inhalt vorbereiten
$body = <<<EOD
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
            line-height: 1.5; 
            color: #333; 
            max-width: 650px; 
            margin: 0 auto; 
            padding: 28px 20px;
        }
        .divider {
            height: 1px;
            background: #eee;
            margin: 32px 0;
        }
        .signature {
            margin-top: 32px;
        }
        .footer {
            font-size: 10px;
            color: #777;
            line-height: 1.5;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <p><strong>Hi $vorname</strong>,</p>
    
    <p>Vielen Dank für das entspannte Telefonat mit Dir!</p>
    
    <p>Anbei dein Lösungsvorschlag für deinen Kindersparer:</p>
    
    $angeboteHTML
    
    <p><strong>Deine Vorteile:</strong></p>
    <ul style="padding-left: 20px; margin: 24px 0;">
        <li style="margin-bottom: 12px;">Keine feste Laufzeit – nimm dir alles auf einmal oder eine mtl. Rente wann du willst (mindestens 5 Jahre)</li>
        <li style="margin-bottom: 12px;">Jederzeitige Änderung der Fondsanlage kostenfrei möglich</li>
        <li style="margin-bottom: 12px;">Jederzeitige Entnahme von bis zu 50% des Kapitals möglich</li>
        <li style="margin-bottom: 12px;">Jederzeit Änderung der Sparbeträge möglich</li>
        <li style="margin-bottom: 12px;">Beitragssparpause möglich</li>
        <li>uvm.</li>
    </ul>
    
    <p>Wir freuen uns sehr über unser nächstes Meeting! 🚀<br>
    Darfst uns dann danken! 😊</p>
    
    <div class="signature">
        <p>Liebe Grüße<br>
        Dein Kindersparer Team</p>
        
        <p>
            <strong>Kindersparer</strong><br>
            Langenpreisinger Str. 59<br>
            80995 München<br><br>
            📧 info@kindersparer.de<br>
            🌐 <a href="https://www.kindersparer.de" style="color: #0070c9; text-decoration: none;">www.pflegerhelden.de</a>
        </p>
    </div>
    
    <div class="divider"></div>
    
    <div class="footer">
        <p>Angaben gemäß § 5 TMG:</p>
        
        <p>Vertreten durch:<br>
        Martin Dominik Stacherczak<br>
        Langenpreisinger Str. 59<br>
        80995 München<br>
        Telefon: +49 52198994073<br>
        E-Mail: info@kindersparer.de</p>
        
        <p>Berufsbezeichnung<br>
        Versicherungsmakler mit Erlaubnis nach § 34d Abs. 1 GewO</p>
        
        <p>Erlaubnis- und Registerbehörde gemäß § 11 VersVermV:<br>
        Industrie- und Handelskammer für München und Oberbayern<br>
        Max-Joseph-Str. 2<br>
        80333 München<br>
        www.muenchen.ihk.de</p>
        
        <p>Registrierungsnummer gemäß § 34d GewO:<br>
        D-8CJW-1NLO9-12</p>
        
        <p>Vermittlerregister:<br>
        Deutsche Industrie- und Handelskammer (DIHK)<br>
        Breite Straße 29<br>
        10178 Berlin<br>
        Telefon: 0180 600 58 50 (20 Cent/Anruf aus dem dt. Festnetz)<br>
        www.vermittlerregister.info<br>
        E-Mail: vr@dihk.de</p>
        
        <p>Berufsrechtliche Regelungen:<br>
        § 34d Gewerbeordnung (GewO)<br>
        §§ 59–68 Versicherungsvertragsgesetz (VVG)<br>
        Verordnung über die Versicherungsvermittlung und -beratung (VersVermV)</p>
        
        <p>Diese Regelungen können eingesehen werden unter:<br>
        www.gesetze-im-internet.de</p>
        
        <p>Beteiligungen:<br>
        Es bestehen keine direkten oder indirekten Beteiligungen von über 10 % an oder von Versicherungsunternehmen.</p>
        
        <p>Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV:<br>
        Martin Dominik Stacherczak<br>
        Langenpreisinger Str. 59<br>
        80995 München<br>
        E-Mail: info@kindersparer.de</p>
    </div>
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
    $mail->Username = 'info@kindersparer.de';
    $mail->Password = 'Kindersparer123!';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('info@kindersparer.de', 'Kindersparer');
    $mail->addAddress($email);
    $mail->addBCC('angebot@kindersparer.de');

    $mail->isHTML(true);
    $mail->Subject = 'Dein persönliches Kindersparer Angebot 🫶🏼';
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
