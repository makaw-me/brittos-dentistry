<?php
/** Optional editorial practice philosophy section. */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$heading = brittos_clinic_field( 'about_philosophy_heading' );
$lede    = brittos_clinic_field( 'about_philosophy_lede' );
$principles = array();
for ( $index = 1; $index <= 4; $index++ ) {
	$title = brittos_clinic_field( 'about_philosophy_' . $index . '_title' );
	$text  = brittos_clinic_field( 'about_philosophy_' . $index . '_text' );
	if ( $title || $text ) { $principles[] = array( 'title' => $title, 'text' => $text ); }
}
if ( ! $heading && ! $lede && empty( $principles ) ) { return; }
?>
<section class="about-philosophy" aria-labelledby="about-philosophy-heading">
	<div class="container about-philosophy__inner">
		<div class="about-philosophy__intro">
			<?php get_template_part( 'template-parts/components/section-heading', null, array( 'heading' => $heading, 'lede' => $lede, 'id' => 'about-philosophy-heading' ) ); ?>
		</div>
		<?php if ( $principles ) : ?><ol class="about-philosophy__list"><?php foreach ( $principles as $index => $principle ) : ?><li class="about-philosophy__item" data-reveal data-reveal-group="about-philosophy"><span class="about-philosophy__number" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span><div><?php if ( $principle['title'] ) : ?><h3><?php echo esc_html( $principle['title'] ); ?></h3><?php endif; ?><?php if ( $principle['text'] ) : ?><p><?php echo esc_html( $principle['text'] ); ?></p><?php endif; ?></div></li><?php endforeach; ?></ol><?php endif; ?>
	</div>
</section>