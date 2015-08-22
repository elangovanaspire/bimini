<?php
/*
Plugin Name: Clean Up Images
Version: 1.04
Plugin URI: http://www.delwinvriend.com/clean-up-images/
Author: Delwin Vriend
Author URI: http://www.delwinvriend.com/
Description: This plugin allows you to delete all unused images. It looks for all images not referred to by any post, page or pod within WordPress

Released under the GPL license http://www.gnu.org/licenses/gpl.txt
*/

/* 	Based on plugins DUI AND DNUI
 *	DNUI: http://www.nicearma.com/delete-not-used-image-wordpress-dnui/ by: Nicearma, Author URI: http://www.nicearma.com/Description: This plugin will delete all not used images file, the plugin search all image, not referred by any post and page of wordpress
 *	DUI: http://www.bobhobby.com/2008/02/24/delete-unused-image-files-plugin-for-wordpress/
*/

function get_text_domain() {
	return "cui";
}

//	Check in the database if the image is used in post_content
function CUI_checkImageDB($ImageName) {
	global $wpdb, $table_prefix;
	$sql = "SELECT COUNT(*) FROM " . $table_prefix . "posts INNER JOIN " . $table_prefix . "postmeta ON " . $table_prefix . "posts.id=" . $table_prefix . "postmeta.post_id" . " WHERE post_content LIKE '%/" . $ImageName . "%' OR meta_value LIKE '%/" . $ImageName . "%'";
	$result = $wpdb->get_results($sql, "ARRAY_A");
	$count = $result[0]['COUNT(*)'];
	if ($count == 0) {
		// check OPTIONS table
		$sql = "SELECT COUNT(*) FROM " . $table_prefix . "options	WHERE option_value LIKE '%/" . $ImageName . "%'";
		$result = $wpdb->get_results($sql, "ARRAY_A");
		$count = $result[0]['COUNT(*)'];
	}
	if ($count == 0) {
		// check PODS images
		$sql = "SELECT COUNT(*) FROM " . $table_prefix . "postmeta INNER JOIN " . $table_prefix . "posts ON " . $table_prefix . "posts.id=" . $table_prefix . "postmeta.meta_value WHERE guid LIKE '%/" . $ImageName . "%' AND meta_key NOT LIKE '[_]%'";
		$result = $wpdb->get_results($sql, "ARRAY_A");
		$count = $result[0]['COUNT(*)'];
	}
	return $count > 0;
}

function CUI_getSQL($fields) {
	global $table_prefix;
	return 'SELECT ' . $fields . ' FROM ' . $table_prefix . 'posts INNER JOIN ' . $table_prefix . "postmeta ON " . $table_prefix . "posts.id=" . $table_prefix . "postmeta.post_id " .
				 "WHERE post_type='attachment' AND " . $table_prefix . "posts.post_mime_type LIKE	'image%' AND " . $table_prefix . "postmeta.meta_key='_wp_attachment_metadata' ";
}

// Check all image  in database having the conditions:
// Type of post 'attachment'
// Mime type = 'image'
// return all the sizes
function CUI_getImages($i, $max, $order=0) {
	global $wpdb, $table_prefix;

	$sql = CUI_getSQL("id, meta_id, meta_value");
	$sql .= " ORDER BY " . $table_prefix . "postmeta.meta_id";
	if ($order == 0) {
		$sql .= ' ASC';
	} else {
		$sql .= ' DESC';
	}
	$sql .= ' LIMIT ' . ($i * $max) . ", $max";
	$results = $wpdb->get_results($sql, "ARRAY_A");
	if (count($results) == 0 && $i > 0) {
		// probably a page refresh done on a no-more-results (empty) page resulting from a Next click
		$_SESSION['CUI']['i'] = 0;
		$results = CUI_getImages(0, $max, $order);
	}
	return $results;
}

function CUI_countImages() {
	global $wpdb;
	$results = $wpdb->get_results(CUI_getSQL("COUNT(id)"), "ARRAY_A");
	return $results[0]['COUNT(id)'];
}

//Make update of wp_postmeta with a vector sizes serialized the one image
function CUI_updateImages($value, $id) {
	global $wpdb, $table_prefix;
	$value = str_replace("'", "''", $value);
	$sql = 'update ' . $table_prefix . "postmeta set meta_value='" . ($value) . "' where meta_id='$id'";
	return $wpdb->query($sql);
}

/*
 * Use for see all the image in the databe
* $i = Point for the query
* $max = limit for the database query, this is because if you have a big site and you not have this, your site will overload
* $used = Number of image used
* $notused = Number of image used
*/

