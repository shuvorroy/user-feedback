<?php

namespace Sr_User_Feedback;

/**
 * The ajax class.
 */
class Ajax {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_action( 'wp_ajax_submit_feedback', array( $this, 'submit_feedback' ) );
		add_action( 'wp_ajax_nopriv_submit_feedback', array( $this, 'submit_feedback' ) );

		add_action( 'wp_ajax_get_user_feedbacks', array( $this, 'get_user_feedbacks' ) );
	}

	/**
	 * Submit User Feedback
	 */
	public function submit_feedback() {
		check_ajax_referer( 'user_feedback', 'security' );
		$response = array();
		$id = Db::insert( $_POST );

		if ( $id ) {
			$response['message'] = __( 'Thank you for sending us your feedback', 'user-feedback' );
			$response['class'] = 'success';
		} else {
			$response['message'] = __( 'Something went wrong!', 'user-feedback' );
			$response['class'] = 'error';
		}
		wp_send_json( $response );
	}

	/**
	 * Get User Feedback
	 */
	public function get_user_feedbacks() {
		check_ajax_referer( 'user_feedback', 'security' );
		if( isset( $_POST['single'] ) && $_POST['single'] ) {
			if ( isset( $_POST['remove'] ) && $_POST['remove'] ) {
				$response = Db::delete_item( $_POST['id'] );
			} else {
				$response = Db::get_item( $_POST['id'] );
			}
		} else  {
			$response = Db::get_items( $_POST );
		}
		wp_send_json( $response );
	}
}
