# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

WordPress website for TC Illertissen e.V. (a tennis club), live at `www.tci-illertissen.de`, hosted on IONOS. Local development uses DDEV (Docker-based).

## Local Development

**Start the local environment:**
```bash
ddev start
```

**Import a database dump:**
```bash
ddev import-db --file=<dump.sql.gz>
```
After import, DDEV automatically rewrites the domain from `tc-illertissen.de` → `tci.develop.ddev.site` (configured via post-import-db hook in `.ddev/config.yaml`).

**Access the local site:** `https://tci.old.ddev.site`

**Run WP-CLI commands:**
```bash
ddev wp <command>
```

**PHP/MySQL access:**
```bash
ddev ssh        # shell into web container
ddev mysql      # MySQL CLI
```

## WordPress Configuration

Environment-specific config files are committed and swapped by CI/CD at deploy time:
- `wordpress/wp-config.php` — active DDEV config (DB host: `ddev-tci.old-db`, DB: `db`/`db`/`db`)
- `wordpress/wp-config.PROD.php` — production credentials
- `wordpress/wp-config.DEV.php` — dev server credentials
- `wordpress/wp-config-ddev.php` — DDEV template

Table prefix: `wOzvRyiS`

Debug logging is enabled locally (`WP_DEBUG_LOG=true`, `WP_DEBUG_DISPLAY=false`). Logs go to `wordpress/wp-content/debug.log`.

## Deployment

Deployment is fully automated via GitHub Actions (`.github/workflows/`):
- Push to `main` → deploys to production (`www.tci-illertissen.de`) using `wp-config.PROD.php`
- Push to `develop` → deploys to `dev.tc-illertissen.de` using `wp-config.DEV.php`

Deployment uses rsync over SSH. Languages, node_modules, and uploads are excluded from sync.

Production path: `/kunden/homepages/41/d100076765/htdocs/clickandbuilds/TCIllertissen/`

## PDF Shortcode — `[nuliga_pdf]`

The plugin can fetch nuLiga `ScheduleReportFOP` PDFs and render their tables directly in WordPress pages.

```
[nuliga_pdf url="https://btv.liga.nu/.../nuDokument?dokument=ScheduleReportFOP&group=XXX&etag=YYY"
            type="spielplan"
            highlight="TC Illertissen"
            title="Spielplan Herren Südliga"]
```

| Attribute | Default | Values |
|-----------|---------|--------|
| `url` | *(required)* | nuLiga PDF URL — with or without `etag`; the server redirects to the current version automatically |
| `type` | `spielplan` | `spielplan`, `tabelle`, `beide` |
| `highlight` | *(empty)* | Team name fragment — matching rows get green background |
| `title` | auto from PDF | Override heading text |

**Implementation files:**
- `includes/class-nuliga-pdf-parser.php` — fetches PDF, extracts standings via DataTm positions and schedule via text parsing
- `public/class-nuliga-public.php` — shortcode handler + HTML rendering (`render_tabelle`, `render_spielplan`)
- `vendor/` — `smalot/pdfparser` (gitignored, run `composer install` after checkout)

**PDF parsing approach:**
- Standings (Tabelle): position-based via `getDataTm()` — items with X < 400 pt belong to the left column; rows are grouped by Y proximity
- Schedule (Spielplan): text-based via `getText()` — date headers matching `So.dd.mm.yyyyHH:MM` trigger row parsing; multi-game dates collect N×time, N×home, N×guest as separate lines
- Results are cached in WordPress transients for 1 hour

## Custom Code

### Plugin: `wp-nuliga-master`
Location: `wordpress/wp-content/plugins/wp-nuliga-master/`

The only fully custom plugin. Integrates sports league schedules from the nuLiga/nuTab system into WordPress. Standard WordPress OOP plugin structure:
- `class-nuliga.php` — main class, wires up hooks via the loader
- `class-nuliga-loader.php` — manages action/filter registration
- `includes/` — core business logic
- `admin/` and `public/` — admin and frontend concerns separated

### Theme: `pro-child`
Location: `wordpress/wp-content/themes/pro-child/`

Child theme extending the commercial "Pro" theme (Themeco). Currently minimal — only enqueues parent stylesheet. Custom site-specific PHP/CSS overrides belong here.

### Theme: `x-child`
Location: `wordpress/wp-content/themes/x-child/`

Child theme for the commercial "X" theme (also by Themeco, more modern architecture using `Themeco\Theme` namespace and service-based boot). Same pattern as pro-child.

## Stack

- PHP 8.4, Apache-FPM, MySQL 5.7
- WordPress with Cornerstone page builder (Themeco), SiteOrigin Panels, Revolution Slider
- Yoast SEO, UpdraftPlus backups, Contact Form 7
- WPML configured for multilingual support
