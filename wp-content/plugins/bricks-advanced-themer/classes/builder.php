<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Builder{
    public static function setup_query_controls( $control_options ) {
        $get_option = get_option('bricks_advanced_themer_builder_settings');

        if (!isset($get_option) || !is_array($get_option) || !isset($get_option['query_manager']) || !is_array($get_option['query_manager'])) {
            return $control_options;
        }

        $options = $get_option['query_manager'];

        foreach ($options as $settings){
            if (isset($settings['id']) && isset($settings['title'])) {
                $control_options['queryTypes'][$settings['id']] = esc_html__( $settings['title'] );
            }
        }

        return $control_options;

    }

    public static function maybe_run_new_queries( $results, $query_obj ) {

        if (!class_exists('Bricks\Query') || !class_exists('Bricks\Helpers') || !method_exists('Bricks\Helpers', 'code_execution_enabled') || ! \Bricks\Helpers::code_execution_enabled()) {
			return [];
		}
        $get_option = get_option('bricks_advanced_themer_builder_settings');
    
        if (!isset($get_option) || !is_array($get_option) || !isset($get_option['query_manager']) || !is_array($get_option['query_manager'])) {
            return $results;
        }
    
        $options = $get_option['query_manager'];
    
        foreach ($options as $settings){
            if (isset($settings['id']) && isset($settings['args'])) {
                if ($query_obj->object_type === $settings['id']) {
                    
                    $php_query_raw = bricks_render_dynamic_data( $settings['args'] );
                    $query_vars['posts_per_page'] = get_option( 'posts_per_page' );

                    $execute_user_code = function () use ( $php_query_raw ) {
                        $user_result = null; // Initialize a variable to capture the result of user code
        
                        // Capture user code output using output buffering
                        ob_start();
                        $user_result = eval( $php_query_raw ); // Execute the user code
                        ob_get_clean(); // Get the captured output
        
                        return $user_result; // Return the user code result
                    };
        
                    ob_start();
        
                    // Prepare & set error reporting
                    $error_reporting = error_reporting( E_ALL );
                    $display_errors  = ini_get( 'display_errors' );
                    ini_set( 'display_errors', 1 );
        
                    try {
                        $php_query = $execute_user_code();
                    } catch ( \Exception $error ) {
                        echo 'Exception: ' . $error->getMessage();
                        return $results;
                    } catch ( \ParseError $error ) {
                        echo 'ParseError: ' . $error->getMessage();
                        return $results;
                    } catch ( \Error $error ) {
                        echo 'Error: ' . $error->getMessage();
                        return $results;
                    }
        
                    // Reset error reporting
                    ini_set( 'display_errors', $display_errors );
                    error_reporting( $error_reporting );
        
                    // @see https://www.php.net/manual/en/function.eval.php
                    if ( version_compare( PHP_VERSION, '7', '<' ) && $php_query === false || ! empty( $error ) ) {
                        ob_end_clean();
                    } else {
                        ob_get_clean();
                    }

                    if ( ! empty( $php_query ) && is_array( $php_query ) ) {
                        $query_vars          = array_merge( $query_vars, $php_query );
                        $query_vars['paged'] = \Bricks\Query::get_paged_query_var( $query_vars );
                    }
        
                    $posts_query = new \WP_Query( $query_vars  );
        
                    $results = $posts_query->posts;
                }
            }
        }
        
        return $results;
    }
    
    
    public static function setup_post_data( $loop_object, $loop_key, $query_obj ) {
        $get_option = get_option('bricks_advanced_themer_builder_settings');

        if (!isset($get_option) || !is_array($get_option) || !isset($get_option['query_manager']) || !is_array($get_option['query_manager'])) {
            return $loop_object;
        }

        $options = $get_option['query_manager'];

        foreach ($options as $settings){
            if (isset($settings['id'])) {
                if ($query_obj->object_type === $settings['id']) {
                    global $post;

                    if (isset($loop_object)) {
                        $post = get_post($loop_object);
                        setup_postdata($post);
                    }
                }
            }
        }

        return $loop_object;
    }

    public static function populate_grid_classes(){

        $grid_classes = [];

        if ( have_rows( 'field_63b59j871b209' , 'bricks-advanced-themer' ) ) :

            while ( have_rows( 'field_63b59j871b209' , 'bricks-advanced-themer' ) ) : the_row();

                if ( have_rows( 'field_63b48c6f1b20a', 'bricks-advanced-themer' ) ) :

                    while ( have_rows( 'field_63b48c6f1b20a', 'bricks-advanced-themer' ) ) :

                        the_row();

                        $name = get_sub_field('field_63b48c6f1b20b', 'bricks-advanced-themer' );

                        $grid_classes[] = $name;

                    endwhile;

                endif;

            endwhile;

        endif;

        return $grid_classes;

    }

    public static function populate_class_importer(){

        $total_classes = [];
        if ( have_rows( 'field_63b59j871b209' , 'bricks-advanced-themer' ) ) :

            while ( have_rows( 'field_63b59j871b209' , 'bricks-advanced-themer' ) ) : the_row();

                if ( have_rows( 'field_63b4bd5c16ac1', 'bricks-advanced-themer' ) ) :

                    while ( have_rows( 'field_63b4bd5c16ac1', 'bricks-advanced-themer' ) ) :

                        the_row();

                        $id_stylesheet = get_sub_field('field_63b4bd5c16ac2', 'bricks-advanced-themer' );

                        $is_url = get_sub_field('field_6406649wdr55cx', 'bricks-advanced-themer' );

                        $file = $is_url ? get_sub_field('field_63b4bd5drd51x', 'bricks-advanced-themer' ) : get_sub_field('field_63b4bdf216ac7', 'bricks-advanced-themer' );

                        $classes = AT__Class_Importer::extract_selectors_from_css($file);


                        if (isset($classes) && !empty($classes) && is_array($classes) ) {

                            foreach ( $classes as $class) {
            
                                $total_classes[] = str_replace(['.', '#'],'', esc_attr($class));
            
                            }

                        }


                    endwhile;

                endif;

            endwhile;

        endif;

        // Filter to add class: UNDOCUMENTED
        $value = '';
        $imported_classes_from_filter = apply_filters( 'at/imported_classes/import_classes', $value );
        if(isset($imported_classes_from_filter) && !empty($imported_classes_from_filter) && is_array($imported_classes_from_filter) ){
            $classes = array_unique($imported_classes_from_filter);
            foreach ( $classes as $class ) {
                if(isset($class) && !empty($class) && is_string($class)){
                    $total_classes[] = esc_attr($class);
                }
            }
        }
        return $total_classes;
    }

    public static function add_modal_after_body_wrapper() {

        if (!class_exists('Bricks\Capabilities')) {

            return;
        }

        global $brxc_acf_fields;

        if( !function_exists('bricks_is_builder') || ! bricks_is_builder() || !function_exists('bricks_is_builder_iframe') || bricks_is_builder_iframe() || !\Bricks\Capabilities::current_user_has_full_access() === true) return;

        $css = '';

        if(AT__Helpers::is_builder_tweaks_category_activated() && isset($brxc_acf_fields['elements_shortcut_icons']) && !empty($brxc_acf_fields['elements_shortcut_icons']) && is_array($brxc_acf_fields['elements_shortcut_icons']) && in_array('pseudo-shortcut', $brxc_acf_fields['elements_shortcut_icons']) ){
        // Show Open in new tab Icon
        $css .= '#bricks-panel #bricks-panel-element #bricks-panel-header{
            gap: 2px;
            padding-top: var(--builder-spacing);
        }
        #bricks-panel:has([data-control="code"] .CodeMirror) #bricks-panel-element #bricks-panel-header{
            min-height: unset;
        }
        #bricks-panel #bricks-panel-element #bricks-panel-header .actions {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(24px, 1fr));
            justify-content: space-between;
            width: 100%;
            gap: 5px;
            margin-bottom: 22px;
        }
        #bricks-panel #bricks-panel-element #bricks-panel-header .actions li {
           border-radius: var(--builder-border-radius);
           background-color: var(--builder-bg-2);
           min-width: 24px;
           width: 100%;
        }
        #bricks-panel #bricks-panel-element #bricks-panel-header .actions li:nth-of-type(1):after,
        #bricks-panel #bricks-panel-element #bricks-panel-header .actions li:nth-of-type(2):after,
        #bricks-panel #bricks-panel-element #bricks-panel-header .actions li:nth-of-type(3):after {
            right: unset;
            left: 0;
        }
        #bricks-panel #bricks-panel-element #bricks-panel-header input {
            height: auto;
            line-height: var(--builder-input-height);
        }
        #bricks-panel #bricks-panel-element #bricks-panel-header .actions {
            flex-wrap: wrap;
        }
        #bricks-panel #bricks-panel-element #bricks-panel-header .actions li.brxc-header-icon__before svg {
            transform: rotate(90deg);
            scale: 1.1;
         }
         
         #bricks-panel #bricks-panel-element #bricks-panel-header .actions li.brxc-header-icon__after svg {
            transform: rotate(-90deg);
            scale: 1.1;
         }';
        }

        // Hide Bricks Elements inside the builder
        $settings = $brxc_acf_fields['disable_bricks_elements'];
        $all_elements = $brxc_acf_fields['builder_elements'];
        
        if(isset($settings) && is_array($settings)){
            foreach($all_elements as $key){
                if(!in_array($key, $settings, true)){
                    $css .= '#bricks-panel-elements-categories ul.sortable-wrapper li.bricks-add-element[data-element-name="' . esc_attr($key) . '"] {display:none !important;}';;
                }
            }
        }
        
        // Show Parent Structure Item
        if(AT__Helpers::is_builder_tweaks_category_activated() && isset($brxc_acf_fields['structure_panel_general_tweaks']) && !empty($brxc_acf_fields['structure_panel_general_tweaks']) && is_array($brxc_acf_fields['structure_panel_general_tweaks']) && in_array('highlight-parent-elements', $brxc_acf_fields['structure_panel_general_tweaks']) ){
            $css .= '#bricks-structure .structure-item:has(+ .bricks-structure-list .element.active){background-color: #253543;}
            #bricks-structure .structure-item:hover:has(+ .bricks-structure-list .element.active) {background-color: #304455;}
            body[data-builder-mode="light"] #bricks-structure .structure-item:has(+ .bricks-structure-list .element.active){background-color: #dcdde2;}
            body[data-builder-mode="light"] #bricks-structure .structure-item:hover:has(+ .bricks-structure-list .element.active){background-color: #d0d2da;}';
        }

        wp_add_inline_style('bricks-advanced-themer-builder', $css, 'after');
        

        $option = get_option('bricks_advanced_themer_builder_settings');
        // Grid Guides
        if(isset($option['gridGuide']) ){
            $grid_guide_output = "JSON.parse('" . json_encode($option['gridGuide']) . "')";
        } else {
            $grid_guide_output = "false";
        }
        // Query Manager
        if(isset($option['query_manager']) ){
            $query_manager_output = json_encode($option['query_manager']);
        } else {
            $query_manager_output = json_encode([]);
        }

        // Query Manager Cats
        if(isset($option['query_manager_cats']) ){
            $query_manager_cats_output = json_encode($option['query_manager_cats']);
        } else {
            $query_manager_cats_output = json_encode([]);
        }

        // Full Access
        if(isset($option['full_access']) ){
            $full_access_output = json_encode($option['full_access']);
        } else {
            $full_access_output = '{}';
        }

        // Custom Components
        if(isset($option['custom_components_elements']) ){
            $custom_components_elements_output = json_encode($option['custom_components_elements']);
        } else {
            $custom_components_elements_output = json_encode([]);
        }

        if(isset($option['custom_components_categories']) ){
            $custom_components_categories_output = json_encode($option['custom_components_categories']);
        } else {
            $custom_components_categories_output = json_encode([]);
        }



        wp_add_inline_script('bricks-builder', preg_replace( '/\s+/', '', "window.addEventListener('DOMContentLoaded', () => {
            ADMINBRXC.globalSettings.placeholderImg = '" . BRICKS_ADVANCED_THEMER_URL . "assets/img/placeholder-image.png';
            ADMINBRXC.globalSettings.generalCats.gridGuide = " . $grid_guide_output . ";
            ADMINBRXC.globalSettings.generalCats.globalColorsPrefix = '" . $brxc_acf_fields['color_prefix'] . "';
            ADMINBRXC.globalSettings.generalCats.minViewportWidth = " . $brxc_acf_fields['min_vw'] . ";
            ADMINBRXC.globalSettings.generalCats.maxViewportWidth = " . $brxc_acf_fields['max_vw'] . ";
            ADMINBRXC.globalSettings.generalCats.clampUnit = '" . $brxc_acf_fields['clamp_unit']  . "';
            ADMINBRXC.globalSettings.generalCats.cssVariables = JSON.parse('" . json_encode($brxc_acf_fields['css_variables_general']) . "');
            ADMINBRXC.globalSettings.generalCats.classesAndStyles = JSON.parse('" . json_encode($brxc_acf_fields['classes_and_styles_general']) . "');
            ADMINBRXC.globalSettings.shortcutsTabs = JSON.parse('" . json_encode($brxc_acf_fields['enable_tabs_icons']) . "');
            ADMINBRXC.globalSettings.shortcutsIcons = JSON.parse('" . json_encode($brxc_acf_fields['enable_shortcuts_icons']) . "');
            ADMINBRXC.globalSettings.topbarShortcuts = JSON.parse('" . json_encode($brxc_acf_fields['topbar_shortcuts']) . "');
            ADMINBRXC.globalSettings.globalFeatures = JSON.parse('" . json_encode($brxc_acf_fields['enable_global_features']) . "');
            ADMINBRXC.globalSettings.structurePanelIcons = JSON.parse('" . json_encode($brxc_acf_fields['structure_panel_icons']) . "');
            ADMINBRXC.globalSettings.structurePanelTagDefaultView = '" . $brxc_acf_fields['structure_panel_default_tag_view'] . "';
            ADMINBRXC.globalSettings.structurePanelContextualMenu = JSON.parse('" . json_encode($brxc_acf_fields['structure_panel_contextual_menu']) . "');
            ADMINBRXC.globalSettings.structurePanelGeneralTweaks = JSON.parse('" . json_encode($brxc_acf_fields['structure_panel_general_tweaks']) . "');
            ADMINBRXC.globalSettings.structurePanelTagIndicatorColors = '" . $brxc_acf_fields['structure_panel_styles_and_classes_indicator_colors'] . "';
            ADMINBRXC.globalSettings.defaultElementsCol = '" .$brxc_acf_fields['default_elements_list_cols'] . "';
            ADMINBRXC.globalSettings.superPowerCSSEnableSass = '" . $brxc_acf_fields['superpowercss-enable-sass'] . "';
            ADMINBRXC.globalSettings.defaultElementFeatures = JSON.parse('" . json_encode($brxc_acf_fields['custom_default_settings']) . "');
            ADMINBRXC.globalSettings.elementShortcutIcons = JSON.parse('" . json_encode($brxc_acf_fields['elements_shortcut_icons']) . "');
            ADMINBRXC.globalSettings.classFeatures = JSON.parse('" . json_encode($brxc_acf_fields['class_features']) . "');
            ADMINBRXC.globalSettings.classFeatures.lockIdWithClasses = '" .$brxc_acf_fields['lock_id_styles_with_classes'] . "';
            ADMINBRXC.globalSettings.autoFormatFunctions = JSON.parse('" . json_encode($brxc_acf_fields['autoformat_control_values']) . "');
            ADMINBRXC.globalSettings.classFeatures.advancedCSSEnableSass = '" . $brxc_acf_fields['advanced_css_enable_sass'] . "';
            ADMINBRXC.globalSettings.elementFeatures = JSON.parse('" . json_encode($brxc_acf_fields['element_features']) . "');
            ADMINBRXC.globalSettings.themeSettingsTabs = JSON.parse('" . json_encode($brxc_acf_fields['theme_settings_tabs']) . "');
            ADMINBRXC.globalSettings.createElementsShortcuts = JSON.parse('" . json_encode($brxc_acf_fields['create_elements_shortcuts']) . "');
            ADMINBRXC.rightShortcutStates.keyboard = '" . $brxc_acf_fields['create_elements_shortcuts_keyboard_default'] . "';
            ADMINBRXC.globalSettings.loremIpsumtype = '" . $brxc_acf_fields['lorem_type'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.options = JSON.parse('" . json_encode($brxc_acf_fields['keyboard_sc_options']) . "');
            ADMINBRXC.globalSettings.keyboardShortcuts.cssVariableModal = '" . $brxc_acf_fields['keyboard_sc_open_css_variable_modal'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.gridGuides = '" . $brxc_acf_fields['keyboard_sc_enable_grid_guides'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.xMode = '" . $brxc_acf_fields['keyboard_sc_enable_xmode'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.contrastChecker = '" . $brxc_acf_fields['keyboard_sc_enable_constrast_checker'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.darkmode = '" . $brxc_acf_fields['keyboard_sc_enable_darkmode'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.cssStylesheets = '" . $brxc_acf_fields['keyboard_sc_enable_css_stylesheets'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.resources = '" . $brxc_acf_fields['keyboard_sc_enable_resources'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.openai = '" . $brxc_acf_fields['keyboard_sc_enable_openai'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.brickslabs = '" . $brxc_acf_fields['keyboard_sc_enable_brickslabs'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.colorManager = '" . $brxc_acf_fields['keyboard_sc_enable_color_manager'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.classManager = '" . $brxc_acf_fields['keyboard_sc_enable_class_manager'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.variableManager = '" . $brxc_acf_fields['keyboard_sc_enable_variable_manager'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.queryLoopManager = '" . $brxc_acf_fields['keyboard_sc_enable_query_loop_manager'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.structureHelper = '" . $brxc_acf_fields['keyboard_sc_enable_structure_helper'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.findAndReplace = '" . $brxc_acf_fields['keyboard_sc_enable_find_and_replace'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.plainClasses = '" . $brxc_acf_fields['keyboard_sc_enable_plain_classes'] . "';
            ADMINBRXC.globalSettings.keyboardShortcuts.nestedElemenets = '" . $brxc_acf_fields['keyboard_sc_nested_elements'] . "';
            ADMINBRXC.globalSettings.gridClasses = JSON.parse('" . json_encode(self::populate_grid_classes()) . "');
            ADMINBRXC.globalSettings.importedClasses = JSON.parse('" . json_encode(self::populate_class_importer()) . "');
            ADMINBRXC.globalSettings.isAIApiKeyEmpty = '" . $brxc_acf_fields['openai_api_key'] . "';
            ADMINBRXC.globalSettings.defaultAIModel = '" . $brxc_acf_fields['default_api_model'] . "';
            ") . 
            "ADMINBRXC.globalSettings.generalCats.queryManager = " . $query_manager_output . ";
            ADMINBRXC.globalSettings.generalCats.queryManagerCats = " . $query_manager_cats_output . ";
            ADMINBRXC.globalSettings.generalCats.fullAccess = " . $full_access_output . ";
            ADMINBRXC.globalSettings.customComponentsCategories = " . $custom_components_categories_output . ";
            ADMINBRXC.globalSettings.customComponentsElements = " . $custom_components_elements_output . ";
            ADMINBRXC.globalSettings.customDummyContent = `" . $brxc_acf_fields['custom_dummy_content'] . "`;
            })", 'after');

        

        require_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builder_modal.php';
    }

    public static function add_modal_after_body_wrapper_editor() {

        if (!class_exists('Bricks\Capabilities')) {

            return;
        }

        global $brxc_acf_fields;

        if( !function_exists('bricks_is_builder') || ! bricks_is_builder() || !function_exists('bricks_is_builder_iframe') || bricks_is_builder_iframe() || !\Bricks\Capabilities::current_user_has_full_access() !== true) return;

        wp_add_inline_script('bricks-strict-editor-view', preg_replace( '/\s+/', '', "window.addEventListener('DOMContentLoaded', () => {

                ADMINEDITORBRXC.limitPanelVisibilityArr = JSON.parse('" . json_encode($brxc_acf_fields['enable_left_visibility_elements']) . "');
            })"
        ), 'after');
    }
    
    // Create the AJAX function
    public static function openai_ajax_function() {
        // Verify the nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
    
        // Get the data from the wp_option table
        $my_option = get_option( 'bricks-advanced-themer__brxc_ai_api_key_skip_export' );
        $ciphering = "AES-128-CTR";
        $options = 0;
        $decryption_iv = 'UrsV9aENFT*IRfhr';
        $decryption_key = "#34x*R8zmVK^IFG4#a4B3BVYIb";
        $value = openssl_decrypt ($my_option, $ciphering, $decryption_key, $options, $decryption_iv);
    
        // Return the data as JSON
        wp_send_json( $value );
    }
    
    public static function openai_save_image_to_media_library() {
        // Verify the nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
    
        if (!current_user_can('edit_posts')) { 

            wp_send_json_error('You do not have permission to save images.'); 

        } 
        $base64_img= $_POST['image_url'];

        if(!$base64_img){
            wp_send_json_error('Could not retrieve image data.');
        }

        $title = 'ai-image-' . AT__Helpers::generate_unique_string( 6 );
        $upload_dir  = wp_upload_dir();
        $upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

        $img             = str_replace( 'data:image/png;base64,', '', $base64_img );
        $img             = str_replace( ' ', '+', $img );
        $decoded         = base64_decode( $img );
        $filename        = $title . '.png';
        $file_type       = 'image/png';
        $hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

        // Save the image in the uploads directory.
        $upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );
        $target_file = trailingslashit($upload_dir['path']) . $hashed_filename;

        $attachment = array(
            'post_mime_type' => $file_type,
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
            'post_content'   => '',
            'post_status'    => 'inherit',
            'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename )
        );

        $attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );
        $attachment_data = wp_generate_attachment_metadata($attach_id, $target_file);
        wp_update_attachment_metadata($attach_id, $attachment_data);
        wp_send_json_success('Image saved successfully.'); 

    }

    public static function export_advanced_options_callback() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
        // Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    
        if (!wp_verify_nonce($nonce, 'export_advanced_options_nonce')) {
            wp_die("Invalid nonce, please refresh the page and try again.");
        }
        $checked_data = $_POST['checked_data'];

        if(!is_array($checked_data)){
            return;
        }

        $json_data = array();
        global $wpdb;

        // AT Settings
        if(in_array('at-theme-settings', $checked_data)){
            $option_data = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE '%bricks-advanced-themer%' AND option_name NOT LIKE '%_variables_repeater%' AND option_name NOT LIKE '%_skip_export' AND option_name NOT LIKE '%\_api\_%'");
            
            if(isset($option_data) && is_array($option_data)){
                $json_data['at_settings'] = [];
                foreach ($option_data as $row) {
                    $json_data['at_settings'][$row->option_name] = maybe_unserialize($row->option_value);
                }
            }

            $at_settings_builder = get_option('bricks_advanced_themer_builder_settings');
            if( isset($at_settings_builder) && $at_settings_builder && is_array($at_settings_builder) && !empty($at_settings_builder) ) {
                $json_data['at_settings_builder'] = $at_settings_builder;
            }

        }

        // Global Variables
        if(in_array('global-variables', $checked_data)){
            $global_variables = get_option( 'bricks_global_variables' );
            if( isset($global_variables) && $global_variables && is_array($global_variables) && !empty($global_variables) ) {
                $json_data['global-variables'] = $global_variables;
            }

            $global_variables_categories = get_option( 'bricks_global_variables_categories' );
            if( isset($global_variables_categories) && $global_variables_categories && is_array($global_variables_categories) && !empty($global_variables_categories) ) {
                $json_data['global-variables-categories'] = $global_variables_categories;
            }
        }

        // Global Colors
        if(in_array('global-colors', $checked_data)){
            $palette_arr = get_option( 'bricks_color_palette' );
            if( isset($palette_arr) && $palette_arr && is_array($palette_arr) && !empty($palette_arr) ) {
                $json_data['global-colors'] = $palette_arr;
            } 
        }

        // Global Classes
        if(in_array('global-classes', $checked_data)){
            $global_classes = get_option( 'bricks_global_classes' );
            if( isset($global_classes) && $global_classes && is_array($global_classes) && !empty($global_classes) ) {
                $json_data['global-classes'] = $global_classes;
            }
            $global_classes_categories = get_option( 'bricks_global_classes_categories' );
            if( isset($global_classes_categories) && $global_classes_categories && is_array($global_classes_categories) && !empty($global_classes_categories) ) {
                $json_data['global-classes-categories'] = $global_classes_categories;
            }

            $global_classes_locked = get_option( 'bricks_global_classes_locked' );
            if( isset($global_classes_locked) && $global_classes_locked && is_array($global_classes_locked) && !empty($global_classes_locked) ) {
                $json_data['global-classes-locked'] = $global_classes_locked;
            }
        }

        // Theme Styles
        if(in_array('theme-styles', $checked_data)){
            $theme_styles = get_option( 'bricks_theme_styles' );
            if( isset($theme_styles) && $theme_styles && is_array($theme_styles) && !empty($theme_styles) ) {
                $json_data['theme_styles'] = $theme_styles;
            } 
        }

        echo json_encode($json_data);
        
        wp_die(); // Required for AJAX callback 

    } 

    public static function reset_advanced_options_callback() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
        // Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    
        if (!wp_verify_nonce($nonce, 'export_advanced_options_nonce')) {
            wp_die("Invalid nonce, please refresh the page and try again.");
        }
        
        $checked_data = $_POST['checked_data'];

        if(!is_array($checked_data)){
            return;
        }

        $json_data = array();
        global $wpdb;

        // AT Settings
        if (in_array('at-theme-settings', $checked_data)) {
            $option_data = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE '%bricks-advanced-themer%' AND option_name NOT LIKE '%_variables_repeater%'");

            // Delete options
            foreach ($option_data as $option) {
                delete_option($option->option_name);
            }
        }

        // Global Variables
        if (in_array('global-variables', $checked_data)) {
            delete_option( 'bricks_global_variables' );
            delete_option( 'bricks_global_variables_categories' );
        }

        // Global Colors
        if(in_array('global-colors', $checked_data)){
            delete_option( 'bricks_color_palette' );
        }

        // Global Classes
        if(in_array('global-classes', $checked_data)){
            delete_option( 'bricks_global_classes' );
            delete_option( 'bricks_global_classes_categories' );
            delete_option( 'bricks_global_classes_locked' );
        }

        // Theme Styles
        if(in_array('theme-styles', $checked_data)){
            delete_option( 'bricks_theme_styles' );
        }

        wp_send_json_success();
        
        wp_die(); // Required for AJAX callback 

    }
    private static function add_non_duplicate_entries($arr1, $arr2, $property){
        if(isset($arr1) && is_array($arr1) && !empty($arr1)){
            foreach ($arr1 as $objectA) {
                $idA = $objectA[$property];
                $found = false;
            
                // Check if the object with the same id exists in arrayB
                if(isset($arr2) && is_array($arr2) && !empty($arr2)){
                    foreach ($arr2 as $objectB) {
                        $idB = $objectB[$property];
                        if ($idA === $idB) {
                            $found = true;
                            break;
                        }
                    }
                }
            
                // If the object with the same name was not found in arrayB, add it
                if (!$found) {
                    $arr2[] = $objectA;
                }
            }
        }

        return $arr2;
    }

    public static function import_advanced_options_callback() {

        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }


        // Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    
        if (!wp_verify_nonce($nonce, 'export_advanced_options_nonce')) {
            wp_die("Invalid nonce, please refresh the page and try again.");
        }

            
        if ( ! isset( $_FILES['file']['tmp_name'] ) ) { 
            wp_send_json_error( 'File not uploaded.' ); 
        } 

        $temp_path = $_FILES['file']['tmp_name']; 
        $checked_data = $_POST['checked_data'];
        $overwrite = $_POST['overwrite'];
        

        if ($checked_data === null) {
            wp_send_json_error('Invalid checked data.');
        }


        $json_file = AT__Helpers::read_file_contents($temp_path);

        if ($json_file !== false){

            $data = json_decode($json_file, true);

            if ($data === null) {
                wp_send_json_error('Invalid JSON file.');
            }

            global $wpdb;

            // AT Settings
            $pos = strpos($checked_data, 'at-theme-settings');
            if( $pos && isset($data['at_settings']) && is_array($data['at_settings']) ){

                // Theme Settings
                
                $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '%bricks-advanced-themer%' AND option_name NOT LIKE '%_variables_repeater%'");
        
                foreach ($data['at_settings'] as $option_name => $option_value) {
                    if (is_array($option_value)) {
                        $option_value = maybe_serialize($option_value);
                    }

                    $wpdb->insert($wpdb->options, array('option_name' => $option_name, 'option_value' => $option_value));

                }

                // Builder settings
                
                if(isset($data['at_settings_builder']) && $data['at_settings_builder'] && is_array($data['at_settings_builder']) && !empty($data['at_settings_builder'])){
                    update_option('bricks_advanced_themer_builder_settings', $data['at_settings_builder']);
                }
            }

            // Global Variables
            $pos = strpos($checked_data, 'global-variables');
            if( $pos && isset($data['global-variables']) && is_array($data['global-variables']) ){
                
                $global_variables = get_option('bricks_global_variables');

                if( !isset($global_variables) || !$global_variables || !is_array($global_variables) || empty($global_variables) || $overwrite === true ) {
                    $global_variables = [];
                }

                $global_variables = self::add_non_duplicate_entries($data['global-variables'], $global_variables, 'id');

                if(is_array($global_variables) && !empty($global_variables)){
                    update_option('bricks_global_variables', $global_variables);
                }
            }

            if( $pos && isset($data['global-variables-categories']) && is_array($data['global-variables-categories']) ){
                
                $global_variables_categories = get_option('bricks_global_variables_categories');

                if( !isset($global_variables_categories) || !$global_variables_categories || !is_array($global_variables_categories) || empty($global_variables_categories) || $overwrite === true ) {
                    $global_variables_categories = [];
                }

                $global_variables_categories = self::add_non_duplicate_entries($data['global-variables-categories'], $global_variables_categories, 'id');

                if(is_array($global_variables_categories) && !empty($global_variables_categories)){
                    update_option('bricks_global_variables_categories', $global_variables_categories);
                }
            }

            // Global Classes
            $pos = strpos($checked_data, 'global-classes');
            if( $pos && isset($data['global-classes']) && is_array($data['global-classes']) ){
                
                $global_classes = get_option('bricks_global_classes');

                if( !isset($global_classes) || !$global_classes || !is_array($global_classes) || empty($global_classes) || $overwrite === true ) {
                    $global_classes = [];
                }

                $global_classes = self::add_non_duplicate_entries($data['global-classes'], $global_classes, 'name');

                if(is_array($global_classes) && !empty($global_classes)){
                    update_option('bricks_global_classes', $global_classes);
                }
                
            }

            // Global Classes Categories
            if( $pos && isset($data['global-classes-categories']) && is_array($data['global-classes-categories']) ){
                
                $global_classes_categories = get_option('bricks_global_classes_categories');

                if( !isset($global_classes_categories) || !$global_classes_categories || !is_array($global_classes_categories) || empty($global_classes_categories) || $overwrite === true ) {
                    $global_classes_categories = [];
                }

                $global_classes_categories = self::add_non_duplicate_entries($data['global-classes-categories'], $global_classes_categories, 'id');

                if(is_array($global_classes_categories) && !empty($global_classes_categories)){
                    update_option('bricks_global_classes_categories', $global_classes_categories);
                }
                
            }

            // Theme Styles
            $pos = strpos($checked_data, 'theme-styles');
            if( $pos && isset($data['theme_styles']) && is_array($data['theme_styles']) ){
                
                $theme_styles = get_option('bricks_theme_styles');

                if( !isset($theme_styles) || !$theme_styles || !is_array($theme_styles) || empty($theme_styles) || $overwrite === true ) {
                    $theme_styles = [];
                }

                foreach ($data['theme_styles'] as $objectA => $valueA) {
                    $nameA = $objectA;
                    $foundThemeStyle = false;
                
                    // Check if the object with the same name exists in arrayB
                    foreach ($theme_styles as $objectB => $valueB) {
                        $nameB = $objectB;
                        if ($nameA === $nameB) {
                            $foundThemeStyle = true;
                            break;
                        }
                    }
                
                    // If the object with the same name was not found in arrayB, add it
                    if (!$foundThemeStyle) {
                        $theme_styles[$objectA] = $valueA;
                    }
                }

                if(is_array($theme_styles) && !empty($theme_styles)){
                    update_option('bricks_theme_styles', $theme_styles);
                }
                
            }

            // Global Colors
            $pos = strpos($checked_data, 'global-colors');
            if( $pos && isset($data['global-colors']) && is_array($data['global-colors']) ){
                
                $global_colors = get_option('bricks_color_palette');

                if( !isset($global_colors) || !$global_colors || !is_array($global_colors) || empty($global_colors) || $overwrite === true ) {
                    $global_colors = [];
                }

                $global_colors = self::add_non_duplicate_entries($data['global-colors'], $global_colors, 'id');

                if(is_array($global_colors) && !empty($global_colors)){
                    update_option('	bricks_color_palette', $global_colors);
                }
                
            }
    
            wp_send_json_success($data);
        }


        wp_die(); // Required for AJAX callback 
    }

    private static function repositionArrayElement(array &$array, $key, int $order): void{
        if(($a = array_search($key, array_keys($array))) === false){
            throw new \Exception("The {$key} cannot be found in the given array.");
        }
        $p1 = array_splice($array, $a, 1);
        $p2 = array_splice($array, 0, $order);
        $array = array_merge($p2, $p1, $array);
    }
    
    public static function disable_bricks_elements() {
        global $brxc_acf_fields;
        $disable_on_server = $brxc_acf_fields['disable_bricks_elements_on_server'];
        $settings = $brxc_acf_fields['disable_bricks_elements'];
    
        if (!isset($disable_on_server) || !$disable_on_server || !isset($settings) || !is_array($settings)) {
            return;
        }
    
        add_filter('bricks/builder/elements', function ($elements) use ($settings) {
            $index = 0;
            // echo '<pre>';
            // echo json_encode($elements);
            // echo '</pre>';
            foreach ($elements as $element) {
                if (!in_array($element, $settings)) {
                    unset($elements[$index]);
                }
                $index++;
            }
    
            return $elements;
        });
    }

    public static function set_custom_default_values_in_builder(){

        global $brxc_acf_fields;

        $settings = $brxc_acf_fields['custom_default_settings'];

        if (!class_exists('Bricks\Elements') || !AT__Helpers::is_builder_tweaks_category_activated() ) {
            return;
        }

        $elements = \Bricks\Elements::$elements;

        // SuperPower CSS
        if(isset($brxc_acf_fields['element_features']) && is_array($brxc_acf_fields['element_features']) && in_array("superpower-custom-css", $brxc_acf_fields['element_features'])){
            foreach($elements as $element){
                $element = $element['name'];
            
                add_filter( 'bricks/elements/' . $element . '/controls', function( $controls ) {
                    global $brxc_acf_fields;

                    $label = $brxc_acf_fields['superpowercss-enable-sass'] ? esc_html__('SuperPower CSS', 'bricks' ) . '<span class="highlight">SASS</span>' : esc_html__('SuperPower CSS', 'bricks');
        
                    $controls['_cssSuperPowerCSS'] = [
                        'tab'         => 'style',
                        'group'       => '_css',
                        'label'       => $label,
                        'type'        => 'textarea',
                        'pasteStyles' => true,
                        'css'         => [],
                        'hasDynamicData' => false,
                        'description' => esc_html__( 'Use "%root%" to target the element wrapper.', 'bricks' ) 
                                        . '<br /><br /><u>' . esc_html__('Shortcuts', 'bricks' ) . '</u><br />' 
                                        . '<strong>' . esc_html__('r + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rh + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:hover', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rb + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%::before', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('ra + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%::after', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rf + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:focus', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rcf + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:first-child', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rcl + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:last-child', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rc + argument + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:nth-child({argument})', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rtf + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:first-of-type', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rtl + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:last-of-type', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('rt + argument + TAB', 'bricks') . '</strong>' . esc_html__(' => %root%:nth-of-type({argument})', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('q + width + TAB', 'bricks') . '</strong>' . esc_html__(' => @media screen and (max-width: {width}) {}', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('Q + width + TAB', 'bricks') . '</strong>' . esc_html__(' => @media screen and (max-width: {width}) { %root% {} }', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('q + c + TAB', 'bricks') . '</strong>' . esc_html__(' => @media screen and (max-width: {current viewport width}) {}', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('Q + c + TAB', 'bricks') . '</strong>' . esc_html__(' => @media screen and (max-width: {current viewport width}) { %root% {} }', 'bricks' ) . '<br />'
                                        . '<strong>' . esc_html__('CMD + SHIFT + 7', 'bricks') . '</strong>' . esc_html__(' => comment/uncomment the selected code', 'bricks' ) . '<br /><br />'
                                        . esc_html__('Replacing "r" by "R" (capitilized letter) will add the brackets and place the cursor inside of them.' , 'bricks' ) . '<br /><br />',
                        'placeholder' => "Write your CSS here.",
                    ];

                    return $controls;
                });
            }
        }

        // Custom values

        if (isset($settings) && !empty($settings) && is_array($settings) ){
            // Basic Text: p as default HTML Tag
            if( in_array("text-basic-p",  $settings) ){
                add_filter( 'bricks/elements/text-basic/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];
                    $controls['tag']['default'] = "p";
                    return $controls;
                } );
            }
            // Image: figure as default HTML Tag
            if( in_array("image-figure",  $settings) ){
                add_filter( 'bricks/elements/image/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];
                    $controls['tag']['default'] = "figure";
                    return $controls;
                } );
            }
            // Image: caption off
            if( in_array("image-caption-off",  $settings) ){
                add_filter( 'bricks/elements/image/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];
                    $controls['caption']['default'] = 'none';
                    return $controls;
                } );
            }
            // Button: button as default HTML Tag
            if( in_array("button-button",  $settings) ){
                add_filter( 'bricks/elements/button/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];
                    $controls['tag']['default'] = 'button';
                    return $controls;
                } );
            }
            // Set SVG as default icon set for icon elements
            if( in_array("icon-svg",  $settings) ){
                add_filter( 'bricks/elements/icon/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];
                    $controls['icon']['default'] = [
                        'library' => 'svg',
                        'icon'    => '',
                    ];
                    return $controls;
                } );
            }

            // Add fields to all elements

            $settings = $brxc_acf_fields['custom_default_settings'];
            foreach($elements as $element){
                $element = $element['name'];

                
                add_filter( 'bricks/elements/' . $element . '/control_groups', function( $control_groups ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];

                    if(in_array("filter-tab",  $settings) ){
                        $control_groups['_filter'] = [
                            'tab'      => 'style',
                            'title'    => esc_html__( 'Filters / Transitions', 'Bricks' ),
                        ];

                        self::repositionArrayElement($control_groups, "_filter", array_search('_css', array_keys($control_groups)));
                    }

                    if(in_array("classes-tab",  $settings) ){
                        $control_groups['_classes'] = [
                            'tab'      => 'style',
                            'title'    => esc_html__( 'Classes / ID', 'Bricks' ),
                        ];

                        self::repositionArrayElement($control_groups, "_classes", array_search('_css', array_keys($control_groups)) + 1);    
                    }

                    if(in_array("notes",  $settings) ) {
                        $control_groups['notes'] = [
                            'tab'      => 'content',
                            'title'    => esc_html__( 'Notes', 'Bricks' ),
                            'fullAccess' => true,
                        ];   
                    }
                    
                    return $control_groups;
                } );
            
                add_filter( 'bricks/elements/' . $element . '/controls', function( $controls ) {
                    global $brxc_acf_fields;
                    $settings = $brxc_acf_fields['custom_default_settings'];
                
                    if(in_array("background-clip",  $settings) ){
                        $controls['_backgroundClip'] = [
                            'tab'      => 'style',
                            'group'    => '_background',
                            'label'    => esc_html__( 'Background clip' ),
                            'type'     => 'select',
                            'options'  => [
                                'border-box' => esc_html__( 'border-box', 'bricks' ),
                                'content-box' => esc_html__( 'content-box', 'bricks' ),
                                'padding-box' => esc_html__( 'padding-box', 'bricks' ),
                                'text' => esc_html__( 'text', 'bricks' ),
                            ],
                            'css'      => [
                                [
                                    'property' => '-webkit-background-clip',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        self::repositionArrayElement($controls, "_backgroundClip", array_search('_background', array_keys($controls)) + 1);
                    }

                    if(in_array("white-space",  $settings) ){
                        $controls['_whiteSpace'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'White space' ),
                            'type'     => 'select',
                            'options'  => [
                                'normal' => esc_html__( 'normal', 'bricks' ),
                                'nowrap' => esc_html__( 'nowrap', 'bricks' ),
                                'pre' => esc_html__( 'pre', 'bricks' ),
                                'pre-line' => esc_html__( 'pre-line', 'bricks' ),
                                'pre-wrap' => esc_html__( 'pre-wrap', 'bricks' ),
                            ],
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'white-space',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        self::repositionArrayElement($controls, "_whiteSpace", array_search('_overflow', array_keys($controls)) + 1);
                    }

                    if(in_array("content-visibility",  $settings) ){
                        $controls['_contentVisibility'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'Content visibility' ),
                            'type'     => 'select',
                            'options'  => [
                                'auto' => esc_html__( 'auto', 'bricks' ),
                                'hidden' => esc_html__( 'hidden', 'bricks' ),
                                'visible' => esc_html__( 'visible', 'bricks' ),
                            ],
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'content-visibility',
                                    'selector' => '',
                                ],
                            ],
                        ];
                        

                        $controls['_containIntrinsicSize'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'Contain intrinsic size' ),
                            'type'     => 'number',
                            'units'    => true,
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'contain-intrinsic-size',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        self::repositionArrayElement($controls, "_contentVisibility", array_search('_overflow', array_keys($controls)));
                        self::repositionArrayElement($controls, "_containIntrinsicSize", array_search('_contentVisibility', array_keys($controls)) + 1);
                    }

                    if(in_array("overflow-dropdown",  $settings) ){
                        $controls['_overflow']['type'] = 'select';
                        $controls['_overflow']['options']  = [
                                'auto' => esc_html__( 'auto', 'bricks' ),
                                'clip' => esc_html__( 'clip', 'bricks' ),
                                'hidden' => esc_html__( 'hidden', 'bricks' ),
                                'overlay' => esc_html__( 'overlay', 'bricks' ),
                                'revert' => esc_html__( 'revert', 'bricks' ),
                                'scroll' => esc_html__( 'scroll', 'bricks' ),
                                'visible' => esc_html__( 'visible', 'bricks' ),
                        ];
                    }



                    if(in_array("break",  $settings) ){
                        $css_values = [
                            'always' => esc_html__( 'always', 'bricks' ),
                            'auto' => esc_html__( 'auto', 'bricks' ),
                            'avoid' => esc_html__( 'avoid', 'bricks' ),
                            'avoid-column' => esc_html__( 'avoid-column', 'bricks' ),
                            'avoid-page' => esc_html__( 'avoid-page', 'bricks' ),
                            'avoid-region' => esc_html__( 'avoid-region', 'bricks' ),
                            'column' => esc_html__( 'column', 'bricks' ),
                            'left' => esc_html__( 'left', 'bricks' ),
                            'page' => esc_html__( 'page', 'bricks' ),
                            'recto' => esc_html__( 'recto', 'bricks' ),
                            'region' => esc_html__( 'region', 'bricks' ),
                            'right' => esc_html__( 'right', 'bricks' ),
                            'verso' => esc_html__( 'verso', 'bricks' ),
                        ];

                        $controls['_breakBefore'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'Break before' ),
                            'type'     => 'select',
                            'inline'   => true,
                            'options'  => $css_values,
                            'css'      => [
                                [
                                    'property' => 'break-before',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        $controls['_breakInside'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'Break inside' ),
                            'type'     => 'select',
                            'inline'   => true,
                            'options'  => $css_values,
                            'css'      => [
                                [
                                    'property' => 'break-inside',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        $controls['_breakAfter'] = [
                            'tab'      => 'style',
                            'group'    => '_layout',
                            'label'    => esc_html__( 'Break after' ),
                            'type'     => 'select',
                            'inline'   => true,
                            'options'  => $css_values,
                            'css'      => [
                                [
                                    'property' => 'break-after',
                                    'selector' => '',
                                ],
                            ],
                        ];


                        self::repositionArrayElement($controls, "_breakBefore", array_search('_pointerEvents', array_keys($controls)) + 1 );
                        self::repositionArrayElement($controls, "_breakInside", array_search('_breakBefore', array_keys($controls)) + 1 );
                        self::repositionArrayElement($controls, "_breakAfter", array_search('_breakInside', array_keys($controls)) + 1 );
                    }
                    if(in_array("filter-tab",  $settings) ){
                        $controls['_cssFilters']['group'] = '_filter';
                        $controls['_cssTransition']['group'] = '_filter';
                    }

                    if(in_array("classes-tab",  $settings) ){
                        $controls['_cssClasses']['group'] = '_classes';
                        $controls['_cssId']['group'] = '_classes';
                    }

                    if(in_array("transform",  $settings) ){
                        $controls['_transform']['description'] = false;
                        $controls['_transformOrigin']['description'] = false;
                        $controls['_transformStyle'] = [
                            'tab'      => 'style',
                            'group'    => '_transform',
                            'label'    => esc_html__( 'Transform style' ),
                            'type'     => 'select',
                            'options'  => [
                                'flat' => esc_html__( 'flat', 'bricks' ),
                                'preserve-3d' => esc_html__( 'preserve-3d', 'bricks' ),
                            ],
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'transform-style',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        $controls['_transformBox'] = [
                            'tab'      => 'style',
                            'group'    => '_transform',
                            'label'    => esc_html__( 'Transform box' ),
                            'type'     => 'select',
                            'options'  => [
                                'border-box' => esc_html__( 'border-box', 'bricks' ),
                                'content-box' => esc_html__( 'content-box', 'bricks' ),
                                'fill-box' => esc_html__( 'fill-box', 'bricks' ),
                                'stroke-box' => esc_html__( 'stroke-box', 'bricks' ),
                                'view-box' => esc_html__( 'view-box', 'bricks' ),
                            ],
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'transform-box',
                                    'selector' => '',
                                ],
                            ],
                        ];
                        $controls['_perspective'] = [
                            'tab'      => 'style',
                            'group'    => '_transform',
                            'label'    => esc_html__( 'Perspective' ),
                            'type'     => 'number',
                            'units'    => true,
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'perspective',
                                    'selector' => '',
                                ],
                            ],
                        ];
                        $controls['_perspectiveOrigin'] = [
                            'tab'      => 'style',
                            'group'    => '_transform',
                            'label'    => esc_html__( 'Perspective origin' ),
                            'type'     => 'text',
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'perspective-origin',
                                    'selector' => '',
                                ],
                            ],
                            'hasDynamicData' => false,
                            'placeholder'    => esc_html__( 'Center', 'bricks' ),
                        ];

                        $controls['_backfaceVisibility'] = [
                            'tab'      => 'style',
                            'group'    => '_transform',
                            'label'    => esc_html__( 'Backface visibility' ),
                            'type'     => 'select',
                            'options'  => [
                                'hidden' => esc_html__( 'hidden', 'bricks' ),
                                'visible' => esc_html__( 'visible', 'bricks' ),
                            ],
                            'inline'   => true,
                            'css'      => [
                                [
                                    'property' => 'backface-visibility',
                                    'selector' => '',
                                ],
                            ],
                        ];

                        self::repositionArrayElement($controls, "_transformStyle", array_search('_transformOrigin', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_transformBox", array_search('_transformStyle', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_perspective", array_search('_transformBox', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_perspectiveOrigin", array_search('_perspective', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_backfaceVisibility", array_search('_perspectiveOrigin', array_keys($controls)) + 1);
                    }

                    if(in_array("css-filters",  $settings) ){
                        $filter_group = in_array("filter-tab",  $settings) ? '_filter' : '_css';
                        $controls['_backdropFilter'] = [
                            'tab'      => 'style',
                            'group'    => $filter_group,
                            'label'    => esc_html__( 'Backdrop filter' ),
                            'type'     => 'text',
                            'css'      => [
                                [
                                    'property' => 'backdrop-filter',
                                    'selector' => '',
                                ],
                                [
                                    'property' => '-webkit-backdrop-filter',
                                    'selector' => '',
                                ],
                            ],
                            'hasDynamicData' => false,
                            'placeholder'    => esc_html__( 'None', 'bricks' ),
                        ];

                        self::repositionArrayElement($controls, "_backdropFilter", array_search('_cssFilters', array_keys($controls)) + 1);
                    }

                    if( in_array("notes",  $settings) ){
                        $controls['adminNotes'] = [
                            'tab'      => 'content',
                            'group'    => 'notes',
                            'label'    => esc_html__( 'Admin Notes' ),
                            'type'     => 'textarea',
                            'hasDynamicData' => false,
                            'placeholder'    => esc_html__( 'Write some Admin notes here...', 'bricks' ),
                            'fullAccess' => true,
                        ];
                    }

                    if( in_array("notes",  $settings) ){
                        $controls['editorNotes'] = [
                            'tab'      => 'content',
                            'group'    => 'notes',
                            'label'    => esc_html__( 'Editor Notes' ),
                            'type'     => 'textarea',
                            'hasDynamicData' => false,
                            'placeholder'    => esc_html__( 'Write some Editor notes here...', 'bricks' ),
                            'fullAccess' => true,
                        ];
                    }
                    
                    return $controls;
                } );
                
                
                // Target Containers only
                if( in_array("column-count",  $settings) && ($element == 'div' || $element == 'block' || $element == 'container' || $element == 'section') ){
                    add_filter( 'bricks/elements/' . $element . '/controls', function( $controls ) {
                        $controls['_columnCount'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Column count' ),
                            'type'     => 'number',
                            'units'    => false,
                            'css'      => [
                                [
                                    'property' => 'column-count',
                                    'selector' => '',
                                ],
                            ],
                            'required' => [ '_display', '=', 'block' ],
                        ];

                        $controls['_columnCountColumnGap'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Column gap', 'bricks' ),
                            'type'     => 'number',
                            'units'    => true,
                            'css'      => [
                                [
                                    'property' => 'column-gap',
                                    'selector' => '',
                                ],
                            ],
                            'required' => [ '_display', '=', 'block' ],
                        ];
                        $controls['_columnFill'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Column fill','bricks' ),
                            'type'     => 'select',
                            'inline'   => true,
                            'options'  => [
                                'auto' => 'auto',
                                'balance' => 'balance',
                                'balance-all' => 'balance-all',
                            ],
                            'css'      => [
                                [
                                    'property' => 'column-fill',
                                    'selector' => '',
                                ],
                            ],
                            'required' => [ '_display', '=', 'block' ],
                        ];
                        $controls['_columnWidth'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Column width', 'bricks' ),
                            'type'     => 'number',
                            'units'    => true,
                            'css'      => [
                                [
                                    'property' => 'column-width',
                                    'selector' => '',
                                ],
                            ],
                            'required' => [ '_display', '=', 'block' ],
                        ];

                        self::repositionArrayElement($controls, "_columnCount", array_search('_display', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_columnCountColumnGap", array_search('_columnCount', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_columnFill", array_search('_columnCountColumnGap', array_keys($controls)) + 1);
                        self::repositionArrayElement($controls, "_columnWidth", array_search('_columnFill', array_keys($controls)) + 1);

                        return $controls;
                    } );
                }
                if( AT__Helpers::is_builder_tweaks_category_activated() && ($element == 'div' || $element == 'block' || $element == 'container' || $element == 'section') ){
                    add_filter( 'bricks/elements/' . $element . '/controls', function( $controls ) {
                        $controls['classConverterSeparator'] = [
                            'type'  => 'separator',
                            'label' => esc_html__( 'Class Converter', 'bricks' ),
                            'description' => esc_html__( 'When enabled, the class converter will process this element and their children as a standalone component with specific settings (basename, delimiter, convertion settings, etc...)', 'bricks' ),
                            'fullAccess' => true,
                        ];
                        $controls['classConverterComponent'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Set element as a root component' ),
                            'type'  => 'checkbox',
                            'fullAccess' => true,
                        ];

                        return $controls;
                    });
                }
                if(in_array("hide-remove-element",  $settings)){
                    add_filter('bricks/elements/' . $element . '/controls', function($controls){
                        $controls['hideElementSeparator'] = [
                            'type'  => 'separator',
                            'label' => esc_html__( 'Hide/Remove Element', 'bricks' ),
                            'fullAccess' => true,
                        ];
                        $controls['hideElement'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Hide Element inside the builder' ),
                            'description' => esc_html__( 'Toggling this option will hide the element inside the builder, but not on the frontend', 'bricks' ),
                            'type'  => 'checkbox',
                            'fullAccess' => true,
                        ];
                        $controls['unrenderFrontend'] = [
                            'tab'      => 'content',
                            'label'    => esc_html__( 'Remove Element on the frontend' ),
                            'description' => esc_html__( 'Toggling this option will remove the element on the frontend, but will still be visible inside the builder', 'bricks' ),
                            'type'  => 'checkbox',
                            'fullAccess' => true,
                        ];

                        return $controls;
                    });
                }
            }
        }

        //Scoped Variables
        // $class_settings = $brxc_acf_fields['class_features'];
        // if(isset($class_settings) && !empty($class_settings) && is_array($class_settings) && in_array('scoped-variables', $class_settings)){
        //     foreach($elements as $element){

        //         add_filter( 'bricks/elements/' . $element['name'] . '/controls', function( $controls ) {
        //             $controls['_scopedVariables'] = [
        //                 'tab'   => 'style',
        //                 'label' => esc_html__( 'Scoped Variables', 'bricks' ),
        //                 'group' => '_css',
        //                 'type'  => 'repeater',
        //                 'fields' => [
        //                     'title' => [
        //                         'label' => esc_html__( 'Variable', 'bricks' ),
        //                         'type' => 'text',
        //                         'placeholder'   => esc_html__( '--my-var', 'bricks' ),
        //                         'hasDynamicData' => false,
        //                     ],
        //                     'cssVarValue' => [
        //                         'label' => esc_html__( 'Value', 'bricks' ),
        //                         'type' => 'text',
        //                         'hasDynamicData' => false,
        //                     ],
        //                 ],
        //                 'css' => [
        //                     [
        //                       'property' => 'background-color',
        //                     ],
        //                 ],
        //             ];

        //             self::repositionArrayElement($controls, "_scopedVariables", array_search('_cssCustom"', array_keys($controls)));
                
        //             return $controls;
        //         });
        //     }
        // }
    }

    public static function remove_elements_from_frontend(){
        global $brxc_acf_fields;
        $settings = $brxc_acf_fields['custom_default_settings'];

        if(!in_array("hide-remove-element",  $settings)) return;

        add_filter( 'bricks/element/render', function( $render, $element ) {
            if ( isset( $element->settings["unrenderFrontend"] ) && $element->settings["unrenderFrontend"] == true ) {
                $render = false;
            }
        
            return $render;
        }, 10, 2 );
    }

    public static function set_full_access_to_all_elements(){
         
        if(!class_exists('Bricks\Elements') || !AT__Helpers::is_strict_editor_view_elements_tab_activated() || !function_exists('bricks_is_builder') || !bricks_is_builder()){
            return;
        }

        global $brxc_acf_fields;

        if(!isset($brxc_acf_fields['strict_editor_view_tweaks']) || !is_array($brxc_acf_fields['strict_editor_view_tweaks']) || !in_array('disable-all-controls', $brxc_acf_fields['strict_editor_view_tweaks'])){
            return;
        }
    
        $elements = \Bricks\Elements::$elements;

        if( !isset($elements) || !is_array($elements) ){
            return;
        }

        foreach($elements as $element){
            $element = $element['name'];
        
            add_filter( 'bricks/elements/' . $element . '/controls', function( $controls ) {
                foreach($controls as $property => $value){
                    if((!isset($value['tab']) || $value['tab'] !== "style") && isset($value['type']) && $value['type'] !== 'separator' ){
                        $controls[$property]['fullAccess'] = true;
                    }
                }
                return $controls;
            });
            
        }
    }

    public static function set_full_access_settings(){

        if(!function_exists('bricks_is_builder') || !bricks_is_builder()){
            return;
        }
 
        $settings = get_option('bricks_advanced_themer_builder_settings');

        if(!isset($settings) || !is_array($settings) || !isset($settings['full_access']) || !is_array($settings['full_access']) ){
            return;
        }

        foreach($settings['full_access'] as $element => $arr){
            if( !isset($arr) || !is_array($arr) ){
                continue;
            }
            foreach($arr as $property => $value){

                add_filter( 'bricks/elements/' . $element . '/controls', function( $controls) use ($property, $value){
                    if( !isset($controls[$property]) ){
                        return $controls;
                    }
                    if($value === "true"){
                        $controls[$property]['fullAccess'] = true;
                    } else {
                        $controls[$property]['fullAccess'] = false;
                    }

                    return $controls;

                });
            }
        }
    }

    public static function save_grid_guide_ajax_function(){

        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }


        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
        $option = get_option('bricks_advanced_themer_builder_settings');
        $option['gridGuide'] = $_POST['grid'];
        update_option('bricks_advanced_themer_builder_settings', $option);

        wp_send_json_success($option);
    }
    public static function save_query_manager_ajax_function(){

        if (!current_user_can('manage_options') ) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }

        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
     
        $option = get_option('bricks_advanced_themer_builder_settings');
        $post = $_POST['query_manager'];
        $cats = $_POST['query_manager_cats'];

		//$data = json_decode( $data, true );

        if (isset($post) && is_array($post)) {
            foreach ($post as &$item) {
                $item['args'] = stripslashes($item['args']);
                $item['description'] = stripslashes($item['description']);
            }
            // Remove the reference to avoid potential issues
            unset($item);
        }
        $option['query_manager'] = $post;
        $option['query_manager_cats'] = $cats;
        update_option('bricks_advanced_themer_builder_settings', $option);

        // wp_send_json_success($option);
    }

    public static function get_var_query_ajax_function(){
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }


        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }

        // Verify Class exists
        if(!class_exists('Bricks\Query')){
            die( 'Invalid Class' );
        }

        $settings = $_POST['settings'];
        $element_id = $_POST['element_id'];
    
        $query_vars = \Bricks\Query::prepare_query_vars_from_settings($settings, $element_id);
        wp_send_json_success($query_vars);

    }

    public static function save_global_css_ajax_function(){

        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }


        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
        $option = get_option('bricks_global_settings');
        $custom_css = $_POST['custom_css'];
        $option['customCss'] = $custom_css;
        update_option('bricks_global_settings', $option);

        wp_send_json_success($option);
    }

    public static function get_used_global_classes_id_on_site(){
        global $wpdb;

        $uniqueCssGlobalClasses = [];

        // Define the partial meta key you want to search for.
        $partialMetaKey = '_bricks_page_';

        // Create a custom SQL query to retrieve the relevant postmeta data.
        $sql = $wpdb->prepare(
            "SELECT post_id, meta_value
            FROM {$wpdb->postmeta}
            WHERE meta_key LIKE %s",
            '%' . $partialMetaKey . '%'
        );

        // Execute the query.
        $results = $wpdb->get_results($sql);

        // Loop through the results.
        foreach ($results as $result) {
            $metaValue = maybe_unserialize($result->meta_value);

            if (is_array($metaValue)) {
                foreach ($metaValue as $item) {
                    if (isset($item['settings']['_cssGlobalClasses']) && is_array($item['settings']['_cssGlobalClasses'])) {
                        $cssGlobalClasses = $item['settings']['_cssGlobalClasses'];

                        // Add the unique strings to the $uniqueCssGlobalClasses array.
                        $uniqueCssGlobalClasses = array_merge($uniqueCssGlobalClasses, $cssGlobalClasses);
                    }
                }
            }
        }

        // Remove duplicate values and reindex the array.
        $uniqueCssGlobalClasses = array_values(array_unique($uniqueCssGlobalClasses));
        return $uniqueCssGlobalClasses;

    }

} 
