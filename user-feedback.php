<?php
/**
 * Codeable Assessment
 *
 * @package shuvorroy/user-feedback
 * 
 * Plugin Name:       User Feedback
 * Plugin URI:        https://github.com/shuvorroy
 * Description:       A plugin for user feedback
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Shuvo Roy
 * Author URI:        https://github.com/shuvorroy
 * License:           GNU General Public License v2 or later
 * Text Domain:       user-feedback
 * Domain Path:       /languages
 */

namespace Sr_User_Feedback;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';
/**
 * The main plugin class
 */
final class User_Feedback {
    /**
     * Instance of self
     *
     * @var User_Feedback
     */
    private static $instance = null;

    /**
	 * Class construcotr
	 */
	private function __construct() {
		$this->define_constants();

		add_action( 'init', array( $this, 'localization_setup' ) );
		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
	}

    /**
     * Initializes the Main class
     *
     * Checks for an existing Main instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {

        if ( self::$instance === null ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
	 * Define the required plugin constants
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'USERFEEDBACK', __FILE__ );
		define( 'USERFEEDBACK_NAME', 'user-feedback' );
		define( 'USERFEEDBACK_VERSION', '1.0.0' );
		define( 'USERFEEDBACK_DIR', trailingslashit( plugin_dir_path( USERFEEDBACK ) ) );
		define( 'USERFEEDBACK_URL', trailingslashit( plugin_dir_url( USERFEEDBACK ) ) );
		define( 'USERFEEDBACK_ASSETS', trailingslashit( USERFEEDBACK_URL . 'assets' ) );
		define( 'USERFEEDBACK_REST_BASE', 'user-feedback/' );
	}

    /**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	public function init_plugin() {
		new Assets();
		new Blocks();
		new Ajax();
		new Shortcodes();
	}

    /**
	 * Initialize plugin for localization
	 *
	 * @uses load_plugin_textdomain()
	 */
	public function localization_setup() {
		load_plugin_textdomain( USERFEEDBACK_NAME, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

    /**
	 * Placeholder for activation function
	 *
	 * @return void
	 */
	public function activate() {
		Db::init_db();
	}

    /**
	 * Placeholder for deactivation function
	 *
	 * @return void
	 */
	public function deactivate() {

	}
}

/**
 * Load User_Feedback when all plugins loaded
 */

$user_feedback = User_Feedback::init();