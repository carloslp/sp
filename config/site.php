<?php

declare(strict_types=1);

return [
    'meta' => [
        'title' => 'Bloom Latte | Plantilla PHP para cafeterías y marcas lifestyle',
        'description' => 'Base PHP reutilizable para migrar landing pages y personalizarlas mediante configuración.',
    ],
    'theme' => [
        'accent' => '#8a5cf6',
        'accentSoft' => '#efe7ff',
        'surface' => '#fffaf8',
        'text' => '#2f2235',
        'muted' => '#6f6276',
    ],
    'brand' => [
        'name' => 'Bloom Latte',
        'tagline' => 'Café floral, brunch artesanal y experiencias listas para reutilizar.',
    ],
    'navigation' => [
        ['label' => 'Menú', 'href' => '#menu'],
        ['label' => 'Concepto', 'href' => '#concepto'],
        ['label' => 'Preguntas', 'href' => '#preguntas'],
        ['label' => 'Contacto', 'href' => '#contacto'],
    ],
    'hero' => [
        'eyebrow' => 'Migración a PHP',
        'title' => 'Una landing reusable para Bloom Latte y proyectos similares.',
        'description' => 'Esta base transforma el sitio en una plantilla PHP genérica: contenido centralizado, secciones reutilizables y estructura simple para futuras marcas.',
        'primaryAction' => ['label' => 'Ver menú', 'href' => '#menu'],
        'secondaryAction' => ['label' => 'Personalizar base', 'href' => '#concepto'],
        'highlights' => [
            'Configuración única para textos, links y colores',
            'Secciones desacopladas para reutilizar el layout',
            'Salida segura con escape HTML en todos los contenidos',
        ],
    ],
    'sections' => [
        [
            'type' => 'cards',
            'id' => 'menu',
            'eyebrow' => 'Oferta principal',
            'title' => 'Bloques de producto listos para adaptar',
            'description' => 'Ejemplo de contenido inicial para Bloom Latte que puede sustituirse por cualquier catálogo, menú o servicios.',
            'items' => [
                [
                    'title' => 'Lavender Latte',
                    'description' => 'Espresso suave con leche vaporizada y jarabe floral.',
                    'meta' => 'Firma de la casa',
                ],
                [
                    'title' => 'Rose Matcha',
                    'description' => 'Matcha ceremonial con espuma cremosa y notas de rosa.',
                    'meta' => 'Bebida estacional',
                ],
                [
                    'title' => 'Bloom Brunch',
                    'description' => 'Toast artesanal, fruta fresca y acompañamientos del día.',
                    'meta' => 'Ideal para compartir',
                ],
            ],
        ],
        [
            'type' => 'split',
            'id' => 'concepto',
            'eyebrow' => 'Base genérica',
            'title' => 'El sistema usa contenido estructurado en vez de HTML rígido.',
            'description' => 'Así el mismo proyecto puede servir para cafeterías, estudios creativos, marcas personales o lanzamientos.',
            'body' => [
                'La página toma sus datos desde un único archivo de configuración en PHP.',
                'Cada sección declara su tipo y el contenido necesario para renderizarse.',
                'El diseño mantiene un estilo editorial simple para que sea fácil de retematizar.',
            ],
            'bullets' => [
                'Cambiar marca, colores y secciones sin tocar la plantilla base',
                'Mantener una sola entrada pública: index.php',
                'Evitar dependencias adicionales para un despliegue simple',
            ],
        ],
        [
            'type' => 'stats',
            'id' => 'metricas',
            'eyebrow' => 'Ventajas',
            'title' => 'Pensado para reutilizar',
            'description' => 'Una estructura mínima, pero suficiente para extender el sistema.',
            'items' => [
                ['value' => '1', 'label' => 'archivo de configuración principal'],
                ['value' => '5', 'label' => 'tipos de sección reutilizables'],
                ['value' => '0', 'label' => 'dependencias externas obligatorias'],
            ],
        ],
        [
            'type' => 'faq',
            'id' => 'preguntas',
            'eyebrow' => 'FAQ',
            'title' => 'Preguntas comunes al reutilizar la base',
            'items' => [
                [
                    'question' => '¿Puedo usarlo para otra marca?',
                    'answer' => 'Sí. Solo cambia el contenido del archivo config/site.php y conserva la estructura de secciones.',
                ],
                [
                    'question' => '¿Necesita base de datos?',
                    'answer' => 'No para esta versión. Es una landing estática dinámica basada en configuración PHP.',
                ],
                [
                    'question' => '¿Se puede ampliar?',
                    'answer' => 'Sí. Puedes agregar nuevos tipos de sección en app/bootstrap.php y declararlos en la configuración.',
                ],
            ],
        ],
        [
            'type' => 'cta',
            'id' => 'contacto',
            'eyebrow' => 'Siguiente paso',
            'title' => 'Lista para convertirse en tu siguiente sitio.',
            'body' => 'Toma esta base como punto de partida, reemplaza el contenido de ejemplo y despliega el sitio con cualquier hosting PHP sencillo.',
            'actions' => [
                ['label' => 'Escribir por correo', 'href' => 'mailto:hola@bloomlatte.test'],
                ['label' => 'Ver configuración', 'href' => '#top', 'secondary' => true],
            ],
        ],
    ],
    'footer' => [
        'headline' => 'Bloom Latte',
        'copy' => 'Plantilla PHP reusable para landing pages visuales y simples.',
        'links' => [
            ['label' => 'Instagram', 'href' => 'https://instagram.com/', 'external' => true],
            ['label' => 'Email', 'href' => 'mailto:hola@bloomlatte.test'],
        ],
    ],
];
