<?php

declare(strict_types=1);

function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function js(mixed $value): string
{
    return (string) json_encode(
        $value,
        JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
}

function render_link(array $link, string $class = 'nav-link'): string
{
    $label = h($link['label'] ?? '');
    $href = h($link['href'] ?? '#');
    $external = !empty($link['external']) ? ' target="_blank" rel="noopener noreferrer"' : '';

    return sprintf('<a class="%s" href="%s"%s>%s</a>', h($class), $href, $external, $label);
}

function render_section(array $section): void
{
    $type = $section['type'] ?? 'content';
    $id = h($section['id'] ?? $type);
    $eyebrow = $section['eyebrow'] ?? null;
    $title = $section['title'] ?? '';
    $description = $section['description'] ?? null;

    echo '<section id="' . $id . '" class="panel panel-' . h($type) . '">';
    echo '<div class="section-heading">';

    if ($eyebrow) {
        echo '<p class="eyebrow">' . h($eyebrow) . '</p>';
    }

    echo '<h2>' . h($title) . '</h2>';

    if ($description) {
        echo '<p class="section-copy">' . h($description) . '</p>';
    }

    echo '</div>';

    switch ($type) {
        case 'cards':
            echo '<div class="card-grid">';
            foreach ($section['items'] ?? [] as $item) {
                echo '<article class="card">';
                echo '<h3>' . h($item['title'] ?? '') . '</h3>';
                if (!empty($item['description'])) {
                    echo '<p>' . h($item['description']) . '</p>';
                }
                if (!empty($item['meta'])) {
                    echo '<span class="card-meta">' . h($item['meta']) . '</span>';
                }
                echo '</article>';
            }
            echo '</div>';
            break;

        case 'split':
            echo '<div class="split-layout">';
            echo '<div class="split-copy">';
            foreach ($section['body'] ?? [] as $paragraph) {
                echo '<p>' . h($paragraph) . '</p>';
            }
            echo '</div>';

            if (!empty($section['bullets'])) {
                echo '<ul class="bullet-list">';
                foreach ($section['bullets'] as $bullet) {
                    echo '<li>' . h($bullet) . '</li>';
                }
                echo '</ul>';
            }
            echo '</div>';
            break;

        case 'stats':
            echo '<div class="stats-grid">';
            foreach ($section['items'] ?? [] as $item) {
                echo '<article class="stat">';
                echo '<strong>' . h($item['value'] ?? '') . '</strong>';
                echo '<span>' . h($item['label'] ?? '') . '</span>';
                echo '</article>';
            }
            echo '</div>';
            break;

        case 'faq':
            echo '<div class="faq-list">';
            foreach ($section['items'] ?? [] as $item) {
                echo '<details class="faq-item">';
                echo '<summary>' . h($item['question'] ?? '') . '</summary>';
                echo '<p>' . h($item['answer'] ?? '') . '</p>';
                echo '</details>';
            }
            echo '</div>';
            break;

        case 'cta':
            echo '<div class="cta-box">';
            if (!empty($section['body'])) {
                echo '<p>' . h($section['body']) . '</p>';
            }
            if (!empty($section['actions'])) {
                echo '<div class="button-row">';
                foreach ($section['actions'] as $action) {
                    echo render_link($action, 'button' . (!empty($action['secondary']) ? ' button-secondary' : ''));
                }
                echo '</div>';
            }
            echo '</div>';
            break;

        default:
            if (!empty($section['body'])) {
                echo '<div class="content-block">';
                foreach ((array) $section['body'] as $paragraph) {
                    echo '<p>' . h($paragraph) . '</p>';
                }
                echo '</div>';
            }
            break;
    }

    echo '</section>';
}
