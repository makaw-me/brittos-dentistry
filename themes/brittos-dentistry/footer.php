<?php
/**
 * Closes #primary-content, prints footer and sticky mobile CTA.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</main><!-- .site-main -->

<?php get_template_part( 'template-parts/global/site-footer' ); ?>
<?php get_template_part( 'template-parts/global/mobile-cta' ); ?>

<?php wp_footer(); ?>
</body>
</html>
