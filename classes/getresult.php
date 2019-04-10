<?php

/**
 * script ajax
 * connect to wordpress database
 * retunr result in html to view
 */
include_once('../../../../wp-config.php');
include_once('../../../../wp-load.php');
include_once('../../../../wp-includes/wp-db.php');

$table_name = $wpdb->prefix . "custom_map";

require_once("pagination.class.php");
$perPage       = new sbpagination();

$sqlquery       = "SELECT * from $table_name";

$page = 1;
if(!empty($_GET["page"])) {
$page = $_GET["page"];
}

$start = ($page-1)*$perPage->perpage;
if($start < 0) $start = 0;

$query   =  $sqlquery . " limit " . $start . "," . $perPage->perpage; 

$getData = $wpdb->get_results( $query );


$rowcount=$wpdb->query( $sqlquery);

$showpagination = $perPage->getAllPageLinks($rowcount);	

$output = '';
$output .= '<div class="panel-group" id="accordion">';
 
           foreach ($getData as $key=> $data)
                        {
                          $imgsrc='';
                            if(!empty($data->URLLogo)){
                                $imgsrc.='<img style="float: left;" height="134" width="80" src="http://appli.newtritioncoach.com/'.$data->URLLogo.'">';
                            }
                            ?>
                   <div class="panel panel-default">
                    <div class="panel-heading">
                      <?php echo $imgsrc?>
                       <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $key?>">
                  <?php echo $data->Nom ?>
                </a>
      </h4>
    </div>
    <div id="collapse<?php echo $key?>" class="panel-collapse collapse ">
      <div class="panel-body">
               <div class="col-md-8 team-bio column">
                    <a href="<?php echo $data->URLSite ?>" class="name-trainer"><h2> <?php echo $data->Nom ?></h2></a>
                   <p><?php echo htmlspecialchars( $data->keyword) ?><br></p>
                    <a class="coach_link_css btn btn-primary theme-btn btn-sm btn-presentation" href="https://www.bc-training.be/personal-trainer/yoann-capilla/"><i class="fa fa-user"></i> Présentation</a>
        </div>
      </div>
    </div>
  </div>
                    <?php }    
$output .= '</div>';
if(!empty($showpagination))
{
	$output .= '<ul class="pagination">'.$showpagination.'</ul>';
}
echo $output;
die();
?>