<?php
/**
 * Speed: product counts in the catalog filter (owner 19.11.2026: «повырезать лишнее, чтобы шустро грузилось»).
 * The theme's grid filter (Impreza us-core, functions/grid.php) counts the products under EVERY filter value
 * (genre, BPM, key…) with a full WP_Query on every page view — ~325 database queries on /en/all-products/.
 * Products change rarely, so each count is remembered (one stored list, 12 h) and the list is cleared whenever a
 * product is saved, deleted or its stock/status changes. Same numbers, same filter; the counting itself is the
 * theme's own code (copied below), only cached.
 * Loaded from the plugin's main file, i.e. before us-core (plugins load alphabetically), so this version is used.
 * Off: define( 'SS_GRID_CACHE_OFF', true ) in wp-config.php.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
if ( defined( 'SS_GRID_CACHE_OFF' ) && SS_GRID_CACHE_OFF ) return;

if ( ! function_exists( 'us_grid_filter_get_count_items' ) ) {
	function us_grid_filter_get_count_items( $query_args, $selected_taxonomies = array() ) {
		static $mem = null, $dirty = false;
		if ( empty( $query_args ) || ! is_array( $query_args ) ) return 0;
		$k = md5( wp_json_encode( array( $query_args, $selected_taxonomies, function_exists( 'pll_current_language' ) ? pll_current_language() : '' ) ) );
		if ( null === $mem ) {
			$mem = get_transient( 'ss_grid_counts' );
			if ( ! is_array( $mem ) ) $mem = array();
			add_action( 'shutdown', function () use ( &$mem, &$dirty ) { if ( $dirty ) set_transient( 'ss_grid_counts', array_slice( $mem, -3000, null, true ), 12 * HOUR_IN_SECONDS ); }, 1 );
		}
		if ( isset( $mem[ $k ] ) ) return (int) $mem[ $k ];
		$n = ss_grid_count_uncached( $query_args, $selected_taxonomies );
		$mem[ $k ] = (int) $n; $dirty = true;
		return (int) $n;
	}

	function ss_grid_count_uncached( $query_args, $selected_taxonomies = array() ) {
		if ( empty( $query_args ) OR ! is_array( $query_args ) ) {
			return 0;
		}
		if (
			! empty( $query_args['post_type'] )
			AND (
				(
					is_array( $query_args['post_type'] )
					AND in_array( 'product', $query_args['post_type'] )
				)
				OR $query_args['post_type'] == 'product'
			)
			AND class_exists( 'woocommerce' )
			AND is_object( wc() )
		) {
			if ( ! isset( $query_args['tax_query'] ) ) {
				$query_args['tax_query'] = array();
			}
			$query_args['tax_query'] = wc()->query->get_tax_query( $query_args['tax_query'] );
		}

		// Remove duplicate fields in tax_query
		$tax_maps = array();
		foreach ( us_arr_path( $query_args, 'tax_query', array() ) as $index => $tax ) {
			$field = us_arr_path( $tax, 'taxonomy' );
			$taxonomy = us_arr_path( $tax, 'taxonomy' );
			if ( is_null( $taxonomy ) ) {
				continue;
			} elseif ( ! isset( $tax_maps[ $taxonomy ][ $field ] ) ) {
				$tax_maps[ $taxonomy ][ $field ] = $index;
				continue;
			} elseif ( isset( $query_args['tax_query'][ $index ] ) )  {
				unset( $query_args['tax_query'][ $index ] );
			}
		}

		// Remove duplicate fields in meta_query
		$meta_maps = array();
		foreach ( us_arr_path( $query_args, 'meta_query', array() ) as $index => $meta ) {
			if ( $index === 'relation' ) {
				continue;
			}

			if ( $key = us_arr_path( $meta, 'key' ) ) {
				if ( ! isset( $meta_maps[ $key ] ) ) {
					$meta_maps[ $key ] = $index;
				} elseif ( isset( $query_args['meta_query'][ $index ] ) ) {
					unset( $query_args['meta_query'][ $index ] );
				}
			} elseif ( isset( $meta[0] ) ) {
				$keys = array();
				array_walk_recursive(
					$meta, function ( $value, $key ) use ( &$keys ) {
					if ( $key === 'key' ) {
						$keys[] = $value;
					}
				}
				);
				$keys = array_unique( $keys );
				foreach ( $keys as $key ) {
					if ( isset( $meta_maps[ $key ] ) ) {
						unset( $query_args['meta_query'][ $index ] );
					} else {
						$meta_maps[ $key ] = $index;
					}
				}
			}
		}
		unset( $tax_maps, $meta_maps );

		foreach ( array( 'tax_query', 'meta_query' ) as $key ) {
			if ( ! empty( $selected_taxonomies[ $key ] ) AND is_array( $selected_taxonomies[ $key ] ) ) {
				$query_args[ $key ] = array_merge( $query_args[ $key ], $selected_taxonomies[ $key ] );
			}
		}

		if ( $query_args['post_type'] == 'current_child_pages' ) {
			$query_args['post_type'] = 'any';
		}

		$query_args['fields'] = 'ids'; $query_args['no_found_rows'] = true; $query_args['update_post_meta_cache'] = false; $query_args['update_post_term_cache'] = false;
		return ( new WP_Query( $query_args ) )->post_count;
	}
}
/* any product change → counts are made again */
foreach ( array( 'save_post_product', 'deleted_post', 'trashed_post', 'woocommerce_product_set_stock_status', 'set_object_terms' ) as $ss_h ) {
	add_action( $ss_h, function () { delete_transient( 'ss_grid_counts' ); } );
}
unset( $ss_h );
