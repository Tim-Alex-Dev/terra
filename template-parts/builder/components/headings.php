<?php
/**
 * Module headings: uptitle, title, subtitle from the `Component: Module Title` clone of the current module.
 * Title tag: the chosen one, or (none chosen) h1 in the first module of the page, h2 in the others.
 * Optional: get_template_part( 'template-parts/builder/components/headings', null, [ 'class' => 'extra-title-class' ] );
 */

$uptitle   = get_sub_field( 'uptitle' ) ?: false;
$title     = get_sub_field( 'title' ) ?: false;
$subtitle  = get_sub_field( 'subtitle' ) ?: false;
$tag       = tag_escape( get_sub_field( 'title_tag' ) ?: ( 1 === get_row_index() ? 'h1' : 'h2' ) );
$alignment = get_sub_field( 'title_alignment' ) ?: 'left';
$class     = trim( 'module-headings__title ' . ( $args['class'] ?? '' ) );

if ( ! $uptitle && ! $title && ! $subtitle ) {
	return;
}
?>

<div class="module-headings alignment-<?php echo esc_attr( $alignment ); ?>">
	<?php if ( $uptitle ) : ?>
		<span class="module-headings__uptitle">
			<?php echo esc_html( $uptitle ); ?>
		</span>
	<?php endif; ?>

	<?php if ( $title ) : ?>
		<<?php echo $tag; ?> class="<?php echo esc_attr( $class ); ?>">
			<?php echo wp_kses_post( $title ); ?>
		</<?php echo $tag; ?>>
	<?php endif; ?>

	<?php if ( $subtitle ) : ?>
		<p class="module-headings__subtitle">
			<?php echo esc_html( $subtitle ); ?>
		</p>
	<?php endif; ?>
</div>