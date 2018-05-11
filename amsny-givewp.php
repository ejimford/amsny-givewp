<?php
/**
 * Plugin Name: Give - AMSNY Custom Fields
 * Plugin URI: https://givewp.com/documentation/developers/how-to-create-custom-form-fields/
 * Description: This plugin demonstrates adds custom fields to your Give give forms with validation, email functionality, and field data output on the payment record within wp-admin.
 * Version: 1.0
 * Author: WordImpress
 * Author URI: https://givewp.com
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * NOTE: This is not a "snippet" but a plugin that you can install and activate. You can put it in a
 * folder in your /plugins/ directory, or even just drop it directly into the /plugins/ directory
 * and it will activate like any other plugin.
 *
 * DISCLAIMER: This is provided as an EXAMPLE of how to do custom fields for Give. We provide no
 * guarantees if you put this on a live site. And we do not offer Support for this code at all.
 * It is simply a free resource for your purposes.
 */

/**
 * Custom Form Fields
 *
 * @param $form_id
 */
function amsny_give_donations_custom_form_fields( $form_id ) {

	// Only display for forms with the IDs "754" and "578";
	// Remove "If" statement to display on all forms
	// For a single form, use this instead:
	// if ( $form_id == 754) {
	//$forms = array( 754, 578 );

	//if ( in_array( $form_id, $forms ) ) {
		?>
		<div id="give-message-wrap" class="form-row form-row-wide">
            <label class="show-checkbox"><input type="checkbox" id="show-earmark" name="show-earmark"> Choose a program for this gift to support</label>    
            <ul class="give-radio-list" id="give-earmark-list">
                <?php
                    if (have_rows('earmark_options', 'option')):
                        while( have_rows('earmark_options', 'option') ): the_row();
                            $earmark_name = get_sub_field('earmark_name');
                            $earmark_class = sanitize_title_with_dashes($earmark_name);
                            $earmark_length = strlen($earmark_name);
                            $earmark_tooltip = get_sub_field('earmark_tooltip');
    
                ?>
                <li <?php if ($earmark_length > 19) { echo ' class="long-radio-li"'; } ?>>
                    <input type="radio" name="give_earmark" class="give-radio" id="give-earmark-<?php echo $earmark_class; ?>" value="<?php echo $earmark_name; ?>">
                    <label for="give-earmark-<?php echo $earmark_class; ?>" class="give-radio-option give-tooltip" id="give-radio-option-<?php echo $earmark_class; ?>" data-tooltip="<?php echo $earmark_tooltip; ?>"> <?php echo $earmark_name; ?></label>
                </li>
                <?php            
                        endwhile;
    
                    endif;
                ?>
                
                
            </ul>
        </div>
		<?php
	//}
}

add_action( 'give_after_donation_levels', 'amsny_give_donations_custom_form_fields', 10, 1 );

/* ---- */

function amsny_give_donations_custom_form_contact_fields( $form_id ) {

		?>
		<p id="give-phone-wrap" class="form-row form-row-last form-row-responsive">
            <label for="give_phone">Phone Number</label>
            <input type="text" id="give-phone" name="give_phone" class="give-input" placeholder="***-***-****" value="">
        </p>
		<p id="give-ok-contact-wrap" class="form-row form-row-first form-row-responsive">
            <label><input type="checkbox" name="give_ok_to_contact" checked> AMSNY may contact me in the future</label>
        </p>
		<p id="give-anonymous-wrap" class="form-row form-row-last form-row-responsive">
            <label class="checkbox"><input type="checkbox" id="make-anonymous" name="make_anonymous"> Make my donation anonymous</label>    
        </p>
            


        <?php
}

add_action( 'give_purchase_form_before_cc_form', 'amsny_give_donations_custom_form_contact_fields', 11, 1 );


function amsny_give_donations_custom_form_comment_fields( $form_id ) {

		?>
		<div class="form-row form-row-wide">
            <legend>Comments</legend>
            <textarea name="give_comments" placeholder="Let us know why you donated, tell your story, or send words of encouragement."></textarea>
        </div>
		<div id="give-is-amsny-alum-wrap" class="form-row form-row-wide">
            <label class="show-checkbox"><input type="checkbox" id="is-alum" name="is-alum" value="I am an Alum of AMSNY Programs"> I'm an Alum of AMSNY Programs</label>    
        </div>
		<div id="give-amsny-alum-program-wrap" class="form-row form-row-wide">
            <input type="text" id="give-amsny-alum-program" name="give-amsny-alum-program" class="give-input" placeholder="If yes, in what program were you enrolled?" value="">
        </div>
		<?php
}

add_action( 'give_purchase_form_after_cc_form', 'amsny_give_donations_custom_form_comment_fields', 11, 1 );


function amsny_give_donations_ecard_selection( $form_id ) {

		?>
		<div id="give-ecard-select-wrap" class="form-row form-row-wide">
            <ul class="give-radio-list" id="give-ecard-list">
                <li>
                    <input type="radio" name="give_ecard_image" class="give-radio" id="give-ecard-1" value="eCard 1">
                    <label for="give-ecard-1" class="give-radio-option give-tooltip" id="give-radio-option-1">eCard 1</label>
                </li>
            </ul>
        </div>
		<?php
}
add_action( 'give_tributes_form_after_dedicate_donation', 'amsny_give_donations_ecard_selection', 11, 1 );




