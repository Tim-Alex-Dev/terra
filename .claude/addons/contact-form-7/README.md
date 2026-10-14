# Add Contact Form 7 support to a project

Contact Form 7 is not part of the starter. This folder keeps everything needed to add it. `.claude/` is never built or deployed.

1. Copy everything from `files/` into the theme root (same paths): `assets/scss/3-vendors/_contact-form7.scss` (included by the
   `3-vendors/*` glob), `assets/js/components/cf7-events.js` (CF7 DOM events, examples inside).
2. Add the snippets below.
3. Verify: `php -l functions.php`, `npm run lint`, `gulp prod`.

## functions.php (top, after the file comment): no automatic <p> tags, no CF7 stylesheet (the theme styles the forms)
```php
add_filter( 'wpcf7_autop_or_not', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );
```

## assets/js/main.js (with the other component imports)
```js
import './components/cf7-events'; // Custom Contact Form 7 scripts
```
