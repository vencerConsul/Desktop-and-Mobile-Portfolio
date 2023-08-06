<?php

/**
 * Plugin Name: Portfolio Desktop and Mobile
 * Plugin URI: https://github.com/vencerConsul
 * Description: Adds a custom post type for portfolio items with desktop and Mobile images.
 * Version: 1.0.0
 * Author: Vencer Olermo
 * Text Domain: portfolio-desktop-and-tablet
 * Author URI: https://github.com/vencerConsul
 * License: GPL2
 */
if (!defined('ABSPATH')) {
    echo 'Dont';
    exit;
}

class PortfolioDesktopAndTablet
{
    public function __construct()
    {
        add_action('init', array($this, 'create_custom_post_type'));
        add_action('add_meta_boxes', array($this, 'pdt_add_custom_fields'));
        add_action('save_post_portfolio', array($this, 'pdt_save_custom_fields'));
        add_action('admin_enqueue_scripts', array($this, 'loaadJavascript'));
        add_action('wp_enqueue_scripts', array($this, 'loadStyles'));
        add_shortcode('pdt_portfolio', array($this, 'pdt_portfolio_shortcode'));
    }

    public function loaadJavascript()
    {
        wp_enqueue_media();
        wp_enqueue_style('portfolio', plugin_dir_url(__FILE__) . 'assets/css/pdt-admin-style.css', array(), 1, 'all');
        wp_enqueue_script('portfolio', plugin_dir_url(__FILE__) . 'assets/js/pdt-scripts.js', array('jquery'), 1, true);
    }

    public function loadStyles()
    {
        wp_enqueue_style('portfolio', plugin_dir_url(__FILE__) . 'assets/css/pdt-style.css', array(), 1, 'all');
    }


    public function create_custom_post_type()
    {
        register_post_type('portfolio', [
            'label' => __('Portfolio', 'txtdomain'),
            'public' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-layout',
            'supports' => ['title', 'revisions', 'author'],
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'portfolio-desktop-and-tablet'],
            'labels' => [
                'name' => __('Portfolio', 'txtdomain'),
                'singular_name' => __('Portfolio Item', 'txtdomain'),
                'add_new_item' => __('Add New Portfolio Item', 'txtdomain'),
                'new_item' => __('Add New', 'txtdomain'),
                'edit_item'          => 'Edit Portfolio Item',
                'not_found' => __('No portfolio items found.', 'txtdomain'),
                'not_found_in_trash' => __('No portfolio items found in Trash.', 'txtdomain'),
                'all_items' => __('All Portfolio Items', 'txtdomain'),
                'insert_into_item' => __('Insert into Items', 'txtdomain')
            ],
        ]);
    }

    public function pdt_add_custom_fields()
    {
        add_meta_box(
            'pdt_custom_fields',
            'Desktop and Tablet Images',
            array($this, 'pdt_render_custom_fields'),
            'portfolio',
            'normal',
            'high'
        );
    }

    public function pdt_render_custom_fields($post)
    {
        $desktop_image = get_post_meta($post->ID, 'desktop_image', true);
        $tablet_image = get_post_meta($post->ID, 'tablet_image', true);
        $website_link = get_post_meta($post->ID, 'website_link', true);
        $website_description = get_post_meta($post->ID, 'website_description', true);
?>
        <p style="font-weight:bold;">To display the list of portfolio, use this shortcode <code style="color:red;">[pdt_portfolio]</code></p>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); grid-gap: 20px;">
            <div>
                <p>
                    <label for="desktop_image"><strong>Desktop Image:</strong></label>
                    <input type="text" name="desktop_image" id="desktop_image" class="widefat" value="<?php echo esc_attr($desktop_image); ?>" />
                    <input type="button" name="upload_desktop_image_button" id="upload_desktop_image_button" class="button-secondary" value="Upload Image" style="margin-top:10px;" />
                <div class="img-render-desktop" style="height: auto;max-height: 500px;overflow: auto;">
                    <img src="" alt="Desktop Image" id="preview-desktop" style="max-width: 100%;width:100%;" />
                    <?php if ($desktop_image) : ?>
                        <img src="<?php echo esc_attr($desktop_image); ?>" class="has-preview" alt="Desktop Image" style="max-width: 100%;width:100%;" />
                        <input type="button" name="remove_desktop_image_button" id="remove_desktop_image_button" class="button-secondary" value="Remove Image" style="margin-top:10px;" />
                    <?php endif; ?>
                </div>
                </p>
            </div>
            <div>
                <p>
                    <label for="tablet_image"><strong>Mobile Image:</strong></label>
                    <input type="text" name="tablet_image" id="tablet_image" class="widefat" value="<?php echo esc_attr($tablet_image); ?>" />
                    <input type="button" name="upload_tablet_image_button" id="upload_tablet_image_button" class="button-secondary" value="Upload Image" style="margin-top:10px;" />
                <div class="img-render-tablet" style="height: auto;max-height: 500px;overflow: auto;">
                    <img src="" alt="Tablet Image" id="preview-tablet" style="max-width: 100%;width:100%;" />
                    <?php if ($tablet_image) : ?>
                        <img src="<?php echo esc_attr($tablet_image); ?>" class="has-preview" alt="Tablet Image" style="max-width: 100%;width:100%;" />
                        <input type="button" name="remove_tablet_image_button" id="remove_tablet_image_button" class="button-secondary" value="Remove Image" style="margin-top:10px;" />
                    <?php endif; ?>
                </div>
                </p>
            </div>
        </div>
        <hr>
        <p>
            <label for="website_link">Website Link:</label>
            <input type="url" name="website_link" id="website_link" value="<?php echo esc_attr($website_link); ?>" class="widefat" />
        </p>
        <p> <!-- Added lines -->
            <label for="website_description">Website Description:</label>
            <textarea name="website_description" id="website_description" class="widefat" placeholder="Write a description of your website"><?php echo esc_attr($website_description); ?></textarea>
        </p>
