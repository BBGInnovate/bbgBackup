<?php
/**
 * The template for displaying 2 column pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
   template name: Query Builder
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

  $tags = array();
  if (isset($_GET['qtags']) ) {
    
    foreach($_GET['qtags'] as $t) {
      $tags[] = $t;
    }

    $qParams['tag__and'] = $tags;
  }
  $cats = array();
  if (isset($_GET['qcats']) ) {
    
    foreach($_GET['qcats'] as $c) {
      $cats[] = $c;
    }
    $qParams['category__and'] = $cats;
  }

  $custom_query = new WP_Query( $qParams );
  if ( $custom_query->have_posts() ) :
    while ( $custom_query->have_posts() ) : $custom_query->the_post();
      echo "<a target='_blank' href='" . get_the_permalink() . "'>" . get_the_title() . "</a><BR>";
    endwhile;
  endif;
}

?>

<h1>Post Queries</h1>
<form method="get">
        <?php 
          $categoryList = wp_dropdown_categories('echo=0&show_count=1&orderby=name&id=qcats&name=qcats'); 
          $categoryList = str_replace( "<select name='qcats'", "<select multiple name='qcats[]'", $categoryList );
          echo "<h3>Categories</h3>";
          echo $categoryList; 
          echo "<BR><BR>";

          $tagList = wp_dropdown_categories('taxonomy=post_tag&show_count=1&orderby=name&id=qtags&echo=0&name=qtags');
          $tagList = str_replace( "<select name='qtags'", "<select multiple name='qtags[]'", $tagList );
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