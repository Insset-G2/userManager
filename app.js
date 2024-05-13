// Importer Express
const express = require('express');
const session = require('express-session');
const csrf = require('csurf');
const http = require('http');
const crypto = require('crypto');
const https = require('https');
const fs = require('fs');
const nunjucks = require('nunjucks');
const bodyParser = require('body-parser');
const cookieParser = require('cookie-parser');
const path = require('path');

const app = express();

const options = {
  key: fs.readFileSync(path.resolve('./key.pem')),
  cert: fs.readFileSync(path.resolve('./cert.pem'))
};

// Importer les fichiers de routes
const api = require('./routes/api.routes');
const web = require('./routes/web.routes');
const database = require('./routes/database.routes');
const onzecord = require('./routes/onzecord.routes');

// Middlewares
app.use(cookieParser());
// Utiliser body-parser middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.use(csrf({ cookie: true }));

app.use(session({
  secret: crypto.randomBytes(64).toString('hex'),
  resave: false,
  saveUninitialized: true
}));

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

const port = process.env.PORT || 62580;

// Créer un serveur HTTPS
// const server = http.createServer(app);
const server = https.createServer(options, app);

// Démarrer le serveur sur le port spécifié
server.listen(port, () => {
  console.log('starting server');
    console.log(`Le serveur est démarré sur le port ${port}`);
});