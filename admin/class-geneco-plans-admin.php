<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.ytl.com/technology.asp
 * @since      1.0.0
 *
 * @package    Geneco_Plans
 * @subpackage Geneco_Plans/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Geneco_Plans
 * @subpackage Geneco_Plans/admin
 * @author     YTL Digital Design [AL Latif Mohamad] <latif.mohamad@ytl.com>
 */
class Geneco_Plans_Admin
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
     * The prefix for variables.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $prefix     The prefix for variables or options to be used.
     */
    private $prefix;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version, $prefix)
    {
        $this->plugin_name 	= $plugin_name;
        $this->version 		= $version;
        $this->prefix		= $prefix;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name.'-admin', plugin_dir_url(__FILE__) . 'css/geneco-plans-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        // if ('tools_page_geneco-plans' != $hook) {
        //     return;
        // }
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/geneco-plans-admin.js', array( 'jquery' ), $this->version, false);
    }

    /**
     * Register the settings page for the admin area.
     *
     * @since 	 1.0.0
     */
    public function register_settings_page()
    {
        add_menu_page(
            __('Geneco Plans', 'geneco-plans'), 			// page title
            __('Geneco Plans', 'geneco-plans'), 			// menu title
            'manage_options', 								// capability
            'geneco-plans', 								// menu slug
            array($this, 'display_settings_page'), 			// callable function
            'dashicons-open-folder', 						// icon url - https://developer.wordpress.org/resource/dashicons
            65												// menu position
        );
    }

    /**
     * Display the settings page content for the page we have created.
     *
     * @since 	 1.0.0
     */
    public function display_settings_page()
    {
        require_once plugin_dir_path(dirname(__FILE__)).'admin/partials/geneco-plans-admin-display.php';
    }

	/**
	 * Register the settings for our settings page.
	 * 
	 * @since 	 1.0.0
	 */
    public function register_settings()
    {
        $settings_id 		= $this->prefix."settings";
        $settngs_section_id	= $this->prefix."settings_section";

        register_setting(
            $settings_id,
            $settings_id,
            array($this, 'sandbox_register_setting')
        );

        add_settings_section(
            $settngs_section_id,
            __('Settings', 'geneco-plans'),
            array($this, 'sandbox_add_settings_section'),
            $settings_id
        );

        add_settings_field(
            $this->prefix."api_url",
            __('Geneco API URL', 'geneco-plans'),
            array($this, 'sandbox_add_settings_field_input_url'),
            $settings_id,
            $settngs_section_id,
            array(
                'label_for'		=> $this->prefix."api_url",
                'default' 		=> '',                      // https://jsonplaceholder.typicode.com/posts
                'description'	=> __('The URL for the Geneco Plans API from CRM', 'geneco-plans')
            )
        );

        add_settings_field(
            $this->prefix."api_username",
            __('Geneco API Username', 'geneco-plans'),
            array($this, 'sandbox_add_settings_field_input_text'),
            $settings_id,
            $settngs_section_id,
            array(
                'label_for'		=> $this->prefix."api_username",
                'default' 		=> '',
                'description'	=> __('The username used for Geneco Plans API to retrieve the plans', 'geneco-plans')
            )
        );

        add_settings_field(
            $this->prefix."api_password",
            __('Geneco API Password', 'geneco-plans'),
            array($this, 'sandbox_add_settings_field_input_text'),
            $settings_id,
            $settngs_section_id,
            array(
                'label_for'		=> $this->prefix."api_password",
                'default' 		=> '',
                'description'	=> __('The password used for Geneco Plans API to retrieve the plans', 'geneco-plans')
            )
        );
    }

    /**
     * Sandbox our settings.
     *
     * @since 	 1.0.0
     */
    public function sandbox_register_setting($input)
    {
        $new_input 	    = array();
        $valid_submit   = true;

        if (isset($input)) {
            foreach ($input as $key => $value) {
                $new_input[$key] = sanitize_text_field($value);
                if ($value == '' || !$valid_submit) {
                    $valid_submit   = false;
                }
            }
        }

        if ($valid_submit) {
		    add_settings_error('genapi_messages', 'genapi_message', __('API Information has been saved! Please go to <a href="?page=geneco-plans-action">Pull Plans</a> page to pull the latest plans.', 'geneco-plans'), 'updated');
        } else {
            add_settings_error('genapi_messages', 'genapi_message', __('Please fill up the API URL & API Key fields!', 'geneco-plans'), 'error');
        }

        return $new_input;
    }

    /**
     * Sandbox our section for the settings.
     *
     * @since 	 1.0.0
     */
    public function sandbox_add_settings_section()
    {
        return;
    }

    /**
     * Sandbox our inputs with type url
     *
     * @since 	 1.0.0
     */
    public function sandbox_add_settings_field_input_url($args)
    {
        $field_id 		= $args['label_for'];
        $field_default	= $args['default'];
        $field_desc		= esc_html($args['description']);

        $options 	= get_option($this->prefix."settings");
        $option 	= $field_default;

        if (!empty($options[$field_id])) {
            $option = $options[$field_id];
        }

        $input_id 	= $this->prefix."settings[$field_id]";
        $input_name	= $this->prefix."settings[$field_id]";
        $input_value= esc_attr($option);
        $html_input = "	<input type='url' class='regular-text' id='$input_id' name='$input_name' value='$input_value' />
						<p class='description'><em>$field_desc</em></p>";

        echo $html_input;
    }

    /**
     * Sandbox our inputs with type text
     *
     * @since 	 1.0.0
     */
    public function sandbox_add_settings_field_input_text($args)
    {
        $field_id 		= $args['label_for'];
        $field_default	= $args['default'];
        $field_desc		= esc_html($args['description']);

        $options 	= get_option($this->prefix."settings");
        $option 	= $field_default;

        if (!empty($options[$field_id])) {
            $option = $options[$field_id];
        }

        $input_id 	= $this->prefix."settings[$field_id]";
        $input_name	= $this->prefix."settings[$field_id]";
        $input_value= esc_attr($option);
        $html_input = "	<input type='text' class='regular-text' id='$input_id' name='$input_name' value='$input_value' />
						<p class='description'><em>$field_desc</em></p>";

        echo $html_input;
    }

    /**
     * Register the action to pull plans page for the admin area.
     *
     * @since 	 1.0.0
     */
    public function register_action_page()
    {
        add_submenu_page(
            'geneco-plans',                             // parent slug
            __('Geneco Plans - Pull Plans', 'geneco-plans'),      // page title
            __('Pull Plans', 'geneco-plans'),      // menu title
            'manage_options',                        // capability
            'geneco-plans-action',                          // menu_slug
            array( $this, 'display_action_page' )  // callable function
        );
    }

    /**
     * Display the action page content for the page we have created.
     *
     * @since 	 1.0.0
     */
    public function display_action_page()
    {
        if (isset($_POST['trigger_pull_data']) && check_admin_referer('pull_plans_btn_clicked')) {
            $genapi_options	= get_option($this->prefix."settings");
            if (!empty($genapi_options['genapi_api_url'])) {
                $api_url	= $genapi_options['genapi_api_url'];
            }
            if (!empty($genapi_options['genapi_api_username'])) {
                $username 	= $genapi_options['genapi_api_username'];
            }
            if (!empty($genapi_options['genapi_api_password'])) {
                $password 	= $genapi_options['genapi_api_password'];
            }

            if (isset($api_url) && isset($username) && isset($password)) {
				$this->geneco_pull_plans_api($api_url, $username, $password);
            } else {
                add_settings_error('genapi_messages', 'genapi_message', __('Could not pull plans. Please check the API information in <a href="?page=geneco-plans">API Information Settings</a> page.', 'geneco-plans'), 'error');
            }
        }
        require_once plugin_dir_path(dirname(__FILE__)).'admin/partials/geneco-plans-admin-action-display.php';
    }

    /**
     * Function to pull the plans through API
     * 
     * @since    1.0.0
     */
    public function geneco_pull_plans_api($api_url = null, $username = null, $password = null)
    {
        $args       = [
            'headers'   => [
                'Authorization' => 'Basic '.base64_encode($username.':'.$password)
            ] 
        ];
        $response 	= wp_remote_post($api_url, $args);
        $res_code   = $response['response']['code'];
        $data       = $response['body'];

        if (is_wp_error($response)) {
            $error_message  = $response->get_error_message();
            add_settings_error('genapi_messages', 'genapi_message', __('Something went wrong: ', 'geneco-plans').$error_message, 'error');
        } else if ($res_code != 200) {
            $errors         = json_decode($data, true);

            if (isset($errors['Message'])) {
                $error_message  = $errors['Message'];
            } else if (isset($errors[0]) && isset($errors[0]['errordescription'])) {
                $error_message  = $errors[0]['errordescription'];
            } else {
                $error_message  = $data;
            }
            add_settings_error('genapi_messages', 'genapi_message', __('Something went wrong: ', 'geneco-plans')."<strong><em>$error_message</em></strong>", 'error');
        } else {
            update_option('genapi_plans_data', serialize($data), false);
            update_option('genapi_updated_at', strtotime(current_time('mysql')), false);
    
            add_settings_error('genapi_messages', 'genapi_message', __('Geneco plans have been successfully pulled and saved!', 'geneco-plans'), 'updated');
    
            if (function_exists('w3tc_flush_all')) {
                w3tc_flush_all();
            }
        }
    }
}
