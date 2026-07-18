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
        14 => 'AI_DETECTED',
    ];

    $safeApiBase = htmlspecialchars(rtrim($apiBase, '/'), ENT_QUOTES, 'UTF-8');
    echo '<span class="inline-flex items-center gap-1">';
    echo sprintf(
        '<select id="manual-verdict-%d" data-api-base="%s" aria-label="Nuevo veredicto" class="rounded border border-slate-300 bg-white px-2 py-1 text-xs">',
        $solutionId,
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
    echo sprintf(
        '<button id="manual-verdict-button-%d" type="button" onclick="manuallyJudgeSolution(%d)" class="rounded border border-slate-400 px-2 py-1 text-xs transition-colors hover:bg-slate-100 disabled:opacity-50">Cambiar</button>',
        $solutionId,
        $solutionId
    );
    echo '</span>';
}
