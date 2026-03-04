# Script de configuration ULTRA RAPIDE du chatbot
# Exécution: .\quick_setup.ps1

Write-Host ""
Write-Host "🤖 CONFIGURATION EXPRESS DU CHATBOT" -ForegroundColor Cyan -BackgroundColor Black
Write-Host "═══════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""

# Demander la clé API
Write-Host "Entrez votre clé API Gemini:" -ForegroundColor Yellow
Write-Host "(Si vous ne l'avez pas encore, ouvrez: https://makersuite.google.com/app/apikey)" -ForegroundColor Gray
Write-Host ""
$apiKey = Read-Host "Clé API"

if (-not $apiKey) {
    Write-Host ""
    Write-Host "❌ Aucune clé fournie. Configuration annulée." -ForegroundColor Red
    Write-Host ""
    exit
}

Write-Host ""
Write-Host "📝 Configuration en cours..." -ForegroundColor Yellow

# Lire le fichier .env
$envPath = ".env"
$envContent = Get-Content $envPath -Raw

# Supprimer anciennes configs chatbot si présentes
$envContent = $envContent -replace "(?m)^CHATBOT_.*$", ""
$envContent = $envContent -replace "(?m)^GEMINI_.*$", ""
$envContent = $envContent -replace "(?m)^OPENAI_.*$", ""

# Nettoyer lignes vides
$envContent = $envContent -replace "(\r?\n){3,}", "`n`n"

# Ajouter nouvelle config
$newConfig = @"

# Chatbot Configuration
CHATBOT_ENABLED=true
CHATBOT_PROVIDER=gemini
GEMINI_API_KEY=$apiKey
"@

$envContent += $newConfig

# Sauvegarder
Set-Content $envPath $envContent

Write-Host "✅ Configuration ajoutée au .env" -ForegroundColor Green
Write-Host ""
Write-Host "🔄 Rechargement de la configuration Laravel..." -ForegroundColor Yellow

# Recharger Laravel
php artisan config:clear | Out-Null
php artisan config:cache | Out-Null

Write-Host "✅ Configuration rechargée !" -ForegroundColor Green
Write-Host ""
Write-Host "═══════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host "🎉 CONFIGURATION TERMINÉE !" -ForegroundColor Green -BackgroundColor Black
Write-Host "═══════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""
Write-Host "✅ Le chatbot est maintenant opérationnel !" -ForegroundColor Green
Write-Host ""
Write-Host "🧪 TESTEZ MAINTENANT:" -ForegroundColor Yellow
Write-Host "1. Ouvrez: http://localhost:8000" -ForegroundColor Cyan
Write-Host "2. Cliquez sur le bouton de chat (bas droite)" -ForegroundColor Cyan
Write-Host "3. Tapez: 'Bonjour, quels produits vendez-vous ?'" -ForegroundColor Cyan
Write-Host "4. Le chatbot va répondre ! 🚀" -ForegroundColor Green
Write-Host ""
