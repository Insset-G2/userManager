// Importer Express
const express = require('express');
const api = express.Router();

// POST pour ajouter un utilisateur
api.post('/api/users/create-user', (req, res) => {
    const formData = req.body;

     if (!formData.password || !formData.email) {
        return res.status(400).json({ error: 'ERROR' });
    }else{
        var options = {
            'method': 'POST',
            'hostname': 'onzecord-mail-ynl52tk6za-ey.a.run.app',
            'path': '/send_email',
            'headers': {
              'Content-Type': 'application/json'
            },
            'maxRedirects': 20
          };
          
          var req = https.request(options, function (res) {
            var chunks = [];
          
            res.on("data", function (chunk) {
              chunks.push(chunk);
            });
          
            res.on("end", function (chunk) {
              var body = Buffer.concat(chunks);
              console.log(body.toString());
            });
          
            res.on("error", function (error) {
              console.error(error);
            });
          });
          
          var postData = JSON.stringify({
            "sender": "onzecordmail@gmail.com",
            "to": formData.email,
            "subject": "Confirmation de création de compte sur OnzeCord",
            "message": "Nous sommes ravis de vous informer que votre compte OnzeCord a été créé avec succès. Vous pouvez désormais profiter de tous les avantages offerts par notre plateforme."
          });
          
          req.write(postData);
          req.end();
    }
    // Faire quelque chose avec les données du formulaire
    res.json({ message: 'SUCCESS' });
});

// POST pour connecter l'utilisateur
api.post('/api/users/signin', (req, res) => {
    const formData = req.body;

     if (!formData.password || !formData.email) {
        return res.status(400).json({ error: 'ERROR' });
    }
    // Faire quelque chose avec les données du formulaire
    console.log(formData);
    res.json({ message: 'SUCCESS' });
});


// POST pour modifier un utilisateur
api.post('/api/users/update-user', (req, res) =>{
    const formData = req.body;

    if (!formData.email) {
        return res.status(400).json({ error: 'ERROR' });
    }
    // Faire quelque chose avec les données du formulaire
    console.log(formData);
    res.json({ message: 'SUCCESS' });
});

// POST pour supprimer un utilisateur
api.post('/api/users/delete-user', (req, res) => {
    const formData = req.body;
    // Faire quelque chose avec les données du formulaire
    console.log(formData);
    res.json({ message: 'SUCCESS' });
});

api.get('/api/users/count-user', (req, res) => {
    const formData = req.body;

    if (!formData.name || !formData.email) {
        return res.status(400).json({ error: 'ERROR' });
    }
    // Faire quelque chose avec les données du formulaire
    console.log(formData);
    res.json({ message: 'SUCCESS' });
});

api.post('/api/users/information-user', (req, res) => {
    const formData = req.body;
    // Faire quelque chose avec les données du formulaire
    console.log(formData);
    res.json({ message: 'SUCCESS' });
});

// Exporter le routeur
module.exports = api;