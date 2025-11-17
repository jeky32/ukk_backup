@extends('layouts.teamlead')

@section('title', 'Messages - Team Lead')
@section('page-title', 'Messages')
@section('page-subtitle', 'Chat dengan team members Anda')

@push('styles')
<style>
    .messages-container {
        display: flex;
        flex: 1;
        background-color: #fff;
        height: calc(100vh - 120px);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* ===== SIDEBAR KIRI CHAT (30%) ===== */
    .chat-sidebar {
        width: 30%;
        min-width: 300px;
        background-color: #fff;
        border-right: 1px solid #E5E7EB;
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        padding: 20px;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sidebar-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #1F2937;
    }

    .btn-new-chat {
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 12px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-new-chat:hover {
        opacity: 0.9;
    }

    .search-container {
        padding: 16px 20px;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #F9FAFB;
    }

    .search-container i {
        color: #6B7280;
        font-size: 16px;
    }

    .search-input {
        flex: 1;
        border: 1px solid #E5E7EB;
        padding: 10px 14px;
        border-radius: 12px;
        font-size: 14px;
        outline: none;
        background-color: #fff;
    }

    .search-input:focus {
        border-color: #2563EB;
    }

    .chat-list {
        flex: 1;
        overflow-y: auto;
    }

    .chat-item {
        display: flex;
        align-items: center;
        padding: 16px 20px;
        cursor: pointer;
        border-bottom: 1px solid #F3F4F6;
        position: relative;
    }

    .chat-item:hover {
        background-color: #F9FAFB;
    }

    .chat-item.active {
        background-color: #EFF6FF;
        border-left: 3px solid #2563EB;
    }

    .chat-avatar {
        position: relative;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .chat-avatar img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .status-online {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        background-color: #10B981;
        border: 2px solid #fff;
        border-radius: 50%;
    }

    .chat-info {
        flex: 1;
        min-width: 0;
    }

    .chat-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }

    .chat-name {
        font-size: 15px;
        font-weight: 700;
        color: #1F2937;
    }

    .chat-time {
        font-size: 11px;
        color: #6B7280;
    }

    .chat-preview {
        font-size: 13px;
        color: #6B7280;
        font-style: italic;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .unread-badge {
        position: absolute;
        right: 20px;
        bottom: 16px;
        background-color: #EF4444;
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 12px;
        min-width: 20px;
        text-align: center;
    }

    /* ===== AREA CHAT KANAN (70%) ===== */
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background-color: #F9FAFB;
    }

    .chat-header {
        background-color: #fff;
        padding: 16px 24px;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-avatar {
        position: relative;
    }

    .header-avatar img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
    }

    .header-details h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 2px;
    }

    .status-text {
        font-size: 12px;
        color: #10B981;
        font-weight: 500;
    }

    .btn-options {
        background: none;
        border: none;
        cursor: pointer;
        color: #6B7280;
        font-size: 18px;
        padding: 8px;
        border-radius: 8px;
    }

    .btn-options:hover {
        background-color: #F3F4F6;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .message-wrapper {
        display: flex;
        gap: 8px;
        max-width: 70%;
    }

    .message-wrapper.received {
        align-self: flex-start;
    }

    .message-wrapper.sent {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .message-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .message-content {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .message-wrapper.sent .message-content {
        align-items: flex-end;
    }

    .message-bubble {
        padding: 12px 16px;
        border-radius: 12px;
        word-wrap: break-word;
    }

    .received-bubble {
        background-color: #fff;
        color: #1F2937;
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .sent-bubble {
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
        color: white;
        border-bottom-right-radius: 4px;
    }

    .message-bubble p {
        margin: 0;
        font-size: 14px;
        line-height: 1.5;
    }

    .message-time {
        font-size: 11px;
        color: #6B7280;
        padding: 0 4px;
    }

    .chat-input-container {
        background-color: #fff;
        padding: 16px 24px;
        border-top: 1px solid #E5E7EB;
    }

    .chat-input-form {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .message-input {
        flex: 1;
        border: 1px solid #E5E7EB;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 14px;
        outline: none;
        background-color: #F9FAFB;
    }

    .message-input:focus {
        border-color: #2563EB;
        background-color: #fff;
    }

    .btn-send {
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
        color: white;
        border: none;
        padding: 12px 16px;
        border-radius: 12px;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 48px;
    }

    .btn-send:hover {
        opacity: 0.9;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #9CA3AF;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.3;
    }

    /* Scrollbar */
    .chat-list::-webkit-scrollbar,
    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chat-list::-webkit-scrollbar-track,
    .chat-messages::-webkit-scrollbar-track {
        background: #F3F4F6;
    }

    .chat-list::-webkit-scrollbar-thumb,
    .chat-messages::-webkit-scrollbar-thumb {
        background: #D1D5DB;
        border-radius: 3px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .messages-container {
            flex-direction: column;
        }

        .chat-sidebar {
            width: 100%;
            min-width: auto;
        }

        .chat-main {
            display: none;
        }

        .chat-main.active {
            display: flex;
        }
    }
</style>
@endpush

@section('content')
<div class="messages-container">
    <!-- Sidebar Kiri: List Chat -->
    <div class="chat-sidebar">
        <!-- Header Sidebar -->
        <div class="sidebar-header">
            <h2>Messages</h2>
            <button class="btn-new-chat" id="btnNewChat">
                <i class="fas fa-edit"></i> New Chat
            </button>
        </div>

        <!-- Search Bar -->
        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari konversasi..." class="search-input" id="searchInput">
        </div>

        <!-- List Kontak Chat -->
        <div class="chat-list" id="chatList">
            @forelse($contacts as $contact)
            <div class="chat-item {{ $loop->first ? 'active' : '' }}"
                 data-user-id="{{ $contact->id }}"
                 data-user-name="{{ $contact->full_name ?? $contact->username }}"
                 data-user-avatar="{{ $contact->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($contact->full_name ?? $contact->username) . '&background=2563EB&color=fff' }}">
                <div class="chat-avatar">
                    <img src="{{ $contact->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($contact->full_name ?? $contact->username) . '&background=2563EB&color=fff' }}"
                         alt="{{ $contact->full_name ?? $contact->username }}">
                    @if($contact->is_online ?? false)
                    <span class="status-online"></span>
                    @endif
                </div>
                <div class="chat-info">
                    <div class="chat-header-row">
                        <h4 class="chat-name">{{ $contact->full_name ?? $contact->username }}</h4>
                        <span class="chat-time">
                            @if($contact->last_message)
                                {{ $contact->last_message->created_at->diffForHumans() }}
                            @else
                                Baru
                            @endif
                        </span>
                    </div>
                    <p class="chat-preview">
                        @if($contact->last_message)
                            {{ Str::limit($contact->last_message->message, 40) }}
                        @else
                            Mulai percakapan...
                        @endif
                    </p>
                </div>
                @if($contact->unread_count > 0)
                <span class="unread-badge">{{ $contact->unread_count }}</span>
                @endif
            </div>
            @empty
            <div class="empty-state" style="padding: 40px;">
                <i class="fas fa-inbox"></i>
                <p>Belum ada kontak untuk chat</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Area Chat Kanan -->
    <div class="chat-main" id="chatMain">
        @if($selectedContact)
        <!-- Header Chat -->
        <div class="chat-header">
            <div class="header-user-info">
                <div class="header-avatar">
                    <img src="{{ $selectedContact->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($selectedContact->full_name ?? $selectedContact->username) . '&background=2563EB&color=fff' }}"
                         alt="{{ $selectedContact->full_name ?? $selectedContact->username }}"
                         id="headerAvatar">
                    @if($selectedContact->is_online ?? false)
                    <span class="status-online"></span>
                    @endif
                </div>
                <div class="header-details">
                    <h3 id="headerName">{{ $selectedContact->full_name ?? $selectedContact->username }}</h3>
                    <span class="status-text" id="headerStatus">
                        {{ ($selectedContact->is_online ?? false) ? 'Online' : 'Offline' }}
                    </span>
                </div>
            </div>
            <button class="btn-options">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>

        <!-- Area Pesan -->
        <div class="chat-messages" id="chatMessages">
            @foreach($messages as $message)
            <div class="message-wrapper {{ $message->sender_id == Auth::id() ? 'sent' : 'received' }}">
                <img src="{{ $message->sender->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($message->sender->full_name ?? $message->sender->username) . '&background=2563EB&color=fff' }}"
                     alt="{{ $message->sender->full_name ?? $message->sender->username }}"
                     class="message-avatar">
                <div class="message-content">
                    <div class="message-bubble {{ $message->sender_id == Auth::id() ? 'sent-bubble' : 'received-bubble' }}">
                        <p>{{ $message->message }}</p>
                    </div>
                    <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Form Input Pesan -->
        <div class="chat-input-container">
            <form class="chat-input-form" id="messageForm">
                @csrf
                <input type="hidden" id="receiverId" value="{{ $selectedContact->id }}">
                <input type="text" class="message-input" id="messageInput" placeholder="Ketik pesan..." required>
                <button type="submit" class="btn-send">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="fas fa-comments"></i>
            <p>Pilih kontak untuk mulai chat</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentReceiverId = {{ $selectedContact->id ?? 'null' }};

    // Handle click pada chat item
    document.querySelectorAll('.chat-item').forEach(item => {
        item.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            const userAvatar = this.dataset.userAvatar;

            // Set active
            document.querySelectorAll('.chat-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            // Remove unread badge
            const badge = this.querySelector('.unread-badge');
            if (badge) badge.remove();

            // Load chat
            loadChat(userId, userName, userAvatar);
        });
    });

    // Load chat dengan user tertentu
    function loadChat(userId, userName, userAvatar) {
        currentReceiverId = userId;

        fetch(`/teamlead/messages/chat/${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update header
                    document.getElementById('headerName').textContent = data.contact.name;
                    document.getElementById('headerAvatar').src = data.contact.avatar;
                    document.getElementById('headerStatus').textContent = data.contact.is_online ? 'Online' : 'Offline';
                    document.getElementById('receiverId').value = userId;

                    // Render messages
                    renderMessages(data.messages);

                    // Show chat main on mobile
                    document.getElementById('chatMain').classList.add('active');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Render messages
    function renderMessages(messages) {
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.innerHTML = '';

        messages.forEach(message => {
            const messageWrapper = document.createElement('div');
            messageWrapper.className = `message-wrapper ${message.is_mine ? 'sent' : 'received'}`;

            messageWrapper.innerHTML = `
                <img src="${message.sender_avatar}" alt="${message.sender_name}" class="message-avatar">
                <div class="message-content">
                    <div class="message-bubble ${message.is_mine ? 'sent-bubble' : 'received-bubble'}">
                        <p>${escapeHtml(message.message)}</p>
                    </div>
                    <span class="message-time">${message.created_at}</span>
                </div>
            `;

            chatMessages.appendChild(messageWrapper);
        });

        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Handle send message
    document.getElementById('messageForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value.trim();

        if (!message || !currentReceiverId) return;

        const formData = new FormData();
        formData.append('receiver_id', currentReceiverId);
        formData.append('message', message);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('/teamlead/messages/send', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add message to chat
                const chatMessages = document.getElementById('chatMessages');
                const messageWrapper = document.createElement('div');
                messageWrapper.className = 'message-wrapper sent';

                messageWrapper.innerHTML = `
                    <img src="${data.message.sender_avatar}" alt="Me" class="message-avatar">
                    <div class="message-content">
                        <div class="message-bubble sent-bubble">
                            <p>${escapeHtml(data.message.message)}</p>
                        </div>
                        <span class="message-time">${data.message.created_at}</span>
                    </div>
                `;

                chatMessages.appendChild(messageWrapper);
                chatMessages.scrollTop = chatMessages.scrollHeight;

                // Clear input
                messageInput.value = '';
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('.chat-item').forEach(item => {
            const name = item.dataset.userName.toLowerCase();
            item.style.display = name.includes(searchTerm) ? 'flex' : 'none';
        });
    });

    // Escape HTML untuk prevent XSS
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    // Auto scroll to bottom on load
    const chatMessages = document.getElementById('chatMessages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Refresh unread count setiap 30 detik
    setInterval(function() {
        fetch('/teamlead/messages/unread-count')
            .then(response => response.json())
            .then(data => {
                // Update badge di sidebar jika ada
                console.log('Unread messages:', data.unread_count);
            });
    }, 30000);
});
</script>
@endpush
@endsection
