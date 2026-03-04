<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation d'abonnement newsletter</title>
</head>
<body style="margin:0; padding:0; background:#f1f5f9; font-family:Arial, Helvetica, sans-serif; color:#0f172a;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:620px; background:#ffffff; border-radius:18px; overflow:hidden; border:1px solid #e2e8f0;">
                    <tr>
                        <td style="padding:28px; background:linear-gradient(135deg, #0ea5e9 0%, #06b6d4 45%, #10b981 100%); color:#ffffff;">
                            <div style="font-size:14px; opacity:0.95; letter-spacing:0.08em; text-transform:uppercase; font-weight:700;">Newsletter</div>
                            <h1 style="margin:10px 0 0; font-size:28px; line-height:1.2;">Merci pour votre abonnement</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 14px; font-size:16px; line-height:1.6;">
                                Votre abonnement à la newsletter <strong>{{ config('app.name', 'Sakha') }}</strong> est confirmé.
                            </p>
                            <p style="margin:0 0 14px; font-size:16px; line-height:1.6;">
                                E-mail enregistré : <strong>{{ $email }}</strong>
                            </p>
                            <p style="margin:0 0 22px; font-size:15px; line-height:1.6; color:#475569;">
                                Vous recevrez nos offres exclusives et nos nouvelles collections.
                            </p>
                            <a href="{{ url('/') }}" style="display:inline-block; padding:12px 18px; border-radius:10px; background:#0ea5e9; color:#ffffff; text-decoration:none; font-weight:700;">
                                Visiter le site
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 28px 22px; color:#64748b; font-size:12px; line-height:1.5;">
                            Si vous n'êtes pas à l'origine de cet abonnement, vous pouvez ignorer cet e-mail.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
