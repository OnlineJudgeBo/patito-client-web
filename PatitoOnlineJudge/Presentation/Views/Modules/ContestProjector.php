<?php
// Full screen ranking for the projector. Reloads itself every 5 s and animates the changes.
$isObi = $obi == 1;
$feed = [];
foreach ($contestRank as $row) {
    foreach ($problems as $j => $_) {
        if (isset($row->p_ac_sec[$j]) && (!$isObi || ($row->pass_rate[$j] ?? 0) >= 100)) {
            $feed[] = ['nick' => $row->nick ?: $row->user_id, 'problem' => $PID2[$j], 'sec' => $row->p_ac_sec[$j]];
        }
    }
}
usort($feed, fn($a, $b) => $b['sec'] <=> $a['sec']);
$feed = array_slice($feed, 0, 12);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>Ranking en vivo - <?php echo htmlspecialchars(strip_tags($contest["title"])) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes oj-flash {
            0%, 60% { background-color: #bef264; color: #1a2e05; transform: scale(1.15); }
        }
        .oj-flash { animation: oj-flash 4s ease-out; }
    </style>
</head>

<body class="flex h-screen flex-col bg-slate-950 text-slate-100">
    <header class="flex items-center justify-between gap-4 border-b border-slate-800 px-6 py-3">
        <div class="flex min-w-0 items-center gap-4">
            <a href="./contestrank.php?cid=<?php echo (int)$cid ?>" class="text-slate-400 hover:text-white" aria-label="Volver al ranking">&larr;</a>
            <h1 class="truncate text-2xl font-bold lg:text-3xl"><?php echo htmlspecialchars(strip_tags($contest["title"])) ?></h1>
        </div>
        <button type="button" onclick="document.documentElement.requestFullscreen?.()" class="text-slate-400 hover:text-white">Pantalla completa</button>
    </header>

    <div class="flex min-h-0 flex-1">
        <div id="board" class="min-w-0 flex-1 overflow-y-auto">
            <table class="w-full text-base">
                <thead class="sticky top-0 z-10 bg-slate-900 text-xs uppercase tracking-widest text-slate-400">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-2 py-3 text-left">Participante</th>
                        <th class="px-2 py-3 text-right"><?php echo $isObi ? "Puntos" : "Resueltos" ?></th>
                        <?php foreach ($problems as $j => $_) { ?>
                            <th class="px-1 py-3 text-center font-mono"><?php echo $PID2[$j] ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody id="rows">
                    <?php foreach ($contestRank as $index => $row) { ?>
                        <tr class="border-b border-slate-800" data-user="<?php echo htmlspecialchars($row->user_id) ?>">
                            <td class="px-4 py-2 font-mono font-bold"><?php echo $index + 1 ?></td>
                            <td class="max-w-[28ch] truncate px-2 py-2 font-semibold"><?php echo htmlspecialchars($row->nick ?: $row->user_id) ?></td>
                            <td class="px-2 py-2 text-right font-mono font-bold text-emerald-400"><?php echo $isObi ? (float)$row->points : $row->solved ?></td>
                            <?php foreach ($problems as $j => $_) {
                                $wa = $row->p_wa_num[$j] ?? 0;
                                $solved = isset($row->p_ac_sec[$j]) && (!$isObi || ($row->pass_rate[$j] ?? 0) >= 100);
                                $partial = $isObi && !$solved && ($row->pass_rate[$j] ?? 0) > 0;
                                $first = $solved && ($first_blood[$j] ?? null) == $row->user_id;
                            ?>
                                <td class="px-1 py-1 text-center">
                                    <?php if ($solved) { ?>
                                        <span data-ac="<?php echo $j ?>" class="inline-block min-w-[3.5rem] rounded-md px-1.5 py-1 font-mono text-sm font-bold <?php echo $first ? "bg-amber-400 text-amber-950" : "bg-emerald-600 text-white" ?>">
                                            <?php echo ($first ? "&#9733; " : "") . intdiv($row->p_ac_sec[$j], 60) . ($wa > 0 ? "<small class='opacity-75'>/" . ($wa + 1) . "</small>" : "") ?>
                                        </span>
                                    <?php } elseif ($partial) { ?>
                                        <span class="inline-block min-w-[3.5rem] rounded-md bg-emerald-900 px-1.5 py-1 font-mono text-sm font-bold text-emerald-200"><?php echo (float)$row->pass_rate[$j] ?></span>
                                    <?php } elseif ($wa > 0) { ?>
                                        <span class="inline-block min-w-[3.5rem] rounded-md bg-rose-900/70 px-1.5 py-1 font-mono text-sm text-rose-200">-<?php echo $wa ?></span>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <aside class="hidden w-80 shrink-0 overflow-hidden border-l border-slate-800 p-4 lg:block">
            <h2 class="mb-3 text-xs font-semibold uppercase tracking-widest text-slate-400">Últimos aceptados</h2>
            <ul id="feed" class="space-y-2">
                <?php foreach ($feed as $item) { ?>
                    <li class="rounded-md border border-slate-800 bg-slate-900 px-3 py-2 text-sm" data-key="<?php echo htmlspecialchars($item['nick'] . '|' . $item['problem']) ?>">
                        &#127880; <strong><?php echo htmlspecialchars($item['nick']) ?></strong> resolvió
                        <strong class="font-mono"><?php echo $item['problem'] ?></strong>
                        <span class="text-slate-400">(min <?php echo intdiv($item['sec'], 60) ?>)</span>
                    </li>
                <?php } ?>
            </ul>
        </aside>
    </div>

    <script>
        (function () {
            const board = document.getElementById('board');
            const acKeys = (root) => new Set([...root.querySelectorAll('[data-ac]')].map((el) => el.closest('tr').dataset.user + '|' + el.dataset.ac));

            // Swap the rows and slide each one from where it was (FLIP).
            async function refresh() {
                const html = await fetch(location.href, { cache: 'no-store' }).then((r) => r.ok ? r.text() : Promise.reject());
                const next = new DOMParser().parseFromString(html, 'text/html');
                const rows = document.getElementById('rows');
                const before = new Map([...rows.children].map((tr) => [tr.dataset.user, tr.getBoundingClientRect().top]));
                const oldAc = acKeys(rows);
                const oldFeed = new Set([...document.querySelectorAll('#feed li')].map((li) => li.dataset.key));

                rows.innerHTML = next.getElementById('rows').innerHTML;
                document.getElementById('feed').innerHTML = next.getElementById('feed').innerHTML;

                for (const tr of rows.children) {
                    const top = before.get(tr.dataset.user);
                    const dy = top === undefined ? 0 : top - tr.getBoundingClientRect().top;
                    if (dy) tr.animate([{ transform: `translateY(${dy}px)` }, { transform: 'none' }], { duration: 800, easing: 'ease-in-out' });
                }
                rows.querySelectorAll('[data-ac]').forEach((el) => {
                    if (!oldAc.has(el.closest('tr').dataset.user + '|' + el.dataset.ac)) el.classList.add('oj-flash');
                });
                document.querySelectorAll('#feed li').forEach((li) => {
                    if (!oldFeed.has(li.dataset.key)) li.animate([{ opacity: 0, transform: 'translateX(40px)' }, { opacity: 1, transform: 'none' }], { duration: 600 });
                });
            }

            setInterval(() => refresh().catch(() => undefined), 5000);

            // Slow auto-scroll down, pause, jump back to the top.
            let pauseUntil = Date.now() + 4000;
            setInterval(() => {
                if (Date.now() < pauseUntil || board.scrollHeight <= board.clientHeight) return;
                const atBottom = board.scrollTop + board.clientHeight >= board.scrollHeight - 1;
                if (atBottom) {
                    board.scrollTop = 0;
                    pauseUntil = Date.now() + 4000;
                } else {
                    board.scrollTop += 1;
                    if (board.scrollTop + board.clientHeight >= board.scrollHeight - 1) pauseUntil = Date.now() + 4000;
                }
            }, 40);
        })();
    </script>
</body>

</html>
