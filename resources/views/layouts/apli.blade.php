<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Mon Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Styles personnalisés pour le chatbot */
        .chatbot-message {
            max-width: 80%;
            word-wrap: break-word;
        }
        .chatbot-user-message {
            background-color: #0d6efd;
            color: white;
            border-radius: 18px 18px 5px 18px;
        }
        .chatbot-bot-message {
            background-color: #f8f9fa;
            color: #212529;
            border-radius: 18px 18px 18px 5px;
        }
        #chatbot-container {
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        #chatbot-messages {
            height: 300px;
            overflow-y: auto;
        }
        .chatbot-typing {
            display: inline-block;
        }
        .chatbot-typing-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #6c757d;
            border-radius: 50%;
            margin: 0 3px;
            animation: chatbot-typing 1.4s infinite ease-in-out;
        }
        .chatbot-typing-dot:nth-child(1) { animation-delay: 0s; }
        .chatbot-typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .chatbot-typing-dot:nth-child(3) { animation-delay: 0.4s; }
        
        @keyframes chatbot-typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-5px); }
        }
        
        /* CORRECTION DU PROBLÈME DE CLIC */
        #chatbot-widget {
            z-index: 999;
            pointer-events: none; /* Désactive les événements sur toute la zone */
        }
        
        #chatbot-toggle {
            z-index: 1000;
            pointer-events: auto; /* Réactive les événements uniquement sur le bouton */
        }
        
        #chatbot-container {
            z-index: 1001;
            pointer-events: auto; /* Réactive les événements dans le conteneur */
        }
        
        /* Assurer que le contenu principal a un z-index approprié */
        .container {
            position: relative;
            z-index: 1;
        }
        
        /* S'assurer que tous les éléments interactifs sont accessibles */
        .btn, a, input, select, textarea, button {
            position: relative;
            z-index: 2;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container py-4">
        @include('partials.notifications')
        @yield('content')
    </div>

    <!-- Widget de chatbot flottant À GAUCHE - CORRIGÉ -->
    <div id="chatbot-widget" class="fixed-bottom" style="bottom: 20px; left: 20px; pointer-events: none;">
        <button id="chatbot-toggle" class="btn btn-primary rounded-circle" style="width: 60px; height: 60px; pointer-events: auto;">
            <i class="fas fa-robot fa-lg"></i>
        </button>
        
        <div id="chatbot-container" class="card d-none" style="width: 350px; position: absolute; bottom: 70px; left: 0; pointer-events: auto;">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                        <i class="fas fa-robot"></i>
                    </div>
                    <span class="fw-bold">Assistant Virtuel</span>
                </div>
                <button id="chatbot-close" class="btn btn-sm btn-link text-white p-0">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="chatbot-messages" class="card-body p-3" style="max-height: 300px; overflow-y: auto;">
                <div class="d-flex justify-content-start mb-2">
                    <div class="chatbot-message chatbot-bot-message p-3">
                        <p class="mb-0">Bonjour ! Comment puis-je vous aider aujourd'hui ?</p>
                    </div>
                </div>
            </div>
            
            <div class="card-footer p-3">
                <div class="input-group">
                    <input 
                        type="text" 
                        id="chatbot-input" 
                        placeholder="Tapez votre message..." 
                        class="form-control"
                        autocomplete="off"
                    >
                    <button id="chatbot-send" class="btn btn-primary">
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
                messageDiv.className = `d-flex justify-content-${sender === 'user' ? 'end' : 'start'} mb-2`;
                
                const messageContent = document.createElement('div');
                messageContent.className = `chatbot-message p-3 ${sender === 'user' ? 'chatbot-user-message' : 'chatbot-bot-message'}`;
                
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
                    chatbotContainer.classList.remove('d-none');
                    setTimeout(() => {
                        chatbotInput.focus();
                    }, 100);
                } else {
                    chatbotContainer.classList.add('d-none');
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
    @stack('scripts')
</body>
</html>