<?php
/*

Copyright (C) 2008  lbcd78

____________________________________________________________

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/ 


function lbcd78_live_twit_menu()
{   if (function_exists('add_submenu_page')) {
        $location = "../wp-content/plugins/ltt/";
        add_options_page('Live Twit', 'Live Twit', 2,__FILE__,'llt_optionsMenu');
    }

}

add_action('admin_menu', 'lbcd78_live_twit_menu');


function llt_optionsMenu () {

	if ($_POST) {

		if ($_POST['action'] == 'update') {

			update_option('llt', $_POST );

		} 
	}

	if (!$settings = get_option('llt')) {
#0.2 correction lmkg_defaultSettings replace by llt_defaultSettings
		$settings = llt_defaultSettings();
	}	
		if ( ((integer)@$settings[ "llt_rotation_speed" ]<=0) || ((integer)@$settings[ "llt_rotation_speed" ]>8) )
			$settings[ "llt_rotation_speed" ] = 4;

	
	?>

	<div class="wrap">
	<h2>lbcd78 Live Twit</h2>
	<div  style="float:right;width:250px;background:#eee;padding:10px;font-size:0.9em;">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	If you find this plugin helpful, please consider donating a few dollars to support this plugin. Thanks!
	<br /><br />
	<input name="cmd" value="_xclick" type="hidden">
	<input name="business" value="ludovic@lbcd78.fr" type="hidden">
	<input name="item_name" value="lbcd78" type="hidden">
	<input name="no_shipping" value="1" type="hidden">
	<input name="no_note" value="1" type="hidden">
	<input name="currency_code" value="EUR" type="hidden">
	<input name="tax" value="0" type="hidden">
	<input name="bn" value="PP-DonationsBF" type="hidden">
	<input src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" style="border-width: 0px;" name="submit" alt="faire un don" type="image"><img alt="" src="https://www.paypal.com/it_IT/i/scr/pixel.gif" border="0" height="1" width="1"></form>

	</form>
	</div>
	<fieldset class="options">
	<legend>LLT Settings</legend>
	<form method="post">
	<input type="hidden" name="action" value="update" />
	<table class="optiontable">
	<tr valign="top"> 
	<th scope="row">RSS:</th> 
	<td><input type="text" name="llt_rss" size="80" value="<?php echo @$settings['llt_rss']; ?>" /><br />(url)</td> 
	</tr>
	<?php
	$ind = 0;
	#2007/08/28 bug fixed if 
	if ( is_array( $settings ))
	foreach( $settings as $key => $hook ) { 
		if ( trim($hook) !== ""  )
		{
			if ( ereg(  "llt_rss[0-9]+", $key ) ) 
			{
	?>

		<tr valign="top"> 
			<th scope="row">RSS :<br />Nr. <?php echo( $ind ); ?></th> 
			<td>
				<input type="text" name='llt_rss<?php echo( $ind ); ?>' size="80" value="<?php 
echo(  @$settings[ $key ] ); ?>" /> 
			</td> 
		</tr>
		<?php
			$ind++;
			}
			
		}
		
	}
	
?>
		<tr valign="top"> 
			<th scope="row">RSS :<br />Nr. <?php echo( $ind ); ?></th> 
			<td>
				<input type="text" name='llt_rss<?php echo( $ind ); ?>' size="80" value="" />
			</td>
		</tr>
		<tr>
			<th  scope="row">RSS Rotation Speed <br />Betwen 1(fast) and 8(slow)
			</th>
			<td><input type="text" name="llt_rotation_speed" value="<?php echo ( $settings[ "llt_rotation_speed" ] );?>" />
			</td>
		</tr>
		
	</table>
	<p><label><input type="checkbox" name="llt_show_date" value="O" <?php if ( $settings[ "llt_show_date" ] ) echo ( 'checked="checked"' );?> /> Show date</label></p>
	<p><label><input type="checkbox" name="llt_rss_icon" value="1" <?php if ( $settings[ "llt_rss_icon" ] ) echo ( 'checked="checked"' );?> /> Add RSS Icon</label></p>
	<p><label><input type="checkbox" name="llt_dl_icon" value="1" <?php if ( $settings[ "llt_dl_icon" ] ) echo ( 'checked="checked"' );?> /> Add Download Icon</label></p>
	
	<p class="submit"><input type="submit" name="Submit" value="Update Settings &raquo;" />
	</form>
	</filedset>	
	</div>

	<?php

# 0.3 : result in live in your admin panel
llt_header();
llt();
	
	
}

/**
 * Returns default settings for the plugin.
 */

function llt_defaultSettings() {
# 0.2 correction lmkg_accuray replace by llt_rss, rss url changed 
# 0.3 add llt_rss_icon, llt_dl_icon, llt_rotation_speed
	$settings = array		(	
					'llt_rss' => 'http://www.lbcd78.fr/seed/',
					'llt_rss_icon' => 1,
					'llt_rotation_speed' => 4,
					'llt_dl_icon' => 1
					
						);
	return $settings;
}



?>