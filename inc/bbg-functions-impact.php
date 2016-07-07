<?php 
	
	function oneImpactStory ($story) {
		$url = $story['url'];
		$title = $story['title'];
		$excerpt = $story['excerpt'];
		$thumb = $story['thumb'];

		$s = '';
		$s .= '<article class="' . implode(" ", get_post_class( "bbg__article" )) . '"">';
		$s .=	'<header class="entry-header bbg-portfolio__excerpt-header">';
		$s .=		'<div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">';
		$s .=			'<a tabindex="-1" href="' . $url . '">' . $thumb . '</a>';
		$s .=		'</div>';
		$s .=		'<p class=""><a href="'.$url.'">' . $title . '</a></p><BR>';
		$s .=	'</header><!-- .entry-header -->';
		/*
		$s .=	'<div class="entry-content bbg-portfolio__excerpt-content bbg-blog__excerpt-content">';
		$s .=		$excerpt;
		$s .=	'</div><!-- .bbg-portfolio__excerpt-title -->';
		*/
		$s .= '</article><!-- .bbg-portfolio__excerpt -->';
		return $s;
	}

	// Add shortcode reference for the BBG mission
	function impact_shortcode( $atts ) {
	    $label = 'Impact Stories';
	    $permalink = '';

	    $impacts = array(
	    	'inform' => array(),
	    	'engage' => array(),
	    	'be-influential' => array()
	    );
		
	    $engageCategoryID = get_cat_ID('Engage');
	    $influentialCategoryID = get_cat_ID('Be Influential');
	    $informCategoryID = get_cat_ID('Inform');

		$qParams=array(
			'post_type' => array('post'),
			'posts_per_page' => 100,
			'category__in' => array(
									$engageCategoryID,
									$influentialCategoryID,
									$informCategoryID
							  ),
			'orderby', 'date',
			'order', 'DESC'
		);


		$custom_query = new WP_Query($qParams);
		if ($custom_query -> have_posts()) {
			while ( $custom_query -> have_posts() )  {
				$custom_query->the_post();
				$id=get_the_ID();
				if( has_category('inform')) {
					$target = &$impacts['inform'];
				} else if (has_category('be-influential')) {
					$target = &$impacts['be-influential'];
				} else if (has_category('engage')) {
					$target = &$impacts['engage'];
				}
				$target[] = array('url'=>get_permalink($id), 'title'=> get_the_title($id), 'excerpt'=>get_the_excerpt(), 'thumb'=>get_the_post_thumbnail( $id, 'small-thumb' ));
			}
		}
	//	var_dump($impacts);
		wp_reset_postdata();

		$impactPortfolioPermalink = get_permalink( get_page_by_path( 'our-work/impact-and-results/impact-portfolio/' ) );

		$s  = ''; 
		$s .= '<h5 class="bbg__label small"><a href="' . $impactPortfolioPermalink .'">' . $label .'</a></h5>';
		

		if (count($impacts['inform'])) {
			$informLink = get_category
			$s .= '<h3 class="bbg__about__grandchild__title"><a href="' . get_category_link($informCategoryID) . '">INFORM</a></h3>';
			$s .= oneImpactStory($impacts['inform'][0]);
		} 
		if (count($impacts['engage'])) {
			$s .= '<h3 class="bbg__about__grandchild__title"><a href="'. get_category_link($engageCategoryID) . '">ENGAGE</a></h3>';			
			$s .= oneImpactStory($impacts['engage'][0]);

		} 
		if (count($impacts['be-influential'])) {
			$s .= '<h3 class="bbg__about__grandchild__title"><a href="' . get_category_link($influentialCategoryID) . '">BE INFLUENTIAL</a></h3>';
			$s .= oneImpactStory($impacts['be-influential'][0]);
		}
		return $s;
	}
	add_shortcode( 'impact', 'impact_shortcode' );


?>