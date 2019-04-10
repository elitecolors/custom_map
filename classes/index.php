<?php
/**
 * main page
 * liste of coach with pagination
 */
?>
    <style type="text/css">
    #overlay {background-color: rgba(0, 0, 0, 0.6);z-index: 999;position: absolute;left: 0;top: 0;width: 100%;height: 100%;display: none;}
    #overlay div {position:absolute;left:50%;top:50%;margin-top:-32px;margin-left:-32px;}
    </style>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $(document).on("click", ".pagination li a ", function(){
                $.ajax({
                        url: $('#url_plugin').val()+"classes/getresult.php",
                        type: "GET",
                        data:  {page:$(this).attr("data-page")},
                        beforeSend: function(){$("#overlay").show();},
                        success: function(data)
                        {
                            $("#pagination-result").html(data);
                            setInterval(function() {$("#overlay").hide(); },500);
                        },
                        error: function() 
                        {}          
                   });
            });
        });
    </script>
    <div id="overlay"><div><img src="<?php echo MAP__PLUGIN_URL?>loading.gif" width="100px" height="100px"/></div></div>
    <input type="hidden" value="<?php echo MAP__PLUGIN_URL ?>" id="url_plugin" name="">
    <?php
    require_once("pagination.class.php");
    global $wpdb;
    $perPage       = new sbpagination();
    $table_name = $wpdb->prefix . "custom_map";
    $rowcount=$wpdb->query( "SELECT * FROM $table_name" );
    $sqlquery      = "SELECT * from $table_name ";
    $query         = $sqlquery."limit 0," . $perPage->perpage; 
    $getData       = $wpdb->get_results( $query);
    $showpagination = $perPage->getAllPageLinks($rowcount);
    
    ?>
    <div class="container col-md-12" id="list_coach">
        <h2 style="text-align: center;"><?php echo  __( "Liste des coachs", 'custom_map' )?></h2>
            <div id="pagination-result">      
               <div class="panel-group" id="accordion">
                        <?php
                        foreach ($getData as $key=> $data)
                        {
                            $imgsrc='';
                            if(!empty($data->URLLogo)){
                                $imgsrc.='<img style="float: left;" height="134"  width="80" src="http://appli.newtritioncoach.com/'.$data->URLLogo.'">';
                            }
                            
                            ?>
                              <div class="panel panel-default">
                                 <div class="panel-heading">
                                   <?php echo $imgsrc?>
                                    <h5 class="panel-title">
                                           <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $key?>">
          <?php echo $data->Nom ?>
        </a>
      </h5>
    </div>
    <div id="collapse<?php echo $key?>" class="panel-collapse collapse ">
      <div class="panel-body">
        <div class="col-md-8 team-bio column">
                    <a href="<?php echo $data->URLSite ?>" class="name-trainer"><h2> <?php echo $data->Nom ?></h2></a>
                    
                  <p><?php echo htmlspecialchars( $data->keyword) ?><br></p>
                    <a class="coach_link_css btn btn-primary theme-btn btn-sm btn-presentation" href=""><i class="fa fa-user"></i> Présentation</a>
                  </div>
      </div>
    </div>
  </div>
    <?php }     ?>
                </div>
                <?php
                if(!empty($showpagination))
                {
                    ?>
                    <ul class="pagination">
                        <?php echo $showpagination; ?>
                    </ul>
                    <?php
                }
                ?>
            </div>
    </div>
