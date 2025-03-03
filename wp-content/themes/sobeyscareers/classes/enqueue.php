<?php

namespace fp;

// Before registering any new scripts please check https://developer.wordpress.org/reference/functions/wp_enqueue_script/ for libraries already included in WordPress

function css()
{
	$pageurl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	wp_register_style('fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700|Raleway:400,300|Lora');
	wp_enqueue_style('fonts');


	wp_enqueue_style('dashicons');

	// Bootstrap css components should be included via assets/scss/_bootstrap-custom.scss
	wp_register_style('main', get_template_directory_uri() . '/dist/css/index.min.css');
	wp_enqueue_style('main');

	wp_register_style('general', get_template_directory_uri() . '/dist/css/general.min.css', array('main'));
	wp_enqueue_style('general');

	//Reason: Added code to include plugin-slider File
	wp_register_style('plugin-slider', get_template_directory_uri() . '/dist/css/plugin-slider.min.css', array('general'));
	wp_enqueue_style('plugin-slider');

	wp_register_style('plugin-cookie-policy', get_template_directory_uri() . '/dist/css/pages/plugin-cookie-policy.min.css', array('main'));
	wp_enqueue_style('plugin-cookie-policy');

	if (isset($_GET['fl_builder'])) {
		wp_register_style('bb', get_stylesheet_directory_uri() . '/dist/css/bb.min.css');
		wp_enqueue_style('bb');
	}

	if (is_page('jobs') || is_page('search') || is_page('advantages') || is_page('application') || is_page('job-descriptions') || is_page('emplois') || is_page('recherche') || is_page('avantages') || is_page('portraits') || is_page('candidature-spontanee') || is_singular('fp_job')) {
		wp_register_style('plugin-sobeys-job-manager', get_template_directory_uri() . '/dist/css/plugin-sobeys-job-manager.min.css', array('main'));
		wp_enqueue_style('plugin-sobeys-job-manager');
	}

	if (is_front_page()) {
		wp_register_style('home', get_template_directory_uri() . '/dist/css/pages/page-home.min.css', array('main'));
		wp_enqueue_style('home');
	}

	if (is_search()) {
		wp_register_style('search', get_template_directory_uri() . '/dist/css/templates/template-search.min.css', array('main'));
		wp_enqueue_style('search');
	}
	//Reason: Added code for styling "tradition contest" page
	if (is_page('tradition_contest') || is_page('tradition_concours') || is_page('entertainlikegrandma') || is_page('lesfetescommechezmamie')) {
		wp_register_style('tradition-contest', get_template_directory_uri() . '/dist/css/pages/page-tradition-contest.min.css', array('main'));
		wp_enqueue_style('tradition-contest');
	}

	 if (is_singular('store')) {
		wp_register_style('single-store', get_template_directory_uri() . '/dist/css/templates/template-single-store.min.css', array('main'));
	 	wp_enqueue_style('single-store');
	 }
	 if (is_singular('story')) {
		wp_register_style('single-story', get_template_directory_uri() . '/dist/css/templates/template-single-story.min.css', array('main'));
	 	wp_enqueue_style('single-story');
	 }
	 if (is_page('sceneplus')) {
		wp_register_style( 'sceneplus', get_template_directory_uri() . '/dist/css/pages/page-sceneplus.min.css', array( 'main' ) );
		wp_enqueue_style( 'sceneplus' );
	}
	if (is_page('panache')) {
		wp_register_style('panache', get_template_directory_uri() . '/dist/css/pages/page-panache.min.css', array('main'));
		wp_enqueue_style('panache');
	}
	if (is_page('c-est-pret') || is_page('cest-pret')) {
		wp_register_style('page-cest-pret', get_template_directory_uri() . '/dist/css/pages/page-cest-pret.min.css', array('main'));
		wp_enqueue_style('page-cest-pret');
	}
	if (is_page('pret-a-cuire')){
		wp_register_style('pret-a-cuire', get_template_directory_uri() . '/dist/css/pages/page-pret-a-cuire.min.css', array('main'));
		wp_enqueue_style('pret-a-cuire');
	}
	if (is_page('compliments')) {
		wp_register_style('compliments', get_template_directory_uri() . '/dist/css/pages/page-compliments.min.css', array('main'));
		wp_enqueue_style('compliments');
	}
	if (is_page('contest') || is_page('concours')) {
		wp_register_style('contest', get_template_directory_uri() . '/dist/css/pages/page-contest.min.css', array('main'));
		wp_enqueue_style('contest');
	}
	if (is_404()) {
		wp_register_style('page-404', get_template_directory_uri() . '/dist/css/templates/template-page-404.min.css', array('main'));
		wp_enqueue_style('page-404');
	}
	if (is_page('privacy-policy') || is_page('politique-de-confidentialite')) {
		wp_register_style('privacy-policy', get_template_directory_uri() . '/dist/css/pages/page-privacy-policy.min.css', array('main'));
		wp_enqueue_style('privacy-policy');
	}
	if (is_page('terms-of-use') || is_page('conditions-dutilisation')) {
		wp_register_style('terms-of-use', get_template_directory_uri() . '/dist/css/pages/page-terms-of-use.min.css', array('main'));
		wp_enqueue_style('terms-of-use');
	}
	if (is_page('neighbourhood-stories') || is_page('histoires-de-quartier')) {
		wp_register_style('neighborhood-stories', get_template_directory_uri() . '/dist/css/pages/page-neighborhood-stories.min.css', array('main'));
		wp_enqueue_style('neighborhood-stories');
	}
	if (is_page('house-brands') || is_page('marques-maison')) {
		wp_register_style('page-house-brands', get_template_directory_uri() . '/dist/css/pages/page-house-brands.min.css', array('main'));
		wp_enqueue_style('page-house-brands');
	}
	if ( is_page( 'economic-choices' ) || is_page( 'choix-economiques' ) ) {
		wp_register_style( 'page-economic-choices', get_template_directory_uri() . '/dist/css/pages/page-economic-choices.min.css', array( 'main' ) );
		wp_enqueue_style( 'page-economic-choices' );
	}
	if ( is_page( 'feed-the-dream-contest' ) || is_page( 'concours-nourrir-le-reve' ) ) {
		wp_register_style( 'page-feed-the-dream', get_template_directory_uri() . '/dist/css/pages/page-feed-the-dream.min.css', array( 'main' ) );
		wp_enqueue_style( 'page-feed-the-dream' );
	}
	if (is_page('in-the-community') || is_page('a-propos2')) {
		wp_register_style('in-the-community', get_template_directory_uri() . '/dist/css/pages/page-in-the-community.min.css', array('main'));
		wp_enqueue_style('in-the-community');
	}
	if (is_page('fondation-olo')) {
		wp_register_style('fondation-olo', get_template_directory_uri() . '/dist/css/pages/page-fondation-olo.min.css', array('main'));
		wp_enqueue_style('fondation-olo');
	}
	if (is_page('welcome-to-mr-smileys') || is_page('entrevue-avec-daniel-croteau')) {
		wp_register_style('welcome-to-mr-smileys', get_template_directory_uri() . '/dist/css/pages/page-welcome-to-mr-smileys.min.css', array('main'));
		wp_enqueue_style('welcome-to-mr-smileys');
	}
	if (is_page('interview-with-denis-and-jean-francois-tremblay') || is_page('entrevue-avec-denis-et-jean-francois-tremblay')) {
		wp_register_style('interview-with-denis-and-jean-francois-tremblay', get_template_directory_uri() . '/dist/css/pages/page-interview-with-denis-and-jean-francois-tremblay.min.css', array('main'));
		wp_enqueue_style('interview-with-denis-and-jean-francois-tremblay');
	}
	if (is_page('interview-with-fernand-and-sebastien-daviau') || is_page('entrevue-avec-fernand-et-sebastien-daviau')) {
		wp_register_style('interview-with-fernand-and-sebastien-daviau', get_template_directory_uri() . '/dist/css/pages/page-interview-with-fernand-and-sebastien-daviau.min.css', array('main'));
		wp_enqueue_style('interview-with-fernand-and-sebastien-daviau');
	}
	if (is_page('interview-with-junior-forest') || is_page('entrevue-avec-junior-forest')) {
		wp_register_style('interview-with-junior-forest', get_template_directory_uri() . '/dist/css/pages/page-interview-with-junior-forest.min.css', array('main'));
		wp_enqueue_style('interview-with-junior-forest');
	}
	if (is_page('interview-with-audrey-spence') || is_page('entrevue-avec-audrey-spence')) {
		wp_register_style('interview-with-audrey-spence', get_template_directory_uri() . '/dist/css/pages/page-interview-with-audrey-spence.min.css', array('main'));
		wp_enqueue_style('interview-with-audrey-spence');
	}
	if (is_page('about-us') || is_page('a-propos') || is_page('neighborhood-stories')) {
		wp_register_style('about-us', get_template_directory_uri() . '/dist/css/pages/page-about-us.min.css', array('main'));
		wp_enqueue_style('about-us');
	}
	if (is_page('covid-19')) {
		wp_register_style('covid-19', get_template_directory_uri() . '/dist/css/pages/page-covid-19.min.css', array('main'));
		wp_enqueue_style('covid-19');
	}
	if (is_page('inspiration') || is_page('inspiration')) {
		wp_register_style('inspiration', get_template_directory_uri() . '/dist/css/pages/page-inspiration.min.css', array('main'));
		wp_enqueue_style('inspiration');
	}
	if (is_page('blog-and-tips') || is_page('blogs-conseils')) {
		wp_register_style('blog-and-tips', get_template_directory_uri() . '/dist/css/pages/page-blog-and-tips.min.css', array('main'));
		wp_enqueue_style('blog-and-tips');
	}
	if (is_page('contest-aq-2024') || is_page('concours-aq-2024')) {
		wp_register_style('contest-aq-2024', get_template_directory_uri() . '/dist/css/pages/page-contest-aq-2024.min.css', array('main'));
		wp_enqueue_style('contest-aq-2024');
	}
	if (is_page('tradition_contest') || is_page('tradition_concours') || is_page('entertainlikegrandma') || is_page('lesfetescommechezmamie') || is_page('christmas-contest') || is_page('concours-de-noel')) {
		wp_register_style('tradition-contest', get_template_directory_uri() . '/dist/css/pages/page-tradition-contest.min.css', array('main'));
		wp_enqueue_style('tradition-contest');
	}
	if (is_archive('stores')) {
		wp_register_style('store-locator', get_template_directory_uri() . '/dist/css/templates/template-archive-stores.min.css', array('main'));
		wp_enqueue_style('store-locator');
	}
	if (is_archive('article') || is_archive('story') || is_tax('article-category')) {
		wp_register_style('archive-article', get_template_directory_uri() . '/dist/css/templates/template-archive-articles.min.css', array('main'));
		wp_enqueue_style('archive-article');
	}
	if (is_archive('recipe')) {
		wp_register_style('archive-recipe', get_template_directory_uri() . '/dist/css/templates/template-archive-recipes.min.css', array('main'));
		wp_enqueue_style('archive-recipe');
	}
	if (is_singular('article')) {
	 	wp_register_style('single-article', get_template_directory_uri() . '/dist/css/templates/template-single-article.min.css', array('main'));
	 	wp_enqueue_style('single-article');
	}
	if (is_singular('recipe')) {
	 	wp_register_style('single-recipe', get_template_directory_uri() . '/dist/css/templates/template-single-recipe.min.css', array('main'));
	 	wp_enqueue_style('single-recipe');
	}
	if (is_archive('recipe-tag')) {
		wp_register_style('recipe-tag', get_template_directory_uri() . '/dist/css/templates/template-recipe-tag.min.css', array('main'));
		wp_enqueue_style('recipe-tag');
   }
	if (is_page('dream-or-money-contest-winners') || is_page('gagnants-du-concours-reve-ou-largent')) {
		wp_register_style('page-dream-or-money-contest-winners', get_template_directory_uri() . '/dist/css/pages/page-dream-or-money-contest-winners.min.css', array('main'));
		wp_enqueue_style('page-dream-or-money-contest-winners');
	}
	if (is_page('newsletter') || is_page('infolettre')) {
		wp_register_style('page-newsletter', get_template_directory_uri() . '/dist/css/pages/page-newsletter.min.css', array('main'));
		wp_enqueue_style('page-newsletter');
	}
	if (is_page('videos')) {
		wp_register_style('page-videos', get_template_directory_uri() . '/dist/css/pages/page-videos.min.css', array('main'));
		wp_enqueue_style('page-videos');
	}
	if (is_page('air-miles')) {
		wp_register_style('air-miles', get_template_directory_uri() . '/dist/css/pages/page-airmiles.min.css', array('main'));
		wp_enqueue_style('air-miles');
	}
	if (is_page('holiday-surprise-contest') || is_page('concours-les-surprises-des-fetes')) {
		wp_register_style('holiday-surprise-contest', get_template_directory_uri() . '/dist/css/pages/page-holiday-surprise-contest.min.css', array('main'));
		wp_enqueue_style('holiday-surprise-contest');
	}
	if (is_page('contest') || is_page('concours')) {
		wp_register_style('contest', get_template_directory_uri() . '/dist/css/pages/page-contest.min.css', array('main'));
		wp_enqueue_style('contest');
	}
	if (is_page('contest-groceriesandgetaways') || is_page('concours-epicerieetescapade')) {
		wp_register_style('contest-groceriesandgetaways', get_template_directory_uri() . '/dist/css/pages/page-groceriesandgetaways.min.css', array('main'));
		wp_enqueue_style('contest-groceriesandgetaways');
	}
	if (is_page('flyer') || is_page('circulaire')) {
		wp_register_style('flyer', get_template_directory_uri() . '/dist/css/pages/page-flyer.min.css', array('main'));
		wp_enqueue_style('flyer');
	}
	if (is_page('proud-of-our-regions') || is_page('fiers-de-nos-regions') || is_page('want-to-go-out-to-eat') || is_page('on-sort-manger-dehors')) {
		wp_register_style('proud-of-our-regions', get_template_directory_uri() . '/dist/css/pages/page-proud-of-our-regions.min.css', array('main'));
		wp_enqueue_style('proud-of-our-regions');
	}
	if (is_page('spend-your-time-camping-not-cooking') || is_page('maximum-de-camping-minimum-de-cuisine')) {
		wp_register_style('spend-your-time-camping-not-cooking', get_template_directory_uri() . '/dist/css/pages/page-spend-your-time-camping-not-cooking.min.css', array('main'));
		wp_enqueue_style('spend-your-time-camping-not-cooking');
	}
	if (is_page('nb_coop_trad') || is_page('nb_trad_co-op')) {
		wp_register_style('nb_coop_trad', get_template_directory_uri() . '/dist/css/pages/page-nb-coop-trad.min.css', array('main'));
		wp_enqueue_style('nb_coop_trad');
	}
	if (strpos($pageurl, 'gift-cards') == true || strpos($pageurl, 'cartes-cadeaux') == true) {
		wp_register_style('gift-cards', get_template_directory_uri() . '/dist/css/pages/page-gift-cards.min.css', array('general'));
		wp_enqueue_style('gift-cards');
	}
	// if (is_singular('store')) {
	// 	wp_register_style('single-store', get_template_directory_uri() . '/dist/css/pages/page-single-store.min.css', array('main'));
	// 	wp_enqueue_style('single-store');
	// }
}
add_action('wp_enqueue_scripts', 'fp\css');

