<!--@author RonnyValdivieso <ron_251@hotmail.com>-->
<div id="mySidenav" class="sidenav">
	<div class="sidenav-top">
		<!-- <h2 class="sidenav-title">Directorio</h2>
		<a href="#" id="closeNav" class='closebtn'>×</a> -->
	</div>
	<div id="filesDisplay">
		<?php
		    $allowed = array("blend");
			echo php_file_tree("./data/" . $_[user] . "/files", "javascript:alert('You clicked on [link]');", $allowed, $_[user]);
			
			function php_file_tree($directory, $return_link, $extensions = array(), $current_user) {
				// Generates a valid XHTML list of all directories, sub-directories, and files in $directory
				// Remove trailing slash
				if( substr($directory, -1) == "/" ) $directory = substr($directory, 0, strlen($directory) - 1);
				$code .= php_file_tree_dir($directory, $return_link, $extensions, $current_user);
				return $code;
			}

			function php_file_tree_dir($directory, $return_link, $extensions = array(), $current_user, $first_call = true) {
				// Recursive function called by php_file_tree() to list directories/files
				
				// Get and sort directories/files
				if( function_exists("scandir") ) $file = scandir($directory); else $file = php4_scandir($directory);
				natcasesort($file);
				// Make directories first
				$files = $dirs = array();
				foreach($file as $this_file) {
					if( is_dir("$directory/$this_file" ) ) $dirs[] = $this_file; else $files[] = $this_file;
				}
				$file = array_merge($dirs, $files);
				
				// Filter unwanted extensions
				if( !empty($extensions) ) {
					foreach( array_keys($file) as $key ) {
						if( !is_dir("$directory/$file[$key]") ) {
							$ext = substr($file[$key], strrpos($file[$key], ".") + 1); 
							if( !in_array($ext, $extensions) ) unset($file[$key]);
						}
					}
				}
				
				if( count($file) > 2 ) { // Use 2 instead of 0 to account for . and .. "directories"
					$php_file_tree = "<ul";
					if( $first_call ) { $php_file_tree .= " class=\"php-file-tree\""; $first_call = false; }
					$php_file_tree .= ">";

					foreach( $file as $this_file ) {
						if( $this_file != "." && $this_file != ".." ) {
							$route = $directory . "/" . $this_file;
							$route = str_replace("./data/" . $current_user . "/files", "", $route);
							if( is_dir("$directory/$this_file") ) {
								// Directory
								$php_file_tree .= "<li class=\"pft-directory\"><i class='fa fa-folder' aria-hidden=\"true\"></i><a href=\"#\">" . htmlspecialchars($this_file) . "</a>";
								$php_file_tree .= php_file_tree_dir("$directory/$this_file", $return_link ,$extensions, $current_user, false);
								$php_file_tree .= "</li>";
							} else {
								// File
								// Get extension (prepend 'ext-' to prevent invalid classes from extensions that begin with numbers)
								$ext = "ext-" . substr($this_file, strrpos($this_file, ".") + 1); 
								$link = str_replace("[link]", "$directory/" . urlencode($this_file), $return_link);
								$php_file_tree .= "<li class=\"pft-file\"><i class='fa fa-file-o' aria-hidden=\"true\"></i><a class=\"file\" url=\"$route\" href=\"$link\">" . htmlspecialchars($this_file) . "</a></li>";
							}
						}
					}
					$php_file_tree .= "</ul>";
				}
				return $php_file_tree;
			}

			// For PHP4 compatibility
			function php4_scandir($dir) {
				$dh  = opendir($dir);
				while( false !== ($filename = readdir($dh)) ) {
				    $files[] = $filename;
				}
				sort($files);
				return($files);
			}
		?>
	</div>
</div>