function CUI_allImage($i, $max=25) {
	//Get all the image from the data base
	$res = CUI_getImages($i, $max, $_SESSION['CUI']['order']);
	$used = 0;
	$notused = 0;
	$dir_array = explode("/", WP_CONTENT_DIR);
	$content_dir = array_pop($dir_array);
	$upload_dir = wp_upload_dir();
	$upload_dir = $upload_dir['baseurl'];
	$upload_dir = preg_replace('/.*?\/'.$content_dir.'(.*)$/', $content_dir . '$1', $upload_dir);
	if (!empty($res)) {
		$parent_image_count = 0;
		foreach ($res as $key => $unser) {
			//the result of meta_value is serialized
			$seri = unserialize($unser['meta_value']);
			//get the id of the attachment image
			$id = $unser['id'];
			//get the original image
			$name = $seri['file'];
			//save the original vector of meta_value, for use after in delete_image
			$original[$id] = $seri;
			$files[$id]['parent'][] = $name;
			$files[$id]['meta_id'][] = $unser['meta_id'];
			//check the name of one single file, if the parent is used
			if (CUI_checkImageDB($upload_dir . "/" . $name)) {
				$files[$id]['parent']['used'] = true;
				$used++;
			} else {
				$files[$id]['parent']['used'] = false;
				$notused++;
			}
			$parent_image_count++;
			//if the original file have some copy of the different sizes
			if (!empty($seri['sizes'])) {
				//$keyu represent the name of the copy in the database Example 'Large'
				foreach ($seri['sizes'] as $keyu => $sizes) {
					//the name in the server from this copy
					$name = $sizes['file'];
					$files[$id]['child'][$keyu][] = $sizes['file'];
					if (CUI_checkImageDB($name)) {
						$used++;
						$files[$id]['child'][$keyu]['used'] = true;
						// change parent image to used if it wasn't directly used
						$files[$id]['parent']['used'] = true;
					} else {
						$files[$id]['child'][$keyu]['used'] = false;
						$notused++;
					}
				}
			}
		}
	}
	// all the session variables are used later, but only $_SESSION['CUI']['original'] is for CUI_delete
	$_SESSION['CUI']['original'] = $original;
	$_SESSION['CUI']['parent_image_count'] = $parent_image_count;
	$_SESSION['CUI']['used'] = $used;
	$_SESSION['CUI']['notused'] = $notused;
	return $files;
}

