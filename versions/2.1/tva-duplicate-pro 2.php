<?php
/**
 * Plugin Name: tva Duplicate Pro
 * Description: Erstellt Kopien von Seiten, Beiträgen und WooCommerce-Produkten mit einem Klick
 * Version: 2.1
 * Author: tva.sg
 * Author URI: https://www.tva.sg
 * Text Domain: tva-duplicate-pro
 */

namespace tvaDuplicatePro;

// Verhindere direkten Zugriff
if (!defined('ABSPATH')) {
    exit;
}

// Plugin Klasse
class tvaDuplicatePro {
    private static $instance = null;
    private $version = '2.1';

    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Hooks initialisieren
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
        
        add_action('admin_init', [$this, 'checkVersion']);
        add_filter('post_row_actions', [$this, 'addDuplicateLink'], 10, 2);
        add_filter('page_row_actions', [$this, 'addDuplicateLink'], 10, 2);
        add_filter('product_row_actions', [$this, 'addDuplicateLink'], 10, 2);
        add_action('admin_action_duplicate_post_as_draft', [$this, 'duplicatePostAsDraft']);
        add_filter('plugin_row_meta', [$this, 'pluginRowMeta'], 10, 2);
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'pluginActionLinks']);
    }

    public function activate() {
        update_option('tva_duplicate_pro_version', $this->version);
    }

    public function deactivate() {
        // Cleanup wenn nötig
    }

    public function checkVersion() {
        if (get_option('tva_duplicate_pro_version') !== $this->version) {
            $this->updatePlugin();
        }
    }

    private function updatePlugin() {
        update_option('tva_duplicate_pro_version', $this->version);
    }

    public function addDuplicateLink($actions, $post) {
        if (current_user_can('edit_posts')) {
            $actions['duplicate'] = sprintf(
                '<a href="%s">%s</a>',
                wp_nonce_url(
                    admin_url('admin.php?action=duplicate_post_as_draft&post=' . $post->ID),
                    basename(__FILE__),
                    'duplicate_nonce'
                ),
                __('Duplicate with tva.sg', 'tva-duplicate-pro')
            );
        }
        return $actions;
    }

    public function duplicatePostAsDraft() {
        // Sicherheitscheck
        if (!isset($_GET['post']) || !isset($_GET['duplicate_nonce'])) {
            wp_die('No post to duplicate has been provided!');
        }

        if (!wp_verify_nonce($_GET['duplicate_nonce'], basename(__FILE__))) {
            wp_die('Security check failed!');
        }

        $post_id = absint($_GET['post']);
        $post = get_post($post_id);

        if (!$post) {
            wp_die('Post creation failed, could not find original post.');
        }

        // Erstelle den duplizierten Post
        $new_post_args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => get_current_user_id(),
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => 'Copy of ' . $post->post_title,
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
        );

        $new_post_id = wp_insert_post($new_post_args);

        // Kopiere Taxonomien
        $taxonomies = get_object_taxonomies($post->post_type);
        foreach ($taxonomies as $taxonomy) {
            $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
        }

        // Kopiere Post Meta
        $post_meta = get_post_meta($post_id);
        foreach ($post_meta as $key => $values) {
            foreach ($values as $value) {
                if (in_array($key, array('_edit_lock', '_edit_last', '_sale_price_dates_from', '_sale_price_dates_to'))) {
                    continue;
                }
                add_post_meta($new_post_id, $key, maybe_unserialize($value));
            }
        }

        // WooCommerce spezifische Behandlung
        if ($post->post_type === 'product') {
            $this->duplicateProductImages($post_id, $new_post_id);
            $this->duplicateProductGallery($post_id, $new_post_id);
            $this->generateUniqueSku($new_post_id);
        }

        wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
        exit;
    }

    private function duplicateProductImages($original_id, $new_id) {
        $thumbnail_id = get_post_thumbnail_id($original_id);
        if ($thumbnail_id) {
            set_post_thumbnail($new_id, $thumbnail_id);
        }
    }

    private function duplicateProductGallery($original_id, $new_id) {
        $gallery = get_post_meta($original_id, '_product_image_gallery', true);
        if ($gallery) {
            update_post_meta($new_id, '_product_image_gallery', $gallery);
        }
    }

    private function generateUniqueSku($post_id) {
        $sku = get_post_meta($post_id, '_sku', true);
        if ($sku) {
            $new_sku = $sku . '-copy-' . time();
            update_post_meta($post_id, '_sku', $new_sku);
        }
    }

    public function pluginRowMeta($links, $file) {
        if (plugin_basename(__FILE__) === $file) {
            $row_meta = array(
                'greeting' => '<span>Have a wonderful day</span>'
            );
            return array_merge($links, $row_meta);
        }
        return $links;
    }

    public function pluginActionLinks($links) {
        $author_link = '<a href="https://www.tva.sg" target="_blank">By tva.sg</a>';
        return array_merge(array($author_link), $links);
    }
}

// Initialisiere das Plugin
tvaDuplicatePro::getInstance();
