<?php
/*
Plugin Name:  G Auto-Hyperlink
Description: Plugin that automatically converts a text/keyword in a post or page into an anchor text.
Version: 1.0
Author: Godwinh D. Lopez
*/

add_action('admin_menu', 'g_auto_hyperlink_init');
add_action('init', 'g_auto_hyperlink_stylesheet');
add_action('init', 'g_auto_hyperlink_scripts');

add_filter('the_content', 'search_and_replace');

register_activation_hook(__FILE__,'g_auto_hyperlink_install');
register_uninstall_hook(__FILE__, 'g_auto_hyperlink_uninstall');

function g_auto_hyperlink_install() {
	global $wpdb;

	$table = $wpdb->prefix . "gautohyperlink";
	
	$sql = "CREATE TABLE IF NOT EXISTS " . $table . " (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			keyword varchar(50) NOT NULL,
			url varchar(150) NOT NULL,
			title varchar(60) NOT NULL,
			rel varchar(10) NOT NULL,
			target varchar(10) NOT NULL,
			visits bigint(20) NOT NULL,
			appearance varchar(20) NOT NULL,
			appearance_val varchar(255) NOT NULL,
			PRIMARY KEY (id) )";
			
	$wpdb->query($sql);
	
	
}

function g_auto_hyperlink_uninstall() {
	global $wpdb;
	
	$table = $wpdb->prefix . "gautohyperlink";
	
	$sql = "DROP TABLE IF EXISTS " . $table;
	
	$wpdb->query($sql);
}

function g_auto_hyperlink_init() {
	add_menu_page('G Auto-Hyperlink', 'Auto-Hyperlink', 'administrator', 'g-auto-hyperlink', 'g_auto_hyperlink', plugins_url('/images/glink.png', __FILE__));
	add_submenu_page('g-auto-hyperlink', 'Add New', 'Add New', 'administrator', 'g-auto-hyperlink-add', 'g_auto_hyperlink_add');
	add_submenu_page(null, 'Edit', 'Edit', 'administrator', 'g-auto-hyperlink-edit', 'g_auto_hyperlink_edit');
}

function g_auto_hyperlink() {
	global $wpdb;
	
	$table = $wpdb->prefix . 'gautohyperlink';
	
	if(isset($_GET['action'])){
		if($_GET['action']=='delete'){
			$id = $_GET['id'];
			if($wpdb->query("DELETE FROM $table WHERE id=$id")){
				$delete_action_msg = '<div class="action-successful">Entry Deleted Successfully!</div>';
			}else{
				$delete_action_msg = '<div class="action-failed">Failed to Delete Entry. Please try again.</div>';
			}
		}
	}
	
	$delete_img = plugins_url('/images/delete.png', __FILE__);
	$edit_img = plugins_url('images/edit.png', __FILE__);
	
	$html = file_get_contents(plugins_url('/tpl/overview.tpl', __FILE__));
	
	$links_table = '<table border="0" cellspacing="1" cellpadding="1" class="links-table">';
	$links_table.= '<tr>';
	$links_table.= '<th width="13%">Keyword</th>';
	$links_table.= '<th width="28%">URL</th>';
	$links_table.= '<th width="13%">Title</th>';
	$links_table.= '<th width="7%">Rel</th>';
	$links_table.= '<th width="7%">Target</th>';
	$links_table.= '<th width="6%">Visits</th>';
	$links_table.= '<th width="19%">Keyword that appears on</th>';
	$links_table.= '<th width="7%">Action</th>';
	$links_table.= '</tr>';
	
	$entries = get_added_entries();
	
	foreach($entries as $entry){
		$appearance = $entry->appearance;
		switch($appearance){
			case 'spec_cat':
					$category = get_cat_name($entry->appearance_val);
					
					$appearance_val = 'post(s) under category:<br/><em>' . $category . '</em>';
				break;
		
			case 'spec_post':
					$appearance_val = 'on post:<br/>' . $entry->appearance_val . '</em>';
				break;
				
			case 'spec_page':
					$appearance_val = 'on page:<br/><em>' . $entry->appearance_val . '</em>';
				break;
				
			case 'all_post':
					$appearance_val = 'on <em>All Posts</em>';
				break;
			
			case 'all_page':
					$appearance_val = 'on <em>All Pages</em>';
				break;
			
			case 'all_post_and_page':
					$appearance_val = 'on <em>All Posts and All Pages</em>';
				break;
		}
	
		$links_table.= '<tr>';
		$links_table.= '<td>'.$entry->keyword.'</td>';
		$links_table.= '<td>'.$entry->url.'</td>';
		$links_table.= '<td>'.$entry->title.'</td>';
		$links_table.= '<td>'.$entry->rel.'</td>';
		$links_table.= '<td>'.$entry->target.'</td>';
		$links_table.= '<td>'.$entry->visits.'</td>';
		$links_table.= '<td>'.$appearance_val.'</td>';
		$links_table.= '<td><a href="admin.php?page=g-auto-hyperlink-edit&id='.$entry->id.'"><img src="'.$edit_img.'" border="0" alt="Edit" title="Edit" /></a> &nbsp;';
		$links_table.= '<a href="admin.php?page=g-auto-hyperlink&id='.$entry->id.'&action=delete" onclick="return confirm(\'Are you sure in deleting this entry?\');"><img src="'.$delete_img.'" border="0" alt="Delete" title="Delete" /></a></td>';
		$links_table.= '</tr>';
	}
	
	$links_table.= '</table>';
	
	$html = str_replace('{{links-list}}', $links_table, $html);
	
	
	echo $html;
}

