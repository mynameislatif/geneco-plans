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
        <a href="?page=geneco-plans-action" class="nav-tab">Pull Residential Plans</a>
        <a href="javascript:void(0)" class="nav-tab nav-tab-active">Pull B2B Plans</a>
    </div>

    <div class="wrapper-genecoAdmin">
        <div class="layer-section">
            <h2>Pull B2B Plans</h2>

            <?php settings_errors('genapi_messages'); ?>

            <form method="POST" id="form-pullAction">
                <?php wp_nonce_field('pull_b2b_plans_btn_clicked'); ?>

                <table class="form-table" role="presentation">
                    <tbody>
                        <?php if (get_option('genapi_b2b_updated_at') && get_option('genapi_b2b_plans_data')) : ?>
                        <tr>
                            <th scope="row">
                                <label for="genapi_updated_at">Last Pulled At</label>
                            </th>
                            <td>
                                <input type="text" class="regular-text" id="genapi_updated_at" name="genapi_updated_at" value="<?php echo date('Y-m-d H:i:s', get_option('genapi_b2b_updated_at')); ?>" readonly="readonly" />
                                <p class="description"><em><?php echo __('The timestamp for last successful data pulled and saved.', 'geneco-plans'); ?></em></p>
                            </td>
                        </tr>
                        <?php else : ?>
                        <tr>
                            <td><em><?php echo __('No B2B plan pulled from API yet. Please click on the "Pull Data" button below to start pulling B2B plan data.', 'geneco-plans'); ?></em></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="hidden" name="_wp_http_referer" value="/wp-admin/admin.php?page=geneco-plans" />
                    <input type="hidden" name="trigger_b2b_pull_data" value="1" />
                    <input type="submit" name="btn-pull" id="btn-pullData" class="button button-primary" value="Pull Data" />
                </p>
            </form>
        </div>
        <?php 
            $plans_data = get_option('genapi_b2b_plans_data');
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
                    <p><em>You may use this shortcode to display all the plans on your page</em></p>
                    <code>[<?php echo GENAPI_B2B_SHORTCODE; ?>]</code>
                </div>
                <div class="layer-usage">
                    <h3>PHP:</h3>
                    <p><em>You may use this function inside the <code>&lt;?php ?&gt;</code> block.</em></p>

                    <h4>Plans Data</h4>
                    <code style="margin-bottom: 8px;">$b2b_plans_data = get_option('genapi_b2b_plans_data'); <br />$b2b_plan_obj   = json_decode(unserialize($plans_data));</code>
                    <p><em>You will be returned with an array of plan objects.</em></p>

                    <h4>Rates Data</h4>
                    <code>get_b2b_plans_rates()</code>
                    <p><em>You will be returned with arrays of arguments (parameters sent for API call), and data.</em></p>
                </div>
                <div class="layer-usage">
                    <h3>JavaScript:</h3>
                    <p><em>You may use these variables directly inside your JavaScript scripts. <br /></em></p>

                    <h4>General</h4>
                    <code>genecoObj</code>
                    <p><em>You will be returned with arrays of <strong>b2bPlanData</strong>.</em></p>

                    <h4>B2B Plans Data</h4>
                    <code>genecoObj.b2bPlanData</code>
                    <p><em>You will be returned with an array of B2B plan objects.</em></p>

                    <h4>B2B Rates Data</h4>
                    <p class="marginBottom-15"><em>B2B Rates will be pulled automatically by parsing today's date + 28 days as the parameter for <strong>ContractDate</strong> argument.</em></p>
                    <code>genecoObj.b2bRatesData</code>
                    <p><em>You will be returned with arrays of arguments (parameters sent for API call), and data.</em></p>
                    <code>genecoObj.b2bRatesData.data.Success</code>
                    <p><em>To check if the rates pulling is success or not.</em></p>
                    <code>genecoObj.b2bRatesData.data.Result</code>
                    <p><em>You will be returned with arrays of plan rates objects.</em></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="layer-section plugin-card">
            <div class="layer-preCode">
                <h2>B2B Plans Data Object</h2>
                <pre><?php print_r($plans_obj); ?></pre>
            </div>
        </div>
        
        <div class="layer-section plugin-card">
            <div class="layer-preCode">
                <h2>B2B Rates Data Object</h2>
                <pre class="pre-rates"><?php print_r(get_b2b_plans_rates($plans_obj)); ?></pre>
            </div>
        </div>
        <?php 
            endif;
        ?>
    </div>
</div>

<script type="text/javascript">
    (function($) {
        var hasPlans = <?php echo ($has_plans) ? $has_plans : 0; ?>;
        $(document).ready(function() {
            $('#form-pullAction').on('submit', function(e) {
                var confirmText = (hasPlans) ? 'Are you sure you want to pull the B2B plan data? The plan data will be overwritten if you proceed with the Pull Data action.' : 'Are you sure you want to pull the B2B plan data?';
                var retConfirm  = confirm(confirmText);

                if (!retConfirm) {
                    var cancelText  = (hasPlans) ? 'Pull data action has been cancelled. B2B plan data will not be updated.' : 'B2B pull data action has been cancelled.';

                    setTimeout(function() {
                        alert(cancelText);
                    }, 500);

                    e.preventDefault();
                }
            });
        });
    })(jQuery);
</script>