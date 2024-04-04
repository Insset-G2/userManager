// Importer Express
const express = require('express');
const api = express.Router();

// POST pour ajouter un utilisateur
api.post('/api/users/create-user', (req, res) => {
    const formData = req.body;
     if (!formData.password || !formData.email) {
        return res.status(400).json({ error: 'ERROR' });
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

api.post('/api/users/count-user', (req, res) => {
    const formData = req.body;

    if (!formData.name || !formData.email) {
        return res.status(400).json({ error: 'ERROR' });
    }
    // Faire quelque chose avec les données du formulaire
    console.log(formData);
    res.json({ message: 'SUCCESS' });
});

api.post('/api/users/user-info', (req, res) => {
    const formData = req.body;
    // Faire quelque chose avec les données du formulaire
    console.log(formData);
    res.json({ message: 'SUCCESS' });
});

// Exporter le routeur
module.exports = api;