<?php
/**
 * Privacy Policy page template.
 *
 * The policy body is intentionally rendered from the WordPress page editor.
 * Only the presentation shell belongs in the theme.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$updated_date = get_the_modified_date( get_option( 'date_format' ) );
	$updated_iso  = get_the_modified_date( 'c' );
	?>

	<header class="privacy-policy-hero" aria-labelledby="privacy-policy-title">
		<div class="container privacy-policy-hero__inner">
			<p class="privacy-policy-hero__eyebrow">PRIVACY</p>
			<h1 id="privacy-policy-title" class="privacy-policy-hero__title"><?php esc_html_e( 'Privacy Policy', 'brittos-dentistry' ); ?></h1>
			<p class="privacy-policy-hero__intro"><?php esc_html_e( 'A clear explanation of how Britto\'s Dentistry handles information shared through this website.', 'brittos-dentistry' ); ?></p>
			<p class="privacy-policy-hero__updated">
				<span><?php esc_html_e( 'Last updated', 'brittos-dentistry' ); ?></span>
				<time datetime="<?php echo esc_attr( $updated_iso ); ?>"><?php echo esc_html( $updated_date ); ?></time>
			</p>
		</div>
	</header>

	<article <?php post_class( 'privacy-policy-content' ); ?>>
		<div class="container privacy-policy-content__inner entry-content">
			<?php the_content(); ?>
		</div>
	</article>

	<?php
endwhile;

get_footer();