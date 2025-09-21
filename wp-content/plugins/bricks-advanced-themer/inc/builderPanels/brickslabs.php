<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcBricksLabsOverlay';
$prefix = 'brickslabs';
// Heading
$modal_heading_title = 'BricksLabs';
$modal_heading_link = 'https://brickslabs.com/';
//for loops
$i = 0;

if (!AT__Helpers::is_extras_category_activated()){
    $theme_settings = \get_admin_url() . 'admin.php?page=bricks-advanced-themer';
    $error_title = "Feature not enabled";
    $error_desc = "It seems like this feature hasn't been enabled inside the theme settings. Click on the botton below and make sure that the Extras settings are enabled inside <strong class='accent'>Global Settings > General > Customize the functions included in Advanced Themer</strong>.";
    include \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/_default_error.php';
} else {
    ?>
    <!-- Main -->
    <div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper" style="opacity:0" data-input-target="" onmousedown="ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');" >
        <!-- Main Inner -->
        <div class="brxc-overlay__inner brxc-medium">
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
                    <!-- Modal Header External Link Icon-->
                    <a href="<?php echo esc_attr($modal_heading_link);?>" target="_blank" class="brxc-overlay__header-link">
                        <i class="fa-solid fa-up-right-from-square"></i>
                    </a>
                    <div class="brxc-overlay__resize-icons">
                        <i class="fa-solid fa-window-maximize" onclick="ADMINBRXC.maximizeModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                        <i class="ti-layout-sidebar-left" onclick="ADMINBRXC.leftSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                        <i class="ti-layout-sidebar-right" onclick="ADMINBRXC.rightSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                    </div>
                </div>
                <!-- Modal Error Container for OpenAI -->
                <div class="brxc-overlay__error-message-wrapper"></div>
                <!-- Modal Container -->
                <div class="brxc-overlay__container">
                    <!-- Modal Panels Wrapper -->
                    <div class="brxc-overlay__pannels-wrapper">
                        <!-- Modal Panel -->
                        <div class="brxc-overlay__pannel brxc-overlay__pannel-1">
                            <div id="brxc-overlay__canvas"></div>
                        </div>
                    </div>
                    <!-- End of Modal Panels Wrapper -->
                </div>
                <!-- End of Modal Container -->
                <!-- Modal Footer -->
                <div class="brxc-overlay__footer">
                    <div class="brxc-overlay__footer-wrapper">
                        <div class="brxc-overlay__footer-input-wrapper">
                            <div class="iso-search-icon">
                                <i class="bricks-svg ti-search"></i>
                            </div>
                            <input type="text" class="brxc-overlay__footer-input" placeholder="Search on BricksLabs"/>
                        </div>
                        <a class="brxc-overlay__action-btn primary" onClick="ADMINBRXC.bricksLabsAPI(event.target, this.previousElementSibling.querySelector('input').value);"><span>Search</span></a>
                    </div>
                </div>
            </div>
            <!-- End of Modal Wrapper -->
        </div>
        <!-- End of Main Inner -->
    </div>
    <!-- End of Main -->
<?php }
