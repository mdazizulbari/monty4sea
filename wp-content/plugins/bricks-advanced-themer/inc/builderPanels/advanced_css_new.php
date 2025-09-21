<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH')) { die();
}

/*--------------------------------------
Variables
--------------------------------------*/

// ID & Classes
$overlay_id = 'brxcCSSOverlay';
$prefix = 'global-code-openai';
// Heading
$modal_heading_title = 'Advanced CSS';
//for loops
$i = 0;

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
                    <i class="fa-solid fa-window-maximize active" onclick="ADMINBRXC.maximizeModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                    <i class="ti-layout-sidebar-left" onclick="ADMINBRXC.leftSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                    <i class="ti-layout-sidebar-right" onclick="ADMINBRXC.rightSidebarModal(this, '#<?php echo esc_attr($overlay_id);?>');"></i>
                </div>
            </div>
            <!-- Modal Error Container for OpenAI -->
            <div class="brxc-overlay__error-message-wrapper"></div>
            <!-- Modal Container -->
            <div class="brxc-overlay__container">
                <!-- Modal Panel Switch -->
                <?php if (isset($brxc_acf_fields['advanced_css_panels']) && !empty($brxc_acf_fields['advanced_css_panels']) && is_array($brxc_acf_fields['advanced_css_panels']) && count($brxc_acf_fields['advanced_css_panels']) > 1):?>
                <div class="brxc-overlay__panel-switcher-wrapper">
                    <!-- Label/Input Switchers -->
                    <?php if (in_array('page-css',$brxc_acf_fields['advanced_css_panels'])):?>
                    <input type="radio" id="<?php echo esc_attr($prefix)?>-page" name="<?php echo esc_attr($prefix)?>-switch" class="brxc-input__radio" data-code="page" data-transform="0" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);" checked>
                    <label for="<?php echo esc_attr($prefix)?>-page">Page CSS</label>
                    <?php endif; ?>
                    <?php if (in_array('global-css',$brxc_acf_fields['advanced_css_panels'])):?>
                    <input type="radio" id="<?php echo esc_attr($prefix)?>-global" name="<?php echo esc_attr($prefix)?>-switch" class="brxc-input__radio" data-code="global" data-transform="0" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
                    <label for="<?php echo esc_attr($prefix)?>-global">Global CSS</label>
                    <?php endif; ?>
                    <?php if (get_template_directory() !== get_stylesheet_directory() && in_array('child-theme-css', $brxc_acf_fields['advanced_css_panels'])):
                        ?>
                        <input type="radio" id="<?php echo esc_attr($prefix)?>-child-theme" name="<?php echo esc_attr($prefix)?>-switch" class="brxc-input__radio" data-code="child-theme" data-transform="0" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
                        <label for="<?php echo esc_attr($prefix)?>-child-theme">Child Theme CSS</label>
                    <?php endif;?>
                    <?php if( in_array('imported-css',$brxc_acf_fields['advanced_css_panels']) && have_rows('field_63b59j871b209', 'bricks-advanced-themer' ) ):
                            while( have_rows('field_63b59j871b209', 'bricks-advanced-themer' ) ) : the_row();
                                if ( have_rows( 'field_63b4bd5c16ac1', 'bricks-advanced-themer' ) ) :
                                    while ( have_rows( 'field_63b4bd5c16ac1', 'bricks-advanced-themer' ) ) :
                                        the_row();
                                        $label = get_sub_field('field_63b4bd5c16ac3', 'bricks-advanced-themer' );
                                        $label_lower = strtolower( preg_replace( '/\s+/', '-', esc_attr($label) ) );
                                        ?>
                                        <input type="radio" id="<?php echo esc_attr($prefix)?>-<?php echo esc_attr($label_lower);?>" name="<?php echo esc_attr($prefix)?>-switch" class="brxc-input__radio" data-code="<?php echo esc_attr($label_lower);?>" data-transform="0" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
                                        <label for="<?php echo esc_attr($prefix)?>-<?php echo esc_attr($label_lower);?>"><?php echo esc_attr($label);?> (Imported)</label>
                                    <?php endwhile;?>
                                <?php endif;?>
                            <?php endwhile;?>
                    <?php endif;?>
                    <?php /*
                    // Deprecated "CSS Variables" tab since 1.2.4

                    if (in_array('css-variables',$brxc_acf_fields['advanced_css_panels'])):?>
                    <input type="radio" id="<?php echo esc_attr($prefix)?>-variables" name="<?php echo esc_attr($prefix)?>-switch" class="brxc-input__radio" data-code="variables" data-transform="0" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
                    <label for="<?php echo esc_attr($prefix)?>-variables">CSS Variables</label>
                    <?php endif; */?>
                    <?php if (AT__Helpers::is_ai_category_activated() && isset($brxc_acf_fields['advanced_css_panels']) && !empty($brxc_acf_fields['advanced_css_panels']) && is_array($brxc_acf_fields['advanced_css_panels']) && in_array('ai-assistant',$brxc_acf_fields['advanced_css_panels']) && isset( $brxc_acf_fields['openai_api_key']) && $brxc_acf_fields['openai_api_key'] === '0'):?>
                    <input type="radio" id="<?php echo esc_attr($prefix)?>-ai" name="<?php echo esc_attr($prefix)?>-switch" class="brxc-input__radio" data-transform="calc(-100% - 80px)" onClick="ADMINBRXC.movePanel(document.querySelector('#<?php echo esc_attr($overlay_id);?> .brxc-overlay__pannels-wrapper'),this.dataset.transform);">
                    <label for="<?php echo esc_attr($prefix)?>-ai" style="margin-left: auto;">AI Assistant</label>
                    <?php endif; ?>
                    <!-- End of Label/Input Switchers -->
                </div>
                <?php endif; ?>
                <!-- End of Panel Switch -->
                <!-- Modal Panels Wrapper -->
                <div class="brxc-overlay__pannels-wrapper">
                    <!-- Modal Panel -->
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-1">
                        <!-- Panel Content -->

                        <div id="brxcCSSColRight">

                            <?php if (isset($brxc_acf_fields['advanced_css_panels']) && !empty($brxc_acf_fields['advanced_css_panels']) && is_array($brxc_acf_fields['advanced_css_panels']) && in_array('page-css',$brxc_acf_fields['advanced_css_panels'])):?>
                            <!-- Page CSS -->
                            <div id="brxcPageCSSWrapper" class="brxc-overlay-css__wrapper has-codemirror" data-code="page">
                                <p class="brxc-overlay-css__desc" data-control="info">Insert your page-specific CSS code here. It will be automatically applied & synched with the builder.</p>
                                <textarea id="brxcCustomCSS" class="brxcCodeMirror"></textarea>
                                <div class="brxc-overlay__action-btn-wrapper right m-top-16">
                                    <div class="brxc-overlay__action-btn" onClick='ADMINBRXC.importCSSfromElements(this,this.parentElement.previousElementSibling.CodeMirror,"id",false);'>
                                        <span>Import CSS from Elements</span>
                                    </div>
                                </div>
                            </div>
                            <?php endif;?>
                            <?php if (isset($brxc_acf_fields['advanced_css_panels']) && !empty($brxc_acf_fields['advanced_css_panels']) && is_array($brxc_acf_fields['advanced_css_panels']) && in_array('global-css',$brxc_acf_fields['advanced_css_panels'])):?>
                            <!-- Global CSS -->
                            <div id="<?php echo esc_attr($prefix);?>CSSWrapper" class="brxc-overlay-css__wrapper has-codemirror" data-code="global">
                                <p class="brxc-overlay-css__desc" data-control="info">The following CSS codes apply on all the pages of your website. All the changes made here during this session will apply on the page, but they won't be saved inside your database <strong>until you click on the SAVE button at the bottom of this tab.</strong></p>
                                <textarea id="brxcCustomGlobalCSS" class="brxcCodeMirror"></textarea>
                                <div class="brxc-overlay__action-btn-wrapper right m-top-16">
                                    <div class="brxc-overlay__action-btn icons-inside">
                                        <span class="m-right-16">Generate Selectors</span>
                                        <div class="buttons first" data-balloon="Only Plain Classes" data-balloon-pos="top" onClick='ADMINBRXC.generateSelectors(this,this.parentElement.parentElement.previousElementSibling.CodeMirror, false);'>
                                            <span class="action">
                                                <i class="fas fa-code"></i>
                                            </span>
                                        </div>
                                        <div class="buttons" data-balloon="Include Media Queries" data-balloon-pos="top" onClick='ADMINBRXC.generateSelectors(this,this.parentElement.parentElement.previousElementSibling.CodeMirror, true);'>
                                            <span class="action">
                                                <i class="fas fa-at"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="brxc-overlay__action-btn icons-inside">
                                        <span class="m-right-16">Import CSS from Global Classes</span>
                                        <div class="buttons first" data-balloon="All Global Classes" data-balloon-pos="top" onClick='ADMINBRXC.importCSSfromElements(this,this.parentElement.parentElement.previousElementSibling.CodeMirror,"classes","global");'>
                                            <span class="action">
                                                <i class="fas fa-globe"></i>
                                            </span>
                                        </div>
                                        <div class="buttons" data-balloon="Active Global Classes on This Page" data-balloon-pos="top" onClick='ADMINBRXC.importCSSfromElements(this,this.parentElement.parentElement.previousElementSibling.CodeMirror,"classes","page");'>
                                            <span class="action">
                                                <i class="fas fa-file"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="brxc-overlay__action-btn" onClick='ADMINBRXC.parseGlobalCSS(this,this.parentElement.previousElementSibling.CodeMirror.getValue());'>
                                        <span>Extract Classes and Save as Global Classes</span>
                                    </div>
                                    <div class="brxc-overlay__action-btn primary" onClick='ADMINBRXC.saveGlobalCSS( "<?php echo esc_attr($prefix);?>", false,"#<?php echo esc_attr($overlay_id);?>", this, ADMINBRXC.vueState.globalSettings.customCss);'>
                                        <span>Save</span>
                                    </div>
                                </div>
                            </div>
                            <?php endif;?>
                            <?php if (isset($brxc_acf_fields['advanced_css_panels']) && !empty($brxc_acf_fields['advanced_css_panels']) && is_array($brxc_acf_fields['advanced_css_panels']) && in_array('child-theme-css',$brxc_acf_fields['advanced_css_panels'])):?>
                            <?php
                            if (get_template_directory() !== get_stylesheet_directory()){
                                $file = get_stylesheet_directory() . '/style.css';
                                ?>
                                <div class="brxc-overlay-css__wrapper has-codemirror" data-code="child-theme">
                                    <p class="brxc-overlay-css__desc" data-control="info"> The following CSS codes apply on all the pages of your website. The Child Theme CSS are set to be read-only. To modify it, go to your <a href="<?php echo \get_admin_url() ;?>theme-editor.php" target="_blank">Theme File Editor</a>.</p>
                                    <textarea class="brxc-codemirror__imported"><?php echo esc_html(file_get_contents($file));?></textarea>
                                    <div class="brxc-overlay__action-btn-wrapper right m-top-16">
                                        <div class="brxc-overlay__action-btn" onClick='ADMINBRXC.parseGlobalCSS(this,this.parentElement.previousElementSibling.CodeMirror.getValue());'>
                                            <span>Extract Classes and Save as Global Classes</span>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php endif;?>
                            <?php if (isset($brxc_acf_fields['advanced_css_panels']) && !empty($brxc_acf_fields['advanced_css_panels']) && is_array($brxc_acf_fields['advanced_css_panels']) && in_array('imported-css',$brxc_acf_fields['advanced_css_panels']) && have_rows('field_63b59j871b209', 'bricks-advanced-themer' ) ):
                                    while( have_rows('field_63b59j871b209', 'bricks-advanced-themer' ) ) : the_row();
                                        if (have_rows( 'field_63b4bd5c16ac1', 'bricks-advanced-themer' ) ) :
                                            while ( have_rows( 'field_63b4bd5c16ac1', 'bricks-advanced-themer' ) ) :
                                                the_row();
                                                $label = get_sub_field('field_63b4bd5c16ac3', 'bricks-advanced-themer' );
                                                $file = get_sub_field('field_63b4bdf216ac7', 'bricks-advanced-themer' );
                                                ?>
                                                <div class="brxc-overlay-css__wrapper has-codemirror" data-code="<?php echo strtolower( preg_replace( '/\s+/', '-', esc_attr($label) ) );?>">
                                                    <p class="brxc-overlay-css__desc" data-control="info">The following CSS codes apply on all the pages of your website. The imported CSS are set to be read-only. To modify it, go to your <a href="<?php echo \get_admin_url() ;?>admin.php?page=bricks-advanced-themer" target="_blank">Advanced Themer Settings</a>.</p>
                                                    <textarea class="brxc-codemirror__imported"><?php echo esc_html(file_get_contents($file));?></textarea>
                                                </div>
                                            <?php endwhile;?>
                                    <?php endif;?>
                                <?php endwhile;?>
                            <?php endif;?>
                        </div>

                        <!-- End of Panel Content -->
                    </div>
                    <!-- End of Modal Panel -->
                    <?php if (AT__Helpers::is_ai_category_activated() && isset($brxc_acf_fields['advanced_css_panels']) && !empty($brxc_acf_fields['advanced_css_panels']) && is_array($brxc_acf_fields['advanced_css_panels']) && in_array('ai-assistant',$brxc_acf_fields['advanced_css_panels']) && isset( $brxc_acf_fields['openai_api_key']) && $brxc_acf_fields['openai_api_key'] === '0'):?>
                    <!-- Modal Panel -->
                    <div class="brxc-overlay__pannel brxc-overlay__pannel-2 code accordion v1">
                    <?php 
                    $pannel = '.brxc-overlay__pannel-2.code';
                    $type = 'Code';
                    $custom_tone = false;
                    $include_tones = false;
                    ?>
                        <!-- Panel Content -->
                        <div class="brxc-field__wrapper">
                            <label class="brxc-input__label">User Prompt <span class="brxc__light">(Required)</span></label>
                            <?php include \BRICKS_ADVANCED_THEMER_PATH . '/inc/components/openai_no_reset.php';?>
                            <textarea name="<?php echo esc_attr($prefix);?>-prompt-text" id="<?php echo esc_attr($prefix);?>PromptText" class="<?php echo esc_attr($prefix);?>-prompt-text reset-value-on-reset message user" placeholder="Type your prompt text here..." cols="30" rows="3"></textarea>
                        </div>
                        <?php 
                        include \BRICKS_ADVANCED_THEMER_PATH . '/inc/components/openai_advanced_options.php';
                        ?>
                        <div id="<?php echo esc_attr($prefix);?>Generate<?php echo esc_attr($type);?>ContentWrapper" class="brxc-overlay__action-btn-wrapper right m-top-16 generate-content active">
                            <div class="brxc-overlay__action-btn" onClick="ADMINBRXC.resetAIresponses(document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .reset-value-on-reset:not(input.brxc-no-reset:checked ~ *)'), document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .remove-on-reset'), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?>Generate<?php echo esc_attr($type);?>ContentWrapper'))"><span>Reset</span></div>
                            <div class="brxc-overlay__action-btn primary" onclick="ADMINBRXC.getCodeAIResponse('<?php echo esc_attr($prefix);?>',this,true,'#<?php echo esc_attr($overlay_id);?>', parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Temperature').value).toFixed(1), parseInt(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>MaxTokens').value), parseInt(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Choices').value), parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>TopP').value).toFixed(2), parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Presence').value).toFixed(1), parseFloat(document.querySelector('#<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>Frequency').value).toFixed(1), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> input[name=<?php echo esc_attr($prefix);?><?php echo esc_attr($type);?>-models]:checked').value);"><span>Generate Content</span></div>
                        </div>
                        <div id="<?php echo esc_attr($prefix);?>Insert<?php echo esc_attr($type);?>ContentWrapper" class="brxc-overlay__action-btn-wrapper right m-top-16 action-wrapper">
                            <div class="brxc-overlay__action-btn" onClick="ADMINBRXC.resetAIresponses(document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .reset-value-on-reset:not(input.brxc-no-reset:checked ~ *)'), document.querySelectorAll('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> .remove-on-reset'), document.querySelector('#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> #<?php echo esc_attr($prefix);?>Generate<?php echo esc_attr($type);?>ContentWrapper'))">
                                <span>Reset</span>
                            </div>
                            <div class="brxc-overlay__action-btn" onClick='ADMINBRXC.copytoClipboard(this,document.querySelector("#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> input[name=<?php echo esc_attr($prefix);?>-code-results]:checked + label .CodeMirror").CodeMirror.getValue(),"Content Copied!", "Copy Selected to Clipboard");'>
                                <span>Copy Selected to Clipboard</span>
                            </div>
                            <div class="brxc-overlay__action-btn primary" onClick='ADMINBRXC.pasteAICode(document.querySelector("#<?php echo esc_attr($overlay_id);?> <?php echo esc_attr($pannel);?> input[name=<?php echo esc_attr($prefix);?>-code-results]:checked + label .CodeMirror").CodeMirror.getValue());'>
                                <span>Insert Code to your Page CSS</span>
                            </div>
                        </div>
                    </div>
                    <!-- End of Modal Panel -->
                    <?php endif;?>
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