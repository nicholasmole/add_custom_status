<?php
 /**
 * Plugin Name: Add Custom Status
 * Description: Create and edit Custom Status for posts
 * Version: 0.1.0
 * Author: Nick Mole
 * Text Domain: acs_add_custom_status
 */


require_once plugin_dir_path(__FILE__) . 'src/AddCustomStatus.php';


use Mole\ACS;
use Mole\ACS\AddCustomStatus;


new AddCustomStatus();

?>