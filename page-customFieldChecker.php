<?php
/**
 * The template for displaying 2 column pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
   template name: Custom Field Checker
 */

?>

<html><head>
<style>span.select2 {min-width: 500px !important;}
</style>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">       
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


</head><body style="padding:20px;">


<?php 

if (isset($_GET['customFieldKey'] )) {
  $qParams = array(
    'post_type'=> 'page',
    'post_status' => 'publish',
    'posts_per_page' => 200,
    'orderby' => 'post_date',
    'order' => 'desc',
    'meta_query' => array(
        array(
            'key' => $_GET['customFieldKey'],
            'value'   => array(''),
            'compare' => 'NOT IN'
        )
    )
  );

  
  echo "<h1>Post Report (Custom Field Edition)</h1>";
    echo "Key queried: " . $_GET['customFieldKey'] . " <BR>";
 
  echo "<BR>";

  $custom_query = new WP_Query( $qParams );
  if ( $custom_query->have_posts() ) :
    $counter = 0;
    echo "<table class='table table-striped table-bordered'><thead><th >Pub Date</th><th>Post</th><th>Key Value</th><tbody>";
    while ( $custom_query->have_posts() ) : $custom_query->the_post();
      $counter++;
      $id = get_the_ID();
      $key = $_GET['customFieldKey'];
      echo "<tr><td width='175'>" . get_the_date() . "<td><a target='_blank' href='" . get_the_permalink() . "'>" . get_the_title() . "</a></td><td><pre>";
      var_dump(get_post_meta($id,$key));
      echo  "</pre></td></tr>";
    endwhile;
    echo "</tbody></table>";
  else: 
    echo "No results found.";
  endif;
}

?>

<h1>Post Queries</h1>
<form method="get">
        <h2>Custom Field Key</h2>
        <input type="text" name="customFieldKey">
         <button type="submit" class="btn btn-default">Submit</button>
</form>       


</body></html>