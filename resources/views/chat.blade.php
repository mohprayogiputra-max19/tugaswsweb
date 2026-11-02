<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Internal Kasir</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 h-screen flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl flex flex-col h-[85vh] overflow-hidden">
        <div class="bg-blue-600 text-white px-6 py-4 shadow-md z-10 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-lg tracking-wide">Komunikasi Antar Kasir</h3>
                <div class="flex items-center mt-1">
                    <span id="connection-indicator" class="w-2 h-2 rounded-full bg-slate-300 mr-2"></span>
                    <p id="chat-status" class="text-xs text-blue-100" role="status">Menghubungkan ke server chat...</p>
                </div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
            </svg>
        </div>

        <div id="chat-box" aria-live="polite" class="flex-1 p-6 overflow-y-auto bg-slate-50 flex flex-col space-y-4">
            <div id="chat-empty-state" class="flex justify-center mb-4">
                <span class="bg-slate-200 text-slate-500 text-xs px-3 py-1 rounded-full">
                    Koneksi internal minimarket dimulai
                </span>
            </div>
        </div>

        <form id="message-form" class="bg-white border-t p-4">
            <div class="flex items-center gap-3">
                <input type="text" id="message-input" placeholder="Ketik pesan ke kasir lain..."
                    class="flex-1 bg-slate-100 border-transparent rounded-full px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all shadow-sm"
                    maxlength="255" autocomplete="off">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-3 px-6 shadow-md transition-transform active:scale-95 font-semibold">
                    Kirim
                </button>
            </div>
            <p id="send-status" class="mt-2 px-2 text-xs text-slate-500" role="status" aria-live="polite"></p>
        </form>
    </div>

    <script>
        const chatBox = document.getElementById('chat-box');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const chatStatus = document.getElementById('chat-status');
        const sendStatus = document.getElementById('send-status');
        const connectionIndicator = document.getElementById('connection-indicator');
        const emptyState = document.getElementById('chat-empty-state');
        const currentUsername = @js(auth()->user()->name);

        function setConnectionStatus(connected, message) {
            chatStatus.textContent = message;
            connectionIndicator.classList.toggle('bg-green-400', connected);
            connectionIndicator.classList.toggle('animate-pulse', connected);
            connectionIndicator.classList.toggle('bg-red-400', !connected);
        }

        function appendMessage(username, message) {
            emptyState?.remove();

            const wrapper = document.createElement('div');
            const isOwnMessage = username === currentUsername;
            wrapper.className = `flex flex-col w-full ${isOwnMessage ? 'items-end' : 'items-start'}`;

            const sender = document.createElement('span');
            sender.className = `text-xs text-slate-500 mb-1 ${isOwnMessage ? 'mr-2' : 'ml-2'} font-medium`;
            sender.textContent = username;

            const body = document.createElement('div');
            body.className = isOwnMessage
                ? 'bg-blue-600 text-white rounded-2xl rounded-tr-sm px-4 py-2.5 shadow-sm max-w-[85%] leading-relaxed'
                : 'bg-white border border-slate-200 text-slate-700 rounded-2xl rounded-tl-sm px-4 py-2.5 shadow-sm max-w-[85%] leading-relaxed';
            body.textContent = message;

            wrapper.append(sender, body);
            chatBox.append(wrapper);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (!window.Echo) {
                console.error('Laravel Echo belum terinisialisasi. Periksa konfigurasi VITE_REVERB_* dan build Vite.');
                setConnectionStatus(false, 'Konfigurasi server chat belum tersedia');
                return;
            }

            const connectionStatus = window.Echo.connector.connectionStatus();
            const connected = connectionStatus === 'connected';

            setConnectionStatus(
                connected,
                connected ? 'Terhubung ke server chat' : 'Menghubungkan ke server chat...',
            );

            window.Echo.connector.onConnectionChange((status) => {
                const isConnected = status === 'connected';
                const message = isConnected
                    ? 'Terhubung ke server chat'
                    : status === 'failed'
                        ? 'Server chat tidak dapat dijangkau'
                        : 'Menghubungkan ke server chat...';

                setConnectionStatus(isConnected, message);
            });

            window.Echo.private('chat')
                .listen('MessageSent', (event) => appendMessage(event.username, event.message))
                .error((error) => {
                    console.error('Gagal bergabung ke ruang chat:', error);
                    setConnectionStatus(false, 'Akses ruang chat ditolak; periksa login dan server.');
                });
        });

        messageForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const message = messageInput.value.trim();

            if (!message) return;

            const submitButton = messageForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            sendStatus.textContent = 'Mengirim pesan...';

            try {
                const response = await fetch('{{ route('chat.send') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message })
                });

                if (!response.ok) {
                    const result = await response.json().catch(() => ({}));
                    throw new Error(result.message || `Pesan gagal dikirim (HTTP ${response.status}). Periksa login dan server.`);
                }

                messageInput.value = '';
                sendStatus.textContent = 'Pesan terkirim ke server. Pastikan kasir tujuan juga terhubung.';
            } catch (error) {
                console.error('Gagal mengirim pesan:', error);
                sendStatus.textContent = error.message || 'Pesan gagal dikirim. Periksa koneksi server.';
            } finally {
                submitButton.disabled = false;
                messageInput.focus();
            }
        });
    </script>
</body>
</html>
