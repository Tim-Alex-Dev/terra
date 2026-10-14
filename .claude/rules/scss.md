---
paths:
  - "assets/scss/**"
---

# SCSS rules

- Structure is ITCSS (numbered folders, globbed by `main.scss`). New builder layout styles go to `4-builder/_name.scss`, components to `5-components/`, page specific to `6-templates/`.
- Icon sizes (`<svg>`) are set only in SCSS (`width`, `height` in `rem()`, per breakpoint if needed), never as HTML attributes.
- BEM class names. Use `rem()` / `clamp-rem()` instead of px (exceptions: grid, 1px borders).
  Nested elements chain the parent element's name (one `__`, then `-`): `.m-faq__list` > `.m-faq__list-item` >
  `.m-faq__list-item-title`, `.m-faq__list-item-content`; in SCSS nested with `&-` (see `4-builder/_faq.scss`).
  The chain stops when an element gets its own children (title, icon, description...): its last word becomes a new short
  base for them, `.m-faq__list-item-content` > `.content__title`, `.content__icon`, `.content__description`.
  These short classes are styled only inside their block (`.m-faq { .content__title {...} }`), never globally, so the
  same names in other modules do not clash.
- Media queries only through the mixins `min()`, `max()`, `minh()`, `maxh()` with the breakpoints in `$grid-breakpoints` (`xs`, `md`, `lg`, `xl`) or a pixel number. An unknown name (e.g. `sm`) stops the build. Keep `$grid-breakpoints` in sync with the JS breakpoints in `assets/js/_vars.js` (two sources on purpose).
- Sass modules, not global functions: `@use "sass:map"` then `map.get`, `list.nth`, `math.div`.
- `@import` is still used (deprecation silenced in `gulpfile.js`); do not start a partial `@use` migration of the ITCSS files, only add `@use "sass:..."` lines at the top of a partial.
- Swiper 14 CSS is imported from node_modules; override its look with Swiper CSS variables (`--swiper-*`) because its selectors are more specific than plain overrides.
- Verify: `npm run lint:scss`, `gulp prod`. For refactors compare the compiled `dist/css/main.css` before and after.

## 0-settings

Settings only: variables, functions, mixins, `@font-face` and `:root` CSS variables. No selectors or element styles here
(the only CSS it outputs is `:root` and `@font-face`). Loaded first; files that others depend on start with `_!` (the glob loads them first).

| File | Contents |
|---|---|
| `_!system-colors.scss` | `$system-color`, `$system-color-hover` (own file so `admin-styles.scss` can import only them) |
| `_!rem-calc.scss` | `rem()` (px -> rem, 1–4 values), `clamp-rem($min, $max)` fluid sizes between the `md` and `xl` breakpoints |
| `_!variables.scss` | colors, fonts, typography, radius, transition, breakpoints, grid/container, `:root` CSS variables |
| `_fonts.scss` | `@font-face` rules of the project fonts (woff2 + woff from `assets/fonts/`, `font-display: swap`) |
| `_mixins-general.scss` | multi-line helpers: `text-truncate`, `headings`, `no-autofill`, `hover`, `scrollbar`, `unstyled-button`, `unstyled-list` |
| `_mixins-media-query.scss` | `min()`, `max()`, `minh()`, `maxh()` |

