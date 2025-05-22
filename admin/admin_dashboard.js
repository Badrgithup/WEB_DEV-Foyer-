document.addEventListener('DOMContentLoaded', () => {
    // Navigation
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.content-section');

    function showSection(sectionId) {
        sections.forEach(section => {
            section.classList.remove('active');
            if (section.id === sectionId) {
                section.classList.add('active');
            }
        });
        navLinks.forEach(nav => nav.classList.remove('active'));
        document.querySelector(`[data-section="${sectionId.replace('-section', '')}"]`).classList.add('active');
    }

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const sectionId = link.getAttribute('data-section') + '-section';
            showSection(sectionId);
        });
    });

    showSection('dashboard-section');

    const addResidentBtn = document.getElementById('add-resident-btn');
    const addStudentForm = document.getElementById('add-student-form');
    const cancelStudentBtn = document.getElementById('cancel-student');

    addResidentBtn.addEventListener('click', () => {
        addStudentForm.style.display = 'block';
    });

    cancelStudentBtn.addEventListener('click', () => {
        addStudentForm.style.display = 'none';
        addStudentForm.reset();
    });

    document.querySelectorAll('.edit-resident').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const form = document.getElementById(`edit-student-form-${id}`);
            form.style.display = form.style.display === 'none' ? 'table-row' : 'none';
        });
    });

    document.querySelectorAll('.cancel-edit-student').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            document.getElementById(`edit-student-form-${id}`).style.display = 'none';
        });
    });

    const addPaymentBtn = document.getElementById('add-payment-btn');
    const addPaymentForm = document.getElementById('add-payment-form');
    const cancelPaymentBtn = document.getElementById('cancel-payment');

    addPaymentBtn.addEventListener('click', () => {
        addPaymentForm.style.display = 'block';
    });

    cancelPaymentBtn.addEventListener('click', () => {
        addPaymentForm.style.display = 'none';
        addPaymentForm.reset();
    });

    document.querySelectorAll('.edit-payment').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const form = document.getElementById(`edit-payment-form-${id}`);
            form.style.display = form.style.display === 'none' ? 'table-row' : 'none';
        });
    });

    document.querySelectorAll('.cancel-edit-payment').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            document.getElementById(`edit-payment-form-${id}`).style.display = 'none';
        });
    });

    const newAnnouncementBtn = document.getElementById('new-announcement-btn');
    const addAnnouncementForm = document.getElementById('add-announcement-form');
    const cancelAnnouncementBtn = document.getElementById('cancel-announcement');

    newAnnouncementBtn.addEventListener('click', () => {
        addAnnouncementForm.style.display = 'block';
    });

    cancelAnnouncementBtn.addEventListener('click', () => {
        addAnnouncementForm.style.display = 'none';
        addAnnouncementForm.reset();
    });

    document.querySelectorAll('.edit-announcement').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const form = document.getElementById(`edit-announcement-form-${id}`);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });
    });

    document.querySelectorAll('.cancel-edit-announcement').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            document.getElementById(`edit-announcement-form-${id}`).style.display = 'none';
        });
    });

    document.querySelectorAll('.edit-request').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const id = button.getAttribute('data-id');
            const form = document.getElementById(`edit-request-form-${id}`);
            console.log(`Toggling form for request ID: ${id}`);
            form.style.display = form.style.display === 'none' ? 'table-row' : 'none';
        });
    });

    document.querySelectorAll('.cancel-edit-request').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const form = document.getElementById(`edit-request-form-${id}`);
            console.log(`Cancel clicked for request ID: ${id}`);
            form.style.display = 'none';
        });
    });

    document.querySelectorAll('.request-status-form').forEach(form => {
        form.addEventListener('submit', (e) => {
            console.log('Form submitted:', new FormData(form));
        });
    });

    const residentSearch = document.getElementById('resident-search');
    const residentsTable = document.getElementById('residents-table');
    residentSearch.addEventListener('input', () => {
        const filter = residentSearch.value.toLowerCase();
        const rows = residentsTable.querySelectorAll('tbody tr:not([id*="edit-student-form"])');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const text = Array.from(cells).slice(1, 4).map(cell => cell.textContent.toLowerCase()).join(' ');
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    const paymentSearch = document.getElementById('payment-search');
    const paymentsTable = document.getElementById('payments-table');
    paymentSearch.addEventListener('input', () => {
        const filter = paymentSearch.value.toLowerCase();
        const rows = paymentsTable.querySelectorAll('tbody tr:not([id*="edit-payment-form"])');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const text = Array.from(cells).slice(1, 5).map(cell => cell.textContent.toLowerCase()).join(' ');
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    const requestSearch = document.getElementById('request-search');
    const requestsTable = document.getElementById('requests-table');
    requestSearch.addEventListener('input', () => {
        const filter = requestSearch.value.toLowerCase();
        const rows = requestsTable.querySelectorAll('tbody tr:not([id*="edit-request-form"])');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const text = Array.from(cells).slice(1, 5).map(cell => cell.textContent.toLowerCase()).join(' ');
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    const paymentFilter = document.getElementById('payment-filter');
    paymentFilter.addEventListener('change', () => {
        const filter = paymentFilter.value.toLowerCase();
        const rows = paymentsTable.querySelectorAll('tbody tr:not([id*="edit-payment-form"])');
        rows.forEach(row => {
            const status = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
            row.style.display = (filter === 'all' || status === filter) ? '' : 'none';
        });
    });
});