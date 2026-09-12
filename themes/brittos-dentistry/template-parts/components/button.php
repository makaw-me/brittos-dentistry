<?php
/**
 * Button component.
 *
 * Expected $args:
 *   text     (string, required)
 *   url      (string, required)
 *   style    (string) primary|secondary|ghost — default primary
 *   icon     (string|bool) arrow|phone|calendar|none — default depends on style
 *   class    (string) optional extra classes
 *   new_tab  (bool)
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
	'icon'    => '',
	'class'   => '',
	'new_tab' => false,
) );

if ( '' === $args['text'] || '' === $args['url'] ) {
	return;
}

$allowed_styles = array( 'primary', 'secondary', 'ghost' );
$style          = in_array( $args['style'], $allowed_styles, true ) ? $args['style'] : 'primary';

$extra_class = $args['class'] ? ' ' . esc_attr( $args['class'] ) : '';
?>
<a
	class="button button--<?php echo esc_attr( $style ); ?><?php echo $extra_class; ?>"
	href="<?php echo esc_url( $args['url'] ); ?>"
	<?php echo $args['new_tab'] ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
>
	<span class="button__text"><?php echo esc_html( $args['text'] ); ?></span>
	<?php if ( 'ghost' !== $style || 'arrow' === $args['icon'] ) : ?>
		<span class="button__icon" aria-hidden="true">
			<svg width="15" height="15" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M3.33337 8H12.6667M12.6667 8L8.66671 4M12.6667 8L8.66671 12" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</span>
	<?php endif; ?>
</a>
