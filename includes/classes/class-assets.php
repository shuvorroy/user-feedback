<?php

namespace Sr_User_Feedback;

/**
 * The assets class
 */
class Assets {

	/**
	 * The constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_all_scripts' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_scripts' ) );
	}

	/**
	 * Register all scripts and styles
	 */
	public function register_all_scripts() {
		$styles  = $this->get_styles();
		$scripts = $this->get_scripts();
		$this->register_styles( $styles );
		$this->register_scripts( $scripts );
	}

	/**
	 * Get registered styles
	 *
	 * @return array
	 */
	public function get_styles() {
		// All CSS file list.
		$styles = array(
			'user-feedback-admin' => array(
				'src'     => USERFEEDBACK_ASSETS . 'css/admin.css',
				'deps'    => array(),
				'version' => filemtime( USERFEEDBACK_DIR . '/assets/css/admin.css' ),
			),
			'user-feedback-public' => array(
				'src'     => USERFEEDBACK_ASSETS . 'css/public.css',
				'deps'    => array(),
				'version' => filemtime( USERFEEDBACK_DIR . '/assets/css/public.css' ),
			),
		);

		return $styles;
	}

	/**
	 * Get all registered scripts
	 *
	 * @return array
	 */
	public function get_scripts() {
		// All JS file list.
		$scripts = array(
			'user-feedback-admin' => array(
				'src'       => USERFEEDBACK_ASSETS . 'js/admin.js',
				'deps'      => array( 'jquery', 'wp-util' ),
				'version'   => filemtime( USERFEEDBACK_DIR . 'assets/js/admin.js' ),
			),
			'user-feedback-public' => array(
				'src'       => USERFEEDBACK_ASSETS . 'js/public.js',
				'deps'      => array( 'jquery' ),
				'version'   => filemtime( USERFEEDBACK_DIR . 'assets/js/public.js' ),
			),
		);

		return $scripts;
	}

	/**
	 * Register scripts
	 *
	 * @param  array $scripts A collection of scripts file.
	 *
	 * @return void
	 */
	public function register_scripts( $scripts ) {
		foreach ( $scripts as $handle => $script ) {
			$deps      = isset( $script['deps'] ) ? $script['deps'] : false;
			$in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : true;
			$version   = isset( $script['version'] ) ? $script['version'] : USERFEEDBACK_VERSION;
			wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
		}
	}

	/**
	 * Register styles
	 *
	 * @param  array $styles A collection of style files.
	 */
	public function register_styles( $styles ) {
		foreach ( $styles as $handle => $style ) {
			$deps    = isset( $style['deps'] ) ? $style['deps'] : false;
			$version = isset( $style['version'] ) ? $style['version'] : USERFEEDBACK_VERSION;
			wp_register_style( $handle, $style['src'], $deps, $version );
		}
	}

	/**
	 * Enqueue front-end scripts
	 */
	public function enqueue_front_scripts() {
		$default_script = array(
			'ajaxurl'         => admin_url( 'admin-ajax.php' ),
			'security'        => wp_create_nonce( 'user_feedback' ),
			'current_page'    => 1,
			'confrim_remove'  => __( 'Sorry! no feedback found.', 'user-feedback' ),
			'input_errors'    => array(
				'email' => __( 'This email is invalid', 'user-feedback' ),
				'text' => __( 'This field is required', 'user-feedback' )
			)
		);

		$localize_data = apply_filters( 'user_feedback_localized_args', $default_script );

		// Enqueue scripts.
		wp_localize_script( 'user-feedback-public', 'user_feedback', $localize_data );
	}
}
