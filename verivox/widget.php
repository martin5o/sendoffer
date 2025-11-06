<?php
// ----------------------------
// 1. Original Widget URL
// ----------------------------
$widgetUrl = "https://partner.vxcp.de/ffIn9CaJQO9SGy2p/MAK211620/widget.php?maklerSite=true";

// ----------------------------
// 2. Übersetzungen Deutsch => Polnisch
// ----------------------------
// Statische Texte
$translations = [
    "Berechnen" => "Oblicz",
    "Ihre Postleitzahl" => "Twój kod pocztowy",
    "Personenzahl oder kWh / Jahr" => "Liczba osób lub kWh / rok",
    "kWh / Jahr" => "kWh / rok",
    "Wohnungsgröße oder kWh/Jahr" => "Rozmiar mieszkania lub kWh/rok",
    "Jetzt vergleichen!" => "Porównaj teraz!",
    "Vertragslaufzeit" => "Okres umowy",
    "Datenvolumen" => "Dane mobilne",
    "Geschwindigkeit auswählen" => "Wybierz prędkość",
    "Freie Verwendung" => "Dowolne przeznaczenie",
    "Kreditbetrag (Euro)" => "Kwota kredytu (Euro)",
    "Kreditlaufzeit" => "Okres kredytowania",
    "Stromvergleich" => "Porównanie prądu",
    "Gasvergleich" => "Porównanie gazu",
    "DSL-Vergleich" => "Porównanie internetu",
    "Handytarife" => "Taryfy telefoniczne",
    "Mobiles Internet" => "Internet mobilny",
    "KFZ-Versicherungsvergleich" => "Porównanie ubezpieczeń samochodowych",
    "Ratenkredit-Vergleich" => "Porównanie kredytów ratalnych",
    "Versicherungen" => "Ubezpieczenia",
    "Tarife vergleichen" => "Porównaj oferty",
    "Direkt abschließen" => "Zamów od razu",
    "Clever sparen" => "Oszczędzaj mądrze",
    "ein Service der Verivox GmbH" => "usługa Verivox GmbH",
    "Verträge hier kündigen" => "Wypowiedź umów tutaj",
    "Datenschutzbestimmungen" => "Polityka prywatności",
    "Jetzt sparen!" => "Oszczędzaj teraz!",
    "Stromanbieter vergleichen beim Testsieger" => "Porównaj dostawców energii z liderem testu",
    "Gas vergleichen beim Testsieger" => "Porównaj gaz z liderem testu",
    "Internet-Vergleich" => "Porównanie internetu",
    "Mit Highspeed surfen zum besten Preis" => "Surfuj z wysoką prędkością po najlepszej cenie",
    "Handytarife online vergleichen!" => "Porównaj taryfy telefoniczne online!",
    "Günstige Deals & Top-Handytarife" => "Tanie oferty i najlepsze taryfy",
    "Für mehr Spaß beim Surfen!" => "Więcej przyjemności podczas surfowania!",
    "Mobil surfen: Datentarife im Vergleich" => "Mobilne surfowanie: porównanie taryf danych",
    "Der beste Schutz für Ihr Auto" => "Najlepsza ochrona Twojego samochodu",
    "KFZ-Versicherung bis zu 78% günstiger" => "Ubezpieczenie samochodu nawet 78% taniej",
    "Schnell und 100% kostenlos" => "Szybko i 100% za darmo",
    "Günstige Kredite vom Testsieger" => "Tanie kredyty od lidera testu",
    "Versicherungen vergleichen" => "Porównaj ubezpieczenia",
    "Günstig und sorgenfrei versichert" => "Tanie i bezpieczne ubezpieczenie"
];

// Dynamische Texte in data-Attributen (h4, li)
$dataAttributes = [
    "electricity" => "Porównanie prądu",
    "heatingelectricity" => "Porównanie prądu grzewczego",
    "gas" => "Porównanie gazu",
    "broadband" => "Porównanie internetu",
    "mobile" => "Taryfy telefoniczne",
    "mobilebroadband" => "Internet mobilny",
    "loan" => "Porównanie kredytów ratalnych",
    "carinsurance" => "Porównanie ubezpieczeń samochodowych",
    "insurances" => "Ubezpieczenia"
];

// ----------------------------
// 3. Original Widget laden
// ----------------------------
$widgetHtml = file_get_contents($widgetUrl);

// ----------------------------
// 4. Statische Texte ersetzen
// ----------------------------
foreach ($translations as $de => $pl) {
    $widgetHtml = str_replace($de, $pl, $widgetHtml);
}

// ----------------------------
// 5. Dynamische data-Attribute Texte ersetzen
// ----------------------------
foreach ($dataAttributes as $attr => $pl) {
    // h4 Überschriften
    $widgetHtml = preg_replace_callback(
        '/(<h4[^>]*data-' . preg_quote($attr, '/') . '=")([^"]*)(")/',
        function($matches) use ($pl) {
            return $matches[1] . htmlspecialchars($pl) . $matches[3];
        },
        $widgetHtml
    );

    // li innerhalb c_headline
    $widgetHtml = preg_replace_callback(
        '/(<ul class="c_headline"[^>]*data-' . preg_quote($attr, '/') . '=")([^"]*)(")/',
        function($matches) use ($pl) {
            // Setze einfachen li Text, kann bei Bedarf erweitert werden
            $content = '<li class="vxcp_c_uppercase">' . htmlspecialchars($pl) . '</li>';
            return $matches[1] . htmlspecialchars($content) . $matches[3];
        },
        $widgetHtml
    );
}

// ----------------------------
// 6. Übersetztes HTML ausgeben
// ----------------------------
echo $widgetHtml;
?>
