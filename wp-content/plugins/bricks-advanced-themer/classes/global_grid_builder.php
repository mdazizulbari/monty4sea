<?php
namespace Advanced_Themer_Bricks;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AT__Grid_Builder{

    public static function grid_builder_classes() {

        $custom_css = '';

        if ( have_rows( 'field_63b59j871b209' , 'bricks-advanced-themer' ) ) :

            while ( have_rows( 'field_63b59j871b209' , 'bricks-advanced-themer' ) ) : the_row();

                if ( have_rows( 'field_63b48c6f1b20a', 'bricks-advanced-themer' ) ) :

                    $items = [];

                    $classes = [];

                    while ( have_rows( 'field_63b48c6f1b20a', 'bricks-advanced-themer' ) ) :

                        the_row();

                        $class = 'body .' . get_sub_field('field_63b48c6f1b20b', 'bricks-advanced-themer' );

                        $max_col = get_sub_field('field_63b48c6f1b20c', 'bricks-advanced-themer' );

                        $min_width = get_sub_field('field_63b48c6f1b20d', 'bricks-advanced-themer' );

                        $gap = explode(" ", get_sub_field('field_63b48d7e1b20e', 'bricks-advanced-themer' ));

                        if( count($gap) === 1 ){
                            $gap_col = $gap[0];
                            $gap_row = $gap[0];
                        } else {
                            $gap_col = $gap[0];
                            $gap_row = $gap[1];
                        }

                        $item = [$class, $max_col, $min_width, $gap_col, $gap_row];

                        $classes[] = $class;

                        $items[] = $item;

                    endwhile;

                    $imploded_classes = implode(',', $classes);

                    $custom_css .= $imploded_classes;
                    $custom_css .= '{display:grid;gap:var(--grid-layout-gap);grid-template-columns: repeat(auto-fit, minmax(min(100%, var(--grid-item--min-width)), 1fr));}@media screen and (min-width: 781px){';
                    $custom_css .= $imploded_classes;
                    $custom_css .= '{--gap-count: calc(var(--grid-column-count) - 1);--total-gap-width: calc(var(--gap-count) * var(--grid-layout-gap));--grid-item--max-width: calc((100% - var(--total-gap-width)) / var(--grid-column-count));grid-template-columns: repeat(auto-fill, minmax(max(var(--grid-item--min-width), var(--grid-item--max-width)), 1fr));}}';
                    foreach ( $items as $item ){
                        $custom_css .= $item[0] . '{--grid-column-count:' . $item[1] . ';--grid-item--min-width:' . $item[2] . 'px;--grid-layout-gap:' . $item[3] . ';}';
                    }

                endif;

            endwhile;

        endif;

        return $custom_css;

    }
}