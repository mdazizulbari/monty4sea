<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcPlainClassesOverlay';
$prefix_id = 'brxcPlainClasses';
$prefix_class = 'brxc-plain-classes';
// Heading
$modal_heading_title = 'Plain Classes';

if (!AT__Helpers::is_builder_tweaks_category_activated()){
    $theme_settings = \get_admin_url() . 'admin.php?page=bricks-advanced-themer';
    $error_title = "Feature not enabled";
    $error_desc = "It seems like this feature hasn't been enabled inside the theme settings. Click on the botton below and make sure that the <strong class='accent'>Builder Tweaks</strong> settings are enabled inside <strong class='accent'>Global Settings > General > Customize the functions included in Advanced Themer</strong>.";
    include \BRICKS_ADVANCED_THEMER_PATH . '/inc/builderPanels/_default_error.php';
} else {
?>
<!-- Main -->
<div id="<?php echo esc_attr($overlay_id);?>" class="brxc-overlay__wrapper" style="opacity:0" data-input-target="" onmousedown="ADMINBRXC.closeModal(event, this, '#<?php echo esc_attr($overlay_id);?>');" >
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
                <div class="brxc-overlay__resize-icons">
                    <i class="fa-solid fa-window-maximize" onclick="ADMINBRXC.maximizeModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                    <i class="ti-layout-sidebar-left" onclick="ADMINBRXC.leftSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                    <i class="ti-layout-sidebar-right" onclick="ADMINBRXC.rightSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                </div>
            </div>
            <!-- Modal Error Container for OpenAI -->
            <div class="brxc-overlay__error-message-wrapper"></div>
            <!-- Modal Container -->
            <div class="brxc-overlay__container no-radius">
                <!-- Modal Panels Wrapper -->
                <div class="brxc-overlay__pannels-wrapper">
                    <!-- Modal Panel -->
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-1">
                        <!-- Panel Content -->
                        <p class="brxc-overlay-css__desc" data-control="info">Update the classes in bulk. Seperate each different class by a space, without dot. Any deleted class from the list will be removed too.</p>
                        <textarea name="plain-classes" id="plainClassesInput" placeholder="Type your classes here..." cols="30" rows="10"></textarea>
                        <div id="plainClassesMostUsedCanvas"></div>
                        
                        <div id="plainClassesSearchWrapper" class="m-top-16 brxc-overlay__search-box">
                            <input type="search" class="iso-search" placeholder="Type your classname here">
                            <div class="iso-search-icon">
                                        <i class="bricks-svg ti-search"></i>
                                    </div>
                                    <div class="iso-reset" data-balloon="Reset" data-balloon-pos="left" onclick="ADMINBRXC.plainClassesStates.search = '';ADMINBRXC.plainClassesRender(false);this.previousElementSibling.previousElementSibling.value = '';">
                                        <i class="bricks-svg fas fa-undo"></i>
                                    </div>
                        </div>
                        <div id="plainClassesSearchResultsCanvas"></div>
                        <!-- End of Panel Content -->
                    </div>
                    <!-- End of Modal Panel -->
                </div>
                <!-- End of Modal Panels Wrapper -->
            </div>
            <!-- End of Modal Container -->
             <!-- Modal Footer -->
             <div class="brxc-overlay__footer">
                <div class="brxc-overlay__footer-wrapper">
                    <a class="brxc-overlay__action-btn" style="margin-left:auto;" onclick="ADMINBRXC.resetClasses(this)"><span>Reset Classes</span></a>
                    <a class="brxc-overlay__action-btn primary" onclick="ADMINBRXC.savePlainClasses(this, document.querySelector('#<?php echo esc_attr($overlay_id); ?> .CodeMirror').CodeMirror.getValue(CodeMirror));"><span>Update Classes</span></a>
                </div>
            </div>
        </div>
        <!-- End of Modal Wrapper -->
    </div>
    <!-- End of Main Inner -->
</div>
<!-- End of Main -->
<?php }