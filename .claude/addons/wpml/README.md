# Add WPML (multilingual) support to a project

WPML is not part of the starter. This folder keeps everything needed to add it. `.claude/` is never built or deployed.
Read also: https://wpml.org/documentation/related-projects/translate-sites-built-with-acf/

1. Copy everything from `files/` into the theme root (same paths): `assets/scss/3-vendors/_wpml.scss` (language switcher styles).
2. Add the snippets below: the language switcher, shown inside the mobile menu below md and in the header from md up.
   The theme has no widgets: the switcher is printed with WPML's `wpml_add_language_selector` action. In WP admin:
   WPML -> Languages -> Custom language switchers -> enable, and set its look there (dropdown, flags, names).
3. Verify: `php -l` the edited files, `npm run lint`, `gulp prod`.

## header.php: inside `<nav class="main-nav">`, after `wp_nav_menu()`
```php
			<?php if ( class_exists( 'SitePress' ) ) : // WPML is active ?>
				<div class="main-nav__lang">
					<?php do_action( 'wpml_add_language_selector' ); ?>
				</div>
			<?php endif; ?>
```

## header.php: in `.site-header > .container`, before the burger button
```php
		<?php if ( class_exists( 'SitePress' ) ) : // WPML is active ?>
			<div class="header-lang">
				<?php do_action( 'wpml_add_language_selector' ); ?>
			</div>
		<?php endif; ?>
```

## assets/scss/2-layouts/_header.scss
```scss
// Language switcher in the header (from md up; below md it is inside the mobile menu: .main-nav__lang)
.header-lang {
	@include max(md) {
		display: none;
	}
}
```

## assets/scss/2-layouts/_navigation.scss
```scss

// Language switcher inside the mobile menu (below md; from md up it is in the header: .header-lang)
.main-nav__lang {
	@include min(md) {
		display: none;
	}
}
```

## inc/disables/dashboard-widgets.php (inside `it_remove_dashboard_widgets()`)
```php
	remove_meta_box( 'icl_dashboard_widget', 'dashboard', 'normal' ); // Multi Language Plugin
```
