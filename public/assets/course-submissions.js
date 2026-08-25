(function () {
    'use strict';
    const config = window.PATITO_SUBMISSIONS_CONFIG || {};
    const courseBase = `${String(config.apiUrl || '/api').replace(/\/+$/, '')}/academic/sites/${Number(config.siteId) || 1}/courses/${Number(config.courseId)}`;
    const assignmentId = Number(config.assignmentId) || 0;

    const RESULT_COLORS = {
        0: 'gray', 1: 'gray', 2: 'orange', 3: 'orange', 4: 'green',
        5: 'red', 6: 'red', 7: 'red', 8: 'red', 9: 'red', 10: 'red', 11: 'red', 12: 'red',
        13: 'gray', 14: 'black'
    };

    function token() {
        const item = document.cookie.split('; ').find((value) => value.startsWith('accessToken='));
        return item ? decodeURIComponent(item.slice(12)) : '';
    }
    async function get(url) {
        let response = await fetch(url, { headers: { Accept: 'application/json', Authorization: `Bearer ${token()}` } });
        if (response.status === 401 && window.PatitoAuth) {
            const freshToken = await window.PatitoAuth.refreshAccessToken();
            if (freshToken) response = await fetch(url, { headers: { Accept: 'application/json', Authorization: `Bearer ${freshToken}` } });
        }
        if (!response.ok) throw new Error(response.status === 403 ? 'No tienes acceso a los envíos de este curso.' : 'No se pudieron cargar los envíos.');
        return response.json();
    }

    function canViewSource(item) {
        const isOwner = config.userId != null && String(config.userId) === String(item.userId);
        return isOwner || config.canGrade === true;
    }

    function languageCell(item) {
        if (!canViewSource(item)) {
            return document.createTextNode(item.languageName);
        }
        const link = document.createElement('a');
        link.href = `showsource.php?id=${item.solutionId}`;
        link.target = '_blank';
        link.className = 'text-blue-500 hover:text-blue-700';
        link.textContent = item.languageName;
        return link;
    }

    function resultCell(item) {
        const result = document.createElement('span');
        result.className = 'flex items-center gap-4';
        result.addEventListener('click', (event) => event.stopPropagation());

        if (config.canGrade === true) {
            const rejudgeButton = document.createElement('button');
            rejudgeButton.type = 'button';
            rejudgeButton.className = 'border-b hover:bg-muted/50';
            rejudgeButton.textContent = 'Rejudge';
            rejudgeButton.onclick = () => rejudgeSolution(item.solutionId, rejudgeButton);
            result.append(rejudgeButton);
            result.append(buildManualJudgeControls(item.solutionId, item.resultCode));
        }

        result.append(resultBadge(item));
        return result;
    }

    function resultBadge(item) {
        const color = RESULT_COLORS[Number(item.resultCode)] ?? 'gray';
        const resultCode = Number(item.resultCode);
        const showError = resultCode > 4 && canViewSource(item);
        const badge = document.createElement(showError ? 'a' : 'div');
        badge.className = `status-result result-${color}`;
        badge.textContent = item.statusLabel;
        if (showError) {
            badge.href = `showError.php?sid=${item.solutionId}`;
            badge.target = '_blank';
        }
        return badge;
    }

    function buildManualJudgeControls(solutionId, currentResult) {
        const verdicts = {
            4: 'Accepted', 5: 'Presentation Error', 6: 'Wrong Answer', 7: 'Time Limit Exceed',
            8: 'Memory Limit Exceed', 9: 'Output Limit Exceed', 10: 'Runtime Error',
            11: 'Compile Error', 14: 'IA Detected'
        };
        const span = document.createElement('span');
        span.className = 'manual-judge-controls inline-flex items-center gap-1';
        span.dataset.solutionId = String(solutionId);

        const select = document.createElement('select');
        select.dataset.apiBase = config.apiUrl || '/api';
        select.setAttribute('aria-label', 'Nuevo veredicto');
        select.className = 'manual-verdict-select rounded border border-slate-300 bg-white px-2 py-1 text-xs';
        Object.entries(verdicts).forEach(([resultCode, label]) => {
            const option = document.createElement('option');
            option.value = resultCode;
            option.textContent = label;
            option.selected = Number(resultCode) === Number(currentResult);
            select.append(option);
        });

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'rounded border border-slate-400 px-2 py-1 text-xs hover:bg-slate-100 disabled:opacity-50';
        button.textContent = 'Cambiar';
        button.onclick = () => manuallyJudgeSolution(button);

        span.append(select, button);
        return span;
    }

    async function rejudgeSolution(solutionId, button) {
        button.disabled = true;
        try {
            let response = await fetch(`${config.apiUrl}/Judge/rejudge/solution/${solutionId}`, {
                headers: { Authorization: `Bearer ${token()}` }
            });
            if (response.status === 401 && window.PatitoAuth) {
                const freshToken = await window.PatitoAuth.refreshAccessToken();
                if (freshToken) {
                    response = await fetch(`${config.apiUrl}/Judge/rejudge/solution/${solutionId}`, {
                        headers: { Authorization: `Bearer ${freshToken}` }
                    });
                }
            }
            if (!response.ok) throw new Error('No se pudo reenviar a juzgar.');
            window.location.reload();
        } catch (error) {
            window.alert(error.message);
            button.disabled = false;
        }
    }

    async function load() {
        if (assignmentId > 0) {
            const base = `${courseBase}/assignments/${assignmentId}`;
            const [detail, data] = await Promise.all([get(base), get(`${base}/submissions?page=1&pageSize=100`)]);
            return { title: detail.assignment?.title || 'Contest', items: data.items || [] };
        }

        const course = await get(courseBase);
        const assignments = Array.isArray(course.assignments) ? course.assignments : [];
        const pages = await Promise.all(assignments.map(async (assignment) => {
            const data = await get(`${courseBase}/assignments/${assignment.assignmentId}/submissions?page=1&pageSize=100`);
            return (data.items || []).map((item) => ({ ...item, assignmentTitle: assignment.title }));
        }));
        return {
            title: course.name || 'Curso',
            items: pages.flat().sort((left, right) => Number(right.solutionId) - Number(left.solutionId))
        };
    }

    load().then(({ title, items }) => {
        document.getElementById('assignment-title').textContent = `Envíos · ${title}`;
        document.getElementById('submissions-content').classList.remove('hidden');

        const table = new DataTable('#status-table', {
            data: items,
            order: [],
            pageLength: 100,
            dom: 'Prtip',
            searchPanes: { cascadePanes: true, viewTotal: true },
            select: true,
            columns: [
                { title: 'ID', data: 'solutionId', render: (value) => `#${value}`, searchPanes: { show: false } },
                { title: 'Usuario', data: null, render: (item) => item.nick || item.userId, searchPanes: { show: true } },
                { title: 'Problema', data: 'problemTitle', searchPanes: { show: true } },
                {
                    title: 'Lenguaje', data: null, searchPanes: { show: true },
                    render: (item) => item.languageName,
                    createdCell: (cell, _cellData, item) => cell.replaceChildren(languageCell(item))
                },
                {
                    title: 'Resultado', data: null, searchPanes: { show: true },
                    render: (item) => item.statusLabel,
                    createdCell: (cell, _cellData, item) => cell.replaceChildren(resultCell(item))
                },
                {
                    title: 'Fecha', data: 'createdAtUtc', searchPanes: { show: false },
                    render: (value, type) => (type === 'display' ? new Date(value).toLocaleString('es-BO') : value)
                }
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json',
                emptyTable: 'No hay envíos para mostrar',
                searchPanes: {
                    count: '{total}',
                    countFiltered: '{shown} ({total})',
                    emptyPanes: 'No hay paneles de búsqueda',
                    clearMessage: 'Limpiar todo',
                    collapse: { 0: 'Paneles de búsqueda', _: 'Paneles de búsqueda (%d)' },
                    title: { _: 'Filtros Activos - %d', 0: '', 1: '' }
                }
            }
        });

        table.on('init.dt', () => applyDtFilterStyling());
    }).catch((error) => {
        const box = document.getElementById('submissions-error');
        box.textContent = error.message;
        box.classList.remove('hidden');
    }).finally(() => document.getElementById('submissions-loading').classList.add('hidden'));
}());
