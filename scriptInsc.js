document.addEventListener('DOMContentLoaded', () => {
    const about = document.querySelector('.about');
    const btns = document.querySelectorAll('.tab-btn');
    const articles = document.querySelectorAll('.content');

    about.addEventListener('click', (e) => {
        const id = e.target.dataset.id;
        if (id) {
            btns.forEach((btn) => {
                btn.classList.remove('active');
            });
            e.target.classList.add('active');

            articles.forEach((article) => {
                article.classList.remove('active');
            });
            const element = document.getElementById(id);
            element.classList.add('active');
        }
    });

    const loginForm = document.querySelector('#connexion form');
    const registerForm = document.querySelector('#inscription form');

    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            const cin = loginForm.querySelector('#cin').value.trim();
            const password = loginForm.querySelector('#password').value;
            if (!cin || !password) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs.');
            }
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            const nom = registerForm.querySelector('#nom').value.trim();
            const prenom = registerForm.querySelector('#prenom').value.trim();
            const cin = registerForm.querySelector('#cin').value.trim();
            const contact = registerForm.querySelector('#contact').value.trim();
            const email = registerForm.querySelector('#email').value.trim();
            const password = registerForm.querySelector('#password').value;
            const confirmPassword = registerForm.querySelector('#confirm_password').value;
            const dateNaissance = registerForm.querySelector('#date_naissance').value;

            const cinRegex = /^\d{8}$/;
            if (!cinRegex.test(cin)) {
                e.preventDefault();
                alert('Le numéro de CIN doit comporter exactement 8 chiffres.');
                return;
            }

            const contactRegex = /^\d{8}$/;
            if (!contactRegex.test(contact)) {
                e.preventDefault();
                alert('Le numéro de téléphone doit comporter exactement 8 chiffres.');
                return;
            }

            const today = new Date('2025-05-07');
            const birthDate = new Date(dateNaissance);
            const age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            const dayDiff = today.getDate() - birthDate.getDate();

            let adjustedAge = age;
            if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
                adjustedAge--;
            }

            if (adjustedAge < 17) {
                e.preventDefault();
                alert('Vous devez avoir au moins 17 ans pour vous inscrire.');
                return;
            }

            if (!nom || !prenom || !cin || !contact || !email || !password || !confirmPassword || !dateNaissance) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs.');
            } else if (password !== confirmPassword) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas.');
            }
        });
    }
});