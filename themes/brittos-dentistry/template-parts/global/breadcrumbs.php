<?php
/**
 * Simple, accessible breadcrumb trail. Also feeds BreadcrumbList schema
 * via the core plugin's SEO module (see plugin includes/seo/schema.php).
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_front_page() ) {
	return;
}

$trail = array(
	array(
		'label' => __( 'Home', 'brittos-dentistry' ),
		'url'   => home_url( '/' ),
	),
);

if ( is_singular( 'treatment' ) ) {
	$archive_link = get_post_type_archive_link( 'treatment' );
	if ( $archive_link ) {
		$trail[] = array(
			'label' => __( 'Treatments', 'brittos-dentistry' ),
			'url'   => $archive_link,
		);
	}
	$trail[] = array(
		'label' => get_the_title(),
		'url'   => '',
	);
} elseif ( is_post_type_archive( 'treatment' ) ) {
	$trail[] = array(
		'label' => __( 'Treatments', 'brittos-dentistry' ),
		'url'   => '',
	);
} elseif ( is_page() ) {
	$trail[] = array(
		'label' => get_the_title(),
		'url'   => '',
	);
} elseif ( is_singular() ) {
	$trail[] = array(
		'label' => get_the_title(),
		'url'   => '',
	);
} elseif ( is_search() ) {
	$trail[] = array(
		'label' => __( 'Search results', 'brittos-dentistry' ),
		'url'   => '',
	);
} elseif ( is_404() ) {
	$trail[] = array(
		'label' => __( 'Page not found', 'brittos-dentistry' ),
		'url'   => '',
	);
} else {
	$trail[] = array(
		'label' => wp_get_document_title(),
		'url'   => '',
	);
}
?>
<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'brittos-dentistry' ); ?>">
	<ol class="breadcrumbs__list">
		<?php foreach ( $trail as $index => $crumb ) : ?>
			<li class="breadcrumbs__item">
				<?php if ( $crumb['url'] ) : ?>
					<a href="<?php echo esc_url( $crumb['url'] ); ?>"><?php echo esc_html( $crumb['label'] ); ?></a>
				<?php else : ?>
					<span aria-current="page"><?php echo esc_html( $crumb['label'] ); ?></span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
