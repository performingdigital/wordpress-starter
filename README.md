# WordPress App

Composer-managed WordPress project with custom application code, Timber templates, and a safe content directory outside WordPress core.

## Install

```bash
composer install
pnpm install
```

Copy env file and adjust values:

```bash
cp .env.example .env
```

Build frontend assets:

```bash
pnpm build
```

## Structure

- `public/wordpress/` — WordPress core installed by Composer. Do not put custom code here.
- `public/content/` — themes, MU plugins, plugins, uploads. This survives WordPress core reinstalls.
- `public/content/themes/starter/` — starter theme that boots `App\StarterSite`.
- `public/content/mu-plugins/app.php` — forces WordPress to use the starter theme.
- `bootstrap/app.php` — Composer autoload, service container, and config helper.
- `bootstrap/wordpress.php` — WordPress constants and project bootstrap.
- `app/` — application classes and service providers.
- `templates/` — Timber templates.
- `routes/web.php` — Timber template routing.

## Twig components

This project uses `performing/twig-components` with custom tag syntax.

- Components live in `templates/_components/`
- Use them as `<x-name>` / `<x-folder.name>`
- Configuration lives in `App\StarterSite::add_to_twig()`

Example:

```twig
<x-button class="button">Click me</x-button>
```

## Composer notes

WordPress core is installed to `public/wordpress`, while Composer-installed WordPress plugins/themes are installed to `public/content`.

This prevents custom themes and MU plugins from being deleted when WordPress core is reinstalled.
