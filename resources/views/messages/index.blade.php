@extends('layouts.clients.client')

@section('title', 'Messages')

@section('content')
<style>
    :root {
        --primary-color: #22c55e;
        --primary-hover: #16a34a;
        --bg-light: #f7f8fa;
        --border-color: #e9ecef;
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --text-light: #9ca3af;
        --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --radius: 14px;
        --radius-sm: 8px;
    }
    
    .chat-wrapper { 
        max-width: 900px; 
        margin: 0 auto;
        height: calc(100vh - 180px);
        display: flex;
        flex-direction: column;
    }
    
    .chat-header {
        display: flex;
        align-items: center;
        padding: 1rem 1.5rem;
        background: #fff;
        border-radius: var(--radius) var(--radius) 0 0;
        border-bottom: 1px solid var(--border-color);
        box-shadow: var(--shadow);
    }
    
    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 12px;
        background-color: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: var(--primary-color);
    }
    
    .user-info h4 {
        margin: 0;
        font-weight: 600;
        color: var(--text-primary);
    }
    
    .user-info p {
        margin: 0;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }
    
    .chat-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
        border-radius: 0 0 var(--radius) var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    
    .chat-box { 
        flex: 1;
        overflow-y: auto; 
        padding: 1.5rem;
        background: var(--bg-light);
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }
    
    .date-divider {
        text-align: center;
        margin: 1rem 0;
        position: relative;
    }
    
    .date-divider span {
        background: #e5e7eb;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        color: var(--text-secondary);
    }
    
    .msg-row { 
        display: flex;
        position: relative;
    }
    
    .msg-row.msg-them {
        justify-content: flex-start;
    }
    
    .msg-row.msg-me {
        justify-content: flex-end;
    }
    
    .msg-container {
        max-width: 70%;
        display: flex;
        flex-direction: column;
    }
    
    .msg-row.msg-them .msg-container {
        align-items: flex-start;
    }
    
    .msg-row.msg-me .msg-container {
        align-items: flex-end;
    }
    
    .msg-bubble { 
        padding: 0.75rem 1rem; 
        border-radius: 18px;
        word-wrap: break-word;
        position: relative;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .msg-me .msg-bubble {
        background: var(--primary-color);
        color: #fff;
        border-bottom-right-radius: 5px;
    }
    
    .msg-them .msg-bubble {
        background: #fff;
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        border-bottom-left-radius: 5px;
    }
    
    .msg-meta { 
        font-size: 0.75rem; 
        color: var(--text-light);
        margin-top: 0.3rem;
        padding: 0 0.3rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .composer { 
        display: flex; 
        align-items: flex-end;
        gap: 0.6rem; 
        padding: 1rem 1.5rem;
        background: #fff;
        border-top: 1px solid var(--border-color);
    }
    
    .composer .action-btn {
        border: 1px solid var(--border-color);
        background: #fff;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    
    .composer .action-btn:hover {
        background: var(--bg-light);
    }
    
    .composer .message-input-container {
        flex: 1;
        position: relative;
    }
    
    .composer input[type="text"] { 
        width: 100%;
        height: 44px;
        border-radius: 22px;
        border: 1px solid var(--border-color);
        padding: 0 1rem;
        font-size: 0.95rem;
    }
    
    .composer input[type="text"]:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.1);
    }
    
    .composer .send-btn { 
        height: 44px;
        width: 44px;
        border-radius: 50%;
        background: var(--primary-color);
        border: none;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    
    .composer .send-btn:hover {
        background: var(--primary-hover);
        transform: scale(1.03);
    }
    
    .send-btn:disabled { 
        opacity: .5; 
        cursor: not-allowed;
        transform: none;
    }
    
    .selected-files { 
        width: 100%; 
        font-size: 0.85rem; 
        color: var(--text-primary);
        margin-top: 0.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .file-preview {
        background: #f1f5f9;
        border-radius: var(--radius-sm);
        padding: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .file-preview .remove-file {
        cursor: pointer;
        color: #ef4444;
        font-weight: bold;
    }
    
    .edit-input { 
        width: 100%; 
        margin-top: 0.2rem; 
        padding: 0.6rem 0.8rem; 
        border-radius: var(--radius-sm); 
        border: 1px solid #ccc; 
        font-size: 0.95rem;
    }
    
    .options-menu { 
        display: none; 
        position: absolute;
        top: 100%;
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: var(--radius-sm);
        z-index: 10;
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    
    .options-menu button {
        display: block;
        width: 100%;
        text-align: left;
        padding: 0.6rem 1rem;
        background: none;
        border: none;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .options-menu button:hover {
        background: #f3f4f6;
    }
    
    .options-menu button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .msg-options {
        cursor: pointer;
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        transition: background 0.2s;
        user-select: none;
    }
    
    .msg-options:hover {
        background: rgba(0, 0, 0, 0.05);
    }
    
    .typing-indicator {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.5rem 1rem;
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        width: fit-content;
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }
    
    .typing-dots {
        display: flex;
        gap: 2px;
    }
    
    .typing-dot {
        width: 6px;
        height: 6px;
        background: var(--text-light);
        border-radius: 50%;
        animation: typingAnimation 1.4s infinite ease-in-out;
    }
    
    .typing-dot:nth-child(1) { animation-delay: 0s; }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }
    
    @keyframes typingAnimation {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-4px); }
    }
    
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: var(--text-secondary);
    }
    
    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: block;
        color: #d1d5db;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .chat-wrapper {
            height: calc(100vh - 140px);
        }
        
        .chat-header {
            padding: 0.8rem 1rem;
        }
        
        .chat-box {
            padding: 1rem;
        }
        
        .composer {
            padding: 0.8rem 1rem;
        }
        
        .msg-container {
            max-width: 85%;
        }
    }
