<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="/css/output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans h-screen flex flex-col">

    <header class="bg-slate-900 text-white px-6 py-4 flex justify-between items-center shadow-md shrink-0">
        <div class="font-bold text-xl flex items-center gap-2">
            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">ADMIN</span> 
            Pannello di Controllo
        </div>
        <div class="flex gap-4 items-center">
            <a href="/" class="text-slate-300 hover:text-white text-sm font-medium">Torna alle Query</a>
            <a href="/logout" class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded text-sm font-bold transition-colors">Logout</a>
        </div>
    </header>

    <main class="flex-1 overflow-auto p-8">
        <div class="max-w-7xl mx-auto space-y-8">
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-4">
                    <h2 class="text-xl font-extrabold text-slate-800">🏢 Fornitori</h2>
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-bold shadow-sm">+ Nuovo Fornitore</button>
                </div>
                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 sticky top-0 shadow-sm">
                            <tr>
                                <th class="p-3 text-sm font-bold text-slate-600 uppercase tracking-wider">ID</th>
                                <th class="p-3 text-sm font-bold text-slate-600 uppercase tracking-wider">Nome</th>
                                <th class="p-3 text-sm font-bold text-slate-600 uppercase tracking-wider">Indirizzo</th>
                                <th class="p-3 text-sm font-bold text-slate-600 uppercase tracking-wider text-right">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach($fornitori as $f): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-3 font-mono text-sm text-slate-500"><?= htmlspecialchars($f['fid']) ?></td>
                                <td class="p-3 font-medium text-slate-900"><?= htmlspecialchars($f['fnome']) ?></td>
                                <td class="p-3 text-slate-500"><?= htmlspecialchars($f['indirizzo']) ?></td>
                                <td class="p-3 text-right space-x-3">
                                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Modifica</button>
                                    <button class="text-red-600 hover:text-red-800 text-sm font-medium">Elimina</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-4">
                    <h2 class="text-xl font-extrabold text-slate-800">⚙️ Pezzi</h2>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-bold shadow-sm">+ Nuovo Pezzo</button>
                </div>
                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 sticky top-0 shadow-sm">
                            <tr>
                                <th class="p-3 text-sm font-bold text-slate-600 uppercase tracking-wider">ID</th>
                                <th class="p-3 text-sm font-bold text-slate-600 uppercase tracking-wider">Nome Pezzo</th>
                                <th class="p-3 text-sm font-bold text-slate-600 uppercase tracking-wider">Colore</th>
                                <th class="p-3 text-sm font-bold text-slate-600 uppercase tracking-wider text-right">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach($pezzi as $p): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-3 font-mono text-sm text-slate-500"><?= htmlspecialchars($p['pid']) ?></td>
                                <td class="p-3 font-medium text-slate-900"><?= htmlspecialchars($p['pnome']) ?></td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded text-xs font-semibold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                        <?= htmlspecialchars($p['colore']) ?>
                                    </span>
                                </td>
                                <td class="p-3 text-right space-x-3">
                                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Modifica</button>
                                    <button class="text-red-600 hover:text-red-800 text-sm font-medium">Elimina</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-4">
                    <h2 class="text-xl font-extrabold text-slate-800">💰 Catalogo (Associazioni & Prezzi)</h2>
                    <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-bold shadow-sm">+ Inserisci in Catalogo</button>
                </div>
                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 sticky top-0 shadow-sm">
                            <tr>
                                <th class="p-3 text-sm font-bold text-slate-600 uppercase tracking-wider">ID Fornitore</th>
                                <th class="p-3 text-sm font-bold text-slate-600 uppercase tracking-wider">ID Pezzo</th>
                                <th class="p-3 text-sm font-bold text-slate-600 uppercase tracking-wider">Costo (€)</th>
                                <th class="p-3 text-sm font-bold text-slate-600 uppercase tracking-wider text-right">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach($catalogo as $c): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-3 font-mono text-sm font-bold text-indigo-600"><?= htmlspecialchars($c['fid']) ?></td>
                                <td class="p-3 font-mono text-sm font-bold text-teal-600"><?= htmlspecialchars($c['pid']) ?></td>
                                <td class="p-3 font-medium text-slate-900">€ <?= number_format((float)$c['costo'], 2, ',', '.') ?></td>
                                <td class="p-3 text-right space-x-3">
                                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Modifica Costo</button>
                                    <button class="text-red-600 hover:text-red-800 text-sm font-medium">Rimuovi</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</body>
</html>