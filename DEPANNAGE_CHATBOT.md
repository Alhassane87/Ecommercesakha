# 🔧 GUIDE DE DÉPANNAGE - CHATBOT SAKHA

## ❌ Problème: Le bouton du chatbot ne fonctionne pas

### ✅ SOLUTION APPLIQUÉE

Le problème était que le bouton et la fenêtre du chat n'étaient pas dans le même contexte Alpine.js.

**Correction effectuée:**
- Englobé le bouton et la fenêtre dans un seul `<div x-data="chatbot()">`
- Remplacé `x-if` par `x-show` pour de meilleures performances
- Ajouté `x-transition` pour des animations fluides
- Ajouté le style `[x-cloak]` pour éviter le flash au chargement

### 🧪 Comment tester maintenant

1. **Ouvrez votre navigateur**
   ```
   http://127.0.0.1:8000
   ```

2. **Cherchez l'icône de chat**
   - En bas à droite de l'écran
   - Icône bleue ronde avec des points

3. **Cliquez sur le bouton**
   - La fenêtre de chat devrait s'ouvrir avec une animation
   - Le message "Bonjour ! Comment puis-je vous aider aujourd'hui ?" devrait apparaître

### 🐛 Si le bouton ne s'affiche toujours pas

**1. Vérifiez que Alpine.js est chargé:**

Ouvrez la console du navigateur (F12) et tapez:
```javascript
window.Alpine
```

Si c'est `undefined`, Alpine n'est pas chargé. Solution:
```bash
npm install
npm run dev
```

**2. Vérifiez les erreurs JavaScript:**

Ouvrez la console (F12) et regardez s'il y a des erreurs en rouge.

**3. Vérifiez que le widget est inclus:**

Ouvrez `resources/views/layouts/app.blade.php` et vérifiez qu'il y a:
```blade
@include('components.chatbot-widget')
```

**4. Videz tous les caches:**
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## ❌ Problème: Le chatbot s'ouvre mais ne répond pas

### Causes possibles:

#### 1. Clé API manquante

**Symptôme:** Message d'erreur "Désolé, je rencontre un problème technique"

**Solution:**
```env
# Dans votre fichier .env, ajoutez:
CHATBOT_ENABLED=true
CHATBOT_PROVIDER=gemini
GEMINI_API_KEY=votre_clé_ici
```

**Obtenez votre clé gratuite:**
https://makersuite.google.com/app/apikey

Puis:
```bash
php artisan config:cache
```

#### 2. Erreur de route

**Symptôme:** Erreur 404 dans la console

**Solution:**
```bash
php artisan route:clear
php artisan route:cache
php artisan route:list --name=chatbot
```

Vous devriez voir:
- `chatbot.message` (POST)
- `chatbot.history` (GET)

#### 3. Token CSRF manquant

**Symptôme:** Erreur 419 dans la console

**Solution:** Vérifiez que votre layout a:
```blade
<meta name="csrf-token" content="{{ csrf_token() }}">
```

---

## ❌ Problème: Erreur "Alpine is not defined"

**Solution:**

1. Installez les dépendances:
```bash
npm install
```

2. Compilez les assets:
```bash
npm run dev
```

3. En production:
```bash
npm run build
```

---

## ❌ Problème: Le bouton est là mais rien ne se passe au clic

### Diagnostic:

1. **Ouvrez la console du navigateur (F12)**

2. **Cherchez les erreurs**

3. **Vérifiez Alpine.js:**
```javascript
console.log(window.Alpine);
```

### Solutions:

**A. Si Alpine n'est pas défini:**
```bash
# Réinstallez Alpine.js
npm install alpinejs
npm run dev
```

**B. Si erreur de fonction:**

Vérifiez que `resources/views/components/chatbot-widget.blade.php` contient bien:
```javascript
<script>
function chatbot() {
    return {
        isOpen: false,
        toggleChat() {
            this.isOpen = !this.isOpen;
        },
        // ... reste du code
    };
}
</script>
```

---

## ❌ Problème: Le style est cassé

**Symptôme:** Le bouton ou la fenêtre ne ressemblent pas à ce qu'ils devraient

**Solution:**

1. **Vérifiez Tailwind:**
```bash
npm run dev
```

2. **Videz le cache des vues:**
```bash
php artisan view:clear
```

3. **Rechargez la page** avec Ctrl+F5 (force refresh)

---

## ❌ Problème: Messages d'erreur spécifiques

### "SQLSTATE[42S02]: Base table or view not found"

**Solution:**
```bash
php artisan migrate
```

### "Class 'App\Services\ChatbotService' not found"

**Solution:**
```bash
composer dump-autoload
php artisan config:cache
```

### "Call to undefined method"

**Solution:** Vérifiez que tous les fichiers sont bien créés:
```bash
# Vérifiez les fichiers
ls app/Services/ChatbotService.php
ls app/Services/WhatsAppService.php
ls app/Http/Controllers/ChatbotController.php
```

---

## ✅ CHECKLIST DE VÉRIFICATION

Avant de demander de l'aide, vérifiez:

- [ ] Le serveur Laravel est démarré (`php artisan serve`)
- [ ] Vite/npm est en cours d'exécution (`npm run dev`)
- [ ] Alpine.js est chargé (vérifiable dans la console)
- [ ] Le widget est inclus dans le layout
- [ ] Pas d'erreurs JavaScript dans la console (F12)
- [ ] Les routes chatbot existent (`php artisan route:list --name=chatbot`)
- [ ] Les migrations sont exécutées
- [ ] Le fichier `.env` est configuré (si vous voulez des réponses)
- [ ] Tous les caches sont vidés

---

## 🔍 DIAGNOSTIC COMPLET

Exécutez cette commande pour un diagnostic complet:

```bash
Write-Host "=== DIAGNOSTIC CHATBOT ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. Fichiers:" -ForegroundColor Yellow
Test-Path "app\Services\ChatbotService.php" | Out-String
Test-Path "resources\views\components\chatbot-widget.blade.php" | Out-String
Write-Host ""
Write-Host "2. Routes:" -ForegroundColor Yellow
php artisan route:list --name=chatbot --compact
Write-Host ""
Write-Host "3. Config:" -ForegroundColor Yellow
php artisan config:show chatbot
Write-Host ""
Write-Host "4. Migrations:" -ForegroundColor Yellow
php artisan migrate:status | Select-String "chatbot|conversation|whatsapp"
```

---

## 📞 SUPPORT

Si le problème persiste:

1. **Vérifiez les logs Laravel:**
   ```bash
   Get-Content storage/logs/laravel.log -Tail 50
   ```

2. **Vérifiez la console JavaScript** (F12 dans le navigateur)

3. **Testez l'API manuellement** avec Postman ou curl:
   ```bash
   curl -X POST http://localhost:8000/chatbot/message \
     -H "Content-Type: application/json" \
     -H "X-CSRF-TOKEN: votre_token" \
     -d '{"message":"test"}'
   ```

---

## 🎯 SOLUTION RAPIDE (Reset complet)

Si rien ne fonctionne, réinitialisez tout:

```bash
# 1. Videz tous les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Recompilez les assets
npm run dev

# 3. Rechargez la configuration
php artisan config:cache
php artisan route:cache

# 4. Redémarrez le serveur
# Arrêtez (Ctrl+C) puis:
php artisan serve
```

Puis testez à nouveau dans le navigateur.

---

**Date de mise à jour:** 16 Janvier 2026
