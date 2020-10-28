<?php

/**
 * Donor gender and size Fields in Donation form
 *
 * @param $form_id
 */
function donor_gender_custom_form_fields( $form_id ) {

		?>
		<div id="give-gender-wrap" class="form-row form-row-wide">
			<label class="donor-label" for="give-donor-gender">
				<?php _e( 'Please select your gender:', 'give' ); ?>
			</label>
<div class="donor-g-s">
  <input type="radio" id="donor-male" class="give-radio" name="donor_gender" value="male">
  <label for="male">Male</label>
  <input type="radio" id="donor-female" class="give-radio" name="donor_gender" value="female">
  <label for="female">Female</label>

		</div>		</div>


		<div id="give-size-wrap" class="form-row form-row-wide">
			<label class="donor-label" for="give-engraving-message">
				<?php _e( 'Please select your size:', 'give' ); ?>
			</label>

<div class="donor-g-s">

  <input type="radio" id="donor-s" class="give-radio" name="donor_size" value="male">
  <label for="male">S</label>
  <input type="radio" id="donor-m" class="give-radio" name="donor_size" value="female">
  <label for="female">M</label>
  <input type="radio" id="donor-l" class="give-radio" name="donor_size" value="male">
  <label for="male">L</label>
  <input type="radio" id="donor-xl" class="give-radio" name="donor_size" value="female">
  <label for="female">XL</label>
  <input type="radio" id="donor-xxl" class="give-radio" name="donor_size" value="female">
  <label for="female">XXL</label>  
</div>
		</div>

		<?php

}

add_action( 'give_donation_form_after_email', 'donor_gender_custom_form_fields' );


/**
 * Add Field to Payment Meta
 *
 * Store the custom field data custom post meta attached to the `give_payment` CPT.
 *
 * @param $payment_id
 *
 * @return mixed
 */
function donor_gender_save_custom_fields( $payment_id ) {

	if ( isset( $_POST['donor_gender'] ) ) {
		$message = wp_strip_all_tags( $_POST['donor_gender'], true );
		give_update_payment_meta( $payment_id, 'donor_gender', $message );
	}

	if ( isset( $_POST['donor_size'] ) ) {
		$message = wp_strip_all_tags( $_POST['donor_size'], true );
		give_update_payment_meta( $payment_id, 'donor_size', $message );
	}	

}

add_action( 'give_insert_payment', 'donor_gender_save_custom_fields' );

/**
 * Show Data in Transaction Details
 *
 * Show the custom field(s) on the transaction page.
 *
 * @param $payment_id
 */
function donor_gender_donation_details( $payment_id ) {

	$donor_gender = give_get_meta( $payment_id, 'donor_gender', true );
	$donor_size = give_get_meta( $payment_id, 'donor_size', true );	

	if ( $donor_gender ) : ?>

		<div id="give-engraving-message" class="postbox">
			<h3 class="hndle"><?php esc_html_e( 'Donor Gender', 'give' ); ?></h3>
			<div class="inside" style="padding-bottom:10px;">
				<?php echo wpautop( $donor_gender ); ?>
			</div>
		</div>

	<?php endif;

	if ( $donor_size ) : ?>

		<div id="give-engraving-message" class="postbox">
			<h3 class="hndle"><?php esc_html_e( 'Donor Size', 'give' ); ?></h3>
			<div class="inside" style="padding-bottom:10px;">
				<?php echo wpautop( $donor_size ); ?>
			</div>
		</div>

	<?php endif;	

}

add_action( 'give_view_donation_details_billing_before', 'donor_gender_donation_details', 10, 1 );


/**
 * Get Donation Referral Data
 *
 * Example function that returns Custom field data if present in payment_meta;
 * The example used here is in conjunction with the Give documentation tutorials.
 *
 * @param array $tag_args Array of arguments
 *
 * @return string
 */
function donor_gender_referral_data( $tag_args ) {
	$donor_gender = give_get_meta( $tag_args['payment_id'], 'donor_gender', true );
	$donor_size = give_get_meta( $tag_args['payment_id'], 'donor_size', true );

	$output = __( 'No referral data found.', 'give' );

	if ( ! empty( $donor_gender ) ) {
		$output = wp_kses_post( $donor_gender );
	}

	if ( ! empty( $donor_size ) ) {
		$output = wp_kses_post( $donor_size );
	}	

	return $output;
}


/**
 * Add Donation Donor gender.
 *
 * @params array    $args
 * @params int      $donation_id
 * @params int      $form_id
 *
 * @return array
 */
function donor_gender_receipt_args( $args, $donation_id, $form_id ) {

	// Only display for forms with the IDs "754" and "578";
	// Remove "If" statement to display on all forms
	// For a single form, use this instead:
	// if ( $form_id == 754) {
		$donor_gender    = give_get_meta( $donation_id, 'donor_gender', true );
		$args['donor_gender'] = array(
			'name'    => __( 'Donor Gender', 'give' ),
			'value'   => wp_kses_post( $donor_gender ),
			// Do not show Engraved field if empty
			'display' => empty( $donor_gender ) ? false : true,
		);

	return $args;
}

add_filter( 'give_donation_receipt_args', 'donor_gender_receipt_args', 30, 3 );


/**
 * Add Donation donor size.
 *
 * @params array    $args
 * @params int      $donation_id
 * @params int      $form_id
 *
 * @return array
 */
