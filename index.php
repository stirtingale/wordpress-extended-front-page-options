<?php

/**
 * Plugin Name: Extended Front Page
 * Description: Allows any post type to be set as the front page, not just pages
 * Version: 1.0
 * Author: Thomas James Hole
 * Author URI: https://stirtingale.com
 * Plugin URI: https://stirtingale.com
 * Text Domain: extended-front-page
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Extended_Front_Page
{

    public function __construct()
    {
        add_action('admin_init', array($this, 'add_front_page_settings'));
        add_action('pre_get_posts', array($this, 'handle_front_page_query'));
        add_filter('display_post_states', array($this, 'add_front_page_state'), 10, 2);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Hook into WordPress's front page dropdown to extend it
        add_filter('get_pages', array($this, 'extend_front_page_dropdown'), 10, 2);
    }

    /**
     * Add custom front page settings to Reading Settings
     */
    public function add_front_page_settings()
    {
        // Add settings section
        add_settings_section(
            'extended_front_page_section',
            'Extended Front Page Settings',
            array($this, 'front_page_section_callback'),
            'reading'
        );

        // Register the setting
        register_setting('reading', 'extended_front_page_post_id', 'intval');
        register_setting('reading', 'extended_front_page_enabled', 'intval');

        // Add the field
        add_settings_field(
            'extended_front_page_enabled',
            'Use Extended Front Page',
            array($this, 'extended_front_page_checkbox_callback'),
            'reading',
            'extended_front_page_section'
        );

        add_settings_field(
            'extended_front_page_post_id',
            'Choose Front Page Content',
            array($this, 'extended_front_page_dropdown_callback'),
            'reading',
            'extended_front_page_section'
        );

        // Hook into the reading settings page to add our custom JavaScript
        add_action('admin_footer-options-reading.php', array($this, 'add_admin_script'));
    }

    public function front_page_section_callback()
    {
        echo '<p>Select any post, page, or custom post type to display as your front page.</p>';
    }

    public function extended_front_page_checkbox_callback()
    {
        $enabled = get_option('extended_front_page_enabled', 0);
        echo '<label>';
        echo '<input type="checkbox" name="extended_front_page_enabled" value="1" ' . checked(1, $enabled, false) . ' />';
        echo ' Enable extended front page (allows any post type as front page)';
        echo '</label>';
    }

    public function extended_front_page_dropdown_callback()
    {
        $current_post_id = get_option('extended_front_page_post_id', 0);
        $enabled = get_option('extended_front_page_enabled', 0);

        echo '<select name="extended_front_page_post_id" id="extended_front_page_post_id"' . (!$enabled ? ' disabled' : '') . '>';
        echo '<option value="0">— Select —</option>';

        // Get all public post types
        $post_types = get_post_types(array('public' => true), 'objects');

        foreach ($post_types as $post_type) {
            echo '<optgroup label="' . esc_attr($post_type->labels->name) . '">';

            $posts = get_posts(array(
                'post_type' => $post_type->name,
                'post_status' => 'publish',
                'numberposts' => -1,
                'orderby' => 'title',
                'order' => 'ASC'
            ));

            foreach ($posts as $post) {
                $selected = selected($current_post_id, $post->ID, false);
                echo '<option value="' . esc_attr($post->ID) . '" ' . $selected . '>';
                echo esc_html($post->post_title) . ' (' . esc_html($post_type->labels->singular_name) . ')';
                echo '</option>';
            }

            echo '</optgroup>';
        }

        echo '</select>';

        if (!$enabled) {
            echo '<p class="description">Enable extended front page above to use this option.</p>';
        }
    }

    /**
     * Add JavaScript to handle the checkbox enabling/disabling the dropdown
     */
    public function add_admin_script()
    {
?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                var checkbox = $('input[name="extended_front_page_enabled"]');
                var dropdown = $('#extended_front_page_post_id');

                function toggleDropdown() {
                    if (checkbox.is(':checked')) {
                        dropdown.prop('disabled', false);
                    } else {
                        dropdown.prop('disabled', true);
                    }
                }

                // Initial state
                toggleDropdown();

                // Handle checkbox change
                checkbox.change(function() {
                    toggleDropdown();
                });
            });
        </script>
<?php
    }

    /**
     * Handle the front page query
     */
    public function handle_front_page_query($query)
    {
        // Only modify the main query on the front page
        if (!is_admin() && $query->is_main_query() && is_front_page()) {
            $enabled = get_option('extended_front_page_enabled', 0);
            $post_id = get_option('extended_front_page_post_id', 0);

            if ($enabled && $post_id) {
                // Get the post to determine its type
                $post = get_post($post_id);

                if ($post && $post->post_status === 'publish') {
                    // Modify the query to fetch our specific post
                    $query->set('post_type', $post->post_type);
                    $query->set('p', $post_id);
                    $query->set('posts_per_page', 1);

                    // Make sure it's treated as a singular page
                    $query->is_home = false;
                    $query->is_front_page = true;
                    $query->is_singular = true;
                    $query->is_single = ($post->post_type !== 'page');
                    $query->is_page = ($post->post_type === 'page');
                }
            }
        }
    }

    /**
     * Add "Front Page" state to posts list
     */
    public function add_front_page_state($post_states, $post)
    {
        $enabled = get_option('extended_front_page_enabled', 0);
        $front_page_id = get_option('extended_front_page_post_id', 0);

        if ($enabled && $front_page_id == $post->ID) {
            $post_states['extended_front_page'] = __('Front Page');
        }

        return $post_states;
    }
}

// Initialize the plugin
new Extended_Front_Page();

/**
 * Helper function to check if a post is the extended front page
 */
function is_extended_front_page($post_id = null)
{
    if (!$post_id) {
        global $post;
        $post_id = $post ? $post->ID : 0;
    }

    $enabled = get_option('extended_front_page_enabled', 0);
    $front_page_id = get_option('extended_front_page_post_id', 0);

    return $enabled && $front_page_id == $post_id;
}

/**
 * Template function to get the extended front page ID
 */
function get_extended_front_page_id()
{
    $enabled = get_option('extended_front_page_enabled', 0);
    if ($enabled) {
        return get_option('extended_front_page_post_id', 0);
    }
    return 0;
}
