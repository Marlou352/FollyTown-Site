<?php
// Ton webhook Discord
$discordWebhookUrl = 'https://discord.com/api/webhooks/1390786929011851395/_UaBhrY3SVaVW3pNzsiRqj9AFnuclb4fG79ndagtash7YFcKuDbg73up4x-coSUGp3eC';

$pseudo = trim($_POST['pseudo'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$pseudo || !$message) {
    echo "Merci de remplir tous les champs.";
    exit;
}

$content = "**Nouvelle preuve d'achat reçue**\n";
$content .= "Pseudo : $pseudo\n";
$content .= "Message : $message\n";
$content .= "Date : " . date('Y-m-d H:i:s');

$json_data = json_encode([
    "content" => $content
]);

$ch = curl_init($discordWebhookUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    echo "Preuve envoyée avec succès !";
} else {
    echo "Erreur lors de l'envoi.";
}
?>
