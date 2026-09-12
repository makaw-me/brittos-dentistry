<?php
/** Optional patient experience sequence for the About page. */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$heading = brittos_clinic_field( 'about_experience_heading' );
$steps = preg_split( '/\r\n|\r|\n/', (string) brittos_clinic_field( 'about_experience_steps' ) );
$steps = array_values( array_filter( array_map( 'trim', $steps ) ) );
if ( ! $heading && empty( $steps ) ) { return; }
?>
<section class="about-experience" aria-labelledby="about-experience-heading">
	<div class="container">
		<?php if ( $heading ) : ?><h2 id="about-experience-heading"><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
		<?php if ( $steps ) : ?><ol class="about-experience__list" style="--experience-count: <?php echo esc_attr( count( $steps ) ); ?>;"><?php foreach ( $steps as $step ) : ?><li data-reveal data-reveal-group="about-experience"><span><?php echo esc_html( $step ); ?></span></li><?php endforeach; ?></ol><?php endif; ?>
	</div>
</section>