<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>La Buona Salsa - Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-logo { max-width: 300px; margin: 60px auto; display: block; }
        .flyer-img { cursor: pointer; transition: 0.3s; width: 100%; max-width: 300px; border-radius: 10px; }
        .flyer-img:hover { transform: scale(1.05); }
        .footer-socials { background: #f8f9fa; padding: 20px 0; margin-top: 50px; }
        /* Estilo de los globos de cómic */
.comic-bubble {
    position: absolute;
    background: #fff7d6; /* Amarillo crema tipo papel antiguo */
    border: 3px solid #000;
    padding: 15px;
    border-radius: 20px;
    font-family: 'chaotic-neutral', 'Helvetica', sans-serif;
    font-weight: bold;
    color: #000;
    z-index: 2000;
    box-shadow: 8px 8px 0px rgba(0,0,0,0.2);
    display: none; /* Oculto por defecto */
    max-width: 250px;
}

/* La "colita" del globo */
.comic-bubble::after {
    content: '';
    position: absolute;
    border-left: 15px solid transparent;
    border-right: 15px solid transparent;
    border-bottom: 20px solid #000;
    top: -22px;
    left: 20px;
}

/* Animación de flotado */
@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}
.floating { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #9B1212;">
    <div class="container">
        <span class="navbar-brand">La Buona Salsa</span>
        
        <button id="btn-hamburguesa" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto">
                <form class="d-flex" action="<?= base_url('registrar-basico') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input id="guia-nombre" class="form-control me-2" type="text" name="nombre" placeholder="Nombre" required>
                    <input id="guia-email" class="form-control me-2" type="email" name="email" placeholder="Email" required>
                    <button class="btn btn-danger" type="submit">Registrarse</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<div id="burbuja-datos" class="comic-bubble floating">
    ¡Ey! Primero dejanos tu <strong>Nombre y Mail</strong> aquí para conocerte.
    <br><button class="btn btn-sm btn-dark mt-2" onclick="pasarASiguiente()">Siguiente ➔</button>
</div>

<div id="burbuja-perfil" class="comic-bubble floating">
    ¡Genial! Una vez registrado, aquí en el <strong>Menú</strong> podrás ver tu perfil y tus compras.
    <br><button class="btn btn-sm btn-dark mt-2" onclick="finalizarGuia()">¡Entendido!</button>
</div>

<div class="container text-center">
    <img src="<?= base_url('assets/img/LOGO BORDO 1.png') ?>" alt="Logo Salsa" class="hero-logo">

    <div class="row align-items-center mt-5">
        <div class="col-md-6 text-start">
            <h3>Disfrutala como quieras!</h3>
            <img src="<?= base_url('assets/img/flyer.jpg') ?>" alt="Flyer" class="flyer-img" data-bs-toggle="modal" data-bs-target="#flyerModal">
        </div>
        <div class="col-md-6">
            <h2>¡Bienvenido al sabor auténtico!</h2>
        </div>
    </div>
</div>

<div class="modal fade" id="flyerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="<?= base_url('assets/img/flyer.jpg') ?>" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<footer class="footer-socials text-center">
    <div class="container">
        <p>Contáctanos: <a href="mailto:info@labuonasalsa.com">info@labuonasalsa.com</a></p>
        <div class="d-flex justify-content-center gap-3">
            <a href="#">Facebook</a>
            <a href="#">Instagram</a>
            <a href="#">WhatsApp</a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function posicionarElemento(idBurbuja, idTarget) {
    const burbuja = document.getElementById(idBurbuja);
    const target = document.getElementById(idTarget);
    const rect = target.getBoundingClientRect();

    burbuja.style.display = 'block';
    // Lo posicionamos justo debajo del elemento
    burbuja.style.top = (rect.bottom + window.scrollY + 15) + 'px';
    burbuja.style.left = (rect.left + window.scrollX) + 'px';
}

function pasarASiguiente() {
    document.getElementById('burbuja-datos').style.display = 'none';
    posicionarElemento('burbuja-perfil', 'btn-hamburguesa');
}

function finalizarGuia() {
    document.getElementById('burbuja-perfil').style.display = 'none';
    localStorage.setItem('guiaComicVista', 'true');
}

document.addEventListener("DOMContentLoaded", function() {
    // Solo se muestra si NO ha visto la guía antes
    if (!localStorage.getItem('guiaComicVista')) {
        // Esperamos un segundo para que cargue todo bien
        setTimeout(() => {
            posicionarElemento('burbuja-datos', 'guia-nombre');
        }, 1000);
    }
});
</script>
</script>
</body>
</html>