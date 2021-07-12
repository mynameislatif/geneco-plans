<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.ytl.com/technology.asp
 * @since      1.0.0
 *
 * @package    Geneco_Plans
 * @subpackage Geneco_Plans/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php
    $plans_data = get_option('genapi_plans_data');
    if ($plans_data) : 
        $plans_obj  = json_decode(unserialize($plans_data));
?>

<div class="container alignfull">
    <div class="layer-genecoPlans">
        <div class="row">
            
            <?php
                foreach ($plans_obj as $key => $plan) :
            ?>
            <div class="col-sm-3">
                <div class="layer-genecoPlan">
                    <h2 class="aligncenter">Plan <?=$key + 1?></h2>
                    <h4><?=$plan->Name?></h4>
                    <p><?=$plan->Description?></p>
                    <p class="panel-link">
                        <?php if ($plan->FactsheetUrl) : ?><a href="<?=$plan->FactsheetUrl?>" target="_blank" title="Go to Factsheet" class="btn btn-info mb-2 color-white">Go to Factsheet</a><?php endif; ?>
                        <a href="javascript:void(0)" title="Go to Plan" class="btn btn-secondary btn-lg">Go to Plan</a>
                    </p>
                </div>
            </div>
            <?php
                endforeach;
            ?>

        </div>
    </div>
</div>

<?php endif; ?>