<?php

/**
 * Fired during plugin activation
 *
 * @link       https://truvoicer.co.uk
 * @since      1.0.0
 *
 * @package    Tru_Fetcher
 * @subpackage Tru_Fetcher/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Tru_Fetcher
 * @subpackage Tru_Fetcher/includes
 * @author     Michael <michael@local.com>
 */
class Tru_Fetcher_Endpoints
{

	const LISTINGS_FILTERS = [
		"NAME" => "tru_fetcher_listings",
		"OVERRIDE" => "show_filters",
		"OVERRIDE_ARRAY" => "filters",
		"FILTERS_LIST" => "listings_filters",
	] ;

    private $namespace = "wp/v2";
    private $apiPostResponse;

    public function __construct()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/ApiPostResponse.php';
        $this->apiPostResponse = new ApiPostResponse();
    }

    public function register_routes()
    {
        register_rest_route($this->namespace, '/page/(?<page_name>[\w-]+)', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, "getPageBySlug"]
        ));
        register_rest_route($this->namespace, '/menu/(?<menu_name>[\w-]+)', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, "getMenuByName"]
        ));
        register_rest_route($this->namespace, '/sidebar/(?<sidebar_name>[\w-]+)', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, "getSidebar"]
        ));
        register_rest_route($this->namespace, '/site/config', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, "getSiteConfig"]
        ));
    }

    public function getSidebar($request)
    {
        $sidebarName = (string)$request["sidebar_name"];

        if (!isset($sidebarName)) {
            return $this->showError('request_missing_parameters', "Sidebar name doesn't exist in request");
        }
        $sidebarWidgets = wp_get_sidebars_widgets();
        if (!array_key_exists($sidebarName, $sidebarWidgets)) {
            return $this->showError('sidebar_invalid', "Sidebar doesn't exist.");
        }

        $sidebarArray = [];
        $sidebarArray = array_map(function ($item) {
            $array = [];
            $instanceNumber = substr($item, strpos($item, "-") + 1);
            $widgetInstanceName = str_replace(substr($item, strpos($item, "-")), "", $item);

            $widget_instances = get_option('widget_' . $widgetInstanceName);
            $widgetData = $widget_instances[$instanceNumber];
            $array[$widgetInstanceName] = $widgetData;
            if ($widgetInstanceName === "nav_menu") {
                if (array_key_exists("nav_menu", $widgetData)) {
                    $array[$widgetInstanceName]["menu_items"] = $this->getMenu($widgetData['nav_menu']);
                }
            }
            if ($widgetInstanceName === "listings_filter_widget") {
            	$widgetFields = get_fields('widget_' . $item);

	            $widgetFields[self::LISTINGS_FILTERS['FILTERS_LIST']] = $this->buildListingFilters($widgetFields[self::LISTINGS_FILTERS['FILTERS_LIST']]);
	            $array[$widgetInstanceName]                           = $widgetFields;
            }
            return $array;

        }, $sidebarWidgets[$sidebarName]);

        return rest_ensure_response($sidebarArray);
    }

    private function buildListingFilters($listingFiltersArray) {
	    return array_map(function ($widgetItem) {
		    if ($widgetItem['type'] == "list") {
			    $selectedList = $widgetItem['list'];
			    $widgetItem['list'] = false;
			    if ($selectedList) {
				    $widgetItem['list'] = get_field( "list_items", $selectedList->ID );
			    }
			    return $widgetItem;
		    }
		    return $widgetItem;
	    }, $listingFiltersArray);
    }

    public function getMenuByName($request) {
        $menuName = (string)$request["menu_name"];
        if (!isset($menuName)) {
            return $this->showError('request_missing_parameters', "Menu name doesn't exist in request");
        }

        $menuArray = $this->getMenu($menuName);
        return rest_ensure_response($menuArray);
    }

    public function getMenu($menu)
    {
        $getMenu = wp_get_nav_menu_items($menu);
        if (!$getMenu) {
            return $this->showError('menu_not_found', "Menu doesn't exist.");
        }

        $menuArray = [];
        $i = 0;

        foreach ($getMenu as $item) {
            if ((int)$item->menu_item_parent === 0) {
                $menuArray[$i]["menu_item"] = $item;
            }
            foreach ($getMenu as $subItem) {
                if ((int)$subItem->menu_item_parent == (int)$item->ID) {
                    $menuArray[$i]["menu_sub_items"][] = $subItem;
                }
            }
            $i++;
        }
        return $menuArray;
    }

    public function getPageBySlug($request)
    {
        $pageName = (string)$request['page_name'];
        if (!isset($pageName)) {
            return $this->showError('request_missing_parameters', "Page name doesn't exist in request");
        }
        if ($pageName === "home") {
            $pageId = get_option("page_on_front");
            $getPage = get_post($pageId);
        } else {
	        $getPage = get_posts(
                array(
                    'name' => $pageName,
                    'post_type' => 'page',
                    'numberposts' => 1
                )
            )[0];
        }
	    $this->apiPostResponse = $this->buildApiResponse($getPage);
//        return;
        // Return the product as a response.
        return rest_ensure_response($this->apiPostResponse);
    }

    private function buildApiResponse($page) {
    	//Blocks data must be set first
    	$blocksData = $this->buildListingsBlock($page->post_content);
    	$pageObject = $this->buildPageObject($page);
	    $this->apiPostResponse->setPost($pageObject);
	    $this->apiPostResponse->setSiteConfig($this->getSiteConfig());
	    if (count($blocksData) !== 0) {
		    $this->apiPostResponse->setListingsblockdata( $blocksData );
	    }
	    return $this->apiPostResponse;
    }

    private function buildPageObject($page) {
	    $page->seo_title = $page->post_title . " - " . get_bloginfo( 'name' );
	    $page->post_content = apply_filters("the_content", $page->post_content);
	    return $page;
    }

	private function getSiteConfig() {
    	return [
    	    "admin_email" => get_option("admin_email"),
    	    "blogname" => get_option("blogname"),
    	    "blogdescription" => get_option("blogdescription"),
    	    "blog_charset" => get_option("blog_charset"),
    	    "date_format" => get_option("date_format"),
    	    "default_category" => get_option("default_category"),
    	    "home" => get_option("home"),
    	    "siteurl" => get_option("siteurl"),
    	    "posts_per_page" => get_option("posts_per_page"),
	    ];
	}

    private function getAcfBlockData($postContent) {
	    $blocks = parse_blocks($postContent);
	    $blocksDataArray = array();
	    foreach($blocks as $block){
			if (!array_key_exists("data", $block['attrs'])) {
				continue;
			}
		    acf_setup_meta( $block['attrs']['data'], $block['attrs']['id'], true );
		    $fields = get_fields();
		    if ($fields) {
		    	$blockName = str_replace("acf/", "", $block['blockName']);
		    	$blockName = str_replace("-", "_", $blockName);
			    $blocksDataArray[ $blockName ] = $fields;
		    }
		    acf_reset_meta( $block['attrs']['id'] );
	    }
	    return $blocksDataArray;
    }
	private function buildListingsBlock($postContent) {
		$blocksArray = $this->getAcfBlockData($postContent);
		if(array_key_exists(self::LISTINGS_FILTERS['NAME'], $blocksArray)) {
			$listingsArray = $blocksArray[self::LISTINGS_FILTERS['NAME']];
			if ( array_key_exists( self::LISTINGS_FILTERS['OVERRIDE'], $listingsArray ) &&
			     $listingsArray[self::LISTINGS_FILTERS['OVERRIDE']] ) {
				$blocksArray[self::LISTINGS_FILTERS['NAME']]
				[self::LISTINGS_FILTERS['OVERRIDE_ARRAY']]
				[self::LISTINGS_FILTERS['FILTERS_LIST']] =
					$this->buildListingFilters( $listingsArray[self::LISTINGS_FILTERS['OVERRIDE_ARRAY']]
					[self::LISTINGS_FILTERS['FILTERS_LIST']] );
			}
		}
		return $blocksArray;
	}

    private function showError($code, $message)
    {
        return new WP_Error($code,
            esc_html__($message, 'my-text-domain'),
            array('status' => 404));
    }
}
