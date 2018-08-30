<?php
/*
  Plugin Name: Bets
 */

// определяем, mu плагин или нет
// только нужно еще load.php положить в mu-plugins require WPMU_PLUGIN_DIR.'/test-bets/index.php'; чтобы подтянуть папку с плагином, в которым положить перевод

$dir = dirname(__DIR__);
$num = strripos( $dir, DIRECTORY_SEPARATOR );
$newDir = substr( $dir, $num + 1 );

if ($newDir == 'plugins') {
	register_activation_hook(__FILE__, 'test_addRoleBets');
	load_plugin_textdomain('test-bets', false, basename( dirname( __FILE__ ) ) . '/languages' );
} elseif ($newDir == 'mu-plugins') {
	add_action( 'plugins_loaded', 'myplugin_muload_textdomain' );
}

add_action( 'init', 'test_postBets' );
register_deactivation_hook(__FILE__, 'test_deactiveBets');
register_uninstall_hook(__FILE__, 'test_removeRoleBets');
add_action('init', 'role_set');

function role_set (){
	global $wp_roles;

	$role = get_role( 'administrator' );
	$role->add_cap( 'publish_bets' );
	$role->add_cap( 'edit_bet' );
	$role->add_cap( 'edit_bets' );
	$role->add_cap( 'edit_others_bets' );
	$role->add_cap( 'delete_bets' );
	$role->add_cap( 'delete_bet' );
	$role->add_cap( 'delete_others_bets' );
	$role->add_cap( 'delete_private_bets' );
	$role->add_cap( 'delete_published_bets' );
	$role->add_cap( 'read_bet' );
	$role->add_cap( 'read_private_bets' );
	$role->add_cap( 'manage_bet' );
	$role->add_cap( 'edit_published_bets' );

}


function myplugin_muload_textdomain() {
	load_muplugin_textdomain( 'test-bets', basename( dirname(__FILE__) ) . '/languages' );
}

function test_addRoleBets(){
	if (null === get_role('capper')) {
		$capabilitiesCapper = [
			'read' => true,
		    'publish_bets' => true,
			'edit_bets' => true,
			'edit_bet' => true,
		];

		add_role('capper', __('Каппер','test-bets'), $capabilitiesCapper);
	}

	if (null === get_role('moderator')) {
		$capabilitiesModerator = [
			'read' => true,
			'publish_bets' => true,
			'edit_bets' => true,
			'edit_bet' => true,
			'edit_others_bets' => true,
			// Модератор "- с правами добавлять/редактировать любые ставки в типе " про удаление ничего - поставил в коммент
//			'delete_other_bets' => true,
//			'delete_bet' => true,
//			'delete_bets' => true,
		// ...private, published etc

		];
		add_role('moderator', __('Модератор', 'test-bets'), $capabilitiesModerator);
	}

}

function test_deactiveBets(){
	if (null !== get_role('capper')) {
		remove_role('capper');
	}

	if (null !== get_role('moderator')) {
		remove_role('moderator');
	}
}

function test_removeRoleBets(){
	if (null !== get_role('capper')) {
		remove_role('capper');
	}

	if (null !== get_role('moderator')) {
		remove_role('moderator');
	}
}

