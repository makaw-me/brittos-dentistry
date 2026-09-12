<?php
/**
 * Document head and site header.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<?php brittos_theme_color_mode_bootstrap(); ?>
	<script>document.documentElement.classList.add( 'js' );</script>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary-content">
	<?php esc_html_e( 'Skip to content', 'brittos-dentistry' ); ?>
</a>

<?php get_template_part( 'template-parts/global/site-header' ); ?>

<main id="primary-content" class="site-main">
<?php
