(function () {
    'use strict';
    const config = window.PATITO_COURSE_RANKING_CONFIG || {};
    const endpoint = `${String(config.apiUrl || '/api').replace(/\/+$/, '')}/academic/sites/${Number(config.siteId) || 1}/courses/${Number(config.courseId)}`;
    const assignmentId = Number(config.assignmentId) || 0;

    function cookie(name) {
        const item = document.cookie.split('; ').find((value) => value.startsWith(`${name}=`));
        return item ? decodeURIComponent(item.slice(name.length + 1)) : '';
    }
    function node(tag, className, text) {
        const result = document.createElement(tag);
        if (className) result.className = className;
        if (text !== undefined) result.textContent = text;
        return result;
    }
    function fetchWithToken(url, token) {
        return fetch(url, { headers: { Accept: 'application/json', ...(token ? { Authorization: `Bearer ${token}` } : {}) } });
    }
    async function request(url) {
        const token = cookie('accessToken') || localStorage.getItem('accessToken') || '';
        let response = await fetchWithToken(url, token);
        if (response.status === 401 && window.PatitoAuth) {
            const freshToken = await window.PatitoAuth.refreshAccessToken();
            if (freshToken) response = await fetchWithToken(url, freshToken);
        }
        if (!response.ok) throw new Error(response.status === 403 ? 'No tienes acceso a este ranking.' : 'No se pudo cargar el ranking.');
        return response.json();
    }
    function render(course, items) {
        const ranking = Array.isArray(items) ? items : [];
        const selectedContest = assignmentId > 0
            ? (course.assignments || []).find((item) => Number(item.assignmentId) === assignmentId)
            : null;
        document.getElementById('ranking-course-name').textContent = selectedContest?.title || course.name || `Curso #${course.courseId}`;
        document.querySelector('#ranking-content .oj-page-description').textContent = selectedContest
            ? 'Clasificación de este contest del curso.'
            : 'Resultados acumulados de todos los contests del curso.';
        const body = document.getElementById('ranking-body');
        document.getElementById('ranking-empty').classList.toggle('hidden', ranking.length !== 0);
        document.getElementById('ranking-table-wrapper').classList.toggle('hidden', ranking.length === 0);
        ranking.forEach((item, index) => {
            const row = node('tr', index % 2 === 0 ? 'evenrow' : 'oddrow');
            const positionCell = node('td');
            const position = node('span', 'oj-rank-position', String(item.rank || index + 1));
            position.dataset.position = String(item.rank || index + 1);
            positionCell.append(position);
            const name = node('td', 'px-2', item.nick || item.userId);
            const user = node('td', 'px-2', item.userId || '—');
            const assignment = assignmentId > 0
                ? (item.assignments || []).find((value) => Number(value.assignmentId) === assignmentId)
                : null;
            const solvedValue = assignmentId > 0 ? Number(assignment?.solved || 0) : Number(item.solved || 0);
            const solved = node('td', 'px-2');
            solved.append(node('span', 'oj-stat', String(solvedValue)));
            const contests = node('td', 'px-2');
            const badges = node('div', 'flex flex-wrap gap-1');
            (item.assignments || [])
                .filter((value) => assignmentId === 0 || Number(value.assignmentId) === assignmentId)
                .forEach((value) => badges.append(node('span', 'rounded bg-slate-100 px-2 py-1 text-xs text-slate-700', `${value.title}: ${value.solved}/${value.problemCount}`)));
            contests.append(badges);
            row.append(
                positionCell,
                name,
                user,
                solved,
                node('td', 'px-2', assignmentId > 0 ? '—' : String(Number(item.attempts || 0))),
                contests
            );
            body.append(row);
        });
        document.getElementById('ranking-content').classList.remove('hidden');
    }
    Promise.all([request(endpoint), request(`${endpoint}/ranking`)])
        .then(([course, ranking]) => render(course, ranking))
        .catch((error) => {
            const box = document.getElementById('ranking-error');
            box.textContent = error.message;
            box.classList.remove('hidden');
        })
        .finally(() => document.getElementById('ranking-loading').classList.add('hidden'));
}());
