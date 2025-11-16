<?php
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style(
    'tt4c-style',
    get_stylesheet_directory_uri() . '/assets/build/style.css',
    [],
    filemtime(get_stylesheet_directory() . '/assets/build/style.css')
  );
}, 20);

add_action('after_setup_theme', function () {
  remove_theme_support('block-templates');
}, 11);


function bd_get_category_chain( $term = null ) {
    if ( $term === null ) {

        if ( is_category() ) {
            $term = get_queried_object();
        } elseif ( is_single() ) {
            $cats = get_the_category();
            if ( empty( $cats ) ) {
                return [];
            }
            $term = $cats[0];
        } else {
            return [];
        }
    }

    if ( is_numeric( $term ) ) {
        $term = get_term( $term, 'category' );
    }

    if ( ! $term || is_wp_error( $term ) ) {
        return [];
    }

    $chain = [];
    $current = $term;

    while ( $current && ! is_wp_error( $current ) ) {
        $chain[] = $current;

        if ( ! $current->parent ) {
            break;
        }

        $current = get_term( $current->parent, 'category' );
    }

    $chain = array_reverse( $chain );

    return $chain;
}