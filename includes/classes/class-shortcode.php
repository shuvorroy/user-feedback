<?php

namespace Sr_User_Feedback;

/**
 * The shortcode class
 */
class Shortcodes {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_shortcode( 'feeback_list', array( $this, 'show_feeback_list' ) );
	}


	/**
	 * Display feedback list in shortcode
	 *
	 * @param array $atts shortcode attribute.
	 *
	 * @return string
	 */
	public function show_feeback_list( $atts ) {
		wp_enqueue_script( 'user-feedback-public' );
		if ( current_user_can( 'manage_options' ) ) {
			wp_enqueue_script( 'user-feedback-admin' );
			wp_enqueue_style( 'user-feedback-admin' );
		}
		$atts = shortcode_atts( array(), $atts );
		ob_start();
		include_once USERFEEDBACK_DIR . 'views/frontend/list.php';
		return ob_get_clean();
	}
}
