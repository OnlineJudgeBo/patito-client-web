(function () {
    'use strict';

    const config = window.PATITO_COURSE_CONFIG || {};
    const apiBase = String(config.apiUrl || '/api').replace(/\/+$/, '');
    const endpoint = `${apiBase}/academic/sites/${Number(config.siteId) || 1}/courses/${Number(config.courseId)}`;
    const selectedAssignmentId = Number(config.assignmentId) || 0;
    let currentCourse = null;
    let membersLoaded = false;
    let reportLoaded = false;

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

    function formatDate(value) {
        const date = new Date(value);
        return Number.isNaN(date.getTime()) ? 'Sin fecha' : date.toLocaleString('es-BO', {
            dateStyle: 'medium',
            timeStyle: 'short'
        });
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
                if (body.errors) {
                    detail = Object.values(body.errors).flat().join(' ');
                }
            } catch (_) {
                detail = '';
            }
            throw new Error(detail || (response.status === 403
                ? 'No tienes acceso a este curso.'
                : 'No se pudo cargar el curso.'));
        }
        return response.status === 204 ? null : response.json();
    }

    function formatRemaining(milliseconds) {
        const totalSeconds = Math.max(0, Math.floor(milliseconds / 1000));
        const days = Math.floor(totalSeconds / 86400);
        const hours = String(Math.floor((totalSeconds % 86400) / 3600)).padStart(2, '0');
        const minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
        const seconds = String(totalSeconds % 60).padStart(2, '0');
        return (days > 0 ? `${days} días, ` : '') + `${hours}:${minutes}:${seconds}`;
    }

    function updateCountdown(element) {
        const start = new Date(element.dataset.startTime).getTime();
        const end = new Date(element.dataset.endTime).getTime();
        const now = Date.now();
        if (!Number.isFinite(start) || !Number.isFinite(end)) {
            element.textContent = '';
            return;
        }
        if (now < start) {
            element.className = 'result-blue';
            element.textContent = `Inicia el ${formatDate(element.dataset.startTime)} (en ${formatRemaining(start - now)})`;
        } else if (now <= end) {
            element.className = 'result-red';
            element.textContent = `Corriendo · quedan ${formatRemaining(end - now)}`;
        } else {
            element.className = 'result-green';
            element.textContent = `Finalizó el ${formatDate(element.dataset.endTime)}`;
        }
    }

    function updateCountdowns() {
        document.querySelectorAll('[data-course-countdown]').forEach(updateCountdown);
    }

    function renderContest(course, contest) {
        const card = node('div', 'w-full rounded-lg border bg-white shadow-sm');
        const heading = node('div', 'flex flex-col items-center p-6');
        heading.append(node('h3', 'text-2xl font-semibold leading-none tracking-tight text-center', contest.title || `Contest #${contest.assignmentId}`));
        if (contest.description) heading.append(node('h5', 'py-2 text-center font-semibold leading-none tracking-tight', contest.description));
        card.append(heading);

        const info = node('div', 'flex items-center justify-center py-4');
        const grid = node('div', 'grid grid-cols-2 gap-1');
        const startLine = node('div', 'flex justify-left');
        startLine.append(node('b', '', 'Hora de Inicio:'), document.createTextNode(formatDate(contest.opensAt)));
        const endLine = node('div', 'flex justify-left');
        endLine.append(node('b', '', 'Hora de Fin:'), document.createTextNode(formatDate(contest.dueAt)));
        const statusWrap = node('div', 'col-span-2 flex justify-center');
        const status = node('span', 'font-semibold');
        status.dataset.courseCountdown = 'true';
        status.dataset.startTime = contest.opensAt;
        status.dataset.endTime = contest.dueAt;
        updateCountdown(status);
        statusWrap.append(status);
        grid.append(startLine, endLine, statusWrap);
        info.append(grid);
        card.append(info);

        const problems = (contest.problems || []).filter((problem) => problem.isVisible !== false);
        const tableWrapper = node('div', 'p-1');
        const scroll = node('div', 'relative w-full overflow-auto');
        const table = node('table', 'w-full border-b');
        const thead = node('thead', 'bg-gray-900 text-white');
        const headerRow = node('tr', 'shadow-lg');
        ['', 'Problema', 'Nombre', 'Resueltos', 'Intentos'].forEach((label) => headerRow.append(node('th', 'border-r p-1 text-lg font-bold', label)));
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
                const row = node('tr', `border-b transition-colors hover:bg-slate-50 ${index % 2 === 0 ? 'evenrow' : 'oddrow'}`);
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
        scroll.append(table);
        tableWrapper.append(scroll);
        card.append(tableWrapper);
        if (course.canManage && participationCells.size > 0) {
            loadContestParticipation(course.courseId, contest.assignmentId, participationCells);
        }
        return card;
    }

    async function fetchAllAssignmentSubmissions(courseId, assignmentId) {
        const base = `${apiBase}/academic/sites/${Number(config.siteId) || 1}/courses/${Number(courseId)}/assignments/${Number(assignmentId)}/submissions`;
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

    async function loadContestParticipation(courseId, assignmentId, participationCells) {
        try {
            const submissions = await fetchAllAssignmentSubmissions(courseId, assignmentId);
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

    function renderMaterial(material) {
        const card = node('article', 'oj-card w-full');
        const header = node('div', 'border-b border-slate-100 p-6');
        header.append(node('p', 'mb-2 text-xs font-bold uppercase tracking-wider text-blue-700', 'Material de estudio'));
        header.append(node('h2', 'text-xl font-bold text-slate-900', material.title || 'Material'));
        if (material.description) header.append(node('p', 'mt-2 text-sm text-slate-600', material.description));
        card.append(header);
        const body = node('div', 'space-y-4 p-6');
        if (material.contentBody) {
            const content = node('div', 'oj-rich-content text-sm leading-7 text-slate-700');
            content.innerHTML = window.DOMPurify
                ? window.DOMPurify.sanitize(material.contentBody)
                : material.contentBody.replace(/<[^>]*>/g, '');
            body.append(content);
        }
        if (material.contentUrl) {
            const link = node('a', 'inline-flex items-center gap-2 rounded bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600', 'Abrir recurso ↗');
            link.href = material.contentUrl;
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            body.append(link);
        }
        card.append(body);
        return card;
    }

    function renderPathStep(course, item, index, managerStatCells) {
        const stepLabel = `Paso ${index + 1}`;
        const row = node('div', 'flex items-stretch gap-2');
        const canDrag = Boolean(course.canManage) && Number(item.itemId) > 0;
        if (canDrag) {
            row.draggable = true;
            row.dataset.itemId = String(item.itemId);
            const handle = node('div', 'flex w-6 shrink-0 cursor-grab select-none items-center justify-center text-lg text-slate-300 hover:text-slate-500 active:cursor-grabbing', '⠿');
            handle.setAttribute('aria-hidden', 'true');
            handle.title = 'Arrastra para reordenar';
            row.append(handle);
        }

        const inner = node('div', 'min-w-0 flex-1');
        if (item.type === 'contest') {
            const contest = item.assignment || item;
            const link = node('a', 'flex flex-wrap items-center justify-between gap-3 rounded border bg-white p-4 hover:bg-slate-50');
            link.href = `course.php?id=${encodeURIComponent(course.courseId)}&assignmentId=${encodeURIComponent(contest.assignmentId)}`;
            const text = node('div', 'min-w-0 flex-1');
            text.append(
                node('p', 'text-xs font-semibold uppercase text-slate-500', `${stepLabel} · Contest`),
                node('h2', 'truncate text-base font-bold text-slate-900', contest.title || 'Contest')
            );
            if (contest.description) text.append(node('p', 'mt-1 truncate text-sm text-slate-600', contest.description));
            const meta = node('div', 'shrink-0 text-right text-sm text-slate-600');
            const total = Number(contest.problemCount || 0);
            if (course.canManage) {
                const stats = node('div', 'flex flex-col items-end gap-0.5');
                const attemptedLine = node('p', 'text-xs font-semibold text-slate-700', '…');
                const solvedLine = node('p', 'text-xs text-slate-600', '…');
                stats.append(attemptedLine, solvedLine);
                meta.append(stats, node('p', 'mt-1', contest.statusLabel || 'Contest'));
                if (managerStatCells) managerStatCells.set(Number(contest.assignmentId), { attemptedLine, solvedLine });
            } else {
                const solved = Math.min(Number(contest.solvedByCurrentUser || 0), total);
                const progress = node('div', 'flex flex-col items-end gap-1');
                progress.append(node('p', 'text-xs font-semibold text-slate-700', total > 0 ? `${solved}/${total} resueltos` : 'Sin problemas'));
                const track = node('div', 'h-1.5 w-28 overflow-hidden rounded-full bg-slate-200');
                const fill = node('div', 'h-full rounded-full bg-green-700');
                fill.style.width = `${total > 0 ? Math.round((solved / total) * 100) : 0}%`;
                track.append(fill);
                progress.append(track);
                meta.append(progress, node('p', 'mt-1', contest.statusLabel || 'Contest'));
            }
            link.append(text, meta);
            inner.append(link);
        } else {
            const card = node('details', 'group rounded border bg-white');
            const summary = node('summary', 'flex cursor-pointer list-none items-center justify-between gap-3 p-4');
            const text = node('div', 'min-w-0 flex-1');
            text.append(
                node('p', 'text-xs font-semibold uppercase text-slate-500', `${stepLabel} · Material`),
                node('h2', 'truncate text-base font-bold text-slate-900', item.title || 'Material')
            );
            summary.append(text, node('span', 'shrink-0 text-sm font-semibold text-blue-600 group-open:hidden', 'Ver'));
            const detail = renderMaterial(item);
            detail.className = 'border-t';
            if (detail.firstElementChild) detail.firstElementChild.remove();
            card.append(summary, detail);
            inner.append(card);
        }
        row.append(inner);
        return row;
    }

    function enableContentReordering(list, course) {
        let draggedRow = null;
        Array.from(list.querySelectorAll(':scope > [draggable="true"]')).forEach((row) => {
            row.addEventListener('dragstart', (event) => {
                draggedRow = row;
                row.classList.add('opacity-50');
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/plain', row.dataset.itemId || '');
            });
            row.addEventListener('dragend', () => {
                row.classList.remove('opacity-50');
                draggedRow = null;
            });
            row.addEventListener('dragover', (event) => {
                if (!draggedRow || draggedRow === row) return;
                event.preventDefault();
                const rect = row.getBoundingClientRect();
                const before = (event.clientY - rect.top) < rect.height / 2;
                list.insertBefore(draggedRow, before ? row : row.nextElementSibling);
            });
            row.addEventListener('drop', (event) => {
                event.preventDefault();
                saveContentOrder(list, course);
            });
        });
    }

    async function saveContentOrder(list, course) {
        const itemIds = Array.from(list.querySelectorAll(':scope > [draggable="true"]'))
            .map((row) => Number(row.dataset.itemId))
            .filter((id) => Number.isInteger(id) && id > 0);
        if (itemIds.length < 2) return;
        try {
            await request(`${endpoint}/content-order`, { method: 'PUT', body: JSON.stringify({ itemIds }) });
        } catch (error) {
            // Ignored: re-fetching below restores the authoritative order either way.
        }
        render(await request(endpoint));
    }

    function render(course) {
        currentCourse = course;
        const contests = Array.isArray(course.assignments) ? course.assignments : [];
        const content = Array.isArray(course.content) && course.content.length
            ? course.content.filter((item) => item.isPublished !== false || course.canManage === true)
            : contests.map((assignment, index) => ({ type: 'contest', assignment, position: (index + 1) * 10 }));
        const list = document.getElementById('contest-list');
        list.replaceChildren();
        if (selectedAssignmentId > 0) {
            const contest = contests.find((item) => Number(item.assignmentId) === selectedAssignmentId);
            document.getElementById('contest-management').classList.add('hidden');
            document.getElementById('course-path-header').classList.add('hidden');
            if (contest) {
                const back = node('a', 'mb-4 inline-block text-sm font-semibold text-blue-700 hover:underline', '← Volver al contenido del curso');
                back.href = `course.php?id=${encodeURIComponent(course.courseId)}`;
                list.append(back, renderContest(course, contest));
            }
            document.getElementById('contests-empty').classList.toggle('hidden', Boolean(contest));
            list.classList.toggle('hidden', !contest);
        } else {
            document.getElementById('contests-empty').classList.toggle('hidden', content.length !== 0);
            list.classList.toggle('hidden', content.length === 0);
            content.sort((left, right) => Number(left.position || 0) - Number(right.position || 0));
            const managerStatCells = new Map();
            content.forEach((item, index) => list.append(renderPathStep(course, item, index, managerStatCells)));
            if (course.canManage === true && content.length > 1) {
                enableContentReordering(list, course);
            }
            if (course.canManage === true && managerStatCells.size > 0) {
                loadContentManagerStats(managerStatCells);
            }
            const description = document.querySelector('#course-path-header .oj-page-description');
            if (description) {
                description.textContent = course.canManage === true && content.length > 1
                    ? 'Arrastra los elementos con ⠿ para cambiar el orden en el que los ve el estudiante.'
                    : 'Avanza por los materiales y contests en el orden preparado por tu docente.';
            }
        }
        const canManageHere = course.canManage === true && selectedAssignmentId === 0;
        document.getElementById('course-tabs').classList.toggle('hidden', !canManageHere);
        if (canManageHere) {
            activateTab(new URLSearchParams(window.location.search).get('tab') || 'content');
        } else {
            document.getElementById('contest-management').classList.add('hidden');
        }
        document.getElementById('course-content').classList.remove('hidden');
    }

    function activateTab(tabName) {
        const validTab = ['content', 'students', 'report'].includes(tabName) ? tabName : 'content';
        document.querySelectorAll('.course-tab').forEach((button) => {
            const active = button.dataset.tab === validTab;
            button.classList.toggle('border-green-700', active);
            button.classList.toggle('text-slate-900', active);
            button.classList.toggle('border-transparent', !active);
            button.classList.toggle('text-slate-500', !active);
            button.setAttribute('aria-selected', String(active));
        });
        ['content', 'students', 'report'].forEach((name) => {
            document.getElementById(`tab-panel-${name}`).classList.toggle('hidden', name !== validTab);
        });
        const managementPanel = document.getElementById('contest-management');
        const showManagement = validTab === 'content';
        managementPanel.classList.toggle('hidden', !showManagement);
        managementPanel.classList.toggle('flex', showManagement);

        const url = new URL(window.location.href);
        if (validTab === 'content') {
            url.searchParams.delete('tab');
        } else {
            url.searchParams.set('tab', validTab);
        }
        history.replaceState(null, '', url);

        if (validTab === 'students' && !membersLoaded) {
            membersLoaded = true;
            loadMembers();
        }
        if (validTab === 'report' && !reportLoaded) {
            reportLoaded = true;
            loadReport();
        }
    }

    document.querySelectorAll('.course-tab').forEach((button) => {
        button.addEventListener('click', () => activateTab(button.dataset.tab));
    });

    const memberRoleLabels = { teacher: 'Docente', assistant: 'Auxiliar', student: 'Estudiante', admin: 'Administrador' };
    const addMemberMessage = document.getElementById('add-member-message');

    function showAddMemberMessage(text, type) {
        addMemberMessage.textContent = text;
        addMemberMessage.className = `mt-3 rounded border p-3 text-sm ${type === 'error'
            ? 'border-red-200 bg-red-50 text-red-800'
            : 'border-green-200 bg-green-50 text-green-800'}`;
    }

    async function loadMembers() {
        const loading = document.getElementById('members-loading');
        const empty = document.getElementById('members-empty');
        const wrapper = document.getElementById('members-wrapper');
        const list = document.getElementById('members-list');
        loading.classList.remove('hidden');
        try {
            const members = await request(`${endpoint}/members`);
            const values = Array.isArray(members) ? members : [];
            list.replaceChildren();
            values.forEach((member) => {
                const row = node('tr');
                row.append(
                    node('td', 'font-mono text-xs text-slate-500', member.userId),
                    node('td', 'font-semibold text-slate-900', member.nick || member.userId),
                    node('td', '', memberRoleLabels[member.role] || member.role)
                );
                const actionCell = node('td');
                if (!member.isOwner && member.role !== 'teacher') {
                    const remove = node('button', 'text-sm font-semibold text-red-600 hover:underline', 'Quitar');
                    remove.type = 'button';
                    remove.addEventListener('click', () => removeMember(member.userId));
                    actionCell.append(remove);
                }
                row.append(actionCell);
                list.append(row);
            });
            empty.classList.toggle('hidden', values.length !== 0);
            wrapper.classList.toggle('hidden', values.length === 0);
        } catch (error) {
            showAddMemberMessage(error.message, 'error');
        } finally {
            loading.classList.add('hidden');
        }
    }

    async function removeMember(userId) {
        if (!window.confirm(`¿Quitar a ${userId} del curso?`)) return;
        try {
            await request(`${endpoint}/members/${encodeURIComponent(userId)}`, { method: 'DELETE' });
            await loadMembers();
        } catch (error) {
            showAddMemberMessage(error.message, 'error');
        }
    }

    const memberSearchInput = document.getElementById('member-search');
    const memberSearchResults = document.getElementById('member-search-results');
    let selectedMemberUserId = '';
    let lastMemberSearchResults = [];
    let memberSearchTimer;

    function findExactUserMatch(users, term) {
        const normalized = term.trim().toLowerCase();
        return users.find((user) => [user.userId, user.userProfile?.email]
            .filter(Boolean)
            .some((value) => String(value).trim().toLowerCase() === normalized)) || null;
    }

    memberSearchInput.addEventListener('input', () => {
        selectedMemberUserId = '';
        window.clearTimeout(memberSearchTimer);
        const query = memberSearchInput.value.trim();
        if (!query) {
            memberSearchResults.classList.add('hidden');
            return;
        }
        memberSearchTimer = window.setTimeout(async () => {
            try {
                const users = await request(`${apiBase}/users?searchTerm=${encodeURIComponent(query)}`);
                lastMemberSearchResults = Array.isArray(users) ? users : [];
                memberSearchResults.replaceChildren();
                (Array.isArray(users) ? users.slice(0, 12) : []).forEach((user) => {
                    const nick = user.userProfile?.nick || user.userId;
                    const option = node('button', 'block w-full border-b px-3 py-2 text-left text-sm hover:bg-blue-50');
                    option.type = 'button';
                    option.append(node('strong', 'text-blue-700', nick), node('span', 'ml-2 text-slate-500', user.userId));
                    option.addEventListener('click', () => {
                        selectedMemberUserId = user.userId;
                        memberSearchInput.value = `${nick} (${user.userId})`;
                        memberSearchResults.classList.add('hidden');
                    });
                    memberSearchResults.append(option);
                });
                if (!memberSearchResults.children.length) memberSearchResults.append(node('p', 'p-3 text-sm text-slate-500', 'No se encontraron usuarios.'));
                memberSearchResults.classList.remove('hidden');
            } catch (_) {
                memberSearchResults.replaceChildren(node('p', 'p-3 text-sm text-red-700', 'No se pudo realizar la búsqueda.'));
                memberSearchResults.classList.remove('hidden');
            }
        }, 300);
    });

    document.addEventListener('click', (event) => {
        if (!memberSearchResults.contains(event.target) && event.target !== memberSearchInput) {
            memberSearchResults.classList.add('hidden');
        }
    });

    document.getElementById('add-member-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const form = event.currentTarget;
        const button = form.querySelector('button[type="submit"]');
        addMemberMessage.classList.add('hidden');

        let userId = selectedMemberUserId;
        if (!userId) {
            const query = memberSearchInput.value.trim();
            let candidates = lastMemberSearchResults;
            if (query && findExactUserMatch(candidates, query) === null) {
                try {
                    candidates = await request(`${apiBase}/users?searchTerm=${encodeURIComponent(query)}`);
                } catch (_) {
                    candidates = [];
                }
            }
            const exactMatch = query ? findExactUserMatch(Array.isArray(candidates) ? candidates : [], query) : null;
            userId = exactMatch?.userId || '';
        }
        if (!userId) {
            showAddMemberMessage('Escribe un usuario o correo exacto, o selecciona uno de la lista.', 'error');
            return;
        }

        const role = document.getElementById('member-role').value;
        button.disabled = true;
        try {
            await request(`${endpoint}/members`, { method: 'POST', body: JSON.stringify({ userId, role }) });
            showAddMemberMessage(`Se agregó a ${userId}.`, 'success');
            form.reset();
            selectedMemberUserId = '';
            lastMemberSearchResults = [];
            await loadMembers();
        } catch (error) {
            showAddMemberMessage(error.message, 'error');
        } finally {
            button.disabled = false;
        }
    });

    document.getElementById('refresh-members').addEventListener('click', loadMembers);

    async function loadContentManagerStats(cellsByAssignment) {
        try {
            const report = await request(`${endpoint}/report`);
            const items = Array.isArray(report?.items) ? report.items : [];
            const attemptedCount = new Map();
            const solvedTotal = new Map();
            items.forEach((studentItem) => {
                (studentItem.assignments || []).forEach((cell) => {
                    const assignmentId = Number(cell.assignmentId);
                    if (Number(cell.attempts || 0) > 0) {
                        attemptedCount.set(assignmentId, (attemptedCount.get(assignmentId) || 0) + 1);
                    }
                    solvedTotal.set(assignmentId, (solvedTotal.get(assignmentId) || 0) + Number(cell.solved || 0));
                });
            });
            cellsByAssignment.forEach((cells, assignmentId) => {
                const attempted = attemptedCount.get(assignmentId) || 0;
                const solved = solvedTotal.get(assignmentId) || 0;
                cells.attemptedLine.textContent = attempted === 1 ? '1 estudiante intentó' : `${attempted} estudiantes intentaron`;
                cells.solvedLine.textContent = `${solved} resueltos en total`;
            });
        } catch (_) {
            cellsByAssignment.forEach((cells) => {
                cells.attemptedLine.textContent = '—';
                cells.solvedLine.textContent = '—';
            });
        }
    }

    async function loadReport() {
        const loading = document.getElementById('report-loading');
        const empty = document.getElementById('report-empty');
        const wrapper = document.getElementById('report-wrapper');
        loading.classList.remove('hidden');
        empty.classList.add('hidden');
        wrapper.classList.add('hidden');
        try {
            const report = await request(`${endpoint}/report`);
            renderReport(report);
        } catch (error) {
            loading.classList.add('hidden');
            empty.classList.remove('hidden');
            empty.textContent = error.message;
        }
    }

    function assignmentCellColor(solved, attempts, problemCount) {
        // Same green/red heat scheme used by the ICPC-style contest ranking (contestRank.php):
        // greener the more solved, redder the more attempts without a solve.
        if (solved > 0) {
            const ratio = problemCount > 0 ? Math.min(solved / problemCount, 1) : 1;
            const channel = Math.round(0xaa - ratio * (0xaa - 0x33));
            const hex = channel.toString(16).padStart(2, '0');
            return `#${hex}ff${hex}`;
        }
        if (attempts > 0) {
            const channel = Math.max(0x10, 0xaa - attempts * 10);
            const hex = channel.toString(16).padStart(2, '0');
            return `#ff${hex}${hex}`;
        }
        return null;
    }

    function renderReport(report) {
        const loading = document.getElementById('report-loading');
        const empty = document.getElementById('report-empty');
        const wrapper = document.getElementById('report-wrapper');
        const head = document.getElementById('report-head');
        const body = document.getElementById('report-body');
        const assignments = Array.isArray(report.assignments) ? report.assignments : [];
        // The API already returns items ranked (solved desc, accepted desc, attempts asc) and matches
        // the CSV export byte for byte, so we render them in that order instead of re-sorting client-side.
        const items = Array.isArray(report.items) ? report.items : [];

        head.replaceChildren();
        const titleRow = node('tr');
        titleRow.append(node('th', 'text-center', '#'), node('th', '', ''), node('th', 'text-center', 'Resueltos'), node('th', 'text-center', 'Intentos'));
        assignments.forEach((assignment) => {
            const th = node('th', 'text-center', assignment.title || `Contest #${assignment.assignmentId}`);
            th.colSpan = 2;
            titleRow.append(th);
        });
        const subRow = node('tr');
        subRow.append(node('th', '', ''), node('th', '', ''), node('th', '', ''), node('th', '', ''));
        assignments.forEach(() => {
            subRow.append(node('th', 'text-center text-xs font-normal normal-case', 'Res.'), node('th', 'text-center text-xs font-normal normal-case', 'Int.'));
        });
        head.append(titleRow, subRow);

        body.replaceChildren();
        items.forEach((item) => {
            const row = node('tr');
            const nameCell = node('td');
            nameCell.append(
                node('p', 'font-semibold text-slate-900', item.nick || item.userId),
                node('p', 'text-xs text-gray-500', item.userId)
            );
            row.append(
                node('td', 'text-center font-semibold text-slate-700', String(Number(item.rank || 0))),
                nameCell,
                node('td', 'text-center', String(Number(item.totalSolved || 0))),
                node('td', 'text-center', String(Number(item.totalAttempts || 0)))
            );
            const cellsByAssignment = new Map((item.assignments || []).map((cell) => [Number(cell.assignmentId), cell]));
            assignments.forEach((assignment) => {
                const cell = cellsByAssignment.get(Number(assignment.assignmentId));
                const solved = Number(cell?.solved || 0);
                const attempts = Number(cell?.attempts || 0);
                const color = assignmentCellColor(solved, attempts, Number(assignment.problemCount || 0));
                const solvedCell = node('td', 'text-center', String(solved));
                const attemptsCell = node('td', 'text-center', String(attempts));
                if (color) {
                    solvedCell.style.backgroundColor = color;
                    attemptsCell.style.backgroundColor = color;
                }
                row.append(solvedCell, attemptsCell);
            });
            body.append(row);
        });

        loading.classList.add('hidden');
        empty.classList.toggle('hidden', items.length !== 0);
        wrapper.classList.toggle('hidden', items.length === 0);
    }

    document.getElementById('refresh-report').addEventListener('click', loadReport);

    document.getElementById('download-report-csv').addEventListener('click', async (event) => {
        const button = event.currentTarget;
        button.disabled = true;
        try {
            const token = cookie('accessToken') || localStorage.getItem('accessToken') || '';
            const response = await fetch(`${endpoint}/report.csv`, {
                headers: { ...(token ? { Authorization: `Bearer ${token}` } : {}) }
            });
            if (!response.ok) throw new Error('No se pudo descargar el reporte.');
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `curso-${currentCourse ? currentCourse.courseId : Number(config.courseId)}-reporte.csv`;
            document.body.append(link);
            link.click();
            link.remove();
            window.URL.revokeObjectURL(url);
        } catch (error) {
            window.alert(error.message);
        } finally {
            button.disabled = false;
        }
    });

    const management = document.getElementById('contest-management');
    const contestForm = document.getElementById('create-contest-form');
    const contestMessage = document.getElementById('contest-form-message');
    const problemsInput = document.getElementById('contest-problems');
    const problemSearch = document.getElementById('problem-search');
    const problemSearchResults = document.getElementById('problem-search-results');
    const selectedProblemLabels = new Map();

    function parseProblemIds(value) {
        return Array.from(new Set(String(value || '').split(/[\s,;]+/).map(Number).filter((id) => Number.isInteger(id) && id > 0)));
    }

    function renderSelectedProblems() {
        const ids = parseProblemIds(problemsInput.value);
        const wrapper = document.getElementById('selected-problems-wrapper');
        const container = document.getElementById('selected-problems');
        wrapper.classList.toggle('hidden', ids.length === 0);
        document.getElementById('selected-problems-count').textContent = `${ids.length} ${ids.length === 1 ? 'problema seleccionado' : 'problemas seleccionados'}`;
        container.replaceChildren();
        ids.forEach((id, index) => {
            const chip = node('div', 'flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700');
            chip.append(node('strong', 'w-5 text-blue-700', String.fromCharCode(65 + index)), node('span', 'min-w-0 flex-1 truncate', selectedProblemLabels.get(id) || `Problema ${id}`));
            const move = (offset) => {
                const target = index + offset;
                if (target < 0 || target >= ids.length) return;
                [ids[index], ids[target]] = [ids[target], ids[index]];
                problemsInput.value = ids.join('\n');
                renderSelectedProblems();
            };
            const up = node('button', 'font-bold text-slate-400 hover:text-blue-700', '↑');
            up.type = 'button'; up.disabled = index === 0; up.addEventListener('click', () => move(-1));
            const down = node('button', 'font-bold text-slate-400 hover:text-blue-700', '↓');
            down.type = 'button'; down.disabled = index === ids.length - 1; down.addEventListener('click', () => move(1));
            const remove = node('button', 'font-bold text-slate-400 hover:text-red-600', '×');
            remove.type = 'button';
            remove.setAttribute('aria-label', `Quitar problema ${id}`);
            remove.addEventListener('click', () => {
                problemsInput.value = ids.filter((value) => value !== id).join('\n');
                selectedProblemLabels.delete(id);
                renderSelectedProblems();
            });
            chip.append(up, down, remove);
            container.append(chip);
        });
    }

    let problemSearchTimer;
    problemSearch.addEventListener('input', () => {
        window.clearTimeout(problemSearchTimer);
        const query = problemSearch.value.trim();
        if (!query) {
            problemSearchResults.classList.add('hidden');
            return;
        }
        problemSearchTimer = window.setTimeout(async () => {
            try {
                const items = await request(`${apiBase}/problems?searchTerm=${encodeURIComponent(query)}`);
                problemSearchResults.replaceChildren();
                (Array.isArray(items) ? items.slice(0, 12) : []).forEach((problem) => {
                    const id = Number(problem.problemId);
                    const option = node('button', 'block w-full border-b px-3 py-2 text-left text-sm hover:bg-blue-50');
                    option.type = 'button';
                    option.append(node('strong', 'text-blue-700', String(id)), node('span', 'ml-2 text-slate-700', problem.title || 'Problema'));
                    option.addEventListener('click', () => {
                        const ids = parseProblemIds(problemsInput.value);
                        if (!ids.includes(id)) ids.push(id);
                        selectedProblemLabels.set(id, `${id} - ${problem.title || 'Problema'}`);
                        problemsInput.value = ids.join('\n');
                        problemSearch.value = '';
                        problemSearchResults.classList.add('hidden');
                        renderSelectedProblems();
                    });
                    problemSearchResults.append(option);
                });
                if (!problemSearchResults.children.length) problemSearchResults.append(node('p', 'p-3 text-sm text-slate-500', 'No se encontraron problemas.'));
                problemSearchResults.classList.remove('hidden');
            } catch (_) {
                problemSearchResults.replaceChildren(node('p', 'p-3 text-sm text-red-700', 'No se pudo realizar la búsqueda.'));
                problemSearchResults.classList.remove('hidden');
            }
        }, 300);
    });
    problemsInput.addEventListener('input', renderSelectedProblems);

    function toggleContestForm(show) {
        const modal = document.getElementById('contest-form-modal');
        modal.classList.toggle('hidden', !show);
        modal.classList.toggle('flex', show);
        document.body.classList.toggle('overflow-hidden', show);
        if (show) document.getElementById('contest-title').focus();
    }

    function localDateTimeValue(date) {
        const offset = date.getTimezoneOffset() * 60000;
        return new Date(date.getTime() - offset).toISOString().slice(0, 16);
    }

    function apiDateTimeValue(inputValue) {
        const value = String(inputValue || '').trim().replace('T', ' ');
        return value.length === 16 ? `${value}:00` : value;
    }

    const defaultStart = new Date();
    defaultStart.setMinutes(defaultStart.getMinutes() - (defaultStart.getMinutes() % 5), 0, 0);
    const defaultEnd = new Date(defaultStart.getTime() + (7 * 86400000));
    document.getElementById('contest-start').value = localDateTimeValue(defaultStart);
    document.getElementById('contest-end').value = localDateTimeValue(defaultEnd);

    document.getElementById('toggle-contest-form').addEventListener('click', () => toggleContestForm(true));
    document.getElementById('cancel-contest-form').addEventListener('click', () => toggleContestForm(false));
    document.getElementById('close-contest-form').addEventListener('click', () => toggleContestForm(false));
    document.getElementById('contest-form-modal').addEventListener('click', (event) => {
        if (event.target === event.currentTarget) toggleContestForm(false);
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            toggleContestForm(false);
            toggleMaterialForm(false);
        }
    });

    const materialModal = document.getElementById('material-form-modal');
    const materialForm = document.getElementById('create-material-form');
    const materialMessage = document.getElementById('material-form-message');
    let materialEditor = null;
    if (window.ClassicEditor) {
        window.ClassicEditor.create(document.getElementById('material-body'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo']
        }).then((editor) => { materialEditor = editor; }).catch(() => { materialEditor = null; });
    }
    function toggleMaterialForm(show) {
        materialModal.classList.toggle('hidden', !show);
        materialModal.classList.toggle('flex', show);
        document.body.classList.toggle('overflow-hidden', show);
        if (show) document.getElementById('material-title').focus();
    }
    document.getElementById('toggle-material-form').addEventListener('click', () => toggleMaterialForm(true));
    document.getElementById('close-material-form').addEventListener('click', () => toggleMaterialForm(false));
    document.getElementById('cancel-material-form').addEventListener('click', () => toggleMaterialForm(false));
    materialModal.addEventListener('click', (event) => { if (event.target === materialModal) toggleMaterialForm(false); });
    materialForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const button = materialForm.querySelector('button[type="submit"]');
        const contentBody = (materialEditor ? materialEditor.getData() : document.getElementById('material-body').value).trim();
        const contentUrl = document.getElementById('material-url').value.trim();
        materialMessage.classList.add('hidden');
        if (!contentBody && !contentUrl) {
            materialMessage.textContent = 'Agrega contenido o un enlace de apoyo.';
            materialMessage.className = 'rounded border border-red-200 bg-red-50 p-3 text-sm text-red-800';
            return;
        }
        button.disabled = true;
        button.textContent = 'Publicando...';
        try {
            await request(`${endpoint}/materials`, { method: 'POST', body: JSON.stringify({
                title: document.getElementById('material-title').value.trim(),
                description: document.getElementById('material-description').value.trim() || null,
                contentBody: contentBody || null,
                contentUrl: contentUrl || null,
                isPublished: true
            }) });
            materialForm.reset();
            if (materialEditor) materialEditor.setData('');
            toggleMaterialForm(false);
            render(await request(endpoint));
        } catch (error) {
            materialMessage.textContent = error.message;
            materialMessage.className = 'rounded border border-red-200 bg-red-50 p-3 text-sm text-red-800';
        } finally {
            button.disabled = false;
            button.textContent = 'Publicar material';
        }
    });
    contestForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const button = contestForm.querySelector('button[type="submit"]');
        const problemIds = parseProblemIds(problemsInput.value);
        const opensAt = new Date(document.getElementById('contest-start').value);
        const dueAt = new Date(document.getElementById('contest-end').value);
        contestMessage.classList.add('hidden');
        if (problemIds.length === 0 || Number.isNaN(opensAt.getTime()) || Number.isNaN(dueAt.getTime()) || dueAt <= opensAt) {
            contestMessage.textContent = dueAt <= opensAt ? 'La hora de fin debe ser posterior a la hora de inicio.' : 'Completa las fechas y agrega al menos un ID de problema válido.';
            contestMessage.className = 'rounded border border-red-200 bg-red-50 p-3 text-sm text-red-800';
            return;
        }
        button.disabled = true;
        const originalButtonText = button.textContent;
        button.textContent = 'Validando problemas...';
        try {
            const validation = await Promise.all(problemIds.map(async (id) => {
                try {
                    const problem = await request(`${apiBase}/problems/${id}`);
                    selectedProblemLabels.set(id, `${id} - ${problem.title || 'Problema'}`);
                    return null;
                } catch (_) {
                    return id;
                }
            }));
            const invalidIds = validation.filter(Boolean);
            if (invalidIds.length) throw new Error(`No se encontraron los problemas: ${invalidIds.join(', ')}.`);
            renderSelectedProblems();
            button.textContent = 'Creando contest...';
            await request(`${endpoint}/assignments`, {
                method: 'POST',
                body: JSON.stringify({
                    title: document.getElementById('contest-title').value.trim(),
                    description: document.getElementById('contest-description').value.trim() || null,
                    opensAt: apiDateTimeValue(document.getElementById('contest-start').value),
                    dueAt: apiDateTimeValue(document.getElementById('contest-end').value),
                    lateDueAt: null,
                    isActive: true,
                    problemIds
                })
            });
            contestForm.reset();
            selectedProblemLabels.clear();
            renderSelectedProblems();
            document.getElementById('contest-start').value = localDateTimeValue(defaultStart);
            document.getElementById('contest-end').value = localDateTimeValue(defaultEnd);
            toggleContestForm(false);
            render(await request(endpoint));
            management.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch (error) {
            contestMessage.textContent = error.message;
            contestMessage.className = 'rounded border border-red-200 bg-red-50 p-3 text-sm text-red-800';
        } finally {
            button.disabled = false;
            button.textContent = originalButtonText;
        }
    });

    request(endpoint)
        .then(render)
        .catch((error) => {
            const box = document.getElementById('course-error');
            box.textContent = error.message;
            box.classList.remove('hidden');
        })
        .finally(() => document.getElementById('course-loading').classList.add('hidden'));

    window.setInterval(updateCountdowns, 1000);
}());
