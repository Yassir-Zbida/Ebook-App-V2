<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>@yield('email-title', config('app.name'))</title>
    
    <!-- Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:AllowPNG/>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    
    <style>
        /* Reset styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        
        /* Base styles */
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #000000 !important;
            font-family: 'Poppins', Arial, sans-serif !important;
            color: #b3b4b8 !important;
            line-height: 1.6 !important;
        }
        
        /* Container styles */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #000000;
        }
        
        .email-wrapper {
            background-color: #000000;
            border: 1px solid #36145a;
            border-radius: 16px;
            overflow: hidden;
            margin: 20px;
        }
        
        /* Header styles */
        .email-header {
            background: linear-gradient(135deg, #8622c7 0%, #6315a5 50%, #36145a 100%);
            text-align: center;
            padding: 40px 20px;
        }
        
        .logo-container {
            margin-bottom: 20px;
        }
        
        .logo {
            max-width: 120px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        
        .header-title {
            color: white !important;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            text-decoration: none;
        }
        
        .header-subtitle {
            color: rgba(255, 255, 255, 0.8) !important;
            font-size: 14px;
            margin: 8px 0 0 0;
            font-weight: 300;
        }
        
        /* Content styles */
        .email-content {
            padding: 40px 30px;
            background-color: #000000;
        }
        
        .content-title {
            color: white !important;
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 20px 0;
            text-align: center;
        }
        
        .content-text {
            color: #b3b4b8 !important;
            font-size: 16px;
            line-height: 1.6;
            margin: 0 0 20px 0;
        }
        
        .content-text-center {
            text-align: center;
        }
        
        /* Button styles */
        .btn-container {
            text-align: center;
            margin: 30px 0;
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
            border: none;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #6315a5 0%, #36145a 100%);
            transform: translateY(-2px);
        }
        
        /* Alert styles */
        .alert {
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        
        .alert-warning {
            background-color: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.3);
            color: #ffc107 !important;
        }
        
        .alert-info {
            background-color: rgba(134, 34, 199, 0.1);
            border: 1px solid rgba(134, 34, 199, 0.3);
            color: #8622c7 !important;
        }
        
        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #28a745 !important;
        }
        
        /* Security tips */
        .security-tips {
            background-color: rgba(54, 20, 90, 0.2);
            border: 1px solid #36145a;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .security-tips h3 {
            color: white !important;
            font-size: 16px;
            margin: 0 0 15px 0;
            font-weight: 600;
        }
        
        .security-tips ul {
            margin: 0;
            padding-left: 20px;
            color: #b3b4b8 !important;
        }
        
        .security-tips li {
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        /* Footer styles */
        .email-footer {
            background-color: #000000;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #36145a;
        }
        
        .footer-text {
            color: #b3b4b8 !important;
            font-size: 14px;
            margin: 0 0 10px 0;
        }
        
        .footer-links {
            margin: 15px 0 0 0;
        }
        
        .footer-link {
            color: #8622c7 !important;
            text-decoration: none;
            font-size: 14px;
            margin: 0 10px;
        }
        
        .footer-link:hover {
            color: #6315a5 !important;
        }
        
        /* Link copy section */
        .link-copy {
            background-color: rgba(54, 20, 90, 0.1);
            border: 1px solid #36145a;
            border-radius: 8px;
            padding: 15px;
            margin: 25px 0;
            word-break: break-all;
        }
        
        .link-copy-title {
            color: white !important;
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 10px 0;
        }
        
        .link-copy-url {
            color: #8622c7 !important;
            font-size: 12px;
            font-family: monospace;
            background-color: rgba(0, 0, 0, 0.3);
            padding: 8px;
            border-radius: 4px;
            word-break: break-all;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                margin: 10px;
                border-radius: 12px;
            }
            
            .email-header {
                padding: 30px 15px;
            }
            
            .email-content {
                padding: 30px 20px;
            }
            
            .email-footer {
                padding: 20px 15px;
            }
            
            .content-title {
                font-size: 18px;
            }
            
            .content-text {
                font-size: 15px;
            }
            
            .btn-primary {
                display: block;
                margin: 0 auto;
                max-width: 250px;
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .email-container {
                background-color: #000000 !important;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-wrapper">
            <!-- Header -->
            <div class="email-header">
                <div class="logo-container">
                    <img src="{{ asset('assets/images/logo.gif') }}" alt="{{ config('app.name') }}" class="logo">
                </div>
                <h1 class="header-title">{{ config('app.name') }}</h1>
                <p class="header-subtitle">@yield('email-subtitle', 'Votre plateforme d\'ebooks')</p>
            </div>
            
            <!-- Content -->
            <div class="email-content">
                @yield('email-content')
            </div>
            
            <!-- Footer -->
            <div class="email-footer">
                <p class="footer-text">
                    Cordialement,<br>
                    <strong>L'équipe {{ config('app.name') }}</strong>
                </p>
                
                <div class="footer-links">
                    <a href="{{ url('/') }}" class="footer-link">Visiter notre site</a>
                    <a href="mailto:{{ config('mail.from.address') }}" class="footer-link">Nous contacter</a>
                    @yield('footer-links')
                </div>
                
                <p class="footer-text" style="margin-top: 20px; font-size: 12px; opacity: 0.7;">
                    © {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
                </p>
            </div>
        </div>
    </div>
</body>
</html>