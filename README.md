# sp

Sitio PHP genérico orientado a landing pages, inicializado con el contenido de ejemplo de **Bloom Latte** para poder reutilizarlo en otros proyectos cambiando solo la configuración.

## Uso

```bash
php -S 127.0.0.1:8000 -t .
```

Abre `http://127.0.0.1:8000`.

## Personalización

Todo el contenido editable vive en `config/site.php`:

- marca y navegación
- hero principal
- secciones reutilizables
- pie de página
- colores del tema

La plantilla principal está en `index.php` y usa funciones auxiliares de `app/bootstrap.php`.