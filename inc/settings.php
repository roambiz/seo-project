<?php
/*
 * File: settings.php
 * Description: Include custom settings for the SEO Project plugin.
 * Path: inc/settings.php
 * 
 * This file is responsible for defining and handling the custom settings of the SEO Project plugin.
 * It includes the following settings:
 * 
 * - ['seo_project_menu_icon']: This setting represents the menu icon for SEO Project.
 *   It is used to customize the icon displayed in the WordPress admin menu.
 * 
 * - ['seo_project_label']: This setting defines the label for SEO Project.
 *   It is used to customize the label associated with the plugin in the WordPress admin menu.
 * 
 * - ['seo_project_slug']: This setting determines the slug for SEO Project.
 *   It is used to customize the URL slug associated with the plugin's settings page.
 */

class SEO_Project_Settings {
    const MENU_ICON_OPTION = 'seo_project_menu_icon';
    const LABEL_OPTION = 'seo_project_label';
    const SLUG_OPTION = 'seo_project_slug';

    public static function init() {
        // Check if the constant is defined
        if (defined('SEO_PROJECT_PLUGIN_DIR')) {
            $settings = new self();
            $settings->settings_setup();
        }
    }

    private function settings_setup() {
        // Add a settings page to the "Settings" menu
        add_action('admin_menu', array($this, 'add_settings_page'));

        // Register settings and fields
        add_action('admin_init', array($this, 'register_settings'));
    }

    // Add a settings page to the "Settings" menu
    public function add_settings_page() {
        add_options_page(
            'SEO Project Settings',
            'SEO Project',
            'manage_options',
            'seo_project_settings',
            array($this, 'settings_page_content')
        );
    }

    // Register settings and fields
    public function register_settings() {
        // Register a settings group
        register_setting('seo_project_settings_group', 'seo_project_settings', array($this, 'sanitize_settings'));
        // Add fields to the settings group
        add_settings_section('seo_project_general_section', 'General Settings', array($this, 'general_section_callback'), 'seo_project_settings');
        add_settings_field(self::MENU_ICON_OPTION, 'Menu Icon', array($this, 'menu_icon_callback'), 'seo_project_settings', 'seo_project_general_section');
        add_settings_field(self::LABEL_OPTION, 'Label', array($this, 'label_callback'), 'seo_project_settings', 'seo_project_general_section');
        add_settings_field(self::SLUG_OPTION, 'Slug', array($this, 'slug_callback'), 'seo_project_settings', 'seo_project_general_section');
    }

    // Callback function to display the settings page content
    public function settings_page_content() {
        ?>
        <div class="wrap">
            <h2>SEO Project Settings</h2>
            <form method="post" action="options.php">
                <?php settings_fields('seo_project_settings_group'); ?>
                <?php do_settings_sections('seo_project_settings'); ?>
                <?php submit_button(); ?>
            </form>
            <form method="post" action="">
            <input type="hidden" name="refresh_rules" value="1">
            <button type="submit">Flush</button>
        </form>
        </div>
        <?php

        if (isset($_POST['refresh_rules'])) {
            flush_rewrite_rules();
            echo "<p>The rewrite rules have been refreshed.</p>";
        }
    }

    // Sanitize and validate input
    public function sanitize_settings($input) {
        $sanitized_input = array();

        $sanitized_input[self::MENU_ICON_OPTION] = sanitize_text_field($input[self::MENU_ICON_OPTION]);
        $sanitized_input[self::LABEL_OPTION] = sanitize_text_field($input[self::LABEL_OPTION]);
        $sanitized_input[self::SLUG_OPTION] = sanitize_text_field($input[self::SLUG_OPTION]);
    
        return $sanitized_input;
    }

    // Callback functions to display fields
    public function menu_icon_callback() {
        $options = get_option('seo_project_settings');
        echo '<input type="text" name="seo_project_settings[' . self::MENU_ICON_OPTION . ']" value="' . esc_attr($options[self::MENU_ICON_OPTION]) . '" placeholder="dashicons-hammer" />';

    }

    public function label_callback() {
        $options = get_option('seo_project_settings');
        echo '<input type="text" name="seo_project_settings[' . self::LABEL_OPTION . ']" value="' . esc_attr($options[self::LABEL_OPTION]) . '" placeholder="Produce" />';

    }

    public function slug_callback() {
        $options = get_option('seo_project_settings');
        echo '<input type="text" name="seo_project_settings[' . self::SLUG_OPTION . ']" value="' . esc_attr($options[self::SLUG_OPTION]) . '" placeholder="produce" />';

    }

    // Callback function to display the section description
    public function general_section_callback($args) {
        echo 'Customize your SEO Project settings below:';
    }

}
