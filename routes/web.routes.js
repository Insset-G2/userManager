
const express = require('express');
const web = express.Router();

web.get('/', (req, res) => {
    const data = {
      status: 'Active',
    };
      res.render('index.html', { data: data });
  });

 web.get('/api-information', (req, res) => {
    res.render('swagger.html');
});

web.post('/find-user', (req, res) =>{
  
});

  // Exporter le routeur
module.exports = web;