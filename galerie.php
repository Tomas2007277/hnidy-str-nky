
<?php
// ===== NASTAVENÍ GALERIE =====
$folder = __DIR__ . '/fotky1';
$images = [];

$allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'avif', 'gif', 'heic'];

if (is_dir($folder) && is_readable($folder)) {
    foreach (new DirectoryIterator($folder) as $file) {
        if (!$file->isFile()) {
            continue;
        }

        $extension = strtolower($file->getExtension());

        if (in_array($extension, $allowedExtensions, true)) {
            $images[] = $file->getFilename();
        }
    }
}

natcasesort($images);
$images = array_values($images);
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Fotogalerie</title>

    <style>
    /* ===== ZÁKLAD STRÁNKY ===== */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        min-height: 100vh;
        color: #fff;
        background:
            linear-gradient(rgba(20, 20, 20, 0.82), rgba(20, 20, 20, 0.92)),
            url('./fotky/auto1.jpeg') center / cover fixed no-repeat,
            #242424;
    }

    /* ===== HLAVIČKA ===== */
    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        padding: 20px 40px;
        background: #000;
    }

    /* VELIKOST LOGA */
    header img {
        height: 80px;
        max-width: 100%;
        width: auto;
        display: block;
    }

    .logo {
        color: #f5c400;
        font-size: 1.4rem;
        font-weight: 700;
        text-decoration: none;
    }

    nav {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    nav a {
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }

    nav a:hover,
    nav a:focus-visible {
        color: #f5c400;
    }

    /* ===== HLAVNÍ OBSAH ===== */
    main {
        width: min(1400px, 100%);
        margin: 0 auto;
        padding: 45px 30px 60px;
    }

    .heading {
        margin-bottom: 30px;
        text-align: center;
    }

    h1 {
        margin-bottom: 12px;
        font-size: clamp(2rem, 4vw, 2.8rem);
    }

    h1 span {
        color: #f5c400;
    }

    .heading p {
        color: #ddd;
        line-height: 1.6;
    }

    /* ===== GRID GALERIE ===== */
    .gallery {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 15px;
    }

    .photo {
        display: block;
        width: 100%;
        aspect-ratio: 4 / 3;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 10px;
        padding: 0;
        background: #222;
        cursor: pointer;
    }

    .photo img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .photo:hover img {
        transform: scale(1.05);
        opacity: 0.88;
    }

    .photo:focus-visible {
        outline: 3px solid #f5c400;
        outline-offset: 3px;
    }

    .empty {
        text-align: center;
        color: #ddd;
        padding: 40px 10px;
    }

    /* ===== LIGHTBOX ===== */
    #lightbox {
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100%;
        max-width: none;
        max-height: none;
        margin: 0;
        padding: 65px 75px;
        border: none;
        background: rgba(0, 0, 0, 0.95);
        color: #fff;
        overflow: hidden;
    }

    #lightbox::backdrop {
        background: rgba(0, 0, 0, 0.95);
    }

    #lightbox[open] {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #lightbox-image {
        display: block;
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
    }

    .lb-button {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 0;
        border-radius: 8px;
        color: #fff;
        background: rgba(255, 255, 255, 0.14);
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }

    .lb-button:hover {
        color: #f5c400;
        background: rgba(255, 255, 255, 0.25);
    }

    .close {
        top: 15px;
        right: 20px;
        width: 48px;
        height: 48px;
        font-size: 34px;
    }

    .arrow {
        top: 50%;
        transform: translateY(-50%);
        width: 48px;
        height: 58px;
        font-size: 28px;
    }

    .prev {
        left: 15px;
    }

    .next {
        right: 15px;
    }

    #counter {
        position: absolute;
        bottom: 12px;
        left: 0;
        right: 0;
        text-align: center;
        color: #ddd;
        font-size: 0.95rem;
    }

    /* ===== PATIČKA ===== */
    footer {
        padding: 20px;
        text-align: center;
        color: #ccc;
        background: #0b0d12;
        font-size: 0.9rem;
    }

    /* ===== TABLET ===== */
    @media (max-width: 1000px) {
        .gallery {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        main {
            padding-right: 22px;
            padding-left: 22px;
        }
    }

    /* ===== MOBIL ===== */
    @media (max-width: 600px) {
        header {
            flex-direction: column;
            padding: 20px 15px;
        }

        /* LOGO NA MOBILU */
        header img {
            height: 60px;
            max-width: 100%;
            width: auto;
        }

        nav {
            justify-content: center;
            gap: 14px;
        }

        main {
            padding: 30px 12px 40px;
        }

        .gallery {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        .photo {
            border-radius: 7px;
        }

        #lightbox {
            padding: 55px 40px;
        }

        .close {
            top: 8px;
            right: 8px;
            width: 40px;
            height: 40px;
            font-size: 28px;
        }

        .arrow {
            width: 32px;
            height: 46px;
            font-size: 20px;
        }

        .prev {
            left: 3px;
        }

        .next {
            right: 3px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            scroll-behavior: auto !important;
            transition: none !important;
        }
    }
</style>

</head>

<body>

<header>

    <a href="index.html">
    <img src="logo.png" alt="Zapojeno Elektro logo">
    </a>

    <nav>
        <a href="index.html#služby">Služby</a>
        <a href="index.html#kontakt">Kontakt</a>
    </nav>
</header>

<main>
    <div class="heading">
        <h1>Fotogalerie <span>realizovaných zakázek</span></h1>
        <p>
            Celkem <?= count($images) ?> fotografií.
            Kliknutím na obrázek jej otevřeš ve větší velikosti.
        </p>
    </div>

    <div class="gallery" id="gallery">
        <?php foreach ($images as $index => $name):
            $url = 'fotky1/' . rawurlencode($name);
            $safeUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        ?>
            <button
                class="photo"
                type="button"
                data-index="<?= $index ?>"
                data-full="<?= $safeUrl ?>"
                aria-label="Otevřít fotografii <?= $index + 1 ?>"
            >
                <img
                    src="<?= $safeUrl ?>"
                    alt="Fotografie <?= $index + 1 ?>"
                    loading="lazy"
                    decoding="async"
                >
            </button>
        <?php endforeach; ?>
    </div>

    <?php if (count($images) === 0): ?>
        <p class="empty">
            Ve složce fotky1 nebyly nalezeny žádné fotografie.
            Zkontroluj, že složka existuje a obsahuje obrázky.
        </p>
    <?php endif; ?>
</main>

<footer>
    &copy; <?= date('Y') ?> Moje galerie
</footer>

<!-- ZVĚTŠENÍ FOTOGRAFIE -->
<dialog id="lightbox" aria-label="Prohlížeč fotografií">
    <button class="lb-button close" type="button" aria-label="Zavřít">
        &times;
    </button>

    <button class="lb-button arrow prev" type="button"
            aria-label="Předchozí fotografie">&#10094;</button>

    <img id="lightbox-image" alt="Zvětšená fotografie">

    <button class="lb-button arrow next" type="button"
            aria-label="Další fotografie">&#10095;</button>

    <p id="counter"></p>
</dialog>

<script>
(() => {
    const gallery = document.getElementById('gallery');
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightbox-image');
    const counter = document.getElementById('counter');

    const photos = Array.from(gallery.querySelectorAll('.photo'));
    let currentIndex = 0;

    if (photos.length === 0) return;

    function showPhoto(index) {
        currentIndex = (index + photos.length) % photos.length;

        lightboxImage.src = photos[currentIndex].dataset.full;
        counter.textContent =
            `${currentIndex + 1} / ${photos.length}`;
    }

    gallery.addEventListener('click', event => {
        const button = event.target.closest('.photo');

        if (!button) return;

        showPhoto(Number(button.dataset.index));
        lightbox.showModal();
    });

    lightbox.querySelector('.close').addEventListener('click', () => {
        lightbox.close();
    });

    lightbox.querySelector('.prev').addEventListener('click', () => {
        showPhoto(currentIndex - 1);
    });

    lightbox.querySelector('.next').addEventListener('click', () => {
        showPhoto(currentIndex + 1);
    });

    lightbox.addEventListener('click', event => {
        if (event.target === lightbox) {
            lightbox.close();
        }
    });

    document.addEventListener('keydown', event => {
        if (!lightbox.open) return;

        if (event.key === 'ArrowLeft') {
            showPhoto(currentIndex - 1);
        } else if (event.key === 'ArrowRight') {
            showPhoto(currentIndex + 1);
        }
    });
})();
</script>

</body>
</html>