<?

/*
Plugin Name: lbcd78 Live Twiter
Plugin URI: http://www.lbcd78.fr/
Description: lLT give one  RSS on your blog

Author: lbcd78
Version: 0.4
Author URI: http://www.lbcd78.fr

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

__________________________________________________________
# 0.2 : 10/02/1008
Update in ltt-option-0.2.php

# 0.3 : 13/02/2008
-multi rss url
-tuning rotation speed
-icon rss and download could be hide
- style sheet add 

#0.4 : 11/04/2008
- freeze on mouseover
- show date time 
*/ 

$G_ltt_start = timer_stop(0,6);

# 0.3
if (is_admin())
	include_once( "llt-option-0.4.php" );
	
function llt() {

$settings = get_option('llt');	

require_once(ABSPATH . WPINC . '/rss.php');
$flux_url = @$settings[ "llt_rss" ];

$rss[0] = fetch_rss($flux_url);
$ind = 1;
foreach( $settings as $key => $value ) 
{
	if ( trim($value) !== ""  )
	{
		if ( ereg(  "llt_rss[0-9]+", $key ) ) 
		{
			$rss[] = fetch_rss( $value );
			$ind++;
		}
	}
}

$G_dir = split( "/", dirname( __FILE__ ) );

$url =  get_bloginfo('url')."/wp-content/plugins/".$G_dir[count($G_dir)-1];    # put your directory here !!!

echo ("

	<script language=\"javascript\">

function fade(id, color){ 
	
	if(color<255) { //If color is not white yet
		color+=11; // increase color darkness
		document.getElementById(id).style.color=\"rgb(\"+color+\",\"+color+\",\"+color+\")\"; 
		setTimeout(\"fade('\"+id+\"', \"+color+\")\",10); 
	}
	else color=0; //reset colorTitre value
}

mouseover=0;
function flux(i, nb) {
if( mouseover==0)
{");

if ( @$settings[ "llt_show_date" ] )
{
	echo("	d='<span class=\"dt\">'+dtg[i]+'</span>';");
}
else
{
	echo(" d=\"\";" );
	
}
	
echo("	
	document.getElementById(\"spTheme\").innerHTML=theme[i];
	document.getElementById(\"sp\").innerHTML=d+titre[i];
	fade(\"sp\"+i, 0);
	if(i<nb-1) i++;
	else i=0;
	setTimeout(\"flux(\"+i+\",\"+nb+\")\",".( 10*@$settings[ "llt_rotation_speed" ])."00); 
}
else
{
r=nb;e=i;
}	
}

function init(nbItem){
	document.getElementById(\"sp0\").style.color=\"rgb(0,0,0)\";
	flux(0, nbItem);

}
");
if ( @$settings[ "llt_show_date" ] )
{
	echo("var dtg = new Array(); " );
}

echo("
	var theme = new Array();
	var titre = new Array();

</script>	

<div id=\"twit\">	

	<div id=\"closed\" style=\"display:none;\">
	<a href=\"javascript:void(0);\"  onclick=\"document.getElementById('opened').style.display='block'; document.getElementById('closed').style.display='none';\">
	<img src=\"$url/open.gif\"  alt=\"+\"/>ouvrir</a></div>

	<div id=\"opened\" onmouseover=\"mouseover=1;\" onmouseout=\"mouseover=0;flux(e,r);\" >");

$rss_show = 1;
$dl_show = 1;	
# 0.3 rss show or not	
if ( $settings[ "llt_rss_icon" ] ) {
	echo( "<span id=\"rss\">
		<a href=\"".$flux_url."\">
			<img src=\"$url/rss.gif\" width=\"24px\" alt=\"Rss\"/>
		</a>
	</span>" );
}

# 0.3 download icon show or not	
if ( $settings[ "llt_dl_icon" ] ) {
	echo( "<span id=\"dl\">
		<a href=\"http://www.lbcd78.fr/2008/02/01/plugin-de-diffusion-dynamique-de-flux-rss-sur-wordpress/\">
			<img src=\"$url/dl.gif\" width=\"24px\" alt=\"Download Plugin\"/>
		</a>
	</span>" );
}

	
echo( "<span id=\"close\">
		<a href=\"javascript:void(0);\"  onclick=\"document.getElementById('opened').style.display='none'; document.getElementById('closed').style.display='block';\">
			<img src=\"$url/close.gif\"  alt=\"fermer\"/>
		</a>
	</span>

	<span id=\"spTheme\"></span>
	<span id=\"sp\">
		<span id=\"sp0\"></span>
	</span>
	

	<script language=\"javascript\">" );

	
$i=0;
# 0.3 for more than one rss	
foreach ($rss as $flux) {
$items = $flux->items; 
foreach( $items as $item ) {
if ( @$settings[ "llt_show_date" ] )
{ 
	$dtg = date_parse( addslashes($item["pubdate"]) );
	echo( "dtg[$i]='".str_pad($dtg["day"],2, "0", STR_PAD_LEFT)."/".str_pad($dtg["month"],2, "0", STR_PAD_LEFT)." ".str_pad($dtg["hour"],2, "0", STR_PAD_LEFT).":".str_pad($dtg["minute"],2, "0", STR_PAD_LEFT)."';\n" );
}
		echo( "theme[$i]='<a href=\"".$item["link"]."\" class=\"theme\" id=\"spTheme$i\">".addslashes($flux->channel["title"])."</a>';\n" );
		echo( "titre[$i]='<a href=\"".$item["link"]."\"  class=\"titre\" id=\"sp$i\">".addslashes( $item["title"])."</a>';\n" );
		$i++;
	};
}


echo( "
	init(".($i-1).");

	</script>
	</div>
</div>");
}

# 0.3 : optimisation
function llt_header() {
	$G_dir = split( "/", dirname( __FILE__ ) );
	$url =  get_bloginfo('url')."/wp-content/plugins/".$G_dir[count($G_dir)-1];
	echo( "<link rel=\"stylesheet\" type=\"text/css\" href=\"$url/llt.css\" \/>" );
}

add_action( 'wp_head', 'llt_header' );

add_action( 'wp_footer', 'llt' );

$G_llt_stop = timer_stop(0,6) - $G_llt_start;
add_action( 'wp_footer', 'llt_time' );

function llt_time()
{
	global $G_llt_stop;
	echo( "<!-- llt : ".($G_llt_stop*1000)." milliseconds -->\n" );
}


?>