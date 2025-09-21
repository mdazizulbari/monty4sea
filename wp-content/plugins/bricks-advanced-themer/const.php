<?php

if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
CONST
--------------------------------------*/
/**
 * Instanciate the plugin
 *
 * @var string
 */
const BRICKS_AREAS_INST = 'true';

 /**
 * A new version string will force a refresh of CSS and JS files for all users.
 *
 * @var string
 */
const BRICKS_ADVANCED_THEMER_VERSION = '2.8.1';

/**
 * Plugin Path
 *
 * @var string
 */

define( 'BRICKS_ADVANCED_THEMER_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin URL
 *
 * @var string
 */

define( 'BRICKS_ADVANCED_THEMER_URL', plugin_dir_url( __FILE__  ) );

/**
 * Admin Slug
 *
 * @var string
 */

define(' BRICKS_ADMIN_SLUG', 'advanced-themer' );

function brxc_get_css_variables_converted(){
    $option = get_option('bricks_advanced_themer_builder_settings');
    $result = isset($option) && is_array($option) && isset($option['converted']) && is_array($option['converted']) && isset($option['converted']['global_css_variables']) &&  $option['converted']['global_css_variables'] === 1 ? true : false; 
    return $result;
}
define('BRICKS_ADVANCED_THEMER_CSS_VARIABLES_CONVERTED', brxc_get_css_variables_converted());

// EDD Plugin const

define( 'BRXC_STORE_URL', 'https://advancedthemer.com/' );
define( 'BRXC_ITEM_ID', 14 );
define( 'BRXC_ITEM_NAME',  'Advanced Themer for Bricks' );
define( 'BRXC_EDD_AUTHOR', 'Maxime Beguin' );
define( 'BRXC_EDD_PLUGINVERSION',  BRICKS_ADVANCED_THEMER_VERSION );
define( 'BRXC_PLUGIN_LICENSE_PAGE', 'at-license');