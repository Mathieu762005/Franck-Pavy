<head>
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<div class="text-black border background">
    <div class="footerMargin mx-auto d-flex justify-content-between align-items-center">
        <div class="">
            <img src="/assets/image/Logo/LogoArtisan.png" class="Logo border border-black border-2" alt="">
        </div>
        <div class="text-center">
            <img src="/assets/image/Logo/Logo.png" class="mb-3" alt="">
            <p>62 Rue Félix Faure,</p>
            <p>76290 Montivilliers</p>
            <p class="mt-3">02 35 30 27 58</p>
            <span><i class="bi bi-instagram"></i></span>
            <span><i class="bi bi-facebook"></i></span>
        </div>
        <div class="">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2588.75778905509!2d0.19044397724887885!3d49.545723352090796!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e03abf207c051f%3A0x19f908d7f9b7272!2s62%20Rue%20F%C3%A9lix%20Faure%2C%2076290%20Montivilliers!5e0!3m2!1sfr!2sfr!4v1765131683511!5m2!1sfr!2sfr"
                width="220" height="150" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="d-flex">
            <div>
                <h1 class="text-center">Pavy est ouvert :</h1>
                <p>Lundi de 07:00 à 19:00</p>
                <p>Mardi de 07:00 à 19:00</p>
                <p>Jeudi de 07:00 à 19:00</p>
                <p>Vendredi de 07:00 à 19:00</p>
                <p>Samedi de 07:00 à 19:00</p>
                <p>Dimanche de 07:00 à 19:00</p>
            </div>
        </div>
    </div>
    <div class="text-center">
        <span>Politique de confidentialité</span>
        <span>Mentions légales</span>
        <?php if (isset($_SESSION['user'])): ?>
            <a class="btn border border-black" href="index.php?url=logout" type="submit">déconnexion</a>
        <?php endif; ?>
    </div>
</div>