</style>

<div class="chat-wrapper">
    <!-- Header avec info du destinataire -->
    <div class="chat-header">
        <div class="user-avatar">
            {{-- {{ strtoupper(substr($receiver_name, 0, 1)) }} --}}
        </div>
        <div class="user-info">
            {{-- <h4>{{ $receiver_name }}</h4> --}}
            <p id="userStatus">En ligne</p>
        </div>
    </div>
    
    <div class="chat-container">
        {{-- Zone messages --}}
        <div id="chatBox" class="chat-box">
            @if($messages->count() == 0)
                <div class="empty-state">
                    <i>💬</i>
                    <p>Aucun message échangé pour le moment</p>
                    <small>Envoyez votre premier message pour commencer la conversation</small>
                </div>
            @else
                <div class="date-divider">
                    <span>Aujourd'hui</span>
                </div>
                
                @foreach($messages as $msg)
                    @php
                        $isMe = (int)$msg->sender_id === (int)auth()->id();
                        $isDeleted = ($msg->status === 'deleted' || (is_numeric($msg->status ?? null) && (int)$msg->status === 3));
                        $isRead = $msg->status === 'read';
                    @endphp
                    <div class="msg-row {{ $isMe ? 'msg-me' : 'msg-them' }}" data-id="{{ $msg->id }}">
                        <div class="msg-container">
                            <div class="msg-bubble">
                                @if($isDeleted)
                                    <em style="color:var(--text-light)!important">Message supprimé</em>
                                @else
                                    @if($msg->file)
                                        @php $filename = $msg->file; @endphp
                                        @if(str_starts_with($msg->file_type, 'image'))
                                            <img src="{{ asset('storage/'.$filename) }}" style="max-width:200px; border-radius:8px; margin-bottom:0.5rem;">
                                        @elseif(str_starts_with($msg->file_type, 'video'))
                                            <video src="{{ asset('storage/'.$filename) }}" controls style="max-width:200px; border-radius:8px; margin-bottom:0.5rem;"></video>
                                        @else
                                            <a href="{{ asset('storage/'.$filename) }}" target="_blank" download style="display:block; margin-bottom:0.5rem;">
                                                <div style="display:flex; align-items:center; gap:0.5rem;">
                                                    <span style="font-size:1.5rem;">📄</span>
                                                    <span>{{ basename($filename) }}</span>
                                                </div>
                                            </a>
                                        @endif
                                    @endif
                                    @if($msg->message)
                                        <div class="msg-text">{{ $msg->message }}</div>
                                    @endif
                                @endif
                            </div>
                            <div class="msg-meta">
                                {{ optional($msg->created_at)->format('H:i') }}
                                @if($isMe && !$isDeleted)
                                    <span class="status-icon">{{ $isRead ? '✅✅' : '✅' }}</span>
                                    <span class="msg-options">⋯</span>
                                    <div class="options-menu">
                                        <button class="edit-msg" {{ $isRead ? 'disabled' : '' }}>Modifier</button>
                                        <button class="delete-msg" {{ $isRead ? 'disabled' : '' }}>Supprimer</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            
            <!-- Indicateur de frappe -->
            <div id="typingIndicator" class="msg-row msg-them" style="display: none;">
                <div class="msg-container">
                    <div class="typing-indicator">
                        {{-- <span>{{ $receiver_name }} est en train d'écrire</span> --}}
                        <div class="typing-dots">
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Zone saisie --}}
        <form id="chatForm" action="{{ route('messages.store', $receiver_id) }}" method="POST" enctype="multipart/form-data" class="composer">
            @csrf
            <button type="button" id="emojiBtn" class="action-btn" title="Emoji">😊</button>
            <button type="button" id="fileBtn" class="action-btn" title="Fichier">📎</button>
            <input type="file" id="fileInput" name="file" style="display:none" multiple>
            
            <div class="message-input-container">
                <input type="text" id="messageInput" name="message" class="form-control" placeholder="Écrire un message…" autocomplete="off">
                <div id="filePreview" class="selected-files"></div>
            </div>
            
            <button type="submit" id="sendBtn" class="send-btn" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </form>
    </div>
