document.addEventListener('DOMContentLoaded', function() {
    // 1. Mettre le nom en majuscules après la saisie
    const nomInput = document.getElementById('nom');
    if (nomInput) {
        nomInput.addEventListener('blur', function() {
            this.value = this.value.toUpperCase();
        });
    }

    // 2. Validation de l'email
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', validateEmail);
    }

    function validateEmail() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(this.value)) {
            alert('Veuillez entrer une adresse email valide');
            this.focus();
            return false;
        }
        return true;
    }

    // 3. Validation du fichier CV
    const cvInput = document.getElementById('cv');
    if (cvInput) {
        cvInput.addEventListener('change', validateCV);
    }

    function validateCV() {
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text', 'application/rtf', 'image/jpeg', 'image/png'];
        const maxSize = 2 * 1024 * 1024; // 2 Mo
        
        if (this.files.length > 0) {
            const file = this.files[0];
            
            // Vérification du type
            if (!allowedTypes.includes(file.type)) {
                alert('Format de fichier non autorisé. Formats acceptés: PDF, DOC, DOCX, ODT, RTF, JPG, PNG');
                this.value = '';
                return false;
            }
            
            // Vérification de la taille
            if (file.size > maxSize) {
                alert('Le fichier est trop volumineux (max 2 Mo)');
                this.value = '';
                return false;
            }
        }
        return true;
    }

    // 4. Validation du formulaire avant envoi
    const applicationForm = document.querySelector('.application-form');
    if (applicationForm) {
        applicationForm.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });
    }

    function validateForm() {
        let isValid = true;
        const requiredFields = ['nom', 'prenom', 'email', 'message'];
        
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !field.value.trim()) {
                alert(`Le champ ${fieldId.toUpperCase()} est obligatoire`);
                field.focus();
                isValid = false;
                return;
            }
        });

        return isValid && validateEmail.call(emailInput) && (cvInput.files.length === 0 || validateCV.call(cvInput));
    }

    // 5. Bouton retour en haut de page
    const backToTopBtn = document.createElement('button');
    backToTopBtn.innerHTML = '↑';
    backToTopBtn.className = 'back-to-top';
    backToTopBtn.style.display = 'none';
    backToTopBtn.style.position = 'fixed';
    backToTopBtn.style.bottom = '20px';
    backToTopBtn.style.right = '20px';
    backToTopBtn.style.zIndex = '99';
    backToTopBtn.style.border = 'none';
    backToTopBtn.style.outline = 'none';
    backToTopBtn.style.backgroundColor = 'gray';
    backToTopBtn.style.color = 'white';
    backToTopBtn.style.cursor = 'pointer';
    backToTopBtn.style.padding = '15px';
    backToTopBtn.style.borderRadius = '50%';
    backToTopBtn.style.fontSize = '18px';
    
    document.body.appendChild(backToTopBtn);

    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({top: 0, behavior: 'smooth'});
    });

    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.style.display = 'block';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });

    // 6. Menu burger pour mobile
    const burgerMenu = document.createElement('div');
    burgerMenu.className = 'burger-menu';
    burgerMenu.innerHTML = '☰';
    burgerMenu.style.display = 'none';
    burgerMenu.style.cursor = 'pointer';
    burgerMenu.style.fontSize = '24px';
    burgerMenu.style.padding = '10px';
    
    const header = document.querySelector('header');
    if (header) {
        header.insertBefore(burgerMenu, header.firstChild);
    }

    const mainNav = document.querySelector('.main-nav');
    
    function toggleMenu() {
        if (mainNav.style.display === 'block') {
            mainNav.style.display = 'none';
        } else {
            mainNav.style.display = 'block';
        }
    }

    burgerMenu.addEventListener('click', toggleMenu);

    function handleResize() {
        if (window.innerWidth <= 768) {
            burgerMenu.style.display = 'block';
            mainNav.style.display = 'none';
        } else {
            burgerMenu.style.display = 'none';
            mainNav.style.display = 'flex'; // ou 'block' selon votre CSS
        }
    }

    window.addEventListener('resize', handleResize);
    handleResize(); // Initial call
});