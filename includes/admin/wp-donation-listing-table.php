<?php
/**
 * Posts List Table class.
 *
 * @package WordPress
 * @subpackage List_Table
 * @since 3.1.0
 * @access private
 */
class WC_Quick_Donation_Listing_Table extends WP_List_Table {

	/**
	 * Whether the items should be displayed hierarchically or linearly
	 *
	 * @since 3.1.0
	 * @var bool
	 * @access protected
	 */
	protected $hierarchical_display;

	/**
	 * Holds the number of pending comments for each post
	 *
	 * @since 3.1.0
	 * @var int
	 * @access protected
	 */
	protected $comment_pending_count;

	/**
	 * Holds the number of posts for this user
	 *
	 * @since 3.1.0
	 * @var int
	 * @access private
	 */
	private $user_posts_count;

	/**
	 * Holds the number of posts which are sticky.
	 *
	 * @since 3.1.0
	 * @var int
	 * @access private
	 */
	private $sticky_posts_count = 0;

	private $is_trash;

	/**
	 * Current level for output.
	 *
	 * @since 4.3.0
	 * @access protected
	 * @var int
	 */
	protected $current_level = 0;
    public $querypost ;

	/**
	 * Constructor.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 *
	 * @global object $post_type_object
	 * @global wpdb   $wpdb
	 *
	 * @param array $args An associative array of arguments.
	 */
	public function __construct( $query,$args = array() ) {
        global $post_type_object, $wpdb;
        $this->querypost = $query;
        set_current_screen( 'shop_order' );

        parent::__construct( array(
            'plural' => 'posts',
            'screen' => get_current_screen(),
        ) );
        
        
        $this->screen->post_type = 'shop_order';
        $post_type = $this->screen->post_type;
        $post_type_object = get_post_type_object( $post_type );
        $this->user_posts_count = 0;
        $this->sticky_posts_count = 0;
	}

	/**
	 * Sets whether the table layout should be hierarchical or not.
	 *
	 * @since 4.2.0
	 *
	 * @param bool $display Whether the table layout should be hierarchical.
	 */
	public function set_hierarchical_display( $display ) {
		$this->hierarchical_display = $display;
	}

	/**
	 *
	 * @return bool
	 */
	public function ajax_user_can() {
		return current_user_can( get_post_type_object( $this->screen->post_type )->cap->edit_posts );
	}

