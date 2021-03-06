<?php

if ( ! function_exists( 'norebro_setup' ) ) :

	function norebro_setup_enqueue(){
		wp_enqueue_script( 'hide-how-to-notification', get_template_directory_uri() . '/js/hide-how-to-notification.js' );
	}

	function norebro_setup() {
		load_theme_textdomain( 'norebro', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'woocommerce' );
		set_post_thumbnail_size( 200, 200, true );

		add_image_size( 'norebro_thumbnail_next_and_prev', 200, 140, true );

		add_image_size( 'norebro_full', 1920, 9999, false );

		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'norebro' ),
		) );

		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
		add_theme_support( 'post-formats', array( 'video', 'gallery', 'audio', 'quote' ) );

		$GLOBALS['content_width'] = apply_filters( 'norebro_content_width', 640 );
		
		$GLOBALS['norebro_google_fonts'] = array();
		$GLOBALS['norebro_icon_fonts'] = array();
		$GLOBALS['norebro_required_scripts'] = array();

		if ( ! get_option( 'norebro_version' ) || get_option( 'norebro_version' ) < 10 ) {
			add_option( 'norebro_version', 10, '', 'yes' );
		}

		$show_how_to = get_option( 'norebro_how_to_notification' );
		$show_how_to = ( ! $show_how_to || ( strlen( $show_how_to ) > 0 && $show_how_to != 'hidden' ) );

		if ( isset( $_COOKIE['norebro_how_to_closed'] ) && $_COOKIE['norebro_how_to_closed'] == 'yep' ) {
			update_option( 'norebro_how_to_notification', 'hidden' );
			$show_how_to = false;
		}
		if ( !$show_how_to ) {
			add_action( 'admin_enqueue_scripts', 'norebro_setup_enqueue' );
			add_action( 'admin_notices', 'norebro_admin_notice_how_to' );
		}

        // Adding support for core block visual styles.
        add_theme_support( 'wp-block-styles' );

        // Add support for full and wide align images.
        add_theme_support( 'align-wide' );

        // Add support for custom color scheme.
        // add_theme_support( 'disable-custom-colors' );

        $brand_color = NorebroSettings::get( 'page_brand_color', 'global' );


        if(!$brand_color){
            $brand_color = '#505cfd';
        }

        add_theme_support( 'editor-color-palette', array(
            array(
                'name'  => __( 'Brand color', 'norebro' ),
                'slug'  => 'brand-color',
                'color' => $brand_color,
            ),
            array(
                'name'  => __( 'Blue Dark', 'norebro' ),
                'slug'  => 'blue_dark',
                'color' => '#505cfd',
            ),
            array(
                'name'  => __( 'Dark Strong', 'norebro' ),
                'slug'  => 'dark_strong',
                'color' => '#24262B',
            ),
            array(
                'name'  => __( 'Dark Light', 'norebro' ),
                'slug'  => 'dark_light',
                'color' => '#32353C',
            ),
            array(
                'name'  => __( 'Grey Strong', 'norebro' ),
                'slug'  => 'grey_strong',
                'color' => '#6A707E',
            ),
            array(
                'name'  => __( 'Grey Light', 'norebro' ),
                'slug'  => 'grey_light',
                'color' => '#949597',
            ),
        ) );


        // Add support for custom sizes
        // add_theme_support('disable-custom-font-sizes');
        add_theme_support( 'editor-font-sizes', array(
            array(
                'name' => __( 'Extra Small', 'norebro' ),
                'size' => 12,
                'slug' => 'extra-small'
            ),
            array(
                'name' => __( 'Small', 'norebro' ),
                'size' => 13,
                'slug' => 'small'
            ),
            array(
                'name' => __( 'Normal', 'norebro' ),
                'size' => 14,
                'slug' => 'normal'
            ),
            array(
                'name' => __( 'Large', 'norebro' ),
                'size' => 17,
                'slug' => 'large'
            ),
            array(
                'name' => __( 'Extra Large', 'norebro' ),
                'size' => 20,
                'slug' => 'larger'
            )
        ) );




        // Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

        // Add editor styles support
        add_editor_style( 'assets/style_editor/style-editor.css' );
		add_theme_support('editor-styles');

	}

endif;

add_action( 'after_setup_theme', 'norebro_setup' );


function norebro_admin_notice_how_to() {
?>
	<div class="notice notice-warning norebro-admin-notif">
		<p>
			<strong><?php esc_html_e( 'Hi! Are you ready to take the first step with Norebro?', 'norebro' ); ?></strong><br>
			<?php esc_html_e( 'Before you get started, we recommend learn basics of working with this theme.', 'norebro' ); ?>
		</p>
		<p class="links">
			<a class="button-primary" href="#" target="_blank"><i class="ion-share"></i> <?php esc_html_e( 'HowTo Videos', 'norebro' ); ?></a>
			<a class="button-primary" href="#" target="_blank"><i class="ion-share"></i> <?php esc_html_e( 'FAQ', 'norebro' ); ?></a>
			<a class="button-primary" href="#" target="_blank"><i class="ion-share"></i> <?php esc_html_e( 'Documentation', 'norebro' ); ?></a>
			<a class="button-secondary" href="#" id="close_norebro_how_to"><?php esc_html_e( 'Hide Message', 'norebro' ); ?></a>
		</p>
	</div>
<?php
}



add_filter( 'pre_get_posts', 'custom_post_type_search' );
function custom_post_type_search( $query ) {
    if ($query->is_search) {
        $query->set('post_type', array( 'post', 'page', 'norebro_portfolio'));
    }
    return $query;
}

