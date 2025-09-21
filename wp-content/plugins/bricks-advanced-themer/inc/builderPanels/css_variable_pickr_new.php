<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcVariableOverlay';
$prefix_id = 'brxcVariable';
$prefix_class = 'brxc-css-variables';
// Heading
$modal_heading_title = 'CSS Variables';

if (!AT__Helpers::is_builder_tweaks_category_activated()){
    $theme_settings = \get_admin_url() . 'admin.php?page=bricks-advanced-themer';
    $error_title = "Feature not enabled";
    $error_desc = "It seems like this feature hasn't been enabled inside the theme settings. Click on the botton below and make sure that the <strong class='accent'>Builder Tweaks</strong> settings are enabled inside <strong class='accent'>Global Settings > General > Customize the functions included in Advanced Themer</strong>.";
    include \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/_default_error.php';

} else {
    // Panels
    $num_panels = 1;
    $custom_framework = false; 
    $core_active = false;
    $custom_framework = false; 
    
    if (!function_exists('is_plugin_active')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }

    if ( is_plugin_active('core-framework/core-framework.php') ) {
        $option = get_option('core_framework_main', array());
        $license = get_option('core_framework_bricks_license_key', false);
        $active_filter = apply_filters( 'at/variable_picker/enable_core_framework', false);
        $enable = isset($option['bricks']) && $option['bricks'] && $license && $active_filter;
        if($enable){
            $core_active = true;
            $num_panels++;
        }
    }

    if( AT__Helpers::is_import_framework_tab_activated() && have_rows('field_6445ab9f3d498', 'bricks-advanced-themer' ) ):
        while( have_rows('field_6445ab9f3d498', 'bricks-advanced-themer' ) ) : the_row();
            $custom_format = get_sub_field('field_6399a28440091', 'bricks-advanced-themer' );
            $json_from_db_label = get_sub_field('field_63bdedscc0k3l', 'bricks-advanced-themer' );
            $json_from_db = get_sub_field('field_64065d4ffp9c6', 'bricks-advanced-themer' );
            if( ($custom_format === "database" && isset($json_from_db) && !empty($json_from_db) && isset($json_from_db_label) && !empty($json_from_db_label) ) || ($custom_format === "json" && have_rows('field_63b4600putac1', 'bricks-advanced-themer' ) ) ):
                $num_panels++;
                $custom_framework = true;
            endif;
        endwhile;
    endif;
    $j = 0; 


    ?>
    <!-- Main -->
    <div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper sidebar left" style="opacity:0" data-input-target="" onmousedown="ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');" >
        <!-- Main Inner -->
        <div class="brxc-overlay__inner brxc-medium" style="max-height: 840px;">
            <!-- Close Modal Button -->
            <div class="brxc-overlay__close-btn" onClick="ADMINBRXC.closeModal(event, event.target, '#<?php echo esc_attr($overlay_id);?>')">
                <i class="bricks-svg ti-close"></i>
            </div>
            <!-- Modal Wrapper -->
            <div class="brxc-overlay__inner-wrapper">
                <!-- Modal Header -->
                <div class="brxc-overlay__header">
                    <!-- Modal Header Title-->
                    <h3 class="brxc-overlay__header-title"><?php echo esc_attr($modal_heading_title);?></h3>
                    <div class="brxc-overlay__resize-icons">
                        <i class="fa-solid fa-window-maximize" onclick="ADMINBRXC.maximizeModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                        <i class="ti-layout-sidebar-left active" onclick="ADMINBRXC.leftSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                        <i class="ti-layout-sidebar-right" onclick="ADMINBRXC.rightSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                    </div>
                </div>
                <!-- Modal Error Container for OpenAI -->
                <div class="brxc-overlay__error-message-wrapper"></div>
                <!-- Modal Container -->
                <div class="brxc-overlay__container">
                    <!-- Modal Panel Switch -->
                    <?php if($num_panels > 1):?>
                    <div class="brxc-overlay__panel-switcher-wrapper">
                        <!-- Label/Input Switchers -->

                        <input type="radio" id="<?php echo esc_attr($prefix);?>-AT" name="<?php echo esc_attr($prefix);?>-switch" class="brxc-input__radio" data-transform="calc(<?php echo esc_attr($j)?> * (-100% - 80px))" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);" checked>
                        <label for="<?php echo esc_attr($prefix);?>-AT" class="brxc-input__label">Bricks</label>
                        <?php $j++; ?>
                        <?php if($core_active === true){?>
                            <input type="radio" id="<?php echo esc_attr($prefix);?>-CF" name="<?php echo esc_attr($prefix);?>-switch" class="brxc-input__radio" data-transform="calc(<?php echo esc_attr($j)?> * (-100% - 80px))" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
                            <label for="<?php echo esc_attr($prefix);?>-CF" class="brxc-input__label">CF</label>
                        <?php $j++;} ?>
                        <?php if($custom_framework === true){?>
                            <?php //Group
                                if( have_rows('field_6445ab9f3d498', 'bricks-advanced-themer' ) ):
                                    while( have_rows('field_6445ab9f3d498', 'bricks-advanced-themer' ) ) : 
                                        the_row();

                                        // Database

                                        if( $custom_format === "database" && isset($json_from_db) && !empty($json_from_db) && isset($json_from_db_label) && !empty($json_from_db_label) ):
                                            $label = $json_from_db_label;?>
                                            <input type="radio" id="<?php echo esc_attr($prefix);?>-<?php echo esc_attr($label);?>" name="<?php echo esc_attr($prefix);?>-switch" class="brxc-input__radio" data-transform="calc(<?php echo esc_attr($j)?> * (-100% - 80px))" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
                                            <label for="<?php echo esc_attr($prefix);?>-<?php echo esc_attr($label);?>" class="brxc-input__label"><?php echo esc_attr($label);?></label>
                                            <?php $j++;

                                        // Repeater

                                        elseif( $custom_format === "json" && have_rows('field_63b4600putac1', 'bricks-advanced-themer' ) ):
                                            while( have_rows('field_63b4600putac1', 'bricks-advanced-themer' ) ) : the_row();
                                                $label = get_sub_field('field_63bdeds216ac3', 'bricks-advanced-themer' );?>
                                                <input type="radio" id="<?php echo esc_attr($prefix);?>-<?php echo esc_attr($label);?>" name="<?php echo esc_attr($prefix);?>-switch" class="brxc-input__radio" data-transform="calc(<?php echo esc_attr($j)?> * (-100% - 80px))" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
                                                <label for="<?php echo esc_attr($prefix);?>-<?php echo esc_attr($label);?>" class="brxc-input__label"><?php echo esc_attr($label);?></label>
                                                <?php $j++;
                                            endwhile;
                                        endif;
                                    endwhile;
                                endif;
                            ?>
                        <?php } ?>
                        <!-- End of Label/Input Switchers -->
                    </div>
                    <?php endif;?>
                    <!-- End of Panel Switch -->
                    <!-- Modal Panels Wrapper -->
                    <div class="brxc-overlay__pannels-wrapper">
                        <!-- Modal Panel -->
                        <div class="brxc-overlay__pannel brxc-overlay__pannel-1">
                            <div class="isotope-wrapper" data-gutter="10" data-filter-layout="fitRows">
                                <!-- Panel Content -->
                                <div class="brxc-overlay__search-box">
                                    <input type="search" class="iso-search" name="typography-search" placeholder="Type here to filter the CSS variables" data-type="textContent" oninput="ADMINBRXC.variablePickerStates.search = this.value;ADMINBRXC.refreshVariablePickerList();">
                                    <div class="iso-search-icon">
                                        <i class="bricks-svg ti-search"></i>
                                    </div>
                                    <div class="iso-reset" data-balloon="Reset" data-balloon-pos="left" onclick="ADMINBRXC.variablePickerStates.search = '';ADMINBRXC.refreshVariablePickerList();this.previousElementSibling.previousElementSibling.value = '';">
                                        <i class="bricks-svg fas fa-undo"></i>
                                    </div>
                                </div>
                                <div id="brxcVariablePickrAT"></div>
                        </div>
                            <!-- End of Panel Content -->
                        </div>
                        <!-- End of Modal Panel -->                   
                        <!-- Modal Panel -->
                        <?php if($core_active === true):?>
                        <!-- Modal Panel -->
                        <div class="brxc-overlay__pannel brxc-overlay__pannel-2 isotope-wrapper" data-gutter="10" data-filter-layout="fitRows">
                            <!-- Panel Content -->
                            <div class="brxc-overlay__search-box">
                                <input type="search" class="iso-search" name="typography-search" placeholder="Type here to filter the CSS variables" data-type="textContent">
                                <div class="iso-search-icon">
                                    <i class="bricks-svg ti-search"></i>
                                </div>
                                <div class="iso-reset">
                                    <i class="bricks-svg ti-close"></i>
                                </div>
                            </div>
                            <div class="brxc-overlay__pannel--content">
                                <?php
                                // Retrieve the row from the database
                                global $wpdb;
                                $selected_preset_id = \get_option("core_framework_main", [])["selected_id"];
                                $table_name = $wpdb->prefix . 'core_framework_presets';
                                $row = $wpdb->get_row("SELECT * FROM $table_name WHERE id = '$selected_preset_id'");

                                // Check if the row exists
                                if ( ! empty( $row ) ) {
                                    // Convert the JSON data to an array
                                    $core_settings = json_decode( $row->data, true );
                                    if(isset($core_settings) && !empty($core_settings) && is_array($core_settings) ){
                                        $typography_obj = $core_settings["modulesData"]["FLUID_TYPOGRAPHY"];
                                        $spacing_obj = $core_settings["modulesData"]["FLUID_SPACING"];
                                        $colors_arr = $core_settings["modulesData"]["COLOR_SYSTEM"]["groups"];
                                        $stylesheet = $core_settings["styleSheetData"];
                                        $prefix = (isset($core_settings["variablePrefix"])) ? $core_settings["variablePrefix"] : '';
                                        
                                        // Typography
                                        if(isset($typography_obj) && !empty($typography_obj) && isset($typography_obj['namingConvention']) && !empty($typography_obj['namingConvention'])){?>
                                            <div class="brxc-variable-picker__category">
                                                <label class="brxc-input__label">Typography</label>
                                                <div class="brxc-overlay__action-btn-wrapper isotope-container">
                                                <?php
                                                // Fluid
                                                if(isset($typography_obj['mode']) && $typography_obj['mode'] === "fluid" && isset($typography_obj['steps']) && !empty($typography_obj['steps'])){
                                                    $steps = preg_split ("/\,/", $typography_obj['steps']);
                                                    if(isset($steps) && !empty($steps) && is_array($steps)){
                                                        foreach ($steps as $step) {?>
                                                            <div class="brxc-overlay__action-btn isotope-selector" data-variable="var(--<?php echo esc_attr($prefix) . esc_attr($typography_obj['namingConvention']) ?>-<?php echo esc_attr($step)?>)"><?php echo esc_attr($typography_obj['namingConvention']) ?>-<?php echo esc_attr($step)?></div>
                                                        <?php }
                                                    } 
                                                // Manual
                                                } else if(isset($typography_obj['mode']) && $typography_obj['mode'] === "fluid_manual" && isset($typography_obj['manualSizes']) && !empty($typography_obj['manualSizes']) && is_array($typography_obj['manualSizes'])){
                                                    foreach ($typography_obj['manualSizes'] as $size) {?>
                                                        <div class="brxc-overlay__action-btn isotope-selector" data-variable="var(--<?php echo esc_attr($prefix) . esc_attr($size['name'])?>)" data-balloon="<?php echo esc_attr($size['min']);?> to <?php echo esc_attr($size['max']);?> (px)" data-balloon-pos="top"><?php echo esc_attr($size['name'])?></div>
                                                    <?php }
                                                }
                                                ?>
                                                </div>
                                            </div>
                                        <?php }

                                        // Spacing
                                        if(isset($spacing_obj) && !empty($spacing_obj) && isset($spacing_obj['namingConvention']) && !empty($spacing_obj['namingConvention'])){?>
                                            <div class="brxc-variable-picker__category">
                                                <label class="brxc-input__label">Spacing</label>
                                                <div class="brxc-overlay__action-btn-wrapper isotope-container">
                                                <?php
                                                // Fluid
                                                if(isset($spacing_obj['mode']) && $spacing_obj['mode'] === "fluid" && isset($spacing_obj['steps']) && !empty($spacing_obj['steps'])){
                                                    $steps = preg_split ("/\,/", $spacing_obj['steps']);
                                                    if(isset($steps) && !empty($steps) && is_array($steps)){
                                                        foreach ($steps as $step) {?>
                                                            <div class="brxc-overlay__action-btn isotope-selector" data-variable="var(--<?php echo esc_attr($prefix) . esc_attr($spacing_obj['namingConvention']) ?>-<?php echo esc_attr($step)?>)"><?php echo esc_attr($spacing_obj['namingConvention']) ?>-<?php echo esc_attr($step)?></div>
                                                        <?php }
                                                    }
                                                // Manual
                                                } else if(isset($spacing_obj['mode']) && $spacing_obj['mode'] === "fluid_manual" && isset($spacing_obj['manualSizes']) && !empty($spacing_obj['manualSizes']) && is_array($spacing_obj['manualSizes'])){
                                                    foreach ($spacing_obj['manualSizes'] as $size) {?>
                                                        <div class="brxc-overlay__action-btn isotope-selector" data-variable="var(--<?php echo esc_attr($prefix) . esc_attr($size['name'])?>)" data-balloon="<?php echo esc_attr($size['min']);?> to <?php echo esc_attr($size['max']);?> (px)" data-balloon-pos="top"><?php echo esc_attr($size['name'])?></div>
                                                    <?php }
                                                }
                                                ?>
                                                </div>
                                            </div>
                                        <?php }

                                        // Colors
                                        if(isset($colors_arr) && !empty($colors_arr) && is_array($colors_arr) ){
                                            foreach ($colors_arr as $item) {?>
                                                <div class="brxc-variable-picker__category">
                                                    <label class="brxc-input__label">Color: <?php echo esc_attr($item["name"])?></label>
                                                    <div class="brxc-overlay__action-btn-wrapper isotope-container">
                                                    <?php
                                                    // Loop through the items and output them
                                                    if(isset($item["colors"]) && !empty($item["colors"]) && is_array($item["colors"]) ){
                                                        foreach ($item["colors"] as $style) {?>
                                                            <div class="brxc-overlay__action-btn isotope-selector" data-variable="var(--<?php echo esc_attr($prefix) . esc_attr($style["name"]) ?>)" data-balloon="<?php echo esc_attr($style["value"]);?>" data-balloon-pos="top"><div class="brxc-color-preview" style="background:<?php echo esc_attr($style["value"])?>"></div><?php echo esc_attr($style["name"]) ?></div>
                                                            <?php
                                                            if(isset($style["transparent"]) && $style["transparent"] === true && isset($style["transparentVariables"]) && !empty($style["transparentVariables"]) && is_array($style["transparentVariables"])){
                                                                foreach($style["transparentVariables"] as $trans){
                                                                    if(isset($style["name"]) && isset($trans)){
                                                                        $color = str_replace('hsl', 'hsla', substr($style["value"], 0, -1) . ', ' . ($trans / 100) . ')');?>
                                                                        <div class="brxc-overlay__action-btn isotope-selector" data-variable="var(--<?php echo esc_attr($prefix) . esc_attr($style["name"]) ?>-<?php echo esc_attr($trans)?>)" data-balloon="<?php echo esc_attr($color);?>" data-balloon-pos="top"><div class="brxc-color-preview" style="background:<?php echo esc_attr($color)?>"></div><?php echo esc_attr($style["name"]) ?>-<?php echo esc_attr($trans)?></div>
                                                                    <?php }
                                                                }
                                                            }

                                                            if(isset($style["isShades"]) && $style["isShades"] === true && isset($style["shades"]) && !empty($style["shades"]) && is_array($style["shades"])){
                                                                foreach($style["shades"] as $shade){
                                                                    if(isset($shade["name"])){?>
                                                                        <div class="brxc-overlay__action-btn isotope-selector" data-variable="var(--<?php echo esc_attr($prefix) . esc_attr($shade["name"]) ?>)" data-balloon="<?php echo esc_attr($shade["value"]);?>" data-balloon-pos="top"><div class="brxc-color-preview" style="background:<?php echo esc_attr($shade["value"])?>"></div><?php echo esc_attr($shade["name"]) ?></div>
                                                                    <?php }
                                                                }
                                                            }

                                                            if(isset($style["isTints"]) && $style["isTints"] === true && isset($style["tints"]) && !empty($style["tints"]) && is_array($style["tints"])){
                                                                foreach($style["tints"] as $tint){
                                                                    if(isset($tint["name"])){?>
                                                                        <div class="brxc-overlay__action-btn isotope-selector" data-variable="var(--<?php echo esc_attr($prefix) . esc_attr($tint["name"]) ?>)" data-balloon="<?php echo esc_attr($tint["value"]);?>" data-balloon-pos="top"><div class="brxc-color-preview" style="background:<?php echo esc_attr($tint["value"])?>"></div><?php echo esc_attr($tint["name"]) ?></div>
                                                                    <?php }
                                                                }
                                                            }
                                                        }
                                                    }?>
                                                    </div>
                                                </div>
                                            <?php }
                                        }

                                        // Custom Settings
                                        if(isset($stylesheet) && !empty($stylesheet) && is_array($stylesheet) ){
                                            foreach($stylesheet as $tab){
                                                if(isset($tab) && !empty($tab) && is_array($tab) ){
                                                    foreach ($tab as $item) {
                                                        if(isset($item["cssObjects"][0]["selector"]) && $item["cssObjects"][0]["selector"] === ":root" && isset($item["name"])){?>
                                                            <div class="brxc-variable-picker__category">
                                                                <label class="brxc-input__label"><?php echo esc_attr($item["name"]) . ' <span class="brxc__light">(Custom)</span>'?></label>
                                                                <div class="brxc-overlay__action-btn-wrapper isotope-container">
                                                                <?php
                                                                // Loop through the items and output them
                                                                if(isset($item["cssObjects"][0]["declarations"]) && !empty($item["cssObjects"][0]["declarations"]) && is_array($item["cssObjects"][0]["declarations"]) ){
                                                                    foreach ($item["cssObjects"][0]["declarations"] as $style) {
                                                                        if(isset($style["property"]) && isset($style["value"]) && isset($style["type"]) && $style["type"] === "color"){?>
                                                                            <div class="brxc-overlay__action-btn isotope-selector" data-variable="var(<?php echo esc_attr($style["property"]) ?>)" data-balloon="<?php echo esc_attr($style["colorValue"]);?>" data-balloon-pos="top"><div class="brxc-color-preview" style="background:<?php echo esc_attr($style["colorValue"])?>"></div><?php echo esc_attr(substr($style["property"], 2)) ?></div>
                                                                        <?php } else if(isset($style["property"]) && isset($style["value"]) && isset($style["type"]) && $style["type"] === "fluid"){?>
                                                                            <div class="brxc-overlay__action-btn isotope-selector" data-variable="var(<?php echo esc_attr($style["property"]) ?>)" data-balloon="<?php echo esc_attr($style['fluidValue'][0]);?> to <?php echo esc_attr($style['fluidValue'][1]);?> (<?php echo esc_attr($style['fluidValue'][2]);?>)" data-balloon-pos="top"><?php echo esc_attr(substr($style["property"], 2)) ?></div>
                                                                        <?php } else if (isset($style["property"]) && isset($style["value"])){?>
                                                                            <div class="brxc-overlay__action-btn isotope-selector" data-variable="var(<?php echo esc_attr($style["property"]) ?>)" data-balloon="<?php echo esc_attr($style["value"]);?>" data-balloon-pos="top"><?php echo esc_attr(substr($style["property"], 2)) ?></div>
                                                                        <?php }
                                                                    }
                                                                }?>
                                                                </div>
                                                            </div>
                                                        <?php }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }?>
                            </div>
                            <!-- End of Panel Content -->
                            </div>
                        <?php endif;?>
                        <!-- Modal Panel -->
                        <?php
                        if($custom_framework === true){
                            if( have_rows('field_6445ab9f3d498', 'bricks-advanced-themer' ) ):
                                while( have_rows('field_6445ab9f3d498', 'bricks-advanced-themer' ) ) : the_row();

                                    // From Database
                                    if( $custom_format === "database" && isset($json_from_db) && !empty($json_from_db) ){
                                        $jsonString = get_sub_field('field_64065d4ffp9c6', 'bricks-advanced-themer' );
                                        $jsonObj = json_decode($jsonString);
                                        ?>
                                        <div class="brxc-overlay__pannel brxc-overlay__pannel-3 isotope-wrapper" data-gutter="10" data-filter-layout="fitRows">
                                            <!-- Panel Content -->
                                            <div class="brxc-overlay__search-box">
                                                <input type="search" class="iso-search" name="typography-search" placeholder="Type here to filter the CSS variables" data-type="textContent">
                                                <div class="iso-search-icon">
                                                    <i class="bricks-svg ti-search"></i>
                                                </div>
                                                <div class="iso-reset" data-balloon="Reset" data-balloon-pos="left">
                                                    <i class="bricks-svg fas fa-undo"></i>
                                                </div>
                                            </div>
                                            <div class="brxc-overlay__pannel--content">
                                                <?php if (isset($jsonObj) && !empty($jsonObj) && is_object($jsonObj)){
                    
                                                    foreach ($jsonObj as $category => $items) {
                                                        ?>
                                                        <div class="brxc-variable-picker__category">
                                                            <label class="brxc-input__label"><?php echo esc_attr($category) ?></label>
                                                            <div class="brxc-overlay__action-btn-wrapper isotope-container">
                                                            <?php
                                                            if (isset($items) && !empty($items) && is_array($items)){
                                                                foreach ($items as $item) {?>
                                                                    <div class="brxc-overlay__action-btn isotope-selector" data-variable="var(--<?php echo esc_attr($item) ?>)"><?php echo esc_attr($item) ?></div>
                                                                <?php }
                                                            }?>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                }?>
                                            </div>
                                        </div>
                                    <?php }

                                    // Repeater
                                    else if ( $custom_format === "json" && have_rows('field_63b4600putac1', 'bricks-advanced-themer' ) ){
                                        while( have_rows('field_63b4600putac1', 'bricks-advanced-themer' ) ) : the_row();
                                            $label = get_sub_field('field_63bdeds216ac3', 'bricks-advanced-themer' );
                                            $file = get_sub_field('field_6334dcx216ac7', 'bricks-advanced-themer' );
                                            $jsonString = AT__Helpers::read_file_contents($file);
                                            if ($jsonString !== false){

                                                $jsonObj = json_decode($jsonString);
                    
                                                if (isset($jsonObj) && !empty($jsonObj) && is_object($jsonObj)){?>
                                                    <div class="brxc-overlay__pannel brxc-overlay__pannel-3 isotope-wrapper" data-gutter="10" data-filter-layout="fitRows">
                                                        <!-- Panel Content -->
                                                        <div class="brxc-overlay__search-box">
                                                            <input type="search" class="iso-search" name="typography-search" placeholder="Type here to filter the CSS variables" data-type="textContent">
                                                            <div class="iso-search-icon">
                                                                <i class="bricks-svg ti-search"></i>
                                                            </div>
                                                            <div class="iso-reset" data-balloon="Reset" data-balloon-pos="left">
                                                                <i class="bricks-svg fas fa-undo"></i>
                                                            </div>
                                                        </div>
                                                        <div class="brxc-overlay__pannel--content">
                                                            <?php foreach ($jsonObj as $category => $items) {
                                                                ?>
                                                                <div class="brxc-variable-picker__category">
                                                                    <label class="brxc-input__label"><?php echo esc_attr($category) ?></label>
                                                                    <div class="brxc-overlay__action-btn-wrapper isotope-container">
                                                                    <?php
                                                                    if (isset($items) && !empty($items) && is_array($items)){
                                                                        foreach ($items as $item) {?>
                                                                            <div class="brxc-overlay__action-btn isotope-selector" data-variable="var(--<?php echo esc_attr($item) ?>)"><?php echo esc_attr($item) ?></div>
                                                                        <?php }
                                                                    }?>
                                                                    </div>
                                                                </div>
                                                            <?php }?>
                                                        </div>
                                                    </div>
                                                <?php }
                                            }
                                        endwhile;
                                    }
                                endwhile;
                            endif;
                            
                        }?>
                        <!-- End of Modal Panel -->
                    </div>
                    <!-- End of Modal Panels Wrapper -->
                </div>
                <!-- End of Modal Container -->
            </div>
            <!-- End of Modal Wrapper -->
        </div>
        <!-- End of Main Inner -->
    </div>
    <!-- End of Main -->
<?php }