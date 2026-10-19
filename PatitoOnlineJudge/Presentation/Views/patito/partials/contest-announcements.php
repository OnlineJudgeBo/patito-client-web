<?php
// Judge announcements, after the problems and before the questions.
$announcements = array_filter($clarifications, fn($item) => $item["user_id"] === null);
if (!empty($announcements)) { ?>
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm w-full mt-4 p-6">
        <h3 class="text-2xl font-semibold leading-none tracking-tight mb-4">Anuncios</h3>
        <?php foreach ($announcements as $item) { ?>
            <div class="border-b py-3">
                <div class="text-sm text-gray-500">
                    <?php echo $item["problem_num"] === null ? "General" : "Problema " . chr(65 + intval($item["problem_num"])) ?> · <?php echo $item["created_at"] ?>
                </div>
                <div class="oj-rich-content"><?php echo $item["answer"] /* HTML written by the judges in the admin editor */ ?></div>
            </div>
        <?php } ?>
    </div>
<?php }
