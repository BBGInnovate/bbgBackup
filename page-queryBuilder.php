<?php
/**
 * The template for displaying 2 column pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
   template name: Utility: Query Builder
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

if ( isset($_GET['qtags']) || isset($_GET['qcats']) ) {
  


  $qParams = array(
    'post_type'=> 'post',
    'post_status' => 'publish',
    'posts_per_page' => 1000,
    'orderby' => 'post_date',
    'order' => 'desc',
  );

  
  $showTags=false;
  if (isset($_GET['qtags']) ) {
    $showTags=true;
    $tags = array();  
    foreach($_GET['qtags'] as $t) {
      $tags[] = $t;
    }
    $qParams['tag__and'] = $tags;

    $tagsDisplay = get_tags(array('include' => $tags));
    $tagNames = [];
    foreach ($tagsDisplay as $t) {
      $tagNames[] = $t->name;
    }
    $tagNames = implode(", ", $tagNames);

  }



  $showCats=false;
  if (isset($_GET['qcats']) ) {
    $showCats=true;
    $cats = array();
    foreach($_GET['qcats'] as $c) {
      $cats[] = $c;
    }
    $qParams['category__and'] = $cats;

    $catsDisplay = get_categories(array('include' => $cats));
    $catNames = [];
    foreach ($catsDisplay as $c) {
      $catNames[] = $c -> name;
    }
    $catNames = implode(", ", $catNames);
  }
  

  echo "<h1>Post Report</h1>";
  if ($showCats) {
    echo "Categories queried: " . $catNames . "<BR>";
  }
  if ($showTags) {
    echo "Tags queried: " . $tagNames . "<BR>";
  }
  echo "<BR>";

  $custom_query = new WP_Query( $qParams );
  $ids = array();
  if ( $custom_query->have_posts() ) :
    $counter = 0;
    echo "<table class='table table-striped table-bordered'><thead><th >Pub Date</th><th>Post</th><tbody>";
    while ( $custom_query->have_posts() ) : $custom_query->the_post();
     $id = get_the_ID();
     $ids []= $id;
      $counter++;
      echo "<tr><td width='175'>" . get_the_date() . "<td><a target='_blank' href='" . get_the_permalink() . "'>" . get_the_title() . "</a></td></tr>";
    endwhile;
    echo "</tbody></table>";
     echo "<h3>IDs</h3><pre>";
    var_dump($ids);
    echo "</pre>";
  else: 
    echo "No results found.";
  endif;
  die();
}

?>

<h1>Post Queries</h1>
<form method="get">
        <?php 
          $categoryList = wp_dropdown_categories('echo=0&show_count=1&orderby=name&id=qcats&name=qcats'); 
          $categoryList = str_replace( "<select  name='qcats'", "<select multiple name='qcats[]'", $categoryList );
          echo "<h3>Categories</h3>";
          echo $categoryList; 
          echo "<BR><BR>";

          $tagList = wp_dropdown_categories('taxonomy=post_tag&show_count=1&orderby=name&id=qtags&echo=0&name=qtags');
          $tagList = str_replace( "<select  name='qtags'", "<select multiple name='qtags[]'", $tagList );
           echo "<h3>Tags</h3>";
          echo $tagList;
          wp_enqueue_style( 'bbginnovate-style-fonts2', "");
          wp_enqueue_script( 'asdfasdf', "", array('jquery'), '123', false );

        ?>
       
          <script type="text/javascript">
            // A $( document ).ready() block.
            jQuery( document ).ready(function() {
                jQuery("#qcats").select2();
                jQuery("#qtags").select2();
            });
          </script>

          <BR><BR>

         <button type="submit" class="btn btn-default">Submit</button>
</form>       


</body></html>