<?php
/*
Plugin Name: Advanced Custom Fields: Icon Picker
Plugin URI: https://bitbucket.org/adeliomgit/wp-acf-icon-picker/
Description: Allows you to pick an icon from a predefined list
Version: 1.7.0
Author: Adeliom rebuild from Houke de Kwant
Author URI: ttps://github.com/houke/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
GitHub Plugin URI: https://bitbucket.org/adeliomgit/wp-acf-icon-picker/
GitHub Branch: master
*/


if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('acf_plugin_icon_picker') ) :

    class acf_plugin_icon_picker {

        function __construct() {

            $this->settings = array(
                'version'	=> '1.7.0',
                'url'		=> plugin_dir_url( __FILE__ ),
                'path'		=> plugin_dir_path( __FILE__ )
            );

            add_action('acf/include_field_types', 	array($this, 'include_field_types'));
        }

        function include_field_types( $version = false ) {
            include_once('src/fields/acf-icon-picker.php');
        }

    }

    new acf_plugin_icon_picker();

endif;




if (!function_exists('acf_icon_picker')) {
    /**
     * Get an acf icon picker field settings array.
     *
     * @param array $config
     *
     * @return \WordPlate\Acf\Field
     */
    function acf_icon_picker(array $config): \WordPlate\Acf\Field
    {
        $config = array_merge($config, ['type' => 'icon-picker']);

        return new \WordPlate\Acf\Field($config, ['name']);
    }
}