function g_auto_hyperlink_add() {
	if($_POST){
		global $wpdb;
		
		$table 			= $wpdb->prefix . "gautohyperlink";
		$keyword 		= $_POST['keyword'];
		$url			= $_POST['url'];
		$title			= $_POST['title'];
		$rel			= $_POST['rel'];
		$target			= $_POST['target'];
		$appearance		= $_POST['appearance'];
		
		switch($appearance){
			case 'spec_cat':
						$appearance_val = $_POST['spec_cat'];
				break;
				
			case 'spec_post':
						$appearance_val = $_POST['spec_post_or_page'];
				break;
				
			case 'spec_page':
						$appearance_val = $_POST['spec_post_or_page'];
				break;
			
			default:
						$appearance_val = '';
				break;
		}
		
		if($wpdb->insert($table, 
					array('keyword'=>$keyword, 'url'=>$url, 'title'=>$title, 'rel'=>$rel, 'target'=>$target, 'appearance'=>$appearance, 'appearance_val'=>$appearance_val),
					array('%s', '%s', '%s', '%s', '%s', '%s', '%s'))){
			$uri = 'admin.php?page=g-auto-hyperlink-add&add=success';
		}else{
			$uri = 'admin.php?page=g-auto-hyperlink-add&add=failed';
		}
		js_redirect($uri);
	}
	
	if(isset($_GET['add'])){
		switch($_GET['add']){
			case 'success':
					$add_action_msg = '<div class="action-successful">Entry Successfully Added</div>';
				break;
			
			case 'failed':
					$add_action_msg = '<div class="action-failed">Error encountered while adding the entry. Please try again!</div>';
				break;
		}
	}
	
	
	$categories = get_categories_html();
	$html = file_get_contents(plugins_url('/tpl/add.tpl', __FILE__));
	
	$html = str_replace('{{action-msg}}', $add_action_msg, $html);
	$html = str_replace('{{category-opt}}', $categories, $html);
	
	echo $html;
}

