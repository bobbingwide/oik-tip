<?php
/*
Plugin Name: oik-tip
Plugin URI: https://www.oik-plugins.com/oik-plugins/oik-tip
Description: ZIP a WordPress theme for release
Version: 0.1.0
Author: bobbingwide
Author URI: https://bobbingwide.com/about-bobbing-wide
Text Domain: oik-tip
Domain Path: /languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

    Copyright 2012-2021 Bobbing Wide (email : herb@bobbingwide.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/

/*
function phpzip() {

   if ( $cd ) {
     $zip = new ZipArchive();
     $opened = $zip->open( $filename, ZIPARCHIVE::CREATE);
     if ( $opened ) {
       add_files( $zip );
     } else { 
     
         exit("cannot open <$filename>\n");
     }
     echo "numfiles: " . $zip->numFiles . "\n";
echo "status:" . $zip->status . "\n";
     
     $zip->close();
}     

function add_files( $zip ) {
  //$zip->addfile( "readme.txt"

}
*/

/**
 * Prompt to check if the process should be continued
 *
 * This routine does not make any decisions.
 * If you want to stop you just press Ctrl-Break.
 *
 */
if ( !function_exists( 'docontinue' ) ) { 
function docontinue( $plugin="Press Ctrl-Break to halt" ) {
	echo PHP_EOL;
	echo "Continue? $plugin ";
	$stdin = fopen( "php://stdin", "r" );
	$response = fgets( $stdin );
	$response = trim( $response );
	fclose( $stdin );
	return( $response );
}
}

/**
 * Set the current directory to $newdir within $locate
 */ 
function setcd( $locate, $newdir ) {
  $cd = getcwd();
  $cd = str_replace( '\\', "/", $cd );
  $cds = explode( '/', $cd );
  print_r( $cds );
  print_r( $locate );
  
  $i = 0;   
  while ( $cds[$i] <> $locate && ( $i < count( $cds)) ) {
    echo $cds[$i] . PHP_EOL;
    chdir( $cds[$i] . '/' );
    $i++;
  }  
  chdir( $locate );
  chdir( $newdir );
  echo getcwd();
} 


function cd2themes() {
  setcd( "wp-content", "themes" );
}   

/**
 * Create a .zip file package for a theme
 * 
 * Note: This is not yet dependent upon oikwp.
 * 
 */
 
if ( !isset( $argc ) ) {
	$argc = $_SERVER['argc'];
	$argv = $_SERVER['argv'];
	//echo $argv[1];
} 
 if ( $argc < 2 ) {
   echo "Syntax: php oik-wp.php oik-tip.php theme version branch" ;
   echo PHP_EOL;
   echo "e.g. tip olc120815c v1.0 master";
   echo PHP_EOL;
 } else {
   //$phpfile = $argv[0];
   //echo $phpfile;
   //echo PHP_EOL;
   $theme = $argv[1];
   $version = $argv[2];
	 $branch = bw_array_get( $argv, 3, 'master'); // alternative 'main'
   $filename = "$theme $version.zip";
   echo "Creating $filename";
   echo "for branch:" . $branch;
   echo PHP_EOL;
   
   //$sd = chdir( "\apache\htdocs\wordpress\wp-content\themes" );
   //echo $sd;
   cd2themes();
   echo PHP_EOL;
   // $cd = chdir( $theme );
   dosetversion( $theme, $version );
   docontinue( "$theme $version" );
	 
   doreadmemd( $theme, $branch );
   do7zip( $theme, $filename );
   domovetouploadsthemes( $filename );
   
     
   }

 

