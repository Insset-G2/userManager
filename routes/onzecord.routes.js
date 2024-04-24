// Importer Express
const express = require('express');
const csrf = require('csurf');
const onzecord = express.Router();

onzecord.get('/onzecord', (req, res) => {
      var token = req.csrfToken()
      req.session.csrfSecret = token;
      res.render('onzecord-login.html', {csrfToken: token });
  });

  onzecord.get('/onzecord-create-account', (req, res) => {
    var token = req.csrfToken()
    req.session.csrfSecret = token;
    res.render('onzecord-create-account.html', {csrfToken: token});
});
// Exporter le routeur
module.exports = onzecord;