// Importer Express
const express = require('express');
const https = require('https');
const http = require('http');
const crypto = require('crypto');
const fs = require('fs');
const csrf = require('csurf');
const { Console } = require('console');
const api = express.Router();

const APIDatabaseIP = "https://my-api-platform-r7v4dtv3ya-od.a.run.app";

// POST pour ajouter un utilisateur
api.post('/api/users/create-user', async (req, res) => {
try {
        const formData = req.body;

        if (formData._csrf === req.session.csrfSecret) {
            
        if (!formData.password || !formData.email) {
            return res.status(400).json({ message: 'ERROR' });
        }
        
        var hashPassword = crypto.createHash('sha256').update(formData.password).digest('hex');
        var currentDate = new Date(Date.now());
        var formattedDate = `${currentDate.getFullYear()}-${(currentDate.getMonth() + 1).toString().padStart(2, '0')}-${currentDate.getDate().toString().padStart(2, '0')}T${currentDate.getHours().toString().padStart(2, '0')}:${currentDate.getMinutes().toString().padStart(2, '0')}:${currentDate.getSeconds().toString().padStart(2, '0')}`;

        const postDb = JSON.stringify({
            "last_name": formData.email.match(/^([^@]*)@/)[1],
            "first_name": formData.email.match(/^([^@]*)@/)[1],
            "email": formData.email,
            "password": hashPassword,
            "avatar": "neutral",
            "description": "null",
            "created_at": formattedDate,
            "status": 1,
          });

        const postEmail = JSON.stringify({
            "to": formData.email,
            "subject": "Confirmation de création de compte sur OnzeCord",
            "message": "Nous sommes ravis de vous informer que votre compte OnzeCord a été créé avec succès. Vous pouvez désormais profiter de tous les avantages offerts par notre plateforme."
        });

        const optionsEmail = {
            hostname: 'onzecord-mail-ynl52tk6za-ey.a.run.app',
            path: '/send_email',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Content-Length': Buffer.byteLength(postEmail)
            }
        };

        const optionsDb = {
            'method': 'POST',
            'hostname': APIDatabaseIP,
            'path': '/users',
            headers: {
                'Content-Type': 'application/json',
                'Content-Length': Buffer.byteLength(postDb)
            }
          };

        const sendToDbUser = await postDataHttp(optionsDb, postDb);
        const sendToEmail = await postDataHttps(optionsEmail, postEmail);

        res.json({ message: 'SUCCESS', email: formData.email });
        }
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Internal Server Error' });
    }
});

// POST pour connecter l'utilisateur
api.post('/api/users/signin',async (req, res) => {
    const formData = req.body;
    if (formData._csrf === req.session.csrfSecret) {

        if (!formData.password || !formData.email) {
            return res.status(400).json({ error: 'ERROR' });
        }else{
              var options = {
                'method': 'GET',
                'url': APIDatabaseIP + '?users=' + formData.email,
              };
              request(options, function (error, response) {
                if (error) throw new Error(error);
                res.json({ message: 'SUCCESS', data: response.body });
              });
        }
    }
});


// POST pour modifier un utilisateur
api.post('/api/users/update-user', (req, res) =>{
    const formData = req.body;

    if (!formData.email) {
        return res.status(400).json({ error: 'ERROR' });
    }else{

    }
    // Faire quelque chose avec les données du formulaire
    console.log(formData);
    res.json({ message: 'SUCCESS' });
});

// POST pour supprimer un utilisateur
api.post('/api/users/delete-user', (req, res) => {
    const formData = req.body;
    var options = {
        'method': 'DELETE',
        'url': APIDatabaseIP + '?users=' + formData.id,
      };
      request(options, function (error, response) {
        if (error) throw new Error(error);
        res.json({ message: 'SUCCESS', responses: response.body });
      });
});

api.get('/api/users/count-user', (req, res) => {
    const formData = req.body;

    if (!formData.name || !formData.email) {
        return res.status(400).json({ error: 'ERROR' });
    }
   
    var options = {
        'method': 'GET',
        'url': APIDatabaseIP + 'users',
      };
      request(options, function (error, response) {
        if (error) throw new Error(error);
        res.json({ message: 'SUCCESS', responses: response.body });
      });
});

api.post('/api/users/information-user', (req, res) => {
    const formData = req.body;
   
    var options = {
        'method': 'GET',
        'url': APIDatabaseIP + '?users=' + formData.id,
      };
      request(options, function (error, response) {
        if (error) throw new Error(error);
        res.json({ message: 'SUCCESS', data: response.body });
      });
});

function postDataHttps(options, postData) {
    return new Promise((resolve, reject) => {
        const req = https.request(options, (res) => {
            let body = '';
            res.on('data', (chunk) => {
                body += chunk;
            });
            res.on('end', () => {
                resolve(body);
            });
        });

        req.on('error', (error) => {
            reject(error);
        });

        req.write(postData);
        req.end();
    });
}

function postDataHttp(options, postData) {
    return new Promise((resolve, reject) => {
        var req = http.request(options, function (res) {
            var chunks = [];
          
            res.on("data", function (chunk) {
              chunks.push(chunk);
            });
          
            res.on("end", function (chunk) {
              var body = Buffer.concat(chunks);
              resolve(body.toString());
            });
          
            res.on("error", function (error) {
              console.error(error);
              reject(error);
            });
          });
                
          req.write(postData);
          req.end();
    });
}

// Exporter le routeur
module.exports = api;