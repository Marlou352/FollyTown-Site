// require les modules nécessaires
const express = require('express');
const axios = require('axios');
const bodyParser = require('body-parser');

const app = express();
app.use(bodyParser.json());

const DISCORD_WEBHOOK_URL = 'https://discord.com/api/webhooks/1390795522935230525/H1ZkG5XGb2fqhRWR50Gkk1CiJo69GIDLFAiWz38rQPznMQcCUGFjol1WVxFTAdqqAIJw';

app.post('/paypal-webhook', async (req, res) => {
  const event = req.body;

  // Vérifie que c'est un événement de paiement réussi (exemple : CHECKOUT.ORDER.APPROVED)
  if (event.event_type === 'CHECKOUT.ORDER.APPROVED') {
    const payerName = event.resource.payer.name.given_name || 'inconnu';
    const amount = event.resource.purchase_units[0].amount.value;
    const currency = event.resource.purchase_units[0].amount.currency_code;

    // Prépare le message Discord
    const message = {
      content: `✅ Achat PayPal reçu !\nAcheteur : **${payerName}**\nMontant : **${amount} ${currency}**`
    };

    // Envoie sur Discord
    try {
      await axios.post(DISCORD_WEBHOOK_URL, message);
      res.status(200).send('Notification envoyée à Discord');
    } catch (error) {
      console.error('Erreur en envoyant à Discord:', error);
      res.status(500).send('Erreur serveur');
    }
  } else {
    res.status(200).send('Événement ignoré');
  }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Serveur webhook PayPal en écoute sur le port ${PORT}`);
});
