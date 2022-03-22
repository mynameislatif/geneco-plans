<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.ytl.com/technology.asp
 * @since      1.0.0
 *
 * @package    Geneco_Plans
 * @subpackage Geneco_Plans/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Geneco_Plans
 * @subpackage Geneco_Plans/public
 * @author     YTL Digital Design [AL Latif Mohamad] <latif.mohamad@ytl.com>
 */
class Geneco_Plans_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version           The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name      = $plugin_name;
        $this->version          = $version;
        $this->genapi_options   = get_option('genapi_settings');
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/geneco-plans-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/geneco-plans-public.js', array( 'jquery' ), $this->version, true);

        $this->enqueue_js_variable_data();
    }

    /**
     * Register the shortcode for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function register_plan_shortcode()
    {
        return $this->plans_html();
    }

    /**
     * Display the plans HTML rendered from the plans data for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function plans_html()
    {
        require_once plugin_dir_path(dirname(__FILE__)).'public/partials/geneco-plans-display.php';
    }

    /**
     * Localize the script for js variable usage
     * 
     * @since    1.0.2
     */
    public function enqueue_js_variable_data()
    {
        $geneco_obj     = ['planData' => [], 'ratesData' => [], 'b2bPlanData' => []];
        $plans_data     = get_option('genapi_plans_data');
        $b2b_plans_data = get_option('genapi_b2b_plans_data');

        if ($plans_data) {
            $plans      = json_decode(unserialize($plans_data));
            $rates_pull = geneco_pull_rates_api($plans);
            $b2b_plans  = json_decode(unserialize($b2b_plans_data));
            $b2b_rates_pull = geneco_pull_b2b_rates_api($b2b_plans);
            
            $geneco_obj['planData']     = $plans;
            $geneco_obj['ratesData']    = $rates_pull;
            $geneco_obj['b2bPlanData']  = $b2b_plans;
            $geneco_obj['b2bRatesData'] = $b2b_rates_pull;
        }

        wp_localize_script($this->plugin_name, 'genecoObj', $geneco_obj);
    }

    /**
     * Localize the script for js variable usage
     * 
     * @since    1.2.0
     */
    public function register_b2b_plan_shortcode()
    {
        return $this->b2b_plans_html();
    }

    /**
     * Display the plans HTML rendered from the plans data for the public-facing side of the site.
     *
     * @since    1.2.0
     */
    public function b2b_plans_html()
    {
        require_once plugin_dir_path(dirname(__FILE__)).'public/partials/geneco-b2b-plans-display.php';
    }
}
