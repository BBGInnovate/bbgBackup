<?php
/**
 * The template for displaying 2 column pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
   template name: Utility: Custom Field Checker
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

if ( isset($_GET['customFieldKey']) && ($_GET['customFieldKey'] != "" )) {


  $qParams = array(
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

  if (isset($_GET['post_type']) ) {
    if ($_GET['post_type'] == 'q_post') {
      $qParams['post_type'] = 'post';
    } else {
      $qParams['post_type'] = $_GET['post_type'];    
    }
    
  }

  echo "<h1>Post Report (Custom Field Edition)</h1>";
    echo "Key queried: " . $_GET['customFieldKey'] . " <BR>";
 
  echo "<BR>";

  $ids = array();
  $custom_query = new WP_Query( $qParams );
  if ( $custom_query->have_posts() ) :
    $counter = 0;
    echo "<table class='table table-striped table-bordered'><thead><th >Pub Date</th><th>Type</td><th>Post</th><th>Key Value</th><tbody>";
    while ( $custom_query->have_posts() ) : $custom_query->the_post();
      $counter++;
      $id = get_the_ID();
      $ids []= $id;
      $key = $_GET['customFieldKey'];
      echo "<tr><td width='175'>" . get_the_date() . "</td><td>" . get_post_type() . "<td><a target='_blank' href='" . get_the_permalink() . "'>" . get_the_title() . "</a></td><td><pre>";
      var_dump(get_post_meta($id,$key));
      echo  "</pre></td></tr>";
    endwhile;
    echo "</tbody></table>";
    echo "<h3>IDs</h3><pre>";
    var_dump($ids);
    echo "</pre>";
  else: 
    echo "No results found.";
  endif;

}

?>

<form method="get">
        <h3>Custom Field Key</h3>
        <input type="text" name="customFieldKey"> <BR><BR>
        <h3>Post Type</h3>
        <input type="radio" name="post_type" value="any" id="any" checked="checked"> <label for="any">Any</label> &nbsp;&nbsp;
        <input type="radio" name="post_type" value="q_post" id="post"> <label for="post">Posts Only</label> &nbsp;&nbsp;
        <input type="radio" name="post_type" value="page" id="page"> <label for="page">Pages Only</label> <BR><BR>

        <button type="submit" class="btn btn-default">Submit</button>
</form>       


</body></html>