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
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        // wp_enqueue_style($this->plugin_name."-bootstrap", plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/geneco-plans-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        // wp_enqueue_script($this->plugin_name."-bootstrap", plugin_dir_url(__FILE__) . 'js/bootstrap.bundle.min.js', array( 'jquery' ), $this->version, false);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/geneco-plans-public.js', array( 'jquery' ), $this->version, false);
    }

    /**
     * Register the shortcode for the public-facing side of the site.
     * 
     * @since    1.0.0
     */
    public function register_shortcode()
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
}
