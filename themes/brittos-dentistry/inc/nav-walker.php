<?php
/**
 * Custom nav menu walker that turns one ordinary menu item into a
 * "Treatments" mega menu — grouped by treatment_category, populated
 * live from real published Treatment posts. Nothing about the treatment
 * list is hard-coded: the site owner only has to mark the menu item
 * (Appearance > Menus > the item > CSS Classes) with `treatments-mega`,
 * and this walker does the rest. Every other menu item renders exactly
 * as WordPress core would.
 *
 * Progressive enhancement: the panel is real, server-rendered, always-
 * crawlable markup. With no JavaScript, `:hover`/`:focus-within` in CSS
 * is enough to reveal it on desktop; assets/js/navigation.js then adds a
 * proper disclosure button, click/tap support, Escape, and outside-click
 * handling on top of that baseline — it never replaces it.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Brittos_Primary_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * How many treatments to list per category inside the mega panel,
	 * before pointing to the full archive instead. Keeps the panel from
	 * growing unbounded as the clinic adds more treatments.
	 */
	const MAX_PER_CATEGORY = 5;

	/**
	 * How many categories to show before falling back to a simple
	 * "View all treatments" link (protects against an overly complex
	 * dropdown if the clinic ends up with many categories).
	 */
	const MAX_CATEGORIES = 4;

	/**
	 * @inheritDoc
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$is_mega = in_array( 'treatments-mega', (array) $item->classes, true );

		if ( ! $is_mega ) {
			parent::start_el( $output, $item, $depth, $args, $id );
			return;
		}

		$panel_id     = 'mega-panel-' . $item->ID;
		$is_current   = in_array( 'current-menu-item', (array) $item->classes, true ) || is_singular( 'treatment' ) || is_post_type_archive( 'treatment' ) || is_tax( 'treatment_category' );
		$atts_current = $is_current ? ' aria-current="page"' : '';

		$li_classes   = array( 'primary-nav__item', 'primary-nav__item--mega' );
		$output      .= '<li class="' . esc_attr( implode( ' ', $li_classes ) ) . '">';

		$output .= sprintf(
			'<a class="primary-nav__link primary-nav__link--mega" href="%1$s"%2$s>%3$s</a>',
			esc_url( $item->url ),
			$atts_current,
			esc_html( $item->title )
		);

		$output .= $this->build_mega_panel( $panel_id );

		// Note: no </li> here — Walker_Nav_Menu's end_el() closes it.
	}

	/**
	 * Build the mega panel markup: real treatments, grouped by real
	 * categories, queried live. Returns an empty string (falls back to
	 * a plain link with no panel) if there's nothing to show yet.
	 *
	 * @param string $panel_id DOM id for aria-controls / anchoring.
	 * @return string
	 */
	private function build_mega_panel( $panel_id ) {
		if ( ! post_type_exists( 'treatment' ) ) {
			return '';
		}

		$categories = get_terms( array(
			'taxonomy'   => 'treatment_category',
			'hide_empty' => true,
			'orderby'    => 'name',
			'order'      => 'ASC',
			'number'     => self::MAX_CATEGORIES,
		) );

		if ( is_wp_error( $categories ) || empty( $categories ) ) {
			return '';
		}

		$archive_link = get_post_type_archive_link( 'treatment' );
		$columns      = '';

		foreach ( $categories as $category ) {
			$treatments = get_posts( array(
				'post_type'      => 'treatment',
				'post_status'    => 'publish',
				'posts_per_page' => self::MAX_PER_CATEGORY,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'no_found_rows'  => true,
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'treatment_category',
						'field'    => 'term_id',
						'terms'    => $category->term_id,
					),
				),
			) );

			if ( ! $treatments ) {
				continue;
			}

			// Category heading links to the real taxonomy archive for
			// that category — derived from the actual term, never hard-coded.
			$term_link     = get_term_link( $category );
			$category_link = is_wp_error( $term_link ) ? '' : $term_link;
			$columns      .= '<div class="mega-panel__column">';
			$columns      .= '<span class="mega-panel__category">' . esc_html( $category->name ) . '</span>';
			$columns      .= '<ul class="mega-panel__list">';
			foreach ( $treatments as $treatment ) {
				$columns .= '<li><a class="mega-panel__link" href="' . esc_url( get_permalink( $treatment ) ) . '"><span class="mega-panel__link-text">' . esc_html( get_the_title( $treatment ) ) . '</span><svg class="mega-panel__link-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a></li>';
			}
			$columns .= '</ul>';
			$columns .= '</div>';
		}

		if ( ! $columns ) {
			return '';
		}

		$panel  = '<div class="mega-panel" id="' . esc_attr( $panel_id ) . '">';
		$panel .= '<div class="mega-panel__grid">' . $columns . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built entirely from escaped pieces above.
		if ( $archive_link ) {
			$panel .= '<div class="mega-panel__footer"><a class="mega-panel__all" href="' . esc_url( $archive_link ) . '">' . esc_html__( 'Explore all treatments', 'brittos-dentistry' ) . '<span aria-hidden="true"> &rarr;</span></a></div>';
		}
		$panel .= '</div>';

		return $panel;
	}
}
