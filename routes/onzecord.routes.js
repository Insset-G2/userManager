// Importer Express
const express = require('express');
const onzecord = express.Router();

onzecord.get('/onzecord', (req, res) => {
      res.render('onzecord-login.html');
  });

  onzecord.get('/onzecord-create-account', (req, res) => {
    res.render('onzecord-create-account.html');
});
// Exporter le routeur
module.exports = onzecord;