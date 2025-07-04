<?php
// Ton URL webhook Discord ici
$discordWebhookUrl = 'https://discord.com/api/webhooks/1390786929011851395/_UaBhrY3SVaVW3pNzsiRqj9AFnuclb4fG79ndagtash7YFcKuDbg73up4x-coSUGp3eC';

$message = "**Test de notification Discord !**\nDate : " . date('Y-m-d H:i:s');

$json_data = json_encode([
    "content" => $message
]);

$ch = curl_init($discordWebhookUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo "Notification envoyée !";
?>
