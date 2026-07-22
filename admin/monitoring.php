<?php
session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: ../Loginn.php');
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PTC | Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="monitoring.css">
    <link rel="stylesheet" href="nav&footer/usernav.css">
    <link rel="stylesheet" href="nav&footer/footer.css">
</head>
<body>
    <?php include 'nav&footer/usernav.php'; ?>

    <main class="monitoring-page">
        <header class="monitoring-heading">
            <div class="monitoring-heading__title">
                <span class="monitoring-heading__icon"><i class="fa-solid fa-chart-line"></i></span>
                <div>
                    <h1>Participant Monitoring</h1>
                    <p>Track attendance and feedback completion across every event session.</p>
                </div>
            </div>
            <div class="monitoring-heading__controls">
                <label class="monitoring-year" for="monitoringYear">
                    <span>Event year</span>
                    <select id="monitoringYear" aria-label="Filter sessions by event year" disabled></select>
                </label>
                <label class="monitoring-search" for="monitoringSearch">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <input type="search" id="monitoringSearch" placeholder="Search participant, profession, or organization" autocomplete="off">
                    <button type="button" id="clearMonitoringSearch" aria-label="Clear search" hidden><i class="fa-solid fa-xmark"></i></button>
                </label>
            </div>
        </header>

        <div class="monitoring-toolbar">
            <div class="monitoring-legend" aria-label="Status legend">
                <span><i class="status-dot status-dot--complete"></i>Attendance and feedback</span>
                <span><i class="status-dot status-dot--partial"></i>Partially complete</span>
                <span><i class="status-dot status-dot--empty"></i>Not completed</span>
            </div>
            <span class="monitoring-count" id="monitoringCount" aria-live="polite"></span>
        </div>

        <section class="monitoring-card" aria-label="Participant monitoring records" aria-live="polite">
            <div class="monitoring-loading" id="monitoringLoading"><span class="spinner-border spinner-border-sm"></span>Loading monitoring records&hellip;</div>
            <div class="monitoring-table-wrap">
                <table class="monitoring-table">
                    <thead id="monitoringTableHead"></thead>
                    <tbody id="monitoringTableBody"></tbody>
                </table>
            </div>
            <div class="monitoring-empty" id="monitoringEmpty" hidden>
                <i class="fa-solid fa-magnifying-glass"></i><strong>No matching participants</strong><span>Try a different search term.</span>
            </div>
            <div class="monitoring-error" id="monitoringError" hidden>
                <i class="fa-solid fa-triangle-exclamation"></i><strong>Unable to load monitoring records</strong><span>Please refresh and try again.</span>
            </div>
        </section>
        <div class="monitoring-pagination" id="monitoringPagination"></div>
    </main>

    <?php include 'nav&footer/footer.php'; ?>
    <script>
    (function () {
        const search = document.getElementById('monitoringSearch');
        const yearSelect = document.getElementById('monitoringYear');
        const clear = document.getElementById('clearMonitoringSearch');
        const head = document.getElementById('monitoringTableHead');
        const body = document.getElementById('monitoringTableBody');
        const loading = document.getElementById('monitoringLoading');
        const empty = document.getElementById('monitoringEmpty');
        const error = document.getElementById('monitoringError');
        const count = document.getElementById('monitoringCount');
        const pagination = document.getElementById('monitoringPagination');
        let page = 1;
        let pageSize = 10;
        let selectedYear = '';
        let debounceTimer;
        let requestController;

        function text(value, fallback = '—') { return value ? String(value) : fallback; }

        function renderYearFilter(years, activeYear) {
            const signature = years.join(',');
            if (yearSelect.dataset.years !== signature) {
                yearSelect.replaceChildren(...years.map(year => {
                    const option = document.createElement('option');
                    option.value = String(year);
                    option.textContent = String(year);
                    return option;
                }));
                yearSelect.dataset.years = signature;
            }
            selectedYear = String(activeYear);
            yearSelect.value = selectedYear;
            yearSelect.disabled = years.length < 2;
        }

        function renderHeader(sessions) {
            const row = document.createElement('tr');
            ['Participant', 'Gender', 'Profession', 'Position', 'Institution', 'Country'].forEach(label => {
                const th = document.createElement('th');
                th.textContent = label;
                if (label === 'Participant') th.className = 'sticky-column';
                row.appendChild(th);
            });
            sessions.forEach(session => {
                const th = document.createElement('th');
                th.className = 'session-column';
                th.title = session.title;
                const number = document.createElement('span');
                number.textContent = `Session ${session.number}`;
                const title = document.createElement('small');
                title.textContent = session.title;
                th.append(number, title);
                row.appendChild(th);
            });
            const action = document.createElement('th');
            action.textContent = 'Actions';
            row.appendChild(action);
            head.replaceChildren(row);
        }

        function statusCell(status, sessionTitle) {
            const td = document.createElement('td');
            td.className = 'session-status-cell';
            const badge = document.createElement('span');
            const complete = status.attendance && status.feedback;
            const partial = status.attendance || status.feedback;
            badge.className = `session-status ${complete ? 'is-complete' : partial ? 'is-partial' : 'is-empty'}`;
            badge.title = `${sessionTitle}: Attendance ${status.attendance ? 'complete' : 'missing'}; feedback ${status.feedback ? 'complete' : 'missing'}`;
            badge.setAttribute('aria-label', badge.title);
            badge.innerHTML = `<i class="fa-solid ${complete ? 'fa-check' : partial ? 'fa-minus' : 'fa-xmark'}"></i><span>${complete ? 'Complete' : partial ? (status.attendance ? 'Attendance only' : 'Feedback only') : 'Missing'}</span>`;
            td.appendChild(badge);
            return td;
        }

        function renderRows(participants, sessions) {
            body.replaceChildren();
            participants.forEach(participant => {
                const row = document.createElement('tr');
                const name = document.createElement('td');
                name.className = 'participant-cell sticky-column';
                name.innerHTML = `<strong></strong><small></small>`;
                name.querySelector('strong').textContent = text(participant.full_name);
                name.querySelector('small').textContent = `ID ${participant.id}`;
                row.appendChild(name);
                [participant.gender, participant.profession, participant.position, participant.organization, participant.country].forEach(value => {
                    const td = document.createElement('td');
                    td.textContent = text(value);
                    row.appendChild(td);
                });
                sessions.forEach(session => row.appendChild(statusCell(participant.sessions[session.id] || { attendance: false, feedback: false }, session.title)));
                const action = document.createElement('td');
                const link = document.createElement('a');
                link.className = 'monitoring-view-button';
                link.href = `participant_profile.php?id=${encodeURIComponent(participant.id)}`;
                link.innerHTML = '<i class="fa-regular fa-eye"></i><span>View</span>';
                action.appendChild(link);
                row.appendChild(action);
                body.appendChild(row);
            });
        }

        function renderPagination(data) {
            if (!data.total) { pagination.innerHTML = ''; return; }
            const pages = [];
            for (let candidate = 1; candidate <= data.total_pages; candidate++) {
                if (candidate === 1 || candidate === data.total_pages || Math.abs(candidate - data.page) <= 1) pages.push(candidate);
            }
            let pageLinks = '';
            pages.forEach((candidate, index) => {
                if (index && candidate - pages[index - 1] > 1) pageLinks += '<span class="pagination-gap">…</span>';
                pageLinks += `<button type="button" class="${candidate === data.page ? 'active' : ''}" data-page="${candidate}" aria-label="Page ${candidate}" ${candidate === data.page ? 'aria-current="page"' : ''}>${candidate}</button>`;
            });
            pagination.innerHTML = `<div>Showing <strong>${data.first_record}–${data.last_record}</strong> of <strong>${data.total}</strong></div>
                <label>Rows per page <select aria-label="Rows per page"><option value="10">10</option><option value="25">25</option><option value="50">50</option></select></label>
                <div class="pagination-buttons"><button type="button" data-page="${data.page - 1}" ${data.page === 1 ? 'disabled' : ''}><i class="fa-solid fa-chevron-left"></i><span>Previous</span></button>${pageLinks}<button type="button" data-page="${data.page + 1}" ${data.page === data.total_pages ? 'disabled' : ''}><span>Next</span><i class="fa-solid fa-chevron-right"></i></button></div>`;
            pagination.querySelector('select').value = String(pageSize);
            pagination.querySelector('select').addEventListener('change', event => { pageSize = Number(event.target.value); page = 1; loadRecords(); });
            pagination.querySelectorAll('[data-page]').forEach(button => button.addEventListener('click', () => { page = Number(button.dataset.page); loadRecords(); }));
        }

        async function loadRecords() {
            if (requestController) requestController.abort();
            const controller = new AbortController();
            requestController = controller;
            loading.hidden = false; empty.hidden = true; error.hidden = true;
            clear.hidden = search.value.length === 0;
            const params = new URLSearchParams({ q: search.value.trim(), page, per_page: pageSize });
            if (selectedYear) params.set('year', selectedYear);
            try {
                const response = await fetch(`monitoring_data.php?${params}`, { signal: controller.signal, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!response.ok) throw new Error('Request failed');
                const data = await response.json();
                page = data.page;
                renderYearFilter(data.available_years, data.selected_year);
                renderHeader(data.sessions);
                renderRows(data.participants, data.sessions);
                renderPagination(data);
                empty.hidden = data.total !== 0;
                count.textContent = `${data.total} participant${data.total === 1 ? '' : 's'}`;
                params.set('page', data.page);
                history.replaceState(null, '', `?${params}`);
            } catch (requestError) {
                if (requestError.name !== 'AbortError') { head.replaceChildren(); body.replaceChildren(); pagination.innerHTML = ''; count.textContent = ''; error.hidden = false; }
            } finally {
                if (!controller.signal.aborted) loading.hidden = true;
            }
        }

        search.addEventListener('input', () => { clearTimeout(debounceTimer); page = 1; debounceTimer = setTimeout(loadRecords, 300); });
        clear.addEventListener('click', () => { search.value = ''; search.focus(); page = 1; loadRecords(); });
        yearSelect.addEventListener('change', () => { selectedYear = yearSelect.value; page = 1; loadRecords(); });
        const initial = new URLSearchParams(location.search);
        search.value = initial.get('q') || '';
        selectedYear = initial.get('year') || '';
        page = Math.max(1, Number(initial.get('page')) || 1);
        pageSize = [10, 25, 50].includes(Number(initial.get('per_page'))) ? Number(initial.get('per_page')) : 10;
        loadRecords();
    })();
    </script>
</body>
</html>
