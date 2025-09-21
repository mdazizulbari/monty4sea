<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Ajax{
	public static function save_full_access_ajax_function(){

        if (!current_user_can('manage_options') ) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }

        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'openai_ajax_nonce' ) ) {
            die( 'Invalid nonce' );
        }
     
        $option = get_option('bricks_advanced_themer_builder_settings');
		$fullAccess = isset($_POST['fullAccess']) ? $_POST['fullAccess'] : false;

		if($fullAccess === false){
			wp_send_json_error('Data error');
		}

        $data = $fullAccess;
        $option['full_access'] = $data;
        update_option('bricks_advanced_themer_builder_settings', $option);

        wp_send_json_success($option);
    }

    public static function save_custom_components_ajax_function() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('You don\'t have permission to perform this action.');
        }
    
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'openai_ajax_nonce')) {
            die('Invalid nonce');
        }
    
        // Get existing options
        $option = get_option('bricks_advanced_themer_builder_settings');
        
        // Get posted data
        $customComponentsElements = isset($_POST['customComponentsElements']) ? stripslashes_deep($_POST['customComponentsElements']) : false;
        $customComponentsCategories = isset($_POST['customComponentsCategories']) ? stripslashes_deep($_POST['customComponentsCategories']) : false;
    
        // Validate data
        if ($customComponentsElements === false || $customComponentsCategories === false) {
            wp_send_json_error('Data error');
        }
    
        // Decode JSON data
        $customComponentsElements = json_decode($customComponentsElements, true);
        $customComponentsCategories = json_decode($customComponentsCategories, true);
    
        // Update options
        $option['custom_components_elements'] = $customComponentsElements;
        $option['custom_components_categories'] = $customComponentsCategories;
        
        // Save updated options
        update_option('bricks_advanced_themer_builder_settings', $option);
    
        wp_send_json_success($option);
    }
}