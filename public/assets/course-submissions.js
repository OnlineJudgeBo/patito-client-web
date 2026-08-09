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
        const response = await fetch(url, { headers: { Accept: 'application/json', Authorization: `Bearer ${token()}` } });
        if (!response.ok) throw new Error(response.status === 403 ? 'No tienes acceso a los envíos de este curso.' : 'No se pudieron cargar los envíos.');
        return response.json();
    }
    function cell(text, className) {
        const result = document.createElement('td');
        if (className) result.className = className;
        result.textContent = text;
        return result;
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
                cell(item.languageName), cell(new Date(item.createdAtUtc).toLocaleString('es-BO'))
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
