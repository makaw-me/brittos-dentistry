<?php
/**
 * Button component.
 *
 * Expected $args:
 *   text  (string, required)
 *   url   (string, required)
 *   style (string) primary|secondary|ghost — default primary
 *   new_tab (bool)
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args( $args ?? array(), array(
	'text'    => '',
	'url'     => '',
	'style'   => 'primary',
	'new_tab' => false,
) );

if ( '' === $args['text'] || '' === $args['url'] ) {
	return;
}

$allowed_styles = array( 'primary', 'secondary', 'ghost' );
$style          = in_array( $args['style'], $allowed_styles, true ) ? $args['style'] : 'primary';
?>
<a
	class="button button--<?php echo esc_attr( $style ); ?>"
	href="<?php echo esc_url( $args['url'] ); ?>"
	<?php echo $args['new_tab'] ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
>
	<?php echo esc_html( $args['text'] ); ?>
</a>
