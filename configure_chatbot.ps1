# Script de configuration automatique du chatbot
# Exécutez: .\configure_chatbot.ps1

Write-Host ""
Write-Host "🤖 CONFIGURATION DU CHATBOT SAKHA" -ForegroundColor Cyan -BackgroundColor Black
Write-Host "═══════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""

# Vérifier si .env existe
if (-not (Test-Path ".env")) {
    Write-Host "❌ Fichier .env introuvable !" -ForegroundColor Red
    Write-Host "Création d'un fichier .env à partir de .env.example..." -ForegroundColor Yellow
    Copy-Item ".env.example" ".env"
    Write-Host "✅ Fichier .env créé !" -ForegroundColor Green
    Write-Host ""
}

# Demander la clé API
Write-Host "📋 ÉTAPE 1: Obtenir votre clé API Gemini" -ForegroundColor Yellow
Write-Host ""
Write-Host "Si vous n'avez pas encore de clé API:" -ForegroundColor White
Write-Host "1. Ouvrez: https://makersuite.google.com/app/apikey" -ForegroundColor Cyan
Write-Host "2. Connectez-vous avec Google" -ForegroundColor Gray
Write-Host "3. Cliquez sur 'Create API Key'" -ForegroundColor Gray
Write-Host "4. Copiez la clé (commence par 'AIza...')" -ForegroundColor Gray
Write-Host ""

$apiKey = Read-Host "Entrez votre clé API Gemini (ou appuyez sur Entrée pour configurer plus tard)"

if ($apiKey) {
    Write-Host ""
    Write-Host "📝 Configuration du fichier .env..." -ForegroundColor Yellow
    
    $envContent = Get-Content ".env" -Raw
    
    # Supprimer les anciennes configurations chatbot si elles existent
    $envContent = $envContent -replace "(?m)^CHATBOT_.*$", ""
    $envContent = $envContent -replace "(?m)^GEMINI_.*$", ""
    $envContent = $envContent -replace "(?m)^OPENAI_.*$", ""
    $envContent = $envContent -replace "(?m)^WHATSAPP_.*$", ""
    
    # Nettoyer les lignes vides multiples
    $envContent = $envContent -replace "(\r?\n){3,}", "`n`n"
    
    # Ajouter la configuration chatbot
    $chatbotConfig = @"

# ============================================
# CONFIGURATION CHATBOT IA
# ============================================
CHATBOT_ENABLED=true
CHATBOT_PROVIDER=gemini
GEMINI_API_KEY=$apiKey

# WhatsApp (optionnel - laissez désactivé pour l'instant)
WHATSAPP_ENABLED=false
WHATSAPP_ACCESS_TOKEN=
WHATSAPP_PHONE_NUMBER_ID=
WHATSAPP_VERIFY_TOKEN=sakha_webhook_verify_token
"@
    
    $envContent += $chatbotConfig
    
    # Sauvegarder
    Set-Content ".env" $envContent
    
    Write-Host "✅ Configuration ajoutée au fichier .env !" -ForegroundColor Green
    Write-Host ""
    
    # Recharger la configuration Laravel
    Write-Host "🔄 Rechargement de la configuration Laravel..." -ForegroundColor Yellow
    php artisan config:cache | Out-Null
    
    Write-Host "✅ Configuration rechargée !" -ForegroundColor Green
    Write-Host ""
    Write-Host "═══════════════════════════════════════════════════════════" -ForegroundColor Cyan
    Write-Host "🎉 CONFIGURATION TERMINÉE !" -ForegroundColor Green -BackgroundColor Black
    Write-Host "═══════════════════════════════════════════════════════════" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "✅ Le chatbot est maintenant configuré et prêt !" -ForegroundColor Green
    Write-Host ""
    Write-Host "🧪 POUR TESTER:" -ForegroundColor Yellow
    Write-Host "1. Si le serveur n'est pas démarré:" -ForegroundColor White
    Write-Host "   php artisan serve" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "2. Ouvrez votre navigateur:" -ForegroundColor White
    Write-Host "   http://localhost:8000" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "3. Cliquez sur le bouton de chat en bas à droite" -ForegroundColor White
    Write-Host ""
    Write-Host "4. Tapez un message:" -ForegroundColor White
    Write-Host "   'Bonjour, quels produits vendez-vous ?'" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Le chatbot devrait répondre instantanément ! 🚀" -ForegroundColor Green
    Write-Host ""
    
} else {
    Write-Host ""
    Write-Host "⏭️ Configuration reportée." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Quand vous aurez votre clé API:" -ForegroundColor White
    Write-Host "1. Ouvrez le fichier .env" -ForegroundColor Cyan
    Write-Host "2. Ajoutez ces lignes:" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "   CHATBOT_ENABLED=true" -ForegroundColor Gray
    Write-Host "   CHATBOT_PROVIDER=gemini" -ForegroundColor Gray
    Write-Host "   GEMINI_API_KEY=votre_clé_ici" -ForegroundColor Gray
    Write-Host ""
    Write-Host "3. Exécutez: php artisan config:cache" -ForegroundColor Cyan
    Write-Host ""
}

Write-Host "═══════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""