function g_auto_hyperlink_edit(){
	global $wpdb;
	
	$table = $wpdb->prefix . 'gautohyperlink';
	
	if($_POST){
		$post_id				= $_POST['entryid'];
		$post_keyword 			= $_POST['keyword'];
		$post_url				= $_POST['url'];
		$post_title				= $_POST['title'];
		$post_rel				= $_POST['rel'];
		$post_target			= $_POST['target'];
		$post_appearance		= $_POST['appearance'];
		
		switch($post_appearance){
			case 'spec_cat':
						$post_appearance_val = $_POST['spec_cat'];
				break;
				
			case 'spec_post':
						$post_appearance_val = $_POST['spec_post_or_page'];
				break;
				
			case 'spec_page':
						$post_appearance_val = $_POST['spec_post_or_page'];
				break;
			
			default:
						$post_appearance_val = '';
				break;
		}
			
		if($wpdb->update($table, 
					array('keyword'=>$post_keyword, 'url'=>$post_url, 'title'=>$post_title, 'rel'=>$post_rel, 'target'=>$post_target, 'appearance'=>$post_appearance, 'appearance_val'=>$post_appearance_val),
					array('id'=>$post_id),
					array('%s', '%s', '%s', '%s', '%s', '%s', '%s'),
					array('%d'))){
			$uri = 'admin.php?page=g-auto-hyperlink-edit&id='.$post_id.'&add=success';
		}else{
			$uri = 'admin.php?page=g-auto-hyperlink-edit&id='.$post_id.'&add=failed';
		}
		js_redirect($uri);
	}
	
	if(isset($_GET['add'])){
		switch($_GET['add']){
			case 'success':
					$edit_action_msg = '<div class="action-successful">Entry Successfully Updated</div>';
				break;
			
			case 'failed':
					$edit_action_msg = '<div class="action-failed">Failed to Update Entry. Please try again!</div>';
				break;
		}
	}
	
	$id = $_GET['id'];
	$result = $wpdb->get_row("SELECT * FROM $table WHERE id = $id");
	
	$rel = '<option value=""></option>';
	$rel_options = array('external', 'nofollow');
	foreach($rel_options as $option){
		if($result->rel == $option){
			$rel .= '<option selected="selected" value="'.$option.'">'.$option.'</option>';
		}else {
			$rel .= '<option value="'.$option.'">'.$option.'</optiion>';
		}
	}
	
	$target = '<option value=""></option>';
	$target_options = array('self'=>'_self', 'blank'=>'_blank', 'top'=>'_top', 'parent'=>'_parent');
	foreach($target_options as $key => $value){
		if($result->target == $key){
			$target .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
		}else {
			$target .= '<option value="'.$key.'">'.$value.'</optiion>';
		}
	}
	
	$appearance = '<select name="appearance" id="appearance">';
	$appearance.= '<option value="0">Please Choose</option>';
	$appearance_option = array('spec_cat'=>'Specific Category', 'spec_post'=>'Specific Post', 'spec_page'=>'Specific Page', 'all_post'=>'All Posts', 'all_page'=>'All Pages', 'all_post_and_page'=>'All Posts and Pages');
	foreach($appearance_option as $key=>$value){
		if($result->appearance == $key){
			$appearance .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
		}else{
			$appearance .= '<option value="'.$key.'">'.$value.'</option>';
		}
	}
	$appearance.='</select>';
	
	$categories = get_categories_html($result->appearance=='spec_cat', $result->appearance_val);
	$spec_field = get_spec_field_html($result->appearance=='spec_post' || $result->appearance=='spec_page', $result->appearance_val);
	
	$appearance.= $categories;
	$appearance.= $spec_field;
	
	$html = file_get_contents(plugins_url('/tpl/edit.tpl', __FILE__));
	$html = str_replace('{{action-msg}}', $edit_action_msg, $html);
	$html = str_replace('{{ID}}', $result->id, $html);
	$html = str_replace('{{keyword}}', $result->keyword, $html);
	$html = str_replace('{{url}}', $result->url, $html);
	$html = str_replace('{{title}}', $result->title, $html);
	$html = str_replace('{{rel}}', $rel, $html);
	$html = str_replace('{{target}}', $target, $html);
	$html = str_replace('{{appearance}}', $appearance, $html);
	echo $html;
}

function search_and_replace($content = ''){
	global $post;
	
	/*
	Priority:
		* Specific Post
		* Specific Page
		* Specific Category
		* All Posts
		* All Pages
		* All Posts and Pages
	*/
	
	$permalink = get_permalink($post->ID);
	
	if($post->post_type == 'page' && !is_home()){
		$has_spec_page = has_entries_for('spec_page', $permalink);
		$has_all_page = has_entries_for('all_page');
	}else if($post->post_type == 'post' || is_home()){
		$category = get_the_category($post->ID);
		$has_spec_cat = has_entries_for('spec_cat', $category[0]->cat_ID);
		$has_spec_post = has_entries_for('spec_post', $permalink);
		$has_all_post = has_entries_for('all_post');
	}
	
	
	if($has_spec_post) {
		$type = 'spec_post'; 
	}
	else if($has_spec_page) { 
		$type = 'spec_page'; 
	}
	else if($has_spec_cat) {
		$type = 'spec_cat'; 
	}
	else if($has_all_post){
		$type = 'all_post';
	}
	else if($has_all_page){
		$type = 'all_page';
	}
	else {
		$type = 'all_post_and_page';
	}
	
	if($type == 'spec_cat'){
		$entries = get_entries_for($type, $category[0]->cat_ID);
	}
	if($type == 'spec_post' || $type == 'spec_page'){
		$entries = get_entries_for($type, $permalink);
	}
	if($type == 'all_page' || $type == 'all_post' || $type == 'all_post_and_page'){
		$entries = get_entries_for($type);
	}
	
	$rand_index = rand(0, count($entries) - 1);
	$entry = $entries[$rand_index];	
	
	$replaced_result = exec_search_replace($content, $entry->keyword, $entry->url, $entry->title, $entry->rel, $entry->target);
	
	if($replaced_result['count'] > 0){
		update_visit($entry->id);
		return $replaced_result['html'];
	}else{
		return $content;
	}
}

