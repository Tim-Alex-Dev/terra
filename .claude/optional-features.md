# Optional features (new project checklist)

The starter contains what every project needs; rarely used features live in `.claude/addons/` (not built, not deployed).
When a new project is created (`/new-project`), ask the user about every feature in **Ask** below, in one message, one short question each:
- feature **in the starter** -> remove it if not needed (file list below);
- feature **in an add-on** -> add it if needed (follow `.claude/addons/<feature>/README.md`: copy `files/` into the theme root + the snippets).

Never ask about **Always kept**. This list grows while we go through the starter: add new optional features here.

## Always kept (do not ask)

Accordion, tabs, modal, toggle, navigation, header-scrolled, to-top, Nice Select, slider (Swiper), lightbox (GLightbox),
`copy/admin.js` (rarely used, kept just in case).

## Ask

### AJAX — "Will the site use custom AJAX requests?" (in the starter)
Remove if no:
- `assets/js/components/ajax.js` + its import line in `assets/js/main.js`
- `inc/ajax.php` + its `require` line in `functions.php`
- keep `itSettings` (`ajaxUrl`, `nonce`) in `inc/scripts-styles.php` (harmless, other scripts may use it)

### Contact Form 7 — "Will the site use Contact Form 7?" (add-on, the usual form plugin)
Add if yes: `.claude/addons/contact-form-7/README.md` (styles, CF7 events script, two filters in `functions.php`).

### WPML — "Will the site be multilingual (WPML)?" (add-on)
Add if yes: `.claude/addons/wpml/README.md` (language switcher widget area, header/mobile menu markup and styles, dashboard widget).

### WooCommerce — "Is this a shop (WooCommerce)?" (add-on)
Add if yes: `.claude/addons/woocommerce/README.md` (template overrides, `inc/woo.php`, styles, Select2 styles, JS module, mini cart,
admin styles, `rules-woocommerce.md` -> `.claude/rules/woocommerce.md`).

## Not asked (add only when the user asks for it)

- Gravity Forms: `.claude/addons/gravity-forms/README.md` (styles, spinner filter, admin bar, dashboard widget).
- GitLab CI/CD: `.claude/addons/gitlab-ci/README.md` (`.gitlab-ci.yml` build + FTP deploy, CI variables, checks before the first run).

After removing or adding: `php -l` the edited PHP files, `npm run lint`, `gulp prod`, `bash .claude/tests/run.sh`.
