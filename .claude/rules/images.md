# Images and icons

Applies everywhere: theme files (`assets/img/`) and the WordPress Media Library.

## Allowed formats

- **SVG**: icons, logos, other vector graphics.
- **WebP**: every other image (photos, illustrations, backgrounds).
- **JPG**: only in `assets/img/acfe-thumbnails/` (see below). No PNG, JPG, GIF anywhere else.

## Before an image is added (theme or WP admin)

1. Convert: raster -> WebP, icons -> SVG.
2. Minimize: run `/optimize-images` (local sharp + svgo tool in `D:\Work\tools\images\`): WebP quality 100, or 90 if the file
   would be > 1 MB (automatic), max width 2560px (never upscaled), transparency kept, SVG optimized with svgo.
3. Clean file names: lowercase, words separated by `-`, no spaces (`Hero Photo.png` -> `hero-photo.webp`).
4. The user's original files are never changed or deleted.

Never add an image in another format as is. WordPress itself still accepts JPG/PNG uploads, so this is a working rule:
when asked to upload or add images, convert and minimize them first.

## `assets/img/`

- Theme images and icons, copied to `dist/img/` by gulp. Only `.svg` and `.webp` (+ `acfe-thumbnails/`).
- `plyr.svg` is the Plyr video player icon sprite (copy of `node_modules/plyr/dist/plyr.svg`, used by the lightbox video player, see `.claude/rules/js.md`):
  keep it as is (do not run svgo on it, it would drop the unused symbols) and update it together with Plyr.
- `acfe-thumbnails/`: only `.jpg`, 400x320px, one preview per Flexible Content layout, named exactly like the layout
  (`{layout name}.jpg`, e.g. `text_media.jpg` for layout `text_media`). Registered automatically by `inc/acf.php`
  (`acfe/flexible/thumbnail/layout=...`). Create with `/optimize-images --thumb`. Every new builder layout gets one.

## Using images in code

- Theme images: `esc_url( IT_IMG . 'name.webp' )` (`IT_IMG` = `dist/img/` URL, defined in `functions.php`). Always set `alt`.
- Media Library images, this pattern (classes in the attributes array; WordPress adds srcset, sizes and alt):
  `<?php echo wp_get_attachment_image( $image_id, 'full', false, [ 'class' => 'img-cover' ] ); ?>`
  Size: `full` in most cases. A smaller registered size is fine when the image box is known to be small
  (e.g. box max ~400px wide -> `medium`, as for the logo in `header.php` / `footer.php`); no strict rule. ACF image fields return the ID.
- Lazy-load: WordPress lazy-loads images and iframes natively (`loading="lazy"`, also in `the_content` and ACF WYSIWYG); no theme code or script.
  Above-the-fold images (logo, hero) are loaded at once: `wp_get_attachment_image( $id, 'full', false, [ 'loading' => 'eager', 'fetchpriority' => 'high' ] )` (logo: `'loading' => 'eager'` only).
- Frequently used icons: SVG sprite `template-parts/svg.php` (printed in the footer), used as `<svg><use xlink:href="#id"></use></svg>`.
  New icon = new `<symbol id="...">` there. Existing: search, arrow-left/right, angle-up/down/left/right, close,
  facebook, instagram, linkedin, twitter (X logo; id kept = Socials field value), xing, youtube. Social icon id = Socials field value.
- Inline an SVG file (uploads or theme): `it_inline_svg( $url )`. Missing image: `it_image_placeholder()`.
- Custom image sizes: `add_image_size()` in `inc/after-theme-setup.php`.

## SVG uploads (security)

- `inc/svg-support.php` allows SVG uploads for administrators only (`manage_options`, filter `it_svg_upload_capability`)
  and sanitizes every uploaded/sideloaded SVG (scripts, event handlers, external links removed). Never weaken or bypass it.
