<?php
/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */
 
/**
 * custom option and settings
 */
function wporg_settings_init() {
    // Register a new setting for "ctf7vn" page.
    register_setting( 'ctf7vn', 'ctf7vn_options' );
 
    // Register a new section in the "ctf7vn" page.
    add_settings_section(
        'ctf7vn_section_developers',
        __( 'Settings', 'ctf7vn' ), 'ctf7vn_section_developers_callback',
        'ctf7vn'
    );
 
    // Register a new field in the "ctf7vn_section_developers" section, inside the "ctf7vn" page.
    add_settings_field(
        'ctf7vn_field_pill', // As of WP 4.6 this value is used only internally.
                                // Use $args' label_for to populate the id inside the callback.
            __( 'Pill', 'ctf7vn' ),
        'ctf7vn_field_pill_cb',
        'ctf7vn',
        'ctf7vn_section_developers',
        array(
            'label_for'         => 'ctf7vn_field_pill',
            'class'             => 'wporg_row',
            'ctf7vn_custom_data' => 'custom',
        )
    );
}
 
/**
 * Register our wporg_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'wporg_settings_init' );
 
 
/**
 * Custom option and settings:
 *  - callback functions
 */
 
 
/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function ctf7vn_section_developers_callback( $args ) {
    ?>
    <!-- // Text -->
    <?php
}
 
/**
 * Pill field callbakc function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function ctf7vn_field_pill_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'ctf7vn_options' );
    ?>
    <select
            id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['ctf7vn_custom_data'] ); ?>"
            name="ctf7vn_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
        <option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'red pill', 'wporg' ); ?>
        </option>
        <option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'blue pill', 'wporg' ); ?>
        </option>
    </select>

    <?php
}
 
/**
 * Add the top level menu page.
 */
function ctf7vn_options_page() {
    add_menu_page(
        'Address VN for Contact Form 7',
        'Address VN - CTF7',
        'manage_options',
        'ctf7vn',
        'ctf7vn_options_page_html'
    );
}
 
 
/**
 * Register our ctf7vn_options_page to the admin_menu action hook.
 */
add_action( 'admin_menu', 'ctf7vn_options_page' );
 
 
/**
 * Top level menu callback function
 */
function ctf7vn_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
 
    // add error/update messages
 
    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'ctf7vn_messages', 'wporg_message', __( 'Settings Saved', 'ctf7vn' ), 'updated' );
    }
 
    // show error/update messages
    settings_errors( 'ctf7vn_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

        <div>
            <p>Hướng dẫn sử dụng</p>

        </div>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "ctf7vn"
            settings_fields( 'ctf7vn' );
            // output setting sections and their fields
            // (sections are registered for "ctf7vn", each field is registered to a specific section)
            do_settings_sections( 'ctf7vn' );
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}

//////////////////////////////////
add_action( 'wpcf7_init', 'wpcf7_add_shortcode_text' );

function wpcf7_add_shortcode_text() {
    wpcf7_add_form_tag(
        array( 'text', 'text*', 'email', 'email*', 'url', 'url*', 'tel', 'tel*' ),
        'wpcf7_text_shortcode_handler', true );
}

add_action( 'wpcf7_init', 'custom_add_shortcode_hello' );
function custom_add_shortcode_hello() {
    wpcf7_add_form_tag( 'helloworld', 'custom_hello_shortcode_handler' ); // "helloworld" is the type of the form-tag
}
function custom_hello_shortcode_handler( $tag ) {
    return 'hello world ! ';
}