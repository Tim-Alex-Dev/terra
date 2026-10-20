# WordPress Starter v1.0

WordPress starter theme. Every new project starts as a copy of it.

* Pages are built from **ACF Pro Flexible Content** modules ("Page Builder"). No Gutenberg.
* Styles: **SCSS** (ITCSS layers + BEM), own container and grid, no CSS framework.
* Scripts: **Webpack + Babel**, jQuery is available. Heavy scripts load only on pages that need them.
* Build: **Gulp 5** (`gulp`, `gulp watch`, `gulp prod`).

Rules for working on the theme (also read by Claude Code) are in `.claude/CLAUDE.md` and `.claude/rules/*.md`.

## Contents

* [Project setup](#project-setup)
* [Commands](#commands)
* [Project settings](#project-settings)
* [Files and folders](#files-and-folders)
* [Page Builder modules](#page-builder-modules)
* [Theme Settings (ACF options)](#theme-settings-acf-options)
* [SCSS](#scss)
* [JavaScript](#javascript)
* [PHP and templates](#php-and-templates)
* [Images, icons, fonts](#images-icons-fonts)
* [Optional features and add-ons](#optional-features-and-add-ons)
* [Quality checks](#quality-checks)
* [Deployment](#deployment)
* [Known open points](#known-open-points)

## Project setup

<!-- project-setup:start (filled with the project's values by /new-project; update when a version changes) -->
| Setting | Value |
| --- | --- |
| Project | Terra |
| Description | Website for the Ukrainian locale of the German original website https://www.wortmann.de/ (modern computers, laptops, monitors etc.). The goal is to replicate all fonts, colors, styles, menu structure, logos, icons and buttons of the original home page in this project, then use those styles to build a few more pages such as About us. A full visual copy only: all links point to the original website. Work starts with the home page. Tagline: "Ukrainian TERRA site: computers, laptops and monitors" |
| Design source | Reference website: https://www.wortmann.de/ (home page first) |
| Repository | `https://github.com/Tim-Alex-Dev/terra` (`master` = production + default branch, `develop` = base branch) |
| Local URL | `https://terra.loc` (Laragon, SSL) |
| Theme | terra, folder `wp-content/themes/terra`, text domain `terra` |
| WordPress | 7.1.3, English (`en_US`), timezone Europe/Kyiv, week starts Monday, date `d.m.Y`, time `H:i`, permalinks `/%postname%/`, search engines discouraged, comments and avatars off, uploads not in month folders, image sizes medium 768 / large 1920, table prefix `trr_` |
| ACF | field groups synced from `acf-json/` into the database at project start: Component: Module Title, Module: FAQ, Page Builder, Theme Settings |
| PHP | 8.3.30 (the theme works on 7.4+) |
| Database | MySQL 8.4.3, `utf8mb4` / `utf8mb4_unicode_520_ci`, database `terra_db`, prefix `trr_` |
| Web server | Apache 2.4.66 with the default WordPress `.htaccess` |
| Node.js / npm | Node 24.21.0 (`.nvmrc`, minimum 20.19) / npm 11.19.0 |
| Required plugins | Advanced Custom Fields Pro 6.8.10, Advanced Custom Fields: Extended 0.9.2.7 (the theme calls ACF directly, no fallbacks), Yoast SEO 28.6 (breadcrumbs; separator `-`, author archives off), Contact Form 7 6.2.2, WP Mail SMTP 4.10.2 (mail server settings per environment) |
| Optional features | Added: Contact Form 7 add-on (field grid in form templates: `<div class="form-grid">` with `<label>` per field, `form-grid__full` = whole row), WP Mail SMTP plugin. Removed: AJAX (`inc/ajax.php`, `components/ajax.js`). Not used: WPML, WooCommerce |
| `wp-config.php` (local) | `WP_DEBUG`, `WP_DEBUG_LOG`, `WP_DEBUG_DISPLAY` = true, `WP_ENVIRONMENT_TYPE` = `local`, `DISALLOW_FILE_EDIT` = true |
<!-- project-setup:end -->

Logins, passwords and license keys are never stored in the repository: ask the project lead.

### Set up on a new machine

1. Install a local server with PHP and MySQL in the versions above (e.g. Laragon), Node.js (nvm) and Git.
2. Create the site: WordPress in a new site folder, an empty database (charset above), `wp-config.php` with the local
   constants above. Or get a database dump + `wp-content/uploads/` from the project lead and import them.
3. Clone the theme: `git clone https://github.com/Tim-Alex-Dev/terra.git wp-content/themes/terra`, then `git checkout develop` (the base branch; `master` is the default).
   `.claude/` (rules, add-ons, tests, project profile) is not in git: copy it from the starter or from the project lead.
4. Install and activate the required plugins (ACF Pro license key from the project lead), activate the theme.
5. In the theme folder: `npm i`, then `gulp` (watcher) or `gulp watch` (+ BrowserSync on `devUrl`). Deploy: `npm run release` (see [Deployment](#deployment)).
6. ACF field groups load from `acf-json/`. If WP admin shows "Sync available" (ACF > Field Groups), sync them.
7. Check: `npm run lint`, `bash .claude/tests/run.sh`.

## Commands

| Command | What it does |
| --- | --- |
| `npm i` | Install dependencies |
| `gulp` / `npm start` | Development build (with source maps) + watcher (SCSS, JS, images, fonts) |
| `gulp watch` / `npm run watch` | The same + BrowserSync on `devUrl` (port 8000) |
| `gulp prod` / `npm run build` | Clean production build: `dist/` is deleted and rebuilt, minified, no source maps. Fails on Sass, CSS or JS errors |
| `gulp init` | Applies `starter.config.json` (see below). Safe to run again |
| `gulp textdomain` | Only renames the text domain |
| `gulp css` / `js` / `images` / `fonts` | One part of the build |
| `npm run lint` | Stylelint (SCSS) + ESLint (JS). `npm run lint:scss:fix` fixes what it can |
| `bash .claude/tests/run.sh` | Unit tests (PHP + JS, no WordPress needed) |
| `SASS_VERBOSE=1 gulp css` | Shows all Sass deprecation warnings |

`dist/` is build output: never edit it, never commit it (`.gitignore`).

## Project settings

`starter.config.json`:

```json
{
  "themeName": "terra",
  "themeSlug": "terra",
  "textDomain": "terra",
  "themeFolder": "terra",
  "devUrl": "https://terra.loc"
}
```

`gulp init` replaces the current text domain (read from `Text Domain` in `style.css`) in all theme files, sets
`Theme Name` in `style.css` and `name` in `package.json`, and the server folder in `.gitlab-ci.yml` (GitLab CI add-on only).
`devUrl` is the local site for BrowserSync. The project description is written by hand in `style.css` (`Description`).

PHP prefix for functions, hooks and filters: `it_` (constants `IT_*`). It is the same in every project.

## Files and folders

### Root

| File | Purpose |
| --- | --- |
| `functions.php` | Constants (`IT_DIR`, `IT_URL`, `IT_CSS`, `IT_JS`, `IT_IMG` = paths/URLs of the theme and `dist/`) and the `inc/` files |
| `header.php` | `<head>`, skip link, two-level site header (top bar: logo, header buttons, burger; menu bar: main menu, 3 levels), opens `<main class="site-content">` |
| `footer.php` | Closes `<main>`, site footer like the original: link columns (menu "Footer Nav (columns)"; 2 per row on phones, 3 from md, one row from lg), bottom bar (footer logo, menu "Footer Bottom Nav", copyright), SVG sprite, to-top button, `wp_footer()` |
| `page.php` | Pages (no breadcrumbs): Page Builder modules, or title + content if the page has no modules |
| `single.php` | Single posts (and CPTs without own template): image, title, meta, content, prev/next, related posts |
| `index.php` | Blog and archives: title, post cards grid, pagination |
| `search.php` | Search results: search form, post cards, pagination |
| `404.php` | Not found page |
| `searchform.php` | Search form (`get_search_form()`) |
| `style.css` | Theme header only: name, author, description, version, text domain. No CSS (styles are in `dist/css/`) |
| `screenshot.png` | Theme preview in wp-admin |
| `favicon.ico` | Favicon (64-16 px), linked and served at `/favicon.ico` by `inc/favicon.php` (a Site Icon set in WP admin wins) |
| `starter.config.json` | Project settings (above) |
| `gulpfile.js` | Build: SCSS -> LightningCSS, JS -> Webpack/Babel, images and fonts copied to `dist/` |
| `package.json` | Dependencies, npm scripts, `browserslist` (target browsers for CSS and JS) |
| `eslint.config.js`, `.stylelintrc.json` | Lint rules |
| `.editorconfig` | Tabs, LF, UTF-8 (JSON/YAML: 2 spaces) |
| `.nvmrc` | Node version (24) |

### `acf-json/`

ACF field groups as JSON (ACF "Local JSON", Auto Sync). **Written only by ACF** when a field group is saved in wp-admin:
never edit, add or delete files there by hand. To change fields: change them in wp-admin, ACF updates the files.

### `inc/` (PHP, all loaded by `functions.php`)

| File | Purpose |
| --- | --- |
| `after-theme-setup.php` | Theme supports (title tag, thumbnails, HTML5), image size `admin-preview` (300 x 300 max, ACF image field previews in wp-admin), menu locations `main`, `footer` (columns) and `footer-bottom`, translations folder, content width, body class `{post type}-{slug}`, archive titles without prefix, excerpt 20 words |
| `admin-colors.php` | wp-admin color scheme "Theme" in the project colors (`admin-colors.scss`), used for every user |
| `acf.php` | Options page **Theme Settings**, automatic Page Builder layout previews, unused ACF Extended modules off |
| `custom-post-type.php` | Commented example: custom post type + taxonomy |
| `disables.php` + `disables/` | WordPress features the theme does not use, one file each (see below) |
| `editor.php` | Classic editor (TinyMCE, also ACF WYSIWYG): editor styles, "Formats" menu (titles, text sizes, buttons, lists), text colors = palette |
| `favicon.php` | Favicon link in `<head>` (site, admin, login) and `/favicon.ico` redirect to the theme's `favicon.ico` |
| `help-func.php` | Helpers (see below) |
| `login.php` | Login screen: logo links to the site, own styles (`login.scss`) |
| `menu.php` | Main menu: parents without a link (`#`) are printed as `<button class="menu-toggle">` (opened by `navigation.js`). Footer menu: top-level items without a link are the column headings (`<h2 class="footer-links__title">`) |
| `scripts-styles.php` | Enqueues `main.css`, `main.js` (+ `itSettings`: `ajaxUrl`, `nonce`), admin styles/script. Version = file time (cache busting) |
| `svg-support.php` | SVG uploads for administrators only; every SVG is sanitized (scripts, events, external links removed); image sizes of an SVG = its own size scaled into each size box |

`inc/disables/` (comment out a `require` in `disables.php` to keep a feature):

| File | Turns off |
| --- | --- |
| `admin-bar.php` | WordPress logo menu in the admin bar |
| `auto-updates.php` | Automatic plugin and theme updates (updates are done by hand) |
| `basic.php` | WP version, RSD, WLW manifest, shortlink in `<head>` |
| `comments.php` | Comments everywhere (the theme has no comments templates) |
| `dashboard-widgets.php` | Quick Draft, WordPress News, Activity, At a Glance, Welcome panel |
| `emoji.php` | Emoji script and styles |
| `gutenberg.php` | Block editor, block library CSS, global styles |
| `wp-embeds.php` | `wp-embed.js` |
| `xmlrpc.php` | XML-RPC and the pingback header |
| `jquery-migrate.php` | *Off by default.* jQuery Migrate on the front-end (only if no plugin needs it) |
| `theme-plugin-editor.php` | *Off by default.* File editor in wp-admin |

Helpers in `help-func.php`:

| Function | Use |
| --- | --- |
| `it_id_attr( $value )` | Ready `id` attribute: `' id="our-team"'` or `''` (module ids) |
| `it_excerpt( $words )` | Prints the excerpt limited to N words |
| `it_posted_on()`, `it_posted_by()`, `it_cat_links()`, `it_tag_links()` | Post meta (date, author, categories, tags) |
| `it_inline_svg( $url )` | SVG code of a file (uploads or theme read from disk, remote files sanitized) |
| `it_image_placeholder()` | Placeholder for a missing image |
| `it_phone_cleaner( $tel )` | Phone number for `tel:` links |
| `it_console_log( $value )` | Debug: value in the browser console (admins only) |
| `[email]a@b.com[/email]` | Shortcode: e-mail link hidden from spam bots |

### `template-parts/`

| File | Purpose |
| --- | --- |
| `builder.php` | Loops the `builder` field, loads `builder/{layout}.php` per module (layouts without a template are skipped) |
| `builder/{layout}.php` | One template per Page Builder module: `hero.php` (slider + link blocks, normal container (max 1400px) without side padding, 40px vertical padding, thin even gaps; from md (640px) header + hero = 100svh: 2 tile rows of 150px, the banner fills the rest of the height with grey arrow panels beside it), `simple_content.php` (headings + content blocks: text, media (image, video file with banner = lightbox, video link = lazy youtube-nocookie iframe) or text + media in 2 columns, media left/right; background white/grey), `partnership.php` (headings + 2 endless logo rows, odd logos top, even bottom, name on hover; background white/grey), `services.php` (headings + text and a Contact Form 7 form card; from lg text fluid + form 515px but at most half of the row (equal columns on narrower screens; lg-xl: all form fields full width), below lg stacked; background white/grey), `contacts.php` (headings + company/address, hours, phones, e-mails (antispambot), links with icons in 2 blocks (address, phones, hours | e-mails, links; stacked on phones and next to the map from lg, side by side md-lg) and a lazy Google map (only `https://www.google.com/maps/embed` links), 5:7 columns from lg; background white/grey), `faq.php` |
| `builder/components/headings.php` | Module headings: uptitle, title, subtitle |
| `post-card.php` | Post card (image, meta, title, excerpt, "Read more"). A post type can get its own: `post-card-{post_type}.php` |
| `post-related.php` | 3 latest posts of the current post type (single) |
| `pagination.php` | Pagination (`<nav>`) |
| `breadcrumbs.php` | Yoast breadcrumbs (`<nav>`, not on the front page). Not used in this project (removed from `page.php` and `single.php`) |
| `content-none.php` | "Nothing found" |
| `header-buttons.php` | Header buttons from Theme Settings (top bar on desktop, under the menu on mobile) |
| `socials.php` | Social links from Theme Settings |
| `svg.php` | SVG icon sprite (printed in the footer) |

### `assets/` (sources, built into `dist/`)

```
assets/
  fonts/        .woff2 / .woff only -> dist/fonts/
  img/          .svg / .webp only -> dist/img/
                logo.svg, black/white-loader.svg, plyr.svg (video player icons, copy from node_modules/plyr)
    acfe-thumbnails/   Page Builder layout previews: {layout name}.jpg, 400x320 (the only .jpg allowed)
  js/           see JavaScript
  scss/         see SCSS
```

### `.claude/` (local only: never built, deployed or committed, in `.gitignore`)

| Path | Purpose |
| --- | --- |
| `CLAUDE.md` | Main rules of the theme (stack, hard rules, commands) |
| `rules/*.md` | Detailed rules per area: `php`, `scss`, `js`, `acf-builder`, `images`, `fonts` |
| `optional-features.md` | Checklist for a new project: features to remove or add |
| `addons/` | Code for optional plugins, added per project (see add-ons) |
| `tests/` | Unit tests (`run.sh`) |
| `agents/`, `skills/` | Project subagents and skills (empty in the starter) |
| `settings.json` | Claude Code permissions: no edits in `dist/`, `acf-json/`, `package-lock.json` |

## Page Builder modules

The ACF field group **Page Builder** (pages) has a Flexible Content field `builder`. Every layout is a module.

**One module = 4 things with the same name** (example layout `text_media`):

| What | Where |
| --- | --- |
| Field group `Module: Text Media`, cloned (seamless) into Page Builder as layout `text_media` | wp-admin (-> `acf-json/`) |
| Template | `template-parts/builder/text_media.php` |
| Styles, BEM block `.m-text-media` | `assets/scss/4-builder/_text-media.scss` |
| Admin preview 400x320 | `assets/img/acfe-thumbnails/text_media.jpg` (registered automatically) |

**Fields of every module** (default, see `Module: FAQ`):

* tab **Module Settings**: `module_id` (anchor id of the section);
* tab **Module Content**: first the clone of `Component: Module Title` (`title`, `title_tag`, `title_alignment`,
  `uptitle`, `subtitle`), then the module's own fields.

**Template** (`template-parts/builder/faq.php` is the reference): all fields are read into variables at the top, the
markup only uses them. Repeaters are checked with `get_sub_field()` and looped with `have_rows()`.

```php
<?php
$module_id       = it_id_attr( get_sub_field( 'module_id' ) );
$module_title    = get_sub_field( 'title' ) ?: false;
$module_uptitle  = get_sub_field( 'uptitle' ) ?: false;
$module_subtitle = get_sub_field( 'subtitle' ) ?: false;
$module_headings = $module_title || $module_uptitle || $module_subtitle;
?>

<section<?php echo $module_id; ?> class="module m-name">
	<div class="container">
		<?php if ( $module_headings ) : ?>
			<?php get_template_part( 'template-parts/builder/components/headings' ); ?>
		<?php endif; ?>

		<!-- module content -->
	</div>
</section>
```

* Extra elements next to the headings (slider arrows, a button): wrap headings + elements in `<div class="module-header">`.
* **Headings component**: `<div class="module-headings alignment-{left|center|right}">` with `__uptitle`, `__title`,
  `__subtitle`. Title tag: the one chosen in the module, or (none chosen) **h1 in the first module of the page**, h2 in
  the others, so a page built from modules always has its h1. The title looks like an h2 with any tag (Montserrat 900,
  letter-spacing 0.02em). Width: 100% centered below md, 75% centered below lg, from lg max 50% of the container with the
  chosen alignment.
* Shared module styles: `4-builder/_!module.scss` (`.module` spacing: no margin, vertical padding 45/50px, 70px from lg; Hero overrides it with none, module background `bg-white` / `bg-grey` from the `color_theme` field, `.module-headings`, `.module-header`).
* Module script (if needed): `assets/js/modules/{name}.js`, loaded for the selector `.m-{name}`.
* Above-the-fold images (e.g. a hero) must not be lazy-loaded: `[ 'loading' => 'eager', 'fetchpriority' => 'high' ]`.

## Theme Settings (ACF options)

wp-admin -> **Theme Settings**: `header_logo`, `header_buttons` (repeater: `link`, `icon` windows/none), `footer_logo` (falls back to `header_logo`), `enable_to_top`
(to-top button), `socials` (repeater: `name` = network, `url`), tab Leave Popup: `leave_title`, `leave_text`, `leave_address_label`,
`leave_go_button`, `leave_stay_button` (texts of the leave-site popup; an empty field falls back to the theme's translatable string).

## SCSS

Entry files in `assets/scss/` (each -> `dist/css/{name}.css`):

| File | Loaded |
| --- | --- |
| `main.scss` | Front-end, all layers below |
| `editor-styles.scss` | Classic editor (settings + generic layers only) |
| `admin-styles.scss` | wp-admin (system colors only, no theme settings): ACF / ACFE look (buttons and ACF's own hard-coded blue on the editing screens follow the admin scheme color `--wp-admin-theme-color`; ACF's Field Groups pages keep their blue), empty ACFE columns (all fields hidden) take no space |
| `admin-colors.scss` | wp-admin color scheme in the project colors: WordPress core's scheme module (copy in `assets/scss/wp-admin-colors/`, WordPress 7.1.3, so the build works without WordPress around the theme) configured with $grey-950 menu, $primary highlight/buttons/links (HEX values, a configured @use cannot load the settings) |
| `login.scss` | Login screen |
| `plyr.scss` | Video player, loaded by the lightbox only when a video opens |

Layers (`main.scss` includes them in this order, all files of a folder by glob):

| Folder | Files |
| --- | --- |
| `0-settings` | `_!variables` (palette, fonts, breakpoints, container, CSS variables), `_!system-colors` (admin blue), `_!rem-calc` (`rem()`, `clamp-rem()`), `_fonts` (`@font-face`), `_mixins-general` (`hover`, `headings`, `unstyled-list`, `unstyled-button`, `scrollbar`, `text-truncate`, `no-autofill`), `_mixins-media-query` (`min()`, `max()`, `minh()`, `maxh()`) |
| `1-generic` | `1-1-base` (reset, HTML elements, focus, WordPress core classes), `1-2-typography` (headings, lists, `.editor` content styles, editor formats), `1-3-forms` (buttons, fields), `1-4-utilities` (helper classes) |
| `2-layouts` | `_grid` (`.container`, `.content-narrow`, `.grid`), `_header`, `_navigation`, `_footer` |
| `3-vendors` | Swiper, GLightbox, Nice Select (+ plugin styles from add-ons) |
| `4-builder` | `_!module` (shared) + one file per module |
| `5-components` | accordion, tabs, toggle, modal, leave notice, post card, pagination, breadcrumbs, search form, socials, to-top, image placeholder |
| `6-templates` | page and template specific styles (`_page`, `_404`; per project `_single-{cpt}`, `_archive-{cpt}`...) |

Conventions (details: `.claude/rules/scss.md`):

* Units: `rem()` / `clamp-rem()` instead of px (px only for grid and 1px borders).
* Colors, fonts, radius, transitions only through `0-settings` variables; every color is a HEX value with its own variable.
* Breakpoints: `xs` 0, `md` 640, `lg` 1025, `xl` 1440 (`$grid-breakpoints`, same values in `assets/js/_vars.js`).
  Media queries only with the mixins; an unknown name stops the build.
* BEM. Nested elements chain the name: `.m-faq__list` > `.m-faq__list-item` > `.m-faq__list-item-title`. An element
  with its own children starts a short base (`.content__title`), styled only inside its block.
* Hover only through `@include hover` (no sticky hover on touch screens). Icon sizes only in SCSS.
* Buttons: `btn` + type (`btn-outline`) + color (`btn-primary|white|black`; one brand color, no secondary) + size (`btn-sm|lg`).
* Grid: `.grid .grid-{1-6} .grid-{md|lg|xl}-{1-6}`, gap `--grid-gap`. Other layouts: own CSS grid in the module.
* The header is fixed: `main.site-content` has `padding-top: var(--header-height)`. A hero under the header:
  `.site-content:has(> .m-hero:first-child) { padding-top: 0; }` in its module styles.
* CSS is prefixed and minified by LightningCSS for the browsers in `browserslist`.

## JavaScript

```
assets/js/
  main.js          Webpack entry: imports components, registers modules
  _vars.js         breakpoints (keep in sync with SCSS)
  components/      small scripts, always loaded (inside main.js)
  modules/         bigger scripts, separate files, loaded only on pages that need them
  functions/       helpers to import
  vendors/         libraries not on npm (jquery.nice-select)
  copy/            files copied to dist/js/ as they are (admin.js = wp-admin: automatic H1/H2 marker on the Page Builder title tag; plyr.min.js)
```

| File | Purpose |
| --- | --- |
| `components/_custom.js` | Small site-wide snippets (Nice Select on all `<select>`) |
| `components/adminbar.js` | Front-end admin bar hidden above the page, opens on a click on the site name |
| `components/header-scrolled.js` | `body.is-scrolled` after 70px |
| `components/navigation.js` | Mobile menu (`body.is-menu-open`), main menu toggles (desktop: level 1 hover/focus/click, deeper levels accordion; mobile: accordion), `aria-expanded` |
| `components/to-top.js` | To-top button |
| `components/accordion.js` | `.js-accordion > .js-accordion-item > button.js-accordion-title + .js-accordion-content` |
| `components/tabs.js` | `.js-tabs`, `button.js-tab-title[data-item="id"]` + `.js-tab-content#id` |
| `components/toggle.js` | `.js-toggle[data-item="id"]` + `.js-toggle-content#id` |
| `components/modal.js` | `a.js-modal-open[data-modal="id"]` + `.modal#id`, closes with button, overlay, Escape; from other scripts: `$(document).trigger('modal:open', [id, opener])` |
| `components/leave-notice.js` | Leave-site popup (`template-parts/leave-notice.php`, in the footer) before links to the original sites (`wortmann.de`, `wortmann.com.ua` + subdomains): texts from Theme Settings > Leave Popup, "Перейти на сайт" keeps the link's target. Not for Ctrl / Cmd / Shift / middle clicks |
| `modules/slider.js` | Swiper for `.swiper` (only the used Swiper modules) |
| `modules/lightbox.js` | GLightbox for `[data-lightbox]` (images, YouTube/Vimeo, video files with Plyr; same value = one gallery, empty = single item without prev / next) |
| `modules/hero.js` | Hero module slider (`.m-hero`): Swiper with arrows, dots, autoplay 5s (off with reduced motion), loop when more than one slide |
| `modules/partnership.js` | Partnership module logo rows (`.m-partnership`): 2 Swipers in continuous linear movement (bottom row mirrored = other direction), no autoplay with reduced motion |
| `functions/load-modules.js` | Loads modules when the page contains their selector: after load when idle, or on first interaction |
| `functions/breakpoint.js` | `minWidth('lg')` |
| `functions/throttle.js` | `throttle(fn, delay)` |
| `functions/equal-height.js` | `equalHeight('.selector')` |

* New component: file in `components/` + an import in `main.js`. New module: file in `modules/` + a line in
  `loadModules({...})` in `main.js`: `'.selector': () => import(/* webpackChunkName: "name" */ './modules/name'),`
* Every page gets only `main.js` (~11 KB, ~4 KB gzipped) + jQuery. Elements in the first screen that depend on a module
  must look right before it runs (no layout shift).
* Interactive elements are `<button type="button">` and the scripts keep their ARIA states (keyboard and screen reader support).

## PHP and templates

Details: `.claude/rules/php.md`.

* Escape all output (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`); AJAX handlers check the nonce and capabilities.
* ACF functions are called directly (required plugin). Optional plugins (WooCommerce, WPML...) are guarded with `class_exists()` / `function_exists()`.
* Template formatting: an element with a class around PHP output takes 3 lines; an element without a class stays on one
  line; `<svg><use xlink:href="#id"></use></svg>` on one line, without width/height.
* Clickable: link -> `<a href>`, action on the page -> `<button type="button">`, never a clickable `<div>`.
* Comments: off (`inc/disables/comments.php`), no comments templates.

## Images, icons, fonts

* Only **SVG** (icons, logos) and **WebP** (everything else), in the theme and in the Media Library. Convert and
  compress before adding (Claude Code: `/optimize-images`): WebP quality 100 (90 above 1 MB), max 2560px wide.
* Media Library images: `wp_get_attachment_image( $id, 'full', false, [ 'class' => 'img-cover' ] )`. Theme images: `IT_IMG . 'name.svg'`.
* Lazy-loading: native WordPress `loading="lazy"`, no script. Logo: `'loading' => 'eager'`; hero / main image: `+ 'fetchpriority' => 'high'`.
* Icons: sprite `template-parts/svg.php` (search, arrows, angles, play, location, clock, phone, email, globe, close, social icons). New icon = new `<symbol>`.
* Fonts: `.woff2` / `.woff` in `assets/fonts/`, `@font-face` in `0-settings/_fonts.scss` (`font-display: swap`).
  This project: **Inter** 300 / 400 / 700 (text; 300 = quote author, `$font-text`, `$font-alt`) + **Montserrat** 700 / 900 (headings and module titles, `$font-heading`),
  latin + cyrillic subsets (each loads only for its characters, Ukrainian incl. ґ), from Fontsource 5.3.0, SIL Open Font License 1.1. Fallback: Arial.

## Optional features and add-ons

`.claude/optional-features.md` is the checklist for a new project. Rarely used features are kept outside the theme in
`.claude/addons/<name>/` (`files/` = files to copy into the theme, `README.md` = steps and code snippets):

| Add-on | Adds |
| --- | --- |
| `contact-form-7` | CF7 styles, form events script, filters |
| `gravity-forms` | Gravity Forms styles and filters (not asked at project start, only on request) |
| `wpml` | Language switcher in header and mobile menu |
| `woocommerce` | Template overrides, `inc/woo.php`, shop styles, Select2 styles, JS module, mini cart |
| `gitlab-ci` | `.gitlab-ci.yml`: build + FTP deploy (not asked at project start, only on request) |

AJAX (`inc/ajax.php` + `components/ajax.js`) was removed in this project (not needed).

## Quality checks

After every change: `php -l` on edited PHP files, `npm run lint`, `gulp prod`, `bash .claude/tests/run.sh`.

The tests (`.claude/tests/`) run without WordPress (stubs): SVG sanitizer and upload filters, helper functions,
ACF setup, the headings component, JS helpers. New helper or component = new test.

## Deployment

Branches: `develop` = local work (no `dist/`, it is git-ignored), `master` = what is pushed to GitHub and deployed.
Never merge `master` back into `develop` (it would bring the built `dist/` along).

**Release** (on `develop`, everything committed): `npm run release` (`release.mjs`) runs `gulp prod`, merges `develop` into
`master` in a temporary git worktree, commits the built `dist/` there (only `master` tracks it) and pushes `master`.
This folder stays on `develop`; no build happens on the server.

**Dev site (Hostiq, cPanel Git Version Control)** only copies: cPanel pulls `master`, `.cpanel.yml` runs `deploy.sh`, which copies
the theme to `~/public_html/wp-content/themes/terra/` with `rsync --delete` (files there that are not in the theme are removed),
without the files in `deploy-exclude.txt` (sources, dot-folders, configs, build tools). No Node.js needed on the server.

One-time setup in cPanel:
1. **Read access to the private GitHub repo**: Terminal (or SSH) `ssh-keygen -t ed25519 -f ~/.ssh/github_terra -N ""`, then
   `~/.ssh/config`: `Host github.com` / `IdentityFile ~/.ssh/github_terra`. GitHub > repo Settings > Deploy keys > add
   `~/.ssh/github_terra.pub` (read-only).
2. cPanel > Git Version Control > Create: clone URL `git@github.com:Tim-Alex-Dev/terra.git`, repository path
   `~/repositories/terra`, branch `master`.

Each deploy: `npm run release`, then Git Version Control > Manage > Pull or Deploy > **Update from Remote** > **Deploy HEAD Commit**.
Deploy log: `~/.cpanel/logs/vc_*_git_deploy.log`. Database content (pages, ACF data, settings) is not part of git.

Automatic build + FTP deploy with GitLab CI (other projects): `.claude/addons/gitlab-ci/`.

## Known open points

* `npm audit`: findings only in dev tools (the `braces` advisory in gulp, BrowserSync, Stylelint), nothing ships to the
  site; no fixed version yet. Never run `npm audit fix --force` (it downgrades gulp to 3).
* Sass `@import` is deprecated (warning silenced); move to `@use` before Dart Sass 3.
* Plyr: after updating the npm package, copy `node_modules/plyr/dist/plyr.min.js` to `assets/js/copy/` and `plyr.svg`
  to `assets/img/` (the CSS follows automatically).
