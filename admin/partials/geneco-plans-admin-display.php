<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.ytl.com/technology.asp
 * @since      1.0.0
 *
 * @package    Geneco_Plans
 * @subpackage Geneco_Plans/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap layer-genecoAdmin">
    <div class="page-header">
        <h1>Geneco Plans <span class="span-version"><em>v<?php echo GENECO_PLANS_VERSION; ?></em></span></h1>
    </div>
    <div class="nav-tab-wrapper">
        <a href="javascript:void(0)" class="nav-tab nav-tab-active">API Information Settings</a>
        <a href="?page=geneco-plans-action" class="nav-tab">Pull Residential Plans</a>
        <a href="?page=geneco-b2b-plans-action" class="nav-tab">Pull B2B Plans</a>
    </div>
    
    <div class="wrapper-genecoAdmin">
        <div class="layer-section">
            <h2>Residential API Information Settings</h2>
            
            <?php settings_errors('genapi_messages'); ?>

            <form method="POST" action="options.php">
                <?php 
                    settings_fields(GENAPI_PREFIX.'settings');
                    do_settings_sections(GENAPI_PREFIX.'settings');
                    submit_button(__('Save Settings', 'geneco-plans'));
                ?>
            </form>
        </div>
    </div>
</div>