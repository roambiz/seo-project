<?php
/*
 * Module: cpt.php
 * Description: Custom Post Type Module
 * Path: modules/cpt.php
 */

class Module_CPT {

    const NOTICE_TRANSIENT_FLAG = 'cpt_success_notice_displayed';

    public static function init() {
        $cpt = new self();
        $cpt->register_post_type();
        $cpt->add_role_capabilities();
        $cpt->add_cpt_to_query_loop();
    }

    private function register_post_type() {
        
        // Check if a child theme has registered that type name
        if (function_exists('register_post_type') && !post_type_exists('seo-project')) {
            // Notify that the post type registration did not occur
        } else {
            add_action('admin_notices', function () {
                echo '<div class="notice notice-warning is-dismissible"><p>Custom post type "seo-project" could not be registered.</p></div>';
            });
        }
        
        // Get SEO project settings
        $seo_project_settings = get_option('seo_project_settings');

        // If $seo_project_settings is not an array, use default values
        if (!is_array($seo_project_settings)) {
            // Default values
            $menu_icon = 'dashicons-hammer';
            $label = 'Produce';
            $slug = 'produce';
        } else {
            // Use values from settings or default values
            $menu_icon = !empty($seo_project_settings['seo_project_menu_icon']) ? $seo_project_settings['seo_project_menu_icon'] : 'dashicons-hammer';
            $label = !empty($seo_project_settings['seo_project_label']) ? $seo_project_settings['seo_project_label'] : 'Produce';
            $slug = !empty($seo_project_settings['seo_project_slug']) ? $seo_project_settings['seo_project_slug'] : 'produce';
        }

        // Set up custom labels text for the post type
        $labels = array(
            'name'                  => _x($label.'s', 'Post Type General Name', 'text_domain'),
            'singular_name'         => _x($label, 'Post Type Singular Name', 'text_domain'),
            'menu_name'             => __($label.'s', 'text_domain'),
            'name_admin_bar'        => __($label, 'text_domain'),
            'archives'              => __('Item Archives', 'text_domain'),
            'attributes'            => __('Item Attributes', 'text_domain'),
            'parent_item_colon'     => __('Parent '.$label, 'text_domain'),
            'all_items'             => __('All '.$label, 'text_domain'),
            'add_new_item'          => __('Add New '.$label, 'text_domain'),
            'add_new'               => __('New '.$label, 'text_domain'),
            'new_item'              => __('New Item', 'text_domain'),
            'edit_item'             => __('Edit '.$label, 'text_domain'),
            'update_item'           => __('Update '.$label, 'text_domain'),
            'view_item'             => __('View '.$label, 'text_domain'),
            'view_items'            => __('View Items', 'text_domain'),
            'search_items'          => __('Search Items', 'text_domain'),
            'not_found'             => __('No Items found', 'text_domain'),
            'not_found_in_trash'    => __('No Items found in Trash', 'text_domain'),
            'featured_image'        => __('Featured Image', 'text_domain'),
            'set_featured_image'    => __('Set featured image', 'text_domain'),
            'remove_featured_image' => __('Remove featured image', 'text_domain'),
            'use_featured_image'    => __('Use as featured image', 'text_domain'),
            'insert_into_item'      => __('Insert into item', 'text_domain'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
            'items_list'            => __('Items list', 'text_domain'),
            'items_list_navigation' => __('Items list navigation', 'text_domain'),
            'filter_items_list'     => __('Filter items list', 'text_domain'),
        );

        // Set up rewrite rules for URLs
        $rewrite = array(
            'slug'                  => $slug,
            'with_front'            => false,
            'pages'                 => false,
            'feeds'                 => false,
        );

        // Set up custom capabilities for the post type
        $capabilities = array(
            'edit_post'             => 'edit_produce',
            'read_post'             => 'read_produce',
            'delete_post'           => 'delete_produce',
            'edit_posts'            => 'edit_produces',
            'edit_others_posts'     => 'edit_others_produces',
            'publish_posts'         => 'publish_produces',
            'read_private_posts'    => 'read_private_produces',
        );

        // Set up arguments for registering the post type
        $args = array(
            'label'                 => __($label, 'text_domain'),
            'description'           => __('Landing Pages.', 'text_domain'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'trackbacks', 'revisions', 'custom-fields', 'thumbnail'),
            'taxonomies'            => array('category', 'post_tag'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => $menu_icon,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'rewrite'               => $rewrite,
            'capabilities'          => $capabilities,
            'show_in_rest'          => true,
        );

        // Register the custom post type using the register_post_type() function
        $custom_post_type_success = register_post_type('seo-project', $args);

        // Handle registration success or error
        if (!is_wp_error($custom_post_type_success)) {
            // Check if the transient is set to avoid repeated display of success notice
            if (!get_transient(self::NOTICE_TRANSIENT_FLAG)) {
                add_action('admin_notices', function () {
                    echo '<div class="notice notice-success is-dismissible"><p>Custom post type "seo-project" registered successfully!</p></div>';
                });

                // Set the transient to avoid repeated display
                set_transient(self::NOTICE_TRANSIENT_FLAG, true, 0);
            }
        } else {
            // Log the error
            error_log('Failed to register post type: ' . $custom_post_type_success->get_error_message());

            // Display a user-friendly error message
            add_action('admin_notices', function () {
                echo '<div class="notice notice-error is-dismissible"><p>Failed to register custom post type. Please check the error logs for more details.</p></div>';
            });
        }
    }

    private function add_role_capabilities() {
        // Define capabilities and add them to roles here
        $editor_capabilities = array(
            'edit_produce'          => true,
            'read_produce'          => true,
            'delete_produce'        => true,
            'edit_produces'         => true,
            'edit_others_produces'  => true,
            'publish_produces'      => true,
            'read_private_produces' => true,
        );

        $admin_capabilities = array_merge($editor_capabilities, array(
            'manage_options' => true,
        ));

        $editor_role = get_role('editor');
        if (!empty($editor_role)) {
            foreach ($editor_capabilities as $capability => $grant) {
                $editor_role->add_cap($capability, $grant);
            }
        }

        $admin_role = get_role('administrator');
        if (!empty($admin_role)) {
            foreach ($admin_capabilities as $capability => $grant) {
                $admin_role->add_cap($capability, $grant);
            }
        }
    }

    private function add_cpt_to_query_loop() {
        // Set the post type slug
        $cpt_slug = 'seo-project';
        // Set the 'post_type' parameter to include specified post types
        add_filter( 'pre_get_posts', function( $query ) use ( $cpt_slug ) {
            if ( $query->is_category() && 
                 $query->is_main_query() && 
                 post_type_exists( $cpt_slug ) 
            ) {
                $post_types = array( 'post', $cpt_slug ); 
                $query->set( 'post_type', $post_types );
            }
            return $query;
        });
    }

}

// Inc the class
