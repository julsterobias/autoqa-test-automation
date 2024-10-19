<?php 
/**
*
*
*
* Plugin Name: AutoQA
* Description: A plugin that will automate the testing of your website without the need of coding.
* Author:      Juls Terobias
* Plugin Type: Test Tool
* Author URI: https://julsterobias.github.io/autoqa/author/
* Plugin URI: https://julsterobias.github.io/autoqa
* Version: 0.9.6
* Text Domain: autoqa-test-automation
* License:     GPLv3
* License URI: https://www.gnu.org/licenses/gpl.html
*
*
*
*/


defined( 'ABSPATH' ) or die( 'No access area' );
define('CAUTO_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('CAUTO_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('CAUTO_PLUGIN_VERSION','0.9.6');
define('CAUTO_PLUGIN_VERSION_CODE','Beta');
define('CAUTO_NAMESPACES', ['includes','admin/includes']);
define('CAUTO_RUNNER_IS_RUNNING', false);


/**
*
*
* Load text domain from languages
* @since 1.0.0
*
*
*/
add_action( 'init', 'cauto_load_text_domain' );

function cauto_load_text_domain() {
	load_plugin_textdomain( 'autoqa-test-automation', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}


/**
 * 
 * cauto_activate_plugin
 * trigger code during activation
 * @since 1.0.0
 * 
 */
register_activation_hook( __FILE__, 'cauto_activate_plugin' );

if (!function_exists('cauto_activate_plugin')) {

	function cauto_activate_plugin()
	{
        //do all activate hooks here
	}
}

/**
 * 
 * 
 * cauto_deactivate_plugin
 * trigger code during deactivation
 * @since 1.0.0
 * 
 * 
 */
register_deactivation_hook( __FILE__, 'cauto_deactivate_plugin' );

if (!function_exists('cauto_deactivate_plugin')) {
	function cauto_deactivate_plugin()
	{
        //do all inactivation hooks here
	}
}

/**
 * 
 * 
 * autoloader
 * load required files
 * @since 1.0.0
 * @param
 * @return
 * 
 * 
 */
function cauto_init_classes()
{
    //load in priority
    include_once 'includes/class-utils.php';

    foreach (CAUTO_NAMESPACES as $path) {
        $fullpath = CAUTO_PLUGIN_PATH.$path;
        if (is_dir($fullpath)) {
            //get files
            $files = scandir($fullpath);
            
            if (!empty($files)) { 
                foreach ($files as $file) {
                    // Check if it's a file
                    $fullfile = $fullpath . '/' . $file;
                    if (is_file($fullfile)) {
                        $file_extension = pathinfo($file, PATHINFO_EXTENSION); // Get the file extension
                        // Load the php only
                        if ($file_extension === 'php') {
                            include_once $fullfile;
                        }
                    } 
                }
            } else {
                error_log('CAUTOMATION_ERR 01: File is not found');
            }

        } else {
            error_log(sprintf("CAUTOMATION_ERR 02: %s not found", $path));
        } 

    }


}


/**
 * 
 * cauto_plugin_loaded
 * load plugin hook
 * @since 1.0.0
 * 
 */
add_action('plugins_loaded', 'cauto_plugin_loaded');
function cauto_plugin_loaded(){
    if (function_exists('cauto_init_classes')) {
        cauto_init_classes();
        new cauto\admin\includes\cauto_admin; 
    }
}
?>