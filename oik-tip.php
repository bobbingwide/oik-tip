<?php // (C) Copyright Bobbing Wide 2012-2014

/**
 * @package tip
 * Based on zip.php this creates a ZIP file for a theme
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

function docontinue( $theme ) {
    echo "Continue? $theme ";
    $stdin = fopen( "php://stdin", "r" );
    $response = fgets( $stdin );
    fclose( $stdin );
    return( $response );
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
 */
 if ( $argc < 2 ) {
   echo "Syntax: php tip.php theme version" ;
   echo PHP_EOL;
   echo "e.g. php tip.php olc120815c v1.0"; 
   echo PHP_EOL;
 } else {
   //$phpfile = $argv[0];
   //echo $phpfile;
   //echo PHP_EOL;
   $theme = $argv[1];
   $version = $argv[2];
   $filename = "$theme $version.zip";
   echo "creating $filename";
   echo PHP_EOL;
   
   //$sd = chdir( "\apache\htdocs\wordpress\wp-content\themes" );
   //echo $sd;
   cd2themes();
   echo PHP_EOL;
   // $cd = chdir( $theme );
   dosetversion( $theme, $version );
   docontinue( "$theme $version" );
	 
   doreadmemd( $theme );
   do7zip( $theme, $filename );
   
     
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
  $cmd .= " a "; 
  $cmd .= " -xr!flh0grep.* -xr!custom.css  ";
	
  $cmd .= " -xr!.git* -xr!.idea* -xr!working/*";
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
function doreadmemd( $theme ) {
  $cwd = getcwd();
  echo __FUNCTION__ . $cwd;
  echo PHP_EOL;
  setcd( "wp-content", "themes/$theme" );
  docontinue( "in theme dir" );
  $return_var = null;
  $cmd = "t2m > README.md";
  echo $cmd;
  $lastline = exec( $cmd, $output, $return_var );
  echo $return_var;
  //setcd( "wp-content", "plugins" );
  cd2themes();
}
 
   
 
   
   
 
