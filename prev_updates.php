<?
  include("auth.php");
  include("db.php");


  $sql="select  s.entry_id, s.species_name, l.loc_id,l.loc_name, l.loc_city, l.loc_state from carnivore_loc as l, carnivore_records as s,ncf_data_users as u where l.loc_id=s.loc_id  and s.user_id = u.user_id and u.user_id='$user_id' and s.invalid is NULL order by s.entry_id DESC LIMIT 5";
  $result=mysql_query($sql);
  if($user_id && mysql_num_rows($result) > 0 ) {
      echo "<ul id='prev_update_ul'>";
             echo "<li class='liheader'>Your recent locations <a href='viewloc.php' title='View all locations'>[View all]</a></li>";
             while($data = mysql_fetch_assoc($result)) {
                         echo "<li class='sli'>" .  ucfirst($data['species_name']) . ", " . $data['loc_name'] . ", " . $data['loc_state'];
                         echo "&nbsp;<a class='delentry' id='del_" . $data['entry_id'] . "' href='#' onclick='return confirmation(" . $data['entry_id'] . ");'>[delete]</a>";
                         echo "&nbsp;<a class='editentry'  href='editentry.php?id=" . $data['entry_id'] . "'>[View / edit]</a></li>";

             }

     echo "</ul>";
  }
?>
<script>

<? if($user_id && mysql_num_rows($result) > 0 ) { ?>

   $('.help>li:not(:first-child)').hide();
   var text = $('.help_show').text();
   $('.help_show').text( text == "[hide]" ? "[show]" : "[hide]");

<? } ?>

</script>