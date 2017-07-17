<?php 
function euromatech_posts_where_post_title_like( $where, &$wp_query )
{
    global $wpdb;
    if ( !empty($wp_query->get('euromatech_post_title_like')) ) {
        if(false === strpos( $wp_query->get('euromatech_post_title_like') , '&')) {
            $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $wp_query->get('euromatech_post_title_like') ) ) . '%\'';
        } else {
            // search for both & or &amp; in post title
            $title = $wp_query->get('euromatech_post_title_like');
            $title2 = str_replace('&', '&amp;', $title);
            $where .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $title ) ) . '%\' ';
            $where .= ' OR ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $title2 ) ) . '%\' )';
        }
    }
    return $where;
} 




/**
 * @param $s_category
 * @param $s_venue
 * @param $s_date
 * @return array
 */
function euromatech_search_subseminars($s_category='', $s_venue='', $s_date='', $s_month='')
{
    $tax_query = array(
        'relation' => 'AND',
    );

    if (isset($s_category) && $s_category != '') {
        $cat_arry = array(
            'taxonomy' => 'course_category',
            'field' => 'slug',
            'terms' => "" . $s_category . ""
        );
        array_push($tax_query, $cat_arry);
    }

    if (isset($s_venue) && $s_venue != '') {
        $venue_arry = array(
            'taxonomy' => 'course_venues',
            'field' => 'slug',
            'terms' => "" . $s_venue . ""
        );
        array_push($tax_query, $venue_arry);

    }

    if (isset($s_month) && $s_month != '') {
        $month_arry = array(
            'taxonomy' => 'course_month',
            'field' => 'slug',
            'terms' => "" . $s_month . ""
        );
        array_push($tax_query, $month_arry);
    }

    $args = array(
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'post_type' => 'dt_portfolio',
        'tax_query' => $tax_query,
    );

    if(isset($_REQUEST['seminar']) and '' != $_REQUEST['seminar']){
        $args['euromatech_post_title_like'] = $_REQUEST['seminar'];
        add_filter( 'posts_where', 'euromatech_posts_where_post_title_like', 10, 2 );
    }
    
    $subseminars_found = array();

    $the_query = new WP_Query($args);
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {

            $the_query->the_post();
            $sub_seminars = get_field('sub_seminars', get_the_ID());

            foreach ($sub_seminars as $subseminar) {
                $start_date = $subseminar['start_date'];
                $filtermonth = date('F Y', strtotime($start_date));
                $end_date = $subseminar['end_date'];
                $venue = $subseminar['venue'];
                $fees = $subseminar['fees'];
                $ref = $subseminar['ref'];
                $venue_tax = get_term_by('name', $venue, 'course_venues');
                if ($venue_tax instanceof WP_Error) {
                    // in case that venue is not set for this sub seminar
                    $venue_slug = false;
                    $venue_name = '';
                } else {
                    $venue_slug = $venue_tax->slug;

 
                    $venue_name = $venue_tax->name;

                }

                $month_tax = get_term_by('name', $filtermonth, 'course_month');
                if ($month_tax instanceof WP_Error) {
                    // in case that month is not set for this sub seminar
                    $month_slug = false;
                    $month_name = '';
                } else {
                    $month_slug = $month_tax->slug;
 
                    $month_name = $month_tax->name;
                    
                }


                /*
                 *  Filter by values defined in subseminars since those conditions are not included in wp_query
                 */

                if (empty($start_date)) {
                    continue;
                }

                if (empty($end_date)) {
                    continue;
                }

                // filter past seminars
                if (strtotime(date('Ymd')) >= strtotime($subseminar['start_date'])) {
                    continue;
                }

                // filter by venue
                if ($s_venue != '' && $s_venue != $venue_slug) {
                    continue;
                }

                // filter by month
                if ($s_month != '' && $s_month != $month_slug) {
                    continue;
                }

                // filter by date
                if ($s_date != '' && !(strtotime($s_date) >= strtotime($start_date) && strtotime($s_date) <= strtotime($end_date))) {
                    continue;
                }

                $start_month = date('M Y', strtotime($start_date));
                $subseminar['seminarID'] = get_the_ID();
                $subseminar['duration'] = date_diff(date_create($start_date), date_create($end_date))->days;
                $subseminar['venue_name'] = $venue_name;                
                $subseminars_found[strtotime($start_month)][strtotime($start_date)][] = $subseminar;
            }

        }
    }

    $subseminars_found = euromatech_sort_seminar_resulsts($subseminars_found);
    return $subseminars_found;
} ?>
