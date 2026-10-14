---
paths:
  - "**/*.php"
---

# PHP rules

- Prefix functions and hooks with `it_`; translatable strings use the text domain `_it_start` (renamed by `gulp init`).
- Escape late: `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`. Never echo ACF values or `home_url()` raw. Use `esc_url( home_url( '/' ) )`.
- AJAX handlers: `check_ajax_referer( 'ajax-nonce', 'nonce' )` (front-end nonce is `itSettings.nonce`) and a capability check for anything that writes.
- Template whitespace is significant: keep `?> <?php` spacing (use `&nbsp;` or explicit text if a space is needed between tags).
- Markup formatting in templates:
  - An element with a class around PHP output is split into three lines (opening tag, output, closing tag):
    ```php
    <div class="editor">
    	<?php echo wp_kses_post( wpautop( $answer ) ); ?>
    </div>
    ```
  - An element without a class stays on one line: `<span><?php echo esc_html( $question ); ?></span>`.
  - Clickable elements: goes to a URL/anchor -> `<a href>`; does something on the page (open, toggle, close, switch) ->
    `<button type="button">` (keyboard + screen reader support, accessibility audits); never a clickable `<div>` / `<span>`.
  - Inline SVG icons stay on one line and have no `width` / `height` attributes (sizes can differ per device):
    `<svg><use xlink:href="#angle-down"></use></svg>`; the size is set in SCSS.
- ACF Pro and ACF Extended are hard requirements: call ACF functions directly, no `function_exists` guards or fallbacks (they only add weight). Optional plugins (WooCommerce, WPML, Yoast...) must be guarded (`class_exists`, `function_exists`, null checks such as `WC()->cart`).
- Images are lazy-loaded natively by WordPress. Above-the-fold images (logo, hero) get `'loading' => 'eager'` (+ `'fetchpriority' => 'high'` for the hero) in `wp_get_attachment_image()`.
- Check every change with `php -l <file>` and `bash .claude/tests/run.sh`. There is no local WordPress: put pure logic in functions that can be unit tested with stubs (see `.claude/tests/`).
