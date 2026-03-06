<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login - API Explorer</title>
    <link href="/css/output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script> </head>
<body class="bg-slate-50 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border border-slate-200">
        <h2 class="text-2xl font-bold text-center text-slate-800 mb-6">Accesso Admin</h2>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-md mb-4 text-sm font-medium border border-red-200">
                Credenziali non valide. Riprova.
            </div>
        <?php endif; ?>

        <form action="/login" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Username</label>
                <input type="text" name="username" required class="w-full border border-slate-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full border border-slate-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition-colors">
                Accedi
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="/" class="text-sm text-slate-500 hover:text-blue-600 font-medium">← Torna alle Query pubbliche</a>
        </div>
    </div>

</body>
</html>