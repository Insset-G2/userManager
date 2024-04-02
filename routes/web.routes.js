
const express = require('express');
const web = express.Router();

web.get('/', (req, res) => {
    const data = {
      status: 'Active',
    };
      res.render('index.html', { data: data });
  });

  // Exporter le routeur
module.exports = web;