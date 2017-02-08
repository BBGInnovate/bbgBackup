<?php 

    require ('../../../../wp-load.php');
    //switch_to_blog(2);

    $spreadsheetKey = "1JzULIRzp4Meuat8wxRwO8LUoLc8K2dB6HVfHWjepdqo";
    $spreadsheetUrl = "https://docs.google.com/spreadsheets/d/" . $spreadsheetKey . "/pubhtml";
    $csvUrl = "https://docs.google.com/spreadsheets/d/" . $spreadsheetKey . "/export?gid=0&format=csv";
    $threatsCSVArray = getCSV( $csvUrl,'threats',0 );
    array_shift( $threatsCSVArray ); //our first row contained headers

    $threats = array();
    $counter = 0;
    foreach($threatsCSVArray as $t) {
        $counter++;
        if (true) {
            //$t = array_map('utf8_encode', $t);
            $date = $t[2];
            $dateObj = explode("/",$date);
            $month = $dateObj[0];
            $day = $dateObj[1];
            $year = $dateObj[2];
            
            $day = sprintf("%02d",$day);
            $month = sprintf("%02d",$month);

            $dateStr = "$year-$month-$day 12:00:00";
            
            $t = array(
                'country' => strtolower($t[0]),
                'name' => $t[1],
                'date' => $dateStr,
                'status' => $t[3],
                'description' => $t[4],
                'mugshot' => $t[5],
                'network' => $t[6],
                'link' => $t[7],
                'latitude' => $t[8],
                'longitude' => $t[9],
                'headline' => $t[10]
            );
            if (true) {
                //echo $t['description']; echo '<BR>';
                $t['headline'] = utf8_encode($t['headline']);
               // $t['description'] = utf8_encode($t['description']);
                $post_information = array(
                    'post_title' => $t['headline'],
                    'post_content' => $t['description'],
                    'post_type' => 'threat_to_press',
                    'post_status' => 'publish',
                    'post_date' => $dateStr
                );
                
                $address = array("lat" => $t['latitude'], "lng" => $t['longitude']);

                $networks = $t['network'];
                $networks = str_replace("RFE/RL", "RFERL", $networks);
                $networks = strtolower($networks);
                $networks=explode(",", $networks);

                $status = (strtolower($t['status'])); 
                    
                $post_id = wp_insert_post( $post_information );

                if ($post_id == 0) {
                    echo " id is " . $post_id . " for " . $t['headline'] . "<BR>";
                } else {

                    $t['country'] = str_replace(" ", "_", $t['country']);
                    update_field('field_5890db9048521', $t['country'], $post_id); //threats_to_press_country = field_5890db9048521
                    update_field('field_5890dc8248522', $t['name'], $post_id); //threats_to_press_target_names = field_5890dc8248522
                    update_field('field_5890de0e48526', $networks, $post_id); //threats_to_press_network = field_5890de0e48526
                    update_field('field_5890dea748527', $address, $post_id); //threats_to_press_coordinates = field_5890dea748527
                    update_field('1field_589298b8e17e0', $status, $post_id); //threats_to_press_status = field_5890dea748527
                    if ($t['link'] != '') {
                        update_field('field_5890e235f2ed8', $t['link'], $post_id); //threats_to_press_link = field_5890e235f2ed8    
                    }   
                }
            }
        }
    }
    echo "updates complete @ " .  date('l jS \of F Y h:i:s A');
?>