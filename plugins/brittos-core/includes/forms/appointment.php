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

		<div class="appointment-form__row appointment-form__row--split">
			<div>
				<label for="brittos_name"><?php esc_html_e( 'Name', 'brittos-core' ); ?> <span aria-hidden="true">*</span></label>
				<input type="text" id="brittos_name" name="brittos_name" required autocomplete="name">
			</div>
			<div>
				<label for="brittos_phone"><?php esc_html_e( 'Phone', 'brittos-core' ); ?> <span aria-hidden="true">*</span></label>
				<input type="tel" id="brittos_phone" name="brittos_phone" required autocomplete="tel">
			</div>
		</div>

		<div class="appointment-form__row">
			<label for="brittos_email"><?php esc_html_e( 'Email (optional)', 'brittos-core' ); ?></label>
			<input type="email" id="brittos_email" name="brittos_email" autocomplete="email">
		</div>

		<div class="appointment-form__row appointment-form__row--split">
			<div>
				<label for="brittos_preferred_date"><?php esc_html_e( 'Preferred Date', 'brittos-core' ); ?></label>
				<input type="date" id="brittos_preferred_date" name="brittos_preferred_date">
			</div>
			<div>
				<label for="brittos_preferred_time"><?php esc_html_e( 'Preferred Time', 'brittos-core' ); ?></label>
				<input type="time" id="brittos_preferred_time" name="brittos_preferred_time">
			</div>
		</div>

		<div class="appointment-form__row">
			<label for="brittos_reason"><?php esc_html_e( 'Reason for Visit', 'brittos-core' ); ?></label>
			<input type="text" id="brittos_reason" name="brittos_reason" autocomplete="off">
		</div>

		<div class="appointment-form__row">
			<label for="brittos_message"><?php esc_html_e( 'Message (optional)', 'brittos-core' ); ?></label>
			<textarea id="brittos_message" name="brittos_message" rows="4"></textarea>
			<p class="appointment-form__hint">
				<?php esc_html_e( 'Please avoid sharing detailed medical history here — this form is for scheduling only.', 'brittos-core' ); ?>
			</p>
		</div>

		<button type="submit" class="button button--primary">
			<?php esc_html_e( 'Request appointment', 'brittos-core' ); ?>
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
		printf(
			'<p class="appointment-form__notice appointment-form__notice--success" role="status">%s</p>',
			esc_html__( 'Thank you — the clinic will be in touch shortly to confirm your appointment.', 'brittos-core' )
		);
	} elseif ( 'error' === $status ) {
		printf(
			'<p class="appointment-form__notice appointment-form__notice--error" role="alert">%s</p>',
			esc_html__( 'Something went wrong. Please check the required fields and try again.', 'brittos-core' )
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

	if ( '' === $name ) {
		$errors[] = __( 'Please enter your name.', 'brittos-core' );
	}

	if ( '' === $phone ) {
		$errors[] = __( 'Please enter a phone number.', 'brittos-core' );
	} elseif ( ! preg_match( '/^[0-9+()\-\s]{6,20}$/', $phone ) ) {
		$errors[] = __( 'Please enter a valid phone number.', 'brittos-core' );
	}

	if ( '' !== $email && ! is_email( $email ) ) {
		$errors[] = __( 'Please enter a valid email address.', 'brittos-core' );
	}

	if ( $date && ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
		$date = '';
	}
	if ( $time && ! preg_match( '/^\d{2}:\d{2}$/', $time ) ) {
		$time = '';
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

	$lines = array(
		sprintf( __( 'Name: %s', 'brittos-core' ), $data['name'] ),
		sprintf( __( 'Phone: %s', 'brittos-core' ), $data['phone'] ),
	);

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
