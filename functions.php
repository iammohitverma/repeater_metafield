<?php
/**
 * justcoheader functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package justcoheader
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function justcoheader_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on justcoheader, use a find and replace
		* to change 'justcoheader' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'justcoheader', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary_menu' => __( 'Primary Menu', 'justcoheader' ),
		'footer_menu'  => __( 'Footer Menu', 'justcoheader' ),
	) );

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'justcoheader_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'justcoheader_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function justcoheader_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'justcoheader_content_width', 640 );
}
add_action( 'after_setup_theme', 'justcoheader_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
// function justcoheader_widgets_init() {
// 	register_sidebar(
// 		array(
// 			'name'          => esc_html__( 'Sidebar', 'justcoheader' ),
// 			'id'            => 'sidebar-1',
// 			'description'   => esc_html__( 'Add widgets here.', 'justcoheader' ),
// 			'before_widget' => '<section id="%1$s" class="widget %2$s">',
// 			'after_widget'  => '</section>',
// 			'before_title'  => '<h2 class="widget-title">',
// 			'after_title'   => '</h2>',
// 		)
// 	);
// }
// add_action( 'widgets_init', 'justcoheader_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function justcoheader_scripts() {
	wp_enqueue_style( 'justcoheader-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_enqueue_style( 'justcoheader-bootstrap-style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');
	wp_enqueue_style( 'justcoheader-custom-style', get_template_directory_uri() . '/assets/css/custom-style.css');
	wp_style_add_data( 'justcoheader-style', 'rtl', 'replace' );

	wp_enqueue_script( 'justcoheader-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'justcoheader_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

add_filter('use_block_editor_for_post', '__return_false', 10);

function example_theme_support() {
    remove_theme_support( 'widgets-block-editor' );
}
add_action( 'after_setup_theme', 'example_theme_support' );

// added by mv for header
// add caret for submenus
function dynamicwp_menu_arrow($item_output, $item, $depth, $args) {
	if (in_array('menu-item-has-children', $item->classes)) {
		$arrow = '<button class="subMenuAngle"></button>'; // Change the class to your font icon
		$item_output = str_replace('</a>', '</a>'. $arrow .'', $item_output);
	}
	return $item_output;
}
add_filter('walker_nav_menu_start_el', 'dynamicwp_menu_arrow', 10, 4);


// for footer

/**
 * Add a sidebar.
 */
function wpdocs_theme_slug_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer First', 'textdomain' ),
		'id'            => 'footer_first',
		'description'   => __( 'This is First Footer Widget.', 'justco' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Second', 'textdomain' ),
		'id'            => 'footer_second',
		'description'   => __( 'This is second Footer Widget.', 'justco' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Third', 'textdomain' ),
		'id'            => 'footer_third',
		'description'   => __( 'This is third Footer Widget.', 'justco' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Fourth', 'textdomain' ),
		'id'            => 'footer_fourth',
		'description'   => __( 'This is fourth Footer Widget.', 'justco' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'wpdocs_theme_slug_widgets_init' );



// metabox_repeater
require get_template_directory() . '/inc/metabox_repeater.php';
