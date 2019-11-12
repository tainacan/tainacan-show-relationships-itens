<?php
/*
Plugin Name: Exibir itens relacionados
Description: Adiciona a lista de obras relacionadas aos itens das outras coleções
Author: Tainacan / Tainancan.org
Version: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
require_once ('theme-options.php');

add_action('tainacan-interface-single-item-after-metadata', function() {
	global $TAINACAN_BASE_URL;
	$main_collection_id = get_theme_option('main_collection_id');
	if (!is_numeric($main_collection_id)) {
		return;
	}

	$meta_repo = \Tainacan\Repositories\Metadata::get_instance();
	$current_item = tainacan_get_item();
	$col_id = $current_item->get_collection_id();
	
	$r = $meta_repo->fetch_one([
		'metadata_type' => 'Tainacan\Metadata_Types\Relationship',
		'collection_id' => $main_collection_id,
		'post_status' => array('publish', 'private'),
		'meta_query' => [
			[
				'key' => 'metadata_type_options',
				'value' => $col_id,
				'compare' => 'LIKE'
			]
		]
	], 'OBJECT');
	
	if (!$r) {
		return;
	}
	
	$meta_id = $r->get_id();
	$meta_col_id = $r->get_collection_id();
	
	$meta_query_url = 'metaquery[0][key]=' . $meta_id . '&metaquery[0][value][0]=' . $current_item->get_id() . '&metaquery[0][compare]=IN';
	
	$search_url = admin_url('admin.php?page=tainacan_admin') . '#/collections/'.$meta_col_id.'/items?' . $meta_query_url;
	
	$link = trailingslashit( get_permalink( (int) $meta_col_id) ) . '#/?' . $meta_query_url;
	
	// checa se tem algum item 
	$itens_repo = \Tainacan\Repositories\Items::get_instance();
	
	$have_items = $itens_repo->fetch([
		'posts_per_page' => 1,
		'meta_query' => [
			[
				'key' => $meta_id,
				'value' => $current_item->get_id()
			]
		]
	]);
	
	if ( ! $have_items->found_posts) {
		return;
	}

	$show_name = get_theme_option('show_name') == 'no' ? 'false' : 'true';
	$layout = get_theme_option('layout') == 'list' ?  'list' : 'grid';
	$show_search_bar = get_theme_option('show_search_bar') == 'no' ?  'false' : 'true';
	
	?>
	<div class="border-bottom border-jelly-bean tainacan-title-page" style="border-width: 2px !important; margin-top: 30px;">
		<ul class="list-inline mb-1">
			<li class="list-inline-item text-midnight-blue font-weight-bold title-page">
				itens relacionados
			</li>
			
			<li class="list-inline-item float-right title-back">
				<a href="<?php echo $link; ?>">Ver todas</a>
			</li>
		</ul>
	</div>
	
	<div class="wp-block-tainacan-dynamic-items-list"
		id="wp-block-tainacan-dynamic-items-list_addtmp65"
		search-url="<?php echo $search_url; ?>"
		collection-id="<?php echo $meta_col_id; ?>"
		show-image="true"
		show-name="<?php echo $show_name;?>"
		show-search-bar="<?php echo $show_search_bar;?>"
		show-collection-header="0"
		layout="<?php echo $layout;?>"
		collection-background-color=""
		collection-text-color=""
		grid-margin=""
		max-items-number="12"
		order="asc"
		tainacan-api-root=<?php echo home_url('wp-json/tainacan/v2'); ?>
		tainacan-base-url="<?php echo $TAINACAN_BASE_URL; ?>"
		></div>
	<?php
});
