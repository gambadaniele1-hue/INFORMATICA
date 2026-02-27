<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database API Explorer</title>
    <link href="/css/output.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800 font-sans flex h-screen overflow-hidden selection:bg-blue-200">
    
    <aside class="w-80 bg-slate-900 text-slate-300 flex flex-col h-full shadow-2xl z-20">
        <div class="p-6 bg-slate-950 border-b border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded bg-blue-500 flex items-center justify-center text-white font-bold">API</div>
                <div>
                    <h1 class="text-lg font-bold text-white tracking-wide">Data Explorer</h1>
                    <p class="text-xs text-slate-500 uppercase tracking-widest mt-0.5">Pannello di controllo</p>
                </div>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1 custom-scrollbar">
            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 mt-2">Le tue Query</p>
            
            <?php foreach ($queryInfo as $qId => $info): ?>
                <?php $isActive = ($id == $qId); ?>
                <a href="/<?= $qId ?>" 
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 
                          <?= $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                    
                    <span class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold 
                                 <?= $isActive ? 'bg-white text-blue-600' : 'bg-slate-700 text-slate-400 group-hover:bg-slate-600 group-hover:text-white' ?>">
                        <?= $qId ?>
                    </span>
                    
                    <span class="truncate"><?= htmlspecialchars(preg_replace('/^\d+\.\s*/', '', $info['title'])) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full overflow-hidden bg-gray-50/50">
        
        <header class="bg-white border-b border-gray-200 px-8 py-6 shadow-sm z-10">
            <div class="flex justify-between items-end">
                <div>
                    <span class="text-sm font-bold text-blue-600 tracking-wider uppercase mb-1 block">Query Selezionata</span>
                    <h2 class="text-2xl font-extrabold text-slate-800"><?= htmlspecialchars($queryInfo[$id]['title']) ?></h2>
                </div>
            </div>
            
            <?php if (!empty($queryInfo[$id]['params'])): ?>
            <div class="mt-6 pt-6 border-t border-gray-100">
                <form method="GET" action="/<?= $id ?>" class="flex flex-wrap gap-4 items-end">
                    <?php foreach ($queryInfo[$id]['params'] as $param): ?>
                        <div class="flex flex-col w-48">
                            <label class="text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wider"><?= htmlspecialchars($param) ?></label>
                            <input type="text" name="<?= htmlspecialchars($param) ?>" 
                                   value="<?= htmlspecialchars($queryParams[$param] ?? '') ?>" 
                                   placeholder="Es. rosso"
                                   class="border border-slate-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all bg-slate-50 focus:bg-white shadow-inner">
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-md font-semibold text-sm transition-colors shadow-md">
                        Applica Filtri
                    </button>
                    <?php if(!empty($queryParams) && count($queryParams) > (isset($queryParams['page']) ? 1 : 0)): ?>
                        <a href="/<?= $id ?>" class="text-sm text-slate-500 hover:text-slate-800 font-medium px-2 underline decoration-slate-300 underline-offset-4">Resetta</a>
                    <?php endif; ?>
                </form>
            </div>
            <?php endif; ?>
        </header>

        <div class="flex-1 overflow-auto p-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full">
                
                <?php if (empty($dati)): ?>
                    <div class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700">Nessun risultato trovato</h3>
                        <p class="text-slate-500 text-sm mt-1 max-w-sm">La query non ha prodotto risultati con i filtri attuali. Prova a modificare i parametri di ricerca o naviga verso un'altra pagina.</p>
                    </div>
                <?php else: ?>
                    <div class="flex-1 overflow-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-extrabold sticky top-0 shadow-sm z-10 tracking-wider">
                                <tr>
                                    <?php foreach (array_keys($dati[0]) as $header): ?>
                                        <th class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($header) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100">
                                <?php foreach ($dati as $index => $row): ?>
                                    <tr class="hover:bg-blue-50/50 transition-colors <?= $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/30' ?>">
                                        <?php foreach ($row as $cell): ?>
                                            <td class="px-6 py-4 text-slate-700 whitespace-nowrap"><?= htmlspecialchars((string)$cell) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <?php if (!empty($meta)): ?>
                <?php 
                    $currentPage = (int)($meta['page'] ?? 1);
                    $limit = (int)($meta['limit'] ?? 10);
                    $numResults = is_array($dati) ? count($dati) : 0;
                    
                    // Logica Next/Prev corretta
                    $hasPrev = $currentPage > 1;
                    $hasNext = ($numResults === $limit && $numResults > 0); 

                    $prevParams = $queryParams; $prevParams['page'] = $currentPage - 1;
                    $prevUrl = '/' . $id . '?' . http_build_query($prevParams);

                    $nextParams = $queryParams; $nextParams['page'] = $currentPage + 1;
                    $nextUrl = '/' . $id . '?' . http_build_query($nextParams);
                ?>
                <div class="bg-white border-t border-gray-200 p-4 px-6 flex items-center justify-between">
                    <p class="text-sm text-slate-500">
                        Mostrando <span class="font-medium text-slate-900"><?= $numResults ?></span> risultati (Limite: <?= $limit ?>)
                    </p>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <?php if ($hasPrev): ?>
                            <a href="<?= htmlspecialchars($prevUrl) ?>" class="relative inline-flex items-center rounded-l-md px-3 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Precedente</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>
                            </a>
                        <?php else: ?>
                            <button disabled class="relative inline-flex items-center rounded-l-md px-3 py-2 text-gray-300 ring-1 ring-inset ring-gray-200 bg-gray-50 cursor-not-allowed">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>
                            </button>
                        <?php endif; ?>
                        
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">
                            Pagina <?= $currentPage ?>
                        </span>

                        <?php if ($hasNext): ?>
                            <a href="<?= htmlspecialchars($nextUrl) ?>" class="relative inline-flex items-center rounded-r-md px-3 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Successiva</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                            </a>
                        <?php else: ?>
                            <button disabled class="relative inline-flex items-center rounded-r-md px-3 py-2 text-gray-300 ring-1 ring-inset ring-gray-200 bg-gray-50 cursor-not-allowed">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                            </button>
                        <?php endif; ?>
                    </nav>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <style>
        /* Una piccola aggiunta CSS per rendere bella la barra di scorrimento laterale */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #334155; border-radius: 10px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background-color: #475569; }
    </style>
</body>
</html>