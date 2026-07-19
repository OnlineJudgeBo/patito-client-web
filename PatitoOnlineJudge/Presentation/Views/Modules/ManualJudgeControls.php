<?php

function renderManualJudgeControls(int $solutionId, int $currentResult, string $apiBase): void
{
    $verdicts = [
        4 => 'Accepted',
        5 => 'Presentation Error',
        6 => 'Wrong Answer',
        7 => 'Time Limit Exceed',
        8 => 'Memory Limit Exceed',
        9 => 'Output Limit Exceed',
        10 => 'Runtime Error',
        11 => 'Compile Error',
        14 => 'IA Detected',
    ];

    $safeApiBase = htmlspecialchars(rtrim($apiBase, '/'), ENT_QUOTES, 'UTF-8');
    echo sprintf(
        '<span class="manual-judge-controls inline-flex items-center gap-1" data-solution-id="%d">',
        $solutionId
    );
    echo sprintf(
        '<select data-api-base="%s" aria-label="Nuevo veredicto" class="manual-verdict-select rounded border border-slate-300 bg-white px-2 py-1 text-xs">',
        $safeApiBase
    );

    foreach ($verdicts as $resultCode => $label) {
        $selected = $resultCode === $currentResult ? ' selected' : '';
        echo sprintf(
            '<option value="%d"%s>%s</option>',
            $resultCode,
            $selected,
            htmlspecialchars($label, ENT_QUOTES, 'UTF-8')
        );
    }

    echo '</select>';
    echo '<button type="button" onclick="manuallyJudgeSolution(this)" class="rounded border border-slate-400 px-2 py-1 text-xs transition-colors hover:bg-slate-100 disabled:opacity-50">Cambiar</button>';
    echo '</span>';
}
