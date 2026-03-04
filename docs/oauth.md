# OAuth / Social Login — configuration

Ajoutez les variables suivantes dans votre fichier `.env` pour activer Google et Facebook Social Login via Laravel Socialite.

# Google
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT=${APP_URL}/auth/callback/google

# Facebook
FACEBOOK_CLIENT_ID=your_facebook_client_id
FACEBOOK_CLIENT_SECRET=your_facebook_client_secret
FACEBOOK_REDIRECT=${APP_URL}/auth/callback/facebook

Notes importantes:
- Remplacez `your_*` par les identifiants fournis par Google / Facebook.
- `APP_URL` doit être la valeur publique / locale correcte (ex: http://127.0.0.1:8000). Si vous testez en local avec `ngrok`, utilisez l'URL ngrok (ex: https://abcd1234.ngrok.io) et définissez également les Redirect URLs dans les dashboards des providers.
- Les routes utilisées par le projet sont :
  - `GET  /auth/redirect/{provider}`  (nommée `social.redirect`) — redirige vers le provider
  - `GET  /auth/callback/{provider}`  (nommée `social.callback`) — callback

Exemples de Redirect URI à enregistrer dans les dashboards:
- Google: `https://your-app.example.com/auth/callback/google`
- Facebook: `https://your-app.example.com/auth/callback/facebook`

Sécurité:
- Ne commitez jamais vos secrets (`CLIENT_SECRET`) dans le dépôt.
- Pour la production, utilisez un gestionnaire de secrets (Vault, AWS Secrets Manager, Heroku config, etc.).

Dépannage rapide:
- Si Socialite renvoie une erreur `redirect_uri_mismatch`, vérifiez que la Redirect URI enregistrée dans le dashboard correspond exactement à la valeur fournie (et le schéma `https` si nécessaire).
- En local, utilisez `ngrok` pour exposer une URL HTTPS publique et mettre à jour la Redirect URI chez le provider.

Si vous voulez, je peux ajouter automatiquement vos credentials dans `.env` si vous me les fournissez (ou je peux vous montrer les lignes exactes à coller).