<?php

// If access directly, die
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MailpoetCustomField {

	public function __construct() {
		// Admin init
		add_action( 'admin_init', array( $this, 'admin_init' ), 20 );

	} // end of __construct

	public static function init() {

		$instance = false;

		if ( empty( $instance ) ) {
			$instance = new self();
		}
	}

	/**
	 * Translate text
	 */
	public function __( $text ) {
		return __( $text, 'add-on-contact-form-7-mailpoet' );
	}//end __()


	/**
	 * Admin init
	 */
	public function admin_init() {
		// Add Tag generator button
		if ( ! class_exists( 'WPCF7_TagGenerator' ) ) {
			return;
		}
		$tag_generator = WPCF7_TagGenerator::get_instance();

		$tag_generator->add(
			'cf',
			$this->__( 'MailPoet Custom field' ),
			array( $this, 'mailpoetsignup_cf' )
		);

	} //End of admin_init


	/**
	 * Get Mailpoet Custom fields
	 */
	public function mailpoetsignup_cf() {

		// Get subscriber fields
		$fields = \MailPoet\API\API::MP( 'v1' )->getSubscriberFields();
		// Remove defaults fields email, first_name and last_name
		unset( $fields[0] ); // email
		unset( $fields[1] ); // first_name
		unset( $fields[2] ); // last_name

		$results = array();
		foreach ( $fields as $field ) {
			$results[ $field['id'] ] = $field['name'];
		}

		if ( ! empty( $results ) ) {
			foreach ( $results as $key => $value ) {
				echo $this->__( 'MailPoet Custom field name: ' . $value . '<br>' );
				echo $this->__( 'Custom field ID (which should be used as contact form\'s field name): ' . '<strong>' . $key . '</strong>' );
				echo '<br>';
				echo '<br>';
			}
		} else {
			echo $this->__( 'No mailpoet custom field available.' );
		}
	} //End of mailpoetsignup_cf

}

MailpoetCustomField::init();