function donor_size_receipt_args( $args, $donation_id, $form_id ) {

	// Only display for forms with the IDs "754" and "578";
	// Remove "If" statement to display on all forms
	// For a single form, use this instead:
	// if ( $form_id == 754) {
		$donor_size    = give_get_meta( $donation_id, 'donor_size', true );
		$args['donor_size'] = array(
			'name'    => __( 'Donor Size', 'give' ),
			'value'   => wp_kses_post( $donor_size ),
			// Do not show Engraved field if empty
			'display' => empty( $donor_size ) ? false : true,
		);

	return $args;
}

add_filter( 'give_donation_receipt_args', 'donor_size_receipt_args', 30, 3 );


/**
 * Add Donation donor gender in export donor fields tab.
 */
function donor_gender_standard_donor_fields() {
	?>
	<li>
		<label for="donor-gender">
			<input type="checkbox" checked
			       name="give_give_donations_export_option[donor_gender]"
			       id="donor-gender"><?php _e( 'Donor gender', 'give' ); ?>
		</label>
	</li>
	<?php
}

add_action( 'give_export_donation_standard_donor_fields', 'donor_gender_standard_donor_fields' );


/**
 * Add Donation donor gender in export donor fields tab.
 */
function donor_size_standard_donor_fields() {
	?>
	<li>
		<label for="donor-size">
			<input type="checkbox" checked
			       name="give_give_donations_export_option[donor_size]"
			       id="donor-size"><?php _e( 'Donor size', 'give' ); ?>
		</label>
	</li>
	<?php
}

add_action( 'give_export_donation_standard_donor_fields', 'donor_size_standard_donor_fields' );


/**
 * Add Donation donor gender header in CSV.
 *
 * @param array $cols columns name for CSV
 *
 * @return  array $cols columns name for CSV
 */
function donor_gender_update_columns_heading( $cols ) {
	if ( isset( $cols['donor_gender'] ) ) {
		$cols['donor_gender'] = __( 'Donor gender', 'give' );
	}

	return $cols;

}

add_filter( 'give_export_donation_get_columns_name', 'donor_gender_update_columns_heading' );


/**
 * Add Donation donor size header in CSV.
 *
 * @param array $cols columns name for CSV
 *
 * @return  array $cols columns name for CSV
 */
function donor_size_update_columns_heading( $cols ) {
	if ( isset( $cols['donor_size'] ) ) {
		$cols['donor_size'] = __( 'Donor size', 'give' );
	}

	return $cols;

}

add_filter( 'give_export_donation_get_columns_name', 'donor_size_update_columns_heading' );


/**
 * Add Donation engraving message fields in CSV.
 *
 * @param array Donation data.
 * @param Give_Payment $payment Instance of Give_Payment
 * @param array $columns Donation data $columns that are not being merge
 *
 * @return array Donation data.
 */
function donor_gender_export_donation_data( $data, $payment, $columns ) {
	if ( ! empty( $columns['donor_gender'] ) ) {
		$message              = $payment->get_meta( 'donor_gender' );
		$data['donor_gender'] = isset( $message ) ? wp_kses_post( $message ) : '';
	}

	return $data;
}

add_filter( 'give_export_donation_data', 'donor_gender_export_donation_data', 10, 3 );

/**
 * Add Donation engraving message fields in CSV.
 *
 * @param array Donation data.
 * @param Give_Payment $payment Instance of Give_Payment
 * @param array $columns Donation data $columns that are not being merge
 *
 * @return array Donation data.
 */
function donor_size_export_donation_data( $data, $payment, $columns ) {
	if ( ! empty( $columns['donor_size'] ) ) {
		$message              = $payment->get_meta( 'donor_size' );
		$data['donor_size'] = isset( $message ) ? wp_kses_post( $message ) : '';
	}

	return $data;
}

add_filter( 'give_export_donation_data', 'donor_size_export_donation_data', 10, 3 );

/**
 * Remove donor gender from Export donation standard fields.
 *
 * @param array $responses Contain all the fields that need to be display when donation form is display
 * @param int $form_id Donation Form ID
 *
 * @return array $responses
 */
function donor_gender_export_custom_fields( $responses, $form_id ) {

	if ( ! empty( $responses['standard_fields'] ) ) {
		$standard_fields = $responses['standard_fields'];
		if ( in_array( 'donor_gender', $standard_fields ) ) {
			$standard_fields              = array_diff( $standard_fields, array( 'donor_gender' ) );
			$responses['standard_fields'] = $standard_fields;
		}
	}

	return $responses;
}

add_filter( 'give_export_donations_get_custom_fields', 'donor_gender_export_custom_fields', 10, 2 );


/**
 * Remove donor size from Export donation standard fields.
 *
 * @param array $responses Contain all the fields that need to be display when donation form is display
 * @param int $form_id Donation Form ID
 *
 * @return array $responses
 */
function donor_size_export_custom_fields( $responses, $form_id ) {

	if ( ! empty( $responses['standard_fields'] ) ) {
		$standard_fields = $responses['standard_fields'];
		if ( in_array( 'donor_size', $standard_fields ) ) {
			$standard_fields              = array_diff( $standard_fields, array( 'donor_size' ) );
			$responses['standard_fields'] = $standard_fields;
		}
	}

	return $responses;
}

add_filter( 'give_export_donations_get_custom_fields', 'donor_size_export_custom_fields', 10, 2 );
