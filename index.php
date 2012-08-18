<?
       include("auth.php");
       include("db.php");
       $current_year = date('Y', time());
       
       $months = array(
                1 => 'january',
                2 => 'february',
                3 => 'march',
                4 => 'april',
                5 => 'may',
                6 => 'june',
                7 => 'july',
                8 => 'august',
                9 => 'september',
                10 => 'october',
                11 => 'november',
                12 => 'december');
                
        $mnth_drpdwn = '';

        for ($i = 1; $i <= 12; $i++) {
                $mnth_drpdwn .= '<option value="'.$i.'"';
                $mnth_drpdwn .= '>'. ucfirst($months[$i]) .'</option>';
        }

        function yrdrpdown($start,$end) {
            $yr_drpdwn = '';
            for ($i = $end; $i >= $start; $i--) {
                $yr_drpdwn .= '<option value="'.$i.'"';
                $yr_drpdwn .= '>'. $i .'</option>';

            }
            return $yr_drpdwn;
        }

        function get_states() {
           $sql="select state_id, state from indian_states order by state";
           $query=mysql_query($sql);
           $states='';
           while($data = mysql_fetch_assoc($query)) {
                $states .= '<option value="' . $data['state'] . '">' . $data['state'] . '</option>';
           }
           return $states;
        }

        function check_smart() {
                $ua = $_SERVER['HTTP_USER_AGENT'];
                $checker = array('iphone'=>preg_match('/iPhone|iPod|iPad/', $ua),
                                 'blackberry'=>preg_match('/BlackBerry/', $ua),
                                 'android'=>preg_match('/Android/', $ua),
                );
                if ($checker['iphone'] || $checker['blackberry'] || $checker['android']) {
                   return 1;
                } else {
                   return 0;
                }
         }

         $check_smart = check_smart();

         if($check_smart && !$user_id) {
                //header("Location: login.php");

         }


?>
<!DOCTYPE html> 
<html> 
<head> 
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" /> 
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/> <!--
<meta name="title" content="Jackals, past and present: a survey of the golden jackal in India" />
<meta name="description" content="Ever seen a jackal in the wild? Come tell us about it! Although it occurs widely in India, we know very little about the jackal. We need help from people like you to understand a few key facts about their distribution and conservation status. So, do come along and participate in this project!"/>-->
<title>Carnivores</title>
<link href="styles_res_new.css" rel="stylesheet" type="text/css" charset="utf-8" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script type="text/javascript" src ="http://www.google.com/jsapi"></script>
<script type="text/javascript">
      google.load("jquery","1.4.2");
</script>
<script type="text/javascript" src="fancybox/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript" src="notice//jgrowl/jquery.jgrowl.js"></script>
<link rel="stylesheet" type="text/css" href="notice/jgrowl/jquery.jgrowl.css" media="screen" />

<script type="text/javascript" src="http://use.typekit.com/mlz5wyl.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<style>
body { 
color:#FAAB47; background-color: #333; 
font-family: "facitweb",sans-serif;
font-style: normal;
font-weight: 400;

 }

.intable select { width:100%; }

a { color:#FAAB47; }
#survey_form_ul { background-color: #D2D6CE; color: #000; //#4e482c; font-size:12px; padding:10px; 

-moz-border-radius:15px;
border-radius: 15px;
}

#survey_form_ul input[type=text],select { border:solid 1px #ccc; padding:5px; width:90%; margin-top:10px; } 

#survey_form_ul>li>ul>li {  //margin-top:10px; }

.oswald_400 { 

font-family: "facitweb",sans-serif;
font-style: normal;
font-weight: 400;
padding-top:5px; 
margin-top:5px;
border-top:dotted 1px #777;
}


