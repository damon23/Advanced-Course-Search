
<?php 
$archive_data = get_queried_object();
//echo '<pre>';print_r( $archive_data );
$archiveId = $archive_data->term_id;
$categroy_name =  $archive_data->slug;
$subseminars_found = euromatech_search_subseminars( $archive_data->slug, '', '');  

if(!empty($subseminars_found)){
    foreach($subseminars_found as $month => $day){
        ?>           
            <h3 class="year-title"><?php echo date('M Y', $month); ?></h3>
                                 
                    <?php

                    foreach($day as $subseminars){
                        foreach($subseminars as $subseminar) {
                            ?>
                            
                            <article style="border-bottom: 1px solid #cecece; padding: 15px;">
                           

                    <div>
                                <a href="<?php the_permalink($subseminar['seminarID']); ?>">
                                        <h4 class="entry-title" style="margin: 0px; color: #044970;"><?php echo get_the_title($subseminar['seminarID']); ?></h4><h6 style="color:#044970;"> <?php if (get_field('seminar_sub_title', $subseminar['seminarID'])) : ?> <?php echo get_field('seminar_sub_title', $subseminar['seminarID']); ?><?php endif; ?></h6></a>


                                <table class="no-border" style=" border-collapse: collapse; table-layout: fixed; width: 100%;">
                                <tbody>
                                <tr>
                                    <td>Reference</td>
                                    <td >Dates</h6></td>
                                    <td >Venue</h6></td>
                                    <td>Fees</h6></td>
                                </tr>
                                <tr>
                                <td><?php echo esc_html($subseminar['ref']); ?></td>
                                <td><?php
                                 if(date("m", strtotime($subseminar['start_date'])) != date("m", strtotime($subseminar['end_date'])))
                                {


                                 echo date('d M', strtotime($subseminar['start_date'])) . ' - ' . date('d M', strtotime($subseminar['end_date'])); 
                                     
                                 }


                               else
                                {
                                echo date('d', strtotime($subseminar['start_date'])) . ' - ' . date('d M', strtotime($subseminar['end_date']));
                                }
                  
                                ?></h6></td>
                                <td><?php echo esc_html($subseminar['venue']); ?></td>
                                <td><?php echo esc_html($subseminar['fees']); ?></td>
                                <td ><a href="<?php home_url(); ?>/seminar-registrations?seminartitle=<?php echo htmlentities(urlencode(get_the_title($subseminar['seminarID']))) ?>&amp;seminarvenue=<?php echo esc_html($subseminar['venue']); 
            ?>&amp;seminardates=<?php echo date("d M", strtotime($subseminar['start_date'])).' - '.date("d M Y", strtotime($subseminar['end_date'])); ?>&amp;seminarref=<?php echo esc_html($subseminar['ref']); ?>" class="dt-btn">Register Now</a></td>
                                </tr>
                                </tbody>
                                </table>
                                                               
                    </div> 
                    </article>       
                            <?php
                        }
                    }
                    ?>
                
                

        <?php
    }

} else {

    echo '<h4>No Upcoming Seminars found</h4>';
        echo '<p>For further information on forthcoming events in this region, please contact <a href="mailto:',ot_get_option('email_address', ''),'">',ot_get_option('email_address', ''),'</a></p>';
}

?>

