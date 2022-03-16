<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.ytl.com/technology.asp
 * @since      1.0.0
 *
 * @package    Geneco_Plans
 * @subpackage Geneco_Plans/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Geneco_Plans
 * @subpackage Geneco_Plans/includes
 * @author     YTL Digital Design [AL Latif Mohamad] <latif.mohamad@ytl.com>
 */
class Geneco_Plans_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        $prefix	= (defined(GENAPI_PREFIX)) ? GENAPI_PREFIX : 'genapi_';
        
        add_option($prefix."plans_data", '');
        add_option($prefix."updated_at", '');
        add_option($prefix."b2b_plans_data", '');
        add_option($prefix."b2b_updated_at", '');
    }
}
