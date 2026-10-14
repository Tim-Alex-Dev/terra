---
paths:
  - "acf-json/**"
  - "template-parts/builder/**"
  - "template-parts/builder.php"
  - "inc/acf.php"
  - "assets/scss/4-builder/**"
---

# ACF Flexible Content builder (modules)

- `template-parts/builder.php` loops the `builder` field and loads `template-parts/builder/{layout}.php` (a layout without a template is skipped).
- The starter has one module: **FAQ** (almost the same on every site). Every project adds its own (no Hero: heroes differ too much per project).
- `acf-json/` is written only by the ACF plugin (when a field group is saved in WP admin). Never edit, add, rename or delete files there;
  read them only to learn field names. Any field change is a manual step for the user in WP admin (exception only when the user explicitly asks).

## One module = four files with the same name

| What | Where | Name (layout `text_and_media` as example) |
|---|---|---|
| ACF | field group `Module: Name`, cloned into `Page Builder` as layout | layout name `text_and_media` (snake_case) |
| Template | `template-parts/builder/` | `text_and_media.php` |
| Styles | `assets/scss/4-builder/` (included by the glob) | `_text-and-media.scss` (kebab-case), BEM block `.m-text-and-media` |
| Admin preview | `assets/img/acfe-thumbnails/` (registered automatically) | `text_and_media.jpg`, 400x320px |

## Module markup (every module, see `faq.php`)

`template-parts/builder/faq.php` is the reference module (approved by the user): build every new module like it
(variable block, markup, formatting, naming), unless the design needs something different.


1. A `<section>` with the classes `module m-{layout-kebab}` (in this order) and `id` from `module_id` (only when set: `it_id_attr()` cleans it with `sanitize_title()`).
2. Inside it `<div class="container">`.
3. Inside the container: the headings component (only if uptitle, title or subtitle is filled; it has its own wrapper
   `.module-headings`), then the module content.
4. `<div class="module-header">` only when the headings area has extra elements (slider buttons, a button...): it wraps the
   headings component + those elements and takes the spacing below. Not used by default.

Default form of a module (a module may differ when its design needs it). All reading, defaults (`?: false`, `?: ''`)
and checks happen in the variable block at the top (`$module_*` for the shared ones); the markup below only uses ready
variables: no `get_sub_field()`, no inline ternaries, no repeated conditions.
Exception: repeaters / flexible content are checked and looped in the markup, not in the variable block:
check with `get_sub_field( 'faq_list' )`, loop with `while ( have_rows( 'faq_list' ) ) : the_row();`. Never check with
`have_rows()` around the module: its first call already enters the repeater, so every `get_sub_field()` after it (e.g. in the
headings component) reads the repeater row instead of the module row and gets nothing.

```php
<?php
$module_id       = it_id_attr( get_sub_field( 'module_id' ) ); // ' id="..."' or '' (inc/help-func.php)
$module_title    = get_sub_field( 'title' ) ?: false;
$module_uptitle  = get_sub_field( 'uptitle' ) ?: false;
$module_subtitle = get_sub_field( 'subtitle' ) ?: false;
$module_headings = $module_title || $module_uptitle || $module_subtitle;
// + the module's own (non-repeater) fields
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

With extra elements in the headings area (only then):

```php
<div class="module-header">
	<?php if ( $module_headings ) : ?>
		<?php get_template_part( 'template-parts/builder/components/headings' ); ?>
	<?php endif; ?>
	<!-- extra elements: slider buttons, a button... -->
</div>
```

- WYSIWYG content in `<div class="editor">`.
- Layouts that are not equal columns get their own CSS grid in the module styles (equal columns: `.grid` classes).
- `assets/scss/4-builder/` holds only module files + `_!module.scss` (styles shared by all modules: `.module` spacing / `--padding`,
  `.module-header`, `.module-headings`). Reusable UI pieces (accordion, tabs...) are styled in `assets/scss/5-components/`.
- JS for a module (if needed): `assets/js/modules/{layout-kebab}.js`, loaded on demand for the selector `.m-{layout-kebab}` (see `rules/js.md`).

## Module fields

Default structure of every `Module:` field group (see `Module: FAQ`; a project may change it later):

| Tab | Fields |
|---|---|
| **Module Settings** | `module_id` (text, 50% width): anchor id of the section. Nothing else by default. |
| **Module Content** | starts with the clone of `Component: Module Title` (seamless, no name prefix), then the module's own fields. |

- `Component: Module Title` fields: `title` (textarea), `title_tag` (h1-h6, span; no default, "Allow Null": empty = automatic h1/h2, see `headings.php`), `title_alignment` (left, center, right),
  `uptitle`, `subtitle` (textarea).
- Field group name prefixes: `Module:`, `Component:`, `Single:`, `Archive:`, `Taxonomy:`, `Page Template:`.

## Components (`template-parts/builder/components/`)

All code of a component is in its template file: the module only calls `get_template_part()`. A component used inside a module
reads the sub fields of the current module row itself (`get_sub_field()`, e.g. from a seamless clone), `$args` only for extras.

- `headings.php` (uptitle, title, subtitle = the fields of `Component: Module Title`): prints
  `<div class="module-headings alignment-{left|center|right}">` (alignment from `title_alignment`, default left) with
  `module-headings__uptitle` (span), `module-headings__title` (tag from `title_tag`, `tag_escape()`d;
  none chosen: h1 in the first module of the page (`get_row_index()`), h2 in the others, so a builder page always has its h1), `module-headings__subtitle` (p). The title has only its own class (+ optional `$args['class']`); it looks like
  an h2 with any tag through SCSS (`@extend .h2` in `_!module.scss`). Each part only when filled, nothing if all are empty.
  Alignment is only the component's business. Extra elements next to the headings go into a `.module-header` div in the
  module (see above), never into the component.
- New component: same pattern (reads its fields, defaults, whitelist for values that select a tag/class, escape everything, nothing if empty)
  + a test in `.claude/tests/components.test.php`.

## Output

- Escape late: `wp_kses_post` for WYSIWYG/title HTML, `esc_html` for plain text, `esc_attr` for colors/ids/classes, `esc_url` for URLs.
- Validate values that select a template or class (whitelist); escape a tag name from a field with `tag_escape()` (see `components/headings.php`).
- Above-the-fold images (e.g. a project's hero module) are not lazy-loaded: `[ 'loading' => 'eager', 'fetchpriority' => 'high' ]`.
