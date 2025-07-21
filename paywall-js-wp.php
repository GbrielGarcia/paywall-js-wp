<?php
/*
Plugin Name: Paywall.js WP
Plugin URI: https://github.com/GbrielGarcia/paywall-js
Description: Integra Paywall.js en tu sitio WordPress para proteger el sitio cuando el cliente no paga, mostrando efectos visuales automáticos.
Version: 1.0.2
Author: Alberto Guaman (@GbrielGarcia) - Tinguar
Author URI: https://tinguar.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: paywall-js-wp
Domain Path: /languages
Requires at least: 5.0
Tested up to: 6.5
Requires PHP: 7.2
*/

if (!defined('ABSPATH')) exit;

if (version_compare(PHP_VERSION, '7.2', '<')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>' . esc_html__('Paywall.js WP requiere PHP 7.2 o superior.', 'paywall-js-wp') . '</p></div>';
    });
    return;
}

if (version_compare(get_bloginfo('version'), '5.0', '<')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>' . esc_html__('Paywall.js WP requiere WordPress 5.0 o superior.', 'paywall-js-wp') . '</p></div>';
    });
    return;
}

class Paywall_JS_WP {
    private $options;

    public function __construct() {
        add_action('admin_menu', array($this, 'paywall_js_wp_add_admin_menu'));
        add_action('admin_init', array($this, 'paywall_js_wp_settings_init'));
        add_action('wp_enqueue_scripts', array($this, 'paywall_js_wp_enqueue_paywall_js'));
        add_action('wp_footer', array($this, 'paywall_js_wp_inject_paywall_config'));
        add_action('plugins_loaded', array($this, 'paywall_js_wp_load_textdomain'));
        add_action('admin_enqueue_scripts', array($this, 'paywall_js_wp_enqueue_admin_assets'));
    }

