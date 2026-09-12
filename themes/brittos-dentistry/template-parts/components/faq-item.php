<?php
/**
 * FAQ accordion item using native <details>/<summary> for accessibility
 * and functionality without JavaScript. Expects global $post = `faq`.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

static $faq_index = 0;
$faq_index++;
$panel_id = 'faq-panel-' . $faq_index;
?>
<div class="faq-item" data-reveal data-reveal-group="faq">
	<details class="faq-item__details">
		<summary class="faq-item__question" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
			<span><?php the_title(); ?></span>
			<span class="faq-item__icon" aria-hidden="true"></span>
		</summary>
		<div class="faq-item__answer" id="<?php echo esc_attr( $panel_id ); ?>">
			<?php the_content(); ?>
		</div>
	</details>
</div>
