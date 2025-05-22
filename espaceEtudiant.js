document.addEventListener('DOMContentLoaded', () => {
    const demandeForm = document.getElementById('demandeForm');

    if (demandeForm) {
        demandeForm.addEventListener('submit', (e) => {
            const type = document.getElementById('type').value;
            const message = document.getElementById('message').value.trim();

            if (!type || !message) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs.');
            }
        });
    }
});