    public function paywall_js_wp_load_textdomain() {
        load_plugin_textdomain('paywall-js-wp', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    public function paywall_js_wp_add_admin_menu() {
        if (!current_user_can('manage_options')) return;
        add_options_page(
            __('Paywall.js', 'paywall-js-wp'),
            __('Paywall.js', 'paywall-js-wp'),
            'manage_options',
            'paywall-js-wp',
            array($this, 'paywall_js_wp_options_page')
        );
    }

    public function paywall_js_wp_settings_init() {
        register_setting('paywall_js_wp', 'paywall_js_wp_options', array(
            'sanitize_callback' => array($this, 'paywall_js_wp_sanitize_options')
        ));

        add_settings_section(
            'paywall_js_wp_section',
            __('Configuración de Paywall.js', 'paywall-js-wp'),
            null,
            'paywall_js_wp'
        );

        add_settings_field(
            'enabled',
            __('Habilitar Paywall', 'paywall-js-wp'),
            array($this, 'paywall_js_wp_field_enabled'),
            'paywall_js_wp',
            'paywall_js_wp_section'
        );
        add_settings_field(
            'due_date',
            __('Fecha de vencimiento', 'paywall-js-wp'),
            array($this, 'paywall_js_wp_field_due_date'),
            'paywall_js_wp',
            'paywall_js_wp_section'
        );
        add_settings_field(
            'days_deadline',
            __('Días de plazo', 'paywall-js-wp'),
            array($this, 'paywall_js_wp_field_days_deadline'),
            'paywall_js_wp',
            'paywall_js_wp_section'
        );
        add_settings_field(
            'effect',
            __('Efecto visual', 'paywall-js-wp'),
            array($this, 'paywall_js_wp_field_effect'),
            'paywall_js_wp',
            'paywall_js_wp_section'
        );
        add_settings_field(
            'color',
            __('Color sólido', 'paywall-js-wp'),
            array($this, 'paywall_js_wp_field_color'),
            'paywall_js_wp',
            'paywall_js_wp_section'
        );
        add_settings_field(
            'gradient_from',
            __('Gradiente desde', 'paywall-js-wp'),
            array($this, 'paywall_js_wp_field_gradient_from'),
            'paywall_js_wp',
            'paywall_js_wp_section'
        );
        add_settings_field(
            'gradient_to',
            __('Gradiente hasta', 'paywall-js-wp'),
            array($this, 'paywall_js_wp_field_gradient_to'),
            'paywall_js_wp',
            'paywall_js_wp_section'
        );
    }

    public function paywall_js_wp_sanitize_options($options) {
        $sanitized = array();
        $sanitized['enabled'] = isset($options['enabled']) ? 1 : 0;
        $sanitized['due_date'] = isset($options['due_date']) ? sanitize_text_field($options['due_date']) : '';
        $sanitized['days_deadline'] = isset($options['days_deadline']) ? intval($options['days_deadline']) : 60;
        $allowed_effects = array('gradient', 'solid', 'fade');
        $sanitized['effect'] = in_array($options['effect'] ?? '', $allowed_effects) ? $options['effect'] : 'gradient';
        $sanitized['color'] = isset($options['color']) ? sanitize_hex_color($options['color']) : '#ff0000';
        $sanitized['gradient_from'] = isset($options['gradient_from']) ? sanitize_hex_color($options['gradient_from']) : '#ff0000';
        $sanitized['gradient_to'] = isset($options['gradient_to']) ? sanitize_hex_color($options['gradient_to']) : '#000000';
        return $sanitized;
    }

    public function paywall_js_wp_field_enabled() {
        $options = get_option('paywall_js_wp_options');
        $checked = !isset($options['enabled']) || $options['enabled'] ? 'checked' : '';
        echo '<input type="checkbox" name="paywall_js_wp_options[enabled]" value="1" ' . $checked . ' /> ' . __('Activar el paywall en el sitio', 'paywall-js-wp');
    }

    public function paywall_js_wp_field_due_date() {
        $options = get_option('paywall_js_wp_options');
        echo '<input type="date" name="paywall_js_wp_options[due_date]" value="' . esc_attr($options['due_date'] ?? '') . '" id="paywall-js-wp-due-date" />';
    }
    public function paywall_js_wp_field_days_deadline() {
        $options = get_option('paywall_js_wp_options');
        echo '<input type="number" name="paywall_js_wp_options[days_deadline]" value="' . esc_attr($options['days_deadline'] ?? '60') . '" min="1" />';
    }
    public function paywall_js_wp_field_effect() {
        $options = get_option('paywall_js_wp_options');
        $effect = $options['effect'] ?? 'gradient';
        echo '<select name="paywall_js_wp_options[effect]">';
        echo '<option value="gradient"' . selected($effect, 'gradient', false) . '>' . esc_html__('Gradiente', 'paywall-js-wp') . '</option>';
        echo '<option value="solid"' . selected($effect, 'solid', false) . '>' . esc_html__('Sólido', 'paywall-js-wp') . '</option>';
        echo '<option value="fade"' . selected($effect, 'fade', false) . '>' . esc_html__('Fade', 'paywall-js-wp') . '</option>';
        echo '</select>';
    }
    public function paywall_js_wp_field_color() {
        $options = get_option('paywall_js_wp_options');
        $color = $options['color'] ?? '#ff0000';
        echo '<input type="color" id="paywall-js-wp-color" name="paywall_js_wp_options[color]" value="' . esc_attr($color) . '" />';
    }
    public function paywall_js_wp_field_gradient_from() {
        $options = get_option('paywall_js_wp_options');
        $color = $options['gradient_from'] ?? '#ff0000';
        echo '<input type="color" id="paywall-js-wp-gradient-from" name="paywall_js_wp_options[gradient_from]" value="' . esc_attr($color) . '" />';
    }
    public function paywall_js_wp_field_gradient_to() {
        $options = get_option('paywall_js_wp_options');
        $color = $options['gradient_to'] ?? '#000000';
        echo '<input type="color" id="paywall-js-wp-gradient-to" name="paywall_js_wp_options[gradient_to]" value="' . esc_attr($color) . '" />';
    }

    public function paywall_js_wp_enqueue_paywall_js() {
        if (is_admin()) return;
        wp_enqueue_script('paywall-js', 'https://unpkg.com/paywall-js@1.0.0/dist/paywall.min.js', array(), '1.0.0', true);
    }

    public function paywall_js_wp_enqueue_admin_assets($hook) {
        if ($hook !== 'settings_page_paywall-js-wp') return;
        wp_enqueue_style('paywall-js-wp-admin', plugin_dir_url(__FILE__) . 'paywall-js-wp-admin.css', array(), '1.0.1');
        wp_enqueue_script('paywall-js-wp-admin', plugin_dir_url(__FILE__) . 'paywall-js-wp-admin.js', array('jquery'), '1.0.1', true);
    }

    public function paywall_js_wp_inject_paywall_config() {
        if (is_admin()) return;
        $options = get_option('paywall_js_wp_options');
        if (empty($options['due_date']) || empty($options['enabled'])) return;
        $days_deadline = !empty($options['days_deadline']) ? intval($options['days_deadline']) : 60;
        $effect = !empty($options['effect']) ? esc_js($options['effect']) : 'gradient';
        $color = !empty($options['color']) ? esc_js($options['color']) : '#ff0000';
        $gradient_from = !empty($options['gradient_from']) ? esc_js($options['gradient_from']) : '#ff0000';
        $gradient_to = !empty($options['gradient_to']) ? esc_js($options['gradient_to']) : '#000000';
        echo "<script>window.addEventListener('DOMContentLoaded',function(){new Paywall({dueDate:'{$options['due_date']}',daysDeadline:{$days_deadline},effect:'{$effect}',color:'{$color}',gradientFrom:'{$gradient_from}',gradientTo:'{$gradient_to}'})});</script>";
    }

    public function paywall_js_wp_options_page() {
        if (!current_user_can('manage_options')) return;
        ?>
        <div class="wrap paywall-js-wp-admin-wrap">
            <h1><?php _e('Paywall.js - Protección de Sitio por Falta de Pago', 'paywall-js-wp'); ?></h1>
            <form action="options.php" method="post" id="paywall-js-wp-form">
                <?php
                settings_fields('paywall_js_wp');
                do_settings_sections('paywall_js_wp');
                submit_button();
                ?>
            </form>
            <p style="margin-top:2em;">
                <a href="https://github.com/GbrielGarcia" target="_blank">Alberto Guaman (@GbrielGarcia)</a> - <a href="https://tinguar.com" target="_blank">Tinguar</a>.
            </p>
        </div>
        <?php
    }
}

new Paywall_JS_WP(); 

/*
 * Ejemplo básico de test automatizado para PHPUnit
 * Guarda este archivo como tests/test-paywall-js-wp.php y ejecuta con PHPUnit
 *
 * <?php
 * class Paywall_JS_WP_Test extends WP_UnitTestCase {
 *     public function test_plugin_options_exist() {
 *         $this->assertNotNull(get_option('paywall_js_wp_options'));
 *     }
 * }
 */ 