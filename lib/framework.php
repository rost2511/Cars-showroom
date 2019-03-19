<?php

/**
 * Various functions required for bt-bootstrap to work properly
 *
 */
/*
 * Table of contests:
 * 1 - WP Content Width
 * 2 - Menu navigation walker
 * 3 - Pagination
 * 4 - Comments tree
 * */

// 1 - WP Content Width @link {https://codex.wordpress.org/Content_Width}

if ( ! isset( $content_width ) ) {
    $content_width = 1140;
}

/**
 * 2 - Menu navigation walker
 * Class Name: bt_navwalker
 * GitHub URI: https://github.com/twittem/wp-bt-navwalker
 * Description: A custom WordPress nav walker class to implement the bt 3 navigation style in a custom theme using the WordPress built in menu manager.
 */

class bt_navwalker extends Walker_Nav_Menu {

    /**
     * @see Walker::start_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int $depth Depth of page. Used for padding.
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat( "\t", $depth );
        $output .= "\n$indent<ul role=\"menu\" class=\" dropdown-menu\">\n";
    }

    /**
     * @see Walker::start_el()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param int $current_page Menu item ID.
     * @param object $args
     */
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        /**
         * Dividers, Headers or Disabled
         * =============================
         * Determine whether the item is a Divider, Header, Disabled or regular
         * menu item. To prevent errors we use the strcasecmp() function to so a
         * comparison that is not case sensitive. The strcasecmp() function returns
         * a 0 if the strings are equal.
         */
        if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
            $output .= $indent . '<li role="presentation" class="divider">';
        } else if ( strcasecmp( $item->title, 'divider') == 0 && $depth === 1 ) {
            $output .= $indent . '<li role="presentation" class="divider">';
        } else if ( strcasecmp( $item->attr_title, 'dropdown-header') == 0 && $depth === 1 ) {
            $output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
        } else if ( strcasecmp($item->attr_title, 'disabled' ) == 0 ) {
            $output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
        } else {

            $class_names = $value = '';

            $classes = empty( $item->classes ) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;

            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

            if ( $args->has_children )
                $class_names .= ' dropdown';

            if ( in_array( 'current-menu-item', $classes ) )
                $class_names .= ' active';

            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

            $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
            $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

            $output .= $indent . '<li' . $id . $value . $class_names .'>';

            $atts = array();
            $atts['title']  = ! empty( $item->title )	? $item->title	: '';
            $atts['target'] = ! empty( $item->target )	? $item->target	: '';
            $atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';

            // If item has_children add atts to a.
            if ( $args->has_children && $depth === 0 ) {
                $atts['href']   		= '#';
                $atts['data-toggle']	= 'dropdown';
                $atts['class']			= 'dropdown-toggle';
                $atts['aria-haspopup']	= 'true';
            } else {
                $atts['href'] = ! empty( $item->url ) ? $item->url : '';
            }

            $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

            $attributes = '';
            foreach ( $atts as $attr => $value ) {
                if ( ! empty( $value ) ) {
                    $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            $item_output = $args->before;

            /*
             * Glyphicons
             * ===========
             * Since the the menu item is NOT a Divider or Header we check the see
             * if there is a value in the attr_title property. If the attr_title
             * property is NOT null we apply it as the class name for the glyphicon.
             */
            if ( ! empty( $item->attr_title ) )
                $item_output .= '<a'. $attributes .'><span class="glyphicon ' . esc_attr( $item->attr_title ) . '"></span>&nbsp;';
            else
                $item_output .= '<a'. $attributes .'>';

            $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
            $item_output .= ( $args->has_children && 0 === $depth ) ? ' <span class="caret"></span></a>' : '</a>';
            $item_output .= $args->after;

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }
    }

    /**
     * Traverse elements to create list from elements.
     *
     * Display one element if the element doesn't have any children otherwise,
     * display the element and its children. Will only traverse up to the max
     * depth and no ignore elements under that depth.
     *
     * This method shouldn't be called directly, use the walk() method instead.
     *
     * @see Walker::start_el()
     * @since 2.5.0
     *
     * @param object $element Data object
     * @param array $children_elements List of elements to continue traversing.
     * @param int $max_depth Max depth to traverse.
     * @param int $depth Depth of current element.
     * @param array $args
     * @param string $output Passed by reference. Used to append additional content.
     * @return null Null on failure with no changes to parameters.
     */
    public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( ! $element )
            return;

        $id_field = $this->db_fields['id'];

        // Display this element.
        if ( is_object( $args[0] ) )
            $args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

    /**
     * Menu Fallback
     * =============
     * If this function is assigned to the wp_nav_menu's fallback_cb variable
     * and a manu has not been assigned to the theme location in the WordPress
     * menu manager the function with display nothing to a non-logged in user,
     * and will add a link to the WordPress menu manager if logged in as an admin.
     *
     * @param array $args passed from the wp_nav_menu function.
     *
     */
    public static function fallback( $args ) {
        if ( current_user_can( 'manage_options' ) ) {

            extract( $args );

            $fb_output = null;

            if ( $container ) {
                $fb_output = '<' . $container;

                if ( $container_id )
                    $fb_output .= ' id="' . $container_id . '"';

                if ( $container_class )
                    $fb_output .= ' class="' . $container_class . '"';

                $fb_output .= '>';
            }

            $fb_output .= '<ul';

            if ( $menu_id )
                $fb_output .= ' id="' . $menu_id . '"';

            if ( $menu_class )
                $fb_output .= ' class="' . $menu_class . '"';

            $fb_output .= '>';
            $fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
            $fb_output .= '</ul>';

            if ( $container )
                $fb_output .= '</' . $container . '>';

            echo $fb_output;
        }
    }
}

