<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gg logout</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Use Inter font family -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-900 flex items-center justify-center p-4">

    <!-- Logout Card Container -->
    <div class="w-full max-w-sm p-8 bg-white rounded-xl shadow-2xl border-t-4 border-blue-500 transition-all duration-300">
        <div class="text-center">
            
            <!-- Icon (Blue Accent) -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto text-blue-600 mb-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
            </svg>

            <!-- Title (Black Text) -->
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Secure Sign Out</h1>
            
            <!-- Message (Gray Text) -->
            <p class="text-gray-600 mb-6">
                ログアウトしますか。確認してください。
            </p>

            <!-- Logout Button (Blue) -->
            <button
                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg transition duration-200 ease-in-out transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-blue-500/50"
            >
                <a href="logout-output.php">ログアウト確認</a>
            </button>
            
            <!-- Secondary Action (Link style) -->
            <a href="index.php" class="block mt-4 text-sm font-medium text-gray-500 hover:text-blue-600 transition duration-200">
                ホームに戻る
            </a>
        </div>
    </div>

</body>
</html>
