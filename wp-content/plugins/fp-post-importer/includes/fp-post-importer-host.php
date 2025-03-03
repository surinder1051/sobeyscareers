<?php

defined( 'ABSPATH' ) or die( 'Access forbidden!' );

class FP_Post_Importer_Host {

    const FP_TRASHED_POST_OPTION_PREFIX = 'fppi_deleted_ids_';

    var $plugin_type;
    var $exportable_post_types = array();

    /**
	 * Singleton instance.
	 *
	 * @var self
	 */
	private static $instance;

    /**
	 * Singleton get.
	 * @return void
	 */
	public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct() {
        $this->plugin_type = 'host';
        $this->exportable_post_types = FP_Post_Importer_Admin::get_exportable_cpt();
        $this->init_actions();
        // Clears any cron jobs if this was ever in client mode.
        wp_clear_scheduled_hook(FP_Post_Importer_Admin::SCHEDULED_HOOK);
    }
    
    /**
     * Setup action hooks/filters for host.
     *
     * @return void
     */
    public function init_actions() {
        add_action( 'template_redirect', array($this, 'maybe_redirect_api'), 0 );
        add_filter( 'register_post_type_args', array($this, 'enable_rest_api_cpt'), 10, 2 );
        add_action( 'rest_api_init', array($this, 'create_host_rest_endpoints' ) );
        add_filter( 'determine_current_user', array(__CLASS__, 'json_basic_auth_handler'), 20 );
        add_filter( 'rest_authentication_errors', array(__CLASS__, 'json_basic_auth_error') );
        add_filter( 'private_title_format', array(__CLASS__, 'remove_private_title_prefix') );
        add_action( 'wp_trash_post', array($this, 'trash_post'), 10, 1 );
        add_action( 'untrash_post', array($this, 'restore_post'), 10, 1 );
        // add_action( 'before_delete_post', array($this, 'trash_post'), 10, 1 );
    }

    /**
     * If the host site is running polylang and we have request with prefixed URL, which polylang does not support, 
     * redirect to the lang param equivalent to retain support without having update all the clients.
     *
     * @return void
     */
    public function maybe_redirect_api() {
        if ( ! function_exists('pll_the_languages') || empty( $_SERVER['REQUEST_URI'] ) ) {
            return;
        }

        if (preg_match('#^\/(en|fr)\/wp\-json\/#i', $_SERVER['REQUEST_URI'], $matches) ) {
            $lang = $matches[1];
            $new_path = preg_replace('#^\/(en|fr)\/#', '', $_SERVER['REQUEST_URI']);
            $new_url = add_query_arg( ['lang' => $lang], get_site_url(null, $new_path) );
            wp_redirect($new_url);
            exit;
        } 
    }

    /**
     * Log when a post is deleted on the host site so the client site can delete it as well.
     *
     * @param int $post_id
     * @return void
     */
    public function trash_post( $post_id ) {
        // Post is being deleted permanantly, not tracking this atm.
        //if (current_filter() == 'before_delete_post') {
        //}
        $post_type = get_post_type($post_id);
        if (empty($post_type) || !in_array($post_type, $this->exportable_post_types)) {
            return;
        }

        $deleted_post_id_key = self::FP_TRASHED_POST_OPTION_PREFIX . $post_type;
        $deleted_post_ids = get_option( $deleted_post_id_key, array() );
        $deleted_post_ids = is_array($deleted_post_ids) ? $deleted_post_ids : array();

        // Already exists.
        if (!in_array($post_id, $deleted_post_ids)) {
            $deleted_post_ids[] = (int) $post_id;
            update_option($deleted_post_id_key, $deleted_post_ids, 'no');
        }
        return;
    }

