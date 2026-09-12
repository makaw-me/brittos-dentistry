<?php
/**
 * The one breadcrumb component used across the entire site — ordinary
 * pages, the Treatments archive, and every Treatment single page all
 * render identically from here, so spacing/positioning/visual treatment
 * never drifts between them. Always rendered as its own solid,
 * self-contained surface (never overlaid on a hero image), and always
 * placed immediately after the header, before any hero/page content.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$trail = brittos_get_breadcrumb_trail();

if ( empty( $trail ) ) {
	return;
}

$crumb_count = count( $trail );
?>
<div class="breadcrumbs">
	<nav class="breadcrumbs__inner" aria-label="<?php esc_attr_e( 'Breadcrumb', 'brittos-dentistry' ); ?>">
		<ol class="breadcrumbs__list">
			<?php foreach ( $trail as $index => $crumb ) : ?>
				<li class="breadcrumbs__item">
					<?php if ( $crumb['url'] ) : ?>
						<a class="breadcrumbs__link" href="<?php echo esc_url( $crumb['url'] ); ?>"><?php echo esc_html( $crumb['label'] ); ?></a>
					<?php else : ?>
						<span class="breadcrumbs__current" aria-current="page"><?php echo esc_html( $crumb['label'] ); ?></span>
					<?php endif; ?>
				</li>
				<?php if ( $index < $crumb_count - 1 ) : ?>
					<li class="breadcrumbs__sep" role="presentation" aria-hidden="true">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ol>
	</nav>
</div>
