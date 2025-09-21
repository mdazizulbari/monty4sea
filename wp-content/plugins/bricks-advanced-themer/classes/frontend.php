<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Frontend{
    public static function generate_default_css_variables(){
        global $brxc_acf_fields;

        $base_font = isset($brxc_acf_fields['base_font']) ? $brxc_acf_fields['base_font'] : 10;
        $min_vw = isset($brxc_acf_fields['min_vw']) ? $brxc_acf_fields['min_vw'] : 360;
        $max_vw = isset($brxc_acf_fields['max_vw']) ? $brxc_acf_fields['max_vw'] : 1600;
        $clamp_unit = isset($brxc_acf_fields['clamp_unit']) && $brxc_acf_fields['clamp_unit'] === "vw" ? "1vw" : "1cqi";
        return ":root{--min-viewport:$min_vw;--max-viewport:$max_vw;--base-font:$base_font;--clamp-unit:$clamp_unit;}";
    }

    public static function generate_css_for_frontend(){

        global $brxc_acf_fields;

        global $brxc_custom_inline_styles;

        if(!isset($brxc_acf_fields['theme_settings_tabs']) || empty($brxc_acf_fields['theme_settings_tabs']) || !is_array($brxc_acf_fields['theme_settings_tabs'])) {

            return;

        }

        $custom_css = ':root,.brxc-light-colors{';

        if ($brxc_acf_fields['color_cpt_deprecated'] && AT__Helpers::is_global_colors_category_activated() ){
            

            $global_colors = AT__Global_Colors::load_converted_colors_variables_on_frontend();

            if($global_colors && is_array($global_colors) && !empty($global_colors)){

                $custom_css .= $global_colors[0];

            }


        }

        $custom_css .= '}';

        // Scoped variables in classes

        // if(AT__Helpers::is_builder_tweaks_category_activated() && isset($brxc_acf_fields['class_features']) && !empty($brxc_acf_fields['class_features']) && is_array($brxc_acf_fields['class_features']) && in_array('scoped-variables', $brxc_acf_fields['class_features'])){

        //     $custom_css .= self::load_scoped_variables_on_classes();

        // }

        if ($brxc_acf_fields['color_cpt_deprecated'] && AT__Helpers::is_global_colors_category_activated() && isset($brxc_acf_fields['enable_dark_mode_on_frontend']) && $brxc_acf_fields['enable_dark_mode_on_frontend']){

            $global_colors = AT__Global_Colors::load_converted_colors_variables_on_frontend();

            if($global_colors && is_array($global_colors) && !empty($global_colors)){

                $custom_css .= 'html[data-theme="dark"],.brxc-dark-colors{';

                $custom_css .= $global_colors[1];

                $custom_css .= '}';
            
            }

        }

        return $custom_css;

    }

    public static function generate_theme_variables(){

        // Theme Style settings
        $settings = \Bricks\Theme_Styles::$active_settings;
        if(!isset($settings) || !is_array($settings) || !isset($settings['general']) || !is_array($settings['general']) || !isset($settings['general']['_cssVariables']) || !is_array($settings['general']['_cssVariables'])) return '';

        $variables = $settings['general']['_cssVariables'];
        $custom_css = '';

        // Loop through each variable and add the declaration to css;
        foreach($variables as $variable ){
            $name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower(trim($variable['name'])));
            $value = $variable['value'];

            if(!$name || !$value) continue;

            $custom_css .= '--' . $name . ':' . $value . ';';
        }  

        return $custom_css;
    }

    public static function load_variables_on_frontend() {

        global $brxc_acf_fields;

        // Skip if post has gutenberg blocks
        if(AT__Helpers::has_any_block()){
            return;
        }

        // Default Variables
        $custom_css = self::generate_default_css_variables();

        if( AT__Helpers::is_grids_tab_activated() ) {
            
            $custom_css .= AT__Grid_Builder::grid_builder_classes();
        
        }

        // Don't enqueue inside the builder for Full Access only
        if ((bricks_is_builder() || bricks_is_builder_iframe()) && (class_exists('Bricks\Capabilities') && \Bricks\Capabilities::current_user_has_full_access() === true)){

            wp_add_inline_style( 'bricks-advanced-themer', wp_strip_all_tags(trim($custom_css) ) );

            return;
            
        }

        $custom_css .= self::generate_css_for_frontend();

        if($custom_css !== ''){

            wp_add_inline_style( 'bricks-advanced-themer', wp_strip_all_tags(trim($custom_css) ) );

        }

    }

    public static function enqueue_gutenberg_colors_in_iframe(){
        global $brxc_acf_fields;
        
        if ( !AT__Helpers::has_any_block() ){

            return;

        }

        wp_enqueue_style('bricks-advanced-themer');

        // Default Variables
        $custom_css = self::generate_default_css_variables();

        if( AT__Helpers::is_grids_tab_activated() ) {
            
            $custom_css .= AT__Grid_Builder::grid_builder_classes();
        
        }

        $custom_css .= self::generate_css_for_frontend();

        if ( AT__Helpers::is_global_colors_category_activated() === true && isset( $brxc_acf_fields['replace_gutenberg_palettes'] ) && $brxc_acf_fields['replace_gutenberg_palettes'] ){

            $custom_css .= self::bricks_colors_gutenberg();

        }

        if($custom_css !== ''){

            wp_add_inline_style( 'bricks-advanced-themer', wp_strip_all_tags(trim($custom_css) ) );

        }
    }

    public static function load_scoped_variables_on_id (){
        
        global $brxc_acf_fields;

        // Inline Styles on ID Level
        add_filter( 'bricks/element/render_attributes', function( $attributes, $key, $element) {
    
            $final = '';
            $el = $element->element ;
        
            if(!isset($el['settings']['_scopedVariables']) || empty($el['settings']['_scopedVariables']) || !is_array($el['settings']['_scopedVariables'])) return $attributes;
        
            $repeater = $el['settings']['_scopedVariables'];
        
            foreach($repeater as $item){
                if (isset($item['title']) && !empty($item['title']) && isset($item['cssVarValue']) && !empty($item['cssVarValue'])){
                    $final .= $item['title'] . ':' . $item['cssVarValue'] . ';';
                }
            }

            // If no styles, return early
            if($final === ''){

                return $attributes;
            
            }

            // Has scoped variables

            if (isset($attributes[$key]['style']) && !empty($attributes[$key]['style'])){
        
                $attributes[$key]['style'] .= $final;
        
            } else {
        
                $attributes[$key]['style'] = $final;
        
            }
        
            return $attributes;
    
        }, 10, 3 );
    
    }
    public static function generate_array_scoped_variables_on_classes(){
        // Get options from the global setting
        $options = get_option('bricks_global_classes');
    
        // If options are not set or not an array, return early
        if (!$options || !is_array($options)) {
            return false;
        }

        // Filter options array to remove invalid entries
        $options = array_filter($options, function($class) {
            return isset($class['id'], $class['name'], $class['settings']['_scopedVariables']) &&
                is_array($class['settings']['_scopedVariables']) &&
                !empty($class['settings']['_scopedVariables']);
        });

        // If options are not set or not an array, return early
        if (!$options || !is_array($options)) {
            return false;
        }
        
        // Initialize an empty array to store classes
        $classes_array = [];
    
        // Iterate through each option
        foreach ($options as $class) {
    
            // Initialize an array to store class details
            $item = [
                'id' => esc_attr($class['id']),
                'isClass' => true,
                'selector' => esc_attr($class['name']),
                'settings' => [],
            ];
    
            // Iterate through each scoped variable
            foreach ($class['settings']['_scopedVariables'] as $variable) {
                // Check if required keys are set and not empty
                if (!isset($variable['title'], $variable['cssVarValue']) || empty($variable['title']) || empty($variable['cssVarValue'])) {
                    continue;
                }
    
                // Add variable details to the class settings
                $item['settings'][] = [
                    'property' => esc_attr($variable['title']),
                    'value' => esc_attr($variable['cssVarValue']),
                ];
            }
    
            // Add class details to the classes array
            $classes_array[] = $item;
        }

        return $classes_array;
    }

    public static function load_scoped_variables_on_classes() {
        global $brxc_acf_fields;

        $class_settings = $brxc_acf_fields['class_features'];
        if(!isset($class_settings) || empty($class_settings) || !is_array($class_settings) || !in_array('scoped-variables', $class_settings)){
            return '';
        }
        
        $classes_array = self::generate_array_scoped_variables_on_classes();
        $custom_css = '';

        // Return early
        if(!isset($classes_array) || !$classes_array || !is_array($classes_array) || empty($classes_array)){
            return $custom_css;
        }
    
        // Iterate through each class to generate custom CSS
        foreach ($classes_array as $class) {
            // Check if selector is set and not empty
            if (!isset($class['selector']) || empty($class['selector'])) {
                continue;
            }
    
            // Add selector to custom CSS string
            $custom_css .= '.' . esc_attr($class['selector']) . '{';
    
            // Iterate through class settings to add CSS properties
            foreach ($class['settings'] as $attributes) {
                // Check if property and value are set and not empty
                if (!isset($attributes['property'], $attributes['value']) || empty($attributes['property']) || empty($attributes['value'])) {
                    continue;
                }
    
                // Add property and value to custom CSS string
                $custom_css .= esc_attr($attributes['property']) . ':' . esc_attr($attributes['value']) . ';';
            }
    
            // Close CSS block for the current selector
            $custom_css .= '}';
        }
    
        // Return the generated custom CSS
        return $custom_css;
    }
    

    public static function bricks_colors_gutenberg() {

        global $brxc_acf_fields;

        if ( AT__Helpers::is_global_colors_category_activated() === false || !isset( $brxc_acf_fields['replace_gutenberg_palettes'] ) || !$brxc_acf_fields['replace_gutenberg_palettes'] ){

            return;

        }
    	
        $gutenberg_colors_frontend_css = ".has-text-color{color: var(--brxc-gutenberg-color)}.has-background,.has-background-color,.has-background-dim{background-color: var(--brxc-gutenberg-bg-color)}.has-border,.has-border-color{border-color: var(--brxc-gutenberg-border-color)}";
        
    	$bricks_palettes = get_option(\BRICKS_DB_COLOR_PALETTE, []);

        if ( isset( $bricks_palettes ) && is_array( $bricks_palettes ) && !empty($bricks_palettes) ){

            foreach( $bricks_palettes as $bricks_palette ) {

                if ( isset( $bricks_palette['colors'] ) && is_array( $bricks_palette['colors'] ) && !empty($bricks_palette['colors']) ){

                    foreach( $bricks_palette['colors'] as $color ) {

                        $name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower(trim($color['name'])));
                        $final_color = '';

                        foreach(['hex', 'rgb','hsl','raw'] as $format){
                            if( isset($color[$format] )){
                                $final_color = $color[$format];
                            }
                        }

                        $gutenberg_colors_frontend_css .= '[class*="has-' . _wp_to_kebab_case($name) . '-color"]{--brxc-gutenberg-color:' . $final_color . ';}[class*="has-' . _wp_to_kebab_case($name) . '-background-color"]{--brxc-gutenberg-bg-color:' . $final_color . ';}[class*="has-' . _wp_to_kebab_case($name) . '-border-color"]{--brxc-gutenberg-border-color:' . $final_color . ';}';

                    }

                }
            }

            $gutenberg_colors_frontend_css = wp_strip_all_tags(trim($gutenberg_colors_frontend_css));

            return $gutenberg_colors_frontend_css;

        }
    
    }

    public static function remove_default_gutenberg_presets() {

        global $brxc_acf_fields;
        
        if ( !isset( $brxc_acf_fields['remove_default_gutenberg_presets'] ) || !$brxc_acf_fields['remove_default_gutenberg_presets'] ){

           return;

        }

        remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
        remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
        remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

    }

    public static function meta_theme_color_tag() {

        // Set control in the page settings
        add_filter( 'builder/settings/page/controls_data', function( $data ) {
            $data['controls']['metaThemeColorSeparator'] = [
                'group'       => 'general',
                'type'        => 'separator',
                'label'       => esc_html__( 'Theme Color', 'bricks' ),
                'description' => esc_html__( 'Add <meta name="theme-color"> to the head of this page.', 'bricks' ),
            ];
            $data['controls']['metaThemeColor'] = [
                'group'       => 'general',
                'type'        => 'color',
                'label'       => esc_html__( 'Meta Theme Color', 'bricks' ),
                'description' => esc_html__( 'The meta tag doesn\'t support CSS variables - choose one of the following format: HEX, RGBA, HSLA.', 'bricks' ),
              ];
           
            return $data;
        } );

        // Add the meta tag
        add_action('bricks_meta_tags', function(){

            global $brxc_acf_fields;

            $color = false;

            // Global Value (ACF)

            if( $brxc_acf_fields['global_meta_theme_color'] && isset($brxc_acf_fields['global_meta_theme_color']) ) {

                $color = $brxc_acf_fields['global_meta_theme_color'];

            } 

            // Page Value (Builder)

            $settings = \bricks\Database::$page_data['settings'];
            
            if( isset($settings) && isset($settings['metaThemeColor']) ) {

                if ( isset($settings['metaThemeColor']['rgb'])){

                    $color = $settings['metaThemeColor']['rgb'];
    
                } elseif ( isset($settings['metaThemeColor']['hsl'])){
    
                    $color = $settings['metaThemeColor']['hsl'];
    
                } elseif( isset($settings['metaThemeColor']['hex'])){
    
                    $color = $settings['metaThemeColor']['hex'];
    
                }

            }

            if(!$color) return;
            
            echo '<meta name="theme-color" content="' . $color . '" />';
            
            return;
        });
    
    }

}