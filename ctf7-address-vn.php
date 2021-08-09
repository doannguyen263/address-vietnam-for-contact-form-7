<?php
/**
 * Plugin Name:       Address VN for Contact Form 7
 * Plugin URI:        https://doanplus.com/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            DoanNguyen
 * Author URI:        https://doanplus.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
 */

define( 'CTF7_ADDRESS_VN_VERSION', '1.0.0' );
define( 'CTF7_ADDRESS_VN_FILE', __FILE__ );
define( 'CTF7_ADDRESS_VN_ROOT', dirname( __FILE__ ) );
define( 'CTF7_ADDRESS_VN_ROOT_URI', plugins_url( '', __FILE__ ) );

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


/**
 * Main bootstrap class for DN User Admin
 *
 * @package DN User Admin
 */
final class CTF7_Address_VN
{
    /**
     * Holds various class instances
     *
     * @since 2.5.7
     *
     * @var array
     */
    private $container = array();
    /**
     * The singleton instance
     *
     * @var WP_User_Frontend
     */
    private static $_instance;

    /**
     * Minimum PHP version required
     *
     * @var string
     */
    private $min_php = '7.2.0';

    function __construct()
    {
        if ( ! $this->is_supported_php() ) {
            add_action( 'admin_notices', array( $this, 'php_version_notice' ) );
            return;
        }

        $this->includes();
        $this->init_hooks();

        do_action( 'dnuser_loaded' );
    }
    /**
     * Check if the PHP version is supported
     *
     * @return bool
     */
    public function is_supported_php( $min_php = null ) {

        $min_php = $min_php ? $min_php : $this->min_php;

        if ( version_compare( PHP_VERSION, $min_php , '<=' ) ) {
            return false;
        }

        return true;
    }
    /**
     * Show notice about PHP version
     *
     * @return void
     */
    function php_version_notice() {

        if ( $this->is_supported_php() || ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $error = __( 'Your installed PHP Version is: ', 'dnuser-admin' ) . PHP_VERSION . '. ';
        $error .= __( 'The <strong>WP User Frontend</strong> plugin requires PHP version <strong>', 'dnuser-admin' ) . $this->min_php . __( '</strong> or greater.', 'dnuser-admin' );
        ?>
        <div class="error">
            <p><?php printf( $error ); ?></p>
        </div>
        <?php
    }

    /**
     * Initialize the hooks
     *
     * @since 2.5.4
     *
     * @return void
     */
    public function init_hooks() {


        // add_action( 'init', array( $this, 'load_textdomain' ) );

        // enqueue plugin scripts, don't remove priority.
        // If remove or set priority under 1000 then registered styles will not load on WC Marketplace vendor dashboard.
        // we have integration with WC Marketplace plugin since version 3.0 where WC Marketplae vendors' can submit post
        add_action( 'wp_enqueue_scripts', array( $this, 'plugin_scripts' ), 9999 );

    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @since 2.5.7
     *
     * @param string $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    /**
     * Singleton Instance
     *
     * @return \self
     */
    public static function init() {

        if ( ! self::$_instance ) {
            self::$_instance = new CTF7_Address_VN();
        }

        return self::$_instance;
    }

    /**
     * Include the required files
     *
     * @return void
     */
    function includes()
    {
        // require_once dirname( __FILE__ ) . '/admin/admin.php';
        require_once dirname( __FILE__ ) . '/functions.php';
    }

    function plugin_scripts() {


        wp_enqueue_style('select2', CTF7_ADDRESS_VN_ROOT_URI . '/public/libs/select2/select2.min.css', false, '0.2' );
        wp_enqueue_script('select2', CTF7_ADDRESS_VN_ROOT_URI . '/public/libs/select2/select2.min.js', false, '0.2' );


        wp_enqueue_style('ctf7vn', CTF7_ADDRESS_VN_ROOT_URI . '/public/css/style.css', false, '0.2' );
        wp_enqueue_script('ctf7vn', CTF7_ADDRESS_VN_ROOT_URI . '/public/js/main.js', false, '0.2' );


        wp_localize_script( 'ctf7vn', 'ctf7vn_params', array(
         'nonce' => wp_create_nonce( 'ctf7vn_nonce' ), // Create nonce which we later will use to verify AJAX request
         'ajax_url' => admin_url( 'admin-ajax.php' ),
         'ctf7vn_cities' => CTF7_ADDRESS_VN_ROOT_URI.'/database/vietnam/tinh_tp.json',
         )
        );
    }
    /**
     * Load the translation file for current language.
     *
     * @since version 0.7
     * @author Tareq Hasan
     */
    function load_textdomain() {
        // load_plugin_textdomain( 'dnuser-admin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

}

/**
 * Returns the singleton instance
 *
 * @return \CTF7_Address_VN
 */
function ctf7vn() {
    return CTF7_Address_VN::init();
}

// kickoff
ctf7vn();
