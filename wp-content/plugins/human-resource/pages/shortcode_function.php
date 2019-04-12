<?php 

function feature_shortcode_styles() {
	
    $post = get_post(get_the_ID());

    if (strpos($post->post_content,'[feature_widget]') != false or $post->post_content == '[feature_widget]'  ) {
		
        $plugin_url = plugins_url('/human-resource');
        
        echo '

        <style>

            .inner-form {
                width: 100%;
                clear: both;
                float: left;
            }
            
            #talbe_data {
                font-size: 15px;
            }

            #table-data {
                min-width: 2000px;
            }
            
            .input-field.first-wrap label {
                display: block;
            }

            .input-field.first-wrap {
                margin-bottom: 10px;
                float: left;
                width: 30%;
            }

            .input-field.first-wrap input {
                width: 95%;
            }

            #table-data_filter {
                display: none;
            }

            .s003 {
                display: none;
            }
            .loadingss {
                display: block;
            }

            #talbe_data {
                display: none;
            }

        </style>
        
        <script>

            window.addEventListener("load", function(evt) {
                
                if(!window.jQuery) {
                    var script = document.createElement("SCRIPT");
                    script.src = "https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js";
                    script.type = "text/javascript";
                    script.onload = function() {
                        var $ = window.jQuery;
                        front_script($);
                    };
                    document.getElementsByTagName("head")[0].appendChild(script);
                } else {
                    front_script(jQuery);
                }

            });


            function front_script($) {

                $.getScript( "https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" )
                    .done(function( script, textStatus ) {
                        
        
                        var tables = $("#table-data").DataTable({
                            searching: true,
                            ordering:  false,
                            "lengthChange": false,
                        });

                        $(".s003").show();
                        $(".loadingss").hide();

                        $(".search-submit-form").submit(function(e){
                    
                            e.preventDefault();
        
                            var person_name = $("[name=person-name]").val() || "";
                            var training_name = $("[name=training-name]").val() || "";
                            var location = $("[name=location]").val() || "";

                            if(person_name == "" && training_name == "" && location == "") {
                                alert("Please enter some value");
                            } else {
                                
                                $("#talbe_data").show();

                                tables
                                .column(1).search( person_name )
                                .column(2).search( location )
                                .column(6).search( training_name )
                                .draw();

                            }

                        });
                });

            }

        </script>';

    }

}

add_action('wp_footer', 'feature_shortcode_styles', 100);


add_shortcode( 'feature_widget', 'Frontned_data_handler' );

function Frontned_data_handler() {

    if(is_admin() == FALSE) {

        $plugin_url = plugins_url('/human-resource');

        global $wpdb;
        $prefix = $wpdb->prefix;
        $person_row = $wpdb->get_results("SELECT * FROM {$prefix}features_of_person", OBJECT);
        
        
        $rows = '';
        
        if(count($person_row) > 0) {

            foreach ($person_row as $key1 => $person_single) {

                $querystr = "SELECT * FROM {$prefix}features_of_training WHERE id IN ($person_single->training) ";
                $training_row = $wpdb->get_results($querystr, OBJECT);

                $Training = '';
                $Time = '';
                $Extend = '';
                $Name = '';
                $Provider = '';

                if(count($training_row) > 0) {
                    
                    foreach ($training_row as $key => $train_row) {
                        

                        $Training .= $train_row->name.'<br>';
                        $Time .= $train_row->time_frame_training.'<br>';
                        $Extend .= $train_row->extend_of_study_credits.'<br>';
                        $Name .= $train_row->name_of_trainer.'<br>';
                        $Provider .= $train_row->provider_of_training.'<br>';
                        
                    }

                }

                $rows .= '<tr>
                    <td>'.($key1+1).'</td>
                    <td>'.ucwords($person_single->name).'</td>
                    <td>'.ucwords($person_single->location).'</td>
                    <td>'.ucwords($person_single->department).'</td>
                    <td>'.ucwords($person_single->phone).'</td>
                    <td>'.ucwords($person_single->email).'</td>
                    <td>'.$Training.'</td>
                    <td>'.$Time.'</td>
                    <td>'.$Extend.'</td>
                    <td>'.$Name.'</td>
                    <td>'.$Provider.'</td>
                    <td>'.$person_single->duration.'</td>
                </tr>';
            }

        }

        echo '
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
            
            <div class="loadingss">
                <img src="'.$plugin_url.'/img/loader.gif" width="100">
            </div>

            <div class="s003">

            


            <h2 class="product-search">Search Product</h2>

                <form class="search-submit-form">
                    <div class="inner-form">
                        <div class="input-field first-wrap">
                            <label>Person\'s Name</label>
                            <input type="text" name="person-name">
                        </div>

                        <div class="input-field first-wrap">
                            <label>Training\'s name</label>
                            <input type="text" name="training-name">
                        </div>

                        <div class="input-field first-wrap">
                            <label>Location</label>
                            <input type="text" name="location">
                        </div>                    
                        <button type="submit" class="input-field first-wrap">Search</button>
                    </div>
                </form>

                <div style="overflow-x:auto; clear: both;" id="talbe_data">
                    <table id="table-data" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Department</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Training</th>
                                <th>Time Frame</th>
                                <th>Extend of Study Credits</th>
                                <th>Name of Trainer</th>
                                <th>Provider of Trainer</th>
                                <th>Period/Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            '.$rows.'
                        </tbody>
                    </table>
                </div>
            </div>


        ';
    }   

}


