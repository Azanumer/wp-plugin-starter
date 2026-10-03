<?php
/**
 * Plugin Name:       My Plugin Starter
 * Plugin URI:        https://example.com
 * Description:       Minimal, secure, OOP WordPress plugin boilerplate — settings page included.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Your Name
 * License:           MIT
 * Text Domain:       my-plugin-starter
 */

// Exit if accessed directly — never let plugin files run standalone.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'MPS_VERSION', '1.0.0' );
define( 'MPS_PLUGIN_FILE', __FILE__ );
define( 'MPS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Main plugin class. Single class keeps the boilerplate tiny;
 * grow into /includes files when the plugin gets real.
 */
final class My_Plugin_Starter {

    /** Option key used by uninstall.php for cleanup. */
    const OPTION_KEY = 'mps_settings';

    /**
     * Singleton instance.
     *
     * @return self
     */
    public static function instance() {
        static $instance = null;
        if ( null === $instance ) {
            $instance = new self();
        }
        return $instance;
    }

    /** Wire up all hooks. */
    private function __construct() {
        add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_shortcode( 'mps_hello', array( $this, 'hello_shortcode' ) );
    }

    /** Runs once on activation: set defaults, flush rewrite rules. */
    public static function activate() {
        if ( false === get_option( self::OPTION_KEY ) ) {
            add_option( self::OPTION_KEY, array( 'greeting' => 'Hello, world!' ) );
        }
        flush_rewrite_rules();
    }

    /** Runs on deactivation: always flush rewrites so no 404s linger. */
    public static function deactivate() {
        flush_rewrite_rules();
    }

    /** Add "Settings → My Plugin" page. */
    public function register_settings_page() {
        add_options_page(
            __( 'My Plugin Settings', 'my-plugin-starter' ),
            __( 'My Plugin', 'my-plugin-starter' ),
            'manage_options',
            'my-plugin-starter',
            array( $this, 'render_settings_page' )
        );
    }

    /** Register one text field via the Settings API (handles nonce + sanitization). */
    public function register_settings() {
        register_setting(
            'mps_settings_group',
            self::OPTION_KEY,
            array( 'sanitize_callback' => array( $this, 'sanitize_settings' ) )
        );

        add_settings_section( 'mps_main', __( 'Main', 'my-plugin-starter' ), '__return_false', 'my-plugin-starter' );

        add_settings_field(
            'greeting',
            __( 'Greeting text', 'my-plugin-starter' ),
            array( $this, 'render_greeting_field' ),
            'my-plugin-starter',
            'mps_main'
        );
    }

    /**
     * Sanitize the whole settings array on save.
     *
     * @param array $input Raw submitted values.
     * @return array Clean values.
     */
    public function sanitize_settings( $input ) {
        $clean = array();
        $clean['greeting'] = isset( $input['greeting'] )
            ? sanitize_text_field( wp_unslash( $input['greeting'] ) )
            : '';
        return $clean;
    }

    /** Render the greeting text field (escaped on output — always). */
    public function render_greeting_field() {
        $options  = get_option( self::OPTION_KEY, array() );
        $greeting = isset( $options['greeting'] ) ? $options['greeting'] : '';
        printf(
            '<input type="text" name="%s[greeting]" value="%s" class="regular-text" />',
            esc_attr( self::OPTION_KEY ),
            esc_attr( $greeting )
        );
    }

    /** Render the settings page shell. */
    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'mps_settings_group' );
                do_settings_sections( 'my-plugin-starter' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Demo shortcode: [mps_hello] prints the saved greeting.
     *
     * @return string Escaped greeting paragraph.
     */
    public function hello_shortcode() {
        $options  = get_option( self::OPTION_KEY, array() );
        $greeting = isset( $options['greeting'] ) ? $options['greeting'] : '';
        return '<p>' . esc_html( $greeting ) . '</p>';
    }
}

// Boot the plugin.
My_Plugin_Starter::instance();

// Activation/deactivation must be registered from the main file.
register_activation_hook( __FILE__, array( 'My_Plugin_Starter', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'My_Plugin_Starter', 'deactivate' ) );
