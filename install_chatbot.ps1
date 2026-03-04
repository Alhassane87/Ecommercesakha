# Script d'installation automatique du chatbot Sakha (Windows PowerShell)

Write-Host "🚀 Installation du Chatbot IA + WhatsApp + Chiffrement URL" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

# Étape 1: Migrations
Write-Host "📦 Étape 1/3 - Exécution des migrations..." -ForegroundColor Yellow
php artisan migrate --force

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Migrations réussies" -ForegroundColor Green
} else {
    Write-Host "❌ Erreur lors des migrations" -ForegroundColor Red
    exit 1
}

Write-Host ""

# Étape 2: Cache configuration
Write-Host "⚙️ Étape 2/3 - Mise en cache de la configuration..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Cache créé avec succès" -ForegroundColor Green
} else {
    Write-Host "❌ Erreur lors de la mise en cache" -ForegroundColor Red
    exit 1
}

Write-Host ""

# Étape 3: Vérifications
Write-Host "🔍 Étape 3/3 - Vérifications..." -ForegroundColor Yellow
Write-Host ""

Write-Host "Routes chatbot:" -ForegroundColor White
$routes = php artisan route:list --name=chatbot --compact
$routeCount = ($routes | Select-String "chatbot" | Measure-Object).Count
Write-Host "  ✅ $routeCount routes configurées" -ForegroundColor Green

Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "✨ Installation terminée avec succès !" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Prochaines étapes:" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Ajoutez votre clé API Gemini dans le fichier .env:" -ForegroundColor White
Write-Host "   GEMINI_API_KEY=votre_clé_ici" -ForegroundColor Cyan
Write-Host ""
Write-Host "2. Obtenez votre clé gratuite sur:" -ForegroundColor White
Write-Host "   https://makersuite.google.com/app/apikey" -ForegroundColor Cyan
Write-Host ""
Write-Host "3. Ajoutez le widget dans votre layout:" -ForegroundColor White
Write-Host "   @include('components.chatbot-widget')" -ForegroundColor Cyan
Write-Host ""
Write-Host "4. Testez le chatbot sur votre site !" -ForegroundColor White
Write-Host ""
Write-Host "📚 Documentation:" -ForegroundColor Yellow
Write-Host "   - Démarrage rapide: DEMARRAGE_RAPIDE_CHATBOT.md" -ForegroundColor White
Write-Host "   - Guide complet: GUIDE_CHATBOT_INSTALLATION.md" -ForegroundColor White
Write-Host "   - Résumé technique: RESUME_IMPLEMENTATION_CHATBOT.md" -ForegroundColor White
Write-Host ""
Write-Host "🎉 Bonne utilisation !" -ForegroundColor Green
Write-Host "============================================================" -ForegroundColor Cyan
