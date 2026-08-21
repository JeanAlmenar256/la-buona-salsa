/**
 * La Buona Salsa - Immersive Scroll & Interactive Experience
 */

document.addEventListener('DOMContentLoaded', () => {
    initNavbarScroll();
    initHeroParallax();
    initScrollShowcase();
    initCard3DTilt();
});

// 1. Navbar elevation on scroll
function initNavbarScroll() {
    const navbar = document.querySelector('.navbar-salsa');
    if (!navbar) return;

    window.addEventListener('scroll', () => {
        if (window.scrollY > 40) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
}

// 2. Parallax on Hero section with mouse movement
function initHeroParallax() {
    const heroSection = document.querySelector('.hero-section');
    const heroProduct = document.querySelector('.hero-product-img');
    const tags = document.querySelectorAll('.floating-card-tag');

    if (!heroSection || !heroProduct) return;

    heroSection.addEventListener('mousemove', (e) => {
        const rect = heroSection.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width - 0.5;
        const y = (e.clientY - rect.top) / rect.height - 0.5;

        heroProduct.style.transform = `translate(${x * 25}px, ${y * 25}px) rotate(${x * 6}deg)`;

        tags.forEach((tag, idx) => {
            const depth = (idx + 1) * 15;
            tag.style.transform = `translate(${x * -depth}px, ${y * -depth}px)`;
        });
    });

    heroSection.addEventListener('mouseleave', () => {
        heroProduct.style.transform = 'translate(0px, 0px) rotate(0deg)';
        tags.forEach((tag) => {
            tag.style.transform = 'translate(0px, 0px)';
        });
    });
}

// 3. Scroll-driven Product Showcase Animation
function initScrollShowcase() {
    const showcase = document.querySelector('.sticky-showcase-container');
    const animatedJar = document.querySelector('.scroll-animated-jar');
    const storyCards = document.querySelectorAll('.story-step-card');

    if (!showcase || !animatedJar) return;

    window.addEventListener('scroll', () => {
        const rect = showcase.getBoundingClientRect();
        const windowHeight = window.innerHeight;
        
        // Calculate scroll progress inside the showcase (0 to 1)
        const totalDistance = rect.height - windowHeight;
        let progress = -rect.top / totalDistance;
        progress = Math.max(0, Math.min(1, progress));

        // Dynamic transforms based on scroll
        const rotate = (progress * 50) - 15; // Rotates smoothly as user scrolls
        const scale = 0.95 + Math.sin(progress * Math.PI) * 0.2; // Pulsing scale
        const translateY = Math.sin(progress * Math.PI * 2) * 20;

        animatedJar.style.transform = `scale(${scale}) rotate(${rotate}deg) translateY(${translateY}px)`;

        // Highlight active story step card
        storyCards.forEach((card) => {
            const cardRect = card.getBoundingClientRect();
            if (cardRect.top < windowHeight * 0.65 && cardRect.bottom > windowHeight * 0.35) {
                card.classList.add('active');
            } else {
                card.classList.remove('active');
            }
        });
    });
}

// 4. 3D Tilt for Product Catalog Cards
function initCard3DTilt() {
    const cards = document.querySelectorAll('.product-tilt-card');

    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = ((y - centerY) / centerY) * -10;
            const rotateY = ((x - centerX) / centerX) * 10;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px)';
        });
    });
}

// 5. Gated purchase check
function handleComprar(event, targetUrl, isLoggedIn) {
    if (isLoggedIn) {
        window.location.href = targetUrl;
        return;
    }

    // If not logged in, prevent default and prompt registration
    if (event) event.preventDefault();

    // Try showing the register modal if available
    const modalEl = document.getElementById('registroModal');
    if (modalEl && typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    } else {
        // Fallback: highlight top register bar with shake animation
        const navForm = document.querySelector('.nav-quick-form') || document.getElementById('guia-nombre');
        if (navForm) {
            navForm.classList.add('shake-attention');
            const nameInput = document.getElementById('guia-nombre');
            if (nameInput) nameInput.focus();
            setTimeout(() => navForm.classList.remove('shake-attention'), 800);
        }
        alert('¡Para realizar tu compra, primero regístrate con tu nombre y correo en la barra superior!');
    }
}
