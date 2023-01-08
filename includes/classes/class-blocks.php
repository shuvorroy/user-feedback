<?php

namespace Sr_User_Feedback;

/**
 * The blocks class
 */
class Blocks {

	/**
	 * The constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_blocks' ) );
	}

	/**
	 * Register all blocks
	 */
	public function register_blocks() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		register_block_type( USERFEEDBACK_DIR . 'includes/blocks/user-form', array(
			'render_callback' => array( $this, 'render_feedback_form' )
		) );
		wp_set_script_translations( 'user-feedback-blocks', 'user-feedback' );
	}

	/**
	 * Render feedback form
	 * 
	 * @param array $block_attributes block attributes.
	 * @param string $content block content.
	 * 
	 * @return string
	 */
	public function render_feedback_form( $block_attributes, $content ) {
		wp_enqueue_script( 'user-feedback-public' );
		wp_enqueue_style( 'user-feedback-public' );
		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();;
			$content = str_replace( "[user_firstname]", $current_user->user_firstname ?: '', $content );
			$content = str_replace( "[user_lastname]", $current_user->user_lastname ?: '', $content );
			$content = str_replace( "[user_email]", $current_user->user_email ?: '', $content );
		} else {
			$content = str_replace( "[user_firstname]", '', $content );
			$content = str_replace( "[user_lastname]", '', $content );
			$content = str_replace( "[user_email]", '', $content );
		}
		return $content;
	}
}
