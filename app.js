// Importer Express
const express = require('express');
const http = require('http');
const nunjucks = require('nunjucks');
const bodyParser = require('body-parser');
const path = require('path');

const app = express();

// Importer les fichiers de routes
const api = require('./routes/api.routes');
const web = require('./routes/web.routes');
const database = require('./routes/database.routes');
const onzecord = require('./routes/onzecord.routes');

// Utiliser body-parser middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// Servir les fichiers statiques depuis le dossier public
app.use(express.static(path.join(__dirname, 'public')));
// Définir le dossier des vues
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'njk');

// Configuration de Nunjucks
nunjucks.configure('views', {
    autoescape: true,
    express: app
});

// Utiliser le routeur
app.use(api);
app.use(web);
app.use(database);
app.use(onzecord);

const port = process.env.PORT || 80;

// Créer un serveur HTTP
const server = http.createServer(app);

// Démarrer le serveur sur le port spécifié
server.listen(port, () => {
  console.log('starting server');
    console.log(`Le serveur est démarré sur le port ${port}`);
});