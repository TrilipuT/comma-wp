<?php
/*
Plugin Name: CloudFront
Description: Change all static files urls to CloudFront CDN domain
Version: 2.0
Plugin URI: https://github.com/shtrihstr/wp-cloudfront
Author: Oleksandr Strikha
Author URI: https://github.com/shtrihstr
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if( defined( 'AWS_CLOUDFRONT_DOMAIN' ) ) {

    if( ! function_exists( 'get_cloudfront_attachment_url' ) ) {

        function get_cloudfront_attachment_url( $uri, $in_text = false ) {

            if( function_exists( 'current_user_can' ) && current_user_can( 'edit_posts' ) && is_admin() ) {
                return $uri;
            }

            // get site hosts
            if ( false === ( $hosts = get_transient( 'cf-site-hosts' ) ) ) {

                $hosts = [ parse_url( home_url(), PHP_URL_HOST )] ;

                if ( defined( 'DOMAIN_MAPPING' ) ) {

                    global $wpdb, $blog_id;
                    $domains1 = $wpdb->get_col( "SELECT domain FROM {$wpdb->blogs} WHERE blog_id = '$blog_id'" );

                    $mapping = $wpdb->base_prefix . 'domain_mapping';
                    $domains2 = $wpdb->get_col( "SELECT domain FROM {$mapping} WHERE blog_id = '$blog_id'" );
                    $hosts = array_merge( $hosts, $domains1, $domains2 );
                }

                $hosts = array_unique( $hosts );
                set_transient( 'cf-site-hosts', $hosts, HOUR_IN_SECONDS );
            }

            $regex_hosts = implode( '|', array_map( 'preg_quote', $hosts ) );

            $ext = apply_filters( 'cloudfront_ext', [
                // images
                'png', 'jpg', 'jpeg', 'gif', 'tif', 'bmp',
                // assets
                'css', 'js',
                // archives
                'zip', 'gz', 'tar',
                // fonts
                'ttf', 'eot', 'svg', 'woff', 'woff2',
                // video
                'webm', 'mp4', 'm4v',
                //audio
                'mp3', 'wav',
            ] );

            $regex_ext = implode( '|', array_map( 'preg_quote', $ext ) );

            $regex = "https?:\/\/($regex_hosts)\/wp-content\/uploads\/(([^\"^']+)\.($regex_ext))";
            if ( ! $in_text ) {
                $regex = '^' . $regex;
            }

            $cf_url = 'http://' . AWS_CLOUDFRONT_DOMAIN;

            return preg_replace( "/$regex/Ui", "$cf_url/$2", $uri );
        }
    }

    $filters = [
        //'template_directory_uri',
        //'stylesheet_directory_uri',
//        'script_loader_src',
//        'style_loader_src',
        'wp_get_attachment_url'
    ];

    foreach ( $filters as $filter ) {
        add_filter( $filter, 'get_cloudfront_attachment_url', 999 );
    }

    add_filter( 'the_content', function ( $content ) {
        return get_cloudfront_attachment_url( $content, true );
    }, 999);


    add_filter( 'wp_calculate_image_srcset', function( $sources ) {
        foreach ( $sources as $key => $source ) {
            $sources[ $key ]['url'] = get_cloudfront_attachment_url( $source['url'] );
        }
        return $sources;
    } );


    $add_version_to_url = function( $url ) {
	if ( false !== mb_strpos( $url , '/wp-includes/' ) ) {
            return $url;
        }
        return add_query_arg( 'ver', get_site_option( 'cf-assets-version', '1.0' ), $url );
    };

    add_filter( 'script_loader_src', $add_version_to_url );
    add_filter( 'style_loader_src', $add_version_to_url );

    add_filter( 'editor_stylesheets', function( $stylesheets ) use ( $add_version_to_url ) {
        return array_map( $add_version_to_url, $stylesheets );
    } );

    add_action( 'muplugins_loaded', function() {
        if( function_exists( 'mu_add_flush_button' ) ) {
            mu_add_flush_button( __( 'Assets cache' ), function() {
                update_site_option( 'cf-assets-version',  sprintf( '%0.1f', get_site_option( 'cf-assets-version', '1.0' ) + 0.1 ) );
            } );
        }
    } );

}


