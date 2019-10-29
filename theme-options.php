<?php

function get_tncrelationshipsitems_default_options() {
	// Coloquei aqui o nome e o valor padrão de cada opção que você criar
	return array(
		'main_collection_id' => '',
		'show_name' => 'no',
		'layout' => 'list',
		'show_search_bar' => 'yes'
	);
}

function tncrelationshipsitems_options_menu() {
	$topLevelMenuLabel = 'Opções do Museu';
	$page_title = 'Opções';
	$menu_title = 'Opções';
	
	/* Top level menu */
	add_submenu_page('tncrelationshipsitems_options', $page_title, $menu_title, 'edit_others_posts', 'tncrelationshipsitems_options', 'tncrelationshipsitems_options_page_callback_function');
	add_menu_page($topLevelMenuLabel, $topLevelMenuLabel, 'edit_others_posts', 'tncrelationshipsitems_options', 'tncrelationshipsitems_options_page_callback_function');
}

function tncrelationshipsitems_options_validate_callback_function($input) {
	// Se necessário, faça aqui alguma validação ao salvar seu formulário
	return $input;
}

function tncrelationshipsitems_options_page_callback_function() {
	// Crie o formulário. Abaixo você vai ver exemplos de campos de texto, textarea e checkbox. Crie quantos você quiser
	$dropdown_args = array(
		'post_type'        => 'tainacan-collection',
		'selected'         => get_theme_option('main_collection_id'),
		'name'             => 'tncrelationshipsitems_options[main_collection_id]',
		'post_status'      => array('publish', 'private')
	);
	?>
	<div class="wrap span-20">
		<h2>Opções do Museu para itens relacionados</h2>

		<form action="options.php" method="post" class="clear prepend-top">
			<?php settings_fields('tncrelationshipsitems_options_options'); ?>
			<?php $options = wp_parse_args( get_option('tncrelationshipsitems_options'), get_tncrelationshipsitems_default_options() );?>

			<div class="span-20 ">
				<h3>Itens Relacionados</h3>
				<div class="span-6 last">
					<p>Informe qual é a coleção de obras. Nas páginas dos itens de outras coleções, como autores, aparecerão a lista de obras relacionadas</p>
					<label><strong>Coleção principal</strong></label><br/>
					<?php wp_dropdown_pages($dropdown_args); ?>
				</div>

				<h3>Exibir nome</h3>
				<select name="tncrelationshipsitems_options[show_name]">
					<option value="yes" <?php  echo get_theme_option('show_name') == 'yes' ? 'selected' : '' ?>>Sim</option>
					<option value="no" <?php  echo get_theme_option('show_name') == 'no' ? 'selected' : '' ?>>Não</option>
				</select>

				<h3>Exibir como</h3>
				<select name="tncrelationshipsitems_options[layout]">
					<option value="list" <?php  echo get_theme_option('layout') == 'list' ? 'selected' : '' ?>>Lista</option>
					<option value="grid" <?php  echo get_theme_option('layout') == 'grid' ? 'selected' : '' ?>>Grade</option>
				</select>

				<h3>Exibir barra de busca</h3>
				<select name="tncrelationshipsitems_options[show_search_bar]">
					<option value="yes" <?php  echo get_theme_option('show_search_bar') == 'yes' ? 'selected' : '' ?>>Sim</option>
					<option value="no" <?php  echo get_theme_option('show_search_bar') == 'no' ? 'selected' : '' ?>>Não</option>
				</select>

			</div>

			<p class="textright clear prepend-top">
				<input type="submit" class="button-primary" value="Salvar" />
			</p>
		</form>
	</div>
	<?php
}

function tncrelationshipsitems_options_show_name() {
	?><?php
}

function get_theme_option($option_name) {
		$option = wp_parse_args(
			get_option('tncrelationshipsitems_options'),
			get_tncrelationshipsitems_default_options()
		);
		return isset($option[$option_name]) ? $option[$option_name] : false;
}

add_action('admin_init', 'tncrelationshipsitems_options_init');
add_action('admin_menu', 'tncrelationshipsitems_options_menu');

function tncrelationshipsitems_options_init() {
		register_setting('tncrelationshipsitems_options_options', 'tncrelationshipsitems_options', 'tncrelationshipsitems_options_validate_callback_function');
}
