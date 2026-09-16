<?php
/**
 * Lightweight appointment enquiry form: not a booking/calendar system,
 * just a validated, sanitized, spam-resistant contact form that emails
 * the clinic via wp_mail(). Supports both a plain POST (works with no
 * JavaScript) and an AJAX submission (progressively enhanced by the
 * theme's assets/js/main.js).
 *
 * @package Brittos_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BRITTOS_CORE_APPOINTMENT_ACTION', 'brittos_core_submit_appointment' );

/**
 * Render the enquiry form markup.
 */
function brittos_core_render_appointment_form() {
	$action_url = admin_url( 'admin-post.php' );
	$text = static function ( $key, $default ) {
		return brittos_core_get_clinic_field( $key, $default );
	};
	$name_label = $text( 'appointment_name_label', __( 'Name', 'brittos-core' ) );
	$phone_label = $text( 'appointment_phone_label', __( 'Phone', 'brittos-core' ) );
	$email_label = $text( 'appointment_email_label', __( 'Email', 'brittos-core' ) );
	$date_label = $text( 'appointment_date_label', __( 'Preferred Date', 'brittos-core' ) );
	$time_label = $text( 'appointment_time_label', __( 'Preferred Time', 'brittos-core' ) );
	$reason_label = $text( 'appointment_reason_label', __( 'Reason for Visit', 'brittos-core' ) );
	$message_label = $text( 'appointment_message_label', __( 'Message', 'brittos-core' ) );
	$message_hint = $text( 'appointment_message_hint', __( 'Please avoid sharing detailed medical history here — this form is for scheduling only.', 'brittos-core' ) );
	$submit_label = $text( 'appointment_submit_label', __( 'Request appointment', 'brittos-core' ) );
	?>
	<div
		id="appointment-form-response"
		class="appointment-form__notice"
		role="status"
		aria-live="polite"
		hidden
	></div>

	<?php brittos_core_render_appointment_notice_from_redirect(); ?>

	<form class="appointment-form" method="post" action="<?php echo esc_url( $action_url ); ?>" novalidate>
		<input type="hidden" name="action" value="<?php echo esc_attr( BRITTOS_CORE_APPOINTMENT_ACTION ); ?>">
		<?php wp_nonce_field( 'brittos_core_appointment', 'brittos_core_appointment_nonce' ); ?>
		<input type="hidden" name="brittos_core_form_started" value="<?php echo esc_attr( time() ); ?>">

		<!-- Honeypot: real visitors never see or fill this field. -->
		<p class="appointment-form__honeypot" aria-hidden="true">
			<label for="brittos_website">
				<?php esc_html_e( 'Leave this field empty', 'brittos-core' ); ?>
			</label>
			<input type="text" id="brittos_website" name="brittos_website" tabindex="-1" autocomplete="off" value="">
		</p>

		<?php if ( brittos_core_appointment_field_visible( 'name' ) ) : ?>
			<div class="appointment-form__row">
				<label for="brittos_name">
					<?php echo esc_html( $name_label ); ?>
					<?php if ( brittos_core_appointment_field_required( 'name' ) ) : ?><span aria-hidden="true">*</span><?php endif; ?>
				</label>
				<input
					type="text"
					id="brittos_name"
					name="brittos_name"
					autocomplete="name"
					<?php echo brittos_core_appointment_field_required( 'name' ) ? 'required aria-required="true"' : ''; ?>
				>
			</div>
		<?php endif; ?>

		<?php if ( brittos_core_appointment_field_visible( 'phone' ) ) : ?>
			<div class="appointment-form__row">
				<label for="brittos_phone">
					<?php echo esc_html( $phone_label ); ?>
					<?php if ( brittos_core_appointment_field_required( 'phone' ) ) : ?><span aria-hidden="true">*</span><?php endif; ?>
				</label>
				<input
					type="tel"
					id="brittos_phone"
					name="brittos_phone"
					autocomplete="tel"
					<?php echo brittos_core_appointment_field_required( 'phone' ) ? 'required aria-required="true"' : ''; ?>
				>
			</div>
		<?php endif; ?>

		<?php if ( brittos_core_appointment_field_visible( 'email' ) ) : ?>
			<div class="appointment-form__row">
				<label for="brittos_email">
					<?php echo esc_html( $email_label ); ?>
					<?php if ( brittos_core_appointment_field_required( 'email' ) ) : ?><span aria-hidden="true">*</span><?php endif; ?>
				</label>
				<input
					type="email"
					id="brittos_email"
					name="brittos_email"
					autocomplete="email"
					<?php echo brittos_core_appointment_field_required( 'email' ) ? 'required aria-required="true"' : ''; ?>
				>
			</div>
		<?php endif; ?>

		<?php if ( brittos_core_appointment_field_visible( 'preferred_date' ) || brittos_core_appointment_field_visible( 'preferred_time' ) ) : ?>
			<div class="appointment-form__row appointment-form__row--split">
				<?php if ( brittos_core_appointment_field_visible( 'preferred_date' ) ) : ?>
					<div>
						<label for="brittos_preferred_date">
							<?php echo esc_html( $date_label ); ?>
							<?php if ( brittos_core_appointment_field_required( 'preferred_date' ) ) : ?><span aria-hidden="true">*</span><?php endif; ?>
						</label>
						<input
							type="date"
							id="brittos_preferred_date"
							name="brittos_preferred_date"
							<?php echo brittos_core_appointment_field_required( 'preferred_date' ) ? 'required aria-required="true"' : ''; ?>
						>
					</div>
				<?php endif; ?>
				<?php if ( brittos_core_appointment_field_visible( 'preferred_time' ) ) : ?>
					<div>
						<label for="brittos_preferred_time">
							<?php echo esc_html( $time_label ); ?>
							<?php if ( brittos_core_appointment_field_required( 'preferred_time' ) ) : ?><span aria-hidden="true">*</span><?php endif; ?>
						</label>
						<input
							type="time"
							id="brittos_preferred_time"
							name="brittos_preferred_time"
							<?php echo brittos_core_appointment_field_required( 'preferred_time' ) ? 'required aria-required="true"' : ''; ?>
						>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( brittos_core_appointment_field_visible( 'reason' ) ) : ?>
			<div class="appointment-form__row">
				<label for="brittos_reason">
					<?php echo esc_html( $reason_label ); ?>
					<?php if ( brittos_core_appointment_field_required( 'reason' ) ) : ?><span aria-hidden="true">*</span><?php endif; ?>
				</label>
				<input
					type="text"
					id="brittos_reason"
					name="brittos_reason"
					autocomplete="off"
					<?php echo brittos_core_appointment_field_required( 'reason' ) ? 'required aria-required="true"' : ''; ?>
				>
			</div>
		<?php endif; ?>

		<?php if ( brittos_core_appointment_field_visible( 'message' ) ) : ?>
			<div class="appointment-form__row">
				<label for="brittos_message">
					<?php echo esc_html( $message_label ); ?>
					<?php if ( brittos_core_appointment_field_required( 'message' ) ) : ?><span aria-hidden="true">*</span><?php endif; ?>
				</label>
				<textarea
					id="brittos_message"
					name="brittos_message"
					rows="4"
					<?php echo brittos_core_appointment_field_required( 'message' ) ? 'required aria-required="true"' : ''; ?>
				></textarea>
				<?php if ( $message_hint ) : ?>
					<p class="appointment-form__hint"><?php echo esc_html( $message_hint ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<button type="submit" class="button button--primary">
			<?php echo esc_html( $submit_label ); ?>
		</button>
	</form>
	<?php
}

/**
 * If the visitor arrived back from a non-JS form submission, show the
 * success/error notice based on the redirect query args.
 */
function brittos_core_render_appointment_notice_from_redirect() {
	if ( ! isset( $_GET['brittos_appointment'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}

	$status = sanitize_key( wp_unslash( $_GET['brittos_appointment'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( 'success' === $status ) {
		$message = brittos_core_get_clinic_field( 'appointment_success_message', __( 'Thank you — the clinic will be in touch shortly to confirm your appointment.', 'brittos-core' ) );
		printf(
			'<p class="appointment-form__notice appointment-form__notice--success" role="status">%s</p>',
			esc_html( $message )
		);
	} elseif ( 'error' === $status ) {
		$message = brittos_core_get_clinic_field( 'appointment_error_message', __( 'Something went wrong. Please check the required fields and try again.', 'brittos-core' ) );
		printf(
			'<p class="appointment-form__notice appointment-form__notice--error" role="alert">%s</p>',
			esc_html( $message )
		);
	}
}

/**
 * Validate and sanitize the posted appointment fields.
 *
 * @return array {
 *     @type array  $data   Cleaned field values.
 *     @type array  $errors Validation error messages, empty when valid.
 * }
 */
function brittos_core_validate_appointment_submission() {
	$errors = array();

	$name  = isset( $_POST['brittos_name'] ) ? sanitize_text_field( wp_unslash( $_POST['brittos_name'] ) ) : '';
	$phone = isset( $_POST['brittos_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['brittos_phone'] ) ) : '';
	$email = isset( $_POST['brittos_email'] ) ? sanitize_email( wp_unslash( $_POST['brittos_email'] ) ) : '';
	$date  = isset( $_POST['brittos_preferred_date'] ) ? sanitize_text_field( wp_unslash( $_POST['brittos_preferred_date'] ) ) : '';
	$time  = isset( $_POST['brittos_preferred_time'] ) ? sanitize_text_field( wp_unslash( $_POST['brittos_preferred_time'] ) ) : '';
	$reason  = isset( $_POST['brittos_reason'] ) ? sanitize_text_field( wp_unslash( $_POST['brittos_reason'] ) ) : '';
	$message = isset( $_POST['brittos_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['brittos_message'] ) ) : '';

	if ( brittos_core_appointment_field_required( 'name' ) && '' === $name ) {
		$errors[] = __( 'Please enter your name.', 'brittos-core' );
	}

	if ( brittos_core_appointment_field_required( 'phone' ) && '' === $phone ) {
		$errors[] = __( 'Please enter a phone number.', 'brittos-core' );
	} elseif ( '' !== $phone && ! preg_match( '/^[0-9+()\-\s]{6,20}$/', $phone ) ) {
		$errors[] = __( 'Please enter a valid phone number.', 'brittos-core' );
	}

	if ( brittos_core_appointment_field_required( 'email' ) && '' === $email ) {
		$errors[] = __( 'Please enter your email address.', 'brittos-core' );
	} elseif ( '' !== $email && ! is_email( $email ) ) {
		$errors[] = __( 'Please enter a valid email address.', 'brittos-core' );
	}

	if ( brittos_core_appointment_field_required( 'preferred_date' ) && '' === $date ) {
		$errors[] = __( 'Please choose a preferred date.', 'brittos-core' );
	}
	if ( $date && ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
		$date = '';
	}

	if ( brittos_core_appointment_field_required( 'preferred_time' ) && '' === $time ) {
		$errors[] = __( 'Please choose a preferred time.', 'brittos-core' );
	}
	if ( $time && ! preg_match( '/^\d{2}:\d{2}$/', $time ) ) {
		$time = '';
	}

	if ( brittos_core_appointment_field_required( 'reason' ) && '' === $reason ) {
		$errors[] = __( 'Please share your reason for visiting.', 'brittos-core' );
	}

	if ( brittos_core_appointment_field_required( 'message' ) && '' === $message ) {
		$errors[] = __( 'Please add a short message.', 'brittos-core' );
	}

	return array(
		'data'   => compact( 'name', 'phone', 'email', 'date', 'time', 'reason', 'message' ),
		'errors' => $errors,
	);
}

/**
 * Basic spam checks: nonce, honeypot, and a minimum time-on-form.
 *
 * @return bool True when the submission looks legitimate.
 */
function brittos_core_appointment_passes_spam_checks() {

	if (
		! isset( $_POST['brittos_core_appointment_nonce'] )
		|| ! wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['brittos_core_appointment_nonce'] ) ),
			'brittos_core_appointment'
		)
	) {
		return false;
	}

	// Honeypot: bots tend to fill every field.
	if ( ! empty( $_POST['brittos_website'] ) ) {
		return false;
	}

	// Require at least 3 seconds between the form rendering and submitting.
	$started = isset( $_POST['brittos_core_form_started'] ) ? absint( $_POST['brittos_core_form_started'] ) : 0;
	if ( $started && ( time() - $started ) < 3 ) {
		return false;
	}

	return true;
}

/**
 * Compose and send the notification email to the clinic.
 *
 * @param array $data Cleaned form data.
 * @return bool
 */
function brittos_core_send_appointment_email( $data ) {
	$to = brittos_core_get_clinic_field( 'notification_email' );
	if ( ! $to ) {
		$to = get_option( 'admin_email' );
	}

	$clinic_name = brittos_core_get_clinic_field( 'clinic_name', get_bloginfo( 'name' ) );

	/* translators: %s: clinic name */
	$subject = sprintf( __( 'New appointment enquiry — %s', 'brittos-core' ), $clinic_name );

	$lines = array();

	if ( $data['name'] ) {
		$lines[] = sprintf( __( 'Name: %s', 'brittos-core' ), $data['name'] );
	}
	if ( $data['phone'] ) {
		$lines[] = sprintf( __( 'Phone: %s', 'brittos-core' ), $data['phone'] );
	}
	if ( $data['email'] ) {
		$lines[] = sprintf( __( 'Email: %s', 'brittos-core' ), $data['email'] );
	}
	if ( $data['date'] ) {
		$lines[] = sprintf( __( 'Preferred date: %s', 'brittos-core' ), $data['date'] );
	}
	if ( $data['time'] ) {
		$lines[] = sprintf( __( 'Preferred time: %s', 'brittos-core' ), $data['time'] );
	}
	if ( $data['reason'] ) {
		$lines[] = sprintf( __( 'Reason for visit: %s', 'brittos-core' ), $data['reason'] );
	}
	if ( $data['message'] ) {
		$lines[] = sprintf( __( "Message:\n%s", 'brittos-core' ), $data['message'] );
	}

	$body = implode( "\n", $lines );

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( $data['email'] ) {
		$headers[] = 'Reply-To: ' . $data['email'];
	}

	return wp_mail( $to, $subject, $body, $headers );
}

/**
 * Shared handler for both logged-in and anonymous submissions.
 */
function brittos_core_handle_appointment_submission() {

	if ( ! brittos_core_appointment_passes_spam_checks() ) {
		brittos_core_appointment_respond( false, __( 'We could not verify your submission. Please reload the page and try again.', 'brittos-core' ) );
		return;
	}

	$result = brittos_core_validate_appointment_submission();

	if ( ! empty( $result['errors'] ) ) {
		brittos_core_appointment_respond( false, implode( ' ', $result['errors'] ) );
		return;
	}

	$sent = brittos_core_send_appointment_email( $result['data'] );

	if ( $sent ) {
		/**
		 * Fires after an appointment enquiry has been successfully emailed.
		 *
		 * @param array $data Cleaned form data.
		 */
		do_action( 'brittos_core_appointment_submitted', $result['data'] );
		brittos_core_appointment_respond( true, __( 'Thank you — the clinic will be in touch shortly to confirm your appointment.', 'brittos-core' ) );
	} else {
		brittos_core_appointment_respond( false, __( 'We could not send your enquiry. Please try again or call the clinic directly.', 'brittos-core' ) );
	}
}
add_action( 'admin_post_' . BRITTOS_CORE_APPOINTMENT_ACTION, 'brittos_core_handle_appointment_submission' );
add_action( 'admin_post_nopriv_' . BRITTOS_CORE_APPOINTMENT_ACTION, 'brittos_core_handle_appointment_submission' );

/**
 * Respond either as JSON (AJAX/fetch request) or as a safe redirect back
 * to the referring page (plain form POST, no JavaScript).
 *
 * @param bool   $success Whether the submission succeeded.
 * @param string $message Human-readable message.
 */
function brittos_core_appointment_respond( $success, $message ) {

	$is_ajax = ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] )
		&& 'xmlhttprequest' === strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) );

	if ( $is_ajax ) {
		wp_send_json( array(
			'success' => (bool) $success,
			'data'    => array( 'message' => $message ),
		) );
	}

	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$redirect = remove_query_arg( 'brittos_appointment', $redirect );
	$redirect = add_query_arg( 'brittos_appointment', $success ? 'success' : 'error', $redirect );
	$redirect .= '#appointment-form';

	wp_safe_redirect( $redirect );
	exit;
}