/*
 * 3 - Pagination
 * Custom pagination with bt .pagination class
 * source: http://www.ordinarycoder.com/paginate_links-class-ul-li-bt/
 */
function bt_pagination( $echo = true ) {
    global $wp_query;

    $big = 999999999; // need an unlikely integer

    $pages = paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'total' => $wp_query->max_num_pages,
            'type'  => 'array',
            'prev_next'   => true,
            'prev_text'    => __('&laquo; Prev'),
            'next_text'    => __('Next &raquo;'),
        )
    );

    if( is_array( $pages ) ) {
        $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');

        $pagination = '<ul class="pagination">';

        foreach ( $pages as $page ) {
            $pagination .= "<li>$page</li>";
        }

        $pagination .= '</ul>';

        if ( $echo ) {
            echo $pagination;
        } else {
            return $pagination;
        }
    }
}

/**
 * 4 - Comments tree
 * bt Comments Tree
 *
 */
if ( ! class_exists( 'bt_Comments' ) ) :
    class bt_Comments extends Walker_Comment{
        // Init classwide variables.
        var $tree_type = 'comment';
        var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );
        /** CONSTRUCTOR
         * You'll have to use this if you plan to get to the top of the comments list, as
         * start_lvl() only goes as high as 1 deep nested comments */
        function __construct() { ?>

            <h3><?php comments_number( __( 'No Responses to', THEME_TD ), __( 'One Response to', THEME_TD ), __( '% Responses to', THEME_TD ) ); ?> &#8220;<?php the_title(); ?>&#8221;</h3>
            <ol class="comment-list">

        <?php }
        /** START_LVL
         * Starts the list before the CHILD elements are added. */
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $GLOBALS['comment_depth'] = $depth + 1; ?>

        <ul class="children">
    <?php }
        /** END_LVL
         * Ends the children list of after the elements are added. */
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $GLOBALS['comment_depth'] = $depth + 1; ?>

        </ul><!-- /.children -->

    <?php }
        /** START_EL */
    function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
        $depth++;
        $GLOBALS['comment_depth'] = $depth;
        $GLOBALS['comment'] = $comment;
        $parent_class = ( empty( $args['has_children'] ) ? '' : 'parent' ); ?>

        <li <?php comment_class( $parent_class ); ?> id="comment-<?php comment_ID() ?>">
        <article id="comment-body-<?php comment_ID() ?>" class="comment-body">



            <header class="comment-author">

                <?php echo get_avatar( $comment, $args['avatar_size'] ); ?>

                <div class="author-meta vcard author">

                    <?php printf( __( '<cite class="fn">%s</cite>', THEME_TD ), get_comment_author_link() ) ?>
                    <time datetime="<?php echo comment_date( 'c' ) ?>"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf( __( '%1$s', THEME_TD ), get_comment_date(),  get_comment_time() ) ?></a></time>

                </div><!-- /.comment-author -->

            </header>

            <section id="comment-content-<?php comment_ID(); ?>" class="comment">
                <?php if ( ! $comment->comment_approved ) : ?>
                    <div class="notice">
                        <p class="bottom"><?php _e( 'Your comment is awaiting moderation.', THEME_TD ); ?></p>
                    </div>
                <?php else : comment_text(); ?>
                <?php endif; ?>
            </section><!-- /.comment-content -->

            <div class="comment-meta comment-meta-data hide">
                <a href="<?php echo htmlspecialchars( get_comment_link( get_comment_ID() ) ) ?>"><?php comment_date(); ?> at <?php comment_time(); ?></a> <?php edit_comment_link( '(Edit)' ); ?>
            </div><!-- /.comment-meta -->

            <div class="reply">
                <?php $reply_args = array(
                    'depth' => $depth,
                    'max_depth' => $args['max_depth'],
                );
                comment_reply_link( array_merge( $args, $reply_args ) );  ?>
            </div><!-- /.reply -->
        </article><!-- /.comment-body -->

    <?php }
    function end_el(& $output, $comment, $depth = 0, $args = array() ) { ?>

        </li><!-- /#comment-' . get_comment_ID() . ' -->

    <?php }
        /** DESTRUCTOR */
        function __destruct() { ?>

            </ol><!-- /#comment-list -->

        <?php }
    }
endif;
?>
