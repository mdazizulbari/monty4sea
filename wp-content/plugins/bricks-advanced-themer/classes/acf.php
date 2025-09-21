<?php
namespace Advanced_Themer_Bricks;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__ACF{

    public static function acf_get_role(){

        global $brxc_acf_fields;

        return $brxc_acf_fields['user_role_permissions'];
    }

    public static function acf_settings_path( $path ) {

        $acf_path = \BRICKS_ADVANCED_THEMER_PATH . 'plugins/acf-pro/';

        return $acf_path;

    }

    public static function acf_settings_dir( $path ) {

        $acf_path = \BRICKS_ADVANCED_THEMER_URL . '/plugins/acf-pro/';

        return $acf_path;

    }

    public static function stop_acf_update_notifications( $value ) {
        // Do not modify ACF update notifications during uninstallation.
        if (defined('WP_UNINSTALL_PLUGIN') && WP_UNINSTALL_PLUGIN) {
            return $value;
        }

        unset( $value->response[ \BRICKS_ADVANCED_THEMER_URL . '/plugins/acf-pro/acf.php' ] );

        return $value;

    }

    private static function check_nested_acf_fields( $post_data, &$errors, $parent_labels = array() ) {
        foreach ( $post_data as $field_key => $value ) {
            $field = get_field_object( $field_key );
            if ( $field ) {

                if ( is_array( $value ) && ( $field['type'] == 'group' || $field['type'] == 'repeater' ) ) {
                    
                    $new_parent_labels = array_merge( $parent_labels, array( $field['label'] ) );
                    
                    self::check_nested_acf_fields( $value, $errors, $new_parent_labels );
                } else {
                    
                    if ( empty( $value )  ) {

                        $full_label = implode( ' -> ', array_merge( $parent_labels, array( $field['label'] ) ) );

                        $errors[] = $full_label;
                    }
                }
            }
        }
    }

    public static function validate_save_post() {
        $errors = array();
    
        // Check if $_POST contains ACF data
        if ( isset( $_POST['acf'] ) && is_array( $_POST['acf'] ) ) {
            self::check_nested_acf_fields( $_POST['acf'], $errors );
        }
        
        // Check if there are any errors
        if ( !empty( $errors ) ) {
            // Create an HTML list with errors
            $error_list = '<ul><li>' . implode( '</li><li>', $errors ) . '</li></ul>';
            
            // Combine the error message and the list
            $error_message = 'Oops! You forgot to enter a value for the following required fields:' . $error_list;
            
            // Display the error message using acf_add_validation_error()
            acf_add_validation_error( '', $error_message );
        }
    }

    public static function create_advanced_themer_option_page() {

        // Check function exists.
        if( function_exists( 'acf_add_options_sub_page' )) {

            // Register options page.
            $option_page = acf_add_options_sub_page(
                array(
                'page_title'    => __( 'Theme Settings' ),
                'menu_title'    => __( 'AT - Theme Settings' ),
                'menu_slug'     => 'bricks-advanced-themer',
                'parent'        => 'bricks',
                'capability'    => 'edit_posts',
                'redirect'      => false,
                'position'      => '98',
                'update_button' => __('Save Settings', 'acf'),
                'post_id' => 'bricks-advanced-themer',
                )

            );

        }

    }

    // Get a list of editable user roles
    private static function get_editable_roles() {

        $all_roles = wp_roles()->roles;


	    $editable_roles = apply_filters( 'editable_roles', $all_roles );
    
        return $editable_roles;

    }

    // Return a list of all the public post types on the site
    private static function return_array_all_post_types() {

        $args = array(
            'public'   => true,
        );
        
        $output = 'names';

        $operator = 'and';

        $post_types = get_post_types( $args, $output, $operator );

        return $post_types;

    }

    public static function load_user_roles_inside_select_field( $field ){

        $roles = self::get_editable_roles();

        if ( !$roles || !is_array( $roles ) ){

            return;

        }

        $field['choices'] = [];

        $default = [];
      
        foreach ( $roles as $role ) {

            $field['choices'][strtolower( $role['name'] )] = $role['name'];

        }

        return $field;

    }

    public static function load_post_types_inside_select_field( $field ){

        $post_types_arr = self::return_array_all_post_types();

        if ( !$post_types_arr || !is_array( $post_types_arr ) ) {

            return;

        }

        $field['choices'] = [];

        $default = [];
      
        foreach ( $post_types_arr as $post_type ){

            $field['choices'][strtolower( $post_type )] = $post_type;

            $default[] = strtolower( $post_type );

        }
        
        $field['default_value'] = $default;

        return $field;

    }

    public static function load_grid_default_repeater_values($value, $post_id, $field) {


        if ($value === false) {

            $value = array();
            
            $value[] = array(
                'field_63b48c6f1b20b' => 'grid-3',
                'field_63b48c6f1b20c' => '3',
                'field_63b48c6f1b20d' => '280',
                'field_63b48d7e1b20e' => '2rem',
            );

          }
        
          return $value;

    }
    public static function load_human_readable_text_value($value, $post_id, $field) {


        if ($value === false || $value === '') {

            $value = 'This is just placeholder text. We will change this out later. It’s just meant to fill space until your content is ready.
Don’t be alarmed, this is just here to fill up space since your finalized copy isn’t ready yet.
Once we have your content finalized, we’ll replace this placeholder text with your real content.
Sometimes it’s nice to put in text just to get an idea of how text will fill in a space on your website.
Traditionally our industry has used Lorem Ipsum, which is placeholder text written in Latin.
 Unfortunately, not everyone is familiar with Lorem Ipsum and that can lead to confusion.
I can’t tell you how many times clients have asked me why their website is in another language.
There are other placeholder text alternatives like Hipster Ipsum, Zombie Ipsum, Bacon Ipsum, and many more.
While often hilarious, these placeholder passages can also lead to much of the same confusion.
If you’re curious, this is Website Ipsum. It was specifically developed for the use on development websites.
Other than being less confusing than other Ipsum’s, Website Ipsum is also formatted in patterns more similar to how real copy is formatted on the web today.';

        }
    
        return $value;

    }

    public static function change_flexible_layout_no_value_msg( $no_value_message, $field) {
        if($field['key'] !== 'field_63dd12891d1d9') return $no_value_message = __('Click the "%s" button below to start creating your layout','acf');;

        $no_value_message = __('Click the "%s" button below to start creating your own CSS variables','acf');

        return $no_value_message;
    }
    
    //openaAI Password
    public static function load_openai_password($value, $post_id, $field) {


        if (isset($value) && !empty($value) && $value) {
            $ciphering = "AES-128-CTR";
            $options = 0;
            $decryption_iv = 'UrsV9aENFT*IRfhr';
            $decryption_key = "#34x*R8zmVK^IFG4#a4B3BVYIb";
            $value = openssl_decrypt ($value, $ciphering, $decryption_key, $options, $decryption_iv);

        }
        
        return $value;

    }

    public static function save_openai_password(){

        if(!function_exists('get_current_screen') ) return;

        $screen = get_current_screen();

        if (!$screen || (strpos($screen->id, "bricks-advanced-themer") == false) )  return;

        // Check if a specific value was updated.
        if( isset($_POST['acf']['field_63dd51rkj633r']['field_64018efb660fb']) && !empty($_POST['acf']['field_63dd51rkj633r']['field_64018efb660fb'])) {

            $ciphering = "AES-128-CTR";
            $iv_length = openssl_cipher_iv_length($ciphering);
            $options = 0;
            $encryption_iv = 'UrsV9aENFT*IRfhr';
            $encryption_key = "#34x*R8zmVK^IFG4#a4B3BVYIb";
            $_POST['acf']['field_63dd51rkj633r']['field_64018efb660fb'] = openssl_encrypt($_POST['acf']['field_63dd51rkj633r']['field_64018efb660fb'], $ciphering, $encryption_key, $options, $encryption_iv);

        }
    
    }

    public static function save_inline_css_in_db() {
        // Check if this is a save_post action or on "bricks-advanced-themer" screen
        $should_save = false;
        $post_id = 0;
        
        if (function_exists('get_current_screen')) {
            $screen = get_current_screen();
            if ($screen && strpos($screen->id, "bricks-advanced-themer") !== false) {
                $should_save = true;
            }
        }

        if (isset($_POST['action']) && ($_POST['action'] == 'editpost' || $_POST['action'] == 'inline-save')) {
            $post_id = isset($_POST['post_ID']) ? intval($_POST['post_ID']) : 0;
            if ($post_id && (get_post_type($post_id) === 'brxc_color_palette')) {
                $should_save = true;
            }
        }

        if ($should_save) {
            self::update_inline_css_in_db($post_id);
        }
    }

    private static function update_inline_css_in_db($post_id) {
        $custom_css = AT__Frontend::generate_css_for_frontend();

        if (get_option('bricks-advanced-themer_frontend_styles')) {
            update_option('bricks-advanced-themer_frontend_styles', $custom_css);
        } else {
            add_option('bricks-advanced-themer_frontend_styles', $custom_css);
        }
    }


    // ACF fields from Option Page
    public static function load_global_acf_variable() {

        global $brxc_acf_fields;
        global $brxc_custom_inline_styles;
        
        $brxc_acf_fields = [];
        $brxc_custom_inline_styles = [];

        /** Deprecated **/
        $brxc_acf_fields['color_cpt_deprecated'] = get_option( 'advanced_themer_color_palette_converted');
        //$brxc_acf_fields['color_cpt_deprecated'] = false;

        /** Setting Group **/
        if( have_rows('field_63daa58ccc209', 'bricks-advanced-themer' ) ):
            while( have_rows('field_63daa58ccc209', 'bricks-advanced-themer' ) ) : the_row(); 

                // Advanced Themer Tab
                $brxc_acf_fields['theme_settings_tabs'] = get_sub_field('field_645s9g7tddfj2', 'bricks-advanced-themer' );

                // Builder Elements
                $brxc_acf_fields['enable_elements'] = get_sub_field('field_63aabb0ccebeb', 'bricks-advanced-themer' );
                $brxc_acf_fields['disable_bricks_elements'] = get_sub_field('field_63aabb0hgh4xs', 'bricks-advanced-themer' );
                $brxc_acf_fields['disable_bricks_elements_on_server'] = get_sub_field('field_63a8765frfdx5', 'bricks-advanced-themer' );

                // Permissions
                $brxc_acf_fields['user_role_permissions'] = get_sub_field('field_6388e73289b6a','bricks-advanced-themer' );
                $brxc_acf_fields['file_upload_format_permissions'] = get_sub_field('field_638tt5f119b6a','bricks-advanced-themer' );

                // Misc
                $brxc_acf_fields['remove_acf_menu'] = get_sub_field('field_63a8765e6ceed', 'bricks-advanced-themer' );
                $brxc_acf_fields['remove_data'] = get_sub_field('field_63ab55f50e545', 'bricks-advanced-themer' );

            endwhile;
        endif;

        /** Global Colors Group **/
        if( have_rows('field_63dd51rtyue5e', 'bricks-advanced-themer' ) ):
            while( have_rows('field_63dd51rtyue5e', 'bricks-advanced-themer' ) ) : the_row();

                // General
                $brxc_acf_fields['color_prefix'] = get_sub_field('field_639570dfuf009', 'bricks-advanced-themer' );
                $brxc_acf_fields['enable_dark_mode_on_frontend'] = get_sub_field('field_63a8765565dhd', 'bricks-advanced-themer' );
                $brxc_acf_fields['force_default_color_scheme'] = get_sub_field('field_63d651dfufnaq', 'bricks-advanced-themer' );

                // Gutenberg
                $brxc_acf_fields['replace_gutenberg_palettes'] = get_sub_field('field_63b3dc8b9484d', 'bricks-advanced-themer' );
                $brxc_acf_fields['remove_default_gutenberg_presets'] = get_sub_field('field_63b3ddc49484f', 'bricks-advanced-themer' );
                $brxc_acf_fields['global_meta_theme_color'] = get_sub_field('field_640660a191382', 'bricks-advanced-themer' );
                

            endwhile;
        endif;

        /** CSS Variables Group **/
        if( have_rows('field_6445ab9f3d498', 'bricks-advanced-themer' ) ):
            while( have_rows('field_6445ab9f3d498', 'bricks-advanced-themer' ) ) : the_row();

                // General
                $brxc_acf_fields['css_variables_general'] = get_sub_field('field_641aferwtt57v', 'bricks-advanced-themer' );
                $brxc_acf_fields['global_prefix'] = get_sub_field('field_63ab121136eb9', 'bricks-advanced-themer' );
                $brxc_acf_fields['base_font'] = get_sub_field('field_63a843db56979', 'bricks-advanced-themer' );
                $brxc_acf_fields['min_vw'] = get_sub_field('field_63a843f85697a', 'bricks-advanced-themer' );
                $brxc_acf_fields['max_vw'] = get_sub_field('field_63a8440d5697b', 'bricks-advanced-themer' );
                $brxc_acf_fields['clamp_unit'] = get_sub_field('field_6399a28ddt82e', 'bricks-advanced-themer' );

                // Theme Variables
                $brxc_acf_fields['theme_var_position'] = get_sub_field('field_6f5o9q1dr5gcv', 'bricks-advanced-themer' );
                $brxc_acf_fields['theme_var_priority'] = get_sub_field('field_63b49e5fefk54', 'bricks-advanced-themer' );

            endwhile;
        endif;

        /** Classes & Styles Group **/
        if( have_rows('field_63b59j871b209', 'bricks-advanced-themer' ) ):
            while( have_rows('field_63b59j871b209', 'bricks-advanced-themer' ) ) : the_row();

                // General
                $brxc_acf_fields['classes_and_styles_general'] = get_sub_field('field_641aferxdk11m', 'bricks-advanced-themer' );

            endwhile;
        endif;

        /** Builder Tweaks Group **/
        if( have_rows('field_63daa58w1b209', 'bricks-advanced-themer' ) ):
            while( have_rows('field_63daa58w1b209', 'bricks-advanced-themer' ) ) : the_row(); 

                // General
                //$brxc_acf_fields['builder_tweaks_general'] = get_sub_field('field_641aferddwwp5', 'bricks-advanced-themer' );

                // Topbar
                $brxc_acf_fields['topbar_shortcuts'] = get_sub_field('field_641af47gigl16', 'bricks-advanced-themer' );
                $brxc_acf_fields['enable_global_features'] = get_sub_field('field_641af47fbf980', 'bricks-advanced-themer' );

                // Structure Panel
                $brxc_acf_fields['structure_panel_icons'] = get_sub_field('field_641af47dd8vp', 'bricks-advanced-themer' );
                $brxc_acf_fields['structure_panel_default_tag_view'] = get_sub_field('field_63d651dddr715', 'bricks-advanced-themer' );
                $brxc_acf_fields['structure_panel_contextual_menu'] = get_sub_field('field_641af4eeoch5', 'bricks-advanced-themer' );
                $brxc_acf_fields['structure_panel_general_tweaks'] = get_sub_field('field_641af4wox5p1', 'bricks-advanced-themer' );
                $brxc_acf_fields['create_elements_shortcuts'] = get_sub_field('field_6426ffg54d84a', 'bricks-advanced-themer' );
                $brxc_acf_fields['create_elements_shortcuts_keyboard_default'] = get_sub_field('field_63a8765fhfcos', 'bricks-advanced-themer' );
                $brxc_acf_fields['structure_panel_styles_and_classes_indicator_colors'] = get_sub_field('field_63d651ddrf5c2', 'bricks-advanced-themer' ); 
                //$brxc_acf_fields['structure_panel_width'] = get_sub_field('field_63a843d5jff9x', 'bricks-advanced-themer' ); 

                // Classes & Styles
                $brxc_acf_fields['class_features'] = get_sub_field('field_64074j8de4756', 'bricks-advanced-themer' );
                $brxc_acf_fields['lock_id_styles_with_classes'] = get_sub_field('field_63a843ddsxzp5', 'bricks-advanced-themer' );
                $brxc_acf_fields['autoformat_control_values'] = get_sub_field('field_6426ffgdf59xp', 'bricks-advanced-themer' );
                $brxc_acf_fields['advanced_css_panels'] = get_sub_field('field_64d8r1j6bf980', 'bricks-advanced-themer' );
                $brxc_acf_fields['advanced_css_enable_sass'] = get_sub_field('field_63a843dddwxp5', 'bricks-advanced-themer' );

                // Elements
                $brxc_acf_fields['element_features'] = get_sub_field('field_64074ge58dfj2', 'bricks-advanced-themer' );
                $brxc_acf_fields['tab_icons_offset'] = get_sub_field('field_63a843d56ff8x', 'bricks-advanced-themer' );
                $brxc_acf_fields['enable_tabs_icons'] = get_sub_field('field_6426786feb84a', 'bricks-advanced-themer' );
                $brxc_acf_fields['lorem_type'] = get_sub_field('field_63d651ddc5a6f', 'bricks-advanced-themer' );
                $brxc_acf_fields['custom_dummy_content'] = get_sub_field('field_63882c3ffbgc1', 'bricks-advanced-themer' );
                $brxc_acf_fields['default_elements_list_cols'] = get_sub_field('field_63dd6b8k2la6f', 'bricks-advanced-themer' );
                $brxc_acf_fields['enable_shortcuts_icons'] = get_sub_field('field_6420a42b78413', 'bricks-advanced-themer' );
                $brxc_acf_fields['superpowercss-enable-sass'] = get_sub_field('field_63a843dhfhx13', 'bricks-advanced-themer' );
                $brxc_acf_fields['default_spacing_controls'] = get_sub_field('field_63a843dssxtd5', 'bricks-advanced-themer' );
                $brxc_acf_fields['elements_shortcut_icons'] = get_sub_field('field_64074geddhxir', 'bricks-advanced-themer' );

                // Templates
                $brxc_acf_fields['templates_features'] = get_sub_field('field_64074gegtr51c', 'bricks-advanced-themer' );
                // SASS INTEGRATION
                //$brxc_acf_fields['enable_sass_superpower_css'] = get_sub_field('field_63b3dc8ddja92', 'bricks-advanced-themer' );
                $brxc_acf_fields['custom_default_settings'] = get_sub_field('field_64074gedhc99o', 'bricks-advanced-themer' );

                // Keyboard Shortcuts
                $brxc_acf_fields['keyboard_sc_options'] = get_sub_field('field_641af4few523', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_open_css_variable_modal'] = get_sub_field('field_63db8199d73d4', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_enable_grid_guides'] = get_sub_field('field_63dba4f8f5056', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_enable_xmode'] = get_sub_field('field_63dba4b8f5055', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_enable_constrast_checker'] = get_sub_field('field_63dba510f5057', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_enable_darkmode'] = get_sub_field('field_63dba543f5058', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_enable_css_stylesheets'] = get_sub_field('field_63dba55ff5059', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_enable_resources'] = get_sub_field('field_63dba59df505a', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_enable_openai'] = get_sub_field('field_6418f83d91c38', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_enable_brickslabs'] = get_sub_field('field_641tt54fe1c38', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_enable_color_manager'] = get_sub_field('field_641tt54ttrkc0', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_enable_class_manager'] = get_sub_field('field_641tt54ddxbo8', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_enable_variable_manager'] = get_sub_field('field_641tt54drd5pl', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_enable_query_loop_manager'] = get_sub_field('field_641tt54ffrdl1', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_enable_structure_helper'] = get_sub_field('field_641tt54ggbg85', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_enable_find_and_replace'] = get_sub_field('field_641tt54drwc51', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_enable_plain_classes'] = get_sub_field('field_641tt54ppin6d', 'bricks-advanced-themer' );
                $brxc_acf_fields['keyboard_sc_nested_elements'] = get_sub_field('field_641tt54ffuc5x', 'bricks-advanced-themer' );


            endwhile;
        endif;

        /** Strict Editor View Group **/
        if( have_rows('field_63dd51rddtr57', 'bricks-advanced-themer' ) ):
            while( have_rows('field_63dd51rddtr57', 'bricks-advanced-themer' ) ) : the_row();

                // General
                $brxc_acf_fields['strict_editor_view_general'] = get_sub_field('field_641afertt51dg', 'bricks-advanced-themer' );

                // White Label
                $brxc_acf_fields['change_logo_img'] = get_sub_field('field_64066003f4140', 'bricks-advanced-themer' );
                $brxc_acf_fields['change_accent_color'] = get_sub_field('field_640660aee91e4', 'bricks-advanced-themer' );

                // Toolbar
                $brxc_acf_fields['disable_toolbar_icons'] = get_sub_field('field_64065d4de47ca', 'bricks-advanced-themer' );

                // Elements
                $brxc_acf_fields['strict_editor_view_elements'] = get_sub_field('field_63e0ccbf3f5d0', 'bricks-advanced-themer' );
                $brxc_acf_fields['enable_left_visibility_elements'] = get_sub_field('field_63aabb0rgrci4', 'bricks-advanced-themer' );
                $brxc_acf_fields['strict_editor_view_tweaks'] = get_sub_field('field_64065d4ttv4z2', 'bricks-advanced-themer' );
                $brxc_acf_fields['strict_editor_view_custom_css'] = get_sub_field('field_64065d455dvt2', 'bricks-advanced-themer' );
            endwhile;
        endif;

        /** AI Group **/
        if( have_rows('field_63dd51rkj633r', 'bricks-advanced-themer' ) ):
            while( have_rows('field_63dd51rkj633r', 'bricks-advanced-themer' ) ) : the_row();

                // General
                $brxc_acf_fields['openai_api_key'] = empty(get_sub_field('field_64018efb660fb', 'bricks-advanced-themer' )) ? '1' : '0';
                $brxc_acf_fields['default_api_model'] = get_sub_field('field_6399a28frf471', 'bricks-advanced-themer' );
                $brxc_acf_fields['ai_tone_of_voice'] = get_sub_field('field_64e487ajsie19', 'bricks-advanced-themer' );

            endwhile;
        endif;

        /** No ACF values **/

        $brxc_acf_fields['tone_of_voice'] = preg_split("/\r\n|\n|\r/", $brxc_acf_fields['ai_tone_of_voice']);
        $ai_models = ['gpt-4o','gpt-4o-mini','gpt-4-turbo', 'gpt-4', 'gpt-4-32k', 'gpt-3.5-turbo', 'gpt-3.5-turbo-16k'];
        $valueToRemove = $brxc_acf_fields['default_api_model'];

        $indexToRemove = array_search($valueToRemove, $ai_models);
        if ($indexToRemove !== false) {
            unset($ai_models[$indexToRemove]);
            array_unshift($ai_models, $valueToRemove);
        }
        $brxc_acf_fields['ai_models']['completion'] = $ai_models;
        $brxc_acf_fields['ai_models']['edit'] = $ai_models;
        $brxc_acf_fields['ai_models']['code'] = $ai_models;
    }

