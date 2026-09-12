<?php
/**
 * About page: the dentist and the practice philosophy, with optional clinic
 * context and patient experience content from Clinic Info settings.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main-content" class="about-page">
	<?php get_template_part( 'template-parts/sections/about-hero' ); ?>
	<?php get_template_part( 'template-parts/sections/about-doctor', null, array( 'variant' => 'page' ) ); ?>
	<?php get_template_part( 'template-parts/sections/about-philosophy' ); ?>
	<?php get_template_part( 'template-parts/sections/about-clinic' ); ?>
	<?php get_template_part( 'template-parts/sections/about-experience' ); ?>
	<?php get_template_part( 'template-parts/sections/testimonials' ); ?>
	<?php get_template_part( 'template-parts/sections/final-cta' ); ?>
</main>

<?php get_footer(); ?>