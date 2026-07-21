<?php
session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: ../Loginn.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PTC | Participants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="participants.css">
    <link rel="stylesheet" href="nav&footer/usernav.css">
    <link rel="stylesheet" href="nav&footer/footer.css">
</head>
<body>
    <?php include 'nav&footer/usernav.php'; ?>
    <main class="participants-page">
        <header class="participants-heading">
            <div class="participants-heading__title">
                <img src="picture1.png" alt="PTC Logo">
                <div><h1>Participants</h1><p>View and search registered PTC participants.</p></div>
            </div>
            <label class="participant-search" for="participantSearch">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <input type="search" id="participantSearch" placeholder="Search name, email, profession, or organization" autocomplete="off">
                <button type="button" id="clearParticipantSearch" aria-label="Clear search" hidden><i class="fa-solid fa-xmark"></i></button>
            </label>
        </header>

        <section class="participants-card" aria-label="Participant records" aria-live="polite">
            <div class="participants-loading" id="participantsLoading"><span class="spinner-border spinner-border-sm"></span> Loading participants…</div>
            <div class="table-responsive">
                <table class="participants-table">
                    <thead><tr><th>Registration ID</th><th>Full name</th><th>Date of birth</th><th>Email address</th><th>Profession / PRC License</th><th>Organization</th><th>Position</th></tr></thead>
                    <tbody id="participantsTableBody"></tbody>
                </table>
            </div>
            <div class="participants-empty" id="participantsEmpty" hidden><i class="fa-solid fa-user-slash"></i><strong>No participants found</strong><span>Try a different search term.</span></div>
            <div class="participants-error" id="participantsError" hidden><i class="fa-solid fa-triangle-exclamation"></i><strong>Unable to load participants</strong><span>Please refresh and try again.</span></div>
        </section>
        <div class="participants-pagination" id="participantsPagination"></div>
    </main>
    <?php include 'nav&footer/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function () {
        const search = document.getElementById('participantSearch');
        const clear = document.getElementById('clearParticipantSearch');
        const body = document.getElementById('participantsTableBody');
        const loading = document.getElementById('participantsLoading');
        const empty = document.getElementById('participantsEmpty');
        const error = document.getElementById('participantsError');
        const pagination = document.getElementById('participantsPagination');
        let page = 1;
        let pageSize = 10;
        let debounceTimer;
        let requestController;

        function cell(value, label, strong) {
            const td = document.createElement('td');
            td.dataset.label = label;
            const element = strong ? document.createElement('strong') : document.createTextNode(value || '—');
            if (strong) element.textContent = value || '—';
            td.appendChild(element);
            return td;
        }

        function renderRows(participants) {
            body.replaceChildren();
            participants.forEach(participant => {
                const row = document.createElement('tr');
                row.append(cell(participant.id, 'Registration ID', true));
                row.append(cell(participant.full_name, 'Full name'));
                row.append(cell(participant.birthdate, 'Date of birth'));
                row.append(cell(participant.email, 'Email address'));
                row.append(cell(participant.profession, 'Profession / PRC'));
                row.append(cell(participant.organization, 'Organization'));
                row.append(cell(participant.position, 'Position'));
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
                pageLinks += `<button type="button" class="${candidate === data.page ? 'active' : ''}" data-page="${candidate}">${candidate}</button>`;
            });
            pagination.innerHTML = `<div>Showing <strong>${data.first_record}–${data.last_record}</strong> of <strong>${data.total}</strong></div>
                <label>Rows per page <select aria-label="Rows per page"><option value="10">10</option><option value="25">25</option><option value="50">50</option></select></label>
                <div class="pagination-buttons"><button type="button" data-page="${data.page - 1}" ${data.page === 1 ? 'disabled' : ''}><i class="fa-solid fa-chevron-left"></i><span>Previous</span></button>${pageLinks}<button type="button" data-page="${data.page + 1}" ${data.page === data.total_pages ? 'disabled' : ''}><span>Next</span><i class="fa-solid fa-chevron-right"></i></button></div>`;
            pagination.querySelector('select').value = String(pageSize);
            pagination.querySelector('select').addEventListener('change', event => { pageSize = Number(event.target.value); page = 1; loadParticipants(); });
            pagination.querySelectorAll('[data-page]').forEach(button => button.addEventListener('click', () => { page = Number(button.dataset.page); loadParticipants(); }));
        }

        async function loadParticipants() {
            if (requestController) requestController.abort();
            const controller = new AbortController();
            requestController = controller;
            loading.hidden = false; empty.hidden = true; error.hidden = true;
            clear.hidden = search.value.length === 0;
            const params = new URLSearchParams({ q: search.value.trim(), page, per_page: pageSize });
            try {
                const response = await fetch(`participants_data.php?${params}`, { signal: controller.signal, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!response.ok) throw new Error('Request failed');
                const data = await response.json();
                page = data.page;
                renderRows(data.participants);
                renderPagination(data);
                empty.hidden = data.total !== 0;
                params.set('page', data.page);
                history.replaceState(null, '', `?${params}`);
            } catch (requestError) {
                if (requestError.name !== 'AbortError') { body.replaceChildren(); pagination.innerHTML = ''; error.hidden = false; }
            } finally {
                if (!controller.signal.aborted) loading.hidden = true;
            }
        }

        search.addEventListener('input', function () { clearTimeout(debounceTimer); page = 1; debounceTimer = setTimeout(loadParticipants, 300); });
        clear.addEventListener('click', function () { search.value = ''; search.focus(); page = 1; loadParticipants(); });
        const initial = new URLSearchParams(location.search);
        search.value = initial.get('q') || '';
        page = Math.max(1, Number(initial.get('page')) || 1);
        pageSize = [10, 25, 50].includes(Number(initial.get('per_page'))) ? Number(initial.get('per_page')) : 10;
        loadParticipants();
    })();
    </script>
</body>
</html>