function exec_search_replace($content, $keyword, $link, $title, $rel, $target){
	require_once('lib/simple_html_dom.php');
	$html = str_get_html($content);
	$exclude = array('a');
	$counter = 0;
	$keyword = trim($keyword);
	$link = 'http://' . check_http_prefix($link);
	$target = strlen($target) > 0 ? 'target="_'.$target.'"' : '';
	$rel = strlen($rel) > 0 ? 'rel="'.$rel.'"' : '';
	$done = false;

	if(!empty($keyword)){
		while(!$done){
			$replacement = 0;
			foreach($html->find('text') as $element){
				if(!in_array($element->parent()->tag, $exclude) && $counter < 2){
					if(preg_match("/\b({$keyword})\b/i", $element->innertext, $match)){
						$anchor_text = '<a href="'.$link.'" title="'.$title.'" '.$rel.' '.$target.'>'.$match[0].'</a>';

						if($element->innertext = preg_replace("/\b({$keyword})\b/i", $anchor_text, $element->innertext, 1)){
							$counter++;
							$replacement++;
						}
					}
				}
				if($counter >= 2){
					$done = true;
					break;
				}
			}
			
			$tmp = (string) $html;
			$html = str_get_html($tmp);
			
			if($replacement == 0)
				$done = true;
		}
	}
	
	return array('html'=>(string)$html, 'count'=>$counter);
}

function update_visit($id){
	global $wpdb;
	
	$table = $wpdb->prefix . 'gautohyperlink';
	
	$sql = "UPDATE $table SET `visits`=(`visits` + 1) WHERE `id`=$id";
	
	if($wpdb->query($sql)){
		return true;
	}
	
	return false;
}

function check_http_prefix($url){
	$url = preg_replace('/^(http:\/\/){1}/i', '', $url);
	
	return $url;
}

function has_entries_for($type, $param=''){
	global $wpdb;
	
	$table = $wpdb->prefix . 'gautohyperlink';
	
	$count = $wpdb->get_var($wpdb->prepare("SELECT count(*) FROM $table WHERE appearance=%s and appearance_val=%s",  $type, $param));
	
	if($count > 0)
		return true;
		
	return false;
}

function get_entries_for($type, $param=''){
	global $wpdb;
	$table = $wpdb->prefix . 'gautohyperlink';
	
	if($type == 'spec_cat' || $type == 'spec_post' || $type == 'spec_page'){
		$sql = $wpdb->prepare("SELECT * FROM $table WHERE appearance=%s and appearance_val=%s", $type, $param);
	}else if($type == 'all_post' || $type == 'all_page' || $type == 'all_post_and_page'){
		$sql = $wpdb->prepare("SELECT * FROM $table WHERE appearance=%s", $type);
	}
	
	$entries = $wpdb->get_results($sql);
	
	return $entries;
}

function get_added_entries(){
	global $wpdb;
	
	$table = $wpdb->prefix . 'gautohyperlink';
	$sql = "SELECT * FROM $table";
	
	$entries = $wpdb->get_results($sql);
	
	return $entries;
}

function get_categories_html($display='', $cat_id='') {
	if($display==true)
		$style = 'style="display:inline;"';
		
	$categories_html = '<select name="spec_cat" id="spec_cat" '.$style.'>';
	$categories_html.= '<option value="0">Choose a category</option>';
	$categories =  get_categories();
	
	foreach($categories as $cat){
		if($cat_id==$cat->term_id){
			$categories_html .= '<option value="'.$cat->term_id.'" selected="selected">'.$cat->name.'</option>';
		}else{
			$categories_html .= '<option value="'.$cat->term_id.'">'.$cat->name.'</option>';
		}
	}
	$categories_html.= '</select>';
	return $categories_html;
}

function get_spec_field_html($display='', $value=''){
	if($display==true){
		$style= 'style="display:inline;"';
	}
	
	$html = '<input type="text" id="spec_post_or_page" name="spec_post_or_page" value="'.$value.'" placeholder="Enter URL" '.$style.' />';
	
	return $html;
}

function g_auto_hyperlink_stylesheet() {
	$myStyleUrl = WP_PLUGIN_URL . '/g-auto-hyperlink/css/style.css';
	$myStyleFile = WP_PLUGIN_DIR . '/g-auto-hyperlink/css/style.css';
	
	if(file_exists($myStyleFile)) {
		wp_register_style('GAutoHyperlinkStylesheet', $myStyleUrl);
		wp_enqueue_style('GAutoHyperlinkStylesheet');
	}
}

function g_auto_hyperlink_scripts() {
	$myScriptUrl = WP_PLUGIN_URL . '/g-auto-hyperlink/js/g_auto_hyperlink_script.js';
	$myScriptFile = WP_PLUGIN_DIR . '/g-auto-hyperlink/js/g_auto_hyperlink_script.js';
	
	if(file_exists($myScriptFile)){
		wp_register_script('GAutoHyperlinkScript', $myScriptUrl);
		wp_enqueue_script('GAutoHyperlinkScript');
	}
}

function js_redirect($URI){
	echo '<script>';
	echo 'document.location.href = "'.$URI.'";';
	echo '</script>';
}
?>