<?php
/** Optional concise clinic context for the About page. */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$heading = brittos_clinic_field( 'about_clinic_heading' );
$text    = brittos_clinic_field( 'about_clinic_text' );
$image   = absint( brittos_clinic_field( 'about_clinic_image_id' ) );
if ( ! $heading && ! $text && ! ( $image && wp_attachment_is_image( $image ) ) ) { return; }
?>
<section class="about-clinic" aria-labelledby="about-clinic-heading">
	<div class="container about-clinic__inner">
		<?php if ( $image && wp_attachment_is_image( $image ) ) : ?><div class="about-clinic__media" data-reveal><?php echo wp_get_attachment_image( $image, 'brittos-card', false, array( 'class' => 'about-clinic__image', 'loading' => 'lazy', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div><?php endif; ?>
		<div class="about-clinic__content" data-reveal><?php if ( $heading ) : ?><h2 id="about-clinic-heading"><?php echo esc_html( $heading ); ?></h2><?php endif; ?><?php if ( $text ) : ?><div class="about-clinic__text"><?php echo wpautop( wp_kses_post( $text ) ); ?></div><?php endif; ?></div>
	</div>
</section>