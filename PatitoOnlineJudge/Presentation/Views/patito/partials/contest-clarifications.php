<?php
// Questions and answers at the bottom of the contest page (as in Codeforces), with the form to ask.
$problemLabel = fn($num) => $num === null ? "General" : chr(65 + intval($num));
$questions = array_values(array_filter($clarifications, fn($item) => $item["user_id"] !== null));
$canAsk = strtotime($contestDetail["end_time"]) >= time();
?>
<div id="aclaraciones" class="rounded-lg border bg-card text-card-foreground shadow-sm w-full mt-4">
    <div class="flex flex-wrap items-center justify-between gap-2 p-6 pb-2">
        <h3 class="text-2xl font-semibold leading-none tracking-tight">Aclaraciones</h3>
        <?php if (!$canAsk) { ?>
            <span class="text-gray-500 text-sm">El concurso terminó; ya no se reciben preguntas.</span>
        <?php } elseif (empty($user_id)) { ?>
            <span class="text-gray-500 text-sm"><a href="login.php" class="text-blue-500 hover:underline">Inicia sesión</a> para hacer una pregunta.</span>
        <?php } ?>
    </div>

    <?php if ($canAsk && !empty($user_id)) { ?>
        <details class="px-6 pb-2" <?php echo !empty($clarificationError) ? "open" : "" ?>>
            <summary class="inline-block cursor-pointer bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Hacer una pregunta</summary>
            <?php if (!empty($clarificationError)) { ?>
                <p class="result-red mt-3"><?php echo htmlspecialchars($clarificationError) ?></p>
            <?php } ?>
            <form method="post" class="mt-3 flex flex-col gap-2 max-w-2xl">
                <input type="hidden" name="clarification" value="1">
                <input type="hidden" name="token" value="<?php echo $clarificationToken ?>">
                <label for="clarification-problem" class="font-semibold">Problema</label>
                <select id="clarification-problem" name="problem_num" class="border rounded p-2 w-48">
                    <option value="">General</option>
                    <?php foreach ($clarificationNums as $num) { ?>
                        <option value="<?php echo $num ?>">Problema <?php echo $problemLabel($num) ?></option>
                    <?php } ?>
                </select>
                <label for="clarification-question" class="font-semibold">Pregunta</label>
                <textarea id="clarification-question" name="question" rows="4" maxlength="2000" required class="border rounded p-2" placeholder="Escribe tu pregunta sobre el enunciado"></textarea>
                <div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Enviar</button>
                </div>
            </form>
        </details>
    <?php } ?>

    <div class="p-1">
        <div class="relative w-full overflow-auto">
            <table class="border-b w-full">
                <thead class="bg-gray-900 text-white">
                    <tr class="shadow-lg">
                        <th class="p-1 font-bold text-lg border-r">Hora</th>
                        <th class="p-1 font-bold text-lg border-r">Problema</th>
                        <th class="p-1 font-bold text-lg border-r">Pregunta</th>
                        <th class="p-1 font-bold text-lg">Respuesta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($questions)) { ?>
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500">Todavía no hay preguntas respondidas.</td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($questions as $key => $item) {
                        $isMine = !empty($user_id) && $item["user_id"] === $user_id;
                    ?>
                        <tr class="border-b <?php echo $key % 2 == 0 ? "evenrow" : "oddrow" ?>">
                            <td class="p-2 text-center align-top whitespace-nowrap"><?php echo $item["created_at"] ?></td>
                            <td class="p-2 text-center align-top"><?php echo $problemLabel($item["problem_num"]) ?></td>
                            <td class="p-2 align-top whitespace-pre-wrap"><?php echo ($isMine ? "<b>Tu pregunta:</b> " : "") . htmlspecialchars($item["question"]) ?></td>
                            <td class="p-2 align-top <?php echo $item["answer"] === null ? "result-blue" : "result-green oj-rich-content" ?>"><?php
                                // Answers are HTML written by the judges in the admin editor; questions stay plain text.
                                echo $item["answer"] === null ? "Sin respuesta todavía." : $item["answer"];
                            ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
