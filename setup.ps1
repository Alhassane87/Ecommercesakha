# Configuration Interactive du Chatbot
# Ce script va configurer automatiquement votre chatbot

Write-Host ""
Write-Host "╔═══════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║     CONFIGURATION AUTOMATIQUE DU CHATBOT SAKHA           ║" -ForegroundColor Cyan
Write-Host "╚═══════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# Étape 1: Obtenir la clé API
Write-Host "📋 ÉTAPE 1/2: Obtention de la clé API Gemini" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Ouvrez ce lien: https://makersuite.google.com/app/apikey" -ForegroundColor White
Write-Host "2. Connectez-vous avec Google" -ForegroundColor White
Write-Host "3. Cliquez 'Create API Key'" -ForegroundColor White
Write-Host "4. Copiez la clé (commence par AIza...)" -ForegroundColor White
Write-Host ""

# Demander la clé
$apiKey = ""
while (-not $apiKey) {
    Write-Host "Collez votre clé API ici: " -ForegroundColor Cyan -NoNewline
    $apiKey = Read-Host
    
    if (-not $apiKey) {
        Write-Host "❌ Clé vide, réessayez." -ForegroundColor Red
        Write-Host ""
    }
}

Write-Host ""
Write-Host "✅ Clé API reçue !" -ForegroundColor Green
Write-Host ""

# Étape 2: Configuration
Write-Host "📝 ÉTAPE 2/2: Configuration du fichier .env" -ForegroundColor Yellow
Write-Host ""

# Lire .env
$envPath = ".env"
if (-not (Test-Path $envPath)) {
    Write-Host "⚠️  Fichier .env non trouvé, création..." -ForegroundColor Yellow
    if (Test-Path ".env.example") {
        Copy-Item ".env.example" $envPath
        Write-Host "✅ .env créé depuis .env.example" -ForegroundColor Green
    } else {
        Write-Host "❌ .env.example non trouvé !" -ForegroundColor Red
        exit 1
    }
}

$envContent = Get-Content $envPath -Raw

# Supprimer anciennes configs
$envContent = $envContent -replace "(?m)^CHATBOT_ENABLED=.*$", ""
$envContent = $envContent -replace "(?m)^CHATBOT_PROVIDER=.*$", ""
$envContent = $envContent -replace "(?m)^GEMINI_API_KEY=.*$", ""
$envContent = $envContent -replace "(?m)^OPENAI_API_KEY=.*$", ""

# Nettoyer lignes vides
$envContent = $envContent.TrimEnd()

# Ajouter config chatbot
$chatbotConfig = @"


# ================================================
# CHATBOT CONFIGURATION
# ================================================
CHATBOT_ENABLED=true
CHATBOT_PROVIDER=gemini
GEMINI_API_KEY=$apiKey
"@

$envContent += $chatbotConfig

# Sauvegarder
Set-Content $envPath $envContent -NoNewline

Write-Host "✅ Configuration ajoutée au .env" -ForegroundColor Green
Write-Host ""

# Recharger Laravel
Write-Host "🔄 Rechargement de Laravel..." -ForegroundColor Yellow
try {
    php artisan config:clear | Out-Null
    php artisan config:cache | Out-Null
    Write-Host "✅ Laravel rechargé !" -ForegroundColor Green
} catch {
    Write-Host "⚠️  Erreur lors du rechargement: $_" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "╔═══════════════════════════════════════════════════════════╗" -ForegroundColor Green
Write-Host "║              ✅ CONFIGURATION TERMINÉE !                  ║" -ForegroundColor Green
Write-Host "╚═══════════════════════════════════════════════════════════╝" -ForegroundColor Green
Write-Host ""

Write-Host "🎉 Le chatbot est maintenant opérationnel !" -ForegroundColor Green
Write-Host ""
Write-Host "🧪 TESTEZ MAINTENANT:" -ForegroundColor Yellow
Write-Host "1. Ouvrez: http://localhost:8000" -ForegroundColor Cyan
Write-Host "2. Cliquez sur le bouton de chat (bas à droite)" -ForegroundColor Cyan
Write-Host "3. Tapez: 'Bonjour, quels produits vendez-vous ?'" -ForegroundColor Cyan
Write-Host "4. Le chatbot va répondre intelligemment ! 🚀" -ForegroundColor Green
Write-Host ""
Write-Host "Appuyez sur Entrée pour quitter..." -ForegroundColor Gray
Read-Host
