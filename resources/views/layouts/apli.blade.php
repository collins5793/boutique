<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AkuesleyStore - Votre Boutique en Ligne')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
          --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          --accent-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
          --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
          --glass-bg: rgba(255, 255, 255, 0.25);
          --glass-border: rgba(255, 255, 255, 0.18);
        }

        * {
          box-sizing: border-box;
        }

        body { 
          font-family: 'Inter', 'Segoe UI', sans-serif;
          line-height: 1.6;
          overflow-x: hidden;
        }

        /* Glassmorphism Navigation */
        .navbar {
          backdrop-filter: blur(20px);
          background: var(--glass-bg) !important;
          border-bottom: 1px solid var(--glass-border);
          box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
          transition: all 0.3s ease;
        }

        .navbar-brand {
          background: var(--primary-gradient);
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          background-clip: text;
          font-weight: 800 !important;
          letter-spacing: -0.5px;
        }

        .navbar-brand img { 
          width: 45px; 
          height: 45px; 
          border-radius: 50%; 
          margin-right: 12px;
          border: 3px solid rgba(255,255,255,0.3);
          box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .nav-link {
          font-weight: 500;
          transition: all 0.3s ease;
          position: relative;
        }

        .nav-link::after {
          content: '';
          position: absolute;
          width: 0;
          height: 2px;
          bottom: -5px;
          left: 50%;
          background: var(--primary-gradient);
          transition: all 0.3s ease;
          transform: translateX(-50%);
        }

        .nav-link:hover::after {
          width: 80%;
        }

        /* Modern Buttons */
        .btn-modern {
          background: var(--accent-gradient);
          border: none;
          padding: 16px 32px;
          border-radius: 50px;
          font-weight: 600;
          text-transform: uppercase;
          letter-spacing: 1px;
          transition: all 0.3s ease;
          box-shadow: 0 8px 25px rgba(245, 87, 108, 0.3);
        }

        .btn-modern:hover {
          transform: translateY(-3px);
          box-shadow: 0 15px 35px rgba(245, 87, 108, 0.4);
        }

        /* Section Headers */
        .section-header {
          text-align: center;
          margin-bottom: 60px;
        }

        .section-title {
          font-size: 2.8rem;
          font-weight: 800;
          background: var(--primary-gradient);
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          background-clip: text;
          margin-bottom: 15px;
        }

        .section-subtitle {
          font-size: 1.1rem;
          color: #6c757d;
          max-width: 600px;
          margin: 0 auto;
        }

        /* Footer Modernization */
        footer { 
          background: var(--dark-gradient);
          color: white; 
          padding: 80px 0 40px;
          position: relative;
          overflow: hidden;
        }

        footer::before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          height: 4px;
          background: var(--primary-gradient);
        }

        .footer-title {
          font-weight: 700;
          margin-bottom: 25px;
          font-size: 1.3rem;
        }

        .footer-links {
          list-style: none;
          padding: 0;
        }

        .footer-links li {
          margin-bottom: 10px;
        }

        .footer-links a {
          color: #bdc3c7; 
          text-decoration: none;
          transition: all 0.3s ease;
          position: relative;
        }

        .footer-links a::before {
          content: '→';
          position: absolute;
          left: -20px;
          opacity: 0;
          transition: all 0.3s ease;
        }

        .footer-links a:hover {
          color: white;
          padding-left: 25px;
        }

        .footer-links a:hover::before {
          opacity: 1;
          left: 0;
        }

        .newsletter-form {
          position: relative;
        }

        .newsletter-input {
          background: rgba(255,255,255,0.1);
          border: 1px solid rgba(255,255,255,0.2);
          border-radius: 50px;
          padding: 15px 20px;
          color: white;
          transition: all 0.3s ease;
        }

        .newsletter-input:focus {
          background: rgba(255,255,255,0.15);
          border-color: rgba(255,255,255,0.4);
          box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
        }

        .newsletter-input::placeholder {
          color: rgba(255,255,255,0.7);
        }

        .newsletter-btn {
          background: var(--accent-gradient);
          border: none;
          border-radius: 50px;
          padding: 15px 25px;
          font-weight: 600;
          transition: all 0.3s ease;
        }

        .newsletter-btn:hover {
          transform: scale(1.05);
        }

        .social-links a {
          display: inline-flex;
          width: 45px;
          height: 45px;
          background: rgba(255,255,255,0.1);
          border-radius: 50%;
          align-items: center;
          justify-content: center;
          margin: 0 8px;
          transition: all 0.3s ease;
          backdrop-filter: blur(10px);
        }

        .social-links a:hover {
          background: var(--primary-gradient);
          transform: translateY(-5px);
          box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
          .section-title { font-size: 2.2rem; }
        }

        /* Scroll Animations */
        .animate-on-scroll {
          opacity: 0;
          transform: translateY(30px);
          transition: all 0.8s ease;
        }

        .animate-on-scroll.animated {
          opacity: 1;
          transform: translateY(0);
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="#">
            <img src="{{ asset('logoAkues.jpg') }}" alt="Logo Akues">
            AkuesleyStore
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="/accueil">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('shop.index') }}">Produits</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Catégories</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Promotions</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
            </ul>
            <div class="d-flex align-items-center">
                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Connexion</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Inscription</a>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main>
    @yield('content')
</main>
 
<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="footer-title">
                    <i class="fas fa-store me-2"></i>
                    AkuesleyStore
                </h5>
                <p class="mb-4">La meilleure boutique en ligne du Bénin. Nous offrons une expérience d'achat exceptionnelle avec des produits de qualité, une livraison rapide et un service client incomparable.</p>
                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="footer-title">Navigation</h5>
                <ul class="footer-links">
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#">Produits</a></li>
                    <li><a href="#">Catégories</a></li>
                    <li><a href="#">Promotions</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="footer-title">Services</h5>
                <ul class="footer-links">
                    <li><a href="#">Livraison</a></li>
                    <li><a href="#">Retours</a></li>
                    <li><a href="#">Support</a></li>
                    <li><a href="#">Garanties</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="footer-title">
                    <i class="fas fa-envelope me-2"></i>
                    Newsletter
                </h5>
                <p class="mb-3">Restez informé de nos dernières offres et nouveautés !</p>
                <form class="newsletter-form">
                    <div class="mb-3">
                        <input type="email" class="form-control newsletter-input" placeholder="Votre adresse email" required>
                    </div>
                    <button class="btn newsletter-btn w-100" type="submit">
                        <i class="fas fa-paper-plane me-2"></i>
                        S'abonner
                    </button>
                </form>
            </div>
        </div>
        <hr style="border-color: rgba(255,255,255,0.1); margin: 50px 0 30px;">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">&copy; {{ date('Y') }} AkuesleyStore. Tous droits réservés.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="#" class="text-light me-3">Politique de confidentialité</a>
                <a href="#" class="text-light">Conditions d'utilisation</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Animate on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });

    // Navbar background on scroll
    window.addEventListener('scroll', () => {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 100) {
            navbar.style.background = 'rgba(255, 255, 255, 0.95)';
        } else {
            navbar.style.background = 'rgba(255, 255, 255, 0.25)';
        }
    });

    // Newsletter form handling
    document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('.newsletter-btn');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Inscription...';
        btn.disabled = true;
        
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-check me-2"></i>Inscrit !';
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 2000);
        }, 1500);
    });
</script>
@stack('scripts')
</body>
</html>