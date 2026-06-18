# sp

Sitio PHP genérico para menús dinámicos tipo **Bloom Latte**, con carrito, personalización de productos y envío de pedido por WhatsApp.

## Uso

```bash
php -S 127.0.0.1:8000 -t .
```

Abre `http://127.0.0.1:8000`.

## Personalización

Todo el contenido editable vive en `config/site.php`:

- marca y textos UI
- métodos de pago
- integración del menú remoto
- datos demo para reutilizar la base sin depender de APIs reales

La plantilla principal está en `index.php` y usa funciones auxiliares de `app/bootstrap.php`.

## Integraciones opcionales

Para conectar una hoja remota sin dejar credenciales en el repositorio, puedes definir estas variables de entorno:

- `SP_MENU_API_BASE`
- `SP_MENU_API_TOKEN`
- `SP_MENU_SHEET_ID`
- `SP_WHATSAPP_NUMBER`

Si no se configuran, la app usa los datos demo incluidos en `config/site.php`.