/**
 * Add Field to Payment Meta
 *
 * Store the custom field data custom post meta attached to the `give_payment` CPT.
 *
 * @param $payment_id
 * @param $payment_data
 *
 * @return mixed
 */
function amsny_give_donations_save_custom_fields( $payment_id, $payment_data ) {

	if ( isset( $_POST['give_earmark'] ) ) {
		$message = wp_strip_all_tags( $_POST['give_earmark'], true );
		add_post_meta( $payment_id, 'give_earmark', $message );
	}
	if ( isset( $_POST['give_phone'] ) ) {
		$message = wp_strip_all_tags( $_POST['give_phone'], true );
		add_post_meta( $payment_id, 'give_phone', $message );
	}
	if ( isset( $_POST['make_anonymous'] ) ) {
		$message = wp_strip_all_tags( $_POST['make_anonymous'], true );
		add_post_meta( $payment_id, 'make_anonymous', $message );
	}
	if ( isset( $_POST['give_comments'] ) ) {
		$message = wp_strip_all_tags( $_POST['give_comments'], true );
		add_post_meta( $payment_id, 'give_comments', $message );
	}
	if ( isset( $_POST['give_ok_to_contact'] ) ) {
		$message = wp_strip_all_tags( $_POST['give_ok_to_contact'], true );
		add_post_meta( $payment_id, 'give_ok_to_contact', $message );
	}
    
}

add_action( 'give_insert_payment', 'amsny_give_donations_save_custom_fields', 10, 2 );

/**
 * Show Data in Transaction Details
 *
 * Show the custom field(s) on the transaction page.
 *
 * @param $payment_id
 */
function amsny_give_donations_donation_details( $payment_id ) {

	$donation_earmark = give_get_meta( $payment_id, 'give_earmark', true );
	$donation_phone = give_get_meta( $payment_id, 'give_phone', true );
	$donation_comments = give_get_meta( $payment_id, 'give_comments', true );
	$donation_ok_to_contact = give_get_meta( $payment_id, 'give_ok_to_contact', true );
	$make_anonymous = give_get_meta( $payment_id, 'make_anonymous', true );

    
    
    if ( $donation_earmark ) : ?>
		<div id="give-earmark" class="postbox">
			<h3 class="hndle"><?php esc_html_e( 'Earmark', 'give' ); ?></h3>
			<div class="inside" style="padding-bottom:10px;">
				<?php echo wpautop( $donation_earmark ); ?>
			</div>
		</div>
	<?php endif;

    if ( $donation_phone ) : ?>
		<div id="give-earmark" class="postbox">
			<h3 class="hndle"><?php esc_html_e( 'Phone', 'give' ); ?></h3>
			<div class="inside" style="padding-bottom:10px;">
				<?php echo wpautop( $donation_phone ); ?>
			</div>
		</div>
	<?php endif;

    if ( $make_anonymous ) : ?>
		<div id="give-earmark" class="postbox">
			<h3 class="hndle"><?php esc_html_e( 'Make Donation Anonymous', 'give' ); ?></h3>
			<div class="inside" style="padding-bottom:10px;">
				<?php echo wpautop( $make_anonymous ); ?>
			</div>
		</div>
	<?php endif;
    if ( $donation_comments ) : ?>
		<div id="give-earmark" class="postbox">
			<h3 class="hndle"><?php esc_html_e( 'Comments', 'give' ); ?></h3>
			<div class="inside" style="padding-bottom:10px;">
				<?php echo wpautop( $donation_comments ); ?>
			</div>
		</div>
	<?php endif;

    if ( $donation_ok_to_contact ) : ?>
		<div id="give-earmark" class="postbox">
			<h3 class="hndle"><?php esc_html_e( 'Ok to Contact this Person', 'give' ); ?></h3>
			<div class="inside" style="padding-bottom:10px;">
				<?php echo wpautop( $donation_ok_to_contact ); ?>
			</div>
		</div>
	<?php endif;
}
add_action( 'give_view_order_details_billing_before', 'amsny_give_donations_donation_details', 10, 1 );

/**
 * Adds a Custom Email Tags
 *
 * This function creates a custom Give email template tag.
 */
function amsny_add_sample_referral_tag() {

	give_add_email_tag(
			'donation_earmark',
			'This outputs the Earmark',
			'amsny_get_donation_referral_data'
	);

    
    
    

}

add_action( 'give_add_email_tags', 'amsny_add_sample_referral_tag' );

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
function amsny_get_donation_referral_data( $tag_args ) {

	$donation_earmark = give_get_meta( $tag_args['payment_id'], 'give_earmark', true );

    $output = __( 'No referral data found.', 'give' );

	if ( ! empty( $donation_earmark ) ) {
		$output = wp_kses_post( $donation_earmark );
	}
	return $output;
}