<?php

namespace Mole\ACS;

//use Mole\AUF\Helpers;

class AddCustomStatus {

  //Contructor
  public function __construct()
  { 
		// Register the Wordpress Post Statuses
		add_action( 'init', array($this,'create_new_archive_post_status') );
		// Add drop down to the Post Edit Page
		add_action( 'post_submitbox_misc_actions', array($this,'add_to_post_status_dropdown') );
		// (Mostly Copied) Register a column and merge it into the wp standard columns
		add_filter( 'manage_posts_columns' , array($this,'myplugin_event_modify_columns'));
		// (Mostly copied) Custom column with Status on All Post Page
		add_action( 'manage_posts_custom_column', array($this,'myplugin_event_custom_column_content') );
		// Quick Edits need a seperate connection for the select options
		add_action( 'admin_footer', array($this,'quick_edit_select_options') );

	}

	// Quick Edits need a seperate connection for the select options
	function quick_edit_select_options(){
		//var_dump ('cats');
		$customLabels = array(
			'pitch',
			'assigned',
			'in progress',
			'archive',
		);
		?>
    <script>
    jQuery(document).ready(function($){
			<?php foreach($customLabels as $_label) { ?>
				//$("select[name='_status']").css("background-color", "yellow");
				$("select[name='_status']").append("<option value=\"<?php echo $_label ?>\" <?php selected('<?php echo $_label ?>', $post->post_status); ?>><?php echo ucwords($_label) ?></option>");
			<?php } ?>
    });
    </script>
    <?php
	}

	// Register the Wordpress Post Statuses
	function create_new_archive_post_status(){
		$customLabels = array(
			'pitch',
			'assigned',
			'in progress',
			'archive',
		);
		foreach($customLabels as $_label) {
			register_post_status( $_label, array(
				'label'                     => _x( $_label, 'post' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop(ucwords( $_label).' <span class="count">(%s)</span>', ucwords($_label).' <span class="count">(%s)</span>' ),
			) );
		}
	}

	// Add drop down to the Post Edit Page
	function add_to_post_status_dropdown()
	{
		global $post;
		$label = '';
		$customLabels = array(
			'pitch',
			'assigned',
			'in progress',
			'archive',
		);

		if( $post->post_type == 'post' ){
			
			// custom post status: approved
			$complete = '';
			$label = '';   
			$testStatus = $post->post_status;
			if( ($testStatus != 'publish') && ($testStatus != 'pending') && ($testStatus != 'draft') ){
			//if( $post->post_status == 'barchive' ){
				//var_dump( 'superpostcat');
					//var_dump($customLabels[0]);
					//publish , pending , draf
					$lowerCase = strtolower($testStatus);
					// var_dump($lowerCase);
					// var_dump($testStatus);
					$complete = 'selected=\"selected\"';
				
					$label = ucfirst($testStatus);
					
					?>
					<script>
					jQuery(document).ready(function($){
							$("input#save-post").val('Save');
					});
					</script>
					<?php
			}
		}
    ?>
    <script>
    jQuery(document).ready(function($){
			<?php foreach($customLabels as $_label) { ?>
				$("select#post_status").append("<option value=\"<?php echo $_label ?>\" <?php selected('<?php echo $_label ?>', $post->post_status); ?>><?php echo ucwords($_label) ?></option>");
			<?php } ?>
			<?php foreach($customLabels as $_label) { ?>
				$("select[name='_status']").css("background-color", "yellow");
				$("select[name='_status']").append("<option value=\"<?php echo $_label ?>\" <?php selected('<?php echo $_label ?>', $post->post_status); ?>><?php echo ucwords($_label) ?></option>");
			<?php } ?>
				$("span#post-status-display").append("<?php echo ucwords($label) ?>");
    });
    </script>
    <?php
	}

	//(Mostly Copied) Register a column and merge it into the wp standard columns
	function myplugin_event_modify_columns( $columns ) {
		  
				$new_columns = array(
					'status_table' => __( 'Status', 'myplugin_textdomain' ),
				);
				
				// Combine existing columns with new columns
				$filtered_columns = array_merge( $columns, $new_columns );
			
				// Return our filtered array of columns
				return $filtered_columns;
	}

	//(Mostly copied) Custom column with Status on All Post Page
	function myplugin_event_custom_column_content( $column ) {
  
		// Get the post object for this row so we can output relevant data
		global $post;

		// Check to see if $column matches our custom column names
		switch ( $column ) {
	
			case 'status_table' :
				// Retrieve post meta
				$start = get_post_meta( $post->ID, 'status_table', true );
				
				$testStatus = $post->post_status;
				// Echo output and then include break statement
				echo ucwords($testStatus) ;
				break;
				
		}
	}

}

?>