@extends('layouts.apli')

@section('content')
<style>
    .chat-wrapper { max-width: 820px; margin: 0 auto; }
    .chat-box { max-height: 60vh; overflow-y: auto; background: #f7f8fa; border-radius: 14px; padding: 14px; border: 1px solid #e9ecef; }
    .msg-row { margin-bottom: .6rem; position: relative; }
    .msg-bubble { display: inline-block; max-width: 80%; padding: .6rem .8rem; border-radius: 14px; word-wrap: break-word; }
    .msg-me { background: #22c55e; color: #fff; border-top-right-radius: 6px; }
    .msg-them { background: #fff; border: 1px solid #e5e7eb; color: #111827; border-top-left-radius: 6px; }
    .msg-meta { font-size: .78rem; color: #6b7280; margin-top: .15rem; }
    .composer { display: flex; align-items: center; gap: .5rem; margin-top: .8rem; flex-wrap: wrap; }
    .composer .emoji-btn, .composer .file-btn { border: 1px solid #e5e7eb; background: #fff; width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; }
    .composer input[type="text"] { flex: 1; height: 44px; border-radius: 12px; }
    .composer .send-btn { height: 44px; border-radius: 12px; }
    .send-btn:disabled { opacity: .6; cursor: not-allowed; }
    .selected-files { width: 100%; font-size: .85rem; color: #374151; margin-top: .2rem; }
    .selected-files div { margin-bottom: .2rem; }
    .edit-input { width: 100%; margin-top: .2rem; padding: .4rem .6rem; border-radius: 8px; border:1px solid #ccc; }
    .options-menu { display:none; position:absolute; right:0; background:#fff; border:1px solid #ddd; border-radius:6px; z-index:10; }
</style>

<div class="container py-4 chat-wrapper">
    <h3 class="mb-3">💬 Discussion</h3>

    {{-- Zone messages --}}
    <div id="chatBox" class="card chat-box shadow-sm">
        @foreach($messages as $msg)
            @php
                $isMe = (int)$msg->sender_id === (int)auth()->id();
                $isDeleted = ($msg->status === 'deleted' || (is_numeric($msg->status ?? null) && (int)$msg->status === 3));
                $isRead = $msg->status === 'read';
            @endphp
            <div class="msg-row text-{{ $isMe ? 'end' : 'start' }}" data-id="{{ $msg->id }}">
                <div class="msg-bubble {{ $isMe ? 'msg-me' : 'msg-them' }}">
                    @if($isDeleted)
                        <em style="color:#cbd5e1!important">Message supprimé 🗑</em>
                    @else
                        @if($msg->file)
                            @php $filename = $msg->file; @endphp
                            @if(str_starts_with($msg->file_type, 'image'))
                                <img src="{{ asset('storage/'.$filename) }}" style="max-width:200px;border-radius:8px">
                            @elseif(str_starts_with($msg->file_type, 'video'))
                                <video src="{{ asset('storage/'.$filename) }}" controls style="max-width:200px;border-radius:8px"></video>
                            @else
                                <a href="{{ asset('storage/'.$filename) }}" target="_blank" download>{{ basename($filename) }}</a>
                            @endif
                        @endif
                        <div class="msg-text">{{ $msg->message }}</div>
                    @endif
                </div>
                <div class="msg-meta {{ $isMe ? 'text-end position-relative' : 'text-start' }}">
                    {{ optional($msg->created_at)->format('H:i') }}
                    @if($isMe && !$isDeleted)
                        <span class="status-icon">{{ $isRead ? '✅✅' : '✅' }}</span>
                        <span class="msg-options" style="cursor:pointer; margin-left:5px;">⋯</span>
                        <div class="options-menu">
                            <button class="edit-msg btn btn-sm btn-light" {{ $isRead ? 'disabled' : '' }}>Modifier</button>
                            <button class="delete-msg btn btn-sm btn-light" {{ $isRead ? 'disabled' : '' }}>Supprimer</button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Zone saisie --}}
    <form id="chatForm" action="{{ route('messages.store', $receiver_id) }}" method="POST" enctype="multipart/form-data" class="composer">
        @csrf
        <button type="button" id="emojiBtn" class="emoji-btn" title="Emoji">😊</button>
        <button type="button" id="fileBtn" class="file-btn" title="Fichier">📎</button>
        <input type="file" id="fileInput" name="file" style="display:none" multiple>
        <input type="text" id="messageInput" name="message" class="form-control" placeholder="Écrire un message…" autocomplete="off">
        <button type="submit" id="sendBtn" class="btn btn-success send-btn" disabled>➤</button>
        <div id="filePreview" class="w-100"></div>
    </form>
</div>

{{-- Emoji Picker --}}
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.1.1/dist/index.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){

    const input      = document.getElementById('messageInput');
    const fileInput  = document.getElementById('fileInput');
    const fileBtn    = document.getElementById('fileBtn');
    const sendBtn    = document.getElementById('sendBtn');
    const emojiBtn   = document.getElementById('emojiBtn');
    const form       = document.getElementById('chatForm');
    const chatBox    = document.getElementById('chatBox');
    const preview    = document.getElementById('filePreview');

    // Bouton fichier
    fileBtn.addEventListener('click', ()=> fileInput.click());
    fileInput.addEventListener('change', updateFilePreview);

    function updateFilePreview(){
        preview.innerHTML = '';
        Array.from(fileInput.files).forEach((file, idx)=>{
            const div = document.createElement('div');
            div.className = "preview-item";
            if(file.type.startsWith("image")){
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.maxWidth = '100px';
                div.appendChild(img);
            } else if(file.type.startsWith("video")){
                const vid = document.createElement('video');
                vid.src = URL.createObjectURL(file);
                vid.controls = true;
                vid.style.maxWidth = '100px';
                div.appendChild(vid);
            } else {
                div.textContent = "📄 " + file.name;
            }
            const remove = document.createElement('span');
            remove.textContent = " ✖";
            remove.style.cursor = "pointer";
            remove.onclick = ()=>{
                const dt = new DataTransfer();
                Array.from(fileInput.files).forEach((f,i)=>{ if(i!==idx) dt.items.add(f) });
                fileInput.files = dt.files;
                div.remove();
                toggleSend();
            };
            div.appendChild(remove);
            preview.appendChild(div);
        });
        toggleSend();
    }

    function toggleSend(){ sendBtn.disabled = input.value.trim() === '' && !fileInput.files.length; }
    input.addEventListener('input', toggleSend);
    toggleSend();

    // Emoji
    try{
        const picker = new EmojiButton({position:'top-end'});
        emojiBtn.addEventListener('click', ()=> picker.togglePicker(emojiBtn));
        picker.on('emoji', emoji => { input.value += emoji; toggleSend(); input.focus(); });
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
                    input.value=''; fileInput.value=''; preview.innerHTML=''; toggleSend();
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            }).catch(err=>console.error(err));
    });

    function appendMessage(msg, isMe=false){
        const row = document.createElement('div');
        row.className = "msg-row text-" + (isMe?'end':'start');
        row.dataset.id = msg.id;

        let content = `<div class="msg-text">${msg.message}</div>`;
        if(msg.file){
            if(msg.file_type.startsWith("image")) content += `<img src="${msg.file}" style="max-width:200px;border-radius:8px">`;
            else if(msg.file_type.startsWith("video")) content += `<video src="${msg.file}" controls style="max-width:200px;border-radius:8px"></video>`;
            else content += `<a href="${msg.file}" target="_blank" download>${msg.file.split('/').pop()}</a>`;
        }

        row.innerHTML = `
            <div class="msg-bubble ${isMe?'msg-me':'msg-them'}">${content}</div>
            <div class="msg-meta ${isMe?'text-end position-relative':'text-start'}">
                ${msg.time} ${isMe?'✅':''}
                ${isMe?`<span class="msg-options" style="cursor:pointer; margin-left:5px;">⋯</span>
                <div class="options-menu">
                    <button class="edit-msg btn btn-sm btn-light">Modifier</button>
                    <button class="delete-msg btn btn-sm btn-light">Supprimer</button>
                </div>`:''}
            </div>
        `;
        chatBox.appendChild(row);
        attachMessageEvents(row);
    }

    // Ajouter events aux messages existants
    document.querySelectorAll('.msg-row').forEach(attachMessageEvents);

    function attachMessageEvents(row){
        const editBtn = row.querySelector('.edit-msg');
        const deleteBtn = row.querySelector('.delete-msg');
        const optionsBtn = row.querySelector('.msg-options');

        if(optionsBtn) optionsBtn.addEventListener('click', ()=> {
            const menu = optionsBtn.nextElementSibling;
            menu.style.display = menu.style.display==='block'?'none':'block';
        });

        if(editBtn){
            editBtn.addEventListener('click', ()=>{
                const bubble = row.querySelector('.msg-bubble');
                const textDiv = bubble.querySelector('.msg-text');
                if(bubble.querySelector('.edit-input')) return;

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
                              }
                          });
                    }
                    if(e.key==='Escape'){
                        textDiv.style.display = '';
                        editInput.remove();
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
                          row.querySelector('.msg-bubble').innerHTML='<em style="color:#cbd5e1!important">Message supprimé 🗑</em>';
                          deleteBtn.disabled=true;
                          if(editBtn) editBtn.disabled=true;
                      }
                  });
            });
        }
    }

    // ============================
// Récupération automatique des messages
// ============================
let lastMessageId = Math.max(...Array.from(document.querySelectorAll('.msg-row'))
    .map(r => parseInt(r.dataset.id) || 0));

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
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    })
    .catch(err => console.error(err));
}

// Polling toutes les 2 secondes
setInterval(fetchNewMessages, 2000);

// ============================
// Marquer les messages reçus comme lus
// ============================
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

// Vérifier les messages lus toutes les 5 secondes
setInterval(markMessagesAsRead, 5000);


});
</script>
@endsection
