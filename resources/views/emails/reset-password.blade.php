<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Réinitialisation de mot de passe - {{ config('app.name') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #000000 !important;
            font-family: 'Poppins', Arial, sans-serif !important;
            color: #b3b4b8 !important;
            line-height: 1.6 !important;
        }
        
        .email-container {
            max-width: 500px;
            margin: 40px auto;
            background-color: #000000;
            border: 1px solid #36145a;
            border-radius: 16px;
            overflow: hidden;
        }
        
        
        
        .logo {
            max-width: 120px;
            height: auto;
            display: block;
            margin: 0 auto;
            margin-top: 30px;
        }
        
        .content {
            padding: 30px;
            background-color: #000000;
            text-align: center;
        }
        
        .greeting {
            color: white !important;
            font-size: 18px;
            margin: 0 0 20px 0;
        }
        
        .message {
            color: #b3b4b8 !important;
            font-size: 16px;
            margin: 0 0 30px 0;
            line-height: 1.5;
        }
        
        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #8622c7 0%, #6315a5 100%);
            color: white !important;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
        }
        
        .warning {
            background-color: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.3);
            color: #ffc107 !important;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
        }
        
        .footer-note {
            color: #666 !important;
            font-size: 12px;
            margin: 20px 0 0 0;
            opacity: 0.8;
        }
        
        .footer {
            background-color: #000000;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #36145a;
        }
        
        .footer-text {
            color: #b3b4b8 !important;
            font-size: 12px;
            margin: 0;
        }
        
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 20px;
                border-radius: 12px;
            }
            
            .content {
                padding: 20px;
            }
            
            .greeting {
                font-size: 16px;
            }
            
            .message {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="">
            <img src="https://madeinchina-ebook.com/images/logo.svg" alt="{{ config('app.name') }}" class="logo">
        </div>
        
        <!-- Content -->
        <div class="content">
            <p class="greeting">
                Bonjour @if(isset($user) && $user)<strong>{{ $user->name }}</strong>@endif,
            </p>
            
            <p class="message">
                Vous avez demandé la réinitialisation de votre mot de passe.<br>
                Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe.
            </p>
            
            <a href="{{ $resetUrl }}" class="btn-primary">
                Réinitialiser le mot de passe
            </a>
            
            <div class="warning">
                <strong>⚠️ Important :</strong> Ce lien expirera dans <strong>{{ config('auth.passwords.users.expire', 60) }} minutes</strong>.
            </div>
            
            <p class="footer-note">
                Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p class="footer-text">
                © {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>