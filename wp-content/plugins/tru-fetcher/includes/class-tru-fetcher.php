<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://truvoicer.co.uk
 * @since      1.0.0
 *
 * @package    Tru_Fetcher
 * @subpackage Tru_Fetcher/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Tru_Fetcher
 * @subpackage Tru_Fetcher/includes
 * @author     Michael <michael@local.com>
 */
class Tru_Fetcher {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Tru_Fetcher_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'TRU_FETCHER_VERSION' ) ) {
			$this->version = TRU_FETCHER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'tru-fetcher';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_endpoints();
		$this->define_post_types();
		$this->define_blocks();
		$this->define_menus();
		$this->define_sidebars();
		$this->define_widgets();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Tru_Fetcher_Loader. Orchestrates the hooks of the plugin.
	 * - Tru_Fetcher_i18n. Defines internationalization functionality.
	 * - Tru_Fetcher_Admin. Defines all hooks for the admin area.
	 * - Tru_Fetcher_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tru-fetcher-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tru-fetcher-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-tru-fetcher-admin.php';

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tru-fetcher-endpoints.php';

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tru-fetcher-post-types.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-tru-fetcher-public.php';


		$this->loader = new Tru_Fetcher_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Tru_Fetcher_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Tru_Fetcher_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Tru_Fetcher_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Tru_Fetcher_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	private function define_endpoints() {
        $truFetcherEndpoints = new Tru_Fetcher_Endpoints();
        $this->loader->add_action( 'rest_api_init', $truFetcherEndpoints, "register_routes" );
	}

	private function define_post_types() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post_types/listings_post_type.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post_types/filter_lists_post_type.php';
	}

	private function define_blocks() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'blocks/listings.php';
    }

    private function define_menus() {
	    $this->loader->add_action("init", $this, "register_nav_menus");
    }

    public function register_nav_menus() {
        register_nav_menus(
            array(
                'sidebar-menu' => __( 'Sidebar Menu' ),
                'extra-menu' => __( 'Extra Menu' )
            )
        );
    }

    private function define_widgets() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/class-tru-fetcher-listings-widget.php';
    }

    private function define_sidebars() {
	    $this->loader->add_action("init", $this, "register_sidebars");
    }

    public function register_sidebars() {
        $args = array(
            'name'          =>  __( 'Left Sidebar' ),
            'id'            => "left-sidebar",
            'description'   => '',
            'class'         => '',
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget'  => "</li>\n",
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => "</h2>\n",
        );
        register_sidebar( $args );
    }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Tru_Fetcher_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
