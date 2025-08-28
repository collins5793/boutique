@extends('layouts.clients.client')

@section('title', 'Support')

@section('content')
@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\Order;

    $user = Auth::user();

    $pendingCount = Order::where('user_id', $user->id)
                        ->where('order_status', 'pending')
                        ->count();

    $processingCount = Order::where('user_id', $user->id)
                           ->where('order_status', 'processing')
                           ->count();
@endphp

<style>
        :root {
            --primary: #f506c4;
            --primary-light: #ff33d1;
            --primary-dark: #c0049b;
            --secondary: #007bff;
            --accent: #ff7b00;
            --dark: #1e293b;
            --dark-light: #334155;
            --light: #ffffff;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-600: #6c757d;
            --sidebar-width: 280px;
            --sidebar-collapsed: 85px;
            --header-height: 80px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --radius: 12px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 15px 40px rgba(0, 0, 0, 0.15);
            --mobile-breakpoint: 1024px;
            --tablet-breakpoint: 768px;
            --phone-breakpoint: 576px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* HEADER STYLES */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            animation: fadeInDown 0.8s ease;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        .logo i {
            font-size: 1.8rem;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--light);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .user-menu:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        /* SUPPORT HEADER */
        .support-header {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeIn 1s ease;
        }

        .support-header h1 {
            font-size: 2.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            font-weight: 800;
        }

        .support-header p {
            font-size: 1.1rem;
            color: var(--dark-light);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* SEARCH BAR */
        .search-bar {
            max-width: 650px;
            margin: 0 auto 3.5rem;
            position: relative;
            animation: slideInUp 0.8s ease;
        }

        .search-bar input {
            width: 100%;
            padding: 1.2rem 1.5rem;
            border-radius: 50px;
            border: 2px solid transparent;
            background: var(--light);
            font-size: 1rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--primary-light);
            box-shadow: 0 5px 20px rgba(245, 6, 196, 0.2);
        }

        .search-bar button {
            position: absolute;
            right: 5px;
            top: 5px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .search-bar button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245, 6, 196, 0.4);
        }

        /* HELP OPTIONS */
        .help-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .help-card {
            background: var(--light);
            border-radius: var(--radius);
            padding: 2.5rem 2rem;
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.8s ease;
        }

        .help-card:nth-child(1) { animation-delay: 0.1s; }
        .help-card:nth-child(2) { animation-delay: 0.2s; }
        .help-card:nth-child(3) { animation-delay: 0.3s; }

        .help-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .help-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .help-card i {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .help-card h3 {
            margin-bottom: 1rem;
            color: var(--dark);
            font-size: 1.4rem;
        }

        .help-card p {
            color: var(--gray-600);
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 1.8rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(245, 6, 196, 0.3);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(245, 6, 196, 0.4);
        }

        /* FAQ SECTION */
        .faq-section {
            margin-bottom: 4rem;
            animation: fadeIn 1s ease;
        }

        .section-title {
            text-align: center;
            margin-bottom: 2.5rem;
            color: var(--dark);
            font-size: 2rem;
            font-weight: 700;
            position: relative;
            padding-bottom: 1rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .accordion {
            background: var(--light);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .accordion-item {
            border-bottom: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .accordion-item:last-child {
            border-bottom: none;
        }

        .accordion-header {
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            transition: var(--transition);
        }

        .accordion-header:hover {
            background-color: var(--gray-100);
        }

        .accordion-content {
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease;
            background-color: var(--gray-100);
        }

        .accordion-content.active {
            max-height: 300px;
            padding: 1.5rem;
        }

        .accordion-content p {
            color: var(--gray-600);
            line-height: 1.8;
        }

        /* CONTACT SECTION */
        .contact-section {
            background: var(--light);
            border-radius: var(--radius);
            padding: 3rem;
            box-shadow: var(--shadow);
            margin-bottom: 3rem;
            animation: fadeIn 1s ease;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 3rem;
            margin-bottom: 2rem;
        }

        .contact-info {
            display: flex;
            align-items: flex-start;
            margin-bottom: 2rem;
            transition: var(--transition);
        }

        .contact-info:hover {
            transform: translateX(5px);
        }

        .contact-icon {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.2rem;
            color: white;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .contact-details h4 {
            margin-bottom: 0.5rem;
            color: var(--dark);
            font-size: 1.2rem;
        }

        .contact-details p {
            color: var(--gray-600);
            line-height: 1.6;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        form input, form select, form textarea {
            padding: 1rem 1.2rem;
            border-radius: var(--radius);
            border: 1px solid var(--gray-300);
            font-size: 1rem;
            transition: var(--transition);
        }

        form input:focus, form select:focus, form textarea:focus {
            outline: none;
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(245, 6, 196, 0.1);
        }

        form textarea {
            resize: vertical;
            min-height: 120px;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .social-links a {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            text-decoration: none;
            transition: var(--transition);
            font-size: 1.2rem;
        }

        .social-links a:hover {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(245, 6, 196, 0.3);
        }

        /* FOOTER */
        footer {
            text-align: center;
            padding: 2rem 0;
            color: var(--gray-600);
            border-top: 1px solid var(--gray-200);
            margin-top: 3rem;
            animation: fadeIn 1s ease;
        }

        /* ANIMATIONS */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* RESPONSIVE DESIGN */
        @media (max-width: 1024px) {
            .container {
                padding: 1.5rem;
            }
            
            .support-header h1 {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .support-header h1 {
                font-size: 2rem;
            }
            
            .help-options {
                grid-template-columns: 1fr;
            }
            
            .contact-grid {
                grid-template-columns: 1fr;
            }
            
            .search-bar button {
                position: relative;
                margin-top: 1rem;
                width: 100%;
                top: 0;
                right: 0;
            }
        }

        @media (max-width: 576px) {
            .support-header h1 {
                font-size: 1.8rem;
            }
            
            .contact-section {
                padding: 1.5rem;
            }
            
            .help-card {
                padding: 2rem 1.5rem;
            }
        }
    </style>

    <div class="container">
        <!-- HEADER -->
        <header>
            <div class="logo">
                <i class="fas fa-chart-line" style="color: #f5f7fa; margin-left: 1rem"></i>
                <span></span>
            </div>
            <div class="user-menu">
                <div class="user-avatar">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <span>{{ $user->name }}</span>
            </div>
        </header>
        
        <!-- SUPPORT HEADER -->
        <section class="support-header">
            <h1>Centre d'aide et support</h1>
            <p>Bienvenue {{ $user->name }}, nous sommes là pour vous accompagner.  
            Trouvez des réponses à vos questions ou contactez notre équipe directement.</p>
        </section>
        
        <!-- BARRE DE RECHERCHE -->
        <div class="search-bar">
            <input type="text" placeholder="Rechercher une question, un problème ou un sujet...">
            <button><i class="fas fa-search"></i> Rechercher</button>
        </div>
        
        <!-- OPTIONS D'AIDE -->
        <section class="help-options">
            <div class="help-card">
                <i class="fas fa-book"></i>
                <h3>Centre de documentation</h3>
                <p>Consultez nos guides et tutoriels pour apprendre à utiliser toutes les fonctionnalités.</p>
                <a href="#" class="btn">Explorer</a>
            </div>
            
            <div class="help-card">
                <i class="fas fa-video"></i>
                <h3>Tutoriels vidéo</h3>
                <p>Regardez nos vidéos explicatives et découvrez pas à pas nos solutions.</p>
                <a href="#" class="btn">Regarder</a>
            </div>
            
            <div class="help-card">
                <i class="fas fa-comments"></i>
                <h3>Contact direct</h3>
                <p>Discutez avec notre support technique pour une assistance personnalisée.</p>
                <a href="#" class="btn">Nous contacter</a>
            </div>
        </section>
        
        <!-- FAQ -->
        <section class="faq-section">
            <h2 class="section-title">Questions fréquentes</h2>
            
            <div class="accordion">
                <div class="accordion-item">
                    <div class="accordion-header">
                        <span>Comment suivre mes commandes ?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Vous pouvez suivre vos commandes en cours depuis la section "Mes commandes".  
                        Commandes en attente : {{ $pendingCount }} – En cours de traitement : {{ $processingCount }}.</p>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <div class="accordion-header">
                        <span>Comment changer mon mot de passe ?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Allez dans "Paramètres du compte" puis "Sécurité". Vous pourrez définir un nouveau mot de passe.</p>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <div class="accordion-header">
                        <span>Problèmes de connexion</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Si vous avez oublié votre mot de passe, utilisez "Mot de passe oublié".  
                        En cas de problème persistant, contactez notre support.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- CONTACT -->
        <section class="contact-section">
            <h2 class="section-title">Contactez-nous</h2>
            
            <div class="contact-grid">
                <div>
                    <div class="contact-info">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Email</h4>
                            <p>support@monsite.com</p>
                            <p>Réponse sous 24h</p>
                        </div>
                    </div>
                    
                    <div class="contact-info">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Téléphone</h4>
                            <p>+229 91 23 45 67</p>
                            <p>Lun-Ven, 9h-18h</p>
                        </div>
                    </div>
                    
                    <div class="contact-info">
                        <div class="contact-icon">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Chat en direct</h4>
                            <p>Disponible 24/7</p>
                            <p>Assistance immédiate</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4>Envoyez-nous un message</h4>
                    <form>
                        <input type="text" placeholder="Votre nom" value="{{ $user->name }}">
                        <input type="email" placeholder="Votre email" value="{{ $user->email }}">
                        <select>
                            <option>Sélectionnez un sujet</option>
                            <option>Problème technique</option>
                            <option>Facturation</option>
                            <option>Fonctionnalité</option>
                            <option>Autre</option>
                        </select>
                        <textarea placeholder="Votre message" rows="5"></textarea>
                        <button type="submit" class="btn">Envoyer</button>
                    </form>
                </div>
            </div>
            
            <div class="social-links">
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </section>
        
        <!-- FOOTER -->
        <footer>
            <p>© {{ date('Y') }} MonSite. Tous droits réservés.</p>
        </footer>
    </div>

    <script>
        // Script pour l'accordéon FAQ
        document.querySelectorAll('.accordion-header').forEach(header => {
            header.addEventListener('click', () => {
                const content = header.nextElementSibling;
                content.classList.toggle('active');
                
                const icon = header.querySelector('i');
                if (content.classList.contains('active')) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            });
        });

        // Animation au défilement
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeIn 1s ease forwards';
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.help-card, .faq-section, .contact-section').forEach(el => {
            observer.observe(el);
        });
    </script>
@endsection