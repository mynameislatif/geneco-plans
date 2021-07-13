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
 * Version:           1.0.0
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
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('GENECO_PLANS_VERSION', '1.0.2');

/**
 * Prefix for all plugin's options.
 */
define('GENAPI_PREFIX', 'genapi_');

/**
 * Shortcode for Geneco Plans API.
 */
define('GENAPI_SHORTCODE', 'geneco_plans_api');

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