function test_postBets(){
	register_post_type('bets', [
		'labels' => [
			'name'               => __('Ставки', 'test-bets'),
			'singular_name'      => __('Ставка', 'test-bets'),
			'add_new'            => __('Добавить новую', 'test-bets'),
			'add_new_item'       => __('Добавление ставки', 'test-bets'),
			'edit_item'          => __('Редактирование ставки', 'test-bets'),
			'new_item'           => __('Новая ставка', 'test-bets'),
			'view_item'          => __('Смотреть ставку', 'test-bets'),
			'search_items'       => __('Искать ставки', 'test-bets'),
			'not_found'          => __('Не найдено', 'test-bets'),
			'not_found_in_trash' => __('Не найдено в корзине', 'test-bets'),
			'parent_item_colon'  => __('', 'test-bets'),
			'menu_name'          => __('Ставки', 'test-bets'),
		],
		'public'              => true,
		'show_in_nav_menus'   => true,
		'query_var'           => true,
		'rewrite'             => true,
		'show_ui'             => true,
		'taxonomies'          => [],
		'menu_position'       => 25,
		'menu_icon'           => 'dashicons-format-quote',
		'hierarchical'        => false,
		'supports'            => array('title', 'editor', 'thumbnail', 'custom-fields'),
		'has_archive'         => true,
		'capability_type'     => ['bet', 'bets'],
		'map_meta_cap'        => true,
	]);
	if (!taxonomy_exists('bet_type')) {
		register_taxonomy( 'bet_type', ['bets'], [
			'labels' => [
				'name'               => __('Тип ставки', 'test-bets'),
				'singular_name'      => __('Тип ставки', 'test-bets'),
				'add_new'            => __('Добавить новый тип', 'test-bets'),
				'add_new_item'       => __('Добавление типа', 'test-bets'),
				'edit_item'          => __('Редактирование типа', 'test-bets'),
				'new_item'           => __('Новый тип', 'test-bets'),
				'view_item'          => __('Смотреть тип', 'test-bets'),
				'search_items'       => __('Искать типы ставки', 'test-bets'),
				'not_found'          => __('Не найдено', 'test-bets'),
				'not_found_in_trash' => __('Не найдено в корзине', 'test-bets'),
				'parent_item_colon'  => __('', 'test-bets'),
				'menu_name'          => __('Тип ставки', 'test-bets'),
			],
			'description'       => __('Описание типа ставки', 'test-bets'),
			'public'            => true,
			'hierarchical'      => false
		] );
	}

	if (false === get_term_by( 'name', 'Ординар', 'bet_type' )) {
		wp_insert_term(__('Ординар', 'test-bets'), 'bet_type',[
			'description' => __('some description of ordinar', 'test-bets')
		]);
	}
	if (false === get_term_by( 'name', 'Экспресс', 'bet_type' )) {
		wp_insert_term(__('Экспресс', 'test-bets'), 'bet_type',[
			'description' => __('some description of express', 'test-bets')
		]);
	}

	if ( ! taxonomy_exists( 'bet_status' ) ) {


		register_taxonomy( 'bet_status', ['bets'], [
			'labels' => [
				'name'               => __('Статус ставки', 'test-bets'),
				'singular_name'      => __('Статус ставки', 'test-bets'),
				'add_new'            => __('Добавить новый статус', 'test-bets'),
				'add_new_item'       => __('Добавление статуса', 'test-bets'),
				'edit_item'          => __('Редактирование статуса', 'test-bets'),
				'new_item'           => __('Новый статус', 'test-bets'),
				'view_item'          => __('Смотреть статус', 'test-bets'),
				'search_items'       => __('Искать типы статусов', 'test-bets'),
				'not_found'          => __('Не найдено', 'test-bets'),
				'not_found_in_trash' => __('Не найдено в корзине', 'test-bets'),
				'parent_item_colon'  => __('', 'test-bets'),
				'menu_name'          => __('Статус ставки', 'test-bets'),
			],
			'description'       => __('Описание статуса ставки', 'test-bets'),
			'public'            => true,
			'hierarchical'      => false,


		] );
	}

	if (false === get_term_by( 'name', 'Выигрыш', 'bet_status' )) {
		wp_insert_term(__('Выигрыш', 'test-bets'), 'bet_status',[
			'description' =>  __('some description of win', 'test-bets')
		]);
	}
	if (false === get_term_by( 'name', 'Проигрыш', 'bet_status' )) {
		wp_insert_term(__('Проигрыш', 'test-bets'), 'bet_status',[
			'description' =>  __('some description of loose', 'test-bets')
		]);
	}

	if (false === get_term_by( 'name', 'Возврат', 'bet_status' )) {
		wp_insert_term(__('Возврат', 'test-bets'), 'bet_status',[
			'description' =>  __('some description of return_back', 'test-bets')
		]);
	}

	if (false === get_term_by( 'name', 'Активная', 'bet_status' )) {
		wp_insert_term(__('Активная', 'test-bets'), 'bet_status',[
			'description' =>  __('some description of active', 'test-bets')
		]);
	}
}
