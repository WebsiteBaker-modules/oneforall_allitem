<?php

/*
  Snippet developed for the Open Source Content Management System Website Baker (http://websitebaker.org)
  Copyright (C) 2015, Christoph Marti

  LICENCE TERMS:
  This snippet is free software. You can redistribute it and/or modify it 
  under the terms of the GNU General Public License  - version 2 or later, 
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  DISCLAIMER:
  This snippet is distributed in the hope that it will be useful, 
  but WITHOUT ANY WARRANTY; without even the implied warranty of 
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
  GNU General Public License for more details.


  -----------------------------------------------------------------------------------------
   Code snippet OneForAll ListAllItems for Website Baker v2.6.x
  -----------------------------------------------------------------------------------------

*/



// Function to display a list of all OneForAll items (invoke function from template or code page)
if (!function_exists('oneforall_allitems')) {
	function oneforall_allitems($mod_name, $max_items = null) {



		// MAKE YOUR MODIFICATIONS TO THE LAYOUT OF THE ITEMS DISPLAYED
		// ************************************************************

		// Use this html for the layout
		$setting_header = '
		<div id="mod_oneforall_allitems_wrapper">
		<ul>';

		$setting_item_loop = '
		<li><a href="[LINK]" title="[TITLE]" target="_blank">[TITLE]</a></li>';
	
		$setting_footer = '
		</ul>
		</div>';
		// end layout html




		// DO NOT CHANGE ANYTHING BEYOND THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING
		// **************************************************************************

		global $wb, $database;

		// Check if module exits
		$mod_exists = $database->get_one("SELECT module FROM ".TABLE_PREFIX."sections WHERE module = '$mod_name'");

		if (empty($mod_exists)) {
			echo 'ERROR: No pages make use of module &quot;'.$mod_name.'&quot;. Please check the function arguments.';
		}
		else {

			// The HTML
			$html = $setting_header;

			// Limit number of items
			$limit_sql = '';
			if ($max_items) {
				$limit_sql = " LIMIT $max_items";
			}

			// Query items
			$query_items = $database->query("SELECT page_id, title, link FROM ".TABLE_PREFIX."mod_".$mod_name."_items WHERE active = '1' AND title != '' ORDER BY position ASC".$limit_sql);

			// Loop through all items of this module
			if ($query_items->numRows() > 0) {
				while ($item = $query_items->fetchRow()) {
					$page_id = stripslashes($item['page_id']);
					$title   = htmlspecialchars(stripslashes($item['title']));
					// Work-out the item link
					$item_link = WB_URL.PAGES_DIRECTORY.get_page_link($page_id).$item['link'].PAGE_EXTENSION;

					// Replace placeholders by values
					// Make array of placeholders
					$placeholders = array('[TITLE]', '[LINK]');
					// Make array of values
					$values = array($title, $item_link);
					// HTML of item loop
					$html .= str_replace($placeholders, $values, $setting_item_loop);
				}
			}

			// Add item to the HTML
			$html .= $setting_footer;

			// Output HTML code
			echo $html;
		}
	}
}

?>
