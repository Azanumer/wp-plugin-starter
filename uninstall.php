<?php
/**
 * Runs when the plugin is deleted (not just deactivated).
 * Cleans up the plugin's own data so it leaves no trace.
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Same key as My_Plugin_Starter::OPTION_KEY in the main file.
delete_option( 'mps_settings' );
