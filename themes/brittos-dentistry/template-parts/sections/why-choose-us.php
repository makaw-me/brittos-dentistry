<?php
/**
 * "Why choose this clinic" — factual, configurable trust indicators.
 * Content here is intentionally generic/placeholder and should be
 * edited to reflect only true, verifiable details about the clinic.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$points = array(
	array(
		'title' => __( 'One dentist, every visit', 'brittos-dentistry' ),
		'text'  => __( 'You see the same dentist each time — no unfamiliar faces, no re-explaining your history.', 'brittos-dentistry' ),
	),
	array(
		'title' => __( 'Unhurried appointments', 'brittos-dentistry' ),
		'text'  => __( 'Visits are scheduled with enough time to actually talk through concerns, not just treat them.', 'brittos-dentistry' ),
	),
	array(
		'title' => __( 'Transparent treatment plans', 'brittos-dentistry' ),
		'text'  => __( 'Costs and options are discussed upfront, in plain language, before any work begins.', 'brittos-dentistry' ),
	),
	array(
		'title' => __( 'A calm, comfortable clinic', 'brittos-dentistry' ),
		'text'  => __( 'A small, quiet practice designed to feel more like a considered space than a waiting room.', 'brittos-dentistry' ),
	),
);
?>
<section class="why-choose-us" aria-labelledby="why-choose-us-heading">
	<div class="container">

		<?php get_template_part( 'template-parts/components/section-heading', null, array(
			'eyebrow' => __( 'Why patients stay', 'brittos-dentistry' ),
			'heading' => __( 'Dentistry that feels personal again', 'brittos-dentistry' ),
			'align'   => 'center',
			'id'      => 'why-choose-us-heading',
		) ); ?>

		<ul class="why-choose-us__grid">
			<?php foreach ( $points as $point ) : ?>
				<li class="why-choose-us__item">
					<h3 class="why-choose-us__title"><?php echo esc_html( $point['title'] ); ?></h3>
					<p class="why-choose-us__text"><?php echo esc_html( $point['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
