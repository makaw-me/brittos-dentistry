<?php
/**
 * Section heading component: small eyebrow label + heading + optional lede.
 *
 * Expected $args:
 *   eyebrow (string) optional
 *   heading (string) required
 *   lede    (string) optional
 *   level   (int) 2-4, default 2
 *   align   (string) left|center, default left
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args( $args ?? array(), array(
	'eyebrow' => '',
	'heading' => '',
	'lede'    => '',
	'level'   => 2,
	'align'   => 'left',
	'id'      => '',
) );

if ( '' === $args['heading'] ) {
	return;
}

$level = in_array( (int) $args['level'], array( 2, 3, 4 ), true ) ? (int) $args['level'] : 2;
$align = 'center' === $args['align'] ? 'center' : 'left';
?>
<div class="section-heading section-heading--<?php echo esc_attr( $align ); ?>">
	<?php if ( $args['eyebrow'] ) : ?>
		<p class="section-heading__eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></p>
	<?php endif; ?>

	<?php
	$id_attr = $args['id'] ? sprintf( ' id="%s"', esc_attr( $args['id'] ) ) : '';
	printf( '<h%1$d class="section-heading__title"%3$s>%2$s</h%1$d>', (int) $level, esc_html( $args['heading'] ), $id_attr ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>

	<?php if ( $args['lede'] ) : ?>
		<p class="section-heading__lede"><?php echo esc_html( $args['lede'] ); ?></p>
	<?php endif; ?>
</div>
