<?php

/**
 * Fired during plugin uninstall
 *
 * @link       https://www.ytl.com/technology.asp
 * @since      1.0.0
 *
 * @package    Geneco_Plans
 * @subpackage Geneco_Plans/includes
 */

/**
 * Fired during plugin uninstall.
 *
 * This class defines all code necessary to run during the plugin's uninstallation.
 *
 * @since      1.0.0
 * @package    Geneco_Plans
 * @subpackage Geneco_Plans/includes
 * @author     YTL Digital Design [AL Latif Mohamad] <latif.mohamad@ytl.com>
 */
class Geneco_Plans_Uninstall
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function uninstall()
    {
        $prefix	= (defined(GENAPI_PREFIX)) ? GENAPI_PREFIX : 'genapi_';

        delete_option($prefix."settings");
        delete_option($prefix."plans_data");
        delete_option($prefix."updated_at");
    }
}
