<!-- resources/views/chatbot/index.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Assistant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .chat-container {
            max-height: 400px;
            overflow-y: auto;
            scroll-behavior: smooth;
        }
        .message-user {
            background-color: #3b82f6;
            color: white;
            border-radius: 18px 18px 0 18px;
        }
        .message-bot {
            background-color: #f3f4f6;
            color: #374151;
            border-radius: 18px 18px 18px 0;
        }
        .typing-indicator {
            display: inline-flex;
            align-items: center;
        }
        .typing-dot {
            width: 8px;
            height: 8px;
            background-color: #9ca3af;
            border-radius: 50%;
            margin: 0 3px;
            animation: typing-animation 1.4s infinite ease-in-out;
        }
        .typing-dot:nth-child(1) { animation-delay: 0s; }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing-animation {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto my-10 bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-blue-600 px-4 py-3 text-white flex items-center">
            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center mr-3">
                <i class="fas fa-robot"></i>
            </div>
            <div>
                <h2 class="font-semibold">Assistant Virtuel</h2>
                <p class="text-xs opacity-80">En ligne</p>
            </div>
        </div>
        
        <!-- Chat Messages -->
        <div class="chat-container p-4 space-y-3" id="chatMessages">
            <div class="flex justify-start">
                <div class="message-bot px-4 py-2 max-w-xs">
                    <p>Bonjour! Je suis votre assistant virtuel. Je peux vous renseigner sur nos produits, leurs prix et leur disponibilité.</p>
                </div>
            </div>
        </div>
        
        <!-- Input Area -->
        <div class="border-t border-gray-200 p-3">
            <div class="flex items-center">
                <input 
                    type="text" 
                    id="messageInput" 
                    placeholder="Tapez votre message..." 
                    class="flex-1 border border-gray-300 rounded-l-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    autocomplete="off"
                >
                <button 
                    id="sendButton" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700 transition duration-200"
                >
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatMessages = document.getElementById('chatMessages');
            const messageInput = document.getElementById('messageInput');
            const sendButton = document.getElementById('sendButton');
            
            // Fonction pour ajouter un message dans le chat
            function addMessage(sender, message, isTyping = false) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `flex ${sender === 'user' ? 'justify-end' : 'justify-start'}`;
                
                const messageContent = document.createElement('div');
                messageContent.className = `px-4 py-2 max-w-xs ${sender === 'user' ? 'message-user' : 'message-bot'}`;
                
                if (isTyping) {
                    messageContent.innerHTML = `
                        <div class="typing-indicator">
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                        </div>
                    `;
                } else {
                    messageContent.textContent = message;
                }
                
                messageDiv.appendChild(messageContent);
                chatMessages.appendChild(messageDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
            
            // Fonction pour envoyer un message
            async function sendMessage() {
                const message = messageInput.value.trim();
                if (!message) return;
                
                // Ajouter le message de l'utilisateur
                addMessage('user', message);
                messageInput.value = '';
                
                // Afficher l'indicateur de frappe
                addMessage('bot', '', true);
                
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
                    chatMessages.removeChild(chatMessages.lastChild);
                    
                    // Ajouter la réponse du bot
                    addMessage('bot', data.response);
                } catch (error) {
                    // Supprimer l'indicateur de frappe
                    chatMessages.removeChild(chatMessages.lastChild);
                    
                    // Afficher un message d'erreur
                    addMessage('bot', 'Désolé, une erreur s\'est produite. Veuillez réessayer.');
                    console.error('Erreur:', error);
                }
            }
            
            // Événements
            sendButton.addEventListener('click', sendMessage);
            
            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        });
    </script>
</body>
</html>