# WordPress theme (built on WordPress Starter)

Custom theme for this project. Theme name, text domain and deploy folder are in `starter.config.json`
(applied by `gulp init`); the current text domain is the `Text Domain` in the `style.css` header. PHP prefix `it_`.

## Stack

- ACF Pro + ACF Extended Flexible Content "Page Builder" (no Gutenberg). Both are hard requirements.
- SCSS (modified ITCSS + BEM, rem units). No CSS framework: own container + grid in `assets/scss/2-layouts/_grid.scss`.
- JS via Webpack + Babel, bundled by Gulp 5. jQuery is a dependency (`theme-js` requires it).
- Plugin support for Contact Form 7, Gravity Forms, WPML, WooCommerce and the GitLab CI deploy are not in the theme: add-ons in `.claude/addons/` (see below).

## Layout

- `functions.php` defines `IT_*` constants and requires files from `inc/`.
- `inc/` PHP modules (`disables/` holds one file per disabled core feature).
- `template-parts/builder/` one file per ACF layout, `components/` for reusable pieces.
- `acf-json/` ACF field groups (synced from WP admin).
- `assets/scss/` (numbered ITCSS layers, globbed by `main.scss`), `assets/js/` (`main.js` = Webpack entry with `components/` always loaded and `modules/` loaded on demand, see `.claude/rules/js.md`).
- `dist/` build output, gitignored. Never edit by hand.

## Commands (Node >= 20.19, `.nvmrc` = 24)

- `npm i` install dependencies.
- `gulp` dev watcher, `gulp watch` with BrowserSync (`devUrl`), `gulp prod` clean production build (fails on Sass/JS errors).
  Same as `npm start`, `npm run watch`, `npm run build`.
- `gulp init` applies `starter.config.json` (text domain everywhere, style.css, package.json name, `.gitlab-ci.yml` path if the GitLab CI add-on is used); idempotent.
- `npm run lint` (Stylelint + ESLint), `npm run lint:scss:fix`. PHP: `php -l` + the rules in `.claude/rules/php.md` (no PHP_CodeSniffer).
- `SASS_VERBOSE=1 gulp css` shows every Sass deprecation warning.

## Hard rules

- Do not edit `dist/`, `node_modules/`, or `package-lock.json` by hand.
- Escape all output (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`), verify nonces and capabilities in AJAX handlers
  (front-end nonce is `itSettings.nonce`, action `ajax-nonce`).
- Use the theme's text domain (from `style.css`) in translatable strings.
- Prefer `rem()` / `clamp-rem()` over px in SCSS (exceptions: grid, 1px borders). Colors, fonts, radius and transitions only
  through the variables in `0-settings/_!variables.scss` (every color is a HEX value with its own variable, no color functions).
  Use `sass:` modules (`map.get`, `list.nth`, ...) instead of global Sass functions.
- Lazy-loading is WordPress core's native `loading="lazy"` (no lazy-load script). Above-the-fold images (logo, hero) get
  `'loading' => 'eager'` (+ `'fetchpriority' => 'high'` for the main/hero image) in `wp_get_attachment_image()`.
- Swiper 14 ships plain CSS (with native nesting), imported in `3-vendors/_swiper-bundle.scss`; tune it with Swiper CSS variables
  (`--swiper-navigation-size`, `--swiper-navigation-sides-offset`, `--swiper-pagination-bottom`) because Swiper's own selectors are
  more specific than plain overrides. Import only the needed Swiper modules and pass them in the `modules` option (add `A11y`).
- CSS is prefixed/minified by LightningCSS (`gulpfile.js`, targets from `browserslist`); it fails on invalid CSS such as unknown media queries.
- `acf-json/` belongs to the ACF plugin only: never edit, add, rename or delete files there. ACF writes them itself when field groups
  are saved in WP admin. Reading them is fine (to learn field names and layouts). Field changes = tell the user what to change in WP admin.
- Keep edits of PHP tags whitespace-safe: `?> <?php` spacing is significant in templates.
- Never upload dot-folders (`.claude/`, `.git/`) or `node_modules/` to a server (the GitLab CI add-on excludes them automatically).

## Optional features

The starter contains every feature a project might need. `.claude/optional-features.md` lists the optional ones
(AJAX, Contact Form 7, WPML, WooCommerce are asked about when a new project is created; Gravity Forms and GitLab CI only on request);
features in the starter are removed if not needed, plugin support lives in `.claude/addons/<feature>/` (not built, not deployed:
`files/` + `README.md`) and is added if needed.
When you add an optional feature to the starter, add it to that file too.

## Folder rules

- `acf-json/`: ACF plugin only (see Hard rules).
- `assets/fonts/`: only `.woff2` / `.woff`.
- `assets/img/`: only `.svg` / `.webp`; `assets/img/acfe-thumbnails/`: only `.jpg`, one per Flexible Content layout, named `{layout name}.jpg`.
- Images everywhere (theme and WP Media Library): only SVG (icons) and WebP; convert + minimize with `/optimize-images` before
  adding. Full image rules: `.claude/rules/images.md`.
- Details per area in `.claude/rules/`.

## Working here

- Verify after every change: `php -l` on edited PHP files, `npm run lint`, `gulp prod`, `bash .claude/tests/run.sh`.
- Keep the README in sync when behavior or file layout changes.
- Rules for specific areas are in `.claude/rules/`, repeatable workflows in `.claude/skills/`, subagents in `.claude/agents/`.

## Tests

- `bash .claude/tests/run.sh` runs the unit tests (SVG sanitizer, helper functions, ACF setup, builder components, JS helpers).
  Needs php and node, no WordPress. Run before and after changes to `inc/`, `assets/` or the build.