- **Colors**: every color is a HEX value with its own variable, no color functions (no `darken()`, `lighten()`, `color.adjust()`).
  No global hover colors: each element (link, button, icon...) sets its own hover color in its styles.
  Starter palette: `$primary`, `$secondary`, `$black`, `$white`, `$grey`, `$red`, `$green`. Each project replaces/extends it with its own colors.
  Global text colors `$color-text`, `$color-heading`, `$color-link`, `$color-link-hover` always exist (placeholders in the starter, set per project).
  System colors `$system-color` (#2271b1) / `$system-color-hover` (#135e96) in `_!system-colors.scss`: neutral UI blue for the focus outline,
  admin bar, admin buttons (ACF/ACFE in `admin-styles.scss`), not part of the project palette.
  Hard-coded colors only where `0-settings` is not loaded (e.g. `admin-styles.scss`, `login.scss`, inline SVG data URIs); everywhere else use a variable
  (`main.scss` and `editor-styles.scss` load `0-settings` first). Never hard-code a color in a component.
- **Fonts**: exactly three variables `$font-heading`, `$font-text`, `$font-alt` (extra font for selected elements only). Code/pre use `monospace`.
- **Design values**: `$radius` (8px), `$time` (0.5s) and `$default-transition` (`all $time 0s`); use them instead of literal values.
- **Mixins**: only for multi-line patterns. One-line things are plain CSS (`transition: $default-transition;`, `&::placeholder {}`, `::selection {}`).
  Hover styles through `@include hover { ... }` (`@media (hover: hover)`: only devices that can hover, no sticky hover on touch screens).
  No vendor prefixes by hand: LightningCSS adds them from `browserslist`.
- **Layout values**: `$grid-breakpoints`, `$grid-gutter-width` (grid gap), `$container-max-widths`, `$container-gutter-x-values`, `$container-fluid-max-width`
  feed `2-layouts/_grid.scss` (through the `--container-width` / `--container-gutter-x` CSS variables in `:root`).

## 1-generic / 1-1-base

Foundation layer, in this order: `_!sanitize.scss` (reset) -> `_elements.scss` (bare HTML tags) -> `_wp-core.scss` (WordPress core classes).
Only bare tags and WordPress core classes here, never classes of our components.

- `_!sanitize.scss`: modern reset (useful parts of sanitize.css 13 + modern-normalize). No fixes for dead browsers (IE, old Edge), no styles for
  obsolete HTML tags (`big`, `acronym`, `tt`...). Opinionated rules are marked `// opinionated`.
- Focus must stay visible for keyboard users: never remove outlines globally. `:focus:not(:focus-visible)` has no outline, `:focus-visible`
  shows a `$system-color` outline. Components may restyle `:focus-visible`, but never hide it.
- Respect `prefers-reduced-motion` for page-wide motion (smooth scroll).
- No minimum body width: the layout must work from 320px.
- Values from 0-settings (`rem()`, `$radius`, `$time`, colors). Exception: the WordPress admin bar keeps WordPress' own sizes (46px / 32px, breakpoint 782/783px).
- Transitions name their properties (`transition: fill $time, ...`), no `transition: all` on global selectors.
- Video embeds (YouTube/Vimeo iframes) scale with `aspect-ratio: 16 / 9`, not with calculated heights.
- WordPress classes kept for WYSIWYG content: alignments, captions, galleries. Emoji are disabled by the theme, so no `.wp-smiley` styles.

## 1-generic / 1-2-typography

Global typography: headings, lists, WYSIWYG formats. No table styles in the starter (add them per project if needed).

- `_headings.scss`: `h1`–`h6` and the matching classes `.h1`–`.h6` (same look on any tag). Fluid sizes with `clamp-rem($min, $max)`
  (md 640px -> xl 1440px). Headings use `$font-heading`, `$color-heading`, `text-wrap: balance`.
- `_lists.scss`: default `ul`/`ol`/`dl` + custom lists `.list-check`, `.list-dot`, `.list-number` (offered in the editor, `inc/editor.php`).
  Icons/markers are colored with `$primary`; icons from SVG are drawn with `mask` + `background-color` so their color comes from a variable.
- `_editor.scss`: WYSIWYG style formats (`.text-sm`, `.text-lg`, `.blockquote`) and `.editor`, the wrapper of formatted
  content (post content, ACF WYSIWYG fields: `<div class="editor">`). `%link` is the shared "simple text link" style
  (`$color-link` / `$color-link-hover`), extended by `.editor a:not([class])` and plugin links (e.g. the WooCommerce add-on).
- A class offered in the editor formats (`inc/editor.php`) must exist in the SCSS, and vice versa.
- Pseudo-elements with double colons (`::before`, `::after`).

## 1-generic / 1-3-forms

### Buttons (`_buttons.scss`)

Class = `btn` + type + color (+ size). Every button follows this logic, no combined classes like `btn-primary-outline`.

| Part | Classes |
|---|---|
| base | `.btn` (alone = solid primary) |
| type | solid (default, no class), `.btn-outline` |
| color | `.btn-primary`, `.btn-secondary`, `.btn-white`, `.btn-black` |
| size (optional) | `.btn-sm`, `.btn-lg` |

Example: `<a class="btn btn-outline btn-secondary btn-lg">`.
- Color classes only set `--btn-color`, `--btn-text`, `--btn-hover`, `--btn-hover-text`; type classes use these variables, so every type works
  with every color. New color = new class with the four variables (values from `0-settings`); new type = new class that uses them.
- Hover through `@include hover`, plus the same look on `:focus-visible` and `:active`. Disabled: `:disabled` / `[aria-disabled="true"]`.
- `input[type="submit"]` and plugin buttons (add-ons: CF7, WooCommerce...) reuse the look with `@extend .btn` (`.btn-sm`, `.btn-lg`).
- Button formats in the editor (`inc/editor.php`) use the full class set (`btn btn-outline btn-primary`).

### Fields (`_fields.scss`)

- `%field-input` is the text-field look, applied to all text-like inputs and `textarea`, reusable with `@extend %field-input` (plugin forms).
  Range, color, file inputs are not text fields: style them separately if needed. Selects are styled by Nice Select.
- States: hover (`@include hover`), `:focus` (border), `:user-invalid` (`$red` border, only after interaction), `:disabled`.
- Textarea: `min-height` + `field-sizing: content` (grows with the text in supporting browsers).
- Checkbox / radio: custom look with `appearance: none`; data-URI icons may keep a hard-coded color (`fill='%23fff'`).

## 1-generic / 1-4-utilities

Small helper classes, used in markup and by JS. Prefer real component styles; helpers are for small, repeated needs.

- `_help-classes.scss`: only a few general helpers: `.img-cover` (fills a positioned parent), `.img-contain`,
  `.screen-reader-text` (WordPress accessibility class, visible on focus), `.disabled`, `.d-none`, `.overflow-hidden` (used by JS). More are added per project when needed.
- No generated helper classes for visibility or colors. Show/hide per breakpoint in the component styles with `@include min()` / `max()`
  (e.g. `.icon-burger { @include min(lg) { display: none; } }`); text/background colors in the component styles with palette variables.
  Simple color classes (`.bg-white`, `.bg-black`, ...) are added by hand only when a project needs them.
- No shared animations file: keyframes live with the component that uses them.
- A helper class used in PHP/JS must exist in the SCSS (a missing class fails silently).

## 2-layouts / _grid.scss (no CSS framework)

No Bootstrap or other CSS framework. Layout is our own small code:
- `.container`: the space of a module: centered, `max-width: var(--container-width)`, horizontal padding `var(--container-gutter-x) / 2`.
  `.container-fluid`: same padding, max width `$container-fluid-max-width`. Every module: `<section class="module m-name"><div class="container">`.
- `.content-narrow`: centered column, 75% of the container from lg (page/post content).
- `.grid` + `.grid-{1-6}` + `.grid-{md|lg|xl}-{1-6}`: equal columns, mobile first (`grid grid-2 grid-lg-4`). Gap `$grid-gutter-width`,
  per grid `--grid-gap`. Consecutive `.grid` blocks get the gap as top margin.
- Not equal columns (2/3 + 1/3, 5:7, reverse order, "all in one row"...): own CSS grid in the module styles
  (example in add-ons: `.checkout__grid` (5:7)).
- Never use Bootstrap classes (`row`, `col-*`, `row-cols-*`, `justify-content-*`...): they do not exist.

## 2-layouts / header, navigation, footer

- `_header.scss`: fixed `.site-header` (height `--header-height`, `--header-scrolled-height` when `body.is-scrolled`),
  `.site-logo`, `.header-lang`, `.icon-burger` (a `<button>`, hidden from lg). `main.site-content` gets `padding-top: var(--header-height)`
  (in `_header.scss`, the full height also when the header shrinks on scroll: no content jump). Fixed (not sticky) on purpose: a project's hero
  can slide under it with `.site-content:has(> .m-hero:first-child) { padding-top: 0; }` in its module styles (no PHP needed).
- `_navigation.scss`: `.main-nav` = full-screen mobile menu below lg (`body.is-menu-open`), inline menu from lg. `.main-menu` items, sub-menus:
  mobile = toggled by JS (`navigation.js`, `.active`), desktop = open on hover **and** on `:focus-within` (keyboard users must reach sub-items).
  The sub-menu chevron is a `mask` colored with `$primary`.
- `_footer.scss`: `.site-footer` (logo, `.footer-links` menu, socials, `__copyright`).
- Header/menu states come from body classes set by JS: `is-scrolled`, `is-menu-open`. Keep these names.

## 3-vendors

Styles of third-party libraries used in every project: `_swiper-bundle.scss` (Swiper 14 CSS + `--swiper-*` overrides), `_glightbox.scss`
(GLightbox CSS + `[data-lightbox]`), `_nice-select.scss`. Plugin styles (CF7, Gravity Forms, WPML, WooCommerce + Select2) are not in the starter:
they come with their add-on (`.claude/addons/<feature>/files/assets/scss/3-vendors/...`) and are included by the `3-vendors/*` glob once copied.

## 4-builder

Only module styles: one file per ACF module (`_{layout-kebab}.scss`, BEM block `.m-{layout-kebab}`) + `_!module.scss`
(styles shared by all modules: `.module` spacing / `--padding`, `.module-headings` = headings component, optional `.module-header` wrapper (only with extra elements, takes the spacing)
with `.alignment-left|center|right`, `__uptitle`, `__title` (`@extend .h2`: looks like h2 with any tag), `__subtitle`). The starter has `_faq.scss`. Full module rules: `.claude/rules/acf-builder.md`.
Components used inside modules (accordion, tabs...) are styled in `5-components/`.

## 5-components

Reusable UI pieces, used by modules, posts and pages (not module-specific): accordion, tabs, toggle, modal,
post card, image placeholder, pagination, breadcrumbs, search form, socials, to-top. One file per component.

- Interactive parts are real `<button>`s (accordion titles, tab titles, modal close), never clickable `<div>`/`<span>`, so keyboard users can
  reach them. Reset the browser button look with `:where(button.js-...)` (no specificity: module styles like `.m-faq__list-item-title` still win).
- The JS sets the ARIA states (`assets/js/components/`): accordion `aria-expanded`, tabs `role`/`aria-selected`, toggle `aria-expanded`/`aria-controls`;
  modal: `role="dialog" aria-modal="true"` on `.modal__content` in the markup, closes with the close button, the overlay or Escape, focus
  moves into the modal and back to the opener.
- Markup patterns (JS hooks `js-*`, states `is-open` / `is-active`):
  accordion `.js-accordion > .js-accordion-item > button.js-accordion-title + .js-accordion-content`;
  tabs `.js-tabs > .tabs__titles > button.js-tab-title[data-item="id"]` + `.js-tab-content#id` (unique ids per page);
  toggle `a.js-toggle[data-item="id"]` + `.js-toggle-content#id`; modal `a.js-modal-open[data-modal="id"]` + `.modal#id`.
- Small global helpers (`.stretched-link`, `.img-cover`...) are in `1-4-utilities/_help-classes.scss`, not here.

## 6-templates

Styles of WordPress templates, one file per template, named like its PHP template (WordPress template hierarchy):
- in the starter: `_page.scss` (`page.php`: `.site-content`, `.entry-title`, `.entry-content`), `_404.scss` (`404.php`: `.not-found`).
- per project: single CPT `single-{cpt}.php` -> `_single-{cpt}.scss`, CPT archive `archive-{cpt}.php` -> `_archive-{cpt}.scss`,
  unique page template `page-{name}.php` / `templates/{name}.php` -> `_page-{name}.scss`.
- Scope the styles to the template: with its own wrapper/BEM block, or the WordPress body class (`.single-{cpt}`, `.post-type-archive-{cpt}`,
  `.page-template-{name}`, `.error404`), so they never leak into other pages.
- No blog/archive/search styles in the starter (`index.php`, `search.php`, `single.php` use generic classes): add them per project when needed.

## Entry files (`assets/scss/*.scss`, each compiles to `dist/css/{name}.css`)

- `main.scss`: the front end, all layers 0-6 (globbed).
- `editor-styles.scss`: the WYSIWYG editor (`add_editor_style` in `inc/editor.php`): `0-settings` + `1-generic` only (no layouts),
  `body.mce-content-body` extends `.editor` so the editor preview looks like the front end, plus editor-only helpers (`.btn-white` outline,
  "Button Group" label). Every editor format class must also exist on the front end (e.g. `.btn-group` in `_buttons.scss`).
- `admin-styles.scss`: wp-admin (ACF / ACF Extended look); imports only `0-settings/!system-colors` (no theme CSS in the admin).
- `login.scss`: login page logo; no settings loaded (plain values).
- `plyr.scss`: Plyr video player CSS, loaded by GLightbox only when a video is opened.
- The editor text color palette (`inc/editor.php`, `textcolor_map`) uses the palette HEX values: keep it in sync with `_!variables.scss`.