</div>

{{-- Emoji Picker --}}
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.1.1/dist/index.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Éléments DOM
    const input      = document.getElementById('messageInput');
    const fileInput  = document.getElementById('fileInput');
    const fileBtn    = document.getElementById('fileBtn');
    const sendBtn    = document.getElementById('sendBtn');
    const emojiBtn   = document.getElementById('emojiBtn');
    const form       = document.getElementById('chatForm');
    const chatBox    = document.getElementById('chatBox');
    const preview    = document.getElementById('filePreview');
    const typingIndicator = document.getElementById('typingIndicator');

    // Variables d'état
    let lastMessageId = Math.max(...Array.from(document.querySelectorAll('.msg-row'))
        .map(r => parseInt(r.dataset.id) || 0), 0);
    let isTyping = false;
    let typingTimer;

    // Initialisation
    scrollToBottom();
    attachMessageEvents();

    // Bouton fichier
    fileBtn.addEventListener('click', ()=> fileInput.click());
    fileInput.addEventListener('change', updateFilePreview);

    // Fonction pour afficher les fichiers sélectionnés
    function updateFilePreview(){
        preview.innerHTML = '';
        Array.from(fileInput.files).forEach((file, idx)=>{
            const fileDiv = document.createElement('div');
            fileDiv.className = "file-preview";
            
            if(file.type.startsWith("image")){
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.maxWidth = '40px';
                img.style.maxHeight = '40px';
                img.style.borderRadius = '4px';
                fileDiv.appendChild(img);
            } else if(file.type.startsWith("video")){
                const vidIcon = document.createElement('span');
                vidIcon.textContent = '🎬';
                vidIcon.style.fontSize = '1.2rem';
                fileDiv.appendChild(vidIcon);
            } else {
                const fileIcon = document.createElement('span');
                fileIcon.textContent = '📄';
                fileIcon.style.fontSize = '1.2rem';
                fileDiv.appendChild(fileIcon);
            }
            
            const fileName = document.createElement('span');
            fileName.textContent = file.name.length > 15 ? file.name.substring(0, 15) + '...' : file.name;
            fileName.style.fontSize = '0.8rem';
            fileDiv.appendChild(fileName);
            
            const remove = document.createElement('span');
            remove.textContent = "✕";
            remove.className = "remove-file";
            remove.onclick = ()=>{
                const dt = new DataTransfer();
                Array.from(fileInput.files).forEach((f,i)=>{ if(i!==idx) dt.items.add(f) });
                fileInput.files = dt.files;
                fileDiv.remove();
                toggleSend();
            };
            fileDiv.appendChild(remove);
            preview.appendChild(fileDiv);
        });
        toggleSend();
    }

    // Activer/désactiver le bouton d'envoi
    function toggleSend(){ 
        sendBtn.disabled = input.value.trim() === '' && !fileInput.files.length; 
    }
    
    input.addEventListener('input', function() {
        toggleSend();
        handleTyping();
    });
    
    toggleSend();

    // Gestion de l'indicateur de frappe
    function handleTyping() {
        if (!isTyping) {
            isTyping = true;
            // Notifier le serveur que l'utilisateur est en train d'écrire
            // fetch(...) 
        }
        
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            isTyping = false;
            // Notifier le serveur que l'utilisateur a arrêté d'écrire
            // fetch(...)
        }, 1000);
    }

    // Emoji Picker
    try{
        const picker = new EmojiButton({position:'top-end', theme: 'auto'});
        emojiBtn.addEventListener('click', ()=> picker.togglePicker(emojiBtn));
        picker.on('emoji', emoji => { 
            input.value += emoji; 
            toggleSend(); 
            input.focus(); 
        });
    }catch(e){ console.warn("Emoji picker non initialisé:", e); }

    // Envoyer message
    form.addEventListener('submit', function(e){
        e.preventDefault();
        if(input.value.trim() === '' && !fileInput.files.length) return;

        const formData = new FormData(form);
        sendBtn.disabled = true;

        fetch(form.action, { method:'POST', body: formData, headers: {'X-Requested-With':'XMLHttpRequest'} })
            .then(res=>res.json())
            .then(data=>{
                if(data.success){
                    appendMessage(data.message, true);
                    input.value=''; 
                    fileInput.value=''; 
                    preview.innerHTML=''; 
                    toggleSend();
                    scrollToBottom();
                }
            }).catch(err=>console.error(err));
    });

    // Ajouter un message à la conversation
    function appendMessage(msg, isMe=false){
        // Empêcher les doublons
        if(document.querySelector(`.msg-row[data-id="${msg.id}"]`)) return;

        // Supprimer l'état vide s'il existe
        const emptyState = document.querySelector('.empty-state');
        if(emptyState) emptyState.remove();

        const row = document.createElement('div');
        row.className = `msg-row ${isMe ? 'msg-me' : 'msg-them'}`;
        row.dataset.id = msg.id;

        let content = "";
        if(msg.file){
            if(msg.file_type && msg.file_type.startsWith("image")){
                content += `<img src="${msg.file}" style="max-width:200px; border-radius:8px; margin-bottom:0.5rem;">`;
            } else if(msg.file_type && msg.file_type.startsWith("video")){
                content += `<video src="${msg.file}" controls style="max-width:200px; border-radius:8px; margin-bottom:0.5rem;"></video>`;
            } else {
                content += `<a href="${msg.file}" target="_blank" download style="display:block; margin-bottom:0.5rem;">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <span style="font-size:1.5rem;">📄</span>
                        <span>${msg.file.split('/').pop()}</span>
                    </div>
                </a>`;
            }
        }
        if(msg.message){
            content += `<div class="msg-text">${msg.message}</div>`;
        }

        row.innerHTML = `
            <div class="msg-container">
                <div class="msg-bubble">${content}</div>
                <div class="msg-meta">
                    ${msg.time ?? ''} ${isMe ? '✅' : ''}
                    ${isMe ? `
                        <span class="msg-options">⋯</span>
                        <div class="options-menu">
                            <button class="edit-msg" ${msg.status === 'read' ? 'disabled' : ''}>Modifier</button>
                            <button class="delete-msg" ${msg.status === 'read' ? 'disabled' : ''}>Supprimer</button>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
        
        // Insérer avant l'indicateur de frappe
        chatBox.insertBefore(row, typingIndicator);
        attachMessageEventsToElement(row);
    }

    // Attacher les événements aux messages
    function attachMessageEvents() {
        document.querySelectorAll('.msg-row').forEach(attachMessageEventsToElement);
    }

    function attachMessageEventsToElement(row){
        const editBtn = row.querySelector('.edit-msg');
        const deleteBtn = row.querySelector('.delete-msg');
        const optionsBtn = row.querySelector('.msg-options');
        const optionsMenu = row.querySelector('.options-menu');

        if(optionsBtn) {
            optionsBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                // Fermer tous les autres menus ouverts
                document.querySelectorAll('.options-menu').forEach(menu => {
                    if (menu !== optionsMenu) menu.style.display = 'none';
                });
                
                optionsMenu.style.display = optionsMenu.style.display === 'block' ? 'none' : 'block';
            });
        }

        // Fermer le menu si on clique ailleurs
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.options-menu') && !e.target.closest('.msg-options')) {
                document.querySelectorAll('.options-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
            }
        });

        if(editBtn){
            editBtn.addEventListener('click', ()=>{
                const bubble = row.querySelector('.msg-bubble');
                const textDiv = bubble.querySelector('.msg-text');
                if(!textDiv || bubble.querySelector('.edit-input')) return;

                const editInput = document.createElement('input');
                editInput.type = "text";
                editInput.value = textDiv.textContent;
                editInput.className = 'edit-input';
                bubble.appendChild(editInput);
                textDiv.style.display = 'none';
                editInput.focus();

                editInput.addEventListener('keydown', e=>{
                    if(e.key==='Enter'){
                        fetch(`/messages/${row.dataset.id}`, {
                            method:'PUT',
                            headers:{
                                'Content-Type':'application/json',
                                'X-CSRF-TOKEN':'{{ csrf_token() }}',
                                'X-Requested-With':'XMLHttpRequest'
                            },
                            body: JSON.stringify({ message: editInput.value })
                        }).then(res=>res.json())
                          .then(data=>{
                              if(data.success){
                                  textDiv.textContent = editInput.value;
                                  textDiv.style.display = '';
                                  editInput.remove();
                                  optionsMenu.style.display = 'none';
                              }
                          });
                    }
                    if(e.key==='Escape'){
                        textDiv.style.display = '';
                        editInput.remove();
                        optionsMenu.style.display = 'none';
                    }
                });
            });
        }

        if(deleteBtn){
            deleteBtn.addEventListener('click', ()=>{
                if(!confirm("Voulez-vous vraiment supprimer ce message ?")) return;
                fetch(`/messages/${row.dataset.id}`, {
                    method:'DELETE',
                    headers:{ 
                        'X-CSRF-TOKEN':'{{ csrf_token() }}',
                        'X-Requested-With':'XMLHttpRequest'
                    }
                }).then(res=>res.json())
                  .then(data=>{
                      if(data.success){
                          row.querySelector('.msg-bubble').innerHTML='<em style="color:var(--text-light)!important">Message supprimé</em>';
                          if(deleteBtn) deleteBtn.disabled=true;
                          if(editBtn) editBtn.disabled=true;
                          optionsMenu.style.display = 'none';
                      }
                  });
            });
        }
    }

    // Récupération automatique des messages
    function fetchNewMessages() {
        fetch("{{ route('messages.fetch', $receiver_id) }}?after=" + lastMessageId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.messages && data.messages.length > 0){
                data.messages.forEach(msg => {
                    appendMessage(msg, msg.sender_id == {{ auth()->id() }});
                    lastMessageId = Math.max(lastMessageId, msg.id);
                });
                scrollToBottom();
            }
            
            // Vérifier l'indicateur de frappe
            if(data.typing) {
                typingIndicator.style.display = 'flex';
            } else {
                typingIndicator.style.display = 'none';
            }
        })
        .catch(err => console.error(err));
    }

    // Marquer les messages reçus comme lus
    function markMessagesAsRead() {
        fetch("{{ route('messages.read', $receiver_id) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(res => res.json())
          .then(data => {
              if(data.success){
                  document.querySelectorAll('.msg-row').forEach(row => {
                      if(row.dataset.id <= lastMessageId && row.querySelector('.status-icon')) {
                          row.querySelector('.status-icon').textContent = '✅✅';
                      }
                  });
              }
          });
    }

    // Fonction pour faire défiler vers le bas
    function scrollToBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Polling toutes les 2 secondes pour les nouveaux messages
    setInterval(fetchNewMessages, 2000);
    
    // Vérifier les messages lus toutes les 5 secondes
    setInterval(markMessagesAsRead, 5000);
});
</script>
@endsection