function CUI_delete() {
	// if send to delete something
	if (!empty($_POST['im'])) {
		$im = $_POST['im']; ?>
		<table class="deleted">
			<tbody>
<?php	//verify is session have something to, if null, this plugin have problem with session
			if (!empty($_SESSION['CUI']['original'])) {
				foreach ($im as $key => $value) {
					$image_details = explode(':::', $value);
					// type = "parent" (the original file) or "thumbnail/medium/large/small/etc" (copy of the same file)
					$type = $image_details[0];
					// id of the parent (the original file)
					$id = $image_details[1];

					// only if change to another parent, is because of i used the same vector for do the work, and delete for example the copy Large
					if ($old != $id) {
						$original_db_value = $_SESSION['CUI']['original'][$id];
						$new_db_value = $original_db_value;
						$old = $id;
					}
					//if the file is a copy
					if ($type != 'parent') {
						//take the root of the file in the server
						$file = $image_details[3];
						//take only the name
						$file_arr = explode('/', $file);
						$name = array_pop($file_arr);
						//unset for example Large from the vector
						unset($new_db_value["sizes"][$type]);
						// try to update the database
						if (CUI_updateImages(serialize($new_db_value), $image_details[2]) !== false) {
							$path = explode("/", WP_CONTENT_DIR);
							array_pop($path);
							$path = implode("/", $path);
							$rel_file =  str_replace($path, "", $file);
							// try to delete the file
							if (file_exists(realpath($file)) && @unlink(realpath($file))) { ?>
								<tr><th class="status deleted" width="20"><?= __("Deleted:", get_text_domain()) ?></th><td class="name" width="20"><?= $name ?></td><td class="file" width="*"><?= $rel_file ?></td></tr>
<?php					} else if (file_exists(realpath($file))) {
								// if can't delete the file, restore the old information to the database
								CUI_updateImages(serialize($original_db_value), $image_details[2]); ?>
								<tr><th class="status permission" width="20"><?= __("Cannot delete:", get_text_domain()) ?></th><td class="name" width="20"><?= $name ?></td><td class="file" width="*"><?= $rel_file ?><p class="permission"><?= __('Please change directory premissions to <span class="permission">777</span>', get_text_domain()) ?></p></td></tr>
<?php					} else {
								// the file doesn't exist
								// maybe it was deleted with its parent
								$parent_deleted = false;
								foreach ($im as $keyc => $valuec) {
									$image_details_c = explode(':::', $valuec);
									$typec = $image_details[0];
									$idc = $image_details[1];
									$parent_deleted = ($typec == 'parent' && $idc==$id);
									if ($parent_deleted) break;
								}
								if (!$parent_deleted) { ?>
									<tr><th class="status doesnotexist" width="20"><?= __("File does not exist:", get_text_domain()) ?></th><td class="name" width="20"><?= $name ?></td><td class="file" width="*"><?= realpath($file) ?><p class="doesnotexist"><?= __("The database has been updated to reflect this fact.", get_text_domain()) ?></p></td></tr>
<?php						} else { ?>
									<tr><th class="status deleted" width="20"><?= __("Deleted with parent:", get_text_domain()) ?></th><td class="name" width="20"><?= $name ?></td><td class="file" width="*"><?= $rel_file ?></td></tr>
<?php						}
							}
						}
					} else {
						$file = $image_details[2];
						$file_arr = explode('/', $file);
						$name = array_pop($file_arr);
						wp_delete_attachment($id); ?>
						<tr><th class="status deleted" width="20"><?= __("Deleted:", get_text_domain()) ?></th><td class="name" colspan="2" width="*"><?= $name ?> <i><?= __("(including any of its child/thumbnail images)", get_text_domain()) ?></i></td></tr>
<?php			}
				}
			} else {?>
				<tr><th class="status error" width="20"><?= __("Session error", get_text_domain()) ?></th><td class="name" colspan="2" width="*"><p><?= __("Please ensure you have PHP's session handling set up correctly, especially the directory and its permissions.", get_text_domain()) ?></p><p><?= __("No images have been deleted", get_text_domain()) ?></p></td></tr>
<?php
			} ?>
			</tbody>
		</table>
<?php
	}
}

