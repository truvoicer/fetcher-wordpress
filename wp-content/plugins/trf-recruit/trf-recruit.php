<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://truvoicer.co.uk
 * @since             1.0.0
 * @package           Tru_Fetcher
 *
 * @wordpress-plugin
 * Plugin Name:       TRF Recruit
 * Plugin URI:        https://truvoicer.co.uk
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Michael
 * Author URI:        https://truvoicer.co.uk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tru-fetcher
 * Domain Path:       /languages
 * Requires Plugins:  Tru_Fetcher_Base_Plugin
 */

use TrfRecruit\Includes\Trf_Recruit;
use TrfRecruit\Includes\Trf_Recruit_Activator;
use TrfRecruit\Includes\Trf_Recruit_Deactivator;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
const TRF_RECRUIT_PLUGIN_NAME = 'trf-recruit';
define( 'TRF_RECRUIT_VERSION', '1.0.0' );
define( 'TRF_RECRUIT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TRF_RECRUIT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PARENT_PLUGIN_DIR',  plugin_dir_path( __DIR__ ) . 'tru-fetcher-plugin/');

require_once PARENT_PLUGIN_DIR . 'includes/class-tru-fetcher-auto-loader.php';
spl_autoload_register(
    [
        (new Tru_Fetcher_Auto_Loader())
            ->setConfig([
                [
                    'app_name' => 'TruFetcher',
                    'root_dir' => PARENT_PLUGIN_DIR
                ],
                [
                    'app_name' => 'TrfRecruit',
                    'root_dir' => TRF_RECRUIT_PLUGIN_DIR
                ]
            ]),
        "init"
    ]
);

//register_activation_hook( __FILE__, [new Trf_Recruit_Activator(), 'activate'] );
//register_deactivation_hook( __FILE__, [new Trf_Recruit_Deactivator(), 'deactivate'] );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-trf-recruit.php';


$plugin = new Trf_Recruit();
$plugin->run();
