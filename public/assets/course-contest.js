(function () {
    'use strict';

    const config = window.PATITO_COURSE_CONTEST_CONFIG || {};
    const apiBase = String(config.apiUrl || '/api').replace(/\/+$/, '');
    const courseId = Number(config.courseId) || 0;
    const assignmentId = Number(config.assignmentId) || 0;
    const endpoint = `${apiBase}/academic/sites/${Number(config.siteId) || 1}/courses/${courseId}`;

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

    function formatRawDateTime(value) {
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return 'Sin fecha';
        const pad = (n) => String(n).padStart(2, '0');
        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())} ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
    }

    function formatCountdownParts(diffMs) {
        const totalSeconds = Math.max(0, Math.floor(diffMs / 1000));
        const days = Math.floor(totalSeconds / 86400);
        const hours = Math.floor((totalSeconds % 86400) / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;
        return `${days} días, ${hours} horas, ${minutes} minutos, ${seconds} segundos`;
    }

    async function request(url, options) {
        const token = cookie('accessToken') || localStorage.getItem('accessToken') || '';
        const response = await fetch(url || endpoint, {
            ...(options || {}),
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                ...(token ? { Authorization: `Bearer ${token}` } : {})
            }
        });
        if (!response.ok) {
            let detail = '';
            try {
                const body = await response.json();
                detail = body.message || body.title || body.detail || '';
                if (body.errors) detail = Object.values(body.errors).flat().join(' ');
            } catch (_) {
                detail = '';
            }
            throw new Error(detail || (response.status === 403
                ? 'No tienes acceso a este curso.'
                : 'No se pudo cargar el contest.'));
        }
        return response.status === 204 ? null : response.json();
    }

    function updateCountdown(element) {
        const start = new Date(element.dataset.startTime).getTime();
        const end = new Date(element.dataset.endTime).getTime();
        const now = Date.now();
        if (!Number.isFinite(start) || !Number.isFinite(end)) {
            element.textContent = '';
            return;
        }
        element.replaceChildren();
        if (now < start) {
            element.append(
                node('span', 'result-blue', `Iniciara el ${formatRawDateTime(element.dataset.startTime)}`),
                document.createElement('br'),
                node('span', 'result-green', formatCountdownParts(start - now))
            );
        } else if (now <= end) {
            element.append(
                node('span', 'result-red', ' Corriendo '),
                document.createElement('br'),
                node('span', 'result-green', ` Termina el: ${formatRawDateTime(element.dataset.endTime)} `),
                document.createElement('br'),
                document.createTextNode(formatCountdownParts(end - now))
            );
        } else {
            element.append(node('span', 'result-green', `Termino el ${formatRawDateTime(element.dataset.endTime)}`));
        }
    }

    function updateCountdowns() {
        document.querySelectorAll('[data-course-countdown]').forEach(updateCountdown);
    }

    async function fetchAllAssignmentSubmissions() {
        const base = `${endpoint}/assignments/${assignmentId}/submissions`;
        const pageSize = 100;
        let page = 1;
        let all = [];
        for (let guard = 0; guard < 20; guard += 1) {
            const data = await request(`${base}?page=${page}&pageSize=${pageSize}`);
            const items = Array.isArray(data?.items) ? data.items : [];
            all = all.concat(items);
            if (items.length < pageSize || all.length >= Number(data?.total || 0)) break;
            page += 1;
        }
        return all;
    }

    async function loadContestParticipation(participationCells) {
        try {
            const submissions = await fetchAllAssignmentSubmissions();
            const attempted = new Map();
            const solved = new Map();
            submissions.forEach((item) => {
                const problemId = Number(item.problemId);
                const userId = item.userId;
                if (!userId || !Number.isInteger(problemId)) return;
                if (!attempted.has(problemId)) attempted.set(problemId, new Set());
                attempted.get(problemId).add(userId);
                if (item.statusKey === 'accepted') {
                    if (!solved.has(problemId)) solved.set(problemId, new Set());
                    solved.get(problemId).add(userId);
                }
            });
            participationCells.forEach((cells, problemId) => {
                cells.solvedCell.textContent = String(solved.get(problemId)?.size || 0);
                cells.attemptsCell.textContent = String(attempted.get(problemId)?.size || 0);
            });
        } catch (_) {
            participationCells.forEach((cells) => {
                cells.solvedCell.textContent = '—';
                cells.attemptsCell.textContent = '—';
            });
        }
    }

    function renderContest(course, contest) {
        const card = node('div', 'w-full rounded-lg border bg-white shadow-sm');

        const heading = node('div', 'flex flex-col items-center space-y-1.5 p-6');
        const back = node('a', 'mb-3 self-start text-sm font-semibold text-blue-700 hover:underline', '← Volver al contenido del curso');
        back.href = `course.php?id=${encodeURIComponent(course.courseId)}`;
        heading.append(back);
        heading.append(node('h3', 'text-2xl font-semibold leading-none tracking-tight', `${contest.assignmentId} ${contest.title || 'Contest'}`));
        if (contest.description) heading.append(node('h5', 'font-semibold leading-none tracking-tight py-2', contest.description));
        card.append(heading);

        const info = node('div', 'flex items-center justify-center py-4');
        const grid = node('div', 'grid grid-cols-2 gap-1');
        const startLine = node('div', 'flex justify-left');
        startLine.append(node('b', '', 'Hora de Inicio:'), document.createTextNode(formatRawDateTime(contest.opensAt)));
        const endLine = node('div', 'flex justify-left');
        endLine.append(node('b', '', 'Hora de Fin:'), document.createTextNode(formatRawDateTime(contest.dueAt)));
        const statusWrap = node('div', 'flex justify-center col-span-2');
        const status = node('span', '');
        status.dataset.courseCountdown = 'true';
        status.dataset.startTime = contest.opensAt;
        status.dataset.endTime = contest.dueAt;
        updateCountdown(status);
        statusWrap.append(status);
        grid.append(startLine, endLine, statusWrap);
        info.append(grid);
        card.append(info);

        const problems = (contest.problems || []).filter((problem) => problem.isVisible !== false);
        const tableOuter = node('div', 'p-1');
        const tableWrapper = node('div', 'relative w-full overflow-auto');
        const table = node('table', 'border-b transition-colors hover:bg-muted/50 w-full');
        const thead = node('thead', 'bg-gray-900 text-white');
        const headerRow = node('tr', 'shadow-lg');
        ['', 'Problema', 'Nombre', 'Resueltos', 'Intentos'].forEach((label, index) => {
            headerRow.append(node('th', index < 4 ? 'p-1 font-bold text-lg border-r' : 'p-1 font-bold text-lg', label));
        });
        thead.append(headerRow);
        const tbody = node('tbody', 'content-center');
        const participationCells = new Map();
        if (problems.length === 0) {
            const emptyRow = node('tr');
            const emptyCell = node('td', 'p-4 text-center text-gray-500', 'Los problemas de este contest todavía no están disponibles.');
            emptyCell.colSpan = 5;
            emptyRow.append(emptyCell);
            tbody.append(emptyRow);
        } else {
            problems.forEach((problem, index) => {
                const row = node('tr', `border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted ${index % 2 === 0 ? 'evenrow' : 'oddrow'}`);
                row.append(node('td', 'p-1 text-center align-middle', problem.isSolvedByCurrentUser ? 'Y' : ''));
                row.append(node('td', 'p-1 text-center align-middle', `${index + 1} ${String.fromCharCode(65 + index)}`));
                const nameCell = node('td', 'p-1 text-center align-middle result-blue');
                const action = node('a', '', problem.title || 'Problema');
                action.href = `problem.php?id=${encodeURIComponent(problem.problemId)}&courseId=${encodeURIComponent(course.courseId)}&assignmentId=${encodeURIComponent(contest.assignmentId)}`;
                nameCell.append(action);
                const solvedCell = node('td', 'p-1 text-center align-middle', course.canManage ? '…' : (problem.isSolvedByCurrentUser ? '1' : '0'));
                const attemptsCell = node('td', 'p-1 text-center align-middle', course.canManage ? '…' : String(Number(problem.attemptsByCurrentUser || 0)));
                row.append(nameCell, solvedCell, attemptsCell);
                tbody.append(row);
                if (course.canManage) participationCells.set(Number(problem.problemId), { solvedCell, attemptsCell });
            });
        }
        table.append(thead, tbody);
        tableWrapper.append(table);
        tableOuter.append(tableWrapper);
        card.append(tableOuter);
        if (course.canManage && participationCells.size > 0) {
            loadContestParticipation(participationCells);
        }
        return card;
    }

    async function load() {
        const course = await request(endpoint);
        const contest = (course.assignments || []).find((item) => Number(item.assignmentId) === assignmentId);
        if (!contest) throw new Error('No se encontró este contest en el curso.');
        document.getElementById('contest-card').append(renderContest(course, contest));
        document.getElementById('contest-detail').classList.remove('hidden');
    }

    load()
        .catch((error) => {
            const box = document.getElementById('contest-error');
            box.textContent = error.message;
            box.classList.remove('hidden');
        })
        .finally(() => document.getElementById('contest-loading').classList.add('hidden'));

    window.setInterval(updateCountdowns, 1000);
}());
