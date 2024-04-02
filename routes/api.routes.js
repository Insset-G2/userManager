// Importer Express
const express = require('express');
const api = express.Router();

// POST pour ajouter un utilisateur
api.post('/add-user', (req, res) => {
    const formData = req.body;

     if (!formData.name || !formData.email) {
        return res.status(400).json({ error: 'ERROR' });
    }
    // Faire quelque chose avec les données du formulaire
    console.log(formData);
    res.json({ message: 'SUCCESS' });
});

// POST pour modifier un utilisateur
api.post('/update-user', (req, res) =>{
    const formData = req.body;

    if (!formData.name || !formData.email) {
        return res.status(400).json({ error: 'ERROR' });
    }
    // Faire quelque chose avec les données du formulaire
    console.log(formData);
    res.json({ message: 'SUCCESS' });
});

// POST pour supprimer un utilisateur
api.post('/delete-user', (req, res) => {
    const formData = req.body;

    if (!formData.name || !formData.email) {
        return res.status(400).json({ error: 'ERROR' });
    }
    // Faire quelque chose avec les données du formulaire
    console.log(formData);
    res.json({ message: 'SUCCESS' });
});

api.post('/count-user', (req, res) => {
    const formData = req.body;

    if (!formData.name || !formData.email) {
        return res.status(400).json({ error: 'ERROR' });
    }
    // Faire quelque chose avec les données du formulaire
    console.log(formData);
    res.json({ message: 'SUCCESS' });
});

api.post('/user-info', (req, res) => {
    const formData = req.body;

    if (!formData.name || !formData.email) {
        return res.status(400).json({ error: 'ERROR' });
    }
    // Faire quelque chose avec les données du formulaire
    console.log(formData);
    res.json({ message: 'SUCCESS' });
});

// Exporter le routeur
module.exports = api;