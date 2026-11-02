<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Internal Kasir</title>
    <!-- WAJIB: Memanggil Tailwind CSS dan JavaScript (Laravel Echo) bawaan Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 h-screen flex items-center justify-center p-4 antialiased">

    <!-- Container Utama -->
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl flex flex-col h-[85vh] overflow-hidden">
        
        <!-- Header Aplikasi -->
        <div class="bg-blue-600 text-white px-6 py-4 shadow-md z-10 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-lg tracking-wide">Komunikasi Antar Kasir</h3>
                <div class="flex items-center mt-1">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse mr-2"></span>
                    <p class="text-xs text-blue-100">Server Reverb Terhubung</p>
                </div>
            </div>
            <!-- Ikon opsional -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
            </svg>
        </div>

        <!-- Area Chat Box -->
        <div id="chat-box" class="flex-1 p-6 overflow-y-auto bg-slate-50 flex flex-col space-y-4">
            <!-- Menampilkan pesan default sebagai petunjuk -->
            <div class="flex justify-center mb-4">
                <span class="bg-slate-200 text-slate-500 text-xs px-3 py-1 rounded-full">
                    Koneksi internal minimarket dimulai
                </span>
            </div>
        </div>

        <!-- Area Input -->
        <div class="bg-white border-t p-4 flex items-center gap-3">
            <input type="text" id="message-input" placeholder="Ketik pesan ke kasir lain..." 
                class="flex-1 bg-slate-100 border-transparent rounded-full px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all shadow-sm"
                onkeypress="handleKeyPress(event)">
            <button onclick="sendMessage()" 
                class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-3 px-6 shadow-md transition-transform active:scale-95 font-semibold">
                Kirim
            </button>
        </div>
    </div>

    <script>
        // Memastikan elemen DOM dan script app.js termuat sepenuhnya sebelum memanggil Echo
        document.addEventListener('DOMContentLoaded', function () {
            
            // Mendengarkan event dari Reverb
            window.Echo.channel('chat')
                .listen('MessageSent', (e) => {
                    const chatBox = document.getElementById('chat-box');
                    
                    // Template pesan dari orang lain (tampil di sisi kiri)
                    const msgHtml = `
                        <div class="flex flex-col items-start w-full">
                            <span class="text-xs text-slate-500 mb-1 ml-2 font-medium">${e.username}</span>
                            <div class="bg-white border border-slate-200 text-slate-700 rounded-2xl rounded-tl-sm px-4 py-2.5 shadow-sm max-w-[85%] leading-relaxed">
                                ${e.message}
                            </div>
                        </div>
                    `;
                    
                    chatBox.innerHTML += msgHtml;
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        });

        // Menangani tombol Enter
        function handleKeyPress(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        }

        // Fungsi mengirim pesan
        function sendMessage() {
            const input = document.getElementById('message-input');
            const message = input.value;
            const chatBox = document.getElementById('chat-box');

            if(message.trim() === '') return;

            // Template pesan diri sendiri (langsung tampil di sisi kanan)
            const myMsgHtml = `
                <div class="flex flex-col items-end w-full mt-2">
                    <span class="text-xs text-slate-500 mb-1 mr-2 font-medium">Saya</span>
                    <div class="bg-blue-600 text-white rounded-2xl rounded-tr-sm px-4 py-2.5 shadow-md max-w-[85%] leading-relaxed">
                        ${message}
                    </div>
                </div>
            `;
            chatBox.innerHTML += myMsgHtml;
            chatBox.scrollTop = chatBox.scrollHeight;
            
            input.value = ''; // Kosongkan input setelah dikirim

            fetch('/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            }).catch(error => console.error('Error mengirim pesan:', error));
        }
    </script>
</body>
</html>