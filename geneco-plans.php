<?php

/**
 * @link              https://www.ytl.com/technology.asp
 * @since             1.0.0
 * @package           Geneco_Plans
 *
 * @wordpress-plugin
 * Plugin Name:       Geneco Plans
 * Plugin URI:        https://geneco.sg
 * Description:       Plugin to pull and show the plans for Geneco.sg website.
 * Version:           1.2.0
 * Author:            YTL Digital Design [AL Latif Mohamad]
 * Author URI:        https://www.ytl.com/technology.asp
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       geneco-plans
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define('GENECO_PLANS_VERSION', '1.2.0');

/**
 * Prefix for all plugin's options.
 */
define('GENAPI_PREFIX', 'genapi_');

/**
 * Shortcode for Geneco Plans API.
 */
define('GENAPI_SHORTCODE', 'geneco_plans_api');

/**
 * Shortcode for Geneco B2B Plans API.
 */
define('GENAPI_B2B_SHORTCODE', 'geneco_b2b_plans_api');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-geneco-plans-activator.php
 */
function activate_geneco_plans()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-geneco-plans-activator.php';
    Geneco_Plans_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-geneco-plans-deactivator.php
 */
function deactivate_geneco_plans()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-geneco-plans-deactivator.php';
    Geneco_Plans_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_geneco_plans');
register_deactivation_hook(__FILE__, 'deactivate_geneco_plans');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-geneco-plans.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_geneco_plans()
{
    $plugin = new Geneco_Plans();
    $plugin->run();
}
run_geneco_plans();


/**
 * Function to pull rates from API
 * 
 * @since    1.1.0
 * 
 * @param    array      $plans      Object array of plans which can be retrieved from the database - get_option('genapi_plans_data')
 * @return   array
 */
function geneco_pull_rates_api($plans = [])
{
    $return         = ['args' => [], 'data' => []];
    $genapi_options = get_option('genapi_settings');
    $rates_api_url  = $genapi_options['genapi_rates_api_url'];

    if ($rates_api_url) {
        $rates_params   = [];
        $cur_date       = date('Y-m-d');
        $contract_date  = date('Y-m-d', strtotime($cur_date . ' + 28 days'));
    
        foreach ($plans as $plan) {
            $rate_reference = $plan->RateReference;
            $plan_code      = $plan->ReferenceCode;
    
            if ($rate_reference && $plan_code) {
                $rates_param    = [
                    'PlanName'      => $plan->Name, 
                    'ContractDate'  => $contract_date, 
                    'RateReference' => $rate_reference, 
                    'PlanCode'      => $plan_code 
                ];
                $rates_params[] = $rates_param;
            }
        }
    
        $response   = wp_remote_post($rates_api_url, [
            'headers'       => array('Content-Type' => 'application/json; charset=utf-8'),
            'body'          => json_encode($rates_params),
            'data_format'   => 'body'
        ]);
        $return     = [
            'args'  => $rates_params, 
            'data'  => json_decode($response['body']) 
        ];
    }
    
    return $return;
}


/**
 * Function to get plans without arguments. This function will get the plan data and continue pulling the rates through API
 * 
 * @since    1.1.0
 * 
 * @return   array
 */
function get_plans_rates() 
{
    $plans_data = get_option('genapi_plans_data');
    $plans      = json_decode(unserialize($plans_data));

    return geneco_pull_rates_api($plans);
}


/**
 * Function to B2B pull rates from API
 * 
 * @since    1.2.0
 * 
 * @param    array      $plans      Object array of plans which can be retrieved from the database - get_option('genapi_plans_data')
 * @return   array
 */
function geneco_pull_b2b_rates_api($plans = [])
{
    $return         = ['args' => [], 'data' => []];
    $genapi_options = get_option('genapi_settings');
    $rates_api_url  = $genapi_options['genapi_b2b_rates_api_url'];

    if ($rates_api_url) {
        $rates_params   = [];
        $cur_date       = date('Y-m-d');
        $contract_date  = date('Y-m-d', strtotime($cur_date . ' + 28 days'));
    
        foreach ($plans as $plan) {
            $rate_reference = $plan->RateReference;
            $plan_code      = $plan->ReferenceCode;
    
            if ($rate_reference && $plan_code) {
                $rates_param    = [
                    'PlanName'      => $plan->Name, 
                    'ContractDate'  => $contract_date, 
                    'RateReference' => $rate_reference, 
                    'PlanCode'      => $plan_code 
                ];
                $rates_params[] = $rates_param;
            }
        }
    
        $response   = wp_remote_post($rates_api_url, [
            'headers'       => array('Content-Type' => 'application/json; charset=utf-8'),
            'body'          => json_encode($rates_params),
            'data_format'   => 'body'
        ]);
        $return     = [
            'args'  => $rates_params, 
            'data'  => json_decode($response['body']) 
        ];
    }
    
    return $return;
}


/**
 * Function to get B2B plans without arguments. This function will get the plan data and continue pulling the rates through API
 * 
 * @since    1.2.0
 * 
 * @return   array
 */
function get_b2b_plans_rates() 
{
    $plans_data = get_option('genapi_b2b_plans_data');
    $plans      = json_decode(unserialize($plans_data));

    return geneco_pull_b2b_rates_api($plans);
} 