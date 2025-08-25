<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Client | Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --accent: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
            --warning: #f9c74f;
            --gray: #6c757d;
            --light-gray: #e9ecef;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
        }
        
        .logo i {
            margin-right: 10px;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }
        
        .support-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .support-header h1 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .support-header p {
            font-size: 1.2rem;
            color: var(--gray);
            max-width: 700px;
            margin: 0 auto;
        }
        
        .search-bar {
            max-width: 600px;
            margin: 0 auto 40px;
            position: relative;
        }
        
        .search-bar input {
            width: 100%;
            padding: 15px 20px;
            border-radius: 50px;
            border: 1px solid var(--light-gray);
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .search-bar input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.15);
        }
        
        .search-bar button {
            position: absolute;
            right: 5px;
            top: 5px;
            background: var(--primary);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-bar button:hover {
            background: var(--secondary);
        }
        
        .help-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }
        
        .help-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .help-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        
        .help-card i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 20px;
        }
        
        .help-card h3 {
            margin-bottom: 15px;
            color: var(--dark);
        }
        
        .help-card p {
            color: var(--gray);
            margin-bottom: 20px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: var(--primary);
            color: white;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }
        
        .faq-section {
            margin-bottom: 50px;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 30px;
            color: var(--dark);
        }
        
        .accordion {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .accordion-item {
            border-bottom: 1px solid var(--light-gray);
        }
        
        .accordion-item:last-child {
            border-bottom: none;
        }
        
        .accordion-header {
            padding: 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 500;
        }
        
        .accordion-header:hover {
            background-color: #f9fafc;
        }
        
        .accordion-content {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background-color: #f9fafc;
        }
        
        .accordion-content.active {
            max-height: 300px;
            padding: 20px;
        }
        
        .contact-section {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 50px;
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .contact-info {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .contact-icon {
            width: 50px;
            height: 50px;
            background: var(--light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary);
            font-size: 1.2rem;
        }
        
        .contact-details h4 {
            margin-bottom: 5px;
        }
        
        .contact-details p {
            color: var(--gray);
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }
        
        footer {
            text-align: center;
            padding: 30px 0;
            color: var(--gray);
            border-top: 1px solid var(--light-gray);
            margin-top: 50px;
        }
        
        @media (max-width: 768px) {
            .support-header h1 {
                font-size: 2rem;
            }
            
            .help-options {
                grid-template-columns: 1fr;
            }
            
            .contact-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <i class="fas fa-chart-line"></i>
                <span>DashboardPro</span>
            </div>
            <div class="user-menu">
                <div class="user-avatar">JD</div>
                <span>Jean Dupont</span>
            </div>
        </header>
        
        <section class="support-header">
            <h1>Centre d'aide et support</h1>
            <p>Nous sommes là pour vous aider. Trouvez des réponses à vos questions ou contactez notre équipe de support.</p>
        </section>
        
        <div class="search-bar">
            <input type="text" placeholder="Rechercher une question, un problème ou un sujet...">
            <button><i class="fas fa-search"></i> Rechercher</button>
        </div>
        
        <section class="help-options">
            <div class="help-card">
                <i class="fas fa-book"></i>
                <h3>Centre de documentation</h3>
                <p>Parcourez nos guides complets et tutoriels pour maîtriser toutes les fonctionnalités.</p>
                <a href="#" class="btn">Explorer</a>
            </div>
            
            <div class="help-card">
                <i class="fas fa-video"></i>
                <h3>Tutoriels vidéo</h3>
                <p>Apprenez avec nos vidéos explicatives et démonstrations de fonctionnalités.</p>
                <a href="#" class="btn">Regarder</a>
            </div>
            
            <div class="help-card">
                <i class="fas fa-comments"></i>
                <h3>Contact direct</h3>
                <p>Discutez avec notre équipe de support pour une assistance personnalisée.</p>
                <a href="#" class="btn">Nous contacter</a>
            </div>
        </section>
        
        <section class="faq-section">
            <h2 class="section-title">Questions fréquemment posées</h2>
            
            <div class="accordion">
                <div class="accordion-item">
                    <div class="accordion-header">
                        <span>Comment exporter mes données ?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Pour exporter vos données, allez dans la section "Rapports", sélectionnez les données que vous souhaitez exporter et cliquez sur le bouton "Exporter". Vous pouvez choisir entre différents formats (CSV, Excel, PDF).</p>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <div class="accordion-header">
                        <span>Comment ajouter un nouvel utilisateur ?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Pour ajouter un nouvel utilisateur, allez dans "Paramètres" > "Gestion des utilisateurs" et cliquez sur "Ajouter un utilisateur". Remplissez les informations requises et assignez les permissions appropriées.</p>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <div class="accordion-header">
                        <span>Comment personnaliser mon tableau de bord ?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Vous pouvez personnaliser votre tableau de bord en cliquant sur le bouton "Personnaliser" en haut à droite de votre écran. Vous pourrez alors ajouter, supprimer ou réorganiser les widgets selon vos préférences.</p>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <div class="accordion-header">
                        <span>Problèmes de connexion</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Si vous rencontrez des problèmes de connexion, vérifiez d'abord que vos identifiants sont corrects. Si le problème persiste, utilisez la fonction "Mot de passe oublié" ou contactez notre support pour obtenir de l'aide.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="contact-section">
            <h2 class="section-title">Contactez notre équipe</h2>
            
            <div class="contact-grid">
                <div>
                    <div class="contact-info">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Email</h4>
                            <p>support@dashboardpro.fr</p>
                            <p>Réponse sous 24h</p>
                        </div>
                    </div>
                    
                    <div class="contact-info">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Téléphone</h4>
                            <p>+33 1 23 45 67 89</p>
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
                        <input type="text" placeholder="Votre nom" style="width: 100%; padding: 12px; margin: 10px 0; border-radius: 8px; border: 1px solid #ddd;">
                        <input type="email" placeholder="Votre email" style="width: 100%; padding: 12px; margin: 10px 0; border-radius: 8px; border: 1px solid #ddd;">
                        <select style="width: 100%; padding: 12px; margin: 10px 0; border-radius: 8px; border: 1px solid #ddd;">
                            <option>Sélectionnez un sujet</option>
                            <option>Problème technique</option>
                            <option>Question facturation</option>
                            <option>Demande de fonctionnalité</option>
                            <option>Autre</option>
                        </select>
                        <textarea placeholder="Votre message" rows="5" style="width: 100%; padding: 12px; margin: 10px 0; border-radius: 8px; border: 1px solid #ddd;"></textarea>
                        <button type="submit" class="btn" style="width: 100%;">Envoyer le message</button>
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
        
        <footer>
            <p>© 2023 DashboardPro. Tous droits réservés.</p>
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
    </script>
</body>
</html>