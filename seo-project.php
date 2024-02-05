<?php
/* * *
 * Plugin Name:         SEO Project
 * Plugin URI:          https://github.com/roambiz/seo-project
 * Description:         Landing Page Post Type[Produce].
 * Version:             1.0.0
 * File name:           seo-project.php
 * Author:              Singa
 * Author URI:          https://roambiz.com/
 * License:             GNU General Public License v3.0 or later
 * License URI:         https://www.gnu.org/licenses/gpl-3.0.html
 * 
 * Note: 
 * @ref https://developer.wordpress.org/plugins/plugin-basics/header-requirements/
 * @package SEO-Project
 * * *-------------------------------------------------------------------------------------------------------------- */


//////////////////// === Plugin Environment Setup === ////////////////////

// Verify WordPress Environment And Define Access Path;
if (!defined('ABSPATH')) {
    exit;
}

// Ensure WordPress core functionality is available. Exit if not.
if (!function_exists('add_action')) {
    exit;
}

// Define constants
defined('SEO_PROJECT_PLUGIN_DIR') or define('SEO_PROJECT_PLUGIN_DIR', plugin_dir_path(__FILE__));
defined('SEO_PROJECT_PLUGIN_FILE') or define('SEO_PROJECT_PLUGIN_FILE', __FILE__);

//////////////////// === Initialize Settings And Include Module === ////////////////////

// Include and Initialize SEO_Project_Settings
require_once SEO_PROJECT_PLUGIN_DIR . 'inc/settings.php';
add_action('init', array('SEO_Project_Settings', 'init'), 10);

// Include Class
require_once SEO_PROJECT_PLUGIN_DIR . 'modules/cpt.php';
include_once SEO_PROJECT_PLUGIN_DIR . 'modules/flush.php';

// Initialize Module
add_action('init', array('Module_CPT', 'init'), 5);
add_action('init', array('Flush_CPT', 'init'), 10);

//////////////////// === Set Activation or Deactivation Tasks === ////////////////////

// Set a one-time execution flag on plugin activation
register_activation_hook(SEO_PROJECT_PLUGIN_FILE, 'activate_sp_plugin_startup_task');
function activate_sp_plugin_startup_task() {
    // Task 1: Set a one-time execution flag
    if (!get_transient(Flush_CPT::CPT_TRANSIENT_FLAG)) {
        set_transient(Flush_CPT::CPT_TRANSIENT_FLAG, true, 0);
    }

    // Task 2: Additional tasks on activation
	add_action('admin_notices', function () {
		echo '<div class="notice notice-info is-dismissible"><p>Your plugin is activated. Please make sure to refresh the permalink structure.</p></div>';
	});
}

// Hook the function to plugin deactivation
register_deactivation_hook(SEO_PROJECT_PLUGIN_FILE, 'deactivate_sp_plugin_startup_task');
function deactivate_sp_plugin_startup_task() {
    // Task: Additional tasks on deactivation
    // Add more tasks here if needed

    // Refresh rewrite rules
    flush_rewrite_rules();

    // Delete Transient
    delete_transient(Module_CPT::NOTICE_TRANSIENT_FLAG);
}

#END#
?>