function js()
{
	wp_enqueue_script('jquery');

	// Bootstrap js component files need to be enabled via Gulpfile.json and Gulp ran first before uncommenting these files

	wp_enqueue_script('bootstrap-util', get_template_directory_uri() . '/dist/js/bootstrap/util.min.js', array('jquery'), '4.0.0', true);
	wp_enqueue_script('bootstrap-alert', get_template_directory_uri() . '/dist/js/bootstrap/alert.min.js', array('jquery', 'bootstrap-util'), '4.0.0', true);
	wp_enqueue_script('bootstrap-button', get_template_directory_uri() . '/dist/js/bootstrap/button.min.js', array('jquery', 'bootstrap-util'), '4.0.0', true);
	wp_enqueue_script('bootstrap-carousel', get_template_directory_uri() . '/dist/js/bootstrap/carousel.min.js', array('jquery', 'bootstrap-util'), '4.0.0', true);
	wp_enqueue_script('bootstrap-collapse', get_template_directory_uri() . '/dist/js/bootstrap/collapse.min.js', array('jquery', 'bootstrap-util'), '4.0.0', true);
	wp_enqueue_script('bootstrap-dropdown', get_template_directory_uri() . '/dist/js/bootstrap/dropdown.min.js', array('jquery', 'bootstrap-util'), '4.0.0', true);
	wp_enqueue_script('bootstrap-modal', get_template_directory_uri() . '/dist/js/bootstrap/modal.min.js', array('jquery', 'bootstrap-util'), '4.0.0', true);
	wp_enqueue_script('bootstrap-scrollspy', get_template_directory_uri() . '/dist/js/bootstrap/scrollspy.min.js', array('jquery', 'bootstrap-util'), '4.0.0', true);
	wp_enqueue_script('bootstrap-tab', get_template_directory_uri() . '/dist/js/bootstrap/tab.min.js', array('jquery', 'bootstrap-util'), '4.0.0', true);
	wp_enqueue_script('bootstrap-toast', get_template_directory_uri() . '/dist/js/bootstrap/toast.min.js', array('jquery', 'bootstrap-util'), '4.0.0', true);
	wp_enqueue_script('bootstrap-tooltip', get_template_directory_uri() . '/dist/js/bootstrap/tooltip.min.js', array('jquery', 'bootstrap-util'), '4.0.0', true);
	wp_enqueue_script('bootstrap-popover', get_template_directory_uri() . '/dist/js/bootstrap/popover.min.js', array('jquery', 'bootstrap-util', 'bootstrap-tooltip'), '4.0.0', true);
	wp_enqueue_script('common-js', get_template_directory_uri() . '/dist/js/common.min.js', array(), date("Y-m-d---G-i-s", filemtime(get_stylesheet_directory() . '/dist/js/common.min.js')));
	wp_enqueue_script('page-groceriesandgetaways-js', get_template_directory_uri() . '/dist/js/page-groceriesandgetaways.min.js', array(), date("Y-m-d---G-i-s", filemtime(get_stylesheet_directory() . '/dist/js/page-groceriesandgetaways.min.js')));
}
add_action('wp_enqueue_scripts', 'fp\js');

function defer_parsing_of_js($url)
{
	if (is_user_logged_in()) return $url; //don't break WP Admin
	if (FALSE === strpos($url, '.js')) return $url;
	if (strpos($url, 'jquery.min.js')) return $url;
	if (strpos($url, 'jquery.js')) return $url;
	if (strpos($url, 'enquire')) return $url;
	if (strpos($url, 'sobeys-store-locator')) return $url;
	if (strpos($url, 'cookie')) return $url;
	if (strpos($url, 'sl-header')) return $url;
	if (strpos($url, 'jq-validate-js')) return $url;

	return str_replace(' src', ' defer src', $url);
}
add_filter('script_loader_tag', 'fp\defer_parsing_of_js', 10);