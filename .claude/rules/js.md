---
paths:
  - "assets/js/**"
---

# JavaScript rules

## Structure

| Where | What | Loaded |
|---|---|---|
| `main.js` | Webpack entry: imports every component, holds the `loadModules({...})` list | always (only file enqueued in PHP) |
| `components/` | small scripts used on every page (menu, header, accordion, tabs, modal, toggle, to-top, ajax) | always, inside `main.js` |
| `components/_custom.js` | small site-wide snippets and inits too small for their own file (Nice Select) | always, inside `main.js` |
| `modules/` | bigger scripts: libraries (slider = Swiper, lightbox = GLightbox), scripts of builder modules (WooCommerce comes from its add-on) | on demand, separate files in `dist/js/modules/` |
| `_vars.js` | core project variables for JS (breakpoints; more can be added later) | imported where needed |
| `functions/` | helpers imported where needed (`throttle`, `equalHeight`, `minWidth`, `loadModules`) | bundled where imported |
| `vendors/` | third-party files not available on npm (nice-select) | where imported |
| `copy/` | standalone files copied to `dist/js/` as they are: `admin.js` (wp-admin), `plyr.min.js` (video player for the lightbox) | `admin.js` enqueued in PHP; `plyr.min.js` loaded by GLightbox when a video is opened |

- Every script lives in a named file in the right folder. Small + site-wide -> `components/` (a few lines -> `components/_custom.js`, a self-contained piece -> its own component file);
  heavy or needed only on some pages -> `modules/`; reusable helper -> `functions/`.
- New component: file in `components/` + one import line in `main.js`.
- New module: file in `modules/` + one line in the `loadModules({...})` list in `main.js`:
  `'.css-selector': () => import(/* webpackChunkName: "name" */ './modules/name'),`
  A builder module script is named like its layout (`modules/faq.js` for `template-parts/builder/faq.php`, selector `.m-faq`).
- Modules initialise themselves on import (`jQuery(document).ready(...)` runs at once when the DOM is ready).

## Loading strategy (page speed)

- First render gets only `main.js` (~10 KB) + jQuery. Images and iframes are lazy-loaded natively by the browser (no script).
- `functions/load-modules.js` loads a module only if the page contains its selector, after the `load` event when the browser is idle
  (`requestIdleCallback`), or earlier on the first scroll / click / touch / keydown. Pages never download modules they do not use.
- Elements in the first screen that depend on a module (e.g. a hero slider) must look right before the module runs:
  style the first slide/state with CSS so nothing jumps (no layout shift).
- Shared libraries are split by Webpack automatically (e.g. Swiper used by two modules is downloaded once).
- Module files have a content hash in their name (cache busting); `gulp prod` cleans old files.

## Code rules

- jQuery stays (the user's choice): it is provided by WordPress (`theme-js` depends on it) and is the default way to write theme JS.
  Use the `jQuery(document).ready(function ($) { ... })` pattern used in the components. Do not propose removing jQuery or rewriting code to vanilla JS.
- No browser/OS sniffing (user-agent checks) and no JS viewport-height hacks: use CSS (`100dvh`, feature queries) instead.
- Breakpoints have two sources on purpose (JS must not depend on the CSS being loaded, e.g. with CSS-delaying speed plugins):
  `assets/js/_vars.js` (`bp`) and `$grid-breakpoints` in SCSS. When one changes, change the other too.
  In JS check them with `minWidth('lg')` from `functions/breakpoint.js` (matchMedia, measures like CSS incl. scrollbar);
  never hard-code breakpoint numbers and never use `$(window).width()` for breakpoint checks.
- Front-end settings from PHP are in `itSettings` (`ajaxUrl`, `nonce`). Do not use the name `wpApiSettings` (WordPress core uses it).
- Swiper: `import Swiper from 'swiper'` and only the needed modules from `swiper/modules`, passed in the `modules` option. Keep `A11y` for screen reader support.
- Lightbox = GLightbox (`modules/lightbox.js`, MIT): markup `<a href="..." data-lightbox="gallery-name">` (same value = one gallery,
  empty = single item; images, YouTube/Vimeo, mp4). Never use `data-fancybox`. Videos use Plyr, which GLightbox loads itself only when a video is
  opened, from the theme: `copy/plyr.min.js` (copy of `node_modules/plyr/dist/plyr.min.js`), `scss/plyr.scss` -> `dist/css/plyr.css`, `img/plyr.svg`.
  Never from a CDN (GDPR: no third-party requests). When Plyr is updated (npm), copy `plyr.min.js` and `plyr.svg` again.
- Components with interaction keep their ARIA states in sync (aria-expanded, aria-selected) and support the keyboard (real <button>s, Escape closes the modal).
- Never leave `console.log` in committed code (`console.error` for real errors is fine). Use passive listeners for scroll handlers.
- Verify: `npm run lint:js`, `gulp prod` (check the `dist/js/modules/` output), `bash .claude/tests/run.sh` (tests helpers in `functions/`).
