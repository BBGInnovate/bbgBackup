<?php
/**
 * @package bbgRedesign
  template name: Newsletter Template
 */

?>

<!DOCTYPE html>
<html lang="en-US">
	<head>
		<!-- Basic Page Needs
			================================================== -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!-- Mobile Specific Metas
		================================================== -->

			<meta name="HandheldFriendly" content="True">
			<meta name="MobileOptimized" content="320">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="apple-mobile-web-app-title" content="BBG" />

			<!-- for Facebook -->
			<meta property="og:locale" content="en_US">
			<meta property="og:type" content="website" />
			<meta property="og:title" content="BBG" />
			<meta property="og:description" content="The Broadcasting Board of Governors' mission is to inform, engage and connect people around the world in support of freedom and democracy." />
			<meta property="og:image" content="https://bbgredesign.voanews.com/wp-content/media/2016/05/logo-agency-square.png" />
			<meta property="og:url" content="https://bbgredesign.voanews.com/" />

			<!-- for Twitter -->
			<meta property="twitter:card" content="summary">
			<meta name="twitter:site" content="@bbginnovate">
				<meta property="twitter:title" content="BBG">
			<meta property="twitter:description" content="The Broadcasting Board of Governors' mission is to inform, engage and connect people around the world in support of freedom and democracy.">
			<meta property="twitter:image" content="https://bbgredesign.voanews.com/wp-content/media/2016/05/logo-agency-square.png">

			<!-- other og:tags -->
			<meta property="og:site_name" content="BBG" />

			<link rel="profile" href="http://gmpg.org/xfn/11">
			<link rel="pingback" href="">

		<!-- Title, meta description and CSS
		================================================== -->
		<title>BBG Media Highlights</title>

		<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i|Merriweather:300,400,400i,700,700i&subset=latin-ext" rel="stylesheet" type='text/css' media='all' />
		<link rel='stylesheet' id='dashicons-css'  href='https://bbgredesign.voanews.com/wp-includes/css/dashicons.min.css?ver=4.5.3' type='text/css' media='all' />
		<link rel='stylesheet' id='bbginnovate-style-css'  href='https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/style.css?ver=4.5.3' type='text/css' media='all' />

		<!-- IE <9 patch
		================================================== -->

			<!--[if lt IE 9]>
			  <script src="https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/js/vendor/html5shiv.js"></script>
			  <script src="https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/js/vendor/respond.js"></script>
			  <script src="https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/js/vendor/selectivizr-min.js"></script>
			<![endif]-->

			<!-- picturefill - polyfill for srcset sizes on older and/or mobile browsers -->
			<script src="https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/js/vendor/picturefill.min.js"></script>


		<!-- Favicons
		================================================== -->
			<!-- 128x128 -->
			<link rel="shortcut icon" type="image/ico" href="https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/favicons/favicon.ico" />
			<link rel="icon" type="image/png" href="https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/favicons/favicon.png" />

			<!-- 192x192, as recommended for Android
			http://updates.html5rocks.com/2014/11/Support-for-theme-color-in-Chrome-39-for-Android
			-->
			<link rel="icon" type="image/png" sizes="192x192" href="https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/favicons/favicon-192.png" />

			<!-- 57x57 (precomposed) for iPhone 3GS, pre-2011 iPod Touch and older Android devices -->
			<link rel="apple-touch-icon-precomposed" href="https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/favicons/favicon-57.png">
			<!-- 72x72 (precomposed) for 1st generation iPad, iPad 2 and iPad mini -->
			<link rel="apple-touch-icon-precomposed" sizes="72x72" href="https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/favicons/favicon-72.png">
			<!-- 114x114 (precomposed) for iPhone 4, 4S, 5 and post-2011 iPod Touch -->
			<link rel="apple-touch-icon-precomposed" sizes="114x114" href="https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/favicons/favicon-114.png">
			<!-- 144x144 (precomposed) for iPad 3rd and 4th generation -->
			<link rel="apple-touch-icon-precomposed" sizes="144x144" href="https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/favicons/favicon-144.png">

		<style type="text/css" media="screen">
			body {
				background-color: #eee;
			}
			#background {
				background-color: #fff;
				border-top: 1px solid #CCC;
				border-right: 1px solid #CCC;
				border-bottom: 1px solid #f1f1f1;
				border-left: 1px solid #f1f1f1;
				border-radius: 0.3rem;
				-webkit-box-shadow: 1px 0px 1px 0px rgba(153,153,153,0.45);
				-moz-box-shadow: 1px 0px 1px 0px rgba(153,153,153,0.45);
				box-shadow: 1px 0px 1px 0px rgba(153,153,153,0.45);
				margin: 1rem auto 0;
			}
			/*.bbg-header__container__box {
				pad
			}*/
			.bbg-header__logo {
				height: 7rem;
				margin-right: 2rem;
			}
			.bbg-header__site-title {
				font-size: 2.8rem;
				padding-top: .5rem;
			}
			@media screen and (min-width: 490px) {
				.bbg-header__logo {
					height: 9rem;
				}
				.bbg-header__site-title {
					font-size: 3.66667rem;
					padding-top: 1rem;
				}
			}
			@media screen and (min-width: 615px){
				.bbg-header__logo {
					height: 13rem;
				}
				.bbg-header__site-title {
					font-size: 4.7rem;
					padding-top: 1.8rem;
				}
			}

			@media screen and (min-width: 905px) {
				.bbg-header__site-title {
					font-size: 4.7rem;
					padding-top: 4.5rem;
				}
			}

			@media screen and (min-width: 1201px) {
				.bbg-header__site-title {
					font-size: 5.5rem;
				}
			}
			.bbg__label--mobile.large {
				margin-top: 2rem;
			}
			.bbg__ribbon--thin {
				border-top: 1px solid #eee;
				border-top: 1px solid #eee;
				-webkit-box-shadow: 0px 2px 1px 2px rgba(153,153,153,0.45);
				-moz-box-shadow: 0px 2px 1px 2px rgba(153,153,153,0.45);
				box-shadow: 0px 2px 1px 2px rgba(153,153,153,0.45);
			}
			footer {
				border-top: 1px solid #999;
			}
		</style>
	</head>
	<body>
		<div id="background" class="usa-grid-full">
			<div id="page" class="site main-content" role="main">
				<header id="masthead" class="site-header bbg-header" role="banner">
					<div>
						<div id="header" class="usa-grid-full bbg-header__container__box">
							<div class="bbg-header__container">
								<a href="http://localhost/innovationWP/bbg/" rel="home" class="bbg-header__link">
									<img class="bbg-header__logo" src="http://localhost/innovationWP/bbg/wp-content/themes/BBGredesign/img/logo-agency-square.png" alt="Logo for the Broadcasting Board of Governors">
									<h1 class="bbg-header__site-title">Broadcasting <span class='bbg-header__site-title--breakline'>Board of Governors</span></h1>
								</a>
							</div>
						</div>
					</div>
				</header>
				<!-- #masthead -->
				<div id="content" class="site-content">
					<div id="primary" class="content-area">
						<main id="main" class="site-main" role="main">
							<!-- NEWSLETTER INTRODUCTION -->
							<div class="usa-grid">
								<header class="page-header">
									<h5 class="bbg__label--mobile large">Media Highlights</h5>
									<h6 class="bbg__page-header__tagline">Spotlighting some of the media coverage of the BBG networks</h6>
									<h3 id="site-intro" class="usa-font-lead">Our is to inform, engage, and connect people around the world in support of freedom and democracy. The agency’s mission is reinforced by those of the individual broadcasters that are overseen by the BBG. <a href="https://www.bbg.gov/who-we-are/" class="bbg__read-more">LEARN MORE »</a></h3>
								</header>
								<!-- .page-header -->
							</div>

							<div class="usa-grid-full">
								<article class="bbg-blog__excerpt--featured usa-grid-full">
									<header class="entry-header bbg-blog__excerpt-header--featured usa-grid-full">
										<!-- HERO IMAGE FOR FEATURED POST -->
										<div class="bbg__article-header__thumbnail--large">
											<img width="1040" height="624" src="https://bbgredesign.voanews.com/wp-content/media/2016/03/voa__adrift-african-diaspora-cropped-1040x624.jpg" class="attachment-large-thumb size-large-thumb wp-post-image" alt="A rubber boat filled with Africans on the open sea." srcset="https://bbgredesign.voanews.com/wp-content/media/2016/03/voa__adrift-african-diaspora-cropped-1040x624.jpg 1040w, https://bbgredesign.voanews.com/wp-content/media/2016/03/voa__adrift-african-diaspora-cropped-300x180.jpg 300w, https://bbgredesign.voanews.com/wp-content/media/2016/03/voa__adrift-african-diaspora-cropped-768x461.jpg 768w, https://bbgredesign.voanews.com/wp-content/media/2016/03/voa__adrift-african-diaspora-cropped-1024x614.jpg 1024w, https://bbgredesign.voanews.com/wp-content/media/2016/03/voa__adrift-african-diaspora-cropped-600x360.jpg 600w" sizes="(max-width: 1040px) 100vw, 1040px" />
										</div>
										<div class="usa-grid">
											<h2 class="entry-title bbg-blog__excerpt-title--featured"><a href="https://bbgredesign.voanews.com/2016/03/28/special-report-adrift-the-invisible-african-diaspora/" rel="bookmark">Special report: ‘Adrift: The Invisible African Diaspora’</a></h2>
										</div>
									</header>
									<!-- .bbg-blog__excerpt-header--featured -->
									<div class="entry-content bbg-blog__excerpt-content--featured usa-grid">
										<h3 class="usa-font-lead">
											Amid news reports of migrants fleeing turmoil in Syria, Iraq and Afghanistan, over 150,000 people in 2015 have fled violence and hardship in Africa largely have gone unnoticed.
										</h3>
									</div>
									<!-- .entry-content -->
								</article>
								<!-- #post-## -->
								<div class="usa-grid">
									<article id="post-23326" class="bbg-portfolio__excerpt bbg-grid--1-2-3 post-23326 post type-post status-publish format-standard has-post-thumbnail hentry category-highlight category-impact category-inform category-map-it category-project category-press-release category-rferl tag-middle-east-broadcasting-networks tag-radio-free-europeradio-liberty tag-radio-mashaal tag-radio-sawa tag-raiyan-syed tag-rferl tag-syed-abdul-ghani">
										<header class="entry-header bbg-portfolio__excerpt-header">
											<div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">
												<a href="https://bbgredesign.voanews.com/2016/02/19/rferl-video-goes-viral-on-facebook-changes-the-life-of-its-subject/" rel="bookmark" tabindex="-1"><img width="600" height="360" src="https://bbgredesign.voanews.com/wp-content/media/2016/02/Screen-shot-2016-02-19-at-11.58.27-AM-600x360.png" class="attachment-medium-thumb size-medium-thumb wp-post-image" alt="Screenshot of a video with text reading &quot;100 kilos of flour on his back every day.&quot;" srcset="https://bbgredesign.voanews.com/wp-content/media/2016/02/Screen-shot-2016-02-19-at-11.58.27-AM-600x360.png 600w, https://bbgredesign.voanews.com/wp-content/media/2016/02/Screen-shot-2016-02-19-at-11.58.27-AM-1040x624.png 1040w, https://bbgredesign.voanews.com/wp-content/media/2016/02/Screen-shot-2016-02-19-at-11.58.27-AM-300x180.png 300w" sizes="(max-width: 600px) 100vw, 600px" />			</a>
											</div>
											<h3 class="entry-title bbg-portfolio__excerpt-title"><a href="https://bbgredesign.voanews.com/2016/02/19/rferl-video-goes-viral-on-facebook-changes-the-life-of-its-subject/" rel="bookmark">RFE/RL video goes viral on Facebook, changes the life of its subject</a></h3>
											<!--
												<div class="entry-meta bbg__article-meta">
													<span class="posted-on"><time class="entry-date published" datetime="2016-02-19T13:16:05+00:00">February 19, 2016</time></span>		</div>-->
										</header>
										<!-- .entry-header -->
										<div class="entry-content bbg-portfolio__excerpt-content bbg-blog__excerpt-content">
											<p>A touching video produced by Radio Free Europe/Radio Liberty has gone viral on Facebook and is impacting the life of its subject.</p>
										</div>
										<!-- .bbg-portfolio__excerpt-title -->
									</article>
									<!-- .bbg-portfolio__excerpt -->
									<article id="post-23166" class="bbg-portfolio__excerpt bbg-grid--1-2-3 post-23166 post type-post status-publish format-standard has-post-thumbnail hentry category-project category-press-release category-rfa tag-former-north-korean-leader-kim-jong-ill tag-kang-chul-hwan tag-kim-hye-sook tag-kim-jong-ill tag-kim-young-soon tag-north-koreas-prison-camps tag-north-korean-political-prison-camps tag-radio-free-asia-rfa tag-rfa-korean-service tag-rfa-president-libby-liu tag-sung-hye-rim tag-the-book-north-korean-political-prison-camps">
										<header class="entry-header bbg-portfolio__excerpt-header">
											<div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">
												<a href="https://bbgredesign.voanews.com/2016/01/12/radio-free-asia-releases-english-e-book-on-north-koreas-prison-camps/" rel="bookmark" tabindex="-1"><img width="600" height="360" src="https://bbgredesign.voanews.com/wp-content/media/2016/01/illustration__north-korean-prison-camps__RFA-600x360.jpg" class="attachment-medium-thumb size-medium-thumb wp-post-image" alt="Illustration of a North Korean prison camp interrogation. Drawing by Young Jung." srcset="https://bbgredesign.voanews.com/wp-content/media/2016/01/illustration__north-korean-prison-camps__RFA-600x360.jpg 600w, https://bbgredesign.voanews.com/wp-content/media/2016/01/illustration__north-korean-prison-camps__RFA-300x180.jpg 300w, https://bbgredesign.voanews.com/wp-content/media/2016/01/illustration__north-korean-prison-camps__RFA-768x461.jpg 768w, https://bbgredesign.voanews.com/wp-content/media/2016/01/illustration__north-korean-prison-camps__RFA-1024x614.jpg 1024w, https://bbgredesign.voanews.com/wp-content/media/2016/01/illustration__north-korean-prison-camps__RFA.jpg 1040w" sizes="(max-width: 600px) 100vw, 600px" />			</a>
											</div>
											<h3 class="entry-title bbg-portfolio__excerpt-title"><a href="https://bbgredesign.voanews.com/2016/01/12/radio-free-asia-releases-english-e-book-on-north-koreas-prison-camps/" rel="bookmark">Radio Free Asia releases English e-book on North Korea’s prison camps</a></h3>
											<!--
												<div class="entry-meta bbg__article-meta">
													<span class="posted-on"><time class="entry-date published" datetime="2016-01-12T15:15:42+00:00">January 12, 2016</time></span>		</div>-->
										</header>
										<!-- .entry-header -->
										<div class="entry-content bbg-portfolio__excerpt-content bbg-blog__excerpt-content">
											<p>Radio Free Asia (RFA) today released the English version of its e-book about North Korea’s infamous secret labor detention camps for political prisoners and the horrendous human rights violations committed inside them.</p>
										</div>
										<!-- .bbg-portfolio__excerpt-title -->
									</article>
									<!-- .bbg-portfolio__excerpt -->
									<article id="post-16826" class="bbg-portfolio__excerpt bbg-grid--1-2-3 post-16826 post type-post status-publish format-standard has-post-thumbnail hentry category-project category-press-release category-rfa tag-bbg tag-broadcasting-board-of-governors tag-china tag-nurmuhemmet-yasin tag-radio-free-asia tag-rfa tag-uyghur">
										<header class="entry-header bbg-portfolio__excerpt-header">
											<div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">
												<a href="https://bbgredesign.voanews.com/2013/11/12/rfa-unveils-e-book-of-jailed-uyghur-writer-yasins-writings/" rel="bookmark" tabindex="-1"><img width="600" height="360" src="https://bbgredesign.voanews.com/wp-content/media/2013/11/rfa__wild-pigeon__illustration-600x360.jpg" class="attachment-medium-thumb size-medium-thumb wp-post-image" alt="Illustration of pigeons for RFA edition of Yasin&#039;s short story &quot;Wild Pigeon&quot;." srcset="https://bbgredesign.voanews.com/wp-content/media/2013/11/rfa__wild-pigeon__illustration-600x360.jpg 600w, https://bbgredesign.voanews.com/wp-content/media/2013/11/rfa__wild-pigeon__illustration-300x180.jpg 300w, https://bbgredesign.voanews.com/wp-content/media/2013/11/rfa__wild-pigeon__illustration-768x461.jpg 768w, https://bbgredesign.voanews.com/wp-content/media/2013/11/rfa__wild-pigeon__illustration-1024x614.jpg 1024w, https://bbgredesign.voanews.com/wp-content/media/2013/11/rfa__wild-pigeon__illustration.jpg 1040w" sizes="(max-width: 600px) 100vw, 600px" />			</a>
											</div>
											<h3 class="entry-title bbg-portfolio__excerpt-title"><a href="https://bbgredesign.voanews.com/2013/11/12/rfa-unveils-e-book-of-jailed-uyghur-writer-yasins-writings/" rel="bookmark">RFA unveils e-book of jailed Uyghur writer Yasin’s writings</a></h3>
											<!--
												<div class="entry-meta bbg__article-meta">
													<span class="posted-on"><time class="entry-date published" datetime="2013-11-12T13:16:19+00:00">November 12, 2013</time></span>		</div>-->
										</header>
										<!-- .entry-header -->
										<div class="entry-content bbg-portfolio__excerpt-content bbg-blog__excerpt-content">
											<p>Radio Free Asia (RFA) today launched an e-book collecting the writings of Nurmuhemmet Yasin, an award-winning Uyghur writer whom Chinese authorities sentenced to 10 years imprisonment in 2004.</p>
										</div>
										<!-- .bbg-portfolio__excerpt-title -->
									</article>
									<!-- .bbg-portfolio__excerpt -->
								</div>
								<!-- .usa-grid -->
							</div>
							<!-- .usa-grid-full -->
						</main>
						<!-- #main -->
					</div>
					<!-- #primary -->
				</div>
				<!-- #content -->
				<!-- Quotation -->
				<section class="usa-section ">
					<div class="usa-grid">
						<div class="bbg__quotation ">
							<h2 class="bbg__quotation-text--large">“Historians now acknowledge that the Voice of America, Radio Free Europe were major contributors to the bringing down the then Soviet Union. There’s no doubt about it.”</h2>
							<div class="bbg__quotation-attribution__container">
								<p class="bbg__quotation-attribution"><img src="https://bbgredesign.voanews.com/wp-content/media/2016/05/mugshot_John-McCain-100.jpg" class="bbg__quotation-attribution__mugshot"><span class="bbg__quotation-attribution__text"><span class="bbg__quotation-attribution__name">U.S. Senator <span class="u--no-wrap">John McCain</span> (R-AZ)</span><span class="bbg__quotation-attribution__credit"></span></span></p>
							</div>
						</div>
					</div>
				</section>
				<!-- Quotation -->
				<!-- Announcement Ribbon (Threats to press) -->
				<section class="usa-grid-full bbg__about__children--row bbg__ribbon--thin">
					<div class="usa-grid">
						<div class="bbg__announcement__flexbox">
							<div class="bbg__announcement__photo" style="background-image: url(https://bbgredesign.voanews.com/wp-content/media/2015/05/Burundis-Interior-Minister-Denies-Coup.jpeg);"></div>
							<div>
								<h6 class="bbg__label">Threats to press</h6>
								<h2 class="bbg__announcement__headline"><a href="https://bbgredesign.voanews.com/2016/06/30/al-finch-keeping-journalists-safe-at-home-and-abroad/">RFE/RL calls for Turkmen journalist’s release</a></h2>
								<p>On the one year anniversary of the imprisonment of Saparmamed Nepeskuliev, a contributor to RFE/RL’s Turkmen Service and other independent news outlets, RFE/RL joined a chorus of international organizations calling for his freedom.</p>
							</div>
						</div>
						<!-- .bbg__announcement__flexbox -->
					</div>
					<!-- .usa-grid -->
				</section>

				<!-- Entity list -->
				<section id="entities" class="usa-section bbg__staff">
					<div class="usa-grid">
						<h6 class="bbg__label"><a href="<?php echo get_permalink( get_page_by_path( 'networks' ) ); ?>" title="A list of the BBG broadcasters.">Our networks</a></h6>
						<div class="usa-intro bbg__broadcasters__intro">
							<h3 class="usa-font-lead">Every week, more than 226 million listeners, viewers and Internet users around the world turn on, tune in and log onto U.S. international broadcasting programs. The day-to-day broadcasting activities are carried out by the individual BBG international broadcasters.</h3>
						</div>
						<div class="usa-grid-full">
							<article class="bbg__entity bbg-grid--1-1-1-2">
								<div class="bbg__avatar__container bbg__entity__icon">
									<a href="https://bbgredesign.voanews.com/networks/voa/" tabindex="-1">
										<div class="bbg__avatar bbg__entity__icon__image" style="background-image: url(https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/logo_voa--circle-200.png);"></div>
									</a>
								</div>
								<div class="bbg__entity__text">
									<h2 class="bbg__entity__name"><a href="https://bbgredesign.voanews.com/networks/voa/">Voice of America</a></h2>
									<p class="bbg__entity__text-description">Voice of America provides trusted and objective news and information in 45 languages to a measured weekly audience of more than 187 million people around the world.</p>
								</div>
							</article>
							<article class="bbg__entity bbg-grid--1-1-1-2">
								<div class="bbg__avatar__container bbg__entity__icon">
									<a href="https://bbgredesign.voanews.com/networks/rferl/" tabindex="-1">
										<div class="bbg__avatar bbg__entity__icon__image" style="background-image: url(https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/logo_rferl--circle-200.png);"></div>
									</a>
								</div>
								<div class="bbg__entity__text">
									<h2 class="bbg__entity__name"><a href="https://bbgredesign.voanews.com/networks/rferl/">Radio Free Europe / Radio Liberty</a></h2>
									<p class="bbg__entity__text-description">RFE/RL reaches 123.6 million people in 28 languages and in 23 countries, including Afghanistan, Iran, Pakistan, Russia, and Ukraine.</p>
								</div>
							</article>
							<article class="bbg__entity bbg-grid--1-1-1-2">
								<div class="bbg__avatar__container bbg__entity__icon">
									<a href="https://bbgredesign.voanews.com/networks/ocb/" tabindex="-1">
										<div class="bbg__avatar bbg__entity__icon__image" style="background-image: url(https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/logo_ocb--circle-200.png);"></div>
									</a>
								</div>
								<div class="bbg__entity__text">
									<h2 class="bbg__entity__name"><a href="https://bbgredesign.voanews.com/networks/ocb/">Office of Cuba Broadcasting</a></h2>
									<p class="bbg__entity__text-description">OCB oversees Radio and Television Martí at its headquarters in Miami, Florida. Combined with the online platform, martinoticias.com, the Martís are a one-of-a-kind service that brings unbiased, objective information to all Cubans.</p>
								</div>
							</article>
							<article class="bbg__entity bbg-grid--1-1-1-2">
								<div class="bbg__avatar__container bbg__entity__icon">
									<a href="https://bbgredesign.voanews.com/networks/rfa/" tabindex="-1">
										<div class="bbg__avatar bbg__entity__icon__image" style="background-image: url(https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/logo_rfa--circle-200.png);"></div>
									</a>
								</div>
								<div class="bbg__entity__text">
									<h2 class="bbg__entity__name"><a href="https://bbgredesign.voanews.com/networks/rfa/">Radio Free Asia</a></h2>
									<p class="bbg__entity__text-description">RFA journalists provide uncensored, fact-based news to citizens of these countries, among the world’s worst media environments.</p>
								</div>
							</article>
							<article class="bbg__entity bbg-grid--1-1-1-2">
								<div class="bbg__avatar__container bbg__entity__icon">
									<a href="https://bbgredesign.voanews.com/networks/mbn/" tabindex="-1">
										<div class="bbg__avatar bbg__entity__icon__image" style="background-image: url(https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/logo_mbn--circle-200.png);"></div>
									</a>
								</div>
								<div class="bbg__entity__text">
									<h2 class="bbg__entity__name"><a href="https://bbgredesign.voanews.com/networks/mbn/">Middle East Broadcasting Networks</a></h2>
									<p class="bbg__entity__text-description">MBN is the non-profit news organization that operates Alhurra Television, Radio Sawa and MBN Digital reaching audiences in 22 countries across the Middle East and North Africa.</p>
								</div>
							</article>
						</div>
					</div>
				</section>
				<!-- entity list -->
				</main>
			</div>
			<!-- #primary .content-area -->
			</div>
			<!-- #main .site-main -->
			</div>
			<!-- #content -->
			<footer class="usa-footer usa-footer-medium usa-sans" role="contentinfo" style="position: relative; z-index: 9990;">
				<div class="usa-footer-secondary_section usa-footer-big-secondary-section">
					<div class="usa-grid" itemscope="" itemtype="https://schema.org/GovernmentOffice">
						<div class="usa-footer-logo usa-width-one-half">
							<a href="http://localhost/innovationWP/bbg">
								<img itemprop="image" role="img" aria-label="BBG logo" class="usa-footer-logo-img" src="http://localhost/innovationWP/bbg/wp-content/themes/BBGredesign/img/logo-agency-square.png" alt="Broadcasting Board of Governors logo">
								<h3 itemprop="name" class="usa-footer-logo-heading">Broadcasting Board of Governors</h3>
							</a>
						</div>
						<div class="usa-footer-contact-links usa-width-one-half">
							<div class="usa-social-links">
								<a class="bbg_footer_social-link usa-link-facebook" href="https://www.facebook.com/BBGgov/" role="img" aria-label="Facebook"></a>
								<a class="bbg_footer_social-link usa-link-twitter" href="https://twitter.com/BBGgov" role="img" aria-label="Twitter"></a>
								<a class="bbg_footer_social-link usa-link-youtube" href="https://www.youtube.com/user/bbgtunein" role="img" aria-label="YouTube"></a>
								<a class="bbg_footer_social-link usa-link-rss" href="https://www.bbg.gov/category/press-release/feed/" role="img" aria-label="RSS"></a>
							</div>
							<address itemscope="" itemtype="https://schema.org/GovernmentOffice">
								<h3 class="usa-footer-contact-heading">Contact the BBG</h3>
								<p itemprop="address" aria-label="address">330 Independence Avenue, SW<br>Washington, DC 20237</p>
								<p itemprop="telephone" aria-label="telephone"><a href="tel=+01-202-203-4000">(202) 203-4000</a></p>
								<a itemprop="email" aria-label="email" href="mailto:publicaffairs@bbg.gov">publicaffairs@bbg.gov</a>
							</address>
						</div>
					</div>
				</div>
			</footer>
			</div>
			<!-- #page -->
		</div>
	</body>
</html>