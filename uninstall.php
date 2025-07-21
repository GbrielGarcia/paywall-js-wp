<?php
// Si no es una desinstalación de WordPress, salir
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}
// Eliminar las opciones del plugin
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
if (is_multisite()) {
    global $wpdb;
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
    foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);
        delete_option('paywall_js_wp_options');
        restore_current_blog();
    }
} else {
    delete_option('paywall_js_wp_options');
} 