	/**
	 *
	 * @global array    $avail_post_stati
	 * @global WP_Query $wp_query
	 * @global int      $per_page
	 * @global string   $mode
	 */
	public function prepare_items() {
		global $avail_post_stati, $per_page, $mode;

		$avail_post_stati = wp_edit_posts_query();

		$this->set_hierarchical_display( is_post_type_hierarchical( $this->screen->post_type ) && 'menu_order title' == $this->querypost->query['orderby'] );

		$total_items = $this->hierarchical_display ? $this->querypost->post_count : $this->querypost->found_posts;

		$post_type = $this->screen->post_type;
		$per_page = $this->get_items_per_page( 'edit_' . $post_type . '_per_page' );

		/** This filter is documented in wp-admin/includes/post.php */
 		$per_page = apply_filters( 'edit_posts_per_page', $per_page, $post_type );

		if ( $this->hierarchical_display )
			$total_pages = ceil( $total_items / $per_page );
		else
			$total_pages = $this->querypost->max_num_pages;

		if ( ! empty( $_REQUEST['mode'] ) ) {
			$mode = $_REQUEST['mode'] == 'excerpt' ? 'excerpt' : 'list';
			set_user_setting ( 'posts_list_mode', $mode );
		} else {
			$mode = get_user_setting ( 'posts_list_mode', 'list' );
		}

		$this->is_trash = isset( $_REQUEST['post_status'] ) && $_REQUEST['post_status'] == 'trash';

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'total_pages' => $total_pages,
			'per_page' => $per_page
		) );
	}

	/**
	 *
	 * @return bool
	 */
	public function has_items() {
		return $this->querypost->have_posts();
	}

	/**
	 * @access public
	 */
	public function no_items() {
		if ( isset( $_REQUEST['post_status'] ) && 'trash' == $_REQUEST['post_status'] )
			echo get_post_type_object( $this->screen->post_type )->labels->not_found_in_trash;
		else
			echo get_post_type_object( $this->screen->post_type )->labels->not_found;
	}

	/**
	 * Determine if the current view is the "All" view.
	 *
	 * @since 4.2.0
	 *
	 * @return bool Whether the current view is the "All" view.
	 */
	protected function is_base_request() {
		if ( empty( $_GET ) ) {
			return true;
		} elseif ( 1 === count( $_GET ) && ! empty( $_GET['post_type'] ) ) {
			return $this->screen->post_type === $_GET['post_type'];
		}
	}

    function wp_count_posts( $type = 'post', $perm = '' ) {
        global $wpdb;

        if ( ! post_type_exists( $type ) )
            return new stdClass;

        $cache_key = _count_posts_cache_key( $type.'_donation', $perm );
        $counts = wp_cache_get( $cache_key, 'counts' );
        if ( false !== $counts ) {return $counts;}
        
        $query  = "SELECT post_status, COUNT( * ) AS num_posts FROM {$wpdb->posts} ";
        $query .= "LEFT JOIN {$wpdb->postmeta} ON wp_postmeta.post_id = wp_posts.ID ";
        $query .= 'WHERE wp_postmeta.meta_key = "_is_donation" AND wp_postmeta.meta_value = 1 AND post_type = %s ';
        $query .= 'GROUP BY post_status ';

        $results = (array) $wpdb->get_results( $wpdb->prepare( $query, $type ), ARRAY_A );
        $counts = array_fill_keys( get_post_stati(), 0 );

        foreach ( $results as $row ) {$counts[ $row['post_status'] ] = $row['num_posts'];}

        $counts = (object) $counts;
        wp_cache_set( $cache_key.'_donation', $counts, 'counts' );

        return $counts;
    }    
	/**
	 *
	 * @global array $locked_post_status This seems to be deprecated.
	 * @global array $avail_post_stati
	 * @return array
	 */
	protected function get_views() {
		global $locked_post_status, $avail_post_stati;

		$post_type = $this->screen->post_type;

		if ( !empty($locked_post_status) )
			return array();

		$status_links = array();
		$num_posts = $this->wp_count_posts( $post_type, 'readable' );
        
		$class = '';
		$allposts = '';

		$current_user_id = get_current_user_id();

		if ( $this->user_posts_count ) {
			if ( isset( $_GET['author'] ) && ( $_GET['author'] == $current_user_id ) )
				$class = ' class="current"';
			$status_links['mine'] = "<a href='edit.php?post_type=$post_type&author=$current_user_id'$class>" . sprintf( _nx( 'Mine <span class="count">(%s)</span>', 'Mine <span class="count">(%s)</span>', $this->user_posts_count, 'posts' ), number_format_i18n( $this->user_posts_count ) ) . '</a>';
			$allposts = '&all_posts=1';
			$class = '';
		}

		$total_posts = array_sum( (array) $num_posts );

		// Subtract post types that are not included in the admin all list.
		foreach ( get_post_stati( array('show_in_admin_all_list' => false) ) as $state )
			$total_posts -= $num_posts->$state;

		if ( empty( $class ) && ( ( $this->is_base_request() && ! $this->user_posts_count ) || isset( $_REQUEST['all_posts'] ) ) ) {
			$class =  ' class="current"';
		}

		$all_inner_html = sprintf(
			_nx(
				'All <span class="count">(%s)</span>',
				'All <span class="count">(%s)</span>',
				$total_posts,
				'posts'
			),
			number_format_i18n( $total_posts )
		);

		$status_links['all'] = "<a href='edit.php?post_type=$post_type{$allposts}'$class>" . $all_inner_html . '</a>';

		foreach ( get_post_stati(array('show_in_admin_status_list' => true), 'objects') as $status ) {
			$class = '';

			$status_name = $status->name;

			if ( !in_array( $status_name, $avail_post_stati ) )
				continue;

			if ( empty( $num_posts->$status_name ) )
				continue;

			if ( isset($_REQUEST['post_status']) && $status_name == $_REQUEST['post_status'] )
				$class = ' class="current"';

			$status_links[$status_name] = "<a href='edit.php?post_status=$status_name&amp;post_type=$post_type'$class>" . sprintf( translate_nooped_plural( $status->label_count, $num_posts->$status_name ), number_format_i18n( $num_posts->$status_name ) ) . '</a>';
		}

		if ( ! empty( $this->sticky_posts_count ) ) {
			$class = ! empty( $_REQUEST['show_sticky'] ) ? ' class="current"' : '';

			$sticky_link = array( 'sticky' => "<a href='edit.php?post_type=$post_type&amp;show_sticky=1'$class>" . sprintf( _nx( 'Sticky <span class="count">(%s)</span>', 'Sticky <span class="count">(%s)</span>', $this->sticky_posts_count, 'posts' ), number_format_i18n( $this->sticky_posts_count ) ) . '</a>' );

			// Sticky comes after Publish, or if not listed, after All.
			$split = 1 + array_search( ( isset( $status_links['publish'] ) ? 'publish' : 'all' ), array_keys( $status_links ) );
			$status_links = array_merge( array_slice( $status_links, 0, $split ), $sticky_link, array_slice( $status_links, $split ) );
		}

		return $status_links;
	}

	/**
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		$actions = array();
		$post_type_obj = get_post_type_object( $this->screen->post_type );

		if ( $this->is_trash ) {
			$actions['untrash'] = __( 'Restore' );
		} else {
			$actions['edit'] = __( 'Edit' );
		}

		if ( current_user_can( $post_type_obj->cap->delete_posts ) ) {
			if ( $this->is_trash || ! EMPTY_TRASH_DAYS ) {
				$actions['delete'] = __( 'Delete Permanently' );
			} else {
				$actions['trash'] = __( 'Move to Trash' );
			}
		}

		return $actions;
	}
    
    public function get_donation_projects(){
        $args = array(
            'post_type'             => WC_QD_PT,
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 0,
            'posts_per_page'        => '0',
            'fields' => 'ids'
        );
        $products = new WP_Query($args);
        echo '<select name="dproj" id="donation_project" class="wc-enhanced-select">';
            echo '<option value=""> '.__('Donation Project',WC_QD_TXT).'</option>';
            foreach($products->get_posts() as $id){
                $attr = '';
                if(isset($_GET['dproj']) && $id == $_GET['dproj']){ $attr = 'selected';}
                echo '<option value="'.$id.'" '.$attr.'> '.get_the_title($id).'</option>';
            }
        echo '</select>';    
    }
        
        

	/**
	 * @global int $cat
	 * @param string $which
	 */
	protected function extra_tablenav( $which ) {
		global $cat;
?>
		<div class="alignleft actions">
<?php
		if ( 'top' == $which && !is_singular() ) {

			$this->months_dropdown( $this->screen->post_type );

			if ( is_object_in_taxonomy( $this->screen->post_type, 'category' ) ) {
				$dropdown_options = array(
					'show_option_all' => __( 'All categories' ),
					'hide_empty' => 0,
					'hierarchical' => 1,
					'show_count' => 0,
					'orderby' => 'name',
					'selected' => $cat
				);

				echo '<label class="screen-reader-text" for="cat">' . __( 'Filter by category' ) . '</label>';
				wp_dropdown_categories( $dropdown_options );
			}
            $this->get_donation_projects();
			/**
			 * Fires before the Filter button on the Posts and Pages list tables.
			 *
			 * The Filter button allows sorting by date and/or category on the
			 * Posts list table, and sorting by date on the Pages list table.
			 *
			 * @since 2.1.0
			 */
			do_action( 'restrict_manage_posts' );

			submit_button( __( 'Filter' ), 'button', 'filter_action', false, array( 'id' => 'post-query-submit' ) );
		}

		if ( $this->is_trash && current_user_can( get_post_type_object( $this->screen->post_type )->cap->edit_others_posts ) ) {
			submit_button( __( 'Empty Trash' ), 'apply', 'delete_all', false );
		}
?>
		</div>
<?php
	}

	/**
	 *
	 * @return string
	 */
	public function current_action() {
		if ( isset( $_REQUEST['delete_all'] ) || isset( $_REQUEST['delete_all2'] ) )
			return 'delete_all';

		return parent::current_action();
	}

	/**
	 * @global string $mode
	 * @param string $which
	 */
	protected function pagination( $which ) {
		global $mode;

		parent::pagination( $which );

		if ( 'top' == $which && ! is_post_type_hierarchical( $this->screen->post_type ) )
			$this->view_switcher( $mode );
	}

	/**
	 *
	 * @return array
	 */
	protected function get_table_classes() {
		return array( 'widefat', 'fixed', 'striped', is_post_type_hierarchical( $this->screen->post_type ) ? 'pages' : 'posts' );
	}

	/**
	 *
	 * @return array
	 */
	public function get_columns() {
		$post_type = $this->screen->post_type;

		$posts_columns = array();

		$posts_columns['cb'] = '<input type="checkbox" />';

		/* translators: manage posts column name */
		$posts_columns['title'] = _x( 'Title', 'column name' );

		if ( post_type_supports( $post_type, 'author' ) ) {
			$posts_columns['author'] = __( 'Author' );
		}

		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$taxonomies = wp_filter_object_list( $taxonomies, array( 'show_admin_column' => true ), 'and', 'name' );

		/**
		 * Filter the taxonomy columns in the Posts list table.
		 *
		 * The dynamic portion of the hook name, `$post_type`, refers to the post
		 * type slug.
		 *
		 * @since 3.5.0
		 *
		 * @param array  $taxonomies Array of taxonomies to show columns for.
		 * @param string $post_type  The post type.
		 */
		$taxonomies = apply_filters( "manage_taxonomies_for_{$post_type}_columns", $taxonomies, $post_type );
		$taxonomies = array_filter( $taxonomies, 'taxonomy_exists' );

		foreach ( $taxonomies as $taxonomy ) {
			if ( 'category' == $taxonomy )
				$column_key = 'categories';
			elseif ( 'post_tag' == $taxonomy )
				$column_key = 'tags';
			else
				$column_key = 'taxonomy-' . $taxonomy;

			$posts_columns[ $column_key ] = get_taxonomy( $taxonomy )->labels->name;
		}

		$post_status = !empty( $_REQUEST['post_status'] ) ? $_REQUEST['post_status'] : 'all';
		if ( post_type_supports( $post_type, 'comments' ) && !in_array( $post_status, array( 'pending', 'draft', 'future' ) ) )
			$posts_columns['comments'] = '<span class="vers comment-grey-bubble" title="' . esc_attr__( 'Comments' ) . '"><span class="screen-reader-text">' . __( 'Comments' ) . '</span></span>';

		$posts_columns['date'] = __( 'Date' );

		if ( 'page' == $post_type ) {

			/**
			 * Filter the columns displayed in the Pages list table.
			 *
			 * @since 2.5.0
			 *
			 * @param array $post_columns An array of column names.
			 */
			$posts_columns = apply_filters( 'manage_pages_columns', $posts_columns );
		} else {

			/**
			 * Filter the columns displayed in the Posts list table.
			 *
			 * @since 1.5.0
			 *
			 * @param array  $posts_columns An array of column names.
			 * @param string $post_type     The post type slug.
			 */
			$posts_columns = apply_filters( 'manage_posts_columns', $posts_columns, $post_type );
		}

		/**
		 * Filter the columns displayed in the Posts list table for a specific post type.
		 *
		 * The dynamic portion of the hook name, `$post_type`, refers to the post type slug.
		 *
		 * @since 3.0.0
		 *
		 * @param array $post_columns An array of column names.
		 */
		return apply_filters( "manage_{$post_type}_posts_columns", $posts_columns );
	}

	/**
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {
		return array(
			'title'    => 'title',
			'parent'   => 'parent',
			'comments' => 'comment_count',
			'date'     => array( 'date', true )
		);
	}

	/**
	 * @global WP_Query $wp_query
	 * @global int $per_page
	 * @param array $posts
	 * @param int $level
	 */
	public function display_rows( $posts = array(), $level = 0 ) {
		global $per_page;

		if ( empty( $posts ) )
			$posts = $this->querypost->posts;
        
		add_filter( 'the_title', 'esc_html' );

		if ( $this->hierarchical_display ) {
			$this->_display_rows_hierarchical( $posts, $this->get_pagenum(), $per_page );
		} else {
			$this->_display_rows( $posts, $level );
		}
	}

	/**
	 * @global string $mode
	 * @param array $posts
	 * @param int $level
	 */
	private function _display_rows( $posts, $level = 0 ) {
		global $mode;

		// Create array of post IDs.
		$post_ids = array();

		foreach ( $posts as $a_post )
			$post_ids[] = $a_post->ID;

		$this->comment_pending_count = get_pending_comments_num( $post_ids );

		foreach ( $posts as $post )
			$this->single_row( $post, $level );
	}

	/**
	 * @global wpdb    $wpdb
	 * @global WP_Post $post
	 * @param array $pages
	 * @param int $pagenum
	 * @param int $per_page
	 */
	private function _display_rows_hierarchical( $pages, $pagenum = 1, $per_page = 20 ) {
		global $wpdb;

		$level = 0;

		if ( ! $pages ) {
			$pages = get_pages( array( 'sort_column' => 'menu_order' ) );

			if ( ! $pages )
				return;
		}

		/*
		 * Arrange pages into two parts: top level pages and children_pages
		 * children_pages is two dimensional array, eg.
		 * children_pages[10][] contains all sub-pages whose parent is 10.
		 * It only takes O( N ) to arrange this and it takes O( 1 ) for subsequent lookup operations
		 * If searching, ignore hierarchy and treat everything as top level
		 */
		if ( empty( $_REQUEST['s'] ) ) {

			$top_level_pages = array();
			$children_pages = array();

			foreach ( $pages as $page ) {

				// Catch and repair bad pages.
				if ( $page->post_parent == $page->ID ) {
					$page->post_parent = 0;
					$wpdb->update( $wpdb->posts, array( 'post_parent' => 0 ), array( 'ID' => $page->ID ) );
					clean_post_cache( $page );
				}

				if ( 0 == $page->post_parent )
					$top_level_pages[] = $page;
				else
					$children_pages[ $page->post_parent ][] = $page;
			}

			$pages = &$top_level_pages;
		}

		$count = 0;
		$start = ( $pagenum - 1 ) * $per_page;
		$end = $start + $per_page;
		$to_display = array();

		foreach ( $pages as $page ) {
			if ( $count >= $end )
				break;

			if ( $count >= $start ) {
				$to_display[$page->ID] = $level;
			}

			$count++;

			if ( isset( $children_pages ) )
				$this->_page_rows( $children_pages, $count, $page->ID, $level + 1, $pagenum, $per_page, $to_display );
		}

		// If it is the last pagenum and there are orphaned pages, display them with paging as well.
		if ( isset( $children_pages ) && $count < $end ){
			foreach ( $children_pages as $orphans ){
				foreach ( $orphans as $op ) {
					if ( $count >= $end )
						break;

					if ( $count >= $start ) {
						$to_display[$op->ID] = 0;
					}

					$count++;
				}
			}
		}

		$ids = array_keys( $to_display );
		_prime_post_caches( $ids );

		if ( ! isset( $GLOBALS['post'] ) ) {
			$GLOBALS['post'] = reset( $ids );
		}

		foreach ( $to_display as $page_id => $level ) {
			echo "\t";
			$this->single_row( $page_id, $level );
		}
	}

	/**
	 * Given a top level page ID, display the nested hierarchy of sub-pages
	 * together with paging support
	 *
	 * @since 3.1.0 (Standalone function exists since 2.6.0)
	 * @since 4.2.0 Added the `$to_display` parameter.
	 *
	 * @param array $children_pages
	 * @param int $count
	 * @param int $parent
	 * @param int $level
	 * @param int $pagenum
	 * @param int $per_page
	 * @param array $to_display List of pages to be displayed. Passed by reference.
	 */
	private function _page_rows( &$children_pages, &$count, $parent, $level, $pagenum, $per_page, &$to_display ) {
		if ( ! isset( $children_pages[$parent] ) )
			return;

		$start = ( $pagenum - 1 ) * $per_page;
		$end = $start + $per_page;

		foreach ( $children_pages[$parent] as $page ) {
			if ( $count >= $end )
				break;

			// If the page starts in a subtree, print the parents.
			if ( $count == $start && $page->post_parent > 0 ) {
				$my_parents = array();
				$my_parent = $page->post_parent;
				while ( $my_parent ) {
					// Get the ID from the list or the attribute if my_parent is an object
					$parent_id = $my_parent;
					if ( is_object( $my_parent ) ) {
						$parent_id = $my_parent->ID;
					}

					$my_parent = get_post( $parent_id );
					$my_parents[] = $my_parent;
					if ( !$my_parent->post_parent )
						break;
					$my_parent = $my_parent->post_parent;
				}
				$num_parents = count( $my_parents );
				while ( $my_parent = array_pop( $my_parents ) ) {
					$to_display[$my_parent->ID] = $level - $num_parents;
					$num_parents--;
				}
			}

			if ( $count >= $start ) {
				$to_display[$page->ID] = $level;
			}

			$count++;

			$this->_page_rows( $children_pages, $count, $page->ID, $level + 1, $pagenum, $per_page, $to_display );
		}

		unset( $children_pages[$parent] ); //required in order to keep track of orphans
	}

	/**
	 * Handles the checkbox column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_cb( $post ) {
		if ( current_user_can( 'edit_post', $post->ID ) ): ?>
			<label class="screen-reader-text" for="cb-select-<?php the_ID(); ?>"><?php
				printf( __( 'Select %s' ), _draft_or_post_title() );
			?></label>
			<input id="cb-select-<?php the_ID(); ?>" type="checkbox" name="post[]" value="<?php the_ID(); ?>" />
			<div class="locked-indicator"></div>
		<?php endif;
	}

	/**
	 * @since 4.3.0
	 * @access protected
	 *
	 * @param WP_Post $post
	 * @param string  $classes
	 * @param string  $data
	 * @param string  $primary
	 */
	protected function _column_title( $post, $classes, $data, $primary ) {
		echo '<td class="' . $classes . ' page-title" ', $data, '>';
		echo $this->column_title( $post );
		echo $this->handle_row_actions( $post, 'title', $primary );
		echo '</td>';
	}

	/**
	 * Handles the title column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @global string $mode
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_title( $post ) {
		global $mode;

		if ( $this->hierarchical_display ) {
			if ( 0 === $this->current_level && (int) $post->post_parent > 0 ) {
				// Sent level 0 by accident, by default, or because we don't know the actual level.
				$find_main_page = (int) $post->post_parent;
				while ( $find_main_page > 0 ) {
					$parent = get_post( $find_main_page );

					if ( is_null( $parent ) ) {
						break;
					}

					$this->current_level++;
					$find_main_page = (int) $parent->post_parent;

					if ( ! isset( $parent_name ) ) {
						/** This filter is documented in wp-includes/post-template.php */
						$parent_name = apply_filters( 'the_title', $parent->post_title, $parent->ID );
					}
				}
			}
		}

		$pad = str_repeat( '&#8212; ', $this->current_level );
		echo "<strong>";

		$format = get_post_format( $post->ID );
		if ( $format ) {
			$label = get_post_format_string( $format );

			echo '<a href="' . esc_url( add_query_arg( array( 'post_format' => $format, 'post_type' => $post->post_type ), 'edit.php' ) ) . '" class="post-state-format post-format-icon post-format-' . $format . '" title="' . $label . '">' . $label . ":</a> ";
		}

		$can_edit_post = current_user_can( 'edit_post', $post->ID );
		$title = _draft_or_post_title();

		if ( $can_edit_post && $post->post_status != 'trash' ) {
			$edit_link = get_edit_post_link( $post->ID );
			echo '<a class="row-title" href="' . $edit_link . '" title="' . esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $title ) ) . '">' . $pad . $title . '</a>';
		} else {
			echo $pad . $title;
		}
		_post_states( $post );

		if ( isset( $parent_name ) ) {
			$post_type_object = get_post_type_object( $post->post_type );
			echo ' | ' . $post_type_object->labels->parent_item_colon . ' ' . esc_html( $parent_name );
		}
		echo "</strong>\n";

		if ( $can_edit_post && $post->post_status != 'trash' ) {
			$lock_holder = wp_check_post_lock( $post->ID );

			if ( $lock_holder ) {
				$lock_holder = get_userdata( $lock_holder );
				$locked_avatar = get_avatar( $lock_holder->ID, 18 );
				$locked_text = esc_html( sprintf( __( '%s is currently editing' ), $lock_holder->display_name ) );
			} else {
				$locked_avatar = $locked_text = '';
			}

			echo '<div class="locked-info"><span class="locked-avatar">' . $locked_avatar . '</span> <span class="locked-text">' . $locked_text . "</span></div>\n";
		}

		if ( ! is_post_type_hierarchical( $this->screen->post_type ) && 'excerpt' == $mode && current_user_can( 'read_post', $post->ID ) ) {
			the_excerpt();
		}

		get_inline_data( $post );
	}

	/**
	 * Handles the post date column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @global string $mode
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_date( $post ) {
		global $mode;

		if ( '0000-00-00 00:00:00' == $post->post_date ) {
			$t_time = $h_time = __( 'Unpublished' );
			$time_diff = 0;
		} else {
			$t_time = get_the_time( __( 'Y/m/d g:i:s a' ) );
			$m_time = $post->post_date;
			$time = get_post_time( 'G', true, $post );

			$time_diff = time() - $time;

			if ( $time_diff > 0 && $time_diff < DAY_IN_SECONDS ) {
				$h_time = sprintf( __( '%s ago' ), human_time_diff( $time ) );
			} else {
				$h_time = mysql2date( __( 'Y/m/d' ), $m_time );
			}
		}

		if ( 'excerpt' == $mode ) {
			/**
			 * Filter the published time of the post.
			 *
			 * If $mode equals 'excerpt', the published time and date are both displayed.
			 * If $mode equals 'list' (default), the publish date is displayed, with the
			 * time and date together available as an abbreviation definition.
			 *
			 * @since 2.5.1
			 *
			 * @param array   $t_time      The published time.
			 * @param WP_Post $post        Post object.
			 * @param string  $column_name The column name.
			 * @param string  $mode        The list display mode ('excerpt' or 'list').
			 */
			echo apply_filters( 'post_date_column_time', $t_time, $post, 'date', $mode );
		} else {

			/** This filter is documented in wp-admin/includes/class-wp-posts-list-table.php */
			echo '<abbr title="' . $t_time . '">' . apply_filters( 'post_date_column_time', $h_time, $post, 'date', $mode ) . '</abbr>';
		}
		echo '<br />';
		if ( 'publish' == $post->post_status ) {
			_e( 'Published' );
		} elseif ( 'future' == $post->post_status ) {
			if ( $time_diff > 0 ) {
				echo '<strong class="error-message">' . __( 'Missed schedule' ) . '</strong>';
			} else {
				_e( 'Scheduled' );
			}
		} else {
			_e( 'Last Modified' );
		}
	}

	/**
	 * Handles the comments column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_comments( $post ) {
		?>
		<div class="post-com-count-wrapper">
		<?php
			$pending_comments = isset( $this->comment_pending_count[$post->ID] ) ? $this->comment_pending_count[$post->ID] : 0;

			$this->comments_bubble( $post->ID, $pending_comments );
		?>
		</div>
		<?php
	}

	/**
	 * Handles the post author column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_author( $post ) {
		printf( '<a href="%s">%s</a>',
			esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'author' => get_the_author_meta( 'ID' ) ), 'edit.php' )),
			get_the_author()
		);
	}

	/**
	 * Handles the default column output.
	 *
	 * @since 4.3.0
	 * @access public
	 *
	 * @param WP_Post $post        The current WP_Post object.
	 * @param string  $column_name The current column name.
	 */
	public function column_default( $post, $column_name ) {
		if ( 'categories' == $column_name ) {
			$taxonomy = 'category';
		} elseif ( 'tags' == $column_name ) {
			$taxonomy = 'post_tag';
		} elseif ( 0 === strpos( $column_name, 'taxonomy-' ) ) {
			$taxonomy = substr( $column_name, 9 );
		} else {
			$taxonomy = false;
		}
		if ( $taxonomy ) {
			$taxonomy_object = get_taxonomy( $taxonomy );
			$terms = get_the_terms( $post->ID, $taxonomy );
			if ( is_array( $terms ) ) {
				$out = array();
				foreach ( $terms as $t ) {
					$posts_in_term_qv = array();
					if ( 'post' != $post->post_type ) {
						$posts_in_term_qv['post_type'] = $post->post_type;
					}
					if ( $taxonomy_object->query_var ) {
						$posts_in_term_qv[ $taxonomy_object->query_var ] = $t->slug;
					} else {
						$posts_in_term_qv['taxonomy'] = $taxonomy;
						$posts_in_term_qv['term'] = $t->slug;
					}

					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( $posts_in_term_qv, 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $t->name, $t->term_id, $taxonomy, 'display' ) )
					);
				}
				/* translators: used between list items, there is a space after the comma */
				echo join( __( ', ' ), $out );
			} else {
				echo '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . $taxonomy_object->labels->no_terms . '</span>';
			}
			return;
		}

		if ( is_post_type_hierarchical( $post->post_type ) ) {

			/**
			 * Fires in each custom column on the Posts list table.
			 *
			 * This hook only fires if the current post type is hierarchical,
			 * such as pages.
			 *
			 * @since 2.5.0
			 *
			 * @param string $column_name The name of the column to display.
			 * @param int    $post_id     The current post ID.
			 */
			do_action( 'manage_pages_custom_column', $column_name, $post->ID );
		} else {

			/**
			 * Fires in each custom column in the Posts list table.
			 *
			 * This hook only fires if the current post type is non-hierarchical,
			 * such as posts.
			 *
			 * @since 1.5.0
			 *
			 * @param string $column_name The name of the column to display.
			 * @param int    $post_id     The current post ID.
			 */
			do_action( 'manage_posts_custom_column', $column_name, $post->ID );
		}

		/**
		 * Fires for each custom column of a specific post type in the Posts list table.
		 *
		 * The dynamic portion of the hook name, `$post->post_type`, refers to the post type.
		 *
		 * @since 3.1.0
		 *
		 * @param string $column_name The name of the column to display.
		 * @param int    $post_id     The current post ID.
		 */
		do_action( "manage_{$post->post_type}_posts_custom_column", $column_name, $post->ID );
	}

	/**
	 * @global WP_Post $post
	 *
	 * @param int|WP_Post $post
	 * @param int         $level
	 */
	public function single_row( $post, $level = 0 ) {
		$global_post = get_post();

		$post = get_post( $post );
		$this->current_level = $level;

		$GLOBALS['post'] = $post;
		setup_postdata( $post );

		$classes = 'iedit author-' . ( get_current_user_id() == $post->post_author ? 'self' : 'other' );

		$lock_holder = wp_check_post_lock( $post->ID );
		if ( $lock_holder ) {
			$classes .= ' wp-locked';
		}

		if ( $post->post_parent ) {
		    $count = count( get_post_ancestors( $post->ID ) );
		    $classes .= ' level-'. $count;
		} else {
		    $classes .= ' level-0';
		}
	?>
		<tr id="post-<?php echo $post->ID; ?>" class="<?php echo implode( ' ', get_post_class( $classes, $post->ID ) ); ?>">
			<?php $this->single_row_columns( $post ); ?>
		</tr>
	<?php
		$GLOBALS['post'] = $global_post;
	}

	/**
	 * Gets the name of the default primary column.
	 *
	 * @since 4.3.0
	 * @access protected
	 *
	 * @return string Name of the default primary column, in this case, 'title'.
	 */
	protected function get_default_primary_column_name() {
		return 'title';
	}


    
	
    
}

function tt_render_list_page($shop = ''){  
    $wc_qd_donation_orders_listing = new WC_Quick_Donation_Listing_Table($shop);
    $wc_qd_donation_orders_listing->prepare_items();
?>
<div class="wrap">
    <div id="icon-users" class="icon32"><br/></div>
    <h2>Donations</h2>
    <form id="movies-filter" method="get"> 
        <input type="hidden" name="post_type" value="wc_qd_orders" />
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <?php $wc_qd_donation_orders_listing->search_box(__('Search Donations',WC_QD_TXT), 'post' ); ?>
        <?php  
        $wc_qd_donation_orders_listing->views();
        $wc_qd_donation_orders_listing->display() ?>
    </form>
</div>
<?php  } ?>