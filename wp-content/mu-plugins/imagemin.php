<?php

if ( ! defined( 'MAX_IMAGE_SIZE' ) ) {
	define( 'MAX_IMAGE_SIZE', 2600 );
}

function imagemin_optimize_png( $filename ) {

	if ( false === ( $command = wp_cache_get( 'pngquant-bin', 'imagemin' ) ) ) {
		$command = @exec( 'which pngquant' );

		if ( $command && preg_match( '/\/pngquant$/', $command ) ) {
			wp_cache_set( 'pngquant-bin', $command, 'imagemin' );
		} else {
			$command = '';
			wp_cache_set( 'pngquant-bin', '', 'imagemin', MINUTE_IN_SECONDS );
		}
	}

	if ( empty( $command ) || ! preg_match( '/\/pngquant$/', $command ) ) {
		return false;
	}

	$command  = escapeshellarg( $command );
	$filename = escapeshellarg( $filename );
	@exec( "$command --quality=75-80 $filename --output $filename --force" );

	return true;
}

function imagemin_optimize_jpeg( $filename ) {

	if ( false === ( $command = wp_cache_get( 'jpegtran-bin', 'imagemin' ) ) ) {
		$command = @exec( 'which jpegtran' );

		if ( $command && preg_match( '/\/jpegtran$/', $command ) ) {
			wp_cache_set( 'jpegtran-bin', $command, 'imagemin' );
		} else {
			$command = '';
			wp_cache_set( 'jpegtran-bin', '', 'imagemin', MINUTE_IN_SECONDS );
		}
	}

	if ( empty( $command ) || ! preg_match( '/\/jpegtran$/', $command ) ) {
		return false;
	}

	$command  = escapeshellarg( $command );
	$filename = escapeshellarg( $filename );
	@exec( "$command -copy none -trim -optimize -progressive -outfile $filename $filename" );

	return true;
}


add_filter( 'jpeg_quality', function () {
	return 80;
} );

add_filter( 'wp_handle_upload', function ( $args ) {

	$filename = $args['file'];
	$type     = $args['type'];

	if ( 0 === mb_strpos( $type, 'image/' ) && file_exists( $filename ) ) {

		$size     = @getimagesize( $filename );
		$oversize = MAX_IMAGE_SIZE < $size[0] || MAX_IMAGE_SIZE < $size[1];
		if ( 'image/jpeg' == $type || $oversize ) {

			$editor = wp_get_image_editor( $filename );
			if ( ! is_wp_error( $editor ) ) {
				if ( $oversize ) {
					$editor->resize( MAX_IMAGE_SIZE, MAX_IMAGE_SIZE );
				}
				$editor->save( $filename );
			}

		}

		if ( 'image/png' == $type ) {
			imagemin_optimize_png( $filename );
		} elseif ( 'image/jpeg' == $type ) {
			imagemin_optimize_jpeg( $filename );
		}

	}

	return $args;
}, 999 );


add_filter( 'wp_generate_attachment_metadata', function ( $metadata ) {

	$uploads = wp_upload_dir();
	$dir     = dirname( $uploads['basedir'] . DIRECTORY_SEPARATOR . $metadata['file'] );

	foreach ( $metadata['sizes'] as $size ) {

		$filename = $dir . DIRECTORY_SEPARATOR . $size['file'];
		$type     = $size['mime-type'];

		if ( ! file_exists( $filename ) ) {
			continue;
		}

		if ( 'image/png' == $type ) {
			imagemin_optimize_png( $filename );
		} elseif ( 'image/jpeg' == $type ) {
			imagemin_optimize_jpeg( $filename );
		}

	}

	return $metadata;
}, 999 );
