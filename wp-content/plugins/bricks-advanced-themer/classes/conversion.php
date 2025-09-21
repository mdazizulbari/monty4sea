<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Conversion{
    private static function set_css_variables_as_converted(){
        $option = get_option('bricks_advanced_themer_builder_settings');

        if( !isset($option) || !is_array($option) ){
            $option = [];
        }

        if( !isset($option['converted']) || !is_array($option['converted']) ){
            $option['converted'] = [];
        }

        $option['converted']['global_css_variables'] = 1;

        update_option('bricks_advanced_themer_builder_settings', $option);
    }

    private static function has_entry_with_name($array, $name) {
        if(isset($array) && is_array($array)){
            foreach ($array as $entry) {
                if ($entry['name'] === $name) {
                    return $entry;
                }
            }
        }
        return false;
    }

    public static function convert_global_css_variables(){

        // Skip if already converted
        if(BRICKS_ADVANCED_THEMER_CSS_VARIABLES_CONVERTED === true) return;

        // CSS Variables are disabled inside the theme settings
        if(!AT__helpers::is_css_variables_category_activated()){
            self::set_css_variables_as_converted();
            return;
        }

        global $brxc_acf_fields;
        global $wpdb;

        $prefix = strtolower($brxc_acf_fields['global_prefix']);
        $categories = get_option('bricks_global_variables_categories');
        if( !isset($categories) || !is_array($categories) ){
            $categories = [];
        }
        $variables = get_option('bricks_global_variables');
        if( !isset($variables) || !is_array($variables) ){
            $variables = [];
        }
        $themesArray = get_option('bricks_theme_styles');
        if ( !isset($themesArray) || !is_array($themesArray) ) {
            $themesArray = [];
        }

        foreach ($themesArray as &$theme) {
            if (($theme['settings']['general']['_cssVariables'] ?? null) !== null) {
                foreach ($theme['settings']['general']['_cssVariables'] as &$variable) {
                    
                    // Add prefix
                    if (isset($variable['name']) && is_string($variable['name']) ){ 
                        $name_final = $prefix !== '' ? $prefix . '-' . $variable['name'] : $variable['name'];
                        $variable['name'] = $name_final;
                    }

                    // Convert group name into group id
                    if (isset($variable['group']) && is_string($variable['group'])) {

                        // Category
                        $entry = self::has_entry_with_name($categories, $variable['group']);

                        if($entry === false){
                            $category_id = AT__Helpers::generate_unique_string(6);
                            $categories[] = [
                                'id'    => $category_id,
                                'name'  => $variable['group']
                            ];
                        } else {
                            $category_id = $entry['id'];
                        }

                        $variable['category'] = $category_id;
                        unset($variable['group']);
                    }
                    // Remove "order" property
                    if (isset($variable['order'])) {
                        unset($variable['order']); 
                    }

                    // Convert Clamp Values
                    if(isset($variable['type']) && $variable['type'] === "clamp" && isset($variable['min']) && isset($variable['max'])){
                        $variable['value'] = AT__Helpers::clamp_builder((float) $variable['min'], (float) $variable['max']);
                    }
                }
            }
        }
        if(is_array($themesArray) && !empty($themesArray)){
            update_option( 'bricks_theme_styles', $themesArray );
        }

        // Convert Global Variables saved in ACF
        if ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
            while ( have_rows( 'field_6445ab9f3d498', 'bricks-advanced-themer' ) ) :
                the_row();

                // Typography
                if (  AT__Helpers::is_typography_tab_activated() && have_rows( 'field_63a6a58831bbe', 'bricks-advanced-themer' ) ) :

                    // Category
                    $entry = self::has_entry_with_name($categories, 'typography');

                    if($entry === false){
                        $category_id = AT__Helpers::generate_unique_string(6);
                        $categories[] = [
                            'id'    => $category_id,
                            'name'  => 'typography'
                        ];
                    } else {
                        $category_id = $entry['id'];
                    }

                    // Variables
                    while ( have_rows( 'field_63a6a58831bbe', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_typography_label', 'bricks-advanced-themer' );
                        $label_final = $prefix !== '' ? $prefix . '-' . $label : $label;
                        $min_value = get_sub_field('brxc_typography_min_value', 'bricks-advanced-themer' );
                        $max_value = get_sub_field('brxc_typography_max_value', 'bricks-advanced-themer' );
                        $variables[] = [
                            'id'        => AT__Helpers::generate_unique_string(6),
                            'name'      => $label_final,
                            'category'  => $category_id,
                            'type'      => 'clamp',
                            'min'       => $min_value,
                            'max'       => $max_value,
                            'value'     => AT__Helpers::clamp_builder((float) $min_value, (float) $max_value),
                        ];
                        
                    endwhile;
                endif;
    
                // Spacing
                if ( AT__Helpers::is_spacing_tab_activated() && have_rows( 'field_63a6a51731bbb', 'bricks-advanced-themer' ) ) :

                    // Category
                    $entry = self::has_entry_with_name($categories, 'spacing');

                    if($entry === false){
                        $category_id = AT__Helpers::generate_unique_string(6);
                        $categories[] = [
                            'id'    => $category_id,
                            'name'  => 'spacing'
                        ];
                    } else {
                        $category_id = $entry['id'];
                    }

                    // Variables
                    while ( have_rows( 'field_63a6a51731bbb', 'bricks-advanced-themer' ) ) :
                        the_row();
    
                        $label = get_sub_field('brxc_spacing_label', 'bricks-advanced-themer' );
                        $label_final = $prefix !== '' ? $prefix . '-' . $label : $label;
                        $min_value = get_sub_field('brxc_spacing_min_value', 'bricks-advanced-themer' );
                        $max_value = get_sub_field('brxc_spacing_max_value', 'bricks-advanced-themer' );
                        $variables[] = [
                            'id'        => AT__Helpers::generate_unique_string(6),
                            'name'      => $label_final,
                            'category'  => $category_id,
                            'type'      => 'clamp',
                            'min'       => $min_value,
                            'max'       => $max_value,
                            'value'     => AT__Helpers::clamp_builder((float) $min_value, (float) $max_value),
                        ];
                        
                    endwhile;
                endif;

                // Border-radius
                if ( AT__Helpers::is_border_radius_tab_activated() && have_rows( 'field_63c8f17f5e2ed', 'bricks-advanced-themer' ) ) :

                    // Category
                    $entry = self::has_entry_with_name($categories, 'border-radius');

                    if($entry === false){
                        $category_id = AT__Helpers::generate_unique_string(6);
                        $categories[] = [
                            'id'    => $category_id,
                            'name'  => 'border-radius'
                        ];
                    } else {
                        $category_id = $entry['id'];
                    }

                    // Variables
                    while ( have_rows( 'field_63c8f17f5e2ed', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_border_label', 'bricks-advanced-themer' );
                        $label_final = $prefix !== '' ? $prefix . '-' . $label : $label;
                        $min_value = get_sub_field('brxc_border_min_value', 'bricks-advanced-themer' );
                        $max_value = get_sub_field('brxc_border_max_value', 'bricks-advanced-themer' );
                        $variables[] = [
                            'id'        => AT__Helpers::generate_unique_string(6),
                            'name'      => $label_final,
                            'category'  => $category_id,
                            'type'      => 'clamp',
                            'min'       => $min_value,
                            'max'       => $max_value,
                            'value'     => AT__Helpers::clamp_builder((float) $min_value, (float) $max_value),
                        ];
                        
                    endwhile; 
                endif;

                // Border
                if ( AT__Helpers::is_border_tab_activated() && have_rows( 'field_63c8f17ytr545', 'bricks-advanced-themer' ) ) :

                    // Category
                    $entry = self::has_entry_with_name($categories, 'border');

                    if($entry === false){
                        $category_id = AT__Helpers::generate_unique_string(6);
                        $categories[] = [
                            'id'    => $category_id,
                            'name'  => 'border'
                        ];
                    } else {
                        $category_id = $entry['id'];
                    }

                    // Variablies
                    while ( have_rows( 'field_63c8f17ytr545', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_border_simple_label', 'bricks-advanced-themer' );
                        $label_final = $prefix !== '' ? $prefix . '-' . $label : $label;
                        $value = get_sub_field('brxc_border_simple_value', 'bricks-advanced-themer' );
                        $variables[] = [
                            'id'        => AT__Helpers::generate_unique_string(6),
                            'name'      => $label_final,
                            'category'  => $category_id,
                            'type'      => 'static',
                            'value'     => $value,
                        ];
                        
                    endwhile;
                endif;

                // Box-shadow
                if ( AT__Helpers::is_box_shadow_tab_activated() && have_rows( 'field_63c8f17s4stt6', 'bricks-advanced-themer' ) ) :

                    // Category
                    $entry = self::has_entry_with_name($categories, 'box-shadow');

                    if($entry === false){
                        $category_id = AT__Helpers::generate_unique_string(6);
                        $categories[] = [
                            'id'    => $category_id,
                            'name'  => 'box-shadow'
                        ];
                    } else {
                        $category_id = $entry['id'];
                    }

                    // Variables
                    while ( have_rows( 'field_63c8f17s4stt6', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_box_shadow_label', 'bricks-advanced-themer' );
                        $label_final = $prefix !== '' ? $prefix . '-' . $label : $label;
                        $value = get_sub_field('brxc_box_shadow_value', 'bricks-advanced-themer' );
                        $variables[] = [
                            'id'     => AT__Helpers::generate_unique_string(6),
                            'name'   => $label_final,
                            'category'  => $category_id,
                            'type'   => 'static',
                            'value'  => $value,
                        ];
                        
                    endwhile;
                endif;

                // Width
                if ( AT__Helpers::is_width_tab_activated() && have_rows( 'field_63c8f17ppo69i', 'bricks-advanced-themer' ) ) :

                    // Category
                    $entry = self::has_entry_with_name($categories, 'width');

                    if($entry === false){
                        $category_id = AT__Helpers::generate_unique_string(6);
                        $categories[] = [
                            'id'    => $category_id,
                            'name'  => 'width'
                        ];
                    } else {
                        $category_id = $entry['id'];
                    }

                    // Variables
                    while ( have_rows( 'field_63c8f17ppo69i', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $label = get_sub_field('brxc_width_label', 'bricks-advanced-themer' );
                        $label_final = $prefix !== '' ? $prefix . '-' . $label : $label;
                        $min_value = get_sub_field('brxc_width_min_value', 'bricks-advanced-themer' );
                        $max_value = get_sub_field('brxc_width_max_value', 'bricks-advanced-themer' );
                        $variables[] = [
                            'id'        => AT__Helpers::generate_unique_string(6),
                            'name'      => $label_final,
                            'category'  => $category_id,
                            'type'      => 'clamp',
                            'min'       => $min_value,
                            'max'       => $max_value,
                            'value'     => AT__Helpers::clamp_builder((float) $min_value, (float) $max_value),
                        ];
                        
                    endwhile;
                endif;

                // Custom Variables

                if ( AT__Helpers::is_custom_variables_tab_activated() && have_rows( 'field_64066a105f7ec', 'bricks-advanced-themer' ) ) :
                    while ( have_rows( 'field_64066a105f7ec', 'bricks-advanced-themer' ) ) :
                        the_row();

                        $group = get_sub_field('brxc_misc_category_label', 'bricks-advanced-themer');
                        // Flexible Content
                        
                        if( have_rows('field_63dd12891d1d9', 'bricks-advanced-themer') ):

                            // Category
                            $entry = self::has_entry_with_name($categories, $group);

                            if($entry === false){
                                $category_id = AT__Helpers::generate_unique_string(6);
                                $categories[] = [
                                    'id'    => $category_id,
                                    'name'  => $group
                                ];
                            } else {
                                $category_id = $entry['id'];
                            }

                            // Variables
                            while ( have_rows('field_63dd12891d1d9', 'bricks-advanced-themer') ) : the_row();
    
                                // Case: Fluid
                                if( get_row_layout() == 'brxc_misc_fluid_variable' ):
                                    $label = get_sub_field('brxc_misc_fluid_label', 'bricks-advanced-themer' );
                                    $label_final = $prefix !== '' ? $prefix . '-' . $label : $label;
                                    $min_value = get_sub_field('brxc_misc_fluid_min_value', 'bricks-advanced-themer' );
                                    $max_value = get_sub_field('brxc_misc_fluid_max_value', 'bricks-advanced-themer' );
                                    $variables[] = [
                                        'id'        => AT__Helpers::generate_unique_string(6),
                                        'name'      => $label_final,
                                        'category'  => $category_id,
                                        'type'      => 'clamp',
                                        'min'       => $min_value,
                                        'max'       => $max_value,
                                        'value'     => AT__Helpers::clamp_builder((float) $min_value, (float) $max_value),
                                    ];
                        
                                // Case: Static
                                elseif( get_row_layout() == 'brxc_misc_static_variable' ): 
                                    $label = get_sub_field('brxc_misc_static_label', 'bricks-advanced-themer' );
                                    $label_final = $prefix !== '' ? $prefix . '-' . $label : $label;
                                    $value = get_sub_field('brxc_misc_static_value', 'bricks-advanced-themer' );
                                    $variables[] = [
                                        'id'        => AT__Helpers::generate_unique_string(6),
                                        'name'      => $label_final,
                                        'category'  => $category_id,
                                        'type'      => 'static',
                                        'value'     => $value,
                                    ];
                        
                                endif;
                                
                            // End Flexible Content
                            endwhile;
                        endif;
                    // End Repeater
                    endwhile;
                endif;

            // End Global repeater
            endwhile;
        endif;

        // Reset database entries
        $option_data = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE '%bricks-advanced-themer__brxc_%' AND option_name LIKE '%_variables_repeater%'");

        // Delete options
        if(is_array($option_data)){
            foreach ($option_data as $option) {
                delete_option($option->option_name);
            }
        }

        // Update globalVariablesCategories
        if(is_array($categories) && !empty($categories)){
            update_option( 'bricks_global_variables_categories', $categories );
        } 

        // Update globalVariables Array
        if(is_array($variables) && !empty($variables)){
            update_option( 'bricks_global_variables', $variables );  
        } 


        // Update database: CONVERTED
        self::set_css_variables_as_converted();
    }
}