/**
 * 
 C:\apache\htdocs\wordpress\wp-content>"C:\Program Files (x86)\7-Zip\7z.exe"

7-Zip 4.65  Copyright (c) 1999-2009 Igor Pavlov  2009-02-03

Usage: 7z <command> [<switches>...] <archive_name> [<file_names>...]
       [<@listfiles...>]

<Commands>
  a: Add files to archive
  b: Benchmark
  d: Delete files from archive
  e: Extract files from archive (without using directory names)
  l: List contents of archive
  t: Test integrity of archive
  u: Update files to archive
  x: eXtract files with full paths
<Switches>
  -ai[r[-|0]]{@listfile|!wildcard}: Include archives
  -ax[r[-|0]]{@listfile|!wildcard}: eXclude archives
  -bd: Disable percentage indicator
  -i[r[-|0]]{@listfile|!wildcard}: Include filenames
  -m{Parameters}: set compression Method
  -o{Directory}: set Output directory
  -p{Password}: set Password
  -r[-|0]: Recurse subdirectories
  -scs{UTF-8 | WIN | DOS}: set charset for list files
  -sfx[{name}]: Create SFX archive
  -si[{name}]: read data from stdin
  -slt: show technical information for l (List) command
  -so: write data to stdout
  -ssc[-]: set sensitive case mode
  -ssw: compress shared files
  -t{Type}: Set type of archive
  -v{Size}[b|k|m|g]: Create volumes
  -u[-][p#][q#][r#][x#][y#][z#][!newArchiveName]: Update options
  -w[{path}]: assign Work directory. Empty path means a temporary directory
  -x[r[-|0]]]{@listfile|!wildcard}: eXclude filenames
  -y: assume Yes on all queries
  
Code Meaning 
0 No error 
1 Warning (Non fatal error(s)). For example, one or more files were locked by some other application, so they were not compressed. 
2 Fatal error 
7 Command line error 
8 Not enough memory for operation 
255 User stopped the process 

  
*/

function do7zip( $theme, $filename ) {

  $cmd = '"C:\\Program Files (x86)\\7-Zip\\7z.exe"';
  $cmd = '"C:\\Program Files\\7-Zip\\7z.exe"';
  $cmd .= " a "; 
  $cmd .= do7zip_exclusions();
  $cmd .= ' "';
  $cmd .= $theme;
  $cmd .= '.zip" ';
  $cmd .= $theme;
  $output = array();
  $return_var = null;
  echo $cmd;
  echo PHP_EOL;
  $lastline = exec( $cmd, $output, $return_var );
  echo $return_var;
  print_r( $output );
  if ( file_exists( $filename ) ) { 
    unlink( $filename );
  }
  $renamed = rename( "${theme}.zip", $filename );

}

function dosetversion( $theme, $version ) {
  echo "Set the version to $version" . PHP_EOL;
  $cmd = "vs ${theme}\\readme.txt ${theme}\\style.css" ; 
  $output = array();
  $return_var = null;
  echo $cmd;
  echo PHP_EOL;
  $lastline = exec( $cmd, $output, $return_var );
  echo $return_var;
  print_r( $output );

}



/**
 * Create the readme.md file from the readme.txt file
 *
 * Use t2m ( php txt2md.php ) to convert the readme.txt file into a README.md file
 * The README.md file is used in GitHub
 * 
 * @param string $plugin   
 */ 
function doreadmemd( $theme, $branch ) {
  $cwd = getcwd();
  echo __FUNCTION__ . $cwd;
  echo PHP_EOL;
  setcd( "wp-content", "themes/$theme" );
  docontinue( "in theme dir" );
  $return_var = null;
  $cmd = "t2m $branch > README.md";
  echo $cmd;
  $lastline = exec( $cmd, $output, $return_var );
  echo $return_var;
  //setcd( "wp-content", "plugins" );
  cd2themes();
}

/**
 *  Moves the file to uploads/plugins
 *
 */
function domovetouploadsthemes( $filename ) {
	rename( $filename, "C:/apache/htdocs/uploads/themes/" . $filename );
}

/**
 * Return the list of exclusions
 *
 * Don't process these files.
 * $cmd .= " -xr!flh0grep.* -xr!.git* -xr!.idea* -xr!screenshot*";
 *
 * Note: We're in Windows but the forward slashes don't need to be backslashes.
 * What's important is that you don't have *'s when specifying directories.
 *
 * ` -xr!flh0grep.* -xr!.git* -xr!.idea* -xr!working/ -xr!assets\\ ...`
 *
 *  $cmd .= " -xr!flh0grep.*  ";
//$cmd .= " -xr!custom.css  ";
$cmd .= " -xr!.git* -xr!.idea* -xr!working/*";
 *
 * @return string files to exclude from the archive
 */
function do7zip_exclusions() {
	$exclusions = array( null, "flh0grep.*", ".git*", ".idea*", "working/", "assets\*-banner-772x250.jpg", "assets\*-icon-256x256.jpg" );
	$exclusions[] = "phpunit.*";
	$exclusions[] = "tests/";
	$exclusions[] = "node_modules/";
	$exclusions[] = "cache";
	$exclusions[] = "cache_v2";
	$exclusions[] = 'assets/';
	$exclusions = implode( " -xr!", $exclusions );
	return( $exclusions );
}
