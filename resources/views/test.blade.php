<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Circuits - GPTravel</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #00a0a0;
            --secondary: #c05720;
            --text-dark: #1a1a1a;
            --text-gray: #6b7280;
            --text-light: #9ca3af;
            --white: #ffffff;
            --bg-light: #f8fafc;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        }

        .whb-flex-row.whb-general-header-inner{
            padding: 15px 0px !important ;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleInUp {
            from {
                opacity: 0;
                transform: scale(0.8) translateY(30px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes cardSlideIn {
            from {
                opacity: 0;
                transform: translateY(80px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes iconPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 160, 160, 0.4);
            }
            70% {
                box-shadow: 0 0 0 8px rgba(0, 160, 160, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(0, 160, 160, 0);
            }
        }

        @keyframes iconSpinIn {
            from {
                opacity: 0;
                transform: scale(0) rotate(-180deg);
            }
            to {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }

        /* Hero Section */
        .circuits-hero-section {
            min-height: 60vh;
            background: linear-gradient(rgba(0, 160, 160, 0.15), rgba(192, 87, 32, 0.1)),
                url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1.2s cubic-bezier(0.4, 0, 0.2, 1) 0.2s forwards;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            backdrop-filter: blur(2px);
            background: rgba(0, 0, 0, 0.4);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hero-content {
            text-align: center;
            position: relative;
            z-index: 10;
            max-width: 800px;
            padding: 2rem;
            opacity: 0;
            transform: translateY(40px);
            animation: fadeInUp 1s cubic-bezier(0.4, 0, 0.2, 1) 0.6s forwards;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.95);
            color: var(--primary);
            padding: 0.75rem 1.5rem;
            border-radius: 16px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            opacity: 0;
            transform: scale(0.8) translateY(20px);
            animation: scaleInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.8s forwards;
        }

        .hero-title {
            font-size: clamp(2rem, 8vw, 3.5rem);
            font-weight: 800;
            margin-bottom: 1rem;
            color: var(--white);
            letter-spacing: -0.02em;
            line-height: 1.1;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s cubic-bezier(0.4, 0, 0.2, 1) 1s forwards;
        }

        .hero-subtitle {
            font-size: clamp(1rem, 2.5vw, 1.125rem);
            margin-bottom: 2.5rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 1s cubic-bezier(0.4, 0, 0.2, 1) 1.4s forwards;
        }

        

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
            z-index: 2;
            border-radius: 16px;
            opacity: 0;
            transform: translateY(50px);
            animation: fadeInUp 1s ease 0.2s forwards;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--white);
            color: var(--primary);
            padding: 0.75rem 1.5rem;
            border-radius: 16px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 160, 160, 0.2);
            opacity: 0;
            transform: scale(0.8) translateY(30px);
            animation: scaleInUp 0.8s ease 0.4s forwards;
        }

        .section-badge:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background: var(--primary);
            color: var(--white);
        }

        .section-title {
            font-size: clamp(1.75rem, 3vw, 2.5rem);
            font-weight: 800;
            margin-bottom: 0.75rem;
            color: var(--text-dark);
            opacity: 0;
            transform: translateY(40px);
            animation: fadeInUp 1s ease 0.6s forwards;
        }

        .section-subtitle {
            font-size: 1rem;
            color: var(--text-gray);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.7;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s ease 0.8s forwards;
        }

        /* Why Choose Us Section - Using provided template */
        .why-choose {
            background: var(--white);
            border-radius: 16px;
            position: relative;
            overflow: hidden;
        }

        .advantages-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 3rem;
            border-radius: 16px;
        }

        .advantage-item {
            background: var(--white);
            padding: 1.75rem;
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 160, 160, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateY(60px) scale(0.9);
            animation: cardSlideIn 0.8s ease forwards;
        }

        .advantage-item:nth-child(1) { animation-delay: 1s; }
        .advantage-item:nth-child(2) { animation-delay: 1.2s; }
        .advantage-item:nth-child(3) { animation-delay: 1.4s; }
        .advantage-item:nth-child(4) { animation-delay: 1.6s; }

        .advantage-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 160, 160, 0.02);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 16px;
        }

        .advantage-item:hover::before {
            opacity: 1;
        }

        .advantage-item:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .advantage-icon {
            width: 3rem;
            height: 3rem;
            background: var(--primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            position: relative;
            opacity: 0;
            transform: scale(0) rotate(-180deg);
            animation: iconSpinIn 0.6s ease forwards;
        }

        .advantage-item:nth-child(1) .advantage-icon { animation-delay: 1.3s; }
        .advantage-item:nth-child(2) .advantage-icon { animation-delay: 1.5s; }
        .advantage-item:nth-child(3) .advantage-icon { animation-delay: 1.7s; }
        .advantage-item:nth-child(4) .advantage-icon { animation-delay: 1.9s; }

        .advantage-item:nth-child(even) .advantage-icon {
            background: var(--secondary);
        }

        .advantage-icon::before {
            content: '';
            position: absolute;
            top: -0.25rem;
            left: -0.25rem;
            right: -0.25rem;
            bottom: -0.25rem;
            border: 2px solid rgba(0, 160, 160, 0.2);
            border-radius: 16px;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.3s ease;
        }

        .advantage-item:nth-child(even) .advantage-icon::before {
            border-color: rgba(192, 87, 32, 0.2);
        }

        .advantage-item:hover .advantage-icon::before {
            opacity: 1;
            transform: scale(1);
        }

        .advantage-item:hover .advantage-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .advantage-icon i {
            font-size: 1.25rem;
            color: var(--white);
        }

        .advantage-content h3 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--text-dark);
            opacity: 0;
            transform: translateX(-30px);
            animation: slideInLeft 0.6s ease forwards;
        }

        .advantage-item:nth-child(1) .advantage-content h3 { animation-delay: 1.4s; }
        .advantage-item:nth-child(2) .advantage-content h3 { animation-delay: 1.6s; }
        .advantage-item:nth-child(3) .advantage-content h3 { animation-delay: 1.8s; }
        .advantage-item:nth-child(4) .advantage-content h3 { animation-delay: 2s; }

        .advantage-content p {
            color: var(--text-gray);
            font-size: 0.875rem;
            line-height: 1.6;
            opacity: 0;
            transform: translateX(-20px);
            animation: slideInLeft 0.6s ease forwards;
        }

        .advantage-item:nth-child(1) .advantage-content p { animation-delay: 1.5s; }
        .advantage-item:nth-child(2) .advantage-content p { animation-delay: 1.7s; }
        .advantage-item:nth-child(3) .advantage-content p { animation-delay: 1.9s; }
        .advantage-item:nth-child(4) .advantage-content p { animation-delay: 2.1s; }

        .highlight {
            color: var(--primary);
            font-weight: 600;
            background: rgba(0, 160, 160, 0.1);
            padding: 0.125rem 0.25rem;
            border-radius: 16px;
        }

        /* Circuits Grid - Updated to 2 columns */
        .circuits-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .circuit-card {
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 160, 160, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            opacity: 0;
            transform: translateY(80px) scale(0.9);
            animation: cardSlideIn 0.8s ease forwards;
            display: flex;
            flex-direction: column;
        }

        .circuit-card:nth-child(1) { animation-delay: 0.2s; }
        .circuit-card:nth-child(2) { animation-delay: 0.4s; }
        .circuit-card:nth-child(3) { animation-delay: 0.6s; }
        .circuit-card:nth-child(4) { animation-delay: 0.8s; }

        .circuit-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary);
        }

        .circuit-image {
            height: 280px;
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }

        .circuit-image::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, rgba(0, 160, 160, 0.8), rgba(192, 87, 32, 0.6));
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .circuit-card:hover .circuit-image::before {
            opacity: 1;
        }

        .circuit-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 16px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary);
            z-index: 2;
            box-shadow: var(--shadow-sm);
        }

        .circuit-content {
            padding: 2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .circuit-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--text-dark);
            line-height: 1.3;
            transition: color 0.3s ease;
        }

        .circuit-card:hover .circuit-title {
            color: var(--primary);
        }

        .circuit-description {
            color: var(--text-gray);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            flex: 1;
        }

        .circuit-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--text-gray);
            padding: 0.5rem;
            background: var(--bg-light);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .detail-item:hover {
            background: rgba(0, 160, 160, 0.1);
            color: var(--primary);
        }

        .detail-item i {
            color: var(--primary);
            font-size: 1rem;
            flex-shrink: 0;
        }

        .circuit-price {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
            margin-top: auto;
        }

        .price-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
        }

        .circuit-btn {
            background: var(--primary);
            color: var(--white);
            padding: 0.75rem 1.5rem;
            border-radius: 16px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: var(--shadow-sm);
        }

        .circuit-btn:hover {
            background: rgba(0, 160, 160, 0.9);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .circuit-btn:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Scroll animations */
        .scroll-animate {
            opacity: 0;
            transform: translateY(50px);
            transition: all 1s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .scroll-animate.animate {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .circuits-grid {
                grid-template-columns: 1fr;
            }
            
            .advantages-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            

            .circuits-hero-section {
                margin: 1rem 0.5rem;
                min-height: 50vh;
            }

            .hero-content {
                padding: 1.5rem;
            }

            

            .why-choose {
                margin: 1rem 0.5rem;
                padding: 2rem 1rem;
            }

            .circuits-grid {
                margin: 1rem 0.5rem;
                grid-template-columns: 1fr;
            }

            .advantages-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .circuit-details {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .circuit-card {
                margin: 0;
            }

            .circuit-content {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .circuits-hero-section {
                margin: 0.5rem 0.25rem;
                min-height: 45vh;
            }

            .hero-content {
                padding: 1rem 0.75rem;
            }

            .circuit-content {
                padding: 1rem;
            }

            .circuit-price {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .circuit-btn {
                justify-content: center;
                width: 100%;
            }

            .advantages-grid {
                gap: 1rem;
            }

            .advantage-item {
                padding: 1rem;
            }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        .circuit-btn:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .hero-overlay {
                background: rgba(0, 0, 0, 0.6);
            }
            
            .hero-title,
            .hero-subtitle {
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            }
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <section class="circuits-hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-badge">
                <i class="ri-map-2-line"></i>
                Nos Circuits
            </div>
            <h1 class="hero-title">Découvrez Nos Circuits Exceptionnels</h1>
            <p class="hero-subtitle">
                Découvrez nos circuits soigneusement conçus pour vous faire vivre des expériences inoubliables au Burkina Faso et à travers le monde. Que vous soyez passionné de culture, d'aventure, de gastronomie ou de rencontres, nous avons un circuit adapté à vos envies. Explorez ci-dessous nos offres et laissez-nous vous guider vers des destinations exceptionnelles.
            </p>
        </div>
    </section>

    <!-- Why Choose Our Circuits Section -->
    <section class="section">
        <div class="container-2">
            <div class="why-choose scroll-animate">
                <div class="section-header">
                    <div class="section-badge">
                        <i class="ri-star-line"></i>
                        Pourquoi nous choisir
                    </div>
                    <h2 class="section-title">Pourquoi choisir nos circuits ?</h2>
                    <p class="section-subtitle">
                        Nos circuits sont conçus pour vous offrir une expérience fluide, authentique et mémorable
                    </p>
                </div>

                <div class="advantages-grid">
                    <div class="advantage-item">
                        <div class="advantage-icon">
                            <i class="ri-settings-3-line"></i>
                        </div>
                        <div class="advantage-content">
                            <h3>Itinéraires personnalisés</h3>
                            <p>Adaptés à vos <span class="highlight">préférences</span> et à votre rythme pour une expérience unique et sur mesure qui correspond parfaitement à vos attentes.</p>
                        </div>
                    </div>

                    <div class="advantage-item">
                        <div class="advantage-icon">
                            <i class="ri-user-star-line"></i>
                        </div>
                        <div class="advantage-content">
                            <h3>Guides experts</h3>
                            <p>Des professionnels <span class="highlight">passionnés</span> qui partagent leur connaissance locale et leur expertise du terrain avec enthousiasme.</p>
                        </div>
                    </div>

                    <div class="advantage-item">
                        <div class="advantage-icon">
                            <i class="ri-leaf-line"></i>
                        </div>
                        <div class="advantage-content">
                            <h3>Engagement responsable</h3>
                            <p>Nous privilégions des activités <span class="highlight">respectueuses</span> des communautés locales et de l'environnement pour un tourisme durable.</p>
                        </div>
                    </div>

                    <div class="advantage-item">
                        <div class="advantage-icon">
                            <i class="ri-shield-check-line"></i>
                        </div>
                        <div class="advantage-content">
                            <h3>Services inclus</h3>
                            <p>Transports, hébergements, repas (selon le circuit), activités et <span class="highlight">accompagnement complet</span> pour votre sérénité.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Circuits Section -->
    <section class="section">
        <div class="container-2">
            <div class="section-header scroll-animate">
                <div class="section-badge">
                    <i class="ri-route-line"></i>
                    Nos Circuits
                </div>
                <h2 class="section-title">Présentation des circuits</h2>
                <p class="section-subtitle">
                    Choisissez parmi nos circuits soigneusement élaborés pour découvrir des expériences authentiques
                </p>
            </div>

            <div class="circuits-grid">
                <!-- Circuit 1 -->
                <div class="circuit-card scroll-animate">
                    <div class="circuit-image" style="background-image: url('https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                        <div class="circuit-badge">7 jours</div>
                    </div>
                    <div class="circuit-content">
                        <h3 class="circuit-title">Découverte du Burkina Faso – Trésors culturels</h3>
                        <p class="circuit-description">
                            Plongez au cœur de la richesse culturelle du Burkina Faso avec ce circuit qui vous emmène à la rencontre des traditions locales, des sites historiques et des paysages uniques. Visitez les villages de Tiébélé, les cascades de Banfora et les marchés animés de Ouagadougou.
                        </p>
                        <div class="circuit-details">
                            <div class="detail-item">
                                <i class="ri-time-line"></i>
                                <span>7 jours / 6 nuits</span>
                            </div>
                            <div class="detail-item">
                                <i class="ri-group-line"></i>
                                <span>Visites guidées</span>
                            </div>
                            <div class="detail-item">
                                <i class="ri-home-heart-line"></i>
                                <span>Maisons d'hôtes</span>
                            </div>
                            <div class="detail-item">
                                <i class="ri-restaurant-line"></i>
                                <span>Dégustations</span>
                            </div>
                        </div>
                        <div class="circuit-price">
                            <div class="price-text">À partir de 70.000 XOF/personne</div>
                            <a href="occ/contact" class="circuit-btn">
                                <i class="ri-arrow-right-line"></i>
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Circuit 2 -->
                <div class="circuit-card scroll-animate">
                    <div class="circuit-image" style="background-image: url('http://wordpress1.entreprisehub.com/occ/wp-content/uploads/2025/06/card-2-dd-scaled.webp');">
                        <div class="circuit-badge">5 jours</div>
                    </div>
                    <div class="circuit-content">
                        <h3 class="circuit-title">Aventure nature – Exploration des parcs nationaux</h3>
                        <p class="circuit-description">
                            Partez à l'aventure dans les parcs nationaux du Burkina Faso pour découvrir une faune et une flore exceptionnelles. Safari en 4x4, randonnées pédestres et bivouacs sous les étoiles vous attendent.
                        </p>
                        <div class="circuit-details">
                            <div class="detail-item">
                                <i class="ri-time-line"></i>
                                <span>5 jours / 4 nuits</span>
                            </div>
                            <div class="detail-item">
                                <i class="ri-car-line"></i>
                                <span>Safari 4x4</span>
                            </div>
                            <div class="detail-item">
                                <i class="ri-tent-line"></i>
                                <span>Camps écologiques</span>
                            </div>
                            <div class="detail-item">
                                <i class="ri-binoculars-line"></i>
                                <span>Observation faune</span>
                            </div>
                        </div>
                        <div class="circuit-price">
                            <div class="price-text">À partir de 70.000 XOF/personne</div>
                            <a href="occ/contact" class="circuit-btn">
                                <i class="ri-arrow-right-line"></i>
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Circuit 3 -->
                <div class="circuit-card scroll-animate">
                    <div class="circuit-image" style="background-image: url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                        <div class="circuit-badge">3 jours</div>
                    </div>
                    <div class="circuit-content">
                        <h3 class="circuit-title">Gastronomie et culture – Escale au Restaurant La Galinade</h3>
                        <p class="circuit-description">
                            Ce circuit allie découverte culturelle et immersion gastronomique. Visitez les sites emblématiques de Ouagadougou et profitez d'une escale gourmande au Restaurant La Galinade, où vous découvrirez les saveurs authentiques du Burkina Faso.
                        </p>
                        <div class="circuit-details">
                            <div class="detail-item">
                                <i class="ri-time-line"></i>
                                <span>3 jours / 2 nuits</span>
                            </div>
                            <div class="detail-item">
                                <i class="ri-restaurant-2-line"></i>
                                <span>La Galinade</span>
                            </div>
                            <div class="detail-item">
                                <i class="ri-home-line"></i>
                                <span>Maison d'hôtes</span>
                            </div>
                            <div class="detail-item">
                                <i class="ri-chef-hat-line"></i>
                                <span>Atelier culinaire</span>
                            </div>
                        </div>
                        <div class="circuit-price">
                            <div class="price-text">À partir de 70.000 XOF/personne</div>
                            <a href="occ/contact" class="circuit-btn">
                                <i class="ri-arrow-right-line"></i>
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Circuit 4 -->
                <div class="circuit-card scroll-animate">
                    <div class="circuit-image" style="background-image: url('https://images.unsplash.com/photo-1529156069898-49953e39b3ac?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                        <div class="circuit-badge">10 jours</div>
                    </div>
                    <div class="circuit-content">
                        <h3 class="circuit-title">Voyage de groupe – Rencontres et partages</h3>
                        <p class="circuit-description">
                            Rejoignez un voyage de groupe pour partager des moments conviviaux et découvrir des destinations fascinantes. Ce circuit inclut des activités collectives, des moments de détente et des rencontres enrichissantes.
                        </p>
                        <div class="circuit-details">
                            <div class="detail-item">
                                <i class="ri-time-line"></i>
                                <span>10 jours / 9 nuits</span>
                            </div>
                            <div class="detail-item">
                                <i class="ri-group-2-line"></i>
                                <span>Voyage de groupe</span>
                            </div>
                            <div class="detail-item">
                                <i class="ri-hotel-line"></i>
                                <span>Hôtels & maisons</span>
                            </div>
                            <div class="detail-item">
                                <i class="ri-calendar-event-line"></i>
                                <span>Soirées thématiques</span>
                            </div>
                        </div>
                        <div class="circuit-price">
                            <div class="price-text">À partir de 125.000 XOF/personne</div>
                            <a href="occ/contact" class="circuit-btn">
                                <i class="ri-arrow-right-line"></i>
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Enhanced mobile-first JavaScript for GPTravel Circuits Page
        document.addEventListener("DOMContentLoaded", function () {
            // Enhanced scroll animations with mobile optimization
            const observerOptions = {
                threshold: window.innerWidth <= 768 ? 0.05 : 0.1,
                rootMargin: window.innerWidth <= 768 ? "0px 0px -20px 0px" : "0px 0px -50px 0px",
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("animate");
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe all scroll-animate elements
            const animatedElements = document.querySelectorAll(".scroll-animate");
            animatedElements.forEach((el) => {
                observer.observe(el);
            });

            // Force animate elements already in viewport
            setTimeout(() => {
                document.querySelectorAll(".scroll-animate").forEach((el) => {
                    const rect = el.getBoundingClientRect();
                    const threshold = window.innerHeight * 0.8;
                    if (rect.top < threshold) {
                        el.classList.add("animate");
                    }
                });
            }, 100);

            // Mobile-optimized smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
                anchor.addEventListener("click", function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute("href"));
                    if (target) {
                        const offset = window.innerWidth <= 768 ? 20 : 0;
                        const targetPosition = target.offsetTop - offset;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: "smooth"
                        });
                    }
                });
            });

            // Enhanced keyboard navigation
            document.addEventListener("keydown", function (e) {
                if (e.key === "Tab") {
                    document.body.classList.add("keyboard-navigation");
                }
            });

            document.addEventListener("mousedown", function () {
                document.body.classList.remove("keyboard-navigation");
            });

            // Enhanced hover effects for circuit cards
            document.querySelectorAll('.circuit-card').forEach(card => {
                if (!('ontouchstart' in window)) {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-12px) scale(1.02)';
                    });
                    
                    card.addEventListener('mouseleave', function() {
                        this.style.transform = '';
                    });
                }
            });

            // Mobile viewport height fix
            function setMobileVH() {
                const vh = window.innerHeight * 0.01;
                document.documentElement.style.setProperty('--vh', `${vh}px`);
            }

            setMobileVH();
            window.addEventListener('resize', setMobileVH);
            window.addEventListener('orientationchange', () => {
                setTimeout(setMobileVH, 100);
            });

            // Add loading animation
            window.addEventListener("load", function () {
                document.body.classList.add("loaded");
            });

            // Performance optimization for low-end devices
            if (navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 2) {
                document.documentElement.style.setProperty('--animation-duration', '0.3s');
            }

            // Accessibility: Respect user's motion preferences
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                document.querySelectorAll('*').forEach((el) => {
                    el.style.animationDuration = '0.01ms';
                    el.style.transitionDuration = '0.01ms';
                });
            }

            // Mobile-specific optimizations
            if (window.innerWidth <= 768) {
                // Add touch-friendly focus states
                document.querySelectorAll('a, button').forEach((el) => {
                    el.addEventListener('touchstart', function() {
                        this.classList.add('touch-active');
                    });
                    
                    el.addEventListener('touchend', function() {
                        setTimeout(() => {
                            this.classList.remove('touch-active');
                        }, 150);
                    });
                });
            }

            // Add focus styles for accessibility
            document.querySelectorAll('a, button, [tabindex="0"]').forEach(el => {
                el.addEventListener('focus', function() {
                    this.classList.add('focus-visible');
                });
                
                el.addEventListener('blur', function() {
                    this.classList.remove('focus-visible');
                });
            });
        });
    </script>
</body>
</html>