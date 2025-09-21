<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcAddCustomComponentsOverlay';
$prefix = 'brxcAddCustomComponents';
$prefix_id = 'brxc-add-custom-components';
// Heading
$modal_heading_title = 'Add/Edit Nested Element';
//for loops
$i = 0;

if (!AT__Helpers::is_builder_tweaks_category_activated()){
    $theme_settings = \get_admin_url() . 'admin.php?page=bricks-advanced-themer';
    $error_title = "Feature not enabled";
    $error_desc = "It seems like this feature hasn't been enabled inside the theme settings. Click on the botton below and make sure that the Extras settings are enabled inside <strong class='accent'>Global Settings > General > Customize the functions included in Advanced Themer</strong>.";
    include \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/_default_error.php';
} else {
    ?>
    <!-- Main -->
    <div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper sidebar right" style="opacity:0" data-input-target="" onmousedown="ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');" >
        <!-- Main Inner -->
        <div class="brxc-overlay__inner brxc-large">
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
                </div>
                <!-- Modal Error Container for OpenAI -->
                <div class="brxc-overlay__error-message-wrapper"></div>
                <!-- Modal Container -->
                <div class="brxc-overlay__container no-radius">
                    <!-- Modal Panels Wrapper -->
                    <div class="brxc-overlay__pannels-wrapper">
                        <!-- Modal Panel -->
                        <div class="brxc-overlay__pannel brxc-overlay__pannel-1">
                            <div id="brxcAddCustomComponentsCanvas"></div>
                        </div>
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
