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
    // Überschriften / h2 / Formulare
    "Stromvergleich" => "Porównanie prądu",
    "Heizstromvergleich" => "Porównanie prądu grzewczego",
    "Gewerbestromvergleich" => "Porównanie prądu dla firm",
    "Gasvergleich" => "Porównanie gazu",
    "Gewerbegasvergleich" => "Porównanie gazu dla firm",
    "DSL-Vergleich" => "Porównanie internetu",
    "Handytarife" => "Taryfy telefoniczne",
    "Mobiles Internet" => "Internet mobilny",
    "KFZ-Versicherungsvergleich" => "Porównanie ubezpieczeń samochodowych",
    "Ratenkredit-Vergleich" => "Porównanie kredytów ratalnych",
    "Versicherungen" => "Ubezpieczenia",

    // Buttons
    "Jetzt vergleichen!" => "Porównaj teraz!",

    // Labels / Placeholder
    "Ihre Postleitzahl" => "Twój kod pocztowy",
    "Ort" => "Miasto",
    "Personenzahl oder kWh / Jahr" => "Liczba osób lub kWh / rok",
    "Wohnungsgröße oder kWh/Jahr" => "Powierzchnia mieszkania lub kWh/rok",
    "Geschwindigkeit auswählen" => "Wybierz prędkość",
    "Vertragslaufzeit" => "Czas trwania umowy",
    "Datenvolumen" => "Pakiet danych",
    "Kreditbetrag (Euro)" => "Kwota kredytu (Euro)",
    "Kreditlaufzeit" => "Okres kredytowania",
    "Verwendung" => "Cel kredytu",

    // Select-Optionen
    "Alle" => "Wszystkie",
    "unbegrenzt" => "bez limitu",
    "1 Monat" => "1 miesiąc",
    "24 Monate" => "24 miesiące",
    "Freie Verwendung" => "Dowolny cel",
    "Umschuldung / Kreditablösung" => "Refinansowanie / spłata kredytu",
    "Auto / Motorrad" => "Samochód / Motocykl",
    "Modernisierung / Renovierung" => "Modernizacja / Remont",
    "Ausgleich Girokonto" => "Wyrównanie konta",
    "Gewerbe / Selbstständige" => "Biznes / Samozatrudnienie",

    // Versicherungstypen
    "Hausrat" => "Ubezpieczenie mienia",
    "Wohngebäude" => "Ubezpieczenie budynku mieszkalnego",
    "Tierhalterhaftpflicht" => "Ubezpieczenie odpowiedzialności właściciela zwierząt",
    "Rechtsschutz" => "Ubezpieczenie ochrony prawnej",
    "Privathaftpflicht" => "Ubezpieczenie OC prywatne",

    // Teaser / Bottom
    "Tarife vergleichen" => "Porównaj taryfy",
    "Direkt abschließen" => "Zamów od razu",
    "Clever sparen" => "Oszczędzaj mądrze",

    // Teaser-Slogans
    "Jetzt sparen!" => "Oszczędzaj teraz!",
    "Stromanbieter vergleichen beim Testsieger" => "Porównaj dostawców prądu u lidera testów",
    "Gas vergleichen beim Testsieger" => "Porównaj gaz u lidera testów",
    "Internet-Vergleich" => "Porównanie internetu",
    "Mit Highspeed surfen zum besten Preis" => "Surfuj z dużą prędkością w najlepszej cenie",
    "Handytarife online vergleichen!" => "Porównaj taryfy telefoniczne online!",
    "Günstige Deals & Top-Handytarife" => "Tanie oferty & najlepsze taryfy",
    "Für mehr Spaß beim Surfen!" => "Więcej radości podczas surfowania!",
    "Mobil surfen: Datentarife im Vergleich" => "Mobilne surfowanie: porównanie pakietów danych",
    "Der beste Schutz für Ihr Auto" => "Najlepsza ochrona dla Twojego samochodu",
    "KFZ-Versicherung bis zu 78% günstiger" => "Ubezpieczenie samochodu nawet do 78% taniej",
    "Schnell und 100% kostenlos" => "Szybko i w 100% bezpłatnie",
    "Günstige Kredite vom Testsieger" => "Tanie kredyty od lidera testów",
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
