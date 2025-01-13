<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

add_action('admin_menu', 'at_func_transferencia_register_admin_page');

function at_func_transferencia_register_admin_page()
{
    add_submenu_page('woocommerce', 'Configuraciones', 'PTB', 'manage_woocommerce', 'at_transferencia_settings', 'at_func_transferencia_submenu_settings_callback');
    add_action('admin_init', 'at_func_transferencia_register_settings');
}

function at_func_transferencia_submenu_settings_callback()
{
    ?>
    <div class="wrap woocommerce" >
        <div style="background-color:#87b43e;">
        </div>
        <h1><?php _e("Bank transfer price", 'at-precio-transferencia') ?></h1>
<hr>
<h2 class="nav-tab-wrapper">
    <a href="?page=at_transferencia_settings" class="nav-tab nav-tab-active"><?php echo _e('Settings', 'at-precio-transferencia') ?></a>
</h2>
<?php at_func_transferencia_submenu_settings(); ?>
</div>
<?php
}

function at_func_transferencia_submenu_settings()
{
    ?>
        <div class="wrap" style="background-color: #fff;">
            <div style="margin-top: 20px; padding: 20px 30px 10px 30px; min-height: 240px;width: 40%; float:left; margin-right: 20px;  border-radius: 10px ; background-position: center; background-repeat: no-repeat;background-size: cover; ">
                <h2><?php _e('Settings', 'at-precio-transferencia') ?></h2>
                <form method="post" action="options.php" >
                    <?php settings_fields('at_transferencia_settings_group'); ?>
                    <?php do_settings_sections('at_transferencia_settings_group'); ?>
                    <p>
                        <b><?php _e('Text of price html', 'at-precio-transferencia') ?> </b> <span style="color: red;">&nbsp;*</span>
                        <input type="text" name="at_text_transferencia_price_html"  placeholder=""
                               value="<?php echo get_option('at_text_transferencia_price_html'); ?>"
                               style="width: 100%; padding: 10px;">
                    </p>
                    <p>
                        <?php submit_button(__('Save Changes', 'at-precio-transferencia')); ?>
                    </p>
                </form>
            </div>
        </div>
    <?php
}

function at_func_transferencia_register_settings()
{
    register_setting('at_transferencia_settings_group', 'at_text_transferencia_price_html');

}

function at_func_create_transferencia_price_field()
{
    $args = array(
        'id' => 'transferencia_price_field_at',
        'label' => __('Price by Bank Transfer', 'at-precio-transferencia') . ' ($)',
        'desc_tip' => true,
        'description' => __('Enter Price', 'at-precio-transferencia')
    );
    woocommerce_wp_text_input($args);
}

add_action('woocommerce_product_options_general_product_data', 'at_func_create_transferencia_price_field');


function at_func_save_transferencia_price_field($post_id)
{
    $product = wc_get_product($post_id);
    $title = isset($_POST['transferencia_price_field_at']) ? $_POST['transferencia_price_field_at'] : '';
    $product->update_meta_data('transferencia_price_field_at', sanitize_text_field($title));
    $product->save();
}

add_action('woocommerce_process_product_meta', 'at_func_save_transferencia_price_field');
