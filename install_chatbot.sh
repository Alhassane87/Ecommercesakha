#!/bin/bash
# Script d'installation automatique du chatbot Sakha

echo "🚀 Installation du Chatbot IA + WhatsApp + Chiffrement URL"
echo "============================================================"
echo ""

# Étape 1: Migrations
echo "📦 Étape 1/3 - Exécution des migrations..."
php artisan migrate --force

if [ $? -eq 0 ]; then
    echo "✅ Migrations réussies"
else
    echo "❌ Erreur lors des migrations"
    exit 1
fi

echo ""

# Étape 2: Cache configuration
echo "⚙️ Étape 2/3 - Mise en cache de la configuration..."
php artisan config:cache
php artisan route:cache

if [ $? -eq 0 ]; then
    echo "✅ Cache créé avec succès"
else
    echo "❌ Erreur lors de la mise en cache"
    exit 1
fi

echo ""

# Étape 3: Vérifications
echo "🔍 Étape 3/3 - Vérifications..."
echo ""

# Vérifier les tables
echo "Tables créées:"
php artisan db:table conversations 2>/dev/null && echo "  ✅ conversations"
php artisan db:table chatbot_messages 2>/dev/null && echo "  ✅ chatbot_messages"
php artisan db:table whatsapp_sessions 2>/dev/null && echo "  ✅ whatsapp_sessions"

echo ""

# Vérifier les routes
echo "Routes chatbot:"
php artisan route:list --name=chatbot --compact | grep -E "chatbot" | wc -l | xargs -I {} echo "  ✅ {} routes configurées"

echo ""
echo "============================================================"
echo "✨ Installation terminée avec succès !"
echo ""
echo "📋 Prochaines étapes:"
echo ""
echo "1. Ajoutez votre clé API Gemini dans le fichier .env:"
echo "   GEMINI_API_KEY=votre_clé_ici"
echo ""
echo "2. Obtenez votre clé gratuite sur:"
echo "   https://makersuite.google.com/app/apikey"
echo ""
echo "3. Ajoutez le widget dans votre layout:"
echo "   @include('components.chatbot-widget')"
echo ""
echo "4. Testez le chatbot sur votre site !"
echo ""
echo "📚 Documentation:"
echo "   - Démarrage rapide: DEMARRAGE_RAPIDE_CHATBOT.md"
echo "   - Guide complet: GUIDE_CHATBOT_INSTALLATION.md"
echo "   - Résumé technique: RESUME_IMPLEMENTATION_CHATBOT.md"
echo ""
echo "🎉 Bonne utilisation !"
echo "============================================================"
