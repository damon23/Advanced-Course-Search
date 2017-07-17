<form name="search-fomr" action="<?php echo site_url().'/parameters-file/'; ?>" method="get">
      <input type="text" name="seminar" placeholder="Search our seminars" class="seminars-input">
<select name="category" class="category-select">
       <option value="">Category</option>
       <?php  
       $terms = get_terms( 'course_category','hide_empty=0' );
       if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){


           foreach ( $terms as $term ) {
            $term_link = get_term_link( $term );
            echo '<option value="' . $term->slug . '"><a href=>' . $term->name . '</option>';

        }

    }
		 // Code for Downloads
    ?>
</select>
<select name="city" class="category-select">
   <option value="">City</option>
   <?php  $terms = get_terms( 'course_venues','hide_empty=0' );
   if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){

       foreach ( $terms as $term ) {
           echo '<option value="' . $term->slug . '"><a href=>' . $term->name . '</option>';

    }

}?>
</select>

<select name="month" class="category-select">
   <option value="">Month</option>
   <?php  $terms = get_terms( 'course_month','hide_empty=0' );
   if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){

       foreach ( $terms as $term ) {
       echo '<option value="' . $term->slug . '"><a href=>' . $term->name . '</option>';

    }

}?>
</select>
<input type="submit" id="search" name="search" value="Search" class="search-btn">
</form>	