    public static function remove_acf_menu() {

        global $brxc_acf_fields;

        if (  $brxc_acf_fields['remove_acf_menu']) {

            add_filter('acf/settings/show_admin', '__return_false');

        }
    }

    //Enqueue admin ACF Scripts
    public static function acf_admin_enqueue_scripts() {

        if( !is_user_logged_in() ) {

            return;

        }

        wp_enqueue_style( 'brxc_acf_admin', \BRICKS_ADVANCED_THEMER_URL . '/assets/css/acf-admin.css', false, filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/css/acf-admin.css') );
        wp_enqueue_script( 'brxc_acf_admin', \BRICKS_ADVANCED_THEMER_URL . '/assets/js/acf-admin.js', false, filemtime( \BRICKS_ADVANCED_THEMER_PATH . '/assets/js/acf-admin.js') );
        $nonce = wp_create_nonce('export_advanced_options_nonce');
        wp_localize_script('brxc_acf_admin', 'exportOptions', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $nonce,
        ));

    }

    public static function acf_color_palettes_fields(){

        if( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_6389e81fa2085',
                'title' => 'Color Palette Post Type',
                'fields' => array(
                    array(
                        'key' => 'field_63956fca26ebb',
                        'label' => 'Colors',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_6383d6f67641b',
                        'label' => 'Colors',
                        'name' => 'brxc_colors_repeater',
                        'aria-label' => '',
                        'type' => 'repeater',
                        'instructions' => 'Add the colors to your palette here. Choose a unique name for each label in order to avoid CSS conflicts, or make sure to set a prefix value in the settings tab.',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => 'color-repeater',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'pagination' => 0,
                        'min' => 1,
                        'max' => 0,
                        'collapsed' => '',
                        'button_label' => 'Add a New Color',
                        'rows_per_page' => 20,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_638728339e15f',
                                'label' => 'Label',
                                'name' => 'brxc_color_label',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '40',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'parent_repeater' => 'field_6383d6f67641b',
                            ),
                            array(
                                'key' => 'field_638344c95efcf',
                                'label' => 'Color',
                                'name' => 'brxc_color_hex',
                                'aria-label' => '',
                                'type' => 'color_picker',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '60',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'enable_opacity' => 0,
                                'return_format' => 'string',
                                'parent_repeater' => 'field_6383d6f67641b',
                            ),
                            array(
                                'key' => 'field_63958c871e42e',
                                'label' => 'ID',
                                'name' => 'brxc_color_id',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hidden',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'parent_repeater' => 'field_6383d6f67641b',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_63956fe226ebc',
                        'label' => 'Settings',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_639570d626ec1',
                        'label' => 'Add a prefix to your CSS variables',
                        'name' => 'brxc_variable_prefix',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => 'The prefix will be automatically added to all your colors (including shades). Example of variable generated with "p1" as prefix: --brxc-p1-primary-color.<br><strong>Note that if you already added a global prefix inside the theme settings, it will apply on the color variable. So if you add it here as well, you\'ll create duplicated prefixes.</strong>',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => 'prefix-css',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                    ),
                    array(
                        'key' => 'field_6395700626ebd',
                        'label' => 'Enable Shades',
                        'name' => 'brxc_enable_shapes',
                        'aria-label' => '',
                        'type' => 'true_false',
                        'instructions' => 'If this field is checked, the plugin will automatically generate 12 different shades for each color: 6 light and 6 dark variations. They will appear inside the Bricks builder.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                        'ui' => 1,
                    ),
                    array(
                        'key' => 'field_6395707d26ec0',
                        'label' => 'Enable Dark Mode',
                        'name' => 'brxc_enable_dark_mode',
                        'aria-label' => '',
                        'type' => 'true_false',
                        'instructions' => 'Check this field if you plan to implement a dark mode on your website.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                        'ui' => 1,
                    ),
                    array(
                        'key' => 'field_63882c3f1215b',
                        'label' => 'Import custom shapes/colors (JSON)',
                        'name' => 'brxc_import_from_json',
                        'aria-label' => '',
                        'type' => 'textarea',
                        'instructions' => 'Paste here the JSON object generated by the export function of the playground GUI.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'maxlength' => '',
                        'rows' => '',
                        'placeholder' => '',
                        'new_lines' => '',
                    ),
                    array(
                        'key' => 'field_6395702f26ebe',
                        'label' => 'Color Palette Key',
                        'name' => 'brxc_color_palette_key',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => 'hidden',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'brxc_color_palette',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
                'show_in_rest' => 0,
            ));
            
            endif;				

    }

    private static function is_global_css_vars_deprecated($call_back) {
        $condition = BRICKS_ADVANCED_THEMER_CSS_VARIABLES_CONVERTED;
        return !$condition && $call_back !== false ? $call_back : [];
    }

    public static function acf_settings_fields() {
    

        if( function_exists('acf_add_local_field_group') ):

            global $brxc_acf_fields;

            $brxc_acf_fields['builder_elements'] = [
                "container" => "container",
                "section" => "section",
                "block" => "block",
                "div" => "div",
                "heading" => "heading",
                "text-basic" => "text-basic",
                "text" => "text",
                "text-link" => "text-link",
                "button" => "button",
                "icon" => "icon",
                "image" => "image",
                "video" => "video",
                "nav-nested" => "nav-nested",
                "dropdown" => "dropdown",
                "offcanvas" => "offcanvas",
                "toggle" => "toggle",
                "divider" => "divider",
                "icon-box" => "icon-box",
                "social-icons" => "social-icons",
                "list" => "list",
                "accordion" => "accordion",
                "accordion-nested" => "accordion-nested",
                "tabs" => "tabs",
                "tabs-nested" => "tabs-nested",
                "form" => "form",
                "map" => "map",
                "alert" => "alert",
                "animated-typing" => "animated-typing",
                "countdown" => "countdown",
                "counter" => "counter",
                "pricing-tables" => "pricing-tables",
                "progress-bar" => "progress-bar",
                "pie-chart" => "pie-chart",
                "team-members" => "team-members",
                "testimonials" => "testimonials",
                "html" => "html",
                "code" => "code",
                "template" => "template",
                "logo" => "logo",
                "facebook-page" => "facebook-page",
                "breadcrumbs" => "breadcrumbs",
                "image-gallery" => "image-gallery",
                "audio" => "audio",
                "carousel" => "carousel",
                "slider" => "slider",
                "slider-nested" => "slider-nested",
                "svg" => "svg",
                "instagram-feed" => "instagram-feed",
                "wordpress" => "wordpress",
                "posts" => "posts",
                "pagination" => "pagination",
                "nav-menu" => "nav-menu",
                "sidebar" => "sidebar",
                "search" => "search",
                "shortcode" => "shortcode",
                "post-title" => "post-title",
                "post-excerpt" => "post-excerpt",
                "post-meta" => "post-meta",
                "post-content" => "post-content",
                "post-sharing" => "post-sharing",
                "related-posts" => "related-posts",
                "post-author" => "post-author",
                "post-comments" => "post-comments",
                "post-taxonomy" => "post-taxonomy",
                "post-navigation" => "post-navigation",
                "post-reading-time" => "post-reading-time",
                "post-reading-progress-bar" => "post-reading-progress-bar",
                "post-toc" => "post-toc",
                "filter-checkbox" => "filter-checkbox <span class='new-feature'>NEW</span>",
                "filter-datepicker" => "filter-datepicker <span class='new-feature'>NEW</span>",
                "filter-radio" => "filter-radio <span class='new-feature'>NEW</span>",
                "filter-range" => "filter-range <span class='new-feature'>NEW</span>",
                "filter-search" => "filter-search <span class='new-feature'>NEW</span>",
                "filter-select" => "filter-select <span class='new-feature'>NEW</span>",
                "filter-submit" => "filter-submit <span class='new-feature'>NEW</span>"
            ];
            $brxc_acf_fields['builder_elements_left_visibility'] = [
                "heading" => "heading",
                "text-basic" => "text-basic",
                "text" => "text",
                "text-link" => "text-link",
                "button" => "button",
                "icon" => "icon",
                "image" => "image",
                "video" => "video",
                "nav-nested" => "nav-nested",
                "dropdown" => "dropdown",
                "offcanvas" => "offcanvas",
                "toggle" => "toggle",
                "divider" => "divider",
                "icon-box" => "icon-box",
                "social-icons" => "social-icons",
                "list" => "list",
                "accordion" => "accordion",
                "accordion-nested" => "accordion-nested",
                "tabs" => "tabs",
                "tabs-nested" => "tabs-nested",
                "form" => "form",
                "map" => "map",
                "alert" => "alert",
                "animated-typing" => "animated-typing",
                "countdown" => "countdown",
                "counter" => "counter",
                "pricing-tables" => "pricing-tables",
                "progress-bar" => "progress-bar",
                "pie-chart" => "pie-chart",
                "team-members" => "team-members",
                "testimonials" => "testimonials",
                "template" => "template",
                "logo" => "logo",
                "facebook-page" => "facebook-page",
                "breadcrumbs" => "breadcrumbs",
                "image-gallery" => "image-gallery",
                "audio" => "audio",
                "carousel" => "carousel",
                "slider" => "slider",
                "slider-nested" => "slider-nested",
                "instagram-feed" => "instagram-feed",
            ];

            $default_dummy_content = 'This is just placeholder text. We will change this out later. It’s just meant to fill space until your content is ready.
Don’t be alarmed, this is just here to fill up space since your finalized copy isn’t ready yet.
Once we have your content finalized, we’ll replace this placeholder text with your real content.
Sometimes it’s nice to put in text just to get an idea of how text will fill in a space on your website.
Traditionally our industry has used Lorem Ipsum, which is placeholder text written in Latin.
 Unfortunately, not everyone is familiar with Lorem Ipsum and that can lead to confusion.
I can’t tell you how many times clients have asked me why their website is in another language.
There are other placeholder text alternatives like Hipster Ipsum, Zombie Ipsum, Bacon Ipsum, and many more.
While often hilarious, these placeholder passages can also lead to much of the same confusion.
If you’re curious, this is Website Ipsum. It was specifically developed for the use on development websites.
Other than being less confusing than other Ipsum’s, Website Ipsum is also formatted in patterns more similar to how real copy is formatted on the web today.';
            
            $css_variables_tabs = self::is_global_css_vars_deprecated(false) ? array(
                'typography' => '<span>Typography.</span>',
                'spacing' => '<span>Spacing.</span>',
                'border' => '<span>Border.</span>',
                'border-radius' => '<span>Border-Radius.</span>',
                'box-shadow' => '<span>Box-Shadow.</span>',
                'width' => '<span>Width.</span>',
                'custom-variables' => '<span>Custom Variables.</span>',
                'import-framework' => '<span>Import Framework. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The import function let you upload your existing variable\'s labels and integrate them inside the builder functions (such as the variable picker)"></a></span>',
                'theme-variables' => '<span>Theme Variables. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The theme variables are CSS variables attached to a specific theme style. They are managed inside the builder through the variable manager."></a></span>',

            ) : array(
                'import-framework' => '<span>Import Framework. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The import function let you upload your existing variable\'s labels and integrate them inside the builder functions (such as the variable picker)"></a></span>',
                'theme-variables' => '<span>Theme Variables. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The theme variables are CSS variables attached to a specific theme style. They are managed inside the builder through the variable manager."></a></span>',

            );

            acf_add_local_field_group(array(
                'key' => 'group_638315a281bf1',
                'title' => 'Option Page',
                'fields' => array(
                    array(
                        'key' => 'field_63a6feit47c8b4',
                        'label' => 'Global Settings',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_63daa58ccc209',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_23df5h7bvgxib6',
                                'label' => 'General',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1cffpl53',
                                'label' => 'Settings Instruction',
                                'name' => 'brxc_settings_global_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Global Settings</h3>Customize your own experience! Choose the tabs/categories you want to enable inside Advanced Themer, enable the custom elements inside the builder, set the correct permissions in the plugin, and import/export your theme settings. These are only some of the options available in the global settings section.',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_645s9g7tddfj2',
                                'label' => 'Customize the functions included in Advanced Themer',
                                'name' => 'brxc_theme_settings_tabs',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable/Disable any of the following settings. Once disabled, the corresponding function will be completely disabled on both the backend and the frontend',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'global-colors' => '<span>Global Colors. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Enable this option to activate advanced functions related to the Bricks Color palettes."></a></span></span>',
                                    'css-variables' => '<span>CSS Variables.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Enable this option to create Theme CSS variables or import your own CSS Variable framework."></a></span></span>',
                                    'classes-and-styles' => '<span>CSS Classes.</span>',
                                    'builder-tweaks' => '<span>Builder Tweaks.</span>',
                                    'strict-editor-view' => '<span>Strict Editor View. </span>',
                                    'ai' => '<span>AI.</span>',
                                    'extras' => '<span>Extras.</span>',
                                    'admin-bar' => '<span>Admin Bar.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Save the theme settings in order to take effect. Remember that if this option is disabled, you won\'t have access to the Frontend Playground anymore."></a></span>',
                                ),
                                'default_value' => array(
                                    'global-colors',
                                    'css-variables',
                                    'classes-and-styles',
                                    'builder-tweaks',
                                    'strict-editor-view',
                                    'ai',
                                    'extras',
                                    'admin-bar',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),  
                            array(
                                'key' => 'field_23dddd45eexib6',
                                'label' => 'Builder Elements',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_63aabb0ccebeb',
                                'label' => 'Enable / Disable AT\'s Elements',
                                'name' => 'brxc_enable_elements',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Uncheck an element to disable it completely from the whole website.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'darkmode-toggle' => 'Darkmode Toggle',
                                    'darkmode-button' => 'Darkmode Button',
                                ),
                                'default_value' => array(
                                    'darkmode-toggle',
                                    'darkmode-button'
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63aabb0hgh4xs',
                                'label' => 'Enable / Disable any Bricks Element. ',
                                'name' => 'brxc_enable_disable_bricks_elements',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Uncheck the native Bricks elements you want to disable. By default, the unchecked elements will be hidden inside the builder using CSS declarations. However, you can completely unload the elements from the server by toggling the next option.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => $brxc_acf_fields['builder_elements'],
                                'default_value' => $brxc_acf_fields['builder_elements'],
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63a8765frfdx5',
                                'label' => 'Disable Bricks elements on the server. ',
                                'name' => 'brxc_disable_bricks_elements_on_server',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Disabling the elements on the server means all the data related to the unchecked elements won\'t be loaded at all on your site. If you just want to hide elements in the builder\'s list, keep this option unchecked.<div class="helpful-links">Keep in mind that disabling existing elements on your page will generate an error on frontend, so make sure to delete them inside the builder.</div>',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_23der44tyexib6',
                                'label' => 'Permissions',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6388e73289b6a',
                                'label' => 'User Roles Permissions',
                                'name' => 'brxc_user_role_permissions',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Select which roles should have access to your theme settings.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'return_format' => '',
                                'allow_custom' => 0,
                                'layout' => '',
                                'toggle' => 0,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_638tt5f119b6a',
                                'label' => 'File Upload Format Permissions',
                                'name' => 'brxc_file_upload_format_permissions',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Select the following file upload format to upload inside the Media Library',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'css' => 'CSS',
                                    'json' => 'JSON',
                                ),
                                'default_value' => array(
                                    'css',
                                ),
                                'return_format' => '',
                                'allow_custom' => 0,
                                'layout' => '',
                                'toggle' => 0,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_36gssp598yexib6',
                                'label' => 'Miscellaneous',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_63a8765e6ceed',
                                'label' => 'Disable the "ACF" menu item in your Dashboard',
                                'name' => 'brxc_disable_acf_menu_item',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'If for some reason you prefer to hide the ACF menu item from your Dashboard, use this toggle. Note that if you have ACF PRO installed, this option will be ignored and the ACF menu will be visible.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 1,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_63ab55f50e545',
                                'label' => 'Remove all data when uninstalling the plugin ',
                                'name' => 'brxc_remove_data_uninstall',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Check this toggle if you want to erase all the data from your database when uninstalling the plugin. This includes all your theme options, your color palettes, and your license.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_36gd99ldwwp58b6',
                                'label' => 'Import/Export',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6sdtt8p9p89db',
                                'label' => 'Export Instruction',
                                'name' => 'brxc_export_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Export Theme Settings </h3>By clicking the following button, you\'ll download a JSON file with all your theme settings options that can be imported on any site using Advanced Themer. All the options that are related to a file upload (like the Framework import, class importer, resources, etc...) will be skipped from the export and need to be updated manually.<br><br><strong>&#9888; Make sure to save your current settings before the export - unsaved settings won\'t be exported.</strong>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63aabb0frfm12',
                                'label' => 'Choose the Data you want to export',
                                'name' => 'brxc_export_data',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Check the data you want to export.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'at-theme-settings' => '<span>AT Theme Settings.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The AT Theme Settings are all the options set inside this page (Theme Settings). It doesn\'t include the native Bricks Settings, the license key, and other settings that aren\'t saved through the Theme Settings page."></a></span>',
                                    'global-variables' => '<span>Global CSS Variables.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Global CSS variables are all the variables set inside the Builder through the Variable Manager. They don\'t include all the variables set as Theme CSS variables - these ones are includes inside the Theme Styles."></a></span>',
                                    'global-colors' => '<span>Color Palettes.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Color Palettes are all the palettes set inside the Bricks builder. They include both the palettes generated by the core settings of Bricks and by the AT\'Color Manager (including light/dark/shades versions)."></a></span>',
                                    'global-classes' => '<span>Global Classes.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Global Classes are all the classes set inside the core Bricks builder."></a></span>',
                                    'theme-styles' => '<span>Theme Styles.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Theme Styles are all the data set inside the Bricks builder - Settings - Theme Styles. It also includes the Theme variables set on each Theme Style."></a></span>',
                                ),
                                'default_value' => array(
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_64439293865db',
                                'label' => 'Export Settings',
                                'name' => 'brxc_export_theme_settings',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-label hide-border-top',
                                    'id' => '',
                                ),
                                'message' => '<div class="danger-links">⚠ The exported settings can be imported on websites with <strong>Advanced Themer 2.6 (or newer)</strong> installed.</div><br><a id="brxcExportSettings" href="#" class="button button-primary button-large">Export Settings</a>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_6sdtrr8evh9db',
                                'label' => 'Import Instruction',
                                'name' => 'brxc_import_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Import Theme Settings </h3>To import the theme settings, select the JSON file you previously exported and click on Import Theme settings. This action will potentially reset all your current options, and load the exported ones. <strong>The operation can\'t be undone, so before going ahead, make sure to backup and export your actual settings.</strong><br><br><strong><div class="helpful-links">&#9888; In case you don\'t see any changes after the import process, make sure to empty your browser/server caches.</strong></div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63aabb0ddfe51',
                                'label' => 'Choose the Data you want to import',
                                'name' => 'brxc_import_data',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Check the data you want to import.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'at-theme-settings' => '<span>AT Theme Settings.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The AT Theme Settings are all the options set inside this page (Theme Settings). It doesn\'t include the native Bricks Settings, the license key, and other settings that aren\'t saved through the Theme Settings page."></a></span>',
                                    'global-variables' => '<span>Global CSS Variables.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Global CSS variables are all the variables set inside the Builder through the Variable Manager. They don\'t include all the variables set as Theme CSS variables - these ones are includes inside the Theme Styles."></a></span>',
                                    'global-colors' => '<span>Color Palettes.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Color Palettes are all the palettes set inside the Bricks builder. They include both the palettes generated by the core settings of Bricks and by the AT\'Color Manager (including light/dark/shades versions)."></a></span>',
                                    'global-classes' => '<span>Global Classes.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Global Classes are all the classes set inside the core Bricks builder."></a></span>',
                                    'theme-styles' => '<span>Theme Styles.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Theme Styles are all the data set inside the Bricks builder - Settings - Theme Styles. It also includes the Theme variables set on each Theme Style."></a></span>',
                                ),
                                'default_value' => array(
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63a8765grg452',
                                'label' => 'Overwrite Existing Settings',
                                'name' => 'brxc_import_data_overwrite',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'If this option is enabled, existing selected data on the website (such as global classes, theme styles, color palettes, etc...) will be removed before the import. <strong>Use this option wisely!</strong>',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_6445f4r7x85db',
                                'label' => 'Import Settings',
                                'name' => 'brxc_import_theme_settings',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-label hide-border-top',
                                    'id' => '',
                                ),
                                'message' => '<div class="danger-links">⚠ Make sure to import settings from a backup made using <strong>Advanced Themer version 2.6 or newer</strong>. Importing a JSON exported by a previous version of Advanced Themer won\'t work as expected.</div><br><div id="brxcImportWrapper"></div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_36gd99fi8wp58b6',
                                'label' => 'Reset Settings',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6sdggik5r89db',
                                'label' => 'Reset Instruction',
                                'name' => 'brxc_reset_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Reset Settings</h3>By clicking the following button, you\'ll delete the selected options from your database. It\'s recommended to backup your database before proceeding to the theme reset.<br><br><strong>&#9888; The operation can\'t be undone.</strong>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63aabb0rrtrwx',
                                'label' => 'Choose the Data you want to reset',
                                'name' => 'brxc_reset_data',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Check the data you want to reset.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'at-theme-settings' => '<span>AT Theme Settings.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The AT Theme Settings are all the options set inside this page (Theme Settings). It doesn\'t include the native Bricks Settings, the license key, and other settings that aren\'t saved through the Theme Settings page."></a></span>',
                                    'global-variables' => '<span>Global CSS Variables.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Global CSS variables are all the variables set inside the Builder through the Variable Manager. They don\'t include all the variables set as Theme CSS variables - these ones are includes inside the Theme Styles."></a></span>',
                                    'global-colors' => '<span>Color Palettes.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Color Palettes are all the palettes set inside the Bricks builder. They include both the palettes generated by the core settings of Bricks and by the AT\'Color Manager (including light/dark/shades versions)."></a></span>',
                                    'global-classes' => '<span>Global Classes.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Global Classes are all the classes set inside the core Bricks builder."></a></span>',
                                    'theme-styles' => '<span>Theme Styles.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The Theme Styles are all the data set inside the Bricks builder - Settings - Theme Styles. It also includes the Theme variables set on each Theme Style."></a></span>',
                                ),
                                'default_value' => array(
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_6445f4rccdxv5',
                                'label' => 'Reset message CSS Variables',
                                'name' => 'brxc_reset_attention_message_css_variables',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63aabb0rrtrwx',
                                            'operator' => '==',
                                            'value' => 'global-variables',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-label hide-border-top no-padding margin-top-5',
                                    'id' => '',
                                ),
                                'message' => '<div class="danger-links">⚠ You are about to <strong>delete ALL the Global CSS Variables</strong> - including the ones created outside AT - from your server. This action can\'t be restored, unless you have a backup of your settings.</div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_6445f4rhdifn5',
                                'label' => 'Reset message Color Palettes',
                                'name' => 'brxc_reset_attention_message_color_palettes',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63aabb0rrtrwx',
                                            'operator' => '==',
                                            'value' => 'global-colors',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-label hide-border-top no-padding margin-top-5',
                                    'id' => '',
                                ),
                                'message' => '<div class="danger-links">⚠ You are about to <strong>delete ALL the color palettes</strong> - including the ones created outside AT - from your server. This action can\'t be restored, unless you have a backup of your settings.</div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_6445f4rddix5s',
                                'label' => 'Reset message Global Classes',
                                'name' => 'brxc_reset_attention_message_global_classes',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63aabb0rrtrwx',
                                            'operator' => '==',
                                            'value' => 'global-classes',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-label hide-border-top no-padding margin-top-5',
                                    'id' => '',
                                ),
                                'message' => '<div class="danger-links">⚠ You are about to <strong>delete ALL the global classes</strong> - including the ones created outside AT - from your server. This action can\'t be restored, unless you have a backup of your settings.</div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_6445f4rrtrwz5',
                                'label' => 'Reset message Theme Styles',
                                'name' => 'brxc_reset_attention_message_theme_styles',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63aabb0rrtrwx',
                                            'operator' => '==',
                                            'value' => 'theme-styles',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-label hide-border-top no-padding margin-top-5',
                                    'id' => '',
                                ),
                                'message' => '<div class="danger-links">⚠ You are about to <strong>delete ALL the theme styles</strong> - including the ones created outside AT - from your server. This action can\'t be restored, unless you have a backup of your settings.</div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_64dd55fr6j5db',
                                'label' => 'Reset Theme Settings',
                                'name' => 'brxc_reset_theme_settings',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'hide-label margin-top-20',
                                    'id' => '',
                                ),
                                'message' => '<a id="brxcResetSettings" href="#" class="button button-primary button-large">Reset Settings</a>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_63d8cb5hh41vc',
                        'label' => 'Global Colors',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_645s9g7tddfj2',
                                    'operator' => '==',
                                    'value' => 'global-colors',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_63dd51rtyue5e',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_63rri84d4dc52',
                                'label' => 'General',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1cftf81c',
                                'label' => 'Global Colors Instruction',
                                'name' => 'brxc_global_colors_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Global Colors </h3>Manage your global colors inside the builder with ease! All the colors created with the color manager are assigned to a reusable CSS variable. Create shades & scales in few clicks. Define light & dark colors in no time.<div class="helpful-links"><span>ⓘ helpful links: </span><a href="https://advancedthemer.com/category/colors/" target="_blank">Official website</a></div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_639570dfuf009',
                                'label' => 'Add a prefix to your CSS variables',
                                'name' => 'brxc_variable_prefix_global-colors',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => 'The prefix will be automatically added to all your colors (including shades). Example of variable generated with "p1" as prefix: --p1-primary-color.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'prefix-css',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_63a8765565dhd',
                                'label' => 'Enable Dark Mode on frontend',
                                'name' => 'brxc_enable_dark_mode_on_frontend',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Enable this option if you want to enqueue the dark color variables on frontend.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 1,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_63d651dfufnaq',
                                'label' => 'Force a specific default color sheme',
                                'name' => 'brxc_styles_force_default_color_scheme',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'If you wish to enforce a particular default color scheme on the frontend, please choose either "Dark Mode" or "Light Mode" from the dropdown below. Keep in mind that users can override this selection by manually choosing a different color scheme using a darkmode toggle or button.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63a8765565dhd',
                                            'operator' => '==',
                                            'value' => 1,
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'auto' => 'Auto - based on OS preferences',
                                    'light' => 'Light Mode',
                                    'dark' => 'Dark Mode',
                                ),
                                'default_value' => 'auto',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                            array(
                                'key' => 'field_640660a191382',
                                'label' => 'Global Meta Theme Color',
                                'name' => 'brxc_global_meta_theme_color',
                                'aria-label' => '',
                                'type' => 'color_picker',
                                'instructions' => 'Choose a Global Color for the meta name="theme-color". See <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/meta/name/theme-color" target="_blank">https://developer.mozilla.org/en-US/docs/Web/HTML/Element/meta/name/theme-color</a>',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'enable_opacity' => 1,
                                'return_format' => 'string',
                            ),
                            array(
                                'key' => 'field_36gd99l63yexib6',
                                'label' => 'Gutenberg Settings',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_63b3dc8b9484d',
                                'label' => 'Replace Gutenberg Color Palettes',
                                'name' => 'brxc_enable_gutenberg_sync',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'When this option is checked, your Bricks color palettes and Gutenberg color palettes will be synched. Uncheck this option if you don\'t plan to use your custom color palettes with Gutenberg.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_63b3ddc49484f',
                                'label' => 'Remove Default Gutenberg Presets',
                                'name' => 'brxc_remove_default_gutenberg_presets',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'When this option is checked, the default Gutenberg presets\' CSS variables (like --wp--preset--color--black) won\'t be loaded on the frontend anymore.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_63a6a4fg8ec8b6',
                        'label' => 'CSS Variables',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_645s9g7tddfj2',
                                    'operator' => '==',
                                    'value' => 'css-variables',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_6445ab9f3d498',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_63rri84llg132',
                                'label' => 'General',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1cded887',
                                'label' => 'CSS Variables Instruction',
                                'name' => 'brxc_css_variables_global_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>CSS Variables</h3>Manage your CSS variables with ease thanks to our in-built CSS Variables GUI. Create fluid and responsive typography / spacing / border / width scales in few clicks! Since version 2.6, all the CSS variables are managed inside the Builder through the Variable Manager.<br><div class="helpful-links"><span>ⓘ helpful links: </span><a href="https://advancedthemer.com/category/fluid-variables/" target="_blank">Official website</a></div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ), 
                            array(
                                'key' => 'field_641aferwtt57v',
                                'label' => 'Enable CSS Variables Features',
                                'name' => 'brxc_enable_css_variables_features',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Choose which variables you want to use. Disabling a feature will also apply on the frontend.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => $css_variables_tabs,
                                'default_value' => array(),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_63ab121136eb9',
                                'label' => 'Add a prefix to your global CSS variables',
                                'name' => 'brxc_global_prefix',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => 'The prefix will be automatically added to all your CSS variables. Example of variable generated with "p1" as prefix: --p1-gap-1.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'prefix-css',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            )),
                            array(
                                'key' => 'field_63a843db56979',
                                'label' => 'Base Font Size',
                                'name' => 'brxc_base_font_size',
                                'aria-label' => '',
                                'type' => 'number',
                                'instructions' => 'Insert the base font-size you are using on the website. This field is required in order to calculate the correct REM values. Change this value if you know what you\'re doing!<br><strong>The default base font-size in Bricks is 10px.</strong>',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'base-font',
                                    'id' => '',
                                ),
                                'default_value' => 10,
                                'min' => '',
                                'max' => '',
                                'placeholder' => '',
                                'step' => 1,
                                'prepend' => '',
                                'append' => 'px',
                            ),
                            array(
                                'key' => 'field_63a843f85697a',
                                'label' => 'Minimum Viewport Width',
                                'name' => 'brxc_min_vw',
                                'aria-label' => '',
                                'type' => 'number',
                                'instructions' => 'Set the minimum viewport width where the default min value of the clamp function will apply. Above this value, the fluid formula will run until reaching the maximum viewport width.<br><strong>The default value is set to 360px.</strong>',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'min-viewport',
                                    'id' => '',
                                ),
                                'default_value' => 360,
                                'min' => '',
                                'max' => '',
                                'placeholder' => '',
                                'step' => 1,
                                'prepend' => '',
                                'append' => 'px',
                            ),
                            array(
                                'key' => 'field_63a8440d5697b',
                                'label' => 'Maximum Viewport Width',
                                'name' => 'brxc_max_vw',
                                'aria-label' => '',
                                'type' => 'number',
                                'instructions' => 'Set the maximum viewport width where the default max value of the clamp function will apply. Below this value, the fluid formula will run until reaching the minimum viewport width.<br><strong>The default value is set to 1600px.</strong>',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'max-viewport',
                                    'id' => '',
                                ),
                                'default_value' => 1600,
                                'min' => '',
                                'max' => '',
                                'placeholder' => '',
                                'step' => 1,
                                'prepend' => '',
                                'append' => 'px',
                            ),
                            array(
                                'key' => 'field_6399a28ddt82e',
                                'label' => 'Clamp Unit',
                                'name' => 'brxc_clamp_unit',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'Choose the CSS unit used inside the clamp function. Note that CQI might not be supported on older browsers.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'frontend-theme-select',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'vw' => 'VW',
                                    'cqi' => 'CQI',
                                ),
                                'default_value' => 'vw',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_63a6a4d97c8b6',
                                'label' => 'Typography',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641aferwtt57v',
                                            'operator' => '==',
                                            'value' => 'typography',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_6443f5y8765db',
                                'label' => 'Typography Instruction',
                                'name' => 'brxc_typography_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Typography Scale</h3>In the following repeater, you can add/edit/remove your global typography variables. Each row requires a label, a min value, and a max value. The label is used to create your CSS variable like var(--label). The min value is set in Pixels and represents the default value applied when reaching the minimum viewport width set in the Setting tab. The max value is also set in Pixels and represents the default max value when reaching the maximum viewport width. Keep in mind that all the pixels values will be converted in CQI/REM on the frontend.<br><br><strong>The default values are set according to the <a href="https://utopia.fyi/type/calculator/">Utopia\'s fluid type scale calculator</a>.</strong>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_63a6a58831bbe',
                                'label' => 'Typography Variables',
                                'name' => 'brxc_typography_variables_repeater',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'typography-repeater',
                                    'id' => '',
                                ),
                                'layout' => 'block',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => 'field_63a6a58831bbf',
                                'button_label' => 'Add a new typography variable',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_63a6a58831bbf',
                                        'label' => 'Label',
                                        'name' => 'brxc_typography_label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '50',
                                            'class' => 'label',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63a6a58831bbe',
                                    ),
                                    array(
                                        'key' => 'field_63a6a58831bc0',
                                        'label' => 'Min Value',
                                        'name' => 'brxc_typography_min_value',
                                        'aria-label' => '',
                                        'type' => 'number',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '25',
                                            'class' => 'min-value',
                                            'id' => '',
                                        ),
                                        'default_value' => 32,
                                        'min' => 1,
                                        'max' => '',
                                        'placeholder' => '',
                                        'step' => 0.01,
                                        'prepend' => '',
                                        'append' => 'px',
                                        'parent_repeater' => 'field_63a6a58831bbe',
                                    ),
                                    array(
                                        'key' => 'field_63a844885697c',
                                        'label' => 'Max Value',
                                        'name' => 'brxc_typography_max_value',
                                        'aria-label' => '',
                                        'type' => 'number',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '25',
                                            'class' => 'max-value',
                                            'id' => '',
                                        ),
                                        'default_value' => 48,
                                        'min' => 1,
                                        'max' => '',
                                        'placeholder' => '',
                                        'step' => 0.01,
                                        'prepend' => '',
                                        'append' => 'px',
                                        'parent_repeater' => 'field_63a6a58831bbe',
                                    ),
                                    array(
                                        'key' => 'field_63c79e51022d8',
                                        'label' => 'Preview',
                                        'name' => '',
                                        'aria-label' => '',
                                        'type' => 'message',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '100',
                                            'class' => 'preview',
                                            'id' => '',
                                        ),
                                        'message' => '<div class="typography-preview">Bricks is awesome.</div>',
                                        'new_lines' => 'wpautop',
                                        'esc_html' => 0,
                                        'parent_repeater' => 'field_63a6a58831bbe',
                                    ),
                                ),
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_63a6a4d17c8b5',
                                'label' => 'Spacing',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641aferwtt57v',
                                            'operator' => '==',
                                            'value' => 'spacing',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_644f5w8x765db',
                                'label' => 'Spacing Instruction',
                                'name' => 'brxc_spacing_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Spacing Scale</h3>In the following repeater, you can add/edit/remove your global spacing variables. Each row requires a label, a min value, and a max value. The label is used to create your CSS variable like var(--label). The min value is set in Pixels and represents the default value applied when reaching the minimum viewport width set in the Setting tab. The max value is also set in Pixels and represents the default max value when reaching the maximum viewport width. Keep in mind that all the pixels values will be converted in CQI/REM on the frontend.<br><br><strong>The default values are set according to the <a href="https://utopia.fyi/type/calculator/">Utopia\'s fluid space calculator</a>.</strong>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_63a6a51731bbb',
                                'label' => 'Spacing Variables',
                                'name' => 'brxc_spacing_variables_repeater',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'spacing-repeater',
                                    'id' => '',
                                ),
                                'layout' => 'block',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => 'field_63a6a53f31bbc',
                                'button_label' => 'Add a new spacing variable',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_63a6a53f31bbc',
                                        'label' => 'Label',
                                        'name' => 'brxc_spacing_label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '50',
                                            'class' => 'label',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63a6a51731bbb',
                                    ),
                                    array(
                                        'key' => 'field_63a6a55c31bbd',
                                        'label' => 'Min Value',
                                        'name' => 'brxc_spacing_min_value',
                                        'aria-label' => '',
                                        'type' => 'number',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '25',
                                            'class' => 'min-value',
                                            'id' => '',
                                        ),
                                        'default_value' => 10,
                                        'min' => 1,
                                        'max' => '',
                                        'placeholder' => '',
                                        'step' => 0.01,
                                        'prepend' => '',
                                        'append' => 'px',
                                        'parent_repeater' => 'field_63a6a51731bbb',
                                    ),
                                    array(
                                        'key' => 'field_63a82e7791041',
                                        'label' => 'Max Value',
                                        'name' => 'brxc_spacing_max_value',
                                        'aria-label' => '',
                                        'type' => 'number',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '25',
                                            'class' => 'max-value',
                                            'id' => '',
                                        ),
                                        'default_value' => 20,
                                        'min' => 1,
                                        'max' => '',
                                        'placeholder' => '',
                                        'step' => 0.01,
                                        'prepend' => '',
                                        'append' => 'px',
                                        'parent_repeater' => 'field_63a6a51731bbb',
                                    ),
                                    array(
                                        'key' => 'field_63c7dc4a42516',
                                        'label' => 'Preview',
                                        'name' => '',
                                        'aria-label' => '',
                                        'type' => 'message',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '100',
                                            'class' => 'preview',
                                            'id' => '',
                                        ),
                                        'message' => '<div class="spacing-preview">
                    <div class="spacing-preview-1"></div>
                    <div class="spacing-preview-2"></div>
                    </div>',
                                        'new_lines' => 'wpautop',
                                        'esc_html' => 0,
                                        'parent_repeater' => 'field_63a6a51731bbb',
                                    ),
                                ),
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_63c8f16xcx58m',
                                'label' => 'Border',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641aferwtt57v',
                                            'operator' => '==',
                                            'value' => 'border',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_644f5d5uuy876',
                                'label' => 'Border Instruction',
                                'name' => 'brxc_border_simple_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Borders</h3>In the following repeater, you can add/edit/remove your global border variables. Each row requires a label and a value. The label is used to create your CSS variable like var(--label). The value need to be a proper CSS border value (example: 1px solid #000000). ',
                                'new_lines' => '',
                                'esc_html' => 0,
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_63c8f17ytr545',
                                'label' => 'Border Variables',
                                'name' => 'brxc_border_simple_variables_repeater',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'border-repeater',
                                    'id' => '',
                                ),
                                'layout' => 'block',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => 'field_63a6a53f31bbc',
                                'button_label' => 'Add a new border variable',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_63c8f17ttrt81',
                                        'label' => 'Label',
                                        'name' => 'brxc_border_simple_label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '50',
                                            'class' => 'label',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63c8f17ytr545',
                                    ),
                                    array(
                                        'key' => 'field_63c8f17ytr44n',
                                        'label' => 'Value',
                                        'name' => 'brxc_border_simple_value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '50',
                                            'class' => 'max-value',
                                            'id' => '',
                                        ),
                                        'default_value' => "3px solid #1061a3",
                                        'placeholder' => '3px solid #1061a3',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63c8f17ytr545',
                                    ),
                                    array(
                                        'key' => 'field_63c8f17dfdr41',
                                        'label' => 'Preview',
                                        'name' => '',
                                        'aria-label' => '',
                                        'type' => 'message',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '100',
                                            'class' => 'preview',
                                            'id' => '',
                                        ),
                                        'message' => '<div class="border-preview">
                    </div>',
                                        'new_lines' => 'wpautop',
                                        'esc_html' => 0,
                                        'parent_repeater' => 'field_63c8f17ytr545',
                                    ),
                                ),
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_63c8f16e5e2ec',
                                'label' => 'Border-radius',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641aferwtt57v',
                                            'operator' => '==',
                                            'value' => 'border-radius',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_644f5d55r89db',
                                'label' => 'Border-radius Instruction',
                                'name' => 'brxc_border_radius_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Border-Radius</h3>In the following repeater, you can add/edit/remove your global border-radius variables. Each row requires a label, a min value, and a max value. The label is used to create your CSS variable like var(--label). The min value is set in Pixels and represents the default value applied when reaching the minimum viewport width set in the Setting tab. The max value is also set in Pixels and represents the default max value when reaching the maximum viewport width. Keep in mind that all the pixels values will be converted in CQI/REM on the frontend.',
                                'new_lines' => '',
                                'esc_html' => 0,
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_63c8f17f5e2ed',
                                'label' => 'Border-radius Variables',
                                'name' => 'brxc_border_variables_repeater',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'border-repeater',
                                    'id' => '',
                                ),
                                'layout' => 'block',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => 'field_63a6a53f31bbc',
                                'button_label' => 'Add a new border-radius variable',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_63c8f17f5e2ee',
                                        'label' => 'Label',
                                        'name' => 'brxc_border_label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '50',
                                            'class' => 'label',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63c8f17f5e2ed',
                                    ),
                                    array(
                                        'key' => 'field_63c8f17f5e2ef',
                                        'label' => 'Min Value',
                                        'name' => 'brxc_border_min_value',
                                        'aria-label' => '',
                                        'type' => 'number',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '25',
                                            'class' => 'min-value',
                                            'id' => '',
                                        ),
                                        'default_value' => 10,
                                        'min' => 1,
                                        'max' => '',
                                        'placeholder' => '',
                                        'step' => 0.01,
                                        'prepend' => '',
                                        'append' => 'px',
                                        'parent_repeater' => 'field_63c8f17f5e2ed',
                                    ),
                                    array(
                                        'key' => 'field_63c8f17f5e2f0',
                                        'label' => 'Max Value',
                                        'name' => 'brxc_border_max_value',
                                        'aria-label' => '',
                                        'type' => 'number',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '25',
                                            'class' => 'max-value',
                                            'id' => '',
                                        ),
                                        'default_value' => 20,
                                        'min' => 1,
                                        'max' => '',
                                        'placeholder' => '',
                                        'step' => 0.01,
                                        'prepend' => '',
                                        'append' => 'px',
                                        'parent_repeater' => 'field_63c8f17f5e2ed',
                                    ),
                                    array(
                                        'key' => 'field_63c8f17f5e2f1',
                                        'label' => 'Preview',
                                        'name' => '',
                                        'aria-label' => '',
                                        'type' => 'message',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '100',
                                            'class' => 'preview',
                                            'id' => '',
                                        ),
                                        'message' => '<div class="border-preview">
                    </div>',
                                        'new_lines' => 'wpautop',
                                        'esc_html' => 0,
                                        'parent_repeater' => 'field_63c8f17f5e2ed',
                                    ),
                                ),
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_63c8f16ede55m',
                                'label' => 'Box-Shadow',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641aferwtt57v',
                                            'operator' => '==',
                                            'value' => 'box-shadow',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_644f5d5dz55bo',
                                'label' => 'Box-Shadow Instruction',
                                'name' => 'brxc_box_shadow_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Box-Shadow</h3>In the following repeater, you can add/edit/remove your global box-shadow variables. Each row requires a label and a value. The label is used to create your CSS variable like var(--label). The value need to be a proper CSS box-shadow value (example: 0px 20px 40px #000).',
                                'new_lines' => '',
                                'esc_html' => 0,
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_63c8f17s4stt6',
                                'label' => 'Box-Shadow Variables',
                                'name' => 'brxc_box_shadow_variables_repeater',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'border-repeater',
                                    'id' => '',
                                ),
                                'layout' => 'block',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => 'field_63a6a53f31bbc',
                                'button_label' => 'Add a new box_shadow variable',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_63c8f17wewty5',
                                        'label' => 'Label',
                                        'name' => 'brxc_box_shadow_label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '50',
                                            'class' => 'label',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63c8f17s4stt6',
                                    ),
                                    array(
                                        'key' => 'field_63c8f17oioep4',
                                        'label' => 'Value',
                                        'name' => 'brxc_box_shadow_value',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '50',
                                            'class' => 'max-value',
                                            'id' => '',
                                        ),
                                        'default_value' => "0px 0px 20px #00000030",
                                        'placeholder' => '0px 0px 20px #00000030',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63c8f17s4stt6',
                                    ),
                                    array(
                                        'key' => 'field_63c8f1787y8rp',
                                        'label' => 'Preview',
                                        'name' => '',
                                        'aria-label' => '',
                                        'type' => 'message',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '100',
                                            'class' => 'preview',
                                            'id' => '',
                                        ),
                                        'message' => '<div class="border-preview">
                    </div>',
                                        'new_lines' => 'wpautop',
                                        'esc_html' => 0,
                                        'parent_repeater' => 'field_63c8f17s4stt6',
                                    ),
                                ),
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_63c8f16gh51vg',
                                'label' => 'Width',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641aferwtt57v',
                                            'operator' => '==',
                                            'value' => 'width',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_644f5d5ty85c6',
                                'label' => 'Width Instruction',
                                'name' => 'brxc_width_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Width</h3>In the following repeater, you can add/edit/remove your global width variables. Each row requires a label, a min value, and a max value. The label is used to create your CSS variable like var(--label). The min value is set in Pixels and represents the default value applied when reaching the minimum viewport width set in the Setting tab. The max value is also set in Pixels and represents the default max value when reaching the maximum viewport width. Keep in mind that all the pixels values will be converted in CQI/REM on frontend.',
                                'new_lines' => '',
                                'esc_html' => 0,
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_63c8f17ppo69i',
                                'label' => 'Width Variables',
                                'name' => 'brxc_width_variables_repeater',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'border-repeater',
                                    'id' => '',
                                ),
                                'layout' => 'block',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => 'field_63a6a53f31bbc',
                                'button_label' => 'Add a new width variable',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_63c8f17trt527',
                                        'label' => 'Label',
                                        'name' => 'brxc_width_label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '50',
                                            'class' => 'label',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63c8f17ppo69i',
                                    ),
                                    array(
                                        'key' => 'field_63c8f17werd63',
                                        'label' => 'Min Value',
                                        'name' => 'brxc_width_min_value',
                                        'aria-label' => '',
                                        'type' => 'number',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '25',
                                            'class' => 'min-value',
                                            'id' => '',
                                        ),
                                        'default_value' => 100,
                                        'min' => 1,
                                        'max' => '',
                                        'placeholder' => '',
                                        'step' => 0.01,
                                        'prepend' => '',
                                        'append' => 'px',
                                        'parent_repeater' => 'field_63c8f17ppo69i',
                                    ),
                                    array(
                                        'key' => 'field_63c8f17pydk54',
                                        'label' => 'Max Value',
                                        'name' => 'brxc_width_max_value',
                                        'aria-label' => '',
                                        'type' => 'number',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '25',
                                            'class' => 'max-value',
                                            'id' => '',
                                        ),
                                        'default_value' => 200,
                                        'min' => 1,
                                        'max' => '',
                                        'placeholder' => '',
                                        'step' => 0.01,
                                        'prepend' => '',
                                        'append' => 'px',
                                        'parent_repeater' => 'field_63c8f17ppo69i',
                                    ),
                                    array(
                                        'key' => 'field_63c8f17696dpi',
                                        'label' => 'Preview',
                                        'name' => '',
                                        'aria-label' => '',
                                        'type' => 'message',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '100',
                                            'class' => 'preview',
                                            'id' => '',
                                        ),
                                        'message' => '<div class="border-preview">
                    </div>',
                                        'new_lines' => 'wpautop',
                                        'esc_html' => 0,
                                        'parent_repeater' => 'field_63c8f17ppo69i',
                                    ),
                                ),
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_63a84218b5268',
                                'label' => 'Custom Variables',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641aferwtt57v',
                                            'operator' => '==',
                                            'value' => 'custom-variables',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_6ss5fd85r89db',
                                'label' => 'Custom Variables Instruction',
                                'name' => 'brxc_custom_variables_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Custom CSS Variables</h3>In the following repeater, you can add/edit/remove your global custom variables. First, create a category where the variable will be stored. The category label will be shown inside the Variable Picker. Each row requires a label and a value. The label is used to create your CSS variable like var(--label). Choose between a static or a fluid (clamp) variable.',
                                'new_lines' => '',
                                'esc_html' => 0,
                            )),
                            self::is_global_css_vars_deprecated(array(
                                'key' => 'field_64066a105f7ec',
                                'label' => 'Custom Variables',
                                'name' => 'brxc_misc_category_repeater',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'spacing-repeater',
                                    'id' => '',
                                ),
                                'layout' => 'block',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => 'field_64066a535f7ed',
                                'button_label' => 'Add a Category',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_64066a535f7ed',
                                        'label' => 'Category Label',
                                        'name' => 'brxc_misc_category_label',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_64066a105f7ec',
                                    ),
                                    array(
                                        'key' => 'field_63dd12891d1d9',
                                        'label' => 'CSS Variables',
                                        'name' => 'brxc_misc_variables_repeater',
                                        'aria-label' => '',
                                        'type' => 'flexible_content',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => 'misc-repeater',
                                            'id' => '',
                                        ),
                                        'layouts' => array(
                                            'layout_63dd12920c84c' => array(
                                                'key' => 'layout_63dd12920c84c',
                                                'name' => 'brxc_misc_fluid_variable',
                                                'label' => 'Fluid Variable',
                                                'display' => 'block',
                                                'sub_fields' => array(
                                                    array(
                                                        'key' => 'field_63dd12dd1d1dc',
                                                        'label' => 'Label',
                                                        'name' => 'brxc_misc_fluid_label',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 1,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => array(
                                                            'width' => '',
                                                            'class' => 'label',
                                                            'id' => '',
                                                        ),
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ),
                                                    array(
                                                        'key' => 'field_63dd12e61d1dd',
                                                        'label' => 'Min Value',
                                                        'name' => 'brxc_misc_fluid_min_value',
                                                        'aria-label' => '',
                                                        'type' => 'number',
                                                        'instructions' => '',
                                                        'required' => 1,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => array(
                                                            'width' => '31',
                                                            'class' => 'min-value',
                                                            'id' => '',
                                                        ),
                                                        'default_value' => 10,
                                                        'min' => 1,
                                                        'max' => '',
                                                        'placeholder' => '',
                                                        'step' => 0.01,
                                                        'prepend' => '',
                                                        'append' => 'px',
                                                    ),
                                                    array(
                                                        'key' => 'field_63dd12f21d1de',
                                                        'label' => 'Max Value',
                                                        'name' => 'brxc_misc_fluid_max_value',
                                                        'aria-label' => '',
                                                        'type' => 'number',
                                                        'instructions' => '',
                                                        'required' => 1,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => array(
                                                            'width' => '31',
                                                            'class' => 'max-value',
                                                            'id' => '',
                                                        ),
                                                        'default_value' => 20,
                                                        'min' => 1,
                                                        'max' => '',
                                                        'placeholder' => '',
                                                        'step' => 0.01,
                                                        'prepend' => '',
                                                        'append' => 'px',
                                                    ),
                                                ),
                                                'min' => '',
                                                'max' => '',
                                            ),
                                            'layout_63dd13191d1e0' => array(
                                                'key' => 'layout_63dd13191d1e0',
                                                'name' => 'brxc_misc_static_variable',
                                                'label' => 'Static Variable',
                                                'display' => 'block',
                                                'sub_fields' => array(
                                                    array(
                                                        'key' => 'field_63dd13341d1e1',
                                                        'label' => 'Label',
                                                        'name' => 'brxc_misc_static_label',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 1,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => array(
                                                            'width' => '',
                                                            'class' => '',
                                                            'id' => '',
                                                        ),
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ),
                                                    array(
                                                        'key' => 'field_63dd135e1d1e2',
                                                        'label' => 'Value',
                                                        'name' => 'brxc_misc_static_value',
                                                        'aria-label' => '',
                                                        'type' => 'text',
                                                        'instructions' => '',
                                                        'required' => 1,
                                                        'conditional_logic' => 0,
                                                        'wrapper' => array(
                                                            'width' => '75',
                                                            'class' => '',
                                                            'id' => '',
                                                        ),
                                                        'default_value' => '',
                                                        'maxlength' => '',
                                                        'placeholder' => '',
                                                        'prepend' => '',
                                                        'append' => '',
                                                    ),
                                                ),
                                                'min' => '',
                                                'max' => '',
                                            ),
                                        ),
                                        'min' => '',
                                        'max' => '',
                                        'button_label' => 'Add a Variable',
                                        'parent_repeater' => 'field_64066a105f7ec',
                                    ),
                                ),
                            )),
                            array(
                                'key' => 'field_63a8rrtg15268',
                                'label' => 'Import Framework',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641aferwtt57v',
                                            'operator' => '==',
                                            'value' => 'import-framework',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6sdwsdwz111db',
                                'label' => 'Import Framework Instruction',
                                'name' => 'brxc_import_framework_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Import your own CSS Variable Framework</h3>In this section, you can upload your own CSS Variable Framework. To do so, just set a label and select the JSON file that contains your categories and variable values. In order to work correctly, you need to follow the same semantic as <a href="' . \BRICKS_ADVANCED_THEMER_URL . 'assets/json/example-framework.json" target="_blank">this example</a>. If you\'re not allowed to upload JSON files to the Media Library, go to the <strong>Settings tab -> Permissions -> toggle on the JSON option.</strong>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_6399a28440091',
                                'label' => 'Choose how to import your Framework',
                                'name' => 'brxc_how_to_import_framework',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'Choose between importing the JSON from an external file or from the database. The latter is useful if your website is password protected, or if your server limits the access to external files.' ,
                                'required' => 0,
                                'conditional_logic' => '',
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'frontend-theme-select',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'json' => 'External JSON',
                                    'database' => 'From the database',
                                ),
                                'default_value' => 'External JSON',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                            array(
                                'key' => 'field_63bdedscc0k3l',
                                'label' => 'Label',
                                'name' => 'brxc_import_framework_database_label',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => 'This value will be used as the toggle text of the Variable Pickr.',
                                'required' => 1,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_6399a28440091',
                                            'operator' => '==',
                                            'value' => 'database',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '100',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_64065d4ffp9c6',
                                'label' => 'Paste the JSON object',
                                'name' => 'brxc_import_framework_database',
                                'aria-label' => '',
                                'type' => 'textarea',
                                'instructions' => 'Insert here a valid JSON object with your categories labels and variables names. Make sure to follow the exact same data structure shown in the placeholder.',
                                'required' => 1,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_6399a28440091',
                                            'operator' => '==',
                                            'value' => 'database',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placeholder' => '{
    "Category Example 1":[
        "test-var-1",
        "test-var-2",
        "test-var-3"
    ],
    "Category Example 2":[
        "test-var-4",
        "test-var-5",
        "test-var-6"
    ],
    "Category Example 3":[
        "test-var-7",
        "test-var-8",
        "test-var-9"
    ]
}',
                                'default_value' => '',
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63b4600putac1',
                                'label' => 'Import your Variable Framework',
                                'name' => 'brxc_import_framework_repeater_skip_export',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_6399a28440091',
                                            'operator' => '==',
                                            'value' => 'json',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'class-importer-repeater',
                                    'id' => '',
                                ),
                                'layout' => 'block',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add a new CSS Variable Framework',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_63bdeds216ac3',
                                        'label' => 'Label',
                                        'name' => 'brxc_import_framework_label_skip_export',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '100',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63b466yy8pac1',
                                    ),
                                    array(
                                        'key' => 'field_6334dcx216ac7',
                                        'label' => 'JSON file',
                                        'name' => 'brxc_import_framework_file_skip_export',
                                        'aria-label' => '',
                                        'type' => 'file',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '100',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'return_format' => 'url',
                                        'library' => 'all',
                                        'min_size' => '',
                                        'max_size' => '',
                                        'mime_types' => 'json',
                                        'parent_repeater' => 'field_63b466yy8pac1',
                                    ),
                                ),
                            ),
                            array(
                                'key' => 'field_63a8rrt561gbn',
                                'label' => 'Theme Variables',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641aferwtt57v',
                                            'operator' => '==',
                                            'value' => 'theme-variables',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6sdwsdwftg691',
                                'label' => 'Theme Variables Instruction',
                                'name' => 'brxc_theme_variables_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Theme Variables</h3>The theme variables are CSS variables attached to a specific theme style. They are managed directly inside the builder through the Variable Manager. The theme variables have more specificity compared to the global variables: it means you can easily override any global variable by a theme variable. Since the theme variables are integrated inside each specific theme style, it results that you can set different variable values for different theme styles. The theme variables are imported/exported alongside with the theme style settings (using the Bricks core function inside the builder).</br></br>The theme variables require Advanced Themer to be activated in order for the variables to be correctly enqueued on your website. So, if you plan to use Advanced Themer only for the builder tweaks and plan to disable the plugin after the build, it\'s not recommended to use it as it could potentially break your design.</br><div class="helpful-links">As a general rule, use the global variables for values that will hardly change from one site to another, and can be set one time as a variable blueprint or framework. Use the theme variables for variables that are highly design-dependent.</div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_6f5o9q1dr5gcv',
                                'label' => 'Enqueue in',
                                'name' => 'brxc_theme_variables_position',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'Select the position in the DOM where the variables should be output. Choosing "Head" will prevent any Flash of Unstyled Content (FOUC), while choosing "Footer" will ensure that the theme variables override any variables initialized in the head.',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'head' => 'Head',
                                    'footer' => 'Footer',
                                ),
                                'default_value' => 'head',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                            array(
                                'key' => 'field_63b49e5fefk54',
                                'label' => 'Set a Priority',
                                'name' => 'brxc_theme_variables_priority',
                                'aria-label' => '',
                                'type' => 'number',
                                'instructions' => 'The higher the priority you choose, the deeper the style tag will be positioned within the Head/Footer.',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => 9999,
                                'min' => 2,
                                'max' => 9999,
                                'placeholder' => '',
                                'step' => 1,
                                'prepend' => '',
                                'append' => '',
                            ),

                        ),
                    ),
                    array(
                        'key' => 'field_63bf7z2w1b209',
                        'label' => 'CSS Classes',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_645s9g7tddfj2',
                                    'operator' => '==',
                                    'value' => 'classes-and-styles',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_63b59j871b209',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_63rri84ddhg51',
                                'label' => 'General',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1cquc519',
                                'label' => 'Classes Instruction',
                                'name' => 'brxc_classes_and_styles_global_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>CSS Classes</h3>Improve the way you\'re handling classes inside the Bricks Builder.<br><div class="helpful-links"><span>ⓘ helpful links: </span><a href="https://advancedthemer.com/category/styles-classes/" target="_blank">Official website</a></div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ), 
                            array(
                                'key' => 'field_641aferxdk11m',
                                'label' => 'Enable CSS Classes Features',
                                'name' => 'brxc_enable_class_and_styles_features',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable the following features related to your CSS classes inside the Bricks Builder.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'grids' => '<span>Grids Utility Classes. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Create grid utility classes that you can use directly inside the builder."></a></span>',
                                    'class-importer' => '<span>Class Importer.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Import the classes from a CSS file and use them directly inside the builder."></a></span>',
                                ),
                                'default_value' => array(
                                    'grids',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63b48c521b209',
                                'label' => 'Grid Utility Classes',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641aferxdk11m',
                                            'operator' => '==',
                                            'value' => 'grids',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6sdr88tyr89db',
                                'label' => 'Grids Instruction',
                                'name' => 'brxc_grids_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Grid Utility Classes</h3>In the following repeater, you can add/edit/remove your grid utility classes. Each row requires a class name (without dots), a gap value, a maximum number of columns, and a minimum column width (expressed in pixels). Once saved, the classes will be available inside the Builder. Note that grids are already fully responsive.<br><div class="helpful-links">The gap field accepts 2 different values for column-gap/row-gap. The script parse the value if any space is included. If you\'re using a css function to create your gaps (clamp(), minmax(), etc...), make sure to remove any space in it.</div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63b48c6f1b20a',
                                'label' => 'Grid Classes',
                                'name' => 'brxc_grid_builder_repeater',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'grid-repeater',
                                    'id' => '',
                                ),
                                'layout' => 'block',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => 'field_63b48c6f1b20b',
                                'button_label' => 'Add a new grid class',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_63b49e528fc9c',
                                        'label' => 'ID',
                                        'name' => 'brxc_grid_id',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => 'hidden',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63b48c6f1b20a',
                                    ),
                                    array(
                                        'key' => 'field_63b48c6f1b20b',
                                        'label' => 'Class',
                                        'name' => 'brxc_grid_class',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '25',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '.',
                                        'append' => '',
                                        'parent_repeater' => 'field_63b48c6f1b20a',
                                    ),
                                    array(
                                        'key' => 'field_63b48d7e1b20e',
                                        'label' => 'Gap',
                                        'name' => 'brxc_grid_gap',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '25',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '2rem',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63b48c6f1b20a',
                                    ),
                                    array(
                                        'key' => 'field_63b48c6f1b20c',
                                        'label' => 'Max N° of Cols',
                                        'name' => 'brxc_grid_max_col',
                                        'aria-label' => '',
                                        'type' => 'number',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '25',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'min' => 1,
                                        'max' => '',
                                        'placeholder' => '',
                                        'step' => 1,
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63b48c6f1b20a',
                                    ),
                                    array(
                                        'key' => 'field_63b48c6f1b20d',
                                        'label' => 'Min Col Width',
                                        'name' => 'brxc_grid_min_width',
                                        'aria-label' => '',
                                        'type' => 'number',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '25',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'min' => 0,
                                        'max' => '',
                                        'placeholder' => '',
                                        'step' => 1,
                                        'prepend' => '',
                                        'append' => 'px',
                                        'parent_repeater' => 'field_63b48c6f1b20a',
                                    ),
                                ),
                            ),
                            array(
                                'key' => 'field_63b4bd4816ac0',
                                'label' => 'Class Importer',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641aferxdk11m',
                                            'operator' => '==',
                                            'value' => 'class-importer',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6sdw522vr89db',
                                'label' => 'Class Importer Instruction',
                                'name' => 'brxc_class_importer_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Import your classes from a CSS file </h3>In the following repeater, you can add/edit/remove your imported Stylesheets. Each row requires a label and a CSS file attached. The version field is optional. Once saved, the CSS file will be automatically enqueued to your website and all the classes in it will be parsed and added inside the Builder.<br><div class="helpful-links"><strong>Attention:</strong> If you remove one or multiple classes inside your CSS file - or remove an entire CSS file from the repeater - the correspong classes will be automatically removed from the global classes list and from all the elements that are actually using them.</div><br>If you\'re not allowed to upload CSS files to the Media Library, go to the <strong>Settings tab -> Permissions -> toggle on the CSS option.</strong>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63b4bd5c16ac1',
                                'label' => 'Import your Stylesheets',
                                'name' => 'brxc_class_importer_repeater_skip_export',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'class-importer-repeater',
                                    'id' => '',
                                ),
                                'layout' => 'block',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => 'field_63b48c6f1b20b',
                                'button_label' => 'Add a new CSS file',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_63b4bd5c16ac2',
                                        'label' => 'ID',
                                        'name' => 'brxc_class_importer_id_skip_export',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => 'hidden',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63b4bd5c16ac1',
                                    ),
                                    array(
                                        'key' => 'field_63b4bd5c16ac3',
                                        'label' => 'Label',
                                        'name' => 'brxc_class_importer_label_skip_export',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '70',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63b4bd5c16ac1',
                                    ),
                                    array(
                                        'key' => 'field_6406649wff5c12',
                                        'label' => 'Enqueue the CSS',
                                        'name' => 'brxc_class_importer_enqueue_skip_export',
                                        'aria-label' => '',
                                        'type' => 'true_false',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '30',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'message' => '',
                                        'default_value' => '1',
                                        'ui_on_text' => '',
                                        'ui_off_text' => '',
                                        'ui' => 1,
                                    ),
                                    array(
                                        'key' => 'field_6f5o9q1d14dd1',
                                        'label' => 'Enqueue in',
                                        'name' => 'brxc_class_importer_position_skip_export',
                                        'aria-label' => '',
                                        'type' => 'select',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => array(
                                            array(
                                                array(
                                                    'field' => 'field_6406649wff5c12',
                                                    'operator' => '==',
                                                    'value' => '1',
                                                ),
                                            ),
                                        ),
                                        'wrapper' => array(
                                            'width' => '40',
                                            'class' => 'frontend-theme-select',
                                            'id' => '',
                                        ),
                                        'choices' => array(
                                            'head' => 'Head',
                                            'footer' => 'Footer',
                                        ),
                                        'default_value' => 'head',
                                        'return_format' => 'value',
                                        'multiple' => 0,
                                        'allow_null' => 0,
                                        'ui' => 0,
                                        'ajax' => 0,
                                        'placeholder' => '',
                                    ),
                                    array(
                                        'key' => 'field_6f8v4s1x4a5ff',
                                        'label' => 'Priority',
                                        'name' => 'brxc_class_importer_priority_skip_export',
                                        'aria-label' => '',
                                        'type' => 'number',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => array(
                                            array(
                                                array(
                                                    'field' => 'field_6406649wff5c12',
                                                    'operator' => '==',
                                                    'value' => '1',
                                                ),
                                            ),
                                        ),
                                        'wrapper' => array(
                                            'width' => '30',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => 10,
                                        'min' => '',
                                        'max' => '',
                                        'placeholder' => '',
                                        'step' => 1,
                                        'prepend' => '',
                                        'append' => '',
                                    ),
                                    array(
                                        'key' => 'field_63b4bd5c16ac4',
                                        'label' => 'Version',
                                        'name' => 'brxc_class_importer_version_skip_export',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => array(
                                            array(
                                                array(
                                                    'field' => 'field_6406649wff5c12',
                                                    'operator' => '==',
                                                    'value' => '1',
                                                ),
                                            ),
                                        ),
                                        'wrapper' => array(
                                            'width' => '30',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '1.0.0',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63b4bd5c16ac1',
                                    ),
                                    array(
                                        'key' => 'field_6406649wdr55cx',
                                        'label' => 'Use external URL',
                                        'name' => 'brxc_class_importer_use_url_skip_export',
                                        'aria-label' => '',
                                        'type' => 'true_false',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '30',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'message' => '',
                                        'default_value' => '0',
                                        'ui_on_text' => '',
                                        'ui_off_text' => '',
                                        'ui' => 1,
                                    ),
                                    array(
                                        'key' => 'field_63b4bdf216ac7',
                                        'label' => 'CSS file',
                                        'name' => 'brxc_class_importer_file_skip_export',
                                        'aria-label' => '',
                                        'type' => 'file',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => array(
                                            array(
                                                array(
                                                    'field' => 'field_6406649wdr55cx',
                                                    'operator' => '==',
                                                    'value' => '0',
                                                ),
                                            ),
                                        ),
                                        'wrapper' => array(
                                            'width' => '70',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'return_format' => 'url',
                                        'library' => 'all',
                                        'min_size' => '',
                                        'max_size' => '',
                                        'mime_types' => 'css',
                                        'parent_repeater' => 'field_63b4bd5c16ac1',
                                    ),
                                    array(
                                        'key' => 'field_63b4bd5drd51x',
                                        'label' => 'External URL',
                                        'name' => 'brxc_class_importer_url_skip_export',
                                        'aria-label' => '',
                                        'type' => 'url',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => array(
                                            array(
                                                array(
                                                    'field' => 'field_6406649wdr55cx',
                                                    'operator' => '==',
                                                    'value' => '1',
                                                ),
                                            ),
                                        ),
                                        'wrapper' => array(
                                            'width' => '70',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63b4bd5c16ac1',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_63eb7ad55853d',
                        'label' => 'Builder Tweaks',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_645s9g7tddfj2',
                                    'operator' => '==',
                                    'value' => 'builder-tweaks',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_63daa58w1b209',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_63466jhl7c8b6',
                                'label' => 'Topbar',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_641af47fbf980',
                                'label' => 'Topbar <strong>Builder Tweaks</strong>',
                                'name' => 'brxc_enable_global_features',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable the following features inside the Bricks Builder. Once activated, a dedicated icon will be shown inside the Bricks Builder Toolbar.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col big-title',
                                    'id' => '',
                                ),
                                'choices' => array(
                                   'responsive-helper' => '<span>Responsive Helper. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The responsive helper function unlock the 2-click options on breakpoint devices to check the minimum and maximum value of each breakpoint on the fly. It also unlock the breakpoint slider to easily slide between different breakpoints."></a></span>',
                                ),
                                'default_value' => array(
                                    'responsive-helper',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_641af47gigl16',
                                'label' => 'Topbar <strong>Icon Shortcuts</strong>',
                                'name' => 'brxc_topbar_shortcuts',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Choose the shortcuts you want to add to the Bricks topbar. <span style="font-weight:800">All the LEGACY shortcuts are included inside the topbar AT Main Menu</span>: up to you if you want to add a dedicated icon to the header of the Structure Panel (but it\'s not necessary if you have AT\'s main menu enabled).',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col big-title separation',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'main-menu' => '<span>AT Main Menu. <span class="new-feature">RECOMMENDED</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The main menu includes all the other shortcuts. If you want to keep your topbar clean, it\'s probably the only item you want to toggle."></a></span>',
                                    'grid-guides' => '<span>Grid Guides. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="A toggle icon will pop on the toolbar, and when you activate it, a neat grid layer shows up over your design. This helps you make sure everything\'s aligned just right in a snap!"></a></span>',
                                    'x-mode' => '<span>X-Mode. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="By clicking on this toolbar feature, all your elements will receive a greyscale filter and a distinct dark outline to easily spot any layout/overflow issue."></a></span>',
                                    'contrast-checker' => '<span>Contrast Checker. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="The contrast checker highlights any elements in the builder that don\'t meet WCAG 2.0 standards."></a></span>',
                                    'darkmode' => '<span>Darkmode Switcher. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This topbar toggle allows you to switch the preview window from light to dark mode."></a></span>',
                                    'class-manager' => '<span>Global Class Manager. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This topbar icon allows you to quickly open the Class Manager and easily manage your global classes or apply bulk actions to them"></a></span>',
                                    'color-manager' => '<span>Global Color Manager. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This topbar icon allows you to quickly open the Color Manager and create advanced color palettes"></a></span>',
                                    'global-query' => '<span>Global Query Loop Manager. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This topbar icon allows you to quickly open the Query Loop Manager and manage your global queries"></a></span>',
                                    'variable-manager' => '<span>Variable Manager. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This topbar icon allows you to quickly open the Variable Manager and manage all your CSS variables"></a></span>',
                                    'advanced-css' => '<span>Advanced CSS. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This topbar icon allows you to quickly open the Advanced CSS panels and write both page and global CSS inside an enhanced CSS editor"></a></span>',
                                    'openai' => '<span>OpenAI Assistant. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This topbar icon allows you to quickly open the AI assitant and generate text/images/code through simple prompts"></a></span>',
                                    'resources' => '<span>Resources Panel. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This topbar icon allows you to quickly open the Resources Panel and access to predefined images."></a></span>',
                                    'brickslabs' => '<span>BricksLabs Center. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This topbar icon allows you to quickly open the BricksLabs modal and search after articles published on brickslabs.com"></a></span>',
                                ),
                                'default_value' => array(
                                    'main-menu',
                                    'class-manager',
                                    'color-manager',
                                    'variable-manager',
                                    'advanced-css'

                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63466jhric58b',
                                'label' => 'Structure Panel',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_641af4wox5p1',
                                'label' => 'Structure Panel <strong>Builder Tweaks</strong>',
                                'name' => 'brxc_structure_panel_general_tweaks',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable the following global features inside the structure panel',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col big-title',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'new-element-shortcuts' => '<span>Enable shortcuts for creating new elements. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a right sidebar will be created inside the structure panel with shortcuts of the most-used elements that you can add to your structure on the fly."></a></span>',
                                    'styles-and-classes-indicators' => '<span>Styles & Classes indicators. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see new bar indicators inside your structure panel elements. The left bar indicates that the element has at least one global class applied. The right one indicates that it contain styles on the ID level."></a></span>',
                                    'highlight-nestable-elements' => '<span>Highlight Nestable Elements. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the icon of the nestable elements will be highlighted with a blue color."></a></span>',
                                    'highlight-parent-elements' => '<span>Highlight Parent Elements. <span class="improved-feature">IMPROVED</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the background of all the parents of the active element will be highlighted with a light blue color."></a></span>',
                                    'expand-all-children' => '<span>Expand All Children Elements. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new \'expand\' icon will be added inside each item of structure panel. Once clicked, the item and all its children will be expanded."></a></span>',
                                    'draggable-structure-panel' => '<span>Draggable Structure Panel.  <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, double-clicking on the Structure Panel\'s header will dock/undock the panel. Once undocked, you\'ll be able to move the panel around the window by dragging & dropping the header."></a></span>',
                                    'notes' => '<span>Admin/Editor Notes. <span class="new-feature">NEW</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, a new NOTE indicator will be added to your elements inside the structure panel. When hovered over the icon, the note will be displayed. A new icon will also show up inside the structure panel\'s header. When clicked on it, you can toggle the admin/editor notes and show the corresponding icon on each element of your structure panel."></a></span>',
                                    'link' => '<span>Link Indicator. <span class="new-feature">NEW</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, a new LINK indicator will be added to your elements inside the structure panel. "></a></span>',
                                    //'multiselect' => '<span>Multi-edit Elements.<span class="new-feature">NEW</span> <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, hold the SHIFT key and click on multiple elements inside the structure panel. Once selected, any style & class change will apply on all the selected elements at once."></a></span>',
                                ),
                                'default_value' => array(
                                    'new-element-shortcuts',
                                    'styles-and-classes-indicators',
                                    'highlight-nestable-elements',
                                    'highlight-parent-elements',
                                    'expand-all-children',
                                    'draggable-structure-panel',
                                    'notes',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_6426ffg54d84a',
                                'label' => 'Shortcuts for creating the following Elements. ',
                                'name' => 'brxc_elements_shortcuts',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Select the elements shortcuts you want to display inside the right sidebar. Pressing the SHIFT key will show all the keyboard shortcuts associated to any of them inside the Shortcut Bar. Pressing corresponding keyboard key will add the element to the structure panel. Holding both SHIFT and and the key will create the element and set it as active - so you can easily nest elements inside containers/blocks/divs.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4wox5p1',
                                            'operator' => '==',
                                            'value' => 'new-element-shortcuts',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'section' => 'Section',
                                    'container' => 'Container',
                                    'block' => 'Block',
                                    'div' => 'Div',
                                    'heading' => 'Heading',
                                    'text-basic' => 'Text basic',
                                    'text' => 'Rich Text',
                                    'text-link' => '<span>Text Link</span>',
                                    'button' => 'Button',
                                    'icon' => 'Icon',
                                    'image' => 'Image',
                                    'video' => 'Video',
                                    'svg' => 'SVG',
                                    'divider' => 'Divider',
                                    'list' => 'List',
                                    'code' => 'Code',
                                    'template' => 'Template',
                                    'nested-elements' => '<span>Nested Elements <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll have a quick access to the Nested Elements Library introduced in AT version 2.7."></a>',
                                ),
                                'default_value' => array(
                                    'section',
                                    'container',
                                    'block',
                                    'div',
                                    'heading',
                                    'text-basic',
                                    'button',
                                    'icon',
                                    'image',
                                    'code',
                                    'template',
                                    'nested-elements',
                                ),
                                'return_format' => '',
                                'allow_custom' => 0,
                                'layout' => '',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63a8765fhfcos',
                                'label' => 'Enable Keyboard Shortcuts for creating elements by default.',
                                'name' => 'brxc_elements_shortcuts_kb_default',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'If this option is enabled, the keyboard shortcuts will be enabled by default on page load. If this option is off, you\'ll need to activate the keyboard icon on the bottom-right of the builder to activate the shortcuts.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4wox5p1',
                                            'operator' => '==',
                                            'value' => 'new-element-shortcuts',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_63d651ddrf5c2',
                                'label' => 'Styles & Classes Indicators Color',
                                'name' => 'brxc_styles_and_classes_indicator_colors',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'Choose the indicators color inside the structure panel.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4wox5p1',
                                            'operator' => '==',
                                            'value' => 'styles-and-classes-indicators',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'grey' => 'Gray',
                                    'colored' => 'Blue & Pink',
                                ),
                                'default_value' => 'colored',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                            array(
                                'key' => 'field_641af47dd8vp',
                                'label' => 'Structure Panel <strong>Icons Shortcuts</strong>',
                                'name' => 'brxc_structure_panel_icons',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable the following icons inside the header of the structure panel. <span style="font-weight:800">All the LEGACY shortcuts are included inside the topbar AT Main Menu</span>: up to you if you want to add a dedicated icon to the header of the Structure Panel (but it\'s not necessary if you have AT\'s main menu enabled).',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col big-title separation',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'tags' => '<span>Elements Tag. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, a new icon will show up inside the structure panel\'s header. When clicked on it, the HTML tag of each element will show up inside your structure."></a></span>',
                                    'structure-helper' => '<span>Structure Helper Shortcut. <span class="improved-feature">IMPROVED</span><span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, a new icon will show up inside the structure panel\'s header. When clicked on it, a new panel will be shown where you can easily filter your structure panel by advanced helpers filters. This feature is now considered as LEGACY since it\'s available inside the topbar AT Main Menu."></a></span>',
                                ),
                                'default_value' => array(
                                    'structure-helper',
                                    'tags',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63d651dddr715',
                                'label' => 'Default Tag View',
                                'name' => 'brxc_default_tag_view',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'Select the default view of the elements tag when loading the builder.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af47dd8vp',
                                            'operator' => '==',
                                            'value' => 'tags',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'none' => 'None',
                                    'overview' => 'Overview Mode - No colors and dropdowns',
                                    'developer' => 'Developer Mode - With colors and dropdowns',
                                ),
                                'default_value' => 'developer',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                            array(
                                'key' => 'field_641af4eeoch5',
                                'label' => 'Tweaks for the <strong>Contextual Menu</strong>',
                                'name' => 'brxc_structure_panel_contextual_menu',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable the following options to be shown inside the contextual menu. <span style="font-weight:800">All the LEGACY shortcuts are included inside the Class Contextual Menu</span>: up to you if you want to add a dedicated item inside the contextual menu (but it\'s not necessary if you have the Class Contextual Menu enabled).',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col big-title separation',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'convert-text' => '<span> Basic Text / Rich Text / Heading Converter. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, a new \'Convert\' menu item will show up inside the Contextual menu. It consents you to convert any Basic text / Rich text / heading element."></a></span>',
                                    'move-element' => '<span>Move Elements. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, a new contextual item will show up with 4 different arrow icons to move the element inside the structure panel."></a></span>',
                                    'delete-wrapper' => '<span>Delete Wrappers & Move Children Up. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, a new icon will show up inside the \'Delete\' menu item. It consents you to delete a wrapper only and move all the children up."></a></span>',
                                    'hide-element' => '<span>Hide/Show Element. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When clicked on this contextual menu item, the element will be set as display:none. If the element has display:none set, clicking the menu item will remove the display property. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'extend-classes-and-styles' => '<span>Extend Classes & Styles Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When clicked on this contextual menu item, a modal will show up and let you extend the classes & styles of the selected element to others elements inside the structure. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'find-and-replace-styles' => '<span>Find & Replace Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When clicked on this contextual menu item, a modal will show up and let you replace any style value on to the chosen elements inside your structure. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'class-converter' => '<span>Class Converter Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When clicked on this contextual menu item, a modal will show up and let you automatically convert the ID styles of an element (and his children) to an autogenerated class. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'style-overview' => '<span>Style Overview Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When clicked on this contextual menu item, a modal will show up with all the styles and classes applied to the active element (including styles on different pseudo-element and breakpoints). This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'component-class-manager' => '<span> Component Class Manager Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, a new \'Component Class Manager\' menu item will show up inside the Contextual menu. It consents you quickly manage the classes of the selected element and his children. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                ),
                                'default_value' => array(
                                    'convert-text',
                                    'move-element',
                                    'delete-wrapper',
                                    'hide-element',
                                    'class-converter',
                                    'style-overview',
                                    'component-class-manager'
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_234hdghl7c8b6',
                                'label' => 'Classes & Styles',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_64074j8de4756',
                                'label' => 'Classes and Styles <strong>Builder Tweaks</strong>',
                                'name' => 'brxc_builder_tweaks_for_classes',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable/Disable any of the following builder tweaks related to classes and styles. <a href="https://advancedthemer.com/category/styles-classes/" target="_blank">Learn more about the builder tweaks for classes & styles</a>',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col big-title',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'clean-deleted-classes' => '<span>Cleanup deleted global classes from the elements. <span class="new-feature">NEW</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option if you want to automatically remove the class ID of all the deleted global classes attached to your elements."></a></span>',
                                    'reorder-classes' => '<span>Reorder the global classes alphabetically. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option if you want your global classes reordered alphabetically inside the Builder."></a></span>',
                                    'group-classes-by-lock-status' => '<span>Group Classes by Lock Status. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option if you want your global classes reordered by Lock Status (locked classes first) inside the Builder."></a></span>',
                                    'disable-id-styles' => '<span>Lock Styles on element ID level. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option if you want to lock the ability to style your elements on an ID level. In order to style your elements, you\'ll need to either create/activate a class or click on lock icon to unlock the style tab."></a></span>',
                                    'variable-picker' => '<span>CSS Variables Picker. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see a new icon popping up on the relevant style fields inside the Bricks builder. When clicked on it, the script will open a modal where you can pick the CSS variable of your choice."></a></span>',
                                    'autocomplete-variable' => '<span>Suggestions Dropdown for CSS Variables. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a dropdown will show up at the bottom of each field when typing with the suggestion list of all the matching CSS variables."></a></span>',
                                    'autocomplete-variable-preview-hover' => '<span>Autocomplete Suggestions on Hover. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, hovering a CSS variable inside the suggestion dropdown will temporarily apply it the field in order to preview the value inside the builder iframe."></a></span>',
                                    'highlight-classes' => '<span>Highlight Classes. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a blue outline will appear on all the elements that share the same class when you select it inside the builder. It\'s a great way to localize where your classes are applied."></a></span>',
                                    'count-classes' => '<span>Count Classes & Navigation. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new counter will show up next to the class name that indicates the number of times the class is used on the page. Clicking on the counter will scroll the page to each element that is using the active class."></a></span>',
                                    'color-preview' => '<span>Color Preview on hover.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked and the color grid of any element is open, hovering on each color will temporarily apply the color to the element. This is a great way to preview your colors inside the builder."></a></span>',
                                    'class-preview' => '<span>Class Preview on hover.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked and the class dropdown of any element is open, hovering on each class will temporarily apply the class to the element. This is a great way to preview the impact of a class to your elements inside the builder."></a></span>',
                                    'class-indicator' => '<span>Indicators of styles inherited from a class. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see a new blue dot on the left of all the controls that have a style generated from an active class."></a></span>',
                                    'breakpoint-indicator' => '<span>Breakpoint Indicator. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see a new small device icon next to each group that has style set on different breakpoint inside the style tab."></a></span>',
                                    'locked-class-indicator' => '<span>Locked Class Indicator. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the locked classes will appear with red background inside the builder. The unlocked ones will be displayed with a green background."></a></span>',
                                    'focus-on-first-class'  => '<span>Auto-focus on the First Unlocked Class.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the first unlocked class of the selected element will be enabled instead of the ID style level."></a></span>',
                                    'autoformat-field-values' => '<span>Autoformat your control values. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, AT will autoformat your control values with CSS functions such as: var(), calc(), clamp(), min(), max() and PX to REM converter (as soon as you unfocus the control)."></a></span>',
                                    //'scoped-variables' => '<span>Scoped Variables.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new repeater control will be available inside the CSS tab where you can set scoped variables."></a></span>',
        
                                ),
                                'default_value' => array(
                                    'reorder-classes',
                                    'disable-id-styles',
                                    'variable-picker',
                                    'autocomplete-variable',
                                    'autocomplete-variable-preview-hover',
                                    'highlight-classes',
                                    'count-classes',
                                    'color-preview',
                                    'class-preview',
                                    'class-indicator',
                                    'breakpoint-indicator',
                                    'locked-class-indicator',
                                    'focus-on-first-class',
                                    'autoformat-field-values'
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63a843ddsxzp5',
                                'label' => 'Lock ID Styles on elements with Classes ',
                                'name' => 'brxc_lock_id_styles_with_one_global_class',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Toggle this option if you want lock the styles of elements that have at least one Global Class. Elements without any Global Class will be unlocked by default.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074j8de4756',
                                            'operator' => '==',
                                            'value' => 'disable-id-styles',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => 1,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_6426ffgdf59xp',
                                'label' => 'Autoformat your controls with the following CSS functions',
                                'name' => 'brxc_autoformat_controls',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Select the functions that you want to apply on your control values',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074j8de4756',
                                            'operator' => '==',
                                            'value' => 'autoformat-field-values',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'clamp' => '<span>clamp() <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Use the following shortcut to create clamp functions on the fly: \'min|max|minViewport|maxViewport\'. Example: typing \'14|24|400|1600\' creates a clamp function where the value will be 14px on 400px screens and 24px on 1600px screens (converted in REM). The viewport values are optionals."></a></span>',
                                    'calc' => '<span>calc() <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Use the following shortcut to create calc functions on the fly: \'a (operator) b\'. Example: typing \'var(--test) * 2\' creates the following output \'calc(var(--test) * 2)\'. There are 4 valid operators: \'+ - * /\'. It\'s import to leave a space before and after the operator in order to trigger the script correctly."></a></span>',
                                    'min' => '<span>min() <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Use the following shortcut to create min functions on the fly: \'a ' . htmlspecialchars('&lt;',ENT_QUOTES, 'UTF-8') . ' b\'. Example: typing \'10rem ' . htmlspecialchars('&lt;',ENT_QUOTES, 'UTF-8') . ' 50vw\' creates the following output \'min(10rem,50vw)\'. It\'s important to leave a space before and after the operator in order to trigger the script correctly."></a></span>',
                                    'max' => '<span>max() <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Use the following shortcut to create max functions on the fly: \'a ' . htmlspecialchars('&gt;',ENT_QUOTES, 'UTF-8') . ' b\'. Example: typing \'12rem ' . htmlspecialchars('&gt;',ENT_QUOTES, 'UTF-8') . ' 25vw\' creates the following output \'max(12rem,25vw)\'. It\'s important to leave a space before and after the operator in order to trigger the script correctly."></a></span>',
                                    'var' => '<span>var() <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Use the following shortcut to create var functions on the fly: \'--variable\'. Example: typing \'--gap\' creates the following var function \'var(--gap)\'."></a></span>',
                                    'close-var-bracket' => '<span>Close variable brackets. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Enable this function to automatically close your variable\'s brackets. Typing \'var(--test\' will output \'var(--test)\'."></a></span>',
                                    'px-to-rem' => '<span>Pixel to Rem converter. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Use the following shortcut to convert your pixel values in REM: \'r:pixelValue\'. Example: typing \'r:10\' or \'r:10px\' will output \'1rem\' if your base font-size in Bricks is set to 62.5%"></a></span>',
                                ),
                                'default_value' => array(
                                    'clamp',
                                    'calc',
                                    'min',
                                    'max',
                                    'var',
                                    'close-var-bracket',
                                    'px-to-rem'
                                ),
                                'return_format' => '',
                                'allow_custom' => 0,
                                'layout' => '',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_64d8r1j6bf980',
                                'label' => '<strong>Advanced CSS</strong> Editor',
                                'name' => 'brxc_advanced_css_panels',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Advanced CSS is a powerful CSS editor integrated inside the Bricks builder. It comes with many improvements compared to the native CSS editor of bricks. <br><br>Enable the following panels inside the Advanced CSS modal.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col big-title separation',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'page-css' => 'Page CSS',
                                    'global-css' => 'Global CSS',
                                    'child-theme-css' => 'Child Theme CSS (read-only)',
                                    'imported-css' => 'Imported CSS (read-only)',
                                    'ai-assistant' => 'AI Assistant',
                                ),
                                'default_value' => array(
                                    'page-css',
                                    'global-css',
                                    'ai-assistant',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63a843dddwxp5',
                                'label' => 'SASS Integration (Experimental)',
                                'name' => 'brxc_sass_integration_advanced_css',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Toggle this option if you are willing to write SASS codes inside Advanced CSS. This option may require extensive CPU and eventually slowdown the builder.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ), 
                            array(
                                'key' => 'field_234jj85lpc8b6',
                                'label' => 'Elements',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_64074ge58dfj2',
                                'label' => 'Elements <strong>Builder Tweaks</strong>',
                                'name' => 'brxc_builder_tweaks_for_elements',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable/Disable any of the following builder tweaks related to the elements. <a href="https://advancedthemer.com/category/builder-tweaks/" target="_blank">Learn more about the general builder tweaks</a>',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col big-title',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'lorem-ipsum' => '<span>Enable Lorem Ipsum Generator.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see a new icon popping up on the relevant text/textarea fields inside the Bricks builder. When clicked on it, the script will automatically generate dummy content for that specific field."></a></span>',
                                    'diable-pin-on-elements' => '<span>Disable the PIN Icon on the elements list. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, The PIN icon inside the Elements List will be hidden."></a></span>',
                                    'close-accordion-tabs' => '<span>Close all open Style accordions by default. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, all the tabs of the Style panel will be closed by default. This allows you to avoid closing the layout tab continuously when styling an element."></a></span>',
                                    'hide-inactive-accordion-panel' => '<span>Hide inactive Style accordion panel. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, all the inactive accordion panels inside the Style tab will be hidden and only the opened accordion panel will show up."></a></span>',
                                    'disable-borders-boxshadows' => '<span>Disable element\'s outline when styling Borders and Box-shadow. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the green outline that surrounds the active element will be removed to consent you to easily style both borders and box-shadows."></a></span>',
                                    'resize-elements-icons' => '<span>Elements Columns & Collapse/Expand. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see new icons on the top-right of the global elements panel that will allow you to control the grid\'s column number and to collapse/expand the different categories."></a></span>',
                                    'superpower-custom-css' => '<span>Superpower the Custom CSS control. <span class="improved-feature">IMPROVED</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the custom css controls will integrate new functionalities such as: match brackets, auto-indent, search function, css variable autocomplete, etc..."></a></span>',
                                    'increase-field-size' => '<span>Increase the Text Controls Size. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the text control fields (where you add your custom values) will be increased from 30% to 50% and leave more room to write css variables and advanced CSS functions."></a></span>',
                                    'class-icons-reveal-on-hover' => '<span>Reveal Class Icons on Hover. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the icons that stand inside the Class input will be hidden by default, and visible when hovered or on focus."></a></span>',
                                    'expand-spacing' => '<span>Expand Spacing Controls. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new expand icon will be visible next to the spacing controls in the builder and will allow you to resize the input to easily type and see CSS variables."></a></span>',
                                    'grid-builder' => '<span>Grid Builder. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new icon will be visible next to the display control in the builder as soon as you select GRID as the display option of your container."></a></span>',
                                    'copy-interactions-conditions' => '<span>Copy/Paste Interactions & Conditions. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you will see new icons inside the interactions/conditions panels to copy and paste your settings from one element to another."></a></span>',
                                    'box-shadow-generator' => '<span>Box-shadow Generator. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new icon will be visible inside the box-shadow control. Clicking on it will open the box-shadow modal where you generate complex box-shadows or apply one of the ready-made preset."></a></span>',
                                    'text-wrapper' => '<span>Advanced Text Wrapper. <span class="improved-feature">IMPROVED</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, new options will appear inside the Basic Text/Heading element. These options allow you to easily wrap/unwrap your selected content inside custom HTML tags, and add custom properties such as global classes, styles, href, etc..."></a></span>',
                                    'focus-point' => '<span>Focus Point.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This option will add a new icon next to the background-position and the object-position controls. Clicking on it will open a new modal where you can set the exact focus point of your images."></a></span>',
                                    'mask-helper' => '<span>Mask Helper.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This option will add a new icon next to the mask controls. Clicking on it will open a new modal where you can preview all the existing masks included in Bricks on your image."></a></span>',
                                    'dynamic-data-modal' => '<span>Dynamic Data Modal. <span class="improved-feature">IMPROVED</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This option will replace the core Bricks dynamic data dropdown by a fullscreen filterable modal"></a></span>',

                                ),
                                'default_value' => array(
                                    'lorem-ipsum',
                                    'close-accordion-tabs',
                                    'hide-inactive-accordion-panel',
                                    'disable-borders-boxshadows',
                                    'resize-elements-icons',
                                    'superpower-custom-css',
                                    'increase-field-size',
                                    'class-icons-reveal-on-hover',
                                    'expand-spacing',
                                    'grid-builder',
                                    'copy-interactions-conditions',
                                    'box-shadow-generator',
                                    'text-wrapper',
                                    'focus-point',
                                    'mask-helper'
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63d651ddc5a6f',
                                'label' => 'Type of dummy content',
                                'name' => 'brxc_lorem_type',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'Choose between the classic Latin Lorem Ipsum text or your own Custom Dummy Content',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074ge58dfj2',
                                            'operator' => '==',
                                            'value' => 'lorem-ipsum',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'lorem' => 'Lorem Ipsum',
                                    'human' => 'Custom Dummy Content',
                                ),
                                'default_value' => 'lorem',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                            array(
                                'key' => 'field_63882c3ffbgc1',
                                'label' => 'Custom Dummy Content',
                                'name' => 'brxc_custom_dummy_content',
                                'aria-label' => '',
                                'type' => 'textarea',
                                'instructions' => 'Type here the custom dummy content (1 line per sentence). The default human-readable Website Ipsum text has been created by <a href="https://websiteipsum.com/" target="_blank">Kyle Van Deusen</a>',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_63d651ddc5a6f',
                                            'operator' => '==',
                                            'value' => 'human',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => $default_dummy_content,
                                'maxlength' => '',
                                'rows' => '',
                                'placeholder' => $default_dummy_content,
                                'new_lines' => '',
                            ),
                            array(
                                'key' => 'field_63dd6b8k2la6f',
                                'label' => 'Default Elements List Columns ',
                                'name' => 'brxc_elements_default_cols',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'Set the default number of columns of the elements list panel when the page is loaded.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074ge58dfj2',
                                            'operator' => '==',
                                            'value' => 'resize-elements-icons',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    '1' => '1 column',
                                    '2' => '2 columns',
                                    '3' => '3 columns',
                                    '4' => '4 columns',
                                ),
                                'default_value' => '1-col',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                            array(
                                'key' => 'field_63a843dhfhx13',
                                'label' => 'SASS Integration (Experimental)',
                                'name' => 'brxc_sass_integration',
                                'aria-label' => '',
                                'type' => 'true_false',
                                'instructions' => 'Toggle this option if you are willing to write SASS codes inside SuperPowerCSS. This option may require extensive CPU and eventually slowdown the builder.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074ge58dfj2',
                                            'operator' => '==',
                                            'value' => 'superpower-custom-css',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => 0,
                                'ui_on_text' => '',
                                'ui_off_text' => '',
                                'ui' => 1,
                            ),
                            // array(
                            //     'key' => 'field_63a843dssxtd5',
                            //     'label' => 'Expanded Spacing Controls by Default',
                            //     'name' => 'brxc_default_spacing_controls',
                            //     'aria-label' => '',
                            //     'type' => 'true_false',
                            //     'instructions' => 'Toggle this option if you want the spacing controls to be expanded by default',
                            //     'required' => 0,
                            //     'conditional_logic' => array(
                            //         array(
                            //             array(
                            //                 'field' => 'field_64074ge58dfj2',
                            //                 'operator' => '==',
                            //                 'value' => 'expand-spacing',
                            //             ),
                            //         ),
                            //     ),
                            //     'wrapper' => array(
                            //         'width' => '',
                            //         'class' => '',
                            //         'id' => '',
                            //     ),
                            //     'default_value' => 1,
                            //     'ui_on_text' => '',
                            //     'ui_off_text' => '',
                            //     'ui' => 1,
                            // ),
                            array(
                                'key' => 'field_64074geddhxir',
                                'label' => 'Elements <strong>Icon Shortcuts</strong>.',
                                'name' => 'brxc_builder_tweaks_shortcuts_icons',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Manage the icon shortcuts inside the element view. <span style="font-weight:800">All the LEGACY shortcuts are included inside the Class Contextual Menu</span>: up to you if you want to add a dedicated icon to you elements (but it\'s not necessary if you have the Class Contextual Menu enabled).',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col big-title separation',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'class-contextual-menu' => '<span>Class Contextual Menu. <span class="new-feature">RECOMMENDED</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, a new icon will show up next to the class input. When clicked on the icon, a new menu will be displayed with tons of improvements for your classes and styles."></a></span>',
                                    'tabs-shortcuts' => '<span>Left Tabs Shortcuts. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to enable the left-panel shorcuts to quickly access to your style groups."></a></span>',
                                    'pseudo-shortcut' => '<span>Pseudo-Element Shortcuts. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, new icon shortcuts will display next to the Condtions and Interactions icons."></a></span>',
                                    'css-shortcut' => '<span>Element CSS Shortcut. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see a new icon popping up on the left panel of each element. Clicking on this icon open the CSS tab of the current element/class."></a></span>',
                                    'style-overview-shortcut' => '<span>Style Overview Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see a new icon popping up on the left panel. Clicking on this icon will open the Style Overview panel with the current element/class settings opened. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'class-manager-shortcut' => '<span>Class Manager Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, you\'ll see a new icon popping up on the left panel when a global class is active. Clicking on this icon will open the Class Manager with the current class settings opened. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'extend-classes' => '<span>Extend Global Classes and Styles Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This feature will consent you to extend the classes & styles from an element to his parents/children. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'find-and-replace' => '<span>Find & Replace Styles Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This feature will consent you to replace any style value assigned to any element inside the builder. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'plain-classes' => '<span>Plain Classes Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new icon will show up next to the element\'s class field. When you click on it, a popup window will appear where you can type the classes you want to add/remove in bulk. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'export-styles-to-class' => '<span>Export ID Styles to Classes Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new export icon will show up next to the element\'s class field. When you\'ll click on it, you\'ll be able to insert a class name and export all your ID styles to it. Note that you can also import the ID styles if a class is activated. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'clone-class' => '<span>Clone Class Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new clone icon will show up once you activate a class. Once clicked, an input will be visibile with the current class name prefiled. Quickly change the name of the class and save it. All the styles will be copied to the new class. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',
                                    'copy-class-to-clipboard' => '<span>Copy Class to Clipboard Shortcut. <span class="legacy-feature">L</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, a new clone icon will show up once you activate a class. Once clicked, the active class\'s name will be copied to the clipboard. This feature is now considered as LEGACY since it\'s available inside the Class Contextual Menu."></a></span>',

                                ),
                                'default_value' => array(
                                    'class-contextual-menu',
                                    'tabs-shortcuts',
                                    'pseudo-shortcut',
                                    'css-shortcut',
                                    'parent-shortcut',
                                    'style-overview-shortcut',
                                    'class-manager-shortcut',
                                    'plain-classes',
                                    'export-styles-to-class'
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63a843d56ff8x',
                                'label' => 'Left Tabs Shortcuts Top Offset',
                                'name' => 'brxc_shortcuts_top_offset',
                                'aria-label' => '',
                                'type' => 'number',
                                'instructions' => 'Insert the distance between the Bricks logo and the first icon shortcut.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074geddhxir',
                                            'operator' => '==',
                                            'value' => 'tabs-shortcuts',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => 159.5,
                                'min' => '',
                                'max' => '',
                                'placeholder' => '',
                                'step' => 0.1,
                                'prepend' => '',
                                'append' => 'px',
                            ),
                            array(
                                'key' => 'field_6426786feb84a',
                                'label' => 'Left Tabs Shortcuts ',
                                'name' => 'brxc_enable_shortcuts_tabs',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Select the shortcut icons you want to display inside each element panel. This will create an icon for each Content/Style Tab to quickly access the accordion tab when styling an element inside the Builder',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074geddhxir',
                                            'operator' => '==',
                                            'value' => 'tabs-shortcuts',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'content' => 'Content',
                                    'layout' => 'Layout',
                                    'typography' => 'Typography',
                                    'background' => 'Background',
                                    'border' => 'Borders',
                                    'gradient' => 'Gradient',
                                    'shapes' => 'Shape Dividers',
                                    'transform' => 'Transform',
                                    'filter' => 'Filters / Transitions',
                                    'css' => 'CSS',
                                    'classes' => 'Classes / ID',
                                    'attributes' => 'Attributes',
                                ),
                                'default_value' => array(
                                    'content',
                                    'layout',
                                    'typography',
                                    'background',
                                    'border',
                                    'gradient',
                                    'shapes',
                                    'transform',
                                    'filter',
                                    'css',
                                    'classes',
                                    'attributes',
                                ),
                                'return_format' => '',
                                'allow_custom' => 0,
                                'layout' => '',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_6420a42b78413',
                                'label' => 'Pseudo Elements Shortcuts',
                                'name' => 'brxc_enable_shortcuts_icons',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Select the shortcut icons you want to display inside each element panel. This will create an icon for each status to quickly activate/deactivate your pseudo-classes when styling an element inside the Builder',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64074geddhxir',
                                            'operator' => '==',
                                            'value' => 'pseudo-shortcut',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'hover' => 'hover',
                                    'before' => 'before',
                                    'after' => 'after',
                                    'active' => 'active',
                                    'focus' => 'focus',
                                ),
                                'default_value' => array(
                                    'hover',
                                    'before',
                                    'after',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),       
                            // SASS INTEGRATION
                            //
                            // array(
                            //     'key' => 'field_63b3dc8ddja92',
                            //     'label' => 'Enable SASS inside the Superpower CSS (Experimental)',
                            //     'name' => 'brxc_enable_sass_superpower_css',
                            //     'aria-label' => '',
                            //     'type' => 'true_false',
                            //     'instructions' => 'When this option is checked, you\'ll be able to write SASS functions inside the Custom CSS field of each element.',
                            //     'required' => 0,
                            //     'conditional_logic' => array(
                            //         array(
                            //             array(
                            //                 'field' => 'field_64074ge58dfj2',
                            //                 'operator' => '==',
                            //                 'value' => 'superpower-custom-css',
                            //             ),
                            //         ),
                            //     ),
                            //     'wrapper' => array(
                            //         'width' => '',
                            //         'class' => '',
                            //         'id' => '',
                            //     ),
                            //     'message' => '',
                            //     'default_value' => 0,
                            //     'ui_on_text' => '',
                            //     'ui_off_text' => '',
                            //     'ui' => 1,
                            // ),
                            array(
                                'key' => 'field_64074gedhc99o',
                                'label' => 'Elements <strong>Custom Settings</strong>',
                                'name' => 'brxc_builder_default_custom_settings',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Change the default settings of a selected number of elements inside the builder or add new properties to the builder. <strong>The orange settings require AT to be installed in order to work correctly - it\'s not safe to enable them if you plan to disable AT once you finished building this site.</strong>',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col big-title separation',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'text-basic-p' => '<span>Set "p" as the default HTML tag for Basic Text. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to set the default HTML tag of all the basic text elements as a paragraph (p)."></a></span>',
                                    'image-figure' => '<span>Set "figure" as the default HTML tag for Images. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to set the default HTML tag of all the image elements as a figure."></a></span>',
                                    'image-caption-off' => '<span>Set caption as "No caption" for Images. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to set No caption as the default caption value for all the image elements."></a></span>',
                                    'button-button' => '<span>Set "button" as the default HTML tag for Buttons. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to set the default HTML tag of all the button elements as a button."></a></span>',
                                    'heading-textarea' => '<span>Set text input as a textarea for Headings. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to set text input of all the heading elements as a textarea."></a></span>',
                                    'icon-svg' => '<span>Set SVG as the default library for Icons. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to set the default Icon library to SVG (make sure you set the right permission for SVG upload in the Bricks Settings)."></a></span>',
                                    'filter-tab' => '<span>New Filters / Transitions Tab. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to create a new accordion called \'Filters / Transitions\' in the style tab of each element."></a></span>',
                                    'classes-tab' => '<span>New Classes / ID Tab. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to to create a new accordion called \'Classes / ID\' in the style tab of each element.."></a></span>',
                                    'overflow-dropdown' => '<span>Set the Overflow control as a dropdown. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to transform the overflow control in a dropdown control with predefined values."></a></span>',
                                    'notes' => '<span>Admin/Editor Notes <span class="new-feature">NEW</span><a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add the ability to add notes to any Bricks Element."></a></span>',
                                    'background-clip'=> '<span class="attention-text">New "background-clip" control. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add the background-clip control to your background setting options."></a></span>',
                                    'white-space' => '<span class="attention-text">New "white-space" control. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add the white-space property to your style layout settings."></a></span>',
                                    'content-visibility' => '<span class="attention-text">New "content-visibility" & "contain-intrinsic-size" controls. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add the content-visibility and the contain-intrinsic-size properties to your style layout settings."></a></span>',
                                    'column-count' => '<span class="attention-text">New "column-count" controls. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add the column properties (count, gap, fill, width) when selecting display block to an element."></a></span>',
                                    'break' => '<span class="attention-text">New "break" controls. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add the break settings (break-before, break-inside, break-after) to your style layout settings."></a></span>',
                                    'transform' => '<span class="attention-text">New "transform" & "perspective" controls. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add advanced transform properties (style, box, perspective, perspective-origin, backface-visibility) to your transform settings."></a></span>',
                                    'css-filters' => '<span class="attention-text">New "backdrop-filter" controls. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Check this option to add the backdrop-filter property to your Filters settings."></a></span>',
                                    'hide-remove-element' => '<span class="attention-text">New "Hide/Remove Element" controls.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="This option allows you to hide any element inside the builder, and/or remove the element from the DOM on the frontend."></a></span>',
                                    
                                ),
                                'default_value' => array(
                                    'text-basic-p',
                                    'heading-textarea',
                                    'filter-tab',
                                    'classes-tab',
                                    'overflow-dropdown',
                                    'notes',

                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_23df21t6y9c8b6',
                                'label' => 'Keyboard Shortcuts',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_641af4few523',
                                'label' => 'Keyboard <strong>Shortcuts</strong>',
                                'name' => 'brxc_keyboard_shortcuts_type',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable the following keyboard shortcuts inside the builder. Use CTRL + CMD for Mac users - CTRL + SHIFT for windows users.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col big-title',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'move-element' => '<span>Move Elements inside the structure panel. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, you\'ll be able to move the elements inside the structure panel using the key SHIFT + ARROW."></a></span>',
                                    'open-at-modal' => '<span>Open AT\'s modals/functions. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Use keyboard shortcuts to open any Advanced Themer\'s modals and general functions."></a></span>',
                                ),
                                'default_value' => array(
                                    'move-element',
                                    'open-at-modal',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63dba4f8f5056',
                                'label' => 'Enable/Disable Grid Guides',
                                'name' => 'brxc_shortcut_grid_guides',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'i',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD +',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_63dba4b8f5055',
                                'label' => 'Enable/Disable X-Mode',
                                'name' => 'brxc_shortcut_xmode',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'j',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD +',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_63dba510f5057',
                                'label' => 'Enable/Disable Contrast Checker',
                                'name' => 'brxc_shortcut_contrast_checker',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'k',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD +',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_63dba543f5058',
                                'label' => 'Enable/Disable Darkmode',
                                'name' => 'brxc_shortcut_darkmode',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'z',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD +',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_63dba55ff5059',
                                'label' => 'Open the Advanced CSS Modal',
                                'name' => 'brxc_shortcut_stylesheet',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'l',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD +',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_63dba59df505a',
                                'label' => 'Open the Resources Modal',
                                'name' => 'brxc_shortcut_resources',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'x',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD +',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_6418f83d91c38',
                                'label' => 'Open the OpenAI Assistant Modal',
                                'name' => 'brxc_shortcut_openai',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'o',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54fe1c38',
                                'label' => 'Open the BricksLabs Modal',
                                'name' => 'brxc_shortcut_brickslabs',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'n',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54ttrkc0',
                                'label' => 'Open the Color Manager',
                                'name' => 'brxc_shortcut_color_manager',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'm',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54ddxbo8',
                                'label' => 'Open the Class Manager',
                                'name' => 'brxc_shortcut_class_manager',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => ',',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54drd5pl',
                                'label' => 'Open the Variable Manager',
                                'name' => 'brxc_shortcut_variable_manager',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'v',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54ffrdl1',
                                'label' => 'Open the Query Loop Manager.',
                                'name' => 'brxc_shortcut_query_loop_manager',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'g',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54ggbg85',
                                'label' => 'Open the Structure Helper.',
                                'name' => 'brxc_shortcut_structure_helper',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'h',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54drwc51',
                                'label' => 'Open Find & Replace.',
                                'name' => 'brxc_shortcut_find_and_replace',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'f',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54ppin6d',
                                'label' => 'Open Plain Classes.',
                                'name' => 'brxc_shortcut_plain_classes',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'p',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_641tt54ffuc5x',
                                'label' => 'Open Nested Elements Library.',
                                'name' => 'brxc_shortcut_nested_elements',
                                'aria-label' => '',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641af4few523',
                                            'operator' => '==',
                                            'value' => 'open-at-modal',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'brxc-shortcode__input',
                                    'id' => '',
                                ),
                                'default_value' => 'e',
                                'maxlength' => 1,
                                'placeholder' => '',
                                'prepend' => 'CTRL + CMD',
                                'append' => '',
                            ),

                        ),
                    ),
                    array(
                        'key' => 'field_63d8cb5wweq55',
                        'label' => 'Strict Editor View',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_645s9g7tddfj2',
                                    'operator' => '==',
                                    'value' => 'strict-editor-view',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_63dd51rddtr57',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_63rri84ppo63m',
                                'label' => 'General',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1ctrtr98',
                                'label' => 'Strict Editor View Instruction',
                                'name' => 'brxc_stric_editor_view_global_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Strict Editor View</h3>The Strict Sditor View restricts style access inside the builder for your clients. It also adds numerous improvements to the overall Bricks experience for non-techy users.<br><div class="helpful-links"><span>ⓘ helpful links: </span><a href="https://advancedthemer.com/category/strict-editor-view/" target="_blank">Official website</a></div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),   
                            array(
                                'key' => 'field_641afertt51dg',
                                'label' => 'Enable Strict Editor View Features',
                                'name' => 'brxc_enable_strict_editor_view_features',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable the following features for your clients when using the Strict Editor View.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'white-label' => '<span>White Label. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Customize the look and feel of the builder for your clients."></a></span>',
                                    'toolbar' => '<span>Toolbar. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Choose which icon should be enabled inside the builder\'s toolbar."></a></span>',
                                    'elements' => '<span>Elements. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="Choose which bricks element should selectable/editable by your client inside the builder."></a></span>',
                                ),
                                'default_value' => array(
                                    'toolbar',
                                    'elements',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_23df21d33gxib6',
                                'label' => 'White label',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641afertt51dg',
                                            'operator' => '==',
                                            'value' => 'white-label',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_64066003f4140',
                                'label' => 'Change Logo Image in the Builder',
                                'name' => 'brxc_change_logo_img_skip_export',
                                'aria-label' => '',
                                'type' => 'image',
                                'instructions' => 'Switch the default Bricks logo to yours inside the Editor View.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'return_format' => 'array',
                                'library' => 'all',
                                'min_width' => '',
                                'min_height' => '',
                                'min_size' => '',
                                'max_width' => '',
                                'max_height' => '',
                                'max_size' => '',
                                'mime_types' => '',
                                'preview_size' => 'medium',
                            ),
                            array(
                                'key' => 'field_640660aee91e4',
                                'label' => 'Change the Accent Color in Editor Mode',
                                'name' => 'brxc_change_accent_color',
                                'aria-label' => '',
                                'type' => 'color_picker',
                                'instructions' => 'Personalize the accent color of the Editor Mode to match your brand\'s color guidelines.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '#ffd64f',
                                'enable_opacity' => 0,
                                'return_format' => 'string',
                            ),
                            array(
                                'key' => 'field_23df21d3ttr857',
                                'label' => 'Toolbar',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641afertt51dg',
                                            'operator' => '==',
                                            'value' => 'toolbar',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_64065d4de47ca',
                                'label' => 'Disable Toolbar Icons',
                                'name' => 'brxc_disable_toolbar_icons',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Click on any of the following icons to hide them from the Strict Editor View\'s Toolbar.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'logo' => 'Logo',
                                    'help' => 'Help',
                                    'pages' => 'Pages',
                                    'revisions' => 'Revisions',
                                    'class-manager' => 'Class Manager',
                                    'settings' => 'Settings',
                                    'breakpoints' => 'Breakpoints',
                                    'dimensions' => 'Dimensions',
                                    'undo-redo' => 'Undo / Redo',
                                    'edit' => 'Edit with WordPress',
                                    'preview' => 'Preview',
                                ),
                                'default_value' => array(
                                    'help',
                                    'pages',
                                    'revisions',
                                    'class-manager',
                                    'settings',
                                    'breakpoints',
                                    'dimensions',
                                    'undo-redo' => 'Undo / Redo',
                                    'edit',
                                    'preview',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_23df21d3gkp1l9',
                                'label' => 'Elements',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_641afertt51dg',
                                            'operator' => '==',
                                            'value' => 'elements',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),       
                            array(
                                'key' => 'field_63e0ccbf3f5d0',
                                'label' => '<strong>Enable the following elements</strong> on Strict Editor View',
                                'name' => 'brxc_enable_strict_editor_view_elements',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'All the following checked elements will be selectable by your clients inside the editor and, thus, partially editable. All the others will be in read-only mode.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col big-title',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'heading' => 'Heading',
                                    'text-basic' => 'Basic Text',
                                    'text' => 'Rich Text',
                                    'text-link' => "Text Link",
                                    'button' => 'Button',
                                    'icon' => 'Icon',
                                    'image' => 'Image',
                                    'video' => 'Video',
                                    'icon-box' => 'Icon box',
                                    'social-icons' => 'Social icons',
                                    'list' => 'List',
                                    'accordion' => 'Accordion',
                                    'accordion-nested' => 'Accordion nested',
                                    'tabs' => 'Tabs',
                                    'tabs-nested' => 'Tabs nested',
                                    'animated-typing' => 'Animated typing',
                                    'countdown' => 'Countdown',
                                    'counter' => 'Counter',
                                    'pricing-tables' => 'Pricing tables',
                                    'progress-bar' => 'Progress bar',
                                    'pie-chart' => 'Pie chart',
                                    'team-members' => 'Team members',
                                    'testimonials' => 'Testimonials',
                                    'logo' => 'Logo',
                                    'facebook-page' => 'Facebook page',
                                    'image-gallery' => 'Image gallery',
                                    'audio' => 'Audio',
                                    'carousel' => 'Carousel',
                                    'slider' => 'Slider',
                                    'slider-nested' => 'Slider nested',
                                    'svg' => 'Svg',
                                    'nav-menu' => 'Nav menu',
                                    'form' => 'Form',
                                ),
                                'default_value' => array(
                                    'heading',
                                    'text-basic',
                                    'text',
                                    'text-link',
                                    'button',
                                    'icon',
                                    'image',
                                    'video',
                                    'icon-box',
                                    'social-icons',
                                    'list',
                                    'animated-typing',
                                    'countdown',
                                    'counter',
                                    'progress-bar',
                                    'pie-chart',
                                    'team-members',
                                    'testimonials',
                                    'facebook-page',
                                    'image-gallery',
                                    'audio',
                                ),
                                'return_format' => 'array',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_64065d4ttv4z2',
                                'label' => '<strong>Builder Tweaks</strong> for Editors',
                                'name' => 'brxc_strict_editor_view_tweaks',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Enable/Disable any of the following Strict Editor View\'s builder tweaks.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-2-col big-title separation',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'disable-all-controls' => '<span>Disable All controls by default.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, all the controls for each element are disabled in the Editor View. You will need to manually enable the controls you want to show to the editors inside the Builder using the Strict Editor builder tweak."></a></span>',
                                    'hide-id-class' => '<span>Hide the ID/Class control.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the control that shows the ID/Class of each element is hidden (inside the element window)."></a></span>',
                                    'hide-dynamic-data' => '<span>Hide the Dynamic Data trigger. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the dynamic data icon won\'t show up inside the controls that allow dynamic data."></a></span>',
                                    'hide-text-toolbar' => '<span>Hide the Text/Heading Toolbar.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the text toolbar will be hidden when clicking on texts inside the preview window."></a></span>',
                                    'hide-structure-panel' => '<span>Hide the Structure panel.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the right structure panel will be hidden and the preview window will take all the available space in the builder."></a></span>',
                                    'reduce-left-panel-visibility' => '<span>Reduce Left Panel Visibility. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is checked, the left panel will be hidden most of the time. It will show up when strictly necessary (changing an image or a setting that can\'t be edited from the iframe)."></a></span>',
                                    'disable-header-footer-edit-button-on-hover' => '<span>Disable Header & Footer edit button on hover. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, editors won\'t have access to the Header & Footer edit link when hovering over the sections."></a></span>',
                                    'remove-template-settings-links' => '<span>Remove Template & Settings Links. <a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, all the links referring to Bricks templates and Settings will be removed for the editors. That include the Bricks Admin menu item and the Toolbar"></a></span>',
                                    'custom-css' => '<span>Inject Custom CSS.<a href="#" class="dashicons dashicons-info acf-js-tooltip" title="When this option is enabled, you\'ll be able to add Custom CSS that will only apply inside the Builder Editor view."></a></span>',

                                ),
                                'default_value' => array(
                                    'disable-all-controls',
                                    'hide-id-class',
                                    'hide-dynamic-data',
                                    'hide-text-toolbar',
                                    'hide-structure-panel',
                                    'reduce-left-panel-visibility',
                                    'disable-header-footer-edit-button-on-hover',
                                    'remove-template-settings-links',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_63aabb0rgrci4',
                                'label' => 'Elements with left panel visibility enabled.',
                                'name' => 'brxc_enable_left_visibility_elements',
                                'aria-label' => '',
                                'type' => 'checkbox',
                                'instructions' => 'Select the Elements with left panel visibility enabled. When these elements will be active, the left element panel will slide-right and the editor will be able to manage the elements options.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64065d4ttv4z2',
                                            'operator' => '==',
                                            'value' => 'reduce-left-panel-visibility',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field checkbox-3-col',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'heading' => 'Heading',
                                    'text-basic' => 'Basic Text',
                                    'text' => 'Rich Text',
                                    'text-link' => "Text Link",
                                    'button' => 'Button',
                                    'icon' => 'Icon',
                                    'image' => 'Image',
                                    'video' => 'Video',
                                    'icon-box' => 'Icon box',
                                    'social-icons' => 'Social icons',
                                    'list' => 'List',
                                    'accordion' => 'Accordion',
                                    'accordion-nested' => 'Accordion nested',
                                    'tabs' => 'Tabs',
                                    'tabs-nested' => 'Tabs nested',
                                    'animated-typing' => 'Animated typing',
                                    'countdown' => 'Countdown',
                                    'counter' => 'Counter',
                                    'pricing-tables' => 'Pricing tables',
                                    'progress-bar' => 'Progress bar',
                                    'pie-chart' => 'Pie chart',
                                    'team-members' => 'Team members',
                                    'testimonials' => 'Testimonials',
                                    'logo' => 'Logo',
                                    'facebook-page' => 'Facebook page',
                                    'image-gallery' => 'Image gallery',
                                    'audio' => 'Audio',
                                    'carousel' => 'Carousel',
                                    'slider' => 'Slider',
                                    'slider-nested' => 'Slider nested',
                                    'svg' => 'Svg',
                                    'nav-menu' => 'Nav menu',
                                    'form' => 'Form',
                                ),
                                'default_value' => array(
                                    'heading',
                                    'text-basic',
                                    'text',
                                    'text-link',
                                    'button',
                                    'icon',
                                    'image',
                                    'video',
                                    'icon-box',
                                    'social-icons',
                                    'list',
                                    'animated-typing',
                                    'countdown',
                                    'counter',
                                    'progress-bar',
                                    'pie-chart',
                                    'team-members',
                                    'testimonials',
                                    'facebook-page',
                                    'image-gallery',
                                    'audio',
                                ),
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                            array(
                                'key' => 'field_64065d455dvt2',
                                'label' => 'Custom CSS ',
                                'name' => 'brxc_strict_editor_custom_css',
                                'aria-label' => '',
                                'type' => 'textarea',
                                'instructions' => 'The following CSS is being applied inside the Strict Editor View Builder. You can add/delete/modify your own CSS rules by modifying the following code.',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_64065d4ttv4z2',
                                            'operator' => '==',
                                            'value' => 'custom-css',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'vertical-field textarea-100',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'return_format' => 'value',
                                'allow_custom' => 0,
                                'layout' => 'vertical',
                                'toggle' => 1,
                                'save_custom' => 0,
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_63d8cb5tut4gg',
                        'label' => 'AI',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_645s9g7tddfj2',
                                    'operator' => '==',
                                    'value' => 'ai',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_63dd51rkj633r',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_63rri84fun798',
                                'label' => 'General',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1c565gtc',
                                'label' => 'AI Instruction',
                                'name' => 'brxc_ai_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>AI Integration</h3>In this section, you can enable the OpenAI intregration inside the Bricks builder (create AI generated text, images, codes, etc...). Make sure to insert a valid OpenAI API Key.<br><div class="helpful-links"><span>ⓘ helpful links: </span><a href="https://advancedthemer.com/category/ai-integration/" target="_blank">Official website</a></div>',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_64018efb660fb',
                                'label' => 'OpenAI API KEY',
                                'name' => 'brxc_ai_api_key_skip_export',
                                'aria-label' => '',
                                'type' => 'password',
                                'instructions' => 'Insert here your OpenAI API key that you can find in your <a href="https://platform.openai.com/account/api-keys" target="_blank">OpenAI account</a>. The key will be stored in your database using a 128-bit AES encryption method.<br><strong>This field is mandatory if you plan to use the AI integration.</strong>',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_6399a28frf471',
                                'label' => 'Default AI model. <span class="improved-feature">IMPROVED</span>',
                                'name' => 'brxc_default_ai_model',
                                'aria-label' => '',
                                'type' => 'select',
                                'instructions' => 'Choose the default AI model from the following list',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'frontend-theme-select',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'gpt-4o' => 'gpt-4o',
                                    'gpt-4o-mini' => 'gpt-4o-mini',
                                    'gpt-4-turbo' => 'gpt-4-turbo',
                                    'gpt-4' => 'gpt-4',
                                    'gpt-4-32k' => 'gpt-4-32k',
                                    'gpt-3.5-turbo' => 'gpt-3.5-turbo',
                                    'gpt-3.5-turbo-16k' => 'gpt-3.5-turbo-16k',
                                ),
                                'default_value' => 'gpt-4o',
                                'return_format' => 'value',
                                'multiple' => 0,
                                'allow_null' => 0,
                                'ui' => 0,
                                'ajax' => 0,
                                'placeholder' => '',
                            ),
                            array(
                                'key' => 'field_64e487ajsie19',
                                'label' => 'Tones of Voice',
                                'name' => 'brxc_ai_tons_of_voice',
                                'aria-label' => '',
                                'type' => 'textarea',
                                'instructions' => 'Set the list of the predefined tones of voice inside the prompt\'s advanced options',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => 'Authoritative
Conversational
Casual
Enthusiastic
Formal
Frank
Friendly
Funny
Humorous
Informative
Irreverent
Matter-of-fact
Passionate
Playful
Professional
Provocative
Respectful
Sarcastic
Smart
Sympathetic
Trustworthy
Witty',
                                'maxlength' => '',
                                'rows' => '',
                                'placeholder' => '',
                                'new_lines' => '',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_63d8cb54c801e',
                        'label' => 'Extras',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_645s9g7tddfj2',
                                    'operator' => '==',
                                    'value' => 'extras',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_63dd51rw1b209',
                        'label' => '',
                        'name' => '',
                        'aria-label' => '',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_63rri84u7c8b6',
                                'label' => 'General',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1cdrt527',
                                'label' => 'Extras Instruction',
                                'name' => 'brxc_extras_global_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Extras</h3>Inside the extras section, you\'ll find nice-to-have features that don\'t fit in any of the previous categories and enhance your overall Bricks experience.',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63466fft58sb6',
                                'label' => 'Resources',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6schh1cdr89db',
                                'label' => 'Resource Instruction',
                                'name' => 'brxc_resource_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>Resources Panel</h3>In the following repeater, you can add/edit/remove the images added inside the Resources Panel. Each row requires a category label and an image gallery. Once saved, the gallery will be accessible inside the Resource Panel, on the right side of the Builder toolbar.',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_63d8cb65c801f',
                                'label' => 'Resources',
                                'name' => 'brxc_resources_repeater_skip_export',
                                'aria-label' => '',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'class-importer-repeater',
                                    'id' => '',
                                ),
                                'layout' => 'block',
                                'pagination' => 0,
                                'min' => 0,
                                'max' => 0,
                                'collapsed' => '',
                                'button_label' => 'Add a Gallery',
                                'rows_per_page' => 20,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_63d8cbb7c8020',
                                        'label' => 'Category',
                                        'name' => 'brxc_resources_category_skip_export',
                                        'aria-label' => '',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'maxlength' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'parent_repeater' => 'field_63d8cb65c801f',
                                    ),
                                    array(
                                        'key' => 'field_63d8cbd8c8021',
                                        'label' => 'Gallery',
                                        'name' => 'brxc_resources_gallery_skip_export',
                                        'aria-label' => '',
                                        'type' => 'gallery',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'return_format' => 'array',
                                        'library' => 'all',
                                        'min' => 1,
                                        'max' => '',
                                        'min_width' => '',
                                        'min_height' => '',
                                        'min_size' => '',
                                        'max_width' => '',
                                        'max_height' => '',
                                        'max_size' => '',
                                        'mime_types' => '',
                                        'insert' => 'append',
                                        'preview_size' => 'medium',
                                        'parent_repeater' => 'field_63d8cb65c801f',
                                    ),
                                ),
                            ),
                            array(
                                'key' => 'field_63466fffft45b',
                                'label' => 'BricksLabs',
                                'name' => '',
                                'aria-label' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'left',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6scht85f999db',
                                'label' => 'BricksLabs Instruction',
                                'name' => 'brxc_bricklabs_message',
                                'aria-label' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => 'fullwidth-message',
                                    'id' => '',
                                ),
                                'message' => '<h3>BricksLabs Panel</h3>The BricksLabs feed is activated on the builder. Just click on the "lab" icon inside the builder\'s topbar to see the last articles published on Bricklabs and filter your results by any given keyword.',
                                'new_lines' => '',
                                'esc_html' => 0,
                            ),
                        ),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'bricks-advanced-themer',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
                'show_in_rest' => 0,
            ));
            
            endif;			
    }

}