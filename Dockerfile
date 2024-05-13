# Utiliser l'image de base Node.js version 20
FROM node:20

# Créer un répertoire de travail dans le conteneur
WORKDIR /app

# Copier le package.json et le package-lock.json dans le répertoire de travail
COPY package*.json ./

# Installer les dépendances
RUN npm install

# Copier le reste des fichiers de l'application dans le répertoire de travail
COPY . .

# Exposer le port sur lequel l'application s'exécute
EXPOSE 62580

# Commande par défaut pour démarrer l'application
CMD ["npm", "start"]