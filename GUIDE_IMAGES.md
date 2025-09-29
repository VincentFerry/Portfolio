# 📸 Guide d'Optimisation des Images

## 🎯 **Spécifications Recommandées**

### **Taille et Format**
- **Taille maximale** : 2MB par image
- **Formats acceptés** : JPG, PNG, WebP
- **Résolution recommandée** : 1200x800px (ratio 3:2)
- **Résolution minimale** : 800x600px
- **Résolution maximale** : 1920x1280px

### **Optimisation**
- **Compression** : 80-85% pour JPG
- **WebP** : Format recommandé (meilleure compression)
- **PNG** : Uniquement pour les images avec transparence

## 🛠️ **Outils d'Optimisation Gratuits**

### **En ligne**
- [TinyPNG](https://tinypng.com/) - Compression automatique
- [Squoosh](https://squoosh.app/) - Outil Google avancé
- [ImageOptim](https://imageoptim.com/online) - Optimisation complète

### **Logiciels**
- **GIMP** (gratuit) - Export pour le web
- **Photoshop** - Enregistrer pour le web
- **XnConvert** (gratuit) - Traitement par lots

## 📏 **Redimensionnement Recommandé**

### **Pour les projets web**
```
Largeur : 1200px
Hauteur : 800px
Ratio : 3:2
Format : WebP ou JPG
Qualité : 85%
```

### **Pour les projets mobiles**
```
Largeur : 800px
Hauteur : 600px
Ratio : 4:3
Format : WebP ou JPG
Qualité : 80%
```

## ⚙️ **Configuration Serveur**

Le système est configuré pour :
- **Taille max upload** : 3MB (via `.htaccess` et `.user.ini`)
- **Validation EasyAdmin** : 2MB max par image
- **Formats acceptés** : JPG, JPEG, PNG, WebP
- **Optimisation automatique** : Redimensionnement à 1200x800px max
- **Compression automatique** : 85% qualité pour JPG/WebP

## ⚡ **Problèmes Courants et Solutions**

### **Image trop lourde (>2MB)**
- ✅ Réduire la résolution à 1200x800px
- ✅ Augmenter la compression (70-80%)
- ✅ Convertir en WebP
- ✅ L'optimisation automatique se charge du redimensionnement

### **Image floue dans le carousel**
- ✅ Vérifier la résolution minimale (800px largeur)
- ✅ Éviter l'upscaling d'images trop petites
- ✅ Utiliser une image de meilleure qualité

### **Chargement lent**
- ✅ Optimiser avec TinyPNG ou Squoosh
- ✅ Privilégier le format WebP
- ✅ Vérifier la taille du fichier (<500KB idéal)

## 🎨 **Conseils Visuels**

### **Composition**
- **Sujet principal** : Centré ou selon la règle des tiers
- **Arrière-plan** : Éviter les éléments distrayants
- **Contraste** : Bon contraste pour la lisibilité

### **Cohérence**
- **Style uniforme** : Même traitement pour tous les projets
- **Éclairage** : Cohérent entre les images
- **Couleurs** : Palette harmonieuse

## 🔧 **Workflow Recommandé**

1. **Capture/Création** : Image haute résolution
2. **Édition** : Retouches si nécessaires
3. **Redimensionnement** : 1200x800px
4. **Optimisation** : Compression à 85%
5. **Conversion** : WebP si possible
6. **Upload** : Via l'interface admin

## 📱 **Test de Performance**

Après upload, vérifiez :
- ✅ Chargement rapide (<2 secondes)
- ✅ Qualité visuelle acceptable
- ✅ Fonctionnement du carousel
- ✅ Affichage sur mobile

## 🆘 **Dépannage**

### **L'image ne s'affiche pas**
1. Vérifier le format (JPG/PNG/WebP uniquement)
2. Vérifier la taille (<2MB)
3. Vérifier les permissions du dossier
4. Consulter les logs de la console

### **Le carousel ne fonctionne pas**
1. Ouvrir la console du navigateur (F12)
2. Vérifier les erreurs JavaScript
3. Vérifier que plusieurs images sont uploadées
4. Tester la navigation avec les boutons

---

💡 **Astuce** : Commencez toujours par optimiser vos images avant l'upload pour une meilleure expérience utilisateur !
