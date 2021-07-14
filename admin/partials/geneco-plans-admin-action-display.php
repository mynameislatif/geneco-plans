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
        <a href="?page=geneco-plans" class="nav-tab">API Information Settings</a>
        <a href="javascript:void(0)" class="nav-tab nav-tab-active">Pull Plans Action</a>
    </div>

    <div class="wrapper-genecoAdmin">
        <div class="layer-section">
            <h2>Pull Plans</h2>

            <?php settings_errors('genapi_messages'); ?>

            <form method="POST" id="form-pullAction">
                <?php wp_nonce_field('pull_plans_btn_clicked'); ?>

                <table class="form-table" role="presentation">
                    <tbody>
                        <?php if (get_option('genapi_updated_at') && get_option('genapi_plans_data')) : ?>
                        <tr>
                            <th scope="row">
                                <label for="genapi_updated_at">Last Pulled At</label>
                            </th>
                            <td>
                                <input type="text" class="regular-text" id="genapi_updated_at" name="genapi_updated_at" value="<?php echo date('Y-m-d H:i:s', get_option('genapi_updated_at')); ?>" readonly="readonly" />
                                <p class="description"><em><?php echo __('The timestamp for last successful data pulled and saved.', 'geneco-plans'); ?></em></p>
                            </td>
                        </tr>
                        <?php else : ?>
                        <tr>
                            <td><em><?php echo __('No plan pulled from API yet. Please click on the "Pull Data" button below to start pulling plan data.', 'geneco-plans'); ?></em></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="hidden" name="_wp_http_referer" value="/wp-admin/admin.php?page=geneco-plans" />
                    <input type="hidden" name="trigger_pull_data" value="1" />
                    <input type="submit" name="btn-pull" id="btn-pullData" class="button button-primary" value="Pull Data" />
                </p>
            </form>
        </div>
        <?php 
            $plans_data = get_option('genapi_plans_data');
            $has_plans  = false;
            if ($plans_data) :
                $has_plans  = true;
                $plans_obj  = json_decode(unserialize($plans_data));
        ?>

        <?php if (defined('GENAPI_SHORTCODE')) : ?>
        <div class="layer-section">
            <h2>Usage</h2>
            <div class="layer-usages">
                <div class="layer-usage">
                    <h3>Shortcode:</h3>
                    <code>[<?php echo GENAPI_SHORTCODE; ?>]</code>
                    <p><em>You may use this shortcode to display all the plans on your page</em></p>
                </div>
                <div class="layer-usage">
                    <h3>PHP:</h3>
                    <code>get_option('genapi_plans_data')</code>
                    <p><em>You may use this function inside the <code>&lt;?php ?&gt;</code> block</em></p>
                </div>
                <div class="layer-usage">
                    <h3>JavaScript:</h3>
                    <code>genecoObj</code>
                    <em>or</em>
                    <code>genecoObj.data</code>
                    <p><em>You may use these variables directly inside your JavaScript scripts</em></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="layer-section">
            <div class="layer-preCode">
                <h2>Plans Data Object</h2>
                <pre><?php print_r($plans_obj); ?></pre>
            </div>
        </div>
        <?php 
            endif;
        ?>
    </div>
</div>

<script type="text/javascript">
    var hasPlans = <?php echo ($has_plans) ? $has_plans : 0; ?>;
    jQuery(document).ready(function() {
        jQuery('#form-pullAction').on('submit', function(e) {
            var confirmText = (hasPlans) ? 'Are you sure you want to pull the plan data? The plan data will be overwritten if you proceed with the Pull Data action.' : 'Are you sure you want to pull the plan data?';
            var retConfirm  = confirm(confirmText);

            if (!retConfirm) {
                var cancelText  = (hasPlans) ? 'Pull data action has been cancelled. Plan data will not be updated.' : 'Pull data action has been cancelled.';

                setTimeout(function() {
                    alert(cancelText);
                }, 500);

                e.preventDefault();
            }
        });
    });
</script>