<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-gradient-to-r from-blue-500 to-purple-600">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Connexion Google
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Configuration requise
                </p>
            </div>
            
            <div class="bg-white shadow rounded-lg p-6">
                <div class="space-y-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    Configuration OAuth requise
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p class="mb-2">Pour activer la connexion Google, vous devez :</p>
                                    <ol class="list-decimal list-inside space-y-1">
                                        <li>Créer un projet sur <a href="https://console.cloud.google.com/" target="_blank" class="underline">Google Cloud Console</a></li>
                                        <li>Activer l'API Google+</li>
                                        <li>Créer des identifiants OAuth2</li>
                                        <li>Ajouter les variables d'environnement dans votre fichier .env</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-md p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Variables à ajouter dans .env :</h4>
                        <pre class="text-xs bg-gray-900 text-gray-100 p-3 rounded overflow-x-auto">
GOOGLE_CLIENT_ID=votre_client_id_google
GOOGLE_CLIENT_SECRET=votre_client_secret_google
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback</pre>
                    </div>
                    
                    <div class="text-sm text-gray-600">
                        <p>Une fois configuré, la connexion Google sera entièrement fonctionnelle.</p>
                    </div>
                </div>
                
                <div class="mt-6">
                    <a href="{{ route('login') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" style="background: var(--button-action)">
                        Retour à la connexion
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
