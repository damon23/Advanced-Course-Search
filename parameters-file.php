function cmp($a, $b){

	$a = strtotime($a['sdate']);
	$b = strtotime($b['sdate']);

	if ($a == $b) {
		return 0;
	}
	return ($a < $b) ? -1 : 1;
}

$archive_data = get_queried_object();
//echo '<pre>';print_r( $archive_data );
$archiveId = $archive_data->term_id;
$categroy_name =  $archive_data->slug;

?>

	<div id="content" class="content" role="main" style="margin-top: 40px;">
    

                        <?php if((isset($_GET['search']) && $_GET['search']!='')) :?>
                            <?php
                            $s_category = $_REQUEST['category'];
                            $s_venue = $_REQUEST['city'];
                            $s_date = $_REQUEST['date'];
                            $s_month = $_REQUEST['month'];                            
                            $subseminars_found = euromatech_search_subseminars( $s_category, $s_venue, $s_date, $s_month);
                            ?>
                        
                        <?php else: ?>
                            <?php
                                $subseminars_found = euromatech_search_subseminars( '', '', '', '');
                            ?>
                        <?php endif;?>
                        <div id="seminar-list">
                            <?php include ('display-courses.php'); ?>
                        </div>
                        
    </div>  
