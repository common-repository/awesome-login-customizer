<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Plugin Name: Awesome Login Customizer
 * Plugin URI: https://wordpress.org/plugins/awesome-login-customizer/
 * Description: Awesome login customizer is a wordpress plugin that will help you to customize your wordpress default login page.
 * Version: 1.0.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: Md Ripon
 * Author URI: https://github.com/ripon181
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: awesome-login-customizer
 */

function alcwp_add_theme_page() {
    add_menu_page('Login Option for Admin', 'Awesome Login', 'manage_options', 'alcwp-plugin-option', 'alcwp_create_page', 'dashicons-admin-customizer', 103);
}
add_action('admin_menu', 'alcwp_add_theme_page');

function alcwp_add_theme_css() {
    wp_enqueue_style('alcwp-admin-style', plugins_url('css/alcwp-admin-style.css', __FILE__), false, "1.0.0");
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
}
add_action('admin_enqueue_scripts', 'alcwp_add_theme_css');

function alcwp_add_media_uploader_script() {
    wp_enqueue_media();
    wp_enqueue_script('alcwp-media-uploader', plugins_url('js/alcwp-media-uploader.js', __FILE__), array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'alcwp_add_media_uploader_script');

function alcwp_create_page() {
    ?>
    <div class="alcwp_main_area">
        <div class="alcwp_body_area alcwp_common">
            <h3 id="title"><?php print esc_attr('Welcome To Awesome Login Customizer'); ?></h3>
            <form action="options.php" method="post">
                <?php wp_nonce_field('update-options'); ?>

                <div class="form-group">
                    <label for="alcwp-custom-bg-image">Background Image:</label>
                    <input type="text" id="alcwp-custom-bg-image" name="alcwp-custom-bg-image" class="form-control" value="<?php echo esc_attr(get_option('alcwp-custom-bg-image', '')); ?>" />
                    <input type="button" id="alcwp-custom-bg-image-button" class="button form-control" value="Upload Background Image" />
                    <img id="alcwp-custom-bg-image-preview" src="<?php echo esc_attr(get_option('alcwp-custom-bg-image', '')); ?>" style="max-width: 200px; display: <?php echo empty(get_option('alcwp-custom-bg-image', '')) ? 'none' : 'block'; ?>" />
                    <a href="#" class="alcwp-remove-image btn btn-danger" data-input-id="#alcwp-custom-bg-image">Remove</a>
                </div>

                <div class="form-group">
                    <label for="alcwp-logo-image-url">Logo Image:</label>
                    <input type="text" id="alcwp-logo-image-url" class="form-control" name="alcwp-logo-image-url" value="<?php echo esc_attr(get_option('alcwp-logo-image-url', '')); ?>" />
                    <input type="button" id="alcwp-logo-image-url-button" class="button form-control" value="Upload Logo Image" />
                    <img id="alcwp-logo-image-url-preview" src="<?php echo esc_attr(get_option('alcwp-logo-image-url', '')); ?>" style="max-width: 200px; display: <?php echo empty(get_option('alcwp-logo-image-url', '')) ? 'none' : 'block'; ?>" />
                    <a href="#" class="alcwp-remove-image btn btn-danger" data-input-id="#alcwp-logo-image-url">Remove</a>
                </div>

                <div class="form-group">
                    <label for="login-button-gradient">Login Button Gradient:</label>
                    <input type="text" id="login-button-gradient" class="form-control" name="login-button-gradient" value="<?php echo esc_attr(get_option('login-button-gradient', 'linear-gradient(to right, #ff7e5f, #feb47b)')); ?>" />
                </div>

                <input type="hidden" name="action" value="update">
                <input type="hidden" name="page_options" value="alcwp-primary-color, alcwp-logo-image-url, alcwp-custom-bg-image, alcwp-sec-color, alcwp-custom-bg-brightness, login-button-gradient">
                <input type="submit" class="btn btn-info" name="submit" value="<?php esc_html_e('Save Changes', 'alcwp') ?>">
            </form>
        </div>
    </div>
    <?php
}

// Add custom login logo
function alcwp_custom_login_logo() {
    $bg_image = esc_url(get_option('alcwp-custom-bg-image', ''));
    $logo_image = esc_url(get_option('alcwp-logo-image-url', ''));
    $login_button_gradient = esc_attr(get_option('login-button-gradient', 'linear-gradient(to right, #ff7e5f, #feb47b)'));

    ?>
    <style type="text/css">
        body.login {
            background: url('<?php echo esc_url($bg_image); ?>') no-repeat center center;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        #login h1 a {
            background: url('<?php echo esc_url($logo_image); ?>') no-repeat center center;
            background-size: contain;
            height: 100px;
            width: 100%;
            text-indent: -9999px;
            margin-bottom: 10px;
        }

        #loginform .button-primary {
            background: <?php echo esc_attr($login_button_gradient); ?>;
            border: none;
            text-shadow: none;
            box-shadow: none;
        }
    </style>
    <?php
}

add_action('login_head', 'alcwp_custom_login_logo');

function alcwp_login_enqueue_register() {
    wp_enqueue_style('alcwp_login_enqueue', plugins_url('css/alcwp-style.css', __FILE__), false, "1.0.0");
}
add_action('login_enqueue_scripts', 'alcwp_login_enqueue_register');

function alcwp_login_logo_url_change() {
    return home_url();
}
add_filter('login_headerurl', 'alcwp_login_logo_url_change');

register_activation_hook(__FILE__, 'alcwp_plugin_activation');
function alcwp_plugin_activation() {
    add_option('alcwp_plugin_do_activation_redirect', true);
}

add_action('admin_init', 'alcwp_plugin_redirect');
function alcwp_plugin_redirect() {
    if (get_option('alcwp_plugin_do_activation_redirect', false)) {
        delete_option('alcwp_plugin_do_activation_redirect');
        if (!isset($_GET['active-multi'])) {
            wp_safe_redirect(admin_url('plugins.php'));
            exit;
        }
    }
}
?>
