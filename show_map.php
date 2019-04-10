<?php
/**
 * principal view
 * show map
 * include liste of coach
 */

$filename =MAP__PLUGIN_URL. 'js/location.js';
$options = get_option('map_settings');
if(empty($options) || empty($options['map_key'])){
  echo __( "Il faut ajouter Map KEY", 'custom_map' );
  die();
}
$map_key =$options['map_key'];
$js_text_ok=__( "Voici les coachs qui correspondent à votre recherche et qui se situent dans les _klm kilomètres autour de _adr", 'custom_map' );
$js_text_invalid_adress=__( "Invalid adress", 'custom_map' );
$map_zoom=get_option('map_zoom');
?>
    <div class="content custom_map_form">
     
      <div class="form-group custom_map_css">
        <div class="col-sm-3 first_input_map_css" >
        <h5 style="font-size: 12px;text-align: center;word-break: break-word;"><?php echo  __( "Choisissez le rayon de recherche autour de votre domicile", 'custom_map' )?></h5>
        <input type="number"  value="5" min="1"  id="maxRadius" class="input form-control" name="maxRadius"  placeholder="">
      </div>

      <div id="" class="col-sm-5 css_2_input_map" >
        <h5 style="font-size: 12px;text-align: center;word-break: break-word;"><?php echo  __( "Entrez votre code postal ou votre ville pour trouver le ou les coachs en nutrition les plus proches de votre domicile !", 'custom_map' )?></h5>
         <input type="text" name="txtCountry" placeholder="<?php echo  __( "Code postal ou ville ", 'custom_map' )?>" class="typeahead form-control"/>
      </div>
      <input id="userAddress" name="adress_map" type="hidden" value="">
        <button id="submitLocationSearch" class="btn btn-success btn-lg"><?php echo  __( "Valider ma recherche", 'custom_map' )?></button>
      </div>

      <h2 style="font-size: 13px;" id="location-search-alert"><?php echo  __( "Tous les coachs", 'custom_map' )?></h2>

      <div id="locations-near-you-map"></div>

      <div id="locations-near-you"></div>
          <link rel="stylesheet" href="<?php echo MAP__PLUGIN_URL ?>css/map.css">
          <link rel="stylesheet" href="<?php echo MAP__PLUGIN_URL ?>css/bootsrap.css">
          
    
    <script src="<?php echo $filename ?>"></script>
      <script defer src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key=<?php echo $map_key ?>&callback=createSearchableMap"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>  
   <script>
    var text_ok_map="<?php echo $js_text_ok ?>";
    var text_error_adress="<?php echo $js_text_invalid_adress ?>";
    var map_key="<?php echo $map_key ?>";
    var map_zoom="<?php echo $map_zoom ?>";
    
   </script>


      <script src="<?php echo MAP__PLUGIN_URL ?>js/createSearchableMap.js"></script>
      

     <?php require_once( MAP__PLUGIN_DIR . 'classes/index.php' ); ?>
      
    </div>
