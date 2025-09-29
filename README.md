# Portfolio Vincent Ferry

Un portfolio personnel moderne développé avec Symfony 7.3, TailwindCSS et Webpack Encore.

## 🚀 Fonctionnalités

- **Design moderne** : Interface sobre et technologique avec mode clair/sombre
- **Responsive** : Adapté pour mobile, tablette et desktop
- **Multilingue** : Support français et anglais (i18n)
- **Animations fluides** : Transitions CSS et animations JavaScript discrètes
- **Formulaire de contact** : Avec validation et envoi d'email
- **Back-office** : Gestion des projets via EasyAdmin
- **SEO optimisé** : Meta tags, Open Graph, Twitter Cards

## 🛠️ Technologies utilisées

### Backend
- **Symfony 7.3** - Framework PHP
- **Doctrine ORM** - Base de données
- **Twig** - Moteur de templates
- **Symfony Mailer** - Envoi d'emails
- **EasyAdmin** - Interface d'administration

### Frontend
- **TailwindCSS** - Framework CSS utilitaire
- **Webpack Encore** - Bundler d'assets
- **JavaScript Vanilla** - Interactions client
- **CSS3** - Animations et transitions

## 🚧 Gestion de la maintenance

Le site dispose d'un système de maintenance intégré pour les mises à jour en production.

### Activer le mode maintenance
```bash
php bin/console app:maintenance on
```

### Désactiver le mode maintenance
```bash
php bin/console app:maintenance off
```

### Vérifier l'état de la maintenance
```bash
php bin/console app:maintenance status
```

### Fonctionnement
- **Commande Symfony** : Utilise le système de commandes intégré avec gestion d'erreurs
- **Activation** : Crée un fichier `.maintenance` et modifie `.htaccess` pour rediriger vers `/maintenance.html`
- **Page de maintenance** : Design moderne avec actualisation automatique toutes les 30 secondes
- **Désactivation** : Supprime le fichier de maintenance et restaure le `.htaccess` original
- **Sécurité** : Les assets (images, CSS, JS) restent accessibles pendant la maintenance
- **Interface claire** : Messages colorés et tableaux d'informations avec SymfonyStyle

### Notes importantes
- ✅ **En production** (Apache/Nginx) : Fonctionne automatiquement via `.htaccess`
- ⚠️ **En développement** : Le serveur PHP (`php -S`) ne traite pas `.htaccess` - tester manuellement `/maintenance.html`

## 📦 Installation

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- Node.js et npm
- SQLite (ou autre base de données)

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone https://github.com/VincentFerry/portfolio.git
cd portfolio
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Installer les dépendances Node.js**
```bash
npm install
```

4. **Configurer l'environnement**
```bash
cp .env .env.local
# Éditer .env.local avec vos paramètres
```

5. **Créer la base de données**
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

6. **Compiler les assets**
```bash
npm run build
# ou pour le développement
npm run dev
```

7. **Lancer le serveur de développement**
```bash
symfony server:start
# ou
php -S localhost:8000 -t public/
```

## 🎨 Personnalisation

### Modifier les informations personnelles
- Éditer les fichiers de traduction dans `translations/`
- Remplacer les images dans `public/images/`
- Modifier les données de contact dans les templates

### Ajouter des projets
1. Accéder à l'interface d'administration : `/admin`
2. Gérer les projets via EasyAdmin
3. Uploader les images des projets

### Personnaliser le design
- Modifier `tailwind.config.js` pour les couleurs et thèmes
- Éditer `assets/styles/app.css` pour les styles personnalisés
- Ajuster les animations dans `assets/app.js`

## 📧 Configuration email

Pour le formulaire de contact, configurer `MAILER_DSN` dans `.env.local` :

```env
# Exemple avec Gmail
MAILER_DSN=gmail://username:password@default

# Exemple avec SMTP
MAILER_DSN=smtp://user:pass@smtp.example.com:port
```

## 🌐 Déploiement

### Production
1. Configurer les variables d'environnement
2. Compiler les assets pour la production :
```bash
npm run build
```
3. Optimiser l'autoloader Composer :
```bash
composer dump-autoload --optimize --no-dev
```
4. Vider le cache Symfony :
```bash
php bin/console cache:clear --env=prod
```

## 📁 Structure du projet

```
├── assets/                 # Assets frontend (CSS, JS)
├── config/                 # Configuration Symfony
├── public/                 # Point d'entrée web
│   ├── build/             # Assets compilés
│   └── images/            # Images statiques
├── src/
│   ├── Controller/        # Contrôleurs
│   ├── Entity/           # Entités Doctrine
│   └── Repository/       # Repositories
├── templates/             # Templates Twig
├── translations/          # Fichiers de traduction
└── var/                  # Cache et logs
```

## 🎯 Fonctionnalités principales

### Page d'accueil
- Section héro avec présentation
- Compétences organisées par catégories
- Projets mis en avant
- Formulaire de contact

### Gestion des projets
- Interface d'administration EasyAdmin
- Upload d'images
- Gestion des technologies
- Statut publié/brouillon
- Projets mis en avant

### Système multilingue
- Français et anglais
- Sélecteur de langue dans le header
- URLs localisées

### Mode sombre
- Basculement automatique
- Persistance via localStorage
- Transitions fluides

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :
1. Fork le projet
2. Créer une branche feature
3. Commiter vos changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 📞 Contact

Vincent Ferry - [vincent.ferry78490@gmail.com](mailto:vincent.ferry78490@gmail.com)

Lien du projet : [https://github.com/VincentFerry/portfolio](https://github.com/VincentFerry/portfolio)