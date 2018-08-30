<?php
add_filter( 'show_admin_bar', '__return_false' );
add_action( 'wp_enqueue_scripts', 'test_addMedia' );
add_action( 'after_setup_theme', 'test_afterSetup' );
add_action( 'widgets_init', 'test_widgets' );
add_action( 'wp_head', 'test_jsThrough' );


function test_addMedia() {
	wp_enqueue_style('test_addBootstrapCdn', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
	wp_enqueue_style('test_addFontAwesomeCdn', 'https://use.fontawesome.com/releases/v5.1.0/css/all.css');
	wp_enqueue_style('test_mainStyle', get_stylesheet_uri());
	wp_enqueue_script(
		'test_jsCookie',
		get_template_directory_uri() . '/assets/js/js-cookie.js',
		['jquery'], null, true);
	wp_enqueue_script(
		'test_mainScript',
		get_template_directory_uri() . '/assets/js/main.js',
		[], null, true);
}

function test_afterSetup() {
	// TODO: посмотреть, как готовить к переводу
//	register_nav_menu( 'top', 'Верхнее');
	register_nav_menus( ['top'    => __( 'top', 'Верхнее' )] );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
}

function test_widgets() {
	register_sidebar([
		'name' => __('Aside Sidebar'),
		'id' => 'aside_sidebar',
		'description' => __('Боковой сайдбар'),
		'before_widget' => '<div>',
		'after_widget' => '</div>'
	]);

	register_sidebar([
		'name' => __('Bottom Sidebar'),
		'id' => 'bottom_sidebar',
		'description' => __('Сайдбар в подвале'),
		'before_widget' => '<div>',
		'after_widget' => '</div>'
	]);
}

function test_jsThrough() {
	$vars = [
		'ajax_url' => admin_url('admin-ajax.php')
	];
	echo '<script>window.wp = ' . json_encode($vars) . ' </script>';
}


add_action( 'wp_ajax_addBet', 'test_ajax_addBet' );
//add_action( 'wp_ajax_nopriv_addBet', 'test_ajax_addBet' );

add_action( 'wp_ajax_setBet', 'test_ajax_setBet' );
add_action( 'wp_ajax_nopriv_setBet', 'test_ajax_setBet' );
// TODO: разные функции на nopriv & обычную

function cleanValue($value, $type) {
	if ( $type == 'string' ) {
		$value = trim($value);
		$value = stripslashes($value);
		$value = strip_tags($value);
		$value = htmlspecialchars($value);
	} elseif ( $type == 'int' ) {
		$value = (int)$value;
	}

	return $value;
}
function test_ajax_addBet() {
	$id_user = cleanValue( $_POST['id_user'], 'int' );
	$type_bet = cleanValue( $_POST['type_bet'], 'int' );
	$title = cleanValue( $_POST['title'], 'string' );
	$content = cleanValue( $_POST['content'], 'string' );

	$data = [
		'post_type' => 'bets',
		'post_status' => 'publish',
		'post_title' => $title,
		'post_content' => $content
	];
	$termName = get_term( $type_bet );

	if (mb_strlen($title) < 4 || mb_strlen($content) < 4) {
		wp_send_json_error();
	}
	if ((null !== get_term($type_bet, 'bet_type')) && (0 !== $id_user)) {
		$id_post = wp_insert_post($data);
		wp_set_object_terms( $id_post, $termName->name, 'bet_type' );
		wp_send_json_success();
	} else {
		wp_send_json_error();
	}
}

function test_ajax_setBet() {

	$bet_id = (int)$_POST['bet_id'];
	$post = get_post($bet_id);
	if(null !== $post) {
		$data = [
			'ID' => $bet_id,
			'post_content' => 'new_content',
		];
		wp_update_post( $data );
		// здесь не совсем понятно, каким будет значение ключа так как не указано, что форма д. показываться только авторизованным посетителям
		// следовательно, передаем не id юзера, а что тогда. Поставил пока что id поста, но это ближе к заглушке
		add_post_meta($bet_id, '_bet_vote', $bet_id) or update_post_meta($bet_id, '_bet_vote', $bet_id);
		wp_send_json_success();
	} else {
		wp_send_json_error();
	}
}