    /**
     * Log when a post is restored on the host site so the client site can restore it as well.
     *
     * @param int $post_id
     * @return void
     */
    public function restore_post( $post_id ) {
        $post_type = get_post_type($post_id);
        if (empty($post_type) || !in_array($post_type, $this->exportable_post_types)) {
            return;
        }

        $deleted_post_id_key = self::FP_TRASHED_POST_OPTION_PREFIX . $post_type;
        $deleted_post_ids = get_option( $deleted_post_id_key, array() );
        $deleted_post_ids = is_array($deleted_post_ids) ? $deleted_post_ids : array();

        $key = array_search( (int) $post_id, $deleted_post_ids);
        if (false !== $key) {
            unset($deleted_post_ids[$key]);
            update_option($deleted_post_id_key, $deleted_post_ids, 'no');
        }
        return;
    }

    /**
     * Remove the `private` prefix from the title for the REST API output.
     *
     * @param string $format
     * @return void
     */
    public static function remove_private_title_prefix( $format = '%s' ) {
        return '%s';
    }

    public static function json_basic_auth_handler( $user ) {
        global $wp_json_basic_auth_error;
        $wp_json_basic_auth_error = null;
        // Don't authenticate twice
        if ( ! empty( $user ) ) {
            return $user;
        }

        // Ensure you are in a READONLY endpoint.
        if ( ! isset($_SERVER['REQUEST_METHOD']) || ($_SERVER['REQUEST_METHOD'] !== 'GET') ) {
            return $user;
        }

        $site_token = FP_Post_Importer_Admin::get_host_token();
        // Ensure you are in a READONLY endpoint.
        if ( empty($site_token) || !isset($_GET[ FP_POST_API_TOKEN_KEY ]) || ($_GET[ FP_POST_API_TOKEN_KEY ] !== $site_token) ) {
            return $user;
        }

        // Otherwise, authenticate.
        $users = get_users( [ 'role__in' => [ 'administrator' ], 'number' => 1 ] );
        $wp_user = count($users) ? array_shift($users) : false;
        $wp_json_basic_auth_error = true;
        return isset($wp_user->ID) ? $wp_user->ID : $user;
    }
    
    
    public static function json_basic_auth_error( $error ) {
        // Passthrough other errors
        if ( ! empty( $error ) ) {
            return $error;
        }
        global $wp_json_basic_auth_error;
        return $wp_json_basic_auth_error;
    }

    /**
     * Return vars to be used client-side.
     *
     * @return array
     */
    static function get_localized_vars() {
        return array(
            'endpoints' => array(
                'settings' => get_rest_url(null, FP_POST_IMPORTER_API_NAMESPACE . FP_POST_IMPORTER_API_HOST_SETTINGS ),
            ),
        );
    }

    /**
     * Register tax/meta fields for default REST API.
     * Register API endpoints for the host settings.
     *
     * @return void
     */
    public function create_host_rest_endpoints() {
        foreach( $this->exportable_post_types as $post_type ) {
            register_rest_field( $post_type, 'meta-fields', array(
                'get_callback'    => array($this,'get_post_meta_for_api'),
                )
            );
            register_rest_field( $post_type, 'featured-image', array(
                'get_callback'    => array($this,'get_image_for_api'),
                )
            );
            register_rest_field( $post_type, 'media-gallery', array(
                'get_callback'    => array($this,'get_image_gallery_for_api'),
                )
            );
            register_rest_field( $post_type, 'tax-fields', array(
                'get_callback'    => array($this,'get_tax_post_meta_for_api'),
                )
            );

            register_rest_field( $post_type, 'translation', array(
                'get_callback'    => array($this,'get_translated_id_for_api'),
                )
            );

            add_filter('rest_' . $post_type . '_query', array($this,'rest_api_filter_add_filter_param'), 10, 2);
        }

        $endpoints = array(
			FP_POST_IMPORTER_API_HOST_SETTINGS => array( //get posts
				array(
					'callback' => array( $this, 'export_host_settings' ),
					'methods'  => WP_REST_Server::READABLE,
					'args'     => array(),
					//'permission_callback' => array(__CLASS__, 'api_allowed'),
				),
            ),

            FP_POST_IMPORTER_API_HOST_DELETED_POSTS_IDS . '/(?P<posttype>[\w\-_]+)' => array( //get post ids
				array(
					'callback' => array( $this, 'get_deleted_post_ids' ),
					'methods'  => WP_REST_Server::READABLE,
					'args'   => array(
                        'posttype' => array(
                            'description' => __( 'Post type of the IDs to fetch'),
                            'type'        => 'string',
                        ),
                    ),
					'permission_callback' => array(__CLASS__, 'api_allowed'),
				),
            ),
            
            FP_POST_IMPORTER_API_HOST_ALL_POSTS_IDS . '/(?P<posttype>[\w\-_]+)' => array( //get post ids
				array(
					'callback' => array( $this, 'get_all_post_ids' ),
					'methods'  => WP_REST_Server::READABLE,
					'args'   => array(
                        'posttype' => array(
                            'description' => __( 'Post type of the IDs to fetch'),
                            'type'        => 'string',
                        ),
                    ),
					'permission_callback' => array(__CLASS__, 'api_allowed'),
				),
			),
        );	

		foreach ($endpoints as $path => $options) {
			register_rest_route( FP_POST_IMPORTER_API_NAMESPACE, $path, $options);
		}
    }

