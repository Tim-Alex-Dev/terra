# Add Gravity Forms support to a project

Gravity Forms is not part of the starter (Contact Form 7 is the usual choice, also an add-on). This folder keeps everything needed to add it.
`.claude/` is never compiled or deployed, so nothing here affects the site until it is copied into the theme.

1. Styles: copy everything from `files/` into the theme root (same paths): `assets/scss/3-vendors/_gravity-forms.scss` (included automatically by the `3-vendors/*` glob).

2. Spinner: add to `inc/after-theme-setup.php`:

```php
/**
 * Changes Gravity Forms Ajax Spinner (next, back, submit) to a transparent image
 *
 * this allows us to target the css and create a pure css spinner or add different image instead.
 */
add_filter( 'gform_ajax_spinner_url', 'it_gform_spinner_url', 10, 2 );
function it_gform_spinner_url( $image_src, $form ) {
	return 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
}
```

3. Admin bar: in `assets/scss/1-generic/1-1-base/_wp-core.scss` hide the Forms menu on small screens too:
   `&#wp-admin-bar-wpseo-menu,` + new line `&#wp-admin-bar-gform-forms {` (inside `ul#wp-admin-bar-root-default>li`).

4. Dashboard: in `inc/disables/dashboard-widgets.php` add
   `remove_meta_box( 'rg_forms_dashboard', 'dashboard', 'normal' ); // Gravity Forms`

5. Verify: `php -l` the edited files, `npm run lint`, `gulp prod`.
