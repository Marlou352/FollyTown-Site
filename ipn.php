<?php
// Lire les données POST de PayPal
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = [];
foreach ($raw_post_array as $keyval) {
    $keyval = explode('=', $keyval);
    if (count($keyval) == 2)
        $myPost[$keyval[0]] = urldecode($keyval[1]);
}

// Préparer la requête de validation à PayPal
$req = 'cmd=_notify-validate';
foreach ($myPost as $key => $value) {
    $value = urlencode($value);
    $req .= "&$key=$value";
}

// Envoyer la requête pour vérifier avec PayPal
$ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
$res = curl_exec($ch);
curl_close($ch);

if (strcmp($res, "VERIFIED") == 0) {
    // Paiement validé par PayPal
    $item_name = $_POST['item_name'] ?? '';
    $payment_status = $_POST['payment_status'] ?? '';
    $payer_email = $_POST['payer_email'] ?? '';
    $mc_gross = $_POST['mc_gross'] ?? '';
    
    if ($payment_status == "Completed") {
        // Log ou actions à faire après achat validé
        file_put_contents('ipn_log.txt', date('Y-m-d H:i:s') . " - Achat validé: $item_name - $mc_gross € par $payer_email\n", FILE_APPEND);
        // Ici tu peux ajouter envoi Discord, débloquer kit etc.
    }
} else {
    // Paiement non validé
    file_put_contents('ipn_log.txt', date('Y-m-d H:i:s') . " - Achat NON validé.\n", FILE_APPEND);
}
?>
