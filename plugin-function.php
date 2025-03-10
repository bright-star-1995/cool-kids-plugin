<?php

    function hide_admin_bar() {
        show_admin_bar( false );
    }
    add_action( 'after_setup_theme', 'hide_admin_bar' );

    function custom_coolkid_rewrite_rule() {
        add_rewrite_rule( '^signup/?$', 'index.php?param=signup', 'top' );
        add_rewrite_rule( '^signin/?$', 'index.php?param=signin', 'top' );
        flush_rewrite_rules();
    }
    add_action( 'init', 'custom_coolkid_rewrite_rule' );

    function custom_coolkid_query_var( $vars ) {
        $vars[] = 'param';
        return $vars;
    }
    add_filter( 'query_vars', 'custom_coolkid_query_var' );

    function custom_coolkid_template_redirect() {
        $param = get_query_var( 'param' );
        $coolkidsApp = new CoolKidsApp\Controller\MainController();
        if ( $param == 'signup' ) {
            $coolkidsApp->signup();
            exit;
        } else if ( $param == 'signin' ) {
            $coolkidsApp->signin();
            exit;
        } else if ( $param == '' ) {
            $coolkidsApp->index();
            exit;
        } else {
            include( COOLKIDS_PLUGIN_BASEDIR . 'View/404.php' );
            exit;
        }
    }
    add_action( 'template_redirect', 'custom_coolkid_template_redirect' );

    if(!function_exists('wp_get_current_user')) {
        include(ABSPATH . "wp-includes/pluggable.php"); 
    }

    new CoolKidsApp\Controller\MainController();
    new CoolKidsApp\Controller\AdminController();

?>