</style>
	<script type="text/javascript"> 
            var geocoder;
            var map;
            var india = new google.maps.LatLng(22.71,82.15);
	    var markers=[];
	    var markersArray=[];

            function clearOverlays() {
                if (markers) {
                   for (i in markers) {
                      markers[i].setMap(null);
                   }
                }
            }


            function reset_login_fields() {
                 $('.lmain input[type=text], .lmain input[type=password]').val('');
                
                 $(".lmain input:radio").removeAttr("checked");

            }
            
	    function update_geocoder(latlng,getid,zoom) {
	            
                    if( zoom < 6 ) {
                        alert("Current zoom level is " + zoomlevel + ".  The min accepted zoom level is 10. Please zoom in more to select the location.");
                        return false;                        
                    } else {

                    geocoder.geocode({'latLng': latlng}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
			       
                               if (results[1].formatted_address) {
                                  var lat_get = latlng.lat();
                                  var lng_get = latlng.lng();
                                  var str = "";
                                  var addr = results[1].formatted_address;
                                   var loc = addr.split(',');
                                   var arcount = loc.length;
				   
                                  if(loc[arcount - 1]) { var loc_country= loc[arcount - 1]; }  else { var loc_country= ''; }
                                  if(loc[arcount - 2]) { var state_name= loc[arcount - 2]; }  else { var state_name= ''; }
                                  if(loc[arcount - 3]) { var city_name= loc[arcount - 3]; } else { var city_name=''; }
                                  if(loc[arcount - 4]) { var loc_name= loc[arcount - 4]; } else { var loc_name=''; }

                                   var addr_final = addr;
                              } 
                          } else {
                                   var addr_final = 'Unknown';
                          }

			    $('#loc_full_name').html(addr_final);
			    
                            $('#loc_country').val(loc_country);
			    $('#loc_state').val(state_name);
			    $('#loc_city').val(city_name);
                            $('#loc_name').val(loc_name);
                            
                            $('#loc_zoom').val(zoom);
                            $('#loc_lat').val(lat_get);
                            $('#loc_lng').val(lng_get);
			    

		       });
                   }
		    
             }

             function get_session(){
                      $.get('gs.php', function(data) {
                            if(data) {
                                  $('#session_update').html(data + " (Log out)");                                  
                                   $('#session_update').show();
                                  $('#inline').hide();
                                  $('#c1').html("Logged in as " + data);
                                  $('.loginsec').hide();
                                  $('#user_settings').show();
                            } else {
			      
                                  $('#user_settings').hide();
                                  $('#session_update').hide();
                                  $('#inline').show();
                                  $('#c1').html("");
                                  $('.lmain input[type=text], .lmain input[type=password]').val('');
                                  $('.loginsec').show();
                            }
                      });
             }

             function confirmation(id) {
                      var answer = confirm("Are you sure you want to delete this entry? This cannot be undone")
                      if (answer){
                          var data='id=' + id;
                          $.ajax({
                             url: "delentry.php",
                             type: "POST",
                             data: data,
                             cache: false,
                             success: function (html) {
                             if(html == '1') {
                               $.jGrowl("Your entry has been successfully deleted");
                               get_prev_updates();
                               }
                             }
                          });
                            
                      }
             }

	     function create_imgthumb(img) {
	     			
	     	      $('#photo_thumb_div').html("<img src='image_uploads/" + img + "' style='height:160px'>");
		      $('#photo_display_div').show();
		      $('#photo_iframe').hide();
	     }


             function survey_validate() {
	                                  

                      var user_id = '<? echo $user_id; ?>';
		      
                      var url_build='';
                      var lname = $('#loc_name').val();
                      var lcity = $('#loc_city').val();
                      var country = $('#loc_country').val();
                      var lstate = $('#loc_state').val();
                      var loc_zoom =  $('#loc_zoom').val();
                      var loc_lat =  $('#loc_lat').val();
                      var loc_lng =  $('#loc_lng').val(); 

		      if(loc_lat == '' || loc_lng == '' || loc_zoom == '') {
		           alert("There is a problem. Please close the form and start all over again.");
			   return false;
		      } else {
		          url_build+= "loc_zoom=" + loc_zoom  + "&loc_lat=" + loc_lat + "&loc_lng=" + loc_lng;
		      }


                      if( country.toLowerCase() == 'arunachal pradesh') {
                           $('#loc_state').val('Arunachal Pradesh');
                           $('#loc_country').val('India');
                      }
                      
                      if (lname == '') {
                         alert("Please fill in the name of the location of your site.");
                         return false;
                      } else {
                         url_build+="&lname=" + lname;                     
                      }


                      if (lcity == '') {
                         alert("Please fill in name of the city of your site.");
                         return false;
                      } else {
                        url_build+="&lcity=" + lcity;
                      } 

                      if (lstate == '') {
                         alert("Please select the state of your site.");
                         return false;
                      } else {
                         url_build+="&lstate=" + lstate;
                      }

		      var species_name = $('#species_name').val();
		      if( species_name == '') {
                            alert("Please select the species name.");
                            return false;
                      } else {
		            url_build+="&spname=" + species_name;
		      }

		      var species_img = $('#photo_image_file').val();
        	      if( species_img == '') {
                            alert("Please add a photo for your record.");
                            return false;
                      } else {
		            url_build+="&spimg=" + species_img;
			    /*var img_caption = $('#photo_caption').val();
			    if(img_caption == '') {
			       alert("Please add a caption to your story.");
			       return false;
			    } else {
			       url_build+="&img_caption=" + img_caption;
			    } */
                      }



		      var record_month = $('#record_month').val();
                      var record_yr = $('#record_yr').val();
                      
                      if( record_month == '' || record_yr == '') {
                            alert("Please select the month & year of your record.");
                            return false;
                      } else {
		      	     url_build+="&record_month=" + record_month + "&record_yr=" + record_yr;
		      }

                      var conflict_type = $('#conflict_type').val();
                      if (conflict_type == '') {
                               alert("Please tell us about the kind of conflict");
                               return false;
                      } else {
		      	       url_build+="&conflict_type=" + conflict_type;
		      }

                      var habitat_type = $('#habitat_type').val();
                      if (habitat_type == '') {
                               alert("Please tell us about the kind of habitat");
                               return false;
                      } else {
		      	     url_build+="&habitat_type=" + habitat_type;
		      }

		      var data = url_build;
                 
                      $.ajax({
                         url: "submit_survey.php",
                         type: "POST",
                         data: data,
                         cache: false,
                         success: function (html) {
			   
                             if(html == '1') {
                               $.jGrowl("Thank you! Your location has been successfully added.");
			       // $('#survey_form_ul input[type=text]').val('');
			       // $('#survey_form_ul input[type=hidden]').val('');
			       // $('#survey_form_ul select').val('');
                               // $('#survey_form_ul').hide();

			       $('#photo_remove').trigger('click');
			       $('#remove_loc').trigger('click');
                               $('#marker_count').val('0');
                               get_prev_updates();
                               map.setCenter(india);
                               map.setZoom(5);
                             }
                          }
                      });

                      return false;
             }


             function HomeControl(controlDiv, map) {
 
		controlDiv.style.padding = '5px';
 
		// Set CSS for the control border
  		var controlUI = document.createElement('DIV');
  		controlUI.style.backgroundColor = 'white';
  		controlUI.style.borderStyle = 'solid';
  		controlUI.style.borderWidth = '2px';
  		controlUI.style.cursor = 'pointer';
  		controlUI.style.textAlign = 'center';
  		controlUI.title = 'Click to set the map to India';
  		controlDiv.appendChild(controlUI);
 
		// Set CSS for the control interior
  		var controlText = document.createElement('DIV');
  		controlText.style.fontFamily = 'Arial,sans-serif';
  		controlText.style.fontSize = '12px';
 		controlText.style.paddingLeft = '4px';
  		controlText.style.paddingRight = '4px';
  		controlText.innerHTML = '<b>Show entire India</b>';
  		controlUI.appendChild(controlText);

  		google.maps.event.addDomListener(controlUI, 'click', function() {
    		    map.setCenter(india);
    		    map.setZoom(5);
  		}); 
	    }


            var nz = new google.maps.LatLngBounds(new google.maps.LatLng(-48.3124000,164.9268000), new google.maps.LatLng(-34.1118000,180.0000000))
            $(document).ready(function(){
                var zoomin;
                var win_width = $(window).width();
                
                if(win_width < 400) {
                     zoomin = 4;
                } else {
                     zoomin = 5;
                }

		$('#photo_display_div').hide();
	
		$('#survey_form_ul').hide();

		$('#photo_remove').click(function() { 
		      $('#photo_thumb_div').html("");
                      $('#photo_display_div').hide();
                      $('#photo_iframe').show();
		      $("#photo_image_file").val('');

                });

		$('#remove_loc').click(function() {
		     $('#survey_form_ul').hide();
		     for (i in markers) {
                       markers[i].setMap(null);
		      }
		     $('#marker_count').val('0');
		     $('#survey_form_ul input[type=text]').val('');
                     $('#survey_form_ul input[type=hidden]').val('');
                     $('#survey_form_ul select').val('');
		     map.setCenter(india);
                     map.setZoom(5);
		});


		$('#submit_survey_form').click(function() {
		      
		      return survey_validate();
		});
               
                geocoder = new google.maps.Geocoder();
                var latlng = new google.maps.LatLng(22.71,82.15);
                var myOptions = {
                    zoom: zoomin,
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
                var homeControlDiv = document.createElement('DIV');
                var homeControl = new HomeControl(homeControlDiv, map);
 
                homeControlDiv.index = 1;
                map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);
 
                google.maps.event.addListener(map, 'click',function(me){		    
                     $.get('gs.php', function(data) {
                         if(data == '') {
                                 $("#inline").trigger('click');
                                 return false;
                         } else {

                           var zoomlevel = map.getZoom();
                           if (zoomlevel < 8 ) {
                              zoomlevel=8;
                           }

                           var count_marker = $('#marker_count').val();
                           if(count_marker == '0') {
                                codeLatLng(me.latLng,zoomlevel);
                                map.setCenter(me.latLng);
                                map.setZoom(zoomlevel);
                                $('#marker_count').val('1');
                           }
                           
                         }
                     });
                });

	       

		/*$('.helpcontents').show();
                $('.helptext').hide();
		$('#help_1').show();	
		$('.htiptext').hide();
             
                $('.help_show').click(function(){
			$('.help>li:not(:first-child)').toggle('slow');
			var text = $('.help_show').text();
    			$('.help_show').text( text == "[hide]" ? "[show]" : "[hide]");
                        
                });

		

		$('.helphead').click(function(){
			var id = $(this).attr('id');
			id = id.replace(/hhead_/, "");
			$('#help_' + id).toggle();
		});

                $('.tips_show').click(function(){
			$('.htiptext').toggle();
			var text3 = $('.tips_show').text();
    			$('.tips_show').text( text3 == "[hide]" ? "[show]" : "[hide]");
		});

                $("#webtitle").fitText(1.5);

                $(".mainlinks li:first-child").addClass("here");
		*/
		$('.register').hide();
	    $('#reglink').show();
	    $('#loginlink').hide();
	    $('#reset_cancel').hide();


	    $('#reglink').click(function() { 
		$('#loginlink').show();
		$('#reglink').hide();
		$('.login').show();
	        $('.register').show();
		$('#login_btn').val("Register");
                $('#logintitle').html('REGISTER TO PARTICIPATE');
                $('#login_type').val('2');
                $('#response').html('All fields mandatory');
		$('#forgotpass').hide();
                $('#rem').hide();
                reset_login_fields();
	    });

            $('.loginsec').show();

	    $('#loginlink').click(function() { 
		$('#reglink').show();
		$('#loginlink').hide();
		$('.login').show();
	        $('.register').hide();
		$('#login_btn').val("Login");
                $('#logintitle').html('LOGIN');
                $('#login_type').val('1');
                $('#response').html('');
                $('#forgotpass').show();
                $('#rem').show();
                reset_login_fields();
	    });

	    $('#forgotpass').click(function() { 
		$('.register').hide();
		$('.login').hide();
		$('#reglink').hide();
		$('#loginlink').hide();
		$('#forgotpass').hide();
		$('#login_btn').val("Reset password");
		$('#reset_cancel').show();
                $('#logintitle').html('FORGOT PASSWORD?');
                $('#login_type').val('3');
                $('#response').html('');
                $('#rem').hide();
                reset_login_fields();
	    });

	    $('#reset_cancel').click(function() {
		$('#loginlink').trigger('click');
		$('#forgotpass').show();
	    	$('#reset_cancel').hide();
	    });
                
                <? if($_GET['confirm']) { ?>
                   $('#response').html("Thank you. Your email id has now been confirmed. Please login below to participate in the survey.");
                <? } ?>


                <? if(!$check_smart)  { ?> 
                   $("#inline").fancybox(); 
		   $("#inline2").fancybox(); 
                <? } ?>
        
                <? if($email_get) { ?>
                   $("#user_settings").fancybox();
                 <? } ?>
                 <? if(!$email_get) { ?> 
			//$("#various1").trigger('click'); 
			$("#inline").trigger('click');  
		<? } ?>


                get_session();
	        get_prev_updates();

                

                $('#session_update').click(function() {
                      $.ajax({
                         url: "logout.php",
                         type: "POST",
                         cache: false,
                         success: function (html) {
                             $('#session_update').html("Not logged in");
                             get_session();
                             get_prev_updates();
                             reset_login_fields();
                         }
                      });
                     
                });

		$(".defaultText").focus(function(srcc){
        		if ($(this).val() == $(this)[0].title) {
            			$(this).removeClass("defaultTextActive");
            			$(this).val("");
        		}
    		});
    
    		$(".defaultText").blur(function() {
        		if ($(this).val() == "") {
            			$(this).addClass("defaultTextActive");
            			$(this).val($(this)[0].title);
        		}
    		});
    
    		$(".defaultText").blur(); 

                $('#chemail').click(function() {
                   var new_email = $('#user_email1').val();
                   var pwd  = $('#current_pwd').val();
                   var data ="email=" + new_email + "&pwd=" + pwd;
                   $.ajax({
                         url: "chemail.php",
                         type: "POST",
                         data: data,
                         cache: false,
                         success: function (html) {
                             $('#s_response').html(html);    
                             reset_login_fields();
                         }
                  });
                });

                $('#chpass').click(function() {
                     var old_pwd = $('#old_pwd').val();
                     var new_pwd1 = $('#new_pwd1').val();
                     var new_pwd2 = $('#new_pwd2').val();
                     var data ="old_pwd=" + old_pwd + "&new_pwd1=" + new_pwd1 + "&new_pwd2=" + new_pwd2;
                     $.ajax({
                         url: "chpass.php",
                         type: "POST",
                         data: data,
                         cache: false,
                         success: function (html) {
                             $('#s_response').html(html);
                             reset_login_fields();
                         }
                     });
                });


	        
            });

            function codeLatLng(latlng,zoomlevel) {               
 		i=1;
                if (geocoder) {
                    var d = new Date();
                    var ctime = d.getTime();
                    var marker = new google.maps.Marker({
     				position: latlng, 
                                zoom: zoomlevel,
     				map: map,
				draggable: true
                    });
                    
                    
                    map.setZoom(zoomlevel);                    
		    var lat_1 = latlng.lat();
		    var lng_1 = latlng.lng();

                    var gen_id = ctime;
		    gen_id  = gen_id.toString().replace(/\./g, '');
                    marker.set("id", gen_id);

		    run_geocoder(latlng,gen_id,zoomlevel,lat_1,lng_1);   


		    google.maps.event.addListener(marker, 'dragend', function() {
                        var zoomlevel2 = map.getZoom();
                        if(zoomlevel2 < 8) {
                            zoomlevel2 = 8;
                            map.setZoom(8);
                        }
                        var get_id = marker.get("id");
		        var latLng = marker.getPosition(); 
			var lat_1 = latLng.lat();
		        var lng_1 = latLng.lng();
		        update_geocoder(latLng,get_id,zoomlevel2);
                    }); 

		    markers[gen_id] = marker;
                  
                    

                 }
              }		   


 
              function run_geocoder(latlng,gen_id,get_zoom,get_lat,get_lng) {
	      	                     
                    geocoder.geocode({'latLng': latlng}, function(results, status) {
                        
			    //var gen_id = genid;
			    var str = "";
                            if (status == google.maps.GeocoderStatus.OK) {


                               if (results[1].formatted_address) {
                                   var a1 = results[1].formatted_address;


                                   var a2 = a1.split(', ');
                                   var arcount = a2.length;
                           
                                   if(a2[arcount - 1]) { var loc_country = a2[arcount - 1]; }  else { var loc_country= ''; }
                                   if(a2[arcount - 2]) { var state_name= a2[arcount - 2]; }  else { var state_name= ''; }
                           
                                   var get_lat = latlng.lat(); 
                                   var get_lng = latlng.lng();


                                   var addr_final = a1;

				}	
			     } else {
			       	    var addr_final = 'Unknown';
			     }

			     $('#loc_full_name').html(addr_final);	  

			     var loc = addr_final.split(',');
            			   var arcount = loc.length;
				   
            			   var loc_zoom = get_zoom;
            			   var loc_lat = get_lat;
            			   var loc_lng = get_lng;

            
				   if(loc[arcount - 4]) { var loc_name= loc[arcount - 4]; }  else { var loc_name= ''; }
            			   if(loc[arcount - 2]) { var state_name= loc[arcount - 2]; }  else { var state_name= ''; }
            			   if(loc[arcount - 1]) { var loc_country= loc[arcount - 1]; }  else { var loc_country= ''; }
            			   if(loc[arcount - 3]) { var city_name= loc[arcount - 3]; } else { var city_name=''; }

				       
				   $('#loc_country').val(loc_country);
				   $('#loc_state').val(state_name);

				  
                            	   $('#loc_city').val(city_name);
                            	   $('#loc_name').val(loc_name);

                            	   $('#loc_zoom').val(get_zoom);
        		    	   $('#loc_lat').val(loc_lat);
                            	   $('#loc_lng').val(loc_lng);

				   $('#survey_form_ul').show();
                              

                            $('.help>li:not(:first-child)').hide();
                            var text2 = $('.help_show').text();
    			    $('.help_show').text("[show]");

                            

			    $('#survey_form_ul').show();

			    
                            // $('#' + genid).click(function(){
                            //     var id=$(this).attr('id');
                            //     $(this).parent().parent().remove();
			    // 	$('#marker_count').val('0');
                            //     markers[id].setMap(null);
                            //     $('#geocode_info>li:first-child .survey_form').show();

                            // });
                            

                            $('.shform').click(function(){
                                var nid=$(this).attr('id');
                                nid = nid.replace(/shform_/, "");
				nid = 'f' + nid;
                                $("#" + nid).show();
                                
                            });

		});
            } 
               

	    function y1(gen_id,yr) {
                   if(yr) {
                     var data="id=" + yr;
                     $.ajax({
                         url: "select.php",
                         type: "GET",
                         data: data,
                         cache: false,
                         success: function (html) {
                              $('#q1_2_' + gen_id).html(html); 
		
			      $('#q9_' + gen_id).html(html);  
			      $('#q10_' + gen_id).html(html);                                                                            
                              if ( yr == '<? echo $current_year; ?>' ) {
                                 $('#q1_2_' + gen_id).val(yr);
				 $('#q9_' + gen_id).val(yr);
				 $('#q10_' + gen_id).val(yr);
                              }
                         }
                      });

                  }
                                      
            }

           function year1(gen_id,yr) {
                $('.y1_' + gen_id).html(yr);
           }

           function year2(gen_id,yr) {
                $('.y2_' + gen_id).html(yr);
           }

	   function y2(gen_id,yr) {
                   if(yr) {
                
                   
                  }
                                      
            }
	
            
            function update_q(gen_id,yr) {
                   if(yr) {
                     $('#past_' + gen_id).html("How common were jackals in " + yr + "?");
                    
                     var data="id=" + yr;
                     $.ajax({
                         url: "select.php",
                         type: "GET",
                         data: data,
                         cache: false,
                         success: function (html) {
                              $('#q3_y_' + gen_id).html(html);
                              $('#q4_y_' + gen_id).html(html);                                                                                     
                              if ( yr == '<? echo $current_year; ?>' ) {
                                 $('#q3_y_' + gen_id).val(yr);
                                 $('#q4_y_' + gen_id).val(yr);
                              }
                         }
                      });

                      if ( yr == '<? echo $current_year; ?>' ) {
                        $('#pasttr_' + gen_id).hide();
                        
                      } else {
                        $('#pasttr_' + gen_id).show();
                      }

                   } else {
                     $('#past_' + gen_id).html("How common were jackals in the past?");
                   }
                                      
            }

            function codeAddress(addr) {
                     var address = addr;
                     geocoder.geocode( { 'address': address}, function(results, status) {
                      if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        map.setZoom(12);
	                //codeLatLng(results[0].geometry.location,10); 
                      } else {
                       alert("Geocode was not successful for the following reason: " + status);
                      }
                    });
            }


            function set_username(user) {
                     var html_user = user + " (<a href='#' onclick='logout(); return false;'>Log out</a>)" ; 
                     $('.username').html(user);

            }

	    function get_prev_updates() {
		$('#prev_updates').load('prev_updates.php', function() {
                      $(".editentry").fancybox({
                         'width': '95%',
                         'height' : '85%',
                         'autoScale' : true,
                         'type' : 'iframe'

                     });
                });
	    }


            function enterLogin() {
                  var user_email = $('#user_email').val();
                  var user_pwd1 = $('#user_pwd1').val();
                  var user_pwd2 = $('#user_pwd2').val();
                  var user_type = $('input:radio[name=user_type]:checked').val();
                  
                  var login_type = $('#login_type').val();
                  var remember = $('#remember_me').attr("checked");
                  if(remember) { remember = '1'; } else { remember = '0'; }
                  
                  if(!user_type) { user_type = '4'; }

                  if(login_type == '2') {
                         var data = "email=" + user_email + "&user_type=" + user_type + "&user_pwd1=" + user_pwd1 + "&user_pwd2=" + user_pwd2 + "&ltype=2";
                  } else if(login_type == '1')  {
                         var data = "email=" + user_email + "&user_pwd1=" + user_pwd1 + "&ltype=1" + "&remember=" + remember;
                  } else if(login_type == '3') {
                         var data = "email=" + user_email + "&ltype=3";
                  }
        
                  $.ajax({
                         url: "submit_reg.php",
                         type: "POST",
                         data: data,
                         cache: false,
                         success: function (html) {$('.help>li:not(:first-child)').hide();
                               $('#response').html(html);
                               reset_login_fields();
                         }
                  });                     

        }

