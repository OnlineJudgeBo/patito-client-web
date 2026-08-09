(function () {
    'use strict';

    const config = window.PATITO_COURSES_CONFIG || {};
    const baseUrl = String(config.apiUrl || '/api').replace(/\/+$/, '');
    const siteId = Number(config.siteId) || 1;
    const endpoint = `${baseUrl}/academic/sites/${siteId}/courses`;

    const list = document.getElementById('courses-list');
    const listWrapper = document.getElementById('courses-list-wrapper');
    const loading = document.getElementById('courses-loading');
    const empty = document.getElementById('courses-empty');
    const message = document.getElementById('course-message');
    const detail = document.getElementById('course-detail');
    const detailLoading = document.getElementById('course-detail-loading');
    const assignmentsList = document.getElementById('course-assignments');
    const assignmentsEmpty = document.getElementById('course-assignments-empty');

    function cookie(name) {
        const item = document.cookie.split('; ').find((value) => value.startsWith(`${name}=`));
        return item ? decodeURIComponent(item.slice(name.length + 1)) : '';
    }

    async function request(path, options) {
        const token = cookie('accessToken') || localStorage.getItem('accessToken') || '';
        const response = await fetch(path, {
            ...options,
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                ...(token ? { Authorization: `Bearer ${token}` } : {}),
                ...(options && options.headers ? options.headers : {})
            }
        });

        if (!response.ok) {
            let detail = '';
            try {
                const body = await response.json();
                detail = body.message || body.title || body.detail || '';
                if (!detail && body.errors) detail = Object.values(body.errors).flat().join(' ');
            } catch (_) {
                detail = '';
            }
            throw new Error(detail || (response.status === 401
                ? 'Tu sesión venció. Inicia sesión nuevamente.'
                : 'No se pudo completar la operación.'));
        }

        return response.status === 204 ? null : response.json();
    }

    function showMessage(text, type) {
        message.textContent = text;
        message.className = `rounded-lg border p-4 text-sm ${type === 'error'
            ? 'border-red-200 bg-red-50 text-red-800'
            : 'border-green-200 bg-green-50 text-green-800'}`;
    }

    function element(tag, className, text) {
        const node = document.createElement(tag);
        if (className) node.className = className;
        if (text !== undefined) node.textContent = text;
        return node;
    }

    function formatDate(value) {
        const date = new Date(value);
        return Number.isNaN(date.getTime())
            ? 'Sin fecha'
            : date.toLocaleString('es-BO', { dateStyle: 'medium', timeStyle: 'short' });
    }

    function renderCourseDetail(course) {
        document.getElementById('course-detail-name').textContent = course.name || `Curso #${course.courseId}`;
        document.getElementById('course-detail-description').textContent = course.description || 'Sin descripción.';
        document.getElementById('course-detail-role').textContent = course.memberRole === 'student' ? 'Estudiante' : (course.memberRole || 'Miembro');
        assignmentsList.replaceChildren();

        const assignments = Array.isArray(course.assignments) ? course.assignments : [];
        assignmentsEmpty.classList.toggle('hidden', assignments.length !== 0);
        assignmentsList.classList.toggle('hidden', assignments.length === 0);

        assignments.forEach((assignment) => {
            const card = element('article', 'oj-card p-5');
            const header = element('div', 'flex flex-wrap items-start justify-between gap-3');
            const heading = element('div');
            heading.append(
                element('h3', 'text-lg font-bold text-slate-900', assignment.title || `Tarea #${assignment.assignmentId}`),
                element('p', 'mt-1 text-sm text-gray-600', assignment.description || 'Sin descripción.')
            );
            const status = element('span', 'rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-800', assignment.statusLabel || 'Tarea');
            header.append(heading, status);
            card.append(header);

            const dates = element('div', 'mt-4 grid gap-2 text-sm text-gray-600 sm:grid-cols-2');
            dates.append(
                element('p', 'rounded-lg bg-slate-50 p-3', `Disponible: ${formatDate(assignment.opensAt)}`),
                element('p', 'rounded-lg bg-slate-50 p-3', `Entrega: ${formatDate(assignment.dueAt)}`)
            );
            card.append(dates);

            const progress = element('p', 'mt-4 text-sm font-semibold text-slate-700', `${Number(assignment.solvedByCurrentUser || 0)} de ${Number(assignment.problemCount || 0)} problemas resueltos · ${Number(assignment.attemptsByCurrentUser || 0)} intentos`);
            card.append(progress);

            const problems = (assignment.problems || []).filter((problem) => problem.isVisible !== false);
            const problemList = element('div', 'mt-4 divide-y rounded-lg border');
            if (problems.length === 0) {
                problemList.append(element('p', 'p-4 text-sm text-gray-500', 'Los problemas aún no están disponibles.'));
            } else {
                problems.forEach((problem) => {
                    const row = element('div', 'flex flex-wrap items-center justify-between gap-3 p-4');
                    const label = element('div');
                    label.append(
                        element('p', 'font-semibold text-slate-900', `#${problem.problemId} · ${problem.title || 'Problema'}`),
                        element('p', 'text-xs text-gray-500', `${Number(problem.points || 0)} puntos · ${Number(problem.attemptsByCurrentUser || 0)} intentos`)
                    );
                    const solve = element('a', `rounded-lg px-4 py-2 text-sm font-semibold ${problem.isSolvedByCurrentUser ? 'bg-green-100 text-green-800' : 'bg-blue-700 text-white hover:bg-blue-600'}`, problem.isSolvedByCurrentUser ? 'Resuelto · Ver' : 'Resolver');
                    solve.href = `problem.php?id=${encodeURIComponent(problem.problemId)}&courseId=${encodeURIComponent(course.courseId)}&assignmentId=${encodeURIComponent(assignment.assignmentId)}`;
                    row.append(label, solve);
                    problemList.append(row);
                });
            }
            card.append(problemList);
            assignmentsList.append(card);
        });
    }

    async function openCourse(courseId) {
        detail.classList.remove('hidden');
        detailLoading.classList.remove('hidden');
        assignmentsList.classList.add('hidden');
        assignmentsEmpty.classList.add('hidden');
        detail.scrollIntoView({ behavior: 'smooth', block: 'start' });
        try {
            const course = await request(`${endpoint}/${encodeURIComponent(courseId)}`, { method: 'GET' });
            renderCourseDetail(course);
            const url = new URL(window.location.href);
            url.searchParams.set('course', courseId);
            history.replaceState(null, '', url);
        } catch (error) {
            showMessage(error.message, 'error');
            detail.classList.add('hidden');
        } finally {
            detailLoading.classList.add('hidden');
        }
    }

    function renderCourses(courses, targets) {
        const targetList = targets?.list || list;
        const targetEmpty = targets?.empty || empty;
        const targetWrapper = targets?.wrapper || listWrapper;
        targetList.replaceChildren();
        const values = Array.isArray(courses) ? courses : [];
        targetEmpty.classList.toggle('hidden', values.length !== 0);
        targetWrapper.classList.toggle('hidden', values.length === 0);

        values.forEach((course) => {
            const courseUrl = `course.php?id=${encodeURIComponent(course.courseId)}`;
            const row = element('tr', 'cursor-pointer focus-visible:outline focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-blue-600');
            row.tabIndex = 0;
            row.setAttribute('role', 'link');
            row.setAttribute('aria-label', `Ingresar al curso ${course.name || course.courseId}`);
            row.addEventListener('click', () => { window.location.href = courseUrl; });
            row.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    window.location.href = courseUrl;
                }
            });
            const courseCell = element('td');
            const roleLabels = { admin: 'Administrador', teacher: 'Docente', assistant: 'Auxiliar', student: 'Estudiante' };
            courseCell.append(
                element('span', 'oj-table-link', course.name || `Curso #${course.courseId}`),
                element('p', 'mt-1 text-xs text-gray-500', roleLabels[String(course.role || '').toLowerCase()] || `Curso #${course.courseId}`)
            );
            row.append(
                courseCell,
                element('td', 'text-gray-700', course.ownerUserId || 'Sin definir'),
                element('td', 'text-center', String(Number(course.assignmentCount || 0)))
            );
            targetList.append(row);
        });
    }

    function applyCoursesFraming(manages) {
        document.getElementById('courses-page-title').textContent = manages ? 'Cursos' : 'Mis cursos';
        document.getElementById('courses-page-description').textContent = manages
            ? 'Administra los cursos académicos y consulta aquellos en los que estás inscrito.'
            : 'Consulta los contests y problemas asignados por tus docentes.';
        document.getElementById('courses-section-title').textContent = manages ? 'Cursos que administras' : 'Cursos inscritos';
        document.getElementById('courses-empty-title').textContent = manages ? 'No administras ningún curso' : 'No estás inscrito en ningún curso';
        document.getElementById('courses-empty-description').textContent = manages ? 'Crea un curso para comenzar.' : 'Usa el código de invitación para unirte.';
        document.getElementById('enrolled-courses-section').classList.toggle('hidden', !manages);
    }

    async function loadCourses() {
        loading.classList.remove('hidden');
        empty.classList.add('hidden');
        listWrapper.classList.add('hidden');
        try {
            const [manageableResult, enrolled] = await Promise.all([
                request(`${endpoint}/manageable`, { method: 'GET' }).catch(() => []),
                request(`${endpoint}/mine`, { method: 'GET' })
            ]);
            const manageable = Array.isArray(manageableResult) ? manageableResult : [];
            const manages = Boolean(config.canCreate) || manageable.length > 0;
            applyCoursesFraming(manages);

            if (manages) {
                renderCourses(manageable);
                const managedIds = new Set(manageable.map((course) => Number(course.courseId)));
                const enrolledOnly = (Array.isArray(enrolled) ? enrolled : []).filter((course) => !managedIds.has(Number(course.courseId)));
                renderCourses(enrolledOnly, {
                    list: document.getElementById('enrolled-courses-list'),
                    empty: document.getElementById('enrolled-courses-empty'),
                    wrapper: document.getElementById('enrolled-courses-wrapper')
                });
                document.getElementById('enrolled-courses-loading').classList.add('hidden');
            } else {
                renderCourses(enrolled);
            }
        } catch (error) {
            renderCourses([]);
            showMessage(error.message, 'error');
        } finally {
            loading.classList.add('hidden');
        }
    }

    async function submit(form, action, successText, onSuccess) {
        const button = form.querySelector('button[type="submit"]');
        button.disabled = true;
        try {
            const result = await action();
            form.reset();
            showMessage(typeof successText === 'function' ? successText(result) : successText, 'success');
            await loadCourses();
            if (onSuccess) onSuccess(result);
        } catch (error) {
            showMessage(error.message, 'error');
        } finally {
            button.disabled = false;
        }
    }

    document.getElementById('refresh-courses').addEventListener('click', loadCourses);
    document.getElementById('close-course-detail').addEventListener('click', function () {
        detail.classList.add('hidden');
        const url = new URL(window.location.href);
        url.searchParams.delete('course');
        history.replaceState(null, '', url);
        document.getElementById('courses-list').scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
    document.getElementById('join-course-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const inviteCode = document.getElementById('invite-code').value.trim().toUpperCase();
        submit(event.currentTarget, () => request(`${endpoint}/join`, {
            method: 'POST',
            body: JSON.stringify({ inviteCode })
        }), 'Te uniste al curso correctamente.');
    });

    const createForm = document.getElementById('create-course-form');
    if (createForm) {
        const modal = document.getElementById('course-form-modal');
        const toggleCourseModal = (show) => {
            modal.classList.toggle('hidden', !show);
            modal.classList.toggle('flex', show);
            document.body.classList.toggle('overflow-hidden', show);
            if (show) document.getElementById('course-name').focus();
        };
        document.getElementById('open-course-form').addEventListener('click', () => toggleCourseModal(true));
        document.getElementById('close-course-form').addEventListener('click', () => toggleCourseModal(false));
        document.getElementById('cancel-course-form').addEventListener('click', () => toggleCourseModal(false));
        modal.addEventListener('click', (event) => { if (event.target === modal) toggleCourseModal(false); });
        createForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const name = document.getElementById('course-name').value.trim();
            const description = document.getElementById('course-description').value.trim();
            submit(event.currentTarget, () => request(endpoint, {
                method: 'POST',
                body: JSON.stringify({ name, ...(description ? { description } : {}) })
            }), (course) => `Curso creado correctamente. Código de invitación: ${course.inviteCode}`, () => toggleCourseModal(false));
        });
    }

    loadCourses().then(() => {
        const params = new URLSearchParams(window.location.search);
        const courseId = params.get('course');
        if (courseId && /^\d+$/.test(courseId)) openCourse(courseId);

        const inviteCode = params.get('invite');
        if (inviteCode) {
            const url = new URL(window.location.href);
            url.searchParams.delete('invite');
            history.replaceState(null, '', url);
            document.getElementById('invite-code').value = inviteCode.trim().toUpperCase();
            document.getElementById('join-course-form').requestSubmit();
        }
    });
}());