<?php
    }

    public function pdt_save_custom_fields($post_id)
    {
        // Check if this is an autosave or a revision
        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            return;
        }

        // Check if the post is being updated
        if (isset($_POST['action'])) {
            $desktop_image = isset($_POST['desktop_image']) ? sanitize_text_field($_POST['desktop_image']) : '';
            $tablet_image = isset($_POST['tablet_image']) ? sanitize_text_field($_POST['tablet_image']) : '';
            $website_description = isset($_POST['website_description']) ? sanitize_text_field($_POST['website_description']) : '';

            // Perform validation
            if (empty($desktop_image) && empty($tablet_image)) {
                wp_die('Please upload at least one image.');
            }

            if(empty($website_description)){
                wp_die('Please dont leave the website decription empty.');
            }

            update_post_meta($post_id, 'desktop_image', $desktop_image);
            update_post_meta($post_id, 'tablet_image', $tablet_image);
            update_post_meta($post_id, 'website_description', $website_description);

            if (isset($_POST['website_link']) && !empty($_POST['website_link'])) {
                $website_link = sanitize_text_field($_POST['website_link']);
                update_post_meta($post_id, 'website_link', $website_link);
            } else {
                wp_die('Please provide a website link.');
            }
        }
    }

    public function pdt_portfolio_shortcode()
    {
        $args = array(
            'post_type'      => 'portfolio',
            'posts_per_page' => -1,
        );

        $query = new WP_Query($args);

        $html = '<div class="v-portfolio-wrapper">';
        $html .= '<div class="v-portfolio-row">';

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                // Display post title, desktop image, tablet image, and website link
                $desktop_image = get_post_meta(get_the_ID(), 'desktop_image', true);
                $tablet_image = get_post_meta(get_the_ID(), 'tablet_image', true);
                $website_link = get_post_meta(get_the_ID(), 'website_link', true);
                $website_description = get_post_meta(get_the_ID(), 'website_description', true);

                $html .= '<div class="v-portfolio-col">';
                $html .= '<div class="v-portfolio-content">';
                $html .= '<div class="v-desktop-view">';
                $html .= '<img src="'.plugin_dir_url(__FILE__).'/assets/images/desktop-overlay.png" class="v-desktop-view-img" alt="desktop">';
                $html .= '<div class="v-desktop-scroll">';
                $html .= '<img src="'.$desktop_image.'" alt="desktop">';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="v-mobile-view">';
                $html .= '<img src="'.plugin_dir_url(__FILE__).'/assets/images/mobile-view.png" class="v-mobile-view-img" alt="mobile">';
                $html .= '<div class="v-mobile-scroll">';
                $html .= '<img src="'.$tablet_image.'" alt="mobile">';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="v-content">';
                $html .= '<p class="c-content-title">'.$website_description.'</p>';
                $html .= '<a href="'.$website_link.'" target="_blank" class="c-content-button">';
                $html .= '<img src="'.plugin_dir_url(__FILE__).'/assets/images/link.png" alt="">';
                $html .= '</a>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
            wp_reset_postdata();
        } else {
            echo 'No portfolio items found.';
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}

new PortfolioDesktopAndTablet;
