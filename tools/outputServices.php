<?php 

/*
    Author: Joe Flowers
    Description: The map data for worldwide ops is represented as follows:
    	custom post type "country"
    	custom taxonomy called "Language Services"
    	custom field "website name" and "website url" on each language service taxonomy term
    	
    	To keep the data model simple and in a format that could be managed with WordPress's built in taxonomy and custom field capabilities, we elected to duplicate language services when they have more than one website. For instance, many RFERL services have sites in both their native language as well as Russian.

        Data source: We asked the Office of Policy and Research to provide us a list of countries that each of our five networks target, and also which language services are used to target each country. 
*/
?>


<html><head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head><body style='padding:10px'>

<div style='font-size:20px;'><a href="outputCountries.php">View By Country</a> | <a href="outputServices.php">View By Service</a> </div>


<h1>Operations Map Data Reference - By Service</h1><BR />
<div class="alert alert-info" role="alert">

This page dynamically lists the data that drives the display of the operations map. For each network and language service, it lists the countries that are targeted. It is intended only for reference / data confirmation and not external users.
</div>

<style>
	h1,h2 { margin-bottom:0px; }
	h1 { font-size:26px; }
	h2 {font-size:20px;}

	/*div:nth-of-type(odd) {
	    background: #e0e0e0;
	}*/
</style>


<?php

require ('../../../../wp-load.php');


$taxonomies = get_terms( array(
    'taxonomy' => 'language_services',
    'hide_empty' => false
) );


if ( !empty($taxonomies) ) :
    foreach( $taxonomies as $category ) {
        if( $category->parent == 0 ) {
            echo "<div class='entity'><h1>" . $category->name . "</h1>";
            echo "<em>First list is all countries targeted by " . $category->name . " followed by breakdowns by language service </em><BR />";
           
            $the_query = new WP_Query( array(
			    'post_type' => 'country',
			    'post_status' => array('publish'),
			    'tax_query' => array(
			        array (
			            'taxonomy' => 'language_services',
			            'field' => 'term_id',
			            'terms' => $category->term_id
			        )
			    ),
			) );

            echo "<ul>";
			while ( $the_query->have_posts() ) :
			    $the_query->the_post();
			    echo "<li>" .  get_the_title() . "</li>";
			    // Show Posts ...
			endwhile;


            foreach( $taxonomies as $subcategory ) {
                if($subcategory->parent == $category->term_id) {
               	 	
                	$termMeta = get_term_meta( $subcategory->term_id );
				    $siteName = "";
					$siteUrl = "";
					if ( count( $termMeta ) ) {
						$siteName = $termMeta['language_service_site_name'][0];
						$siteUrl = $termMeta['language_service_site_url'][0];
					}

               	 	echo "<h2>" . $subcategory->name . " <span style='font-size:12px;'>$siteUrl</a></h2>";

					$the_query = new WP_Query( array(
					    'post_type' => 'country',
					    'post_status' => array('publish'),
					    'tax_query' => array(
					        array (
					            'taxonomy' => 'language_services',
					            'field' => 'term_id',
					            'terms' => $subcategory->term_id
					        )
					    ),
					) );

					echo "<ul>";
					while ( $the_query->have_posts() ) :
					    $the_query->the_post();
					    echo "<li>" .  get_the_title() . "</li>";
					endwhile;
					echo "</ul>";
               	}
            }
            echo "</ul></div><hr />";
        }
    }
endif;
?>

</body></html>