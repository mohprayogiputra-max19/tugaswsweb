<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <form action="/login" method="POST" class="w-full max-w-sm p-8 bg-white rounded-xl shadow-lg">
        @csrf
        <h2 class="mb-6 text-2xl font-bold text-center text-gray-800">Login Pengguna</h2>
        
        <div class="mb-4">
            <input type="email" name="email" placeholder="Email" required 
                class="w-full px-4 py-2 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition duration-200">
        </div>
        
        <div class="mb-6">
            <input type="password" name="password" placeholder="Password" required 
                class="w-full px-4 py-2 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition duration-200">
        </div>
        
        <button type="submit" 
                class="w-full px-4 py-2 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition duration-200">
            Masuk
        </button>
    </form>
</div>