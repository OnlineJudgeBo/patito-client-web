<?php
// Judge announcements, highlighted above the problems (as in Codeforces).
$announcements = array_filter($clarifications, fn($item) => $item["user_id"] === null);
if (!empty($announcements)) { ?>
    <div class="mx-6 mb-4 rounded-lg border border-yellow-300 bg-yellow-50 p-4">
        <h4 class="font-semibold mb-2">Anuncios</h4>
        <?php foreach ($announcements as $item) { ?>
            <div class="border-t border-yellow-200 py-2 first:border-t-0">
                <div class="text-sm text-gray-500">
                    <?php echo $item["problem_num"] === null ? "General" : "Problema " . chr(65 + intval($item["problem_num"])) ?> · <?php echo $item["created_at"] ?>
                </div>
                <div class="oj-rich-content"><?php echo $item["answer"] /* HTML written by the judges in the admin editor */ ?></div>
            </div>
        <?php } ?>
    </div>
<?php }
