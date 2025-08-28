<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Boutique')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* ===== STYLES DU CHATBOT ===== */
        .chatbot-wrapper {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 10000;
        }

        /* Bouton du chatbot */
        #chatbot-toggle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fd0ddd, #e83e8c);
            border: none;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(253, 13, 221, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #chatbot-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(253, 13, 221, 0.4);
        }

        /* Container du chatbot */
        #chatbot-container {
            position: absolute;
            bottom: 70px;
            right: 0;
            width: 350px;
            height: 450px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 10001;
        }

        #chatbot-container.open {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* Header du chatbot */
        .chatbot-header {
            background: linear-gradient(135deg, #fd0ddd, #e83e8c);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chatbot-header-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chatbot-avatar {
            width: 35px;
            height: 35px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fd0ddd;
            font-size: 1.1rem;
        }

        .chatbot-title {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .chatbot-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .chatbot-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Zone des messages */
        #chatbot-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            background: #f8f9fa;
        }

        /* Styles des messages */
        .message-container {
            display: flex;
            width: 100%;
        }

        .message-container.user {
            justify-content: flex-end;
        }

        .message-container.bot {
            justify-content: flex-start;
        }

        .chatbot-message {
            max-width: 80%;
            padding: 12px 16px;
            border-radius: 18px;
            word-wrap: break-word;
            line-height: 1.4;
        }

        .user .chatbot-message {
            background: linear-gradient(135deg, #fd0ddd, #e83e8c);
            color: white;
            border-radius: 18px 18px 5px 18px;
        }

        .bot .chatbot-message {
            background: white;
            color: #333;
            border-radius: 18px 18px 18px 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Indicateur de frappe */
        .chatbot-typing {
            display: flex;
            gap: 4px;
            padding: 12px 16px;
        }

        .chatbot-typing-dot {
            width: 8px;
            height: 8px;
            background-color: #e83e8c;
            border-radius: 50%;
            animation: chatbot-typing 1.4s infinite ease-in-out;
        }

        .chatbot-typing-dot:nth-child(1) { animation-delay: 0s; }
        .chatbot-typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .chatbot-typing-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes chatbot-typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-5px); }
        }

        /* Zone de saisie */
        .chatbot-input-container {
            padding: 15px;
            background: white;
            border-top: 1px solid #eee;
        }

        .chatbot-input-group {
            display: flex;
            gap: 10px;
        }

        #chatbot-input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            outline: none;
            transition: border-color 0.3s;
            font-size: 0.9rem;
        }

        #chatbot-input:focus {
            border-color: #fd0ddd;
        }

        #chatbot-send {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fd0ddd, #e83e8c);
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }

        #chatbot-send:hover {
            transform: scale(1.05);
        }

        /* Scrollbar personnalisée */
        #chatbot-messages::-webkit-scrollbar {
            width: 6px;
        }

        #chatbot-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #chatbot-messages::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #fd0ddd, #e83e8c);
            border-radius: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .chatbot-wrapper {
                bottom: 20px;
                right: 20px;
            }

            #chatbot-container {
                width: 90vw;
                right: 5vw;
                height: 400px;
            }

            #chatbot-toggle {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }

            .chatbot-message {
                max-width: 85%;
            }
        }

        @media (max-width: 480px) {
            #chatbot-container {
                width: 95vw;
                right: 2.5vw;
                height: 350px;
            }

            .chatbot-header {
                padding: 12px 15px;
            }

            #chatbot-messages {
                padding: 15px;
            }
        }

        /* ===== STYLES EXISTANTS ===== */
        :root {
            --primary: #f506c4;
            --primary-light: #ff33d1;
            --primary-dark: #c0049b;
            --secondary: #007bff;
            --accent: #ff7b00;
            --dark: #1e293b;
            --dark-light: #334155;
            --light: #ffffff;
            --sidebar-width: 280px;
            --sidebar-collapsed: 85px;
            --header-height: 80px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --radius: 12px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --mobile-breakpoint: 1024px;
            --tablet-breakpoint: 768px;
            --phone-breakpoint: 576px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: #fff4f4;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Container principal */
        .dashboard-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
            position: relative;
        }
        
        /* Contenu principal */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            min-height: 100vh;
            position: relative;
        }
        
        .main-content.expanded {
            margin-left: var(--sidebar-collapsed);
        }
        
        /* Section Dashboard */
        .dashboard-content {
            padding: 20px;
            flex: 1;
            margin-top: var(--header-height);
            transition: var(--transition);
        }
        
        /* Section Hero avec image de fond */
        .hero-section {
            position: relative;
            padding: 60px 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .hero-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1497215842964-222b430dc094?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80');
            background-size: cover;
            background-position: center;
            opacity: 0.15;
            z-index: -1;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: #333;
        }
        
        .hero-content h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #d46a6a;
        }
        
        .hero-content p {
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 25px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #d46a6a;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #bf5a5a;
        }
        
        /* Grille de cartes */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .card h3 {
            color: #d46a6a;
            margin-bottom: 15px;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .main-content {
                margin-left: var(--sidebar-collapsed) !important;
            }
            
            .hero-section {
                padding: 40px 30px;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-content {
                padding: 15px;
            }
            
            .hero-section {
                padding: 30px 20px;
                margin-bottom: 20px;
            }
            
            .hero-content h1 {
                font-size: 2rem;
            }
            
            .hero-content p {
                font-size: 1rem;
            }
            
            .cards-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
        
        @media (max-width: 576px) {
            .dashboard-content {
                padding: 10px;
            }
            
            .hero-section {
                padding: 20px 15px;
                border-radius: 8px;
            }
            
            .hero-content h1 {
                font-size: 1.75rem;
            }
            
            .btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="dashboard-container">
        {{-- Sidebar --}}
        @include('layouts.clients.sidebar')
        
        <div class="main-content" id="mainContent">
            {{-- Header --}}
            @include('layouts.clients.header')
            
            {{-- Section personnalisée --}}
            <div class="dashboard-content">
                <!-- Contenu spécifique à la page -->
                @include('partials.notifications')
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Widget de chatbot flottant À DROITE - DESIGN PROPRE -->
    <div class="chatbot-wrapper">
        <button id="chatbot-toggle">
            <i class="fas fa-robot"></i>
        </button>
        
        <div id="chatbot-container">
            <div class="chatbot-header">
                <div class="chatbot-header-content">
                    <div class="chatbot-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <span class="chatbot-title">Assistant Virtuel</span>
                </div>
                <button class="chatbot-close" id="chatbot-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="chatbot-messages">
                <div class="message-container bot">
                    <div class="chatbot-message">
                        Bonjour ! 👋 Comment puis-je vous aider aujourd'hui ?
                    </div>
                </div>
            </div>
            
            <div class="chatbot-input-container">
                <div class="chatbot-input-group">
                    <input 
                        type="text" 
                        id="chatbot-input" 
                        placeholder="Tapez votre message..." 
                        autocomplete="off"
                    >
                    <button id="chatbot-send">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatbotToggle = document.getElementById('chatbot-toggle');
            const chatbotContainer = document.getElementById('chatbot-container');
            const chatbotClose = document.getElementById('chatbot-close');
            const chatbotInput = document.getElementById('chatbot-input');
            const chatbotSend = document.getElementById('chatbot-send');
            const chatbotMessages = document.getElementById('chatbot-messages');
            
            // État du chatbot
            let isChatbotOpen = false;
            
            // Fonction pour ajouter un message dans le chat
            function addChatbotMessage(sender, message, isTyping = false) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message-container ${sender}`;
                
                const messageContent = document.createElement('div');
                messageContent.className = 'chatbot-message';
                
                if (isTyping) {
                    messageContent.innerHTML = `
                        <div class="chatbot-typing">
                            <div class="chatbot-typing-dot"></div>
                            <div class="chatbot-typing-dot"></div>
                            <div class="chatbot-typing-dot"></div>
                        </div>
                    `;
                } else {
                    messageContent.textContent = message;
                }
                
                messageDiv.appendChild(messageContent);
                chatbotMessages.appendChild(messageDiv);
                chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
            }
            
            // Fonction pour envoyer un message
            async function sendChatbotMessage() {
                const message = chatbotInput.value.trim();
                if (!message) return;
                
                // Ajouter le message de l'utilisateur
                addChatbotMessage('user', message);
                chatbotInput.value = '';
                
                // Afficher l'indicateur de frappe
                addChatbotMessage('bot', '', true);
                
                try {
                    // Envoyer la requête au serveur
                    const response = await fetch('{{ route("chatbot.send") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: message })
                    });
                    
                    const data = await response.json();
                    
                    // Supprimer l'indicateur de frappe
                    chatbotMessages.removeChild(chatbotMessages.lastChild);
                    
                    // Ajouter la réponse du bot
                    addChatbotMessage('bot', data.response);
                } catch (error) {
                    // Supprimer l'indicateur de frappe
                    chatbotMessages.removeChild(chatbotMessages.lastChild);
                    
                    // Afficher un message d'erreur
                    addChatbotMessage('bot', 'Désolé, une erreur s\'est produite. Veuillez réessayer.');
                    console.error('Erreur:', error);
                }
            }
            
            // Fonction pour ouvrir/fermer le chatbot
            function toggleChatbot() {
                isChatbotOpen = !isChatbotOpen;
                if (isChatbotOpen) {
                    chatbotContainer.classList.add('open');
                    setTimeout(() => {
                        chatbotInput.focus();
                    }, 100);
                } else {
                    chatbotContainer.classList.remove('open');
                }
            }
            
            // Événements
            chatbotToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleChatbot();
            });
            
            chatbotClose.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleChatbot();
            });
            
            chatbotSend.addEventListener('click', function(e) {
                e.stopPropagation();
                sendChatbotMessage();
            });
            
            chatbotInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.stopPropagation();
                    sendChatbotMessage();
                }
            });
            
            // Fermer le chatbot quand on clique en dehors
            document.addEventListener('click', function(e) {
                if (isChatbotOpen && 
                    !chatbotContainer.contains(e.target) && 
                    !chatbotToggle.contains(e.target)) {
                    toggleChatbot();
                }
            });
            
            // Empêcher la propagation des clics dans le chatbot
            chatbotContainer.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>

    <!-- Le reste de votre code JavaScript existant -->
    <script>
        // Script pour gérer la communication entre les composants
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const header = document.querySelector('.header');
            const toggleBtn = document.getElementById('toggleSidebar');
            const mobileMenuBtn = document.getElementById('mobileMenuButton');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            // Fonction pour basculer la sidebar
            function toggleSidebar() {
                if (window.innerWidth < 1024) {
                    // Sur mobile, on utilise l'overlay
                    sidebar.classList.toggle('mobile-open');
                    mobileOverlay.classList.toggle('active');
                } else {
                    // Sur desktop, on réduit/étend normalement
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                    
                    // Mettre à jour l'icône du bouton
                    const icon = toggleBtn.querySelector('i');
                    if (sidebar.classList.contains('collapsed')) {
                        icon.classList.remove('fa-chevron-left');
                        icon.classList.add('fa-chevron-right');
                    } else {
                        icon.classList.remove('fa-chevron-right');
                        icon.classList.add('fa-chevron-left');
                    }
                }
            }
            
            // Écouter les clics sur le bouton de toggle
            if (toggleBtn) {
                toggleBtn.addEventListener('click', toggleSidebar);
            }
            
            // Mobile menu button
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebar.classList.add('mobile-open');
                    mobileOverlay.classList.add('active');
                });
            }
            
            // Mobile overlay
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-open');
                    mobileOverlay.classList.remove('active');
                });
            }
            
            // Gestion responsive
            function handleResize() {
                if (window.innerWidth < 1024) {
                    // Mode mobile/tablette
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                    
                    // Afficher le bouton menu mobile
                    if (mobileMenuBtn) mobileMenuBtn.style.display = 'flex';
                } else {
                    // Mode desktop
                    sidebar.classList.remove('collapsed', 'mobile-open');
                    mainContent.classList.remove('expanded');
                    
                    // Cacher le bouton menu mobile et l'overlay
                    if (mobileMenuBtn) mobileMenuBtn.style.display = 'none';
                    if (mobileOverlay) mobileOverlay.classList.remove('active');
                }
            }
            
            // Écouter les changements de taille de fenêtre
            window.addEventListener('resize', handleResize);
            handleResize(); // Appel initial
            
            // Fermer le sidebar mobile quand on clique sur un élément de menu
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        sidebar.classList.remove('mobile-open');
                        mobileOverlay.classList.remove('active');
                    }
                });
            });
        });
    </script>
</body>
</html>
