<?php

if (!defined('ABSPATH')) { die();
}

ob_start();
// Global Features
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/class-contextual-menu.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/class_manager.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/query_manager.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/css_variable_manager.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/advanced_css_new.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/color_manager.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/structure_helper.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/find_replace.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/openai_text_new.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/global_openai_text_new.php';
// Elements
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/extend.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/style_overview.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/class_converter.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/plain_classes_new.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/box_shadow_generator.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/grid_ui.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/css_variable_pickr_new.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/background_focus.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/mask_helper.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/dynamic_data_modal.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/custom_components.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/custom_components_add.php';
// Extras
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/resources_new.php';
include_once \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/brickslabs.php';
$output = ob_get_clean();
echo $output;