    public static function api_allowed( WP_REST_Request $request = null ) {
        if ($request === null) {
            $passed_token = !empty( $_REQUEST[ FP_POST_API_TOKEN_KEY ] ) ? $_REQUEST[ FP_POST_API_TOKEN_KEY ] : '';
        } else {
            $passed_token = $request->get_param( FP_POST_API_TOKEN_KEY );
        }
        
        $site_token = FP_Post_Importer_Admin::get_host_token();
        // Ensure you are in a READONLY endpoint.
        if ( ! empty($site_token) && ! empty($passed_token) && ($passed_token === $site_token) ) {
            return true;
        }

        return false;
    }

    /**
     * REST API callback to output list of delete post IDS.
     *
     * @param WP_Rest_Request $request
     * @return void
     */
    public function get_deleted_post_ids( WP_Rest_Request $request ) {
        $params = $request->get_params();
        $post_type = !empty( $params['posttype'] ) ? $params['posttype'] : '';
        $page = !empty( $params['page'] ) ? intval($params['page']) : 1;
        $per_page = !empty( $params['per_page'] ) ? intval($params['per_page']) : 1000;
		$offset = $page > 1 ? ($page - 1) * $per_page : 0;

        if ( !in_array($post_type, $this->exportable_post_types) ) {
            wp_send_json_error('Invalid exportable post type', 400);
        }        

        $deleted_post_id_key = self::FP_TRASHED_POST_OPTION_PREFIX . $post_type;
        $deleted_post_ids = get_option( $deleted_post_id_key, array() );
        $deleted_post_ids = is_array($deleted_post_ids) ? $deleted_post_ids : array();
        $total = count($deleted_post_ids);
        $deleted_post_ids = count($deleted_post_ids) ? array_slice($deleted_post_ids, $offset, $per_page) : array();
        

        $response = array(
            'ids' => $deleted_post_ids,
        );

        $total_page = count($deleted_post_ids);
        $num_of_pages = ceil( $total / $per_page );
        header("X-WP-Total: {$total}");
        header("X-WP-TotalPages: {$num_of_pages}");
        wp_send_json_success($response);
    }

    /**
     * REST API callback to output list of delete post IDS.
     *
     * @param WP_Rest_Request $request
     * @return void
     */
    public function get_all_post_ids( WP_Rest_Request $request ) {
        $params = $request->get_params();
        $post_type = !empty( $params['posttype'] ) ? $params['posttype'] : '';
        $page = !empty( $params['page'] ) ? intval($params['page']) : 1;
        $per_page = !empty( $params['per_page'] ) ? intval($params['per_page']) : -1;
        $taxonomies = !empty( $params['taxonomy'] ) ? $params['taxonomy'] : [];

        if ( !in_array($post_type, $this->exportable_post_types) ) {
            wp_send_json_error('Invalid exportable post type', 400);
        }        

        $args = array(
            'post_status' => 'any',
            'post_type' => $post_type,
            'posts_per_page' => $per_page,
            'fields' => 'ids',
            'suppress_filters' => false,
            'paged' => $page,
        );

        if (!empty($taxonomies)) {
            foreach($taxonomies as $taxonomy_name => $taxonomy_terms) {
                $args['tax_query'][] = array(
                    'taxonomy' => $taxonomy_name,   // taxonomy name
                    'field' => 'slug',           // term_id, slug or name
                    'terms' => explode(",", $taxonomy_terms), // term id, term slug or term name
                );
            }
            $args['tax_query']['relation'] = 'AND';
        }
        
        $post_ids = new WP_Query($args);
        $response = array(
            'count' => count($post_ids->posts),
            'ids' => $post_ids->posts,
        );
        header("X-WP-Total: {$post_ids->found_posts}");
        header("X-WP-TotalPages: {$post_ids->max_num_pages}");
        wp_send_json_success($response);
    }

