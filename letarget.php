<?php
/*
* @wordpress-plugin
* Plugin name:			LeTarget
* Plugin URI:		
* Description:      	Location of the company name to the left or to the right of the title of the posts.
* Version:				1.0
* Author:				Alex
* Author URI:       
* GitHub Plugin URI: 	https://github.com/GaryJones/move-floating-social-bar-in-genesis
* GitHub Branch:     	master
*/

register_activation_hook( __FILE__, 'letarget_activation' );

add_action( 'admin_menu', 'wplt_add_admin_menu' );
add_action( 'admin_init', 'wplt_settings_init' );


function letarget_activation() {
    do_action( 'letarget_default_options' );
}
 

function letarget_default_values() {
    
    $default = [

    	'wplt_settings_activated' => 0,
    	'wplt_settings_side' => 'left',

    ];

    if ( !get_option('wplt_settings' ) )
        update_option( 'wplt_settings', $default );



}
add_action( 'letarget_default_options', 'letarget_default_values' );


register_deactivation_hook( __FILE__, 'letarget_deactivation' );
function letarget_deactivation() {
     delete_option("wplt_settings");
}


function wplt_add_admin_menu() { 

	add_submenu_page( 'options-general.php', 'LeTarget', 'LeTarget', 'manage_options', 'letarget', 'wplt_options_page' );

}


function wplt_settings_init() { 

	register_setting( 'pluginPage', 'wplt_settings' );

	add_settings_section(
		'wplt_pluginPage_section', 
		__( 'Your section description', 'wordpress' ), 
		'wplt_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'wplt_settings_activated', 
		__( 'Активувати функціонал?', 'wordpress' ), 
		'wplt_checkbox_activated_render', 
		'pluginPage', 
		'wplt_pluginPage_section' 
	);

	add_settings_field( 
		'wplt_settings_side', 
		__( 'Сторона виводу', 'wordpress' ), 
		'wplt_radio_side_render', 
		'pluginPage', 
		'wplt_pluginPage_section' 
	);

}
function wplt_checkbox_activated_render() { 

	$options = get_option( 'wplt_settings' );
	$cb_value = "";
	if ( isset($options['wplt_settings_activated']) )
		$cb_value = $options['wplt_settings_activated'];
	?>
	<input type='checkbox' name='wplt_settings[wplt_settings_activated]' <?php checked( $cb_value, 1 ); ?> value='1'>
	<?php

}


function wplt_radio_side_render() { 

	$options = get_option( 'wplt_settings' );
	$rb_value = "";
	if ( isset($options['wplt_settings_side'] ) )
		$rb_value = $options['wplt_settings_side'];	
	?>
	<input type='radio' name='wplt_settings[wplt_settings_side]' <?php checked( $rb_value, "left" ); ?> value='left'> Зліва <br/>
	<input type='radio' name='wplt_settings[wplt_settings_side]' <?php checked( $rb_value, "right" ); ?> value='right'>Справа
	<?php

}

 
function wplt_settings_section_callback() { 

	echo __( 'This section description', 'wordpress' );

}


function wplt_options_page() { 

	?>
	<form action='options.php' method='post'>

		<h2>LeTarget</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		$options = get_option( 'wplt_settings' );
		submit_button();
		?>

	</form>
	<?php

}

add_filter( 'the_title', 'add_text_to_page_title' );

function add_text_to_page_title( $title ) {

	$options = get_option( 'wplt_settings' );

	$cb_value = "";
	if ( isset ($options['wplt_settings_activated'] ) )
		$cb_value = $options['wplt_settings_activated'];

	if ( $cb_value != 1 )
		return $title;

	$rb_value = "";
	if ( isset ($options['wplt_settings_side'] ) )
		$rb_value = $options['wplt_settings_side'];
 
 	if ( is_single() ) {
		if ($rb_value == 'left')
			$title = 'LeTarget '. $title;
		else
			$title = $title . ' LeTarget';
		

	}

	return $title;
}

?>