function CUI_logic() {
	if (!empty($_POST['scan']) || empty($_SESSION['CUI']['i'])) {
		$i = 0;
	} else {
		$i = $_SESSION['CUI']['i'];
	}

	if (!empty($_POST['delete'])) {
		if (!current_user_can('manage_options')) {
			die("You do not have sufficient permissions to delete images using this page");
		}
		CUI_delete();
	} elseif (!empty($_POST['next'])) {
		$i++;
	} elseif (!empty($_POST['prev'])) {
		if ($i > 0) {
			$i--;
		} else {
			$i = 0;
		}
	}
	$_SESSION['CUI']['i'] = $i;

	$image = CUI_allImage($i, $_SESSION['CUI']['query']);
	// getting images may have reset the page number
	$i = $_SESSION['CUI']['i'];
	$count = CUI_countImages();
	$hasMore = (($count - (($i+1) * $_SESSION['CUI']['query'])) >= 1);
	$uploads = wp_upload_dir();
	$newdir = $uploads['basedir'];
	$urldir = $uploads['baseurl'];

	$page = $i + 1;
	$page_count = intval(($count + $_SESSION['CUI']['query'] - 1) / $_SESSION['CUI']['query']);
	$which_page = ($page_count == 1) ? "all images" : (($page == 1) ? "first page ($page/$page_count)" : (($page == $page_count) ? "last page ($page/$page_count)" : "page $page/$page_count"));
	?>
	<div class="report">
		<h3>A total of <?= $count ?> images found in the database</h3>
		<span><?= __("(plus possible thumbnail/child images WordPress created for each image)", get_text_domain()) ?></span><br />
		<h3 style="display: inline-block; clear: left;">Showing <?= $which_page ?>:</h3>
		<span class="used"><?= $_SESSION["CUI"]["used"]  ?></span> used
		<span class="unused"><?= $_SESSION["CUI"]["notused"]  ?></span> unused
	</div>
	<div class="image_list">
<?php
	if (!empty($image)) {
		$parent_count = 0;
		foreach ($image as $key => $file) {
			if (!empty($file["parent"])) {
				$parent_count++;
			}
		}
		$at_least_one_child = false; ?>
		<?= CUI_nextPrevButtons($hasMore) ?>
		<ul class="image">
<?php	$parent_image_count = 0;
			foreach ($image as $key => $file) {
				if ($parent_image_count >= $_SESSION['CUI']['query']) {
					break;
				}
				$delAll = true;
				$html = "";
				$html2 = "";
				if (!empty($file["parent"])) {
					$html = '<li class="parent">';
					$parent_image_count++;
					$parent = explode("/", $file["parent"][0]);
					$name = array_pop($parent);
					$folder = implode("/", $parent);
					$html.= '<input type="checkbox" class="parent select" ';
					if ($file["parent"]['used']) {
						$delAll = false;
					}
					if (!empty($file["child"])) {
						$at_least_one_child = true;
						$meta_id = $file["meta_id"][0];
						$html2 = '<ul class="child">';
						$count = 0;
						foreach ($file["child"] as $keyu => $child) {
							$count++;
							$html2.= '<li><input style="display: inline;" type="checkbox" class="child select" ';
							if ($child['used']) {
								// this is extraneous, the parent is already marked as used
								$delAll = false;
								$html2.= 'disabled="disabled" ';
							}
							$html2.= 'name="im[]" id="child_'.$key.'_'.$count.'" value="' . "$keyu:::$key:::$meta_id:::$newdir/$child[0]" . '" /> <label for="child_' . $key . '_' . $count . '" data-url="' . $urldir . "/$folder/$child[0]" . '">' . $folder . "/" . $child[0] . '</label></li>';
						}
						$html2.= '</ul>';
					}
					if (!$delAll){
						$html.= 'disabled="disabled" ';
					}
					$html.= 'name="im[]" id="'.$key.'" onchange="if (jQuery(this).is(\':checked\')) { jQuery(\'input[id^=child_'.$key.'_]\').prop(\'checked\', true); } jQuery(\'input[id^=child_'.$key.'_]\').prop(\'disabled\', jQuery(this).is(\':checked\'));" value="parent:::' . $key . ':::' . $newdir . "/" . $file["parent"][0] . '"/> <label data-url="' . $urldir . '/' . $file["parent"][0] . '" for="'. $key .'"' . (!$delAll ? ' class="disabled"' : '') . ' onmouseover="previewImage(this);" onmouseout="clearPreview(this);">' . $file["parent"][0] . '</label> <a href="' . $urldir . "/" . $file["parent"][0] . '" target="_blank">' . __("View full size", get_text_domain()) . '</a>' . $html2 . '</li>';
				}
				echo $html;
			} ?>
			<li class="parent"><br/><input type="checkbox" id="selectall" onchange="jQuery('input[type=checkbox].parent.select:not(:disabled)').prop('checked', jQuery(this).is(':checked')).trigger('change')" /> <label for="selectall" style="font-weight: bold;"><?= __("Select/Deselect all unused parent images", get_text_domain()) ?></label>
<?php	if ($at_least_one_child) { ?>
				<ul class="child">
					<li>
						<input type="checkbox" id="selectallchild" onchange="jQuery('input[type=checkbox].child.select:not(:disabled)').prop('checked', jQuery(this).is(':checked')).trigger('change')" /> <label for="selectallchild" style="font-weight: bold;"><?= __("Select/Deselect all unused child images", get_text_domain()) ?></label>
					</li>
				</ul>
<?php } ?>
			</li>
		</ul>
		<?= CUI_nextPrevButtons($hasMore); ?>
	</div>
	<div style="clear: both;"></div>
<?php
	}
}

function CUI_nextPrevButtons($hasMore=true) { ?>
		<div>
			<input type="submit" name="prev" value="<?= __("Prev", get_text_domain()) ?>" <?php if ($_SESSION['CUI']['i'] == 0) echo ' disabled="disabled"'; ?> class="button-primary" style="float: left;" />
			<input type="submit" name="next" value="<?= __("Next", get_text_domain()) ?>" <?php if ((($_SESSION["CUI"]["used"] + $_SESSION["CUI"]["notused"]) == 0) || (!$hasMore)) echo ' disabled="disabled"'; ?> class="button-primary" style="float: left;" />
			<div style="float: right; margin-left: 185px;"><input type="submit" name="delete" value="<?= __("Delete Selected", get_text_domain()) ?>" class="button-primary"/></div>
			<div style="clear: both;"></div>
		</div>
<?php
}