    /**
     * Add the filter parameter
     *
     * @param  array           $args    The query arguments.
     * @param  WP_REST_Request $request Full details about the request.
     * @return array $args.
     **/
    public function rest_api_filter_add_filter_param( $args, $request ) {
        // Bail out if no filter parameter is set.
        if ( empty( $request['filter'] ) || ! is_array( $request['filter'] ) ) {
            return $args;
        }
        $filter = $request['filter'];
        if ( isset( $filter['posts_per_page'] ) && ( (int) $filter['posts_per_page'] >= 1 && (int) $filter['posts_per_page'] <= 100 ) ) {
            $args['posts_per_page'] = $filter['posts_per_page'];
        }
        global $wp;
        $vars = apply_filters( 'rest_query_vars', $wp->public_query_vars );
        // Allow valid meta query vars.
        $vars = array_unique( array_merge( $vars, array( 'meta_query', 'meta_key', 'meta_value', 'meta_compare', 'date_query' ) ) );
        foreach ( $vars as $var ) {
            if ( isset( $filter[ $var ] ) ) {
                $args[ $var ] = $filter[ $var ];
            }
        }
        return $args;
    }

    /**
     * Callback for /host/settings endpoint.
     *
     * @return void
     */
    public function export_host_settings( $request ) {
        $languages = [];
        // polylang
        if (function_exists('pll_the_languages')) {
            $languages = FP_Post_Importer_PLL\icl_get_languages('orderby=id&order=desc');
        } 
        else if (class_exists('SitePress')) {
            $languages = apply_filters( 'wpml_active_languages', array(), 'orderby=id&order=desc' );
        }
        
        $data = array(
            'exportable_cpt'    => $this->exportable_post_types,
            'languages'         => $languages,
        );
        wp_send_json_success( $data );
    }

    /**
     * Ensure all our exportables CPT are REST api enabled.
     *
     * @param array $args
     * @param string $post_type
     * @return array
     */
    public function enable_rest_api_cpt( $args, $post_type ) {
        if ( !in_array($post_type, $this->exportable_post_types) )
            return $args;
        
        // Ensure we have this CPT enabled for REST API
        $args['show_in_rest'] = true;
        return $args;            
    }

    /**
     * Callback to register post meta fields.
     *
     * @param object $object
     * @return array
     */
    public function get_post_meta_for_api( $object ) {
        if ( ! self::api_allowed() ) {
            return array();
        }
        $post_id = $object['id'];
        $post_meta = get_post_meta( $post_id );
        $pm = array();
        foreach ( $post_meta as $key => $value ) {
            $pm[$key] = maybe_unserialize( array_shift( $value ) );
        }
        return $pm;
    }

    /**
     * Callback to register full featured image gallery URLs & meta-data to avoid fetching from another REST api endpoint.
     *
     * @param object $object
     * @return array
     */
    public function get_image_gallery_for_api( $object ) {
        $post_id = $object['id'];
        $metadata = array();
        $gallery_images = [];
        $attachment_ids = get_post_meta( $post_id, FP_Post_Importer_Client::MEDIA_GALLERY_META, true );
        if (!empty($attachment_ids) && is_array($attachment_ids)) {
            foreach($attachment_ids as $attachment_id) :
                $attachment = get_post( $attachment_id );
                if (!isset($attachment->ID))
                    return array();

                $metadata = array(
                    'href' => get_permalink( $attachment->ID ),
                    'src' => wp_get_attachment_image_url( $attachment->ID, 'full' ),
                    'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
                    'caption' => $attachment->post_excerpt,
                    'description' => $attachment->post_content,
                    'title' => $attachment->post_title
                );
                $gallery_images[] = $metadata;
            endforeach;    
        }
        return $gallery_images;
    }

