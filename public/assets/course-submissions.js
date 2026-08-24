(function () {
    'use strict';
    const config = window.PATITO_SUBMISSIONS_CONFIG || {};
    const courseBase = `${String(config.apiUrl || '/api').replace(/\/+$/, '')}/academic/sites/${Number(config.siteId) || 1}/courses/${Number(config.courseId)}`;
    const assignmentId = Number(config.assignmentId) || 0;

    function token() {
        const item = document.cookie.split('; ').find((value) => value.startsWith('accessToken='));
        return item ? decodeURIComponent(item.slice(12)) : (localStorage.getItem('accessToken') || '');
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
    function cell(text, className) {
        const result = document.createElement('td');
        if (className) result.className = className;
        result.textContent = text;
        return result;
    }

    function actionsCell(item) {
        const result = document.createElement('td');
        result.className = 'flex items-center gap-2';
        const isOwner = config.userId != null && String(config.userId) === String(item.userId);
        const canGrade = config.canGrade === true;

        if (isOwner || canGrade) {
            const viewLink = document.createElement('a');
            viewLink.href = `showsource.php?id=${item.solutionId}`;
            viewLink.target = '_blank';
            viewLink.className = 'text-blue-500 hover:text-blue-700';
            viewLink.textContent = 'Ver código';
            result.append(viewLink);
        }

        if (canGrade) {
            const rejudgeButton = document.createElement('button');
            rejudgeButton.type = 'button';
            rejudgeButton.className = 'border-b hover:bg-muted/50';
            rejudgeButton.textContent = 'Rejudge';
            rejudgeButton.onclick = () => rejudgeSolution(item.solutionId, rejudgeButton);
            result.append(rejudgeButton);
            result.append(buildManualJudgeControls(item.solutionId, item.resultCode));
        }

        return result;
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
        const body = document.getElementById('submissions-body');
        document.getElementById('submissions-empty').classList.toggle('hidden', items.length !== 0);
        document.getElementById('submissions-table').classList.toggle('hidden', items.length === 0);
        items.forEach((item, index) => {
            const row = document.createElement('tr');
            row.className = index % 2 === 0 ? 'evenrow' : 'oddrow';
            row.append(
                cell(`#${item.solutionId}`), cell(item.nick || item.userId), cell(item.problemTitle),
                cell(item.statusLabel, item.statusKey === 'accepted' ? 'result-green' : 'result-red'),
                cell(item.languageName), cell(new Date(item.createdAtUtc).toLocaleString('es-BO')),
                actionsCell(item)
            );
            body.append(row);
        });
        document.getElementById('submissions-content').classList.remove('hidden');
    }).catch((error) => {
        const box = document.getElementById('submissions-error');
        box.textContent = error.message;
        box.classList.remove('hidden');
    }).finally(() => document.getElementById('submissions-loading').classList.add('hidden'));
}());
