<?
  include("auth.php");
  include("db.php");

  $loc_name = addslashes($_POST['lname']);
  $loc_city = addslashes($_POST['lcity']);
  $loc_state = addslashes($_POST['lstate']);

  $country = addslashes($_POST['country']);

  $loc_zoom = $_POST['loc_zoom'];
  $loc_lat = $_POST['loc_lat'];
  $loc_lng = $_POST['loc_lng'];

  $species_name = $_POST['spname'];
  $species_img_id = $_POST['spimg'];

  $record_month = $_POST['record_month'];
  $record_yr = $_POST['record_yr'];

  $conflict_type = $_POST['conflict_type'];
  $habitat_type = $_POST['habitat_type'];


 if($user_id) {

  $sql="insert into carnivore_loc(user_id,loc_name,loc_city,loc_state,loc_zoom,loc_lat,loc_lng,loc_country) values ('$user_id','$loc_name','$loc_city','$loc_state','$loc_zoom','$loc_lat','$loc_lng','$country')";
  $success1 = mysql_query($sql);
  $loc_id = mysql_insert_id();

  $sql="insert into carnivore_records(user_id,loc_id,species_name, species_img_id,record_month, record_year, conflict_type, habitat_type) values ('$user_id','$loc_id','$species_name','$species_img_id','$record_month','$record_yr','$conflict_type','$habitat_type')";

  $success2 = mysql_query($sql);

  }

  if ($success1 && $success2) {
     echo "1";
  } else {
     echo "0";

  }

?>