    /**
     * Callback to register full featured image URL & meta-data to avoid fetching from another REST api endpoint.
     *
     * @param object $object
     * @return array
     */
    public function get_image_for_api( $object ) {
        $post_id = $object['id'];
        $metadata = array();
        $attachment_id = get_post_thumbnail_id( $post_id );
        if (!empty($attachment_id)) {
            $attachment = get_post( $attachment_id );
            if (!isset($attachment->ID))
                return array();

            $metadata = array(
                'href' => get_permalink( $attachment->ID ),
                'src' => wp_get_attachment_image_url( $attachment->ID, 'full' ),
                'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
                'caption' => $attachment->post_excerpt,
                'description' => $attachment->post_content,
                'title' => $attachment->post_title
            );
        }
        return $metadata;
    }

    /**
     * Callback to register all taxonomy post related data.
     *
     * @param object $object
     * @return array
     */
    public function get_tax_post_meta_for_api( $object ) {
        global $sitepress;
        $post_id = $object['id'];
        
        if (!empty($sitepress)) {
            remove_filter( 'get_term', array( $sitepress, 'get_term_adjust_id' ), 1 );
            $current_post_details = wpml_get_language_information($post_id);
            if (!empty($current_post_details['language_code'])) {
                do_action( 'wpml_switch_language', $current_post_details['language_code'] );
            }
        } else if (function_exists('pll_the_languages')) {
            $current_post_details = FP_Post_Importer_PLL\wpml_get_language_information($post_id);
            if (!empty($current_post_details['language_code'])) {
                do_action( 'wpml_switch_language', $current_post_details['language_code'] );
            }
        }
        

        $post_type = get_post_type($post_id);
        $cpt_taxonomies = get_object_taxonomies( $post_type );

        $taxonomies = array();
        foreach( $cpt_taxonomies as $cpt_taxonomy ) {
            
            $categories = wp_get_object_terms($post_id, $cpt_taxonomy);
            $terms = array();
            $terms['type'] = is_taxonomy_hierarchical( $cpt_taxonomy ) ? 'hierarchical' : 'tag';
            $terms['terms'] = array();
            $terms['is_translated'] = null;
            if ( $sitepress !== NULL && method_exists( $sitepress, 'is_translated_taxonomy') ) {
                $terms['is_translated'] = $sitepress->is_translated_taxonomy( $cpt_taxonomy );
            } else if (function_exists('pll_is_translated_taxonomy') ) {
                $terms['is_translated'] = pll_is_translated_taxonomy( $cpt_taxonomy );
            }
            $terms['translations'] = array();
            $all_terms = array();
            foreach($categories as $category) {
                // Skip the default WP.
                if ($category->slug == 'uncategorized')
                    continue;
                
                $terms['terms'][ $category->slug ] = self::get_terms_with_parent( $category, $cpt_taxonomy );
                $parent_terms = FP_Post_Importer_Host::get_parent_terms($category, $cpt_taxonomy);
                $all_terms = array_merge($all_terms, $parent_terms);
            }
            
            if (function_exists('pll_the_languages')) {
                $languages = pll_the_languages(array('raw'=>1));
            }    
            
            foreach($all_terms as $all_term) {
                // Skip the default WP.
                if ($all_term->slug == 'uncategorized')
                    continue;
                
               
                // Export WPML/Polylang Data 
                if ( $sitepress !== NULL && method_exists( $sitepress, 'get_default_language') ) {
                    $translation = array();
                    //$translation['type'] = $cpt_taxonomy;
                    // this will return the original post ID for the translated content.
                    $trid = $sitepress->get_element_trid( $all_term->term_id, "tax_{$cpt_taxonomy}" );
                    // True flag to skip the cache here.
                    $translations = $sitepress->get_element_translations( (int) $trid, false, false, true );
                    //$current_lang = apply_filters( 'wpml_current_language', NULL );
                    foreach($translations as $lang => $translation) :
                        //do_action( 'wpml_switch_language', $lang );
                        $translation_term_id = (int) $translation->element_id;
                        $translated_term = get_term($translation_term_id, $cpt_taxonomy);
                        if (!empty($translated_term->term_id)) {
                            $terms['translations'][ $all_term->slug ][ $lang ] = array(
                                'slug' => $translated_term->slug,
                                'name' => $translated_term->name,
                            );
                        }
                        //do_action( 'wpml_switch_language', $current_lang );
                    endforeach;
                } elseif (function_exists('pll_the_languages')) {
                    foreach($languages as $lang => $language) :
                        $translation_term_id = pll_get_term($all_term->term_id, $lang);
                        if (empty($translation_term_id)) {
                            continue;
                        }
                        
                        $translated_term = get_term($translation_term_id, $cpt_taxonomy);
                        if (!empty($translated_term->term_id)) {
                            $terms['translations'][ $all_term->slug ][ $lang ] = array(
                                'slug' => $translated_term->slug,
                                'name' => $translated_term->name,
                            );
                        }
                    endforeach;
                } 
            }
            $taxonomies[ $cpt_taxonomy ] = $terms;
        }

        return $taxonomies;
    }