</script>

<style>

</style>

</head> 
<body> 
       <div class='container'>
           <? include("main_bar.php"); ?>
        
           <ul class='map_layout'>  
              <li class='map_block'>
                  <div class='maphelp'>Click ONCE on the location in the map where you've seen carnivores. When the map zooms in, drag and position the place marker more precisely.</div>
                  <form action="#" onsubmit="codeAddress(this.address.value); return false"> 
                    <ul class='search'>
                        <li><input id="address" class="defaultText" type="text" value="" title="Eg. Bangalore, Karnataka, India"></li>
                        <li><input type="submit" value="Search the map" class="btn"><input type='hidden' id='marker_count' value='0'></li>
                    </ul></form>
                    <div id="map_canvas"></div>                
                    
              </li>
	      
               <li class='sidebar'>
			<? //include("helptext.php"); ?>
		   
			  <ul id='survey_form_ul'>
                                <li class='oswald_300'>All <em>*</em> fields are mandatory</b></li> 
				<li class='oswald_title'>
					<span id='loc_full_name'></span>
					<a href='#x' id='remove_loc' title='Remove this location'>[REMOVE]</a>

				</li>
                                <li><ul> 
                                       <li class='oswald_400'>Location<em>*</em></li> 
                                       <li><input type="text" name="location_name" id="loc_name" value="">  
                                       <input type="hidden" name="loc_zoom" id="loc_zoom" value=""> 
                                       <input type="hidden" name="loc_lat" id="loc_lat" value=""> 
                                       <input type="hidden" name="loc_lng" id="loc_lng" value=""> 
                                       <input type="hidden" name="loc_country" id="loc_country" value=""> 
                                       </li> 
                                </ul></li>  

                                <li><ul> 
                                       <li class='oswald_400'>City/District<em>*</em></li> 
                                       <li><input type="text" name="loc_city" id="loc_city" value=""></li> 
                                </ul></li> 

                                <li><ul> 
                                       <li class='oswald_400'>State<em>*</em></li> 
                                       <li> 
                                              <select name="loc_state" id="loc_state"> 
                                                      <option value="">Select state</option> 
                                                      <option value="Outside India">Outside India</option> 
                                                      <? echo get_states(); ?> 
                                              </select> 
                                       </li> 
                                </ul></li> 

			        <li><ul> 
                                       <li class='oswald_400'>It was a<em>*</em></li> 
                                       <li> 
                                              <select name="species_name" id="species_name"> 
						      <option value="">Select species</option>
						      <option value="leopard">Leopard</option>
						      <option value="tiger">Tiger</option>
					      </select>
					</li>
				</ul></li>

				 <li><ul> 
                                       <li class='oswald_400'>Photo record<em>*</em></li>
				       <li><input type="hidden" name="photo_image_file" id='photo_image_file'></li> 
				       <li id='photo_iframe'> 	
				       		<iframe style='width:100%;height:100px;overflow:hidden; border:none' src='photo_import_submit.php'></iframe>
				       </li> 
				       
                                </ul></li> 
				
				<li><ul id='photo_display_div'>
					<a id='photo_remove' href='#x'>Remove</a>
					<div id='photo_thumb_div'>Testing</div>
					<!--<textarea id='photo_caption'></textarea>-->
				</ul></li>

				<li><ul>
				      <li class='oswald_400'>This was in<em>*</em></li>
  				      <li>	
				           <select name="oneoff_mnth" id="record_month" style="width:49%">
                                               <option value="">Month</option>
                                               <option value="13">Can't remember</option>
                                               <? echo $mnth_drpdwn; ?>
                                            </select>
                                            <select name="oneoff_yr" id="record_yr" style="width:49%">
						<option value="">Year</option>
						<? echo yrdrpdown('1960',$current_year); ?>
                                            </select>
				      </li>
				</ul></li>

				<li><ul> 
                                       <li class='oswald_400'>There was a conflict<em>*</em></li> 
                                       <li> 
                                              <select name="conflict_type" id="conflict_type"> 
						      <option value="">Select conflict</option>
						      <option value="1">Carnivore killed human(s)</option>
						      <option value="2">Carnivore killed livestock</option>
					              <option value="3">Human(s)  killed carnivore</option>
					      </select>
					</li>
				</ul></li>

				<li><ul> 
                                       <li class='oswald_400'>The habitat was a<em>*</em></li> 
                                       <li> 
                                              <select name="habitat_type" id="habitat_type"> 
						      <option value="">Select habitat</option>
						      <option value="1">Forest</option>
						      <option value="2">Buildings</option>
					              <option value="3">Agricultural fields</option>
					      </select>
					</li>
				</ul></li>

				<li><ul>
					<input class='submit' type="submit" name="form_submit" id="submit_survey_form" value="Submit record">
				</ul></li>
		          </ul>
			  
			  <div id='prev_updates'></div>

              </li>
           </ul>           

           <!--<div class='footer'>
             <? //include("footer.php"); ?>

             </div>-->
        </div>
	<div style="display: none;">
	  <? include("aboutproject.php"); ?>

          <? include("login_includes.php"); ?>

	  <? include("user_settings.php"); ?>
	</div>
</body>
</html>
