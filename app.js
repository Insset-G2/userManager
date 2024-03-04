// Importer Express
const express = require('express');
const app = express();
const router = express.Router();
const path = require('path');


// Servir les fichiers statiques depuis le dossier public
app.use(express.static(path.join(__dirname, 'public')));
// Définir le dossier des vues
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'html');

function sendHTMLFile(res, fileName) {
    const filePath = path.join(__dirname, 'views', fileName);
    res.sendFile(filePath);
}

app.get('/', (req, res) => {
    sendHTMLFile(res, 'index.html');
});

app.listen(3000, () => {
console.log('starting server');
  console.log('Le serveur écoute sur le port 3000');
});