    /**
     * Get all the parent terms.
     *
     * @param WP_Term $term
     * @param string $tax
     * @return WP_Term
     */
    public static function get_terms_with_parent( $term = false, $tax = 'category' ) {
        if ( (int) $term->parent === 0 ) {
            return $term;
        }
        $parent_term = get_term_by('id', $term->parent, $tax );
        $term->parent_term = self::get_terms_with_parent($parent_term, $tax);
        return $term;
    }

    /**
     * Get all the parent terms.
     *
     * @param WP_Term $term
     * @param string $tax
     * @return array
     */
    public static function get_parent_terms( $term = false, $tax = 'category' ) {
        $parent_terms = array();
        $parent_terms[] = $term;
        $all_terms = get_ancestors($term->term_id, $tax, 'taxonomy');
        if (!empty($all_terms)) {
            foreach($all_terms as $parent_term_id) {
                $parent_term = get_term($parent_term_id, $tax);
                if (isset($parent_term->term_id)) {
                    $parent_terms[] = $parent_term;
                }
            }
        }
        return $parent_terms;
    }

    /**
     * Callback to register all translation data.
     *
     * @param object $object
     * @return array
     */
    public function get_translated_id_for_api( $object ){
        global $sitepress;
        $post_id = $object['id'];
        $post_type = get_post_type( $post_id );
        if ( $sitepress !== NULL && method_exists( $sitepress, 'get_element_trid' ) ) {
            $translation = array();
            $wpml_element_type = apply_filters( 'wpml_element_type', $post_type );
            $translation['type'] = $wpml_element_type;
            // this will return the original post ID for the translated content.
            $trid = $sitepress->get_element_trid( $post_id );
            $translation['translations'] = $sitepress->get_element_translations($trid);
            $translation['original_id'] = icl_object_id( $post_id, $post_type, false, $sitepress->get_default_language() );
            $translation['default_lang'] = $sitepress->get_default_language();
            $current_post_details = wpml_get_language_information($post_id);
            $translation['current_lang'] = !empty($current_post_details['language_code']) ? $current_post_details['language_code'] : 'en';
            return $translation;
        } else if ( function_exists('pll_the_languages') ) {
            $translation = array();
            $translation['type'] = "post_{$post_type}";
            $post_language = pll_get_post_language($post_id, 'slug');
            $default_lang = pll_default_language('slug');
            $translation['current_lang'] = !empty($post_language) ? $post_language : 'en';
            $translation['default_lang'] = !empty($default_lang) ? $default_lang  : 'en';
            $translation['original_id'] = pll_get_post($post_id, $default_lang);
            $translation['translations'] = pll_get_post_translations($post_id);
            return $translation;
        }

        return null;
    }

}