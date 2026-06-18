<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

$site = require __DIR__ . '/config/site.php';
$meta = $site['meta'];
$theme = $site['theme'];
$brand = $site['brand'];
$hero = $site['hero'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($meta['title']) ?></title>
    <meta name="description" content="<?= h($meta['description']) ?>">
    <link rel="stylesheet" href="/assets/styles.css">
    <style>
        :root {
            --accent: <?= h($theme['accent']) ?>;
            --accent-soft: <?= h($theme['accentSoft']) ?>;
            --surface: <?= h($theme['surface']) ?>;
            --text: <?= h($theme['text']) ?>;
            --muted: <?= h($theme['muted']) ?>;
        }
    </style>
</head>
<body id="top">
    <div class="page-shell">
        <header class="site-header">
            <div>
                <p class="brand-name"><?= h($brand['name']) ?></p>
                <p class="brand-tagline"><?= h($brand['tagline']) ?></p>
            </div>
            <nav class="site-nav" aria-label="Principal">
                <?php foreach ($site['navigation'] as $link): ?>
                    <?= render_link($link) ?>
                <?php endforeach; ?>
            </nav>
        </header>

        <main>
            <section class="hero">
                <div class="hero-copy">
                    <p class="eyebrow"><?= h($hero['eyebrow']) ?></p>
                    <h1><?= h($hero['title']) ?></h1>
                    <p class="hero-description"><?= h($hero['description']) ?></p>
                    <div class="button-row">
                        <?= render_link($hero['primaryAction'], 'button') ?>
                        <?= render_link($hero['secondaryAction'], 'button button-secondary') ?>
                    </div>
                </div>

                <aside class="hero-panel" aria-label="Puntos destacados">
                    <h2>Por qué esta base funciona</h2>
                    <ul class="bullet-list">
                        <?php foreach ($hero['highlights'] as $highlight): ?>
                            <li><?= h($highlight) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </aside>
            </section>

            <?php foreach ($site['sections'] as $section): ?>
                <?php render_section($section); ?>
            <?php endforeach; ?>
        </main>

        <footer class="site-footer">
            <div>
                <p class="brand-name"><?= h($site['footer']['headline']) ?></p>
                <p class="brand-tagline"><?= h($site['footer']['copy']) ?></p>
            </div>
            <div class="footer-links">
                <?php foreach ($site['footer']['links'] as $link): ?>
                    <?= render_link($link, 'nav-link') ?>
                <?php endforeach; ?>
            </div>
        </footer>
    </div>
</body>
</html>
