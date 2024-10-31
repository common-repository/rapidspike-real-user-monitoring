<?php
/*
Plugin Name: RapidSpike Real User Monitoring
Plugin URI: https://www.rapidspike.com/real-user-monitoring/
Description: A plugin to automatically add your RapidSpike Real User Monitoring tracking script to the <head> tag of your WordPress blog. When activated you just need to <a href="plugins.php?page=rapidspike-rum-config">enter your tracking code</a>. If you don't already have an account, get one at <a href="https://www.rapidspike.com/">rapidspike.com</a>.
Author: RapidSpike
Version: 1.0.0
Author URI: https://www.rapidspike.com/
*/

defined( 'ABSPATH' ) or die( 'No.' );

// The <script> code that goes in the <head>
function add_RapidSpikeRUM_header() {
    $code = get_option('rapidspike_rum_code');

    if(!is_admin() && strlen($code) > 0) {
?>

<script>
    var rs_rum_id = "<?php echo $code; ?>";
    (function() {
        var s = document.getElementsByTagName("script")[0], r = document.createElement("script");
        r.async = "async"; r.src = "//cdn-assets.rapidspike.com/static/js/timingcg.min.js";
        s.parentNode.insertBefore(r, s);
    })();
</script>

<?php
    }
}

// Prints the admin menu where it is possible to add the tracking code
function print_RapidSpikeRUM_management() {
    if (isset($_POST['submit'])) {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to manage options for this blog.'));
        }

        $code = trim($_POST['rapidspike_rum_code']);

        if (empty($code)) {
            delete_option('rapidspike_rum_code');
        } else {
            update_option('rapidspike_rum_code', $code);
        }
?>
<div id="message" class="updated fade"><p><strong><?php esc_attr_e('Options saved.'); ?></strong></p></div>
<?php
    }
?>
<div class="wrap">
    <h2><?php esc_attr_e('Real User Monitoring'); ?></h2>
    <p><?php _e('Please enter your RapidSpike Real User Monitoring tracking code. If you do not yet have an account, sign-up at <a href="https://my.rapidspike.com/#/register">rapidspike.com</a>'); ?></p>
    <p><?php _e('For more information about RUM, including how to get your tracking code, please see <a href="https://www.rapidspike.com/blog/real-user-monitoring/">our blog post</a>. Look for this line in the tracking script:'); ?></p>
    <p><code>var rs_rum_id = "3x4mp13-3x4mp13-3x4mp13-3x4mp13";</code></p>
    <p><?php _e('In this example the tracking code is:'); ?></p>
    <p><code>3x4mp13-3x4mp13-3x4mp13-3x4mp13</code></p>

    <form method="post" action="">
        <h3><?php esc_attr_e('Your Real User Monitoring Tracking Code'); ?></h3>
        <input name="rapidspike_rum_code" type="text" id="rapidspike_rum_code" value="<?php echo get_option('rapidspike_rum_code'); ?>" maxlength="40" placeholder="3x4mp13-3x4mp13-3x4mp13-3x4mp13" />
        <input type="submit" name="submit" value="<?php esc_attr_e('Save Changes') ?>" />
        <p><?php esc_attr_e('Leave empty to remove'); ?></span>
    </form>

</div>
<?php
}

function add_RapidSpikeRUM_admin_page()
{
    if ( function_exists('add_submenu_page') ) {
        add_submenu_page('plugins.php', __('RapidSpike Real User Monitoring'), __('RapidSpike Real User Monitoring'), 'manage_options', 'rapidspike-rum-config', 'print_RapidSpikeRUM_management');
    }
}

function add_RapidPikeRUM_action_links( $links )
{
    return array_merge(array('settings' => '<a href="' . get_bloginfo( 'wpurl') . '/wp-admin/plugins.php?page=rapidspike-rum-config">Settings</a>'), $links);
}

add_action('wp_head', 'add_RapidSpikeRUM_header');

if(is_admin()) {
    load_plugin_textdomain('rapidspike-rum', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n');
    add_action('admin_menu', 'add_RapidSpikeRUM_admin_page');
    add_filter('plugin_action_links_' . plugin_basename( __FILE__ ), 'add_RapidPikeRUM_action_links');
}
?>