// main option -----------
function CUI_options() {
	$_SESSION['CUI']['query'] = 20;
	$_SESSION['CUI']['order'] = 0;
	$_SESSION['CUI']['bg-color'] = "999";
	$doScan = !empty($_POST['scan']) || !empty($_POST['prev']) || !empty($_POST['next']) || !empty($_POST['delete']);
	if ((!empty($_POST['query'])) && (is_numeric($_POST['query']))) {
		$_SESSION['CUI']['query'] = $_POST['query'];
	}
	if(!empty ($_POST['order'])&&is_numeric($_POST['order'])) {
		$_SESSION['CUI']['order'] =$_POST['order'];
	}
	if(!empty ($_POST['bg-color'])) {
		$_SESSION['CUI']['bg-color'] =$_POST['bg-color'];
	} ?>
	<link type="text/css" href="<?= plugin_dir_url(__FILE__) ?>cui.css" rel="stylesheet" />
	<style>
		div#img_preview img { background-color: #<?= $_SESSION['CUI']['bg-color'] ?>; }
	</style>
	<div class="wrap">
		<div id="img_preview"><img src="<?= plugin_dir_url(__FILE__) ?>loading.gif" /></div>
		<?php screen_icon('themes'); ?> <h2><?= __("Cleanup Images in Media Library", get_text_domain()) ?></h2>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<table class="query">
				<tbody>
					<tr>
						<th width="20"><label for="query_size"><?= __("Images per page:", get_text_domain()) ?></label></th>
						<td width="*">
							<input id="query_size" type="number" name="query" value="<?php echo $_SESSION['CUI']['query'] ?>" max="200" min="1" />
							<?= __("(plus their child/thumbnail images)", get_text_domain()) ?>
						</td>
					</tr>
					<tr>
						<th width="20"><label for="query_order"><?= __("Order by:", get_text_domain()) ?></label></th>
						<td width="*">
							<select name="order" id="query_order">
								<option <?php if($_SESSION['CUI']['order']==0) echo "selected" ?> value="0"><?= __("Date Added ascending (first added first)", get_text_domain()) ?></option>
								<option <?php if($_SESSION['CUI']['order']==1) echo "selected" ?> value="1"><?= __("Date Added descending (last added first)", get_text_domain()) ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th></th>
						<td><input type="submit" name="scan" value="<?= __("Scan for Unused Images in Media Library", get_text_domain()) ?>" class="button-primary" /></td>
					</tr>
					<tr>
						<th><?= __("Preview background color:", get_text_domain()) ?> #</th>
						<td><input type="text" class="hex" name="bg-color" value="<?= $_SESSION['CUI']['bg-color'] ?>" maxlength="7" oninput="if (jQuery(this).val().startsWith('#')) { jQuery(this).val(jQuery(this).val().substring(1)); } var colorVal = '#' + jQuery(this).val(); if (colorVal.length == 4 || colorVal.length == 7) { jQuery('div#img_preview img').css('background-color', colorVal); }" /></td>
					</tr>
					</tbody>
			</table>
			<?php if ($doScan) CUI_logic(); ?>
		</form>
	</div>
	<script type="text/javascript">
		if (typeof String.prototype.startsWith != 'function') {
		  // see below for better implementation!
		  String.prototype.startsWith = function (str){
		    return this.indexOf(str) == 0;
		  };
		}

		jQuery(document).ready(function() {
			jQuery("li label:not([for^=child]):not([for^=select])").mousemove(function(e) {
				if (jQuery('#img_preview img').attr('src') !== jQuery(this).data('url')) {
					jQuery('#img_preview img').attr('src', '<?= plugin_dir_url(__FILE__) ?>loading.gif');
					jQuery('#img_preview img').attr('src', jQuery(this).data('url'));
				}
				var x = (e.pageX - jQuery("#adminmenuwrap").width());
				var y = (e.pageY - jQuery("#wpadminbar").height() + 30);
				jQuery('#img_preview').css("left", x + "px").css("top", y + "px");
			});
		});

		function previewImage(label) {
			if (jQuery('#img_preview img').attr('src') !== jQuery(label).data('url')) {
			  jQuery('#img_preview img').attr('src', '<?= plugin_dir_url(__FILE__) ?>loading.gif');
				jQuery('#img_preview img').attr('src', jQuery(label).data('url'));
			}
		  jQuery('#img_preview').css("left", "0");
		}

		function clearPreview(label) {
		  jQuery('#img_preview').css("left", "-50000px");
		  jQuery('#img_preview img').attr('src', '<?= plugin_dir_url(__FILE__) ?>clear.gif');
		}
	</script>
<?php
}

function add_CUI_option_menu() {
	add_options_page('Cleanup Images in Media Library', 'Cleanup Images', 'manage_options', basename(__FILE__), 'CUI_options');
}
add_action('init', 'session_start');
add_action('admin_menu', 'add_CUI_option_menu');
?>