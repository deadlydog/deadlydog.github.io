<?php 
/********************************************************************************
* Dans One File Photo Gallery v1.6												*
* Written by Daniel Schroeder, August 30, 2006									*
* Last Updated Jan 19, 2009														*
*																				*
* Project home http://www.danskingdom.com/DansOneFilePhotoGalleryHome.php		*
* Thanks to Shiegege for his thumb class which is used to create the thumbnails	*
*																				*
* PREREQUISITES:																*
*	1 - PHP 5 or greater installed on the webserver								*
*	2 - Pictures to display are of type .jpg, .png, .gif, or .wbmp				*
*																				*
* INSTALLATION:																	*
*	1 - Drop this file into the directory containing your photos, then simply	*
*		navigate to the file in your web browser. The file display any photos	*
*		found in the directory, as well as any found in its subdirectories.		*
*	2 - The GD Graphics Library must be enabled. This is a standard library		*
*		included with PHP5, but it is not enabled by default. Simply locate your*
*		php.ini file (usually in C:\PHP, C:\Program Files\PHP, or C:\Windows) 	*
*		and uncomment the line ";extension=php_gd2.dll" (So it should read 		*
*		"extension=php_gd2.dll"). If you do not host your own website you may 	*
*		have to contact your hosting provider and ask them to do this for you 	*
*		if they have not already done it. If this is not enabled then no 		*
*		pictures will show up. So if everything shows up except for the 		*
*		pictures, this is likely the problem.									*
*																				*
* OPTIONAL:																		*
*	3 - Because thumbnail creation can take a long time if there are a lot of	*
*		thumbnails to make, you may want to increase the "max_execution_time"	*
*		in the php.ini file. The default is 30 seconds, so you may want to bump	*
*		this up to 120 (2 minutes) or 300 (5 minutes), otherwise the page may	*
*		stop loading after 30 seconds and display only the pictures it was able	*
*		to convert within the time limit. This is not too big of a problem		*
*		though since the thumbnails only need to be created once, and releading	*
*		the page would cause it to start creating thumbnails from where it left	*
*		off last time.	This would normally only occur if the Pictures Per Page	*
*		option was set to 0 (all) and there are 100+ pictures in a directory.	*
*																				*
* EDITING THIS FILE:															*
*	1 - The CONFIGURATION SECTION contains the general configuration of the 	*
*		photo gallery, such	as what types of files to display, how many should 	*
*		be displayed per page, the size of the thumbnails, and other 			*
*		functionality of the photo gallery										*
*	2 - The STYLE SHEET SECTION contains the CSS style sheet used for the photo	*
*		gallery and it controls such things as the colors of the website, font	*
*		sizes, etc																*
*																				*
* CONTACT:																		*
*	If you have any problems with my Photo Gallery, notice any bugs, or just 	*
*	want to comment on it, email me at deadlydog@hotmail.com					*
*																				*
* CHANGE LOG:																	*
*	v1.6	- Fixed problem with directory paths having spaces in them when		*
*				$THUMBNAILS_DIRECTORIES_CREATED_IN_PICTURE_DIRECTORIES is set	*
*				to $No.															*
*	v1.5	- Added option to store thumbnails in the same directories as the	*
*			  	pictures, or to store them in their own directory tree.			*
*			- Removed the $THIS_FILENAME variable and replaced it with a php	*
				function to retrieve the name of this file automatically.		*
*	v1.4	- Added min-width of 800px so pictures aren't moved down below the	*
*				menu when the window size is shrunk.							*
*	v1.3	- Fixed apostrophe and ampersand link problems by introducing 2 new	*
*				functions, EncodeSpecialCharacters and DecodeSpecialCharacters.	*
*	v1.2	- Fixed apostrophe link problem with Apply button.					*
*	v1.1	- Fixed apostrophe link problem in the Pages section.				*
*			- Updated Footer link to the proper address.						*
********************************************************************************/

// DO NOT EDIT THIS SECTION
	session_start();			// Start/Resume a session (expires when user closes browser) 
	$NEW_WINDOW = $New_Window = $new_window = '_blank';	
	$CURRENT_WINDOW = $Current_Window = $current_window = '_self';
	$YES = $Yes = $yes = true;
	$NO = $No = $no = false;
// CAN START EDITING FROM HERE DOWN
	
// CONFIGURATION SECTION - General configuration of the photo gallery
	
	// The name of the folders created to hold the Thumbnails (This must not contain spaces)
	// NOTE: This should be different from any existing folder names, as it will delete the contents of the directories if $DELETE_THUMBNAILS = $YES
	$THUMBNAILS_DIRECTORY_NAME = 'thumbnails_dofpg';
	
	// Specify if the directories to contain the Thumbnails should be created in the directories containing the actual pictures or not
	// If $YES, this will create a $THUMBNAILS_DIRECTORY_NAME directory in each folder containing pictures
	// If $NO, a separate folder to hold all of the thumbnails will be created in the same directory as this file
	$THUMBNAILS_DIRECTORIES_CREATED_IN_PICTURE_DIRECTORIES = $NO;	// ($YES or $NO) Default is $NO
	
	// Thumbnail filenames will be formed by combining this prefix with the actual pictures filename
	$THUMBNAIL_FILENAME_PREFIX = 'thumb';
	
	// File types to support (display) (Supported types include .jpg, .png, .gif, and .wbmp)
	// Example: If you only want to disply jpg's, change line below to: $SUPPORTED_FILE_TYPES = array('.jpg');
	$SUPPORTED_FILE_TYPES = array('.jpg', '.png', '.gif', '.wbmp');
	
	// When this is set to $NO (Recommended), the Directory Menu is only built at the start of the session. This can speed up page loading dramatically if there are many directories
	// When this is set to $YES, the Directory Menu will be built everytime a page loads and will show if a directory has been added instantly (instead of having to close the browser and come back to site to trigger starting a new session)
	$BUILD_DIRECTORY_LIST_EVERY_TIME = $NO;	// ($YES or $NO) You may want to set this to $YES when testing and uploading folders, but otherwise it should be $NO to speed up page loading
	
	// Thumbnails are only created when they don't already exist, so set this to $YES when
	//  changing/testing the "Thumbnail Properties" (below) so that all thumbnails are deleted from 
	//  all folders when a page is loaded.  Thumbnails will then be re-created with the new properties
	//  if $RECREATE_THUMBNAILS_AFTER_DELETE = $YES
	// NOTE: If changes do not seem to be taking effect, try clearing your browsers cache.  Also, it
	//		 may be hard to notice a difference when Quality is only adjusted by a little bit
	// NOTE: DO NOT FORGET to put this back to $NO once done testing or it will continue to take 
	//       a long time to display the thumbnails, since they will be deleted and re-created every time a page is loaded
	$DELETE_THUMBNAILS 					= $NO;	// ($YES or $NO) Delete all existing Thumbnails directories and files
	$RECREATE_THUMBNAILS_AFTER_DELETE	= $YES;	// ($YES or $NO) Recreate Thumbnail directories after deleting them. You may want to set this to $NO and load a page before changing the $THUMBNAILS_DIRECTORY_NAME to remove the existing Thumbnail directories. This only has an effect when $DELETE_THUMBNAILS = $YES
	
	// Thumbnail Properties
	$THUMBNAIL_MAX_WIDTH 			= 200;	// Max pixel width thumbnail can be (0 for no limit)
	$THUMBNAIL_MAX_HEIGHT 			= 0;	// Max pixel height thumbnail can be (0 for no limit)
	$THUMBNAIL_QUALITY				= 85;	// From 0 to 100 (worst to best). Thumbnail file size increases with quality
	// End of Thumbnail Properties

	// Title displayed in the browsers Window
	$WINDOW_TITLE = "Example Photo Album 1 - Thanks to Dans One File Photo Gallery";

// Heading and Option Settings (these are related to eachother as they are displayed together in the Heading bar)
	// Heading settings
	$SHOW_HEADING_AND_OPTIONS		= $YES;	// ($YES or $NO) Display the Heading bar with the Heading Title and Options
	$HEADING_TITLE = "Example Photos";	// Title to appear in the Heading bar
	
	// Set default "Picture Option" settings
	$DEFAULT_OPEN_PICTURES_IN		= $NEW_WINDOW;	// ($NEW_WINDOW or $CURRENT_WINDOW) When a picture is clicked on it can open in the Current Window or a New Window
	$DEFAULT_PICTURES_PER_PAGE		= 100;			// (10, 25, 50, 100, 200, 500, 1000 or 0) 0 = Display all pictures. How many pictures to display per page
	
	// Display the "Picture Options" (below) to allow the user to choose settings
	$SHOW_OPTIONS					= $YES;	// ($YES or $NO)
	$ONLY_DISPLAY_OPTIONS_IN_HOME	= $NO;	// ($YES or $NO) If you want the Options to be displayed ONLY in the Home link set this to $YES, otherwise they will be displayed on every page
	
	// Picture Options (Setting these all to $NO is they same as setting $SHOW_OPTIONS to $NO)
	$GIVE_OPEN_PICTURES_IN_OPTION	= $YES;	// ($YES or $NO) Allows user to choose if a clicked on picture opens up in a New Window or in the Current Window
	$GIVE_PICTURES_PER_PAGE_OPTION	= $YES;	// ($YES or $NO) Allows user to choose the max ammount of pictures to display per page	
	// End Picture Options
	
	// Disables the option of "All" from the Pictures Per Page drop down box (forces the user to choose X Pictures Per Page)
	$DISABLE_VIEW_ALL_PICTURES_OPTION	= $NO;	// ($YES or $NO) You may want to set this to $YES when using $SHOW_ALL_PICTURES_AND_HIDE_DIRECTORY_MENU = $YES; if there are many (thousands) of pictures in your photo gallery
// End of Heading and Options settings
	
	// The welcome message will be displayed in the Home link and can contain HTML, but should not have any double quotes (") in it
	$DISPLAY_WELCOME_MESSAGE		= $YES;	// ($YES or $NO) Show the Welcome Message in the Home link or not
	$WELCOME_MESSAGE = "<center>Welcome to the photo album!<br /><br />
						Use the menu on the left to navigate through the pictures.<br>
						To download pictures, left click on an image to view the full sized image, then right click 
						on that image and choose 'Save Picture/Image As'. If you simply right click on the small 
						thumbnail image and save that one, you will not be downloading the full sized image, but 
						the small thumbnail version of it.</center>";
	
	$NUMBER_OF_THUMBNAILS_PER_ROW	= 3;	// Number of thumbnails to display on each row
	$SHOW_FILENAMES					= $YES;	// ($YES or $NO) Display the filenames of the pictures
	$SHOW_PICTURE_NUMBERS			= $YES; // ($YES or $NO) Display the picture numbers (in the order they are displayed)
	$DISPLAY_PICTURES_IN_HOME		= $YES;	// ($YES or $NO) Display the pictures in the Home directory (where this file is placed). NOTE: $SHOW_ALL_PICTURES_IN_HOME_AND_HIDE_DIRECTORY_MENU overrides this setting
	
	$SHOW_DIRECTORY_MENU			= $YES;	// ($YES or $NO) Show the Directory Menu or not (If set to $NO, only pictures in the Home directory will be visible). NOTE: $SHOW_ALL_PICTURES_IN_HOME_AND_HIDE_DIRECTORY_MENU overrides this setting
	$SHOW_DIRECTORY_NUMBERS			= $NO;	// ($YES or $NO) Show the directory level numbers in the Directory Menu	
	
	// Display all pictures from ALL DIRECTORIES and hide the Directory Menu (since it won't be needed because all pictures are being displayed)
	// NOTE: If this is $YES, $DISPLAY_PICTURES_IN_HOME and $SHOW_DIRECTORY_MENU variables are ignored
	// NOTE: Accessing the photo gallery using this set to $YES and the Pictures Per Page option set to 0 (display all pictures)
	//		 may result in a timeout the first few times the photo gallery is accessed for the reason described
	//		 in the OPTIONAL section at the top of this file, so you may need to access the site a few times before all pictures are actually
	//		 displayed. If there are too many pictures in the photo gallery (thousands), the page may never completely load all of the pictures before
	//		 the timeout, so you may want to force using X number of Pictures Per Page (Set $DISABLE_VIEW_ALL_PICTURES_OPTION = $YES and make sure $DEFAULT_PICTURES_PER_PAGE is not 0)
	$SHOW_ALL_PICTURES_AND_HIDE_DIRECTORY_MENU = $NO;	// ($YES or $NO)
	
	// This will display a link to the Dans One File Photo Gallery homepage. I would appreciate you leaving this on, but if you really don't want it shown you can easily turn it off
	$SHOW_FOOTER					= $YES;	// ($YES or $NO)

// END CONFIGURATION SECTION

// DO NOT EDIT THIS SECTION ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php print($WINDOW_TITLE); ?></title><?php
// CAN START EDITING FROM HERE DOWN

// STYLE SHEET SECTION - Adjust the look of website (colors, appearance, etc) by modifying the CSS style sheet below 
?>
<style type="text/css">
<!--
/* Shared properties of all links */
a
{
	text-decoration:none;
}

/* Properties of links which haven't been visited */
a:link
{}

/* Properties of links which have been visited */
a:visited
{
	color:#CC0099;
}

/* Background properties */
body
{
	padding:0;
	margin:0;
	background-color:#FFFFFF;
	color:#000000;
}

/* Everything is contained within this div */
#Container
{
	width:100%;
	margin:0 auto;
	min-width:800px;
}

/* Heading properties */
#Heading
{
	border-style:solid; border-color:#000000; border-width:thin;
	background-color:#FFFFCC;
	margin:0.25em;
	font-weight:bold;
	text-align:center;
	color:#0000CC;
	clear:both;
}

/* Properties of table containing the Options */
#Heading table.Options
{
	font-size:smaller;
	color:#000000;
	width:100%;
}

/* Directory Menu properties */
#DirectoryMenu
{
	float:left;
	width:25%;
	border-style:double;
	background-color:#CCCCCC;
	padding:0.25em;
	margin:0.25em;
}

/* Properties of the Directory Menu Title */
#DirectoryMenu center.Title
{
	text-align:center;
	font-weight:bold;
	color:#0000CC;
}

/* Properties of the Selected Directory Menu link */
#DirectoryMenu a.Selected
{
	text-decoration:none;
	font-weight:bold;
	color:#FF0000;
}

/* Properties of the Directory Menu links when mouse hovers over them */
#DirectoryMenu a:hover
{
	color:#FF0000;
}

/* Properties of the Directory Numbers when enabled */
#DirectoryMenu font.DirectoryNumbers
{
	color:#000000;
}

/* Pictures section properties */
#Pictures
{
	float:right;
	width:70%;
	margin:0.25em;
}

/* Properties of the table which holds all the Pictures */
#Pictures table.Pictures
{
	width:100%;
}

/* Properties of the cell holding individual Pictures */
#Pictures table.Pictures td
{
	padding-bottom:0.5em; padding-top:0.5em;
	text-align:center;
}

/* Properties of the table holding the Pages */
#Pictures table.Pages
{
	width:100%;
	border-style:solid; border-color:#000000; border-width:thin;
	background-color:#CCCCCC;
	text-align:center;
	font-size:small;
}

/* Properties of the Page links */
#Pictures table.Pages a
{}

/* Properties of the current Page link */
#Pictures table.Pages a.Selected
{
	font-size:medium;
	text-decoration:none;
	font-weight:bold;
	color:#FF0000;
}

/* Footer properties */
#Footer
{
	width:100%;
	clear:both;
	font-size:small;
	border:none;
	text-align:center;
}

/* Used to clear floating elements */
.Clear
{
	clear:both;
}
-->
</style>

<?php
	// If the Directory Menu section is not being shown, resize the Pictures section
	if (!$SHOW_DIRECTORY_MENU || $SHOW_ALL_PICTURES_AND_HIDE_DIRECTORY_MENU)
	{
		print("<style type='text/css'>#Pictures{float:none; width:99%;}</style>");
	}

// END STYLE SHEET SECTION

/****************************************************************
*	DO NOT EDIT BELOW HERE UNLESS YOU KNOW WHAT YOU ARE DOING	*
****************************************************************/
?>

<script type="text/javascript">
function EnableApplyButton()
{
	document.Options.Apply.disabled=false;
}
</script>

<?php
	// Get Directory to view if specified (is passed through the URL) (Must also decode any special characters in the URL)
	$Directory = isset($_GET['Directory']) ? DecodeSpecialCharacters($_GET['Directory']) : '.';

	// Get how user wants to open new pictures (In a new window or the current window)
	if (isset($_GET['OpenPicturesIn'])){$_SESSION['OpenPicturesIn'] = $_GET['OpenPicturesIn'];}
	$OpenPicturesIn = isset($_SESSION['OpenPicturesIn']) ? $_SESSION['OpenPicturesIn'] : $DEFAULT_OPEN_PICTURES_IN;
	
	// Get how many Pictures user wanted displayed on each page
	if (isset($_GET['PicturesPerPage'])){$_SESSION['PicturesPerPage'] = $_GET['PicturesPerPage'];}
	$PicturesPerPage = isset($_SESSION['PicturesPerPage']) ? $_SESSION['PicturesPerPage'] : $DEFAULT_PICTURES_PER_PAGE;
	
	// Get what Page we should display
	$CurrentPicturePage = isset($_GET['PicturePage']) ? $_GET['PicturePage'] : 1;
	
	// Function returns a html string containing all Directories and Sub-Directories of the specified RootDirectory
	function ReturnDirectoryList($RootDirectory, $Level = 0, $ParentDirectoryNumber = 0)
	{	
		// Import variables into this function
		global $SUPPORTED_FILE_TYPES, $SHOW_DIRECTORY_NUMBERS, $THUMBNAILS_DIRECTORY_NAME;
	
		// Initialize variable to hold Directory listing
		$DirectoryList = '';
	
		// If we are able to open the directory
		if ($DirHandle = opendir($RootDirectory)) 
		{			
			// Initialize Directory counter (used for $SHOW_DIRECTORY_NUMBERS)
			$DirectoryNumber = 0;
			
			// Loop over files and get filenames
			while (false !== ($Filename = readdir($DirHandle))) 
			{
				// Skip default listings and Thumbnails directories
				if ($Filename != '.' && $Filename != '..' && $Filename != $THUMBNAILS_DIRECTORY_NAME)
				{
					// Specify the Directory Path properly
					$DirectoryPath = "$RootDirectory/$Filename";
					$DirectoryPath = ltrim($DirectoryPath, './');
				
					// If this is a directory
					if (is_dir($DirectoryPath))
					{					
						// Initialize the variable which tells if this directory contains pictures or not
						$ContainsPictures = false;
						
						// Increment Directory count
						$DirectoryNumber++;
					
						// Open this directory and check if it contains any pictures
						if ($InnerDirHandle = opendir($DirectoryPath))
						{
							// Loop over files and get filenames until a Picture is found or we run out of files to check
							while ((false !== ($InnerFilename = readdir($InnerDirHandle))) && ($ContainsPictures == false)) 
							{
								// Check if this is a supported picture
								foreach ($SUPPORTED_FILE_TYPES as $FileType)
								{
									// If this is a supported picture
									if (stripos($InnerFilename, $FileType) !== false)
									{
										// Save that this directory contains pictures
										$ContainsPictures = true;
										
										// Break out of foreach loop since a supported Picture was found
										break;
									}
								}
							}
						
							// Close directory
							closedir($InnerDirHandle);
						}
					
						// Calculate this Directories Number
						if ($ParentDirectoryNumber != 0)
						{
							$ThisDirectoryNumber = "$ParentDirectoryNumber.$DirectoryNumber";
						}else
						{
							$ThisDirectoryNumber = $DirectoryNumber;
						}
					
						// If this directory contains pictures
						if ($ContainsPictures)
						{																			
							// Display the directory as a link (EncodeSpecialCharacters() is used to allow special characters, like apostrophes ('), in the Directory Path)
							$DirectoryList = $DirectoryList . "<a style='margin-left:" . $Level . "em;' href='?Directory=" . EncodeSpecialCharacters($DirectoryPath) . "&PicturePage=1'>";
							
							// If the Directory level numbers should be shown
							if ($SHOW_DIRECTORY_NUMBERS)
							{
								$DirectoryList = $DirectoryList . "<font class='DirectoryNumbers'>$ThisDirectoryNumber - </font>";
							}
							
							$DirectoryList = $DirectoryList . "$Filename</a><br />";
						}
						else
						{
							// Else just display the directory name
							$DirectoryList = $DirectoryList . "<font style='margin-left:" . $Level . "em;'>";
							
							// If the Directory level numbers should be shown
							if ($SHOW_DIRECTORY_NUMBERS)
							{
								$DirectoryList = $DirectoryList . "<font class='DirectoryNumbers'>$ThisDirectoryNumber - </font>";
							}
							
							$DirectoryList = $DirectoryList . "$Filename</font><br />";
						}
						
						// Call recursively to list all other directories
						$DirectoryList = $DirectoryList . ReturnDirectoryList($DirectoryPath, $Level + 1, $ThisDirectoryNumber);
					}
				}
			}
		
			// Close directory
			closedir($DirHandle);
		}
		else
		{
			print("Unable to open directory: $RootDirectory <br />");
		}
		
		// Return the html string of this Directory and it's sub-directories
		return $DirectoryList;
	}
	
	// Function to delete Thumbnails from RootDirectory and all sub-directories
	function DeleteThumbnails($RootDirectory)
	{
		// Import variables into this function
		global $DELETE_THUMBNAILS, $THUMBNAILS_DIRECTORY_NAME, $THUMBNAIL_FILENAME_PREFIX;
	
		// First delete the root Thumbnails directory tree if it exists
		DeleteThumbnailsDirectoryTree($THUMBNAILS_DIRECTORY_NAME);
	
		// If we are able to open the directory
		if ($DirHandle = opendir($RootDirectory)) 
		{	
			// Specify Thumbnail directory for this directory
			$ThumbnailDir = "$RootDirectory/$THUMBNAILS_DIRECTORY_NAME";
			$ThumbnailDir = ltrim($ThumbnailDir, './');

			// If the Thumbnails directory exists and it should be deleted		
			if (is_dir($ThumbnailDir))
			{
				// If we can access the Thumbnails directory to remove
				if ($RemoveDirHandle = opendir($ThumbnailDir)) 
				{	
					// Delete all files in this directory
					while (false !== ($FilenameToRemove = readdir($RemoveDirHandle)))
					{
						// Skip default listings and make sure this File To Remove is one we created (i.e. filename starts with $THUMBNAIL_FILENAME_PREFIX)
						if ($FilenameToRemove != '.' && $FilenameToRemove != '..' && stripos($FilenameToRemove, $THUMBNAIL_FILENAME_PREFIX) == 0 && stripos($FilenameToRemove, $THUMBNAIL_FILENAME_PREFIX) !== false)
						{
							// Delete this file
							if (!unlink("$ThumbnailDir/$FilenameToRemove"))
							{
								print("Cannot delete file: $ThumbnailDir/$FilenameToRemove <br />");
							}
						}
					}
				
					// Delete the Thumbnails directory now that it should be empty
					if (!rmdir($ThumbnailDir))
					{
						print("Could not delete directory: $ThumbnailDir. It may contains filenames not starting with: $THUMBNAIL_FILENAME_PREFIX <br />");
					}					
					
					// Close directory
					closedir($RemoveDirHandle);
				}
			}
			
			// Loop over files and get filenames
			while (false !== ($Filename = readdir($DirHandle))) 
			{
				// Skip default listings and Thumbnails directories
				if ($Filename != '.' && $Filename != '..' && $Filename != $THUMBNAILS_DIRECTORY_NAME)
				{
					// Specify the Directory Path properly
					$DirectoryPath = "$RootDirectory/$Filename";
					$DirectoryPath = ltrim($DirectoryPath, './');
				
					// If this is a directory
					if (is_dir($DirectoryPath))
					{											
						// Call recursively to delete Thumbnails from all sub-directories
						DeleteThumbnails($DirectoryPath);
					}
				}
			}
		
			// Close directory
			closedir($DirHandle);
		}
		else
		{
			print("Unable to open directory: $RootDirectory <br />");
		}
	}
	
	// Function to delete the root Thumbnails directory tree that is created when $THUMBNAILS_DIRECTORIES_CREATED_IN_PICTURE_DIRECTORIES == $NO
	function DeleteThumbnailsDirectoryTree($DirectoryToRemove)
	{
		// Import variables into this function
		global $DELETE_THUMBNAILS, $THUMBNAILS_DIRECTORY_NAME, $THUMBNAIL_FILENAME_PREFIX;
	
		// If we are able to open the Thumbnail directory to remove
		if ($DirHandle = opendir($DirectoryToRemove)) 
		{	
			// Delete all files in this directory
			while (false !== ($FilenameToRemove = readdir($DirHandle)))
			{
				// Skip default listings
				if ($FilenameToRemove != '.' && $FilenameToRemove != '..')
				{

					// Get the Path to the file or directory to remove
					$PathToRemove = "$DirectoryToRemove/$FilenameToRemove";
					$PathToRemove = ltrim($PathToRemove, './');
				
					// If this is a directory
					if (is_dir($FilenameToRemove))
					{
						// Delete all of the thumbnails in this directory
						DeleteThumbnailsDirectoryTree($PathToRemove);
					}
					// Else this is a file
					else
					{
						// If this File To Remove is one we created (i.e. filename starts with $THUMBNAIL_FILENAME_PREFIX)
						if (stripos($FilenameToRemove, $THUMBNAIL_FILENAME_PREFIX) == 0 && stripos($FilenameToRemove, $THUMBNAIL_FILENAME_PREFIX) !== false)
						{
							// Delete this file
							if (!unlink($PathToRemove))
							{
								print("Cannot delete file: $PathToRemove <br />");
							}
						}
					}
				}
			}
		
			// Delete the Thumbnails directory now that it should be empty
			if (!rmdir($DirectoryToRemove))
			{
				print("Could not delete directory: $DirectoryToRemove. It may contains filenames not starting with: $THUMBNAIL_FILENAME_PREFIX <br />");
			}					
		
			// Close the directory
			closedir($DirHandle);
		}
	}
	
	// Function builds and returns an array containing the Paths to all of the Pictures in the specified $SearchDirectory. Returns false if there are no supported pictures in the directory
	function RetrievePicturePathsArrayFromDirectory($SearchDirectory)
	{
		// Import needed variables
		global $SUPPORTED_FILE_TYPES;
	
		// If we are able to open the directory
		if ($SearchDirHandle = opendir($SearchDirectory)) 
		{	
			// Initialize file counter
			$FileIndex = 0;
			
			// Initialize Picture Paths array
			$PicturePathsArray = array();
	
			// Loop over files and get filenames
			while (false !== ($Filename = readdir($SearchDirHandle))) 
			{
				// Skip default listings
				if ($Filename != '.' && $Filename != '..')
				{	
					// Initialize that this file type is not supported
					$IsASupportedFileType = false;
					
					// Check if this file is a supported picture (not a directory or other type of file)
					foreach ($SUPPORTED_FILE_TYPES as $FileType)
					{
						// If this is a supported file type
						if (stripos($Filename, $FileType) !== false)
						{
							// Mark that this file type is supported
							$IsASupportedFileType = true;
							break;	// Break out of this foreach loop
						}
					}	
							
					// If this is a supported picture		
					if ($IsASupportedFileType)
					{					
						// Add this filename to the array
						$PicturePathsArray['Directory'][$FileIndex] = "$SearchDirectory";
						$PicturePathsArray['Filename'][$FileIndex] = "$Filename";

						// Increment file counter
						$FileIndex++;
					}
				}
			}
		
			// Close directory
			closedir($SearchDirHandle);
			
			// If there are no pictures in this directory
			if ($FileIndex == 0)
			{
				return false;
			}
			
			// Return the array containing all of the Picture Paths
			return $PicturePathsArray; 
		}
		else
		{
			print("Unable to open directory: $SearchDirectory<br /> Make sure the directory name does not contain any unusual characters, such as &, +, etc <br />");
		}
	}
	
	// Function fills the given array ($DirectoryArray) with the Paths of all directories and sub-directories in the specified $RootDirectory
	function FillDirectoryArray($RootDirectory, &$DirectoryArray)
	{
		// Import needed variables
		global $THUMBNAILS_DIRECTORY_NAME;
	
		// If we are able to open the directory
		if ($DirHandle = opendir($RootDirectory)) 
		{	
			// Loop over files and get filenames
			while (false !== ($Filename = readdir($DirHandle))) 
			{
				// Skip default listings and Thumbnails directories
				if ($Filename != '.' && $Filename != '..' && $Filename != $THUMBNAILS_DIRECTORY_NAME)
				{
					// Specify the Directory Path properly
					$DirectoryPath = "$RootDirectory/$Filename";
					$DirectoryPath = ltrim($DirectoryPath, './');
				
					// If this is a directory
					if (is_dir($DirectoryPath))
					{								
						// Append this Directory Path to the array	
						$DirectoryArray[count($DirectoryArray)] = $DirectoryPath;
						
						// Call recursively to get all sub-directories
						FillDirectoryArray($DirectoryPath, $DirectoryArray);
					}
				}
			}
		
			// Close directory
			closedir($DirHandle);			
		}
		else
		{
			print("Unable to open directory: $RootDirectory <br />");
		}
	}
	
	// Function to display the table containing the Page links (is only displayed if Pages are being used)
	function DisplayPagesTable($TotalNumberOfPictures)
	{
		// Import needed variables
		global $PicturesPerPage, $CurrentPicturePage, $Directory;
	
		// If show all pictures is not chosen, show the page information
		if ($PicturesPerPage != 0)
		{
			// Calculate how many Pages are needed for this directory
			$NumberOfPages = ceil($TotalNumberOfPictures / $PicturesPerPage);
			
			// If there is more than one Page
			if ($NumberOfPages > 1)
			{		
				print("<table class='Pages'><tr><td width='10%'>");
		
				// If we are not on the first Page
				if ($CurrentPicturePage > 1)
				{
					// Display Previous Page link (htmlentities() is used to allow special characters, like apostrophes, by converting them into html entities)
					print("<a href='?Directory=" . EncodeSpecialCharacters($Directory) . "&PicturePage=" . ($CurrentPicturePage - 1) . "'>Previous </a>");
				}else
				{
					// Display Previous Page, but not as a link
					print("Previous ");
				}
				
				print("</td><td width='80%'>");
						
				// Loop through all Pages
				for ($Count = 0; $Count < $NumberOfPages; $Count++)
				{				
					// Display Page as a link (htmlentities() is used to allow special characters, like apostrophes, by converting them into html entities)
					print("<a ");
					if (($Count + 1) == $CurrentPicturePage){print("class='Selected' ");}
					print("href='?Directory=" . EncodeSpecialCharacters($Directory) . "&PicturePage=" . ($Count + 1) . "'>" . ($Count + 1) . "</a> ");
				}
				
				print("</td><td width='10%'>");
			
				// If there are more Pages
				if ($CurrentPicturePage < $NumberOfPages)
				{
					// Display Next Page link
					print("<a href='?Directory=" . EncodeSpecialCharacters($Directory) . "&PicturePage=" . ($CurrentPicturePage + 1) . "'> Next</a>");
				}else
				{
					// Display Next Page, but not as a link
					print(" Next");				
				}
				
				print("</td></tr></table>");
			}
		}
	}
	
	// Function to convert (encode) special characters into other formats to allow for processing
	function EncodeSpecialCharacters($String)
	{
		// Convert special characters not caught by htmlentities
		$String = str_replace(array('&', '+'), array('AmPeRsAnDEnCoDed', 'PlUsEnCoDeD'), $String);
		
		// Convert rest of special characters into HTML Entities
		$String = htmlentities($String, ENT_QUOTES);
		
		// Return the encoded string
		return $String;
	}
	
	// Function to decode the special characters back to original format
	function DecodeSpecialCharacters($String)
	{
		// Convert special characters not caught by htmlentities
		$String = str_replace(array('AmPeRsAnDEnCoDed', 'PlUsEnCoDeD'), array('&', '+'), $String);
		
		// Return decoded string
		return $String;
	}
		
	// The following thumbnail class was taken from http://www.phpclasses.org/browse/package/1261.html	
	/*############################################
	# Shiege Iseng Resize Class
	# 11 March 2003
	# shiegege_at_yahoo.com
	# View Demo :
	#   http://kentung.f2o.org/scripts/thumbnail/sample.php
	################
	# Thanks to :
	# Dian Suryandari <dianhau_at_yahoo.com>	
	##############################################
	Sample :
	$thumb=new thumbnail("./shiegege.jpg");	// generate image_file, set filename to resize
	$thumb->size_width(100);				// set width for thumbnail, or
	$thumb->size_height(300);				// set height for thumbnail, or
	$thumb->size_auto(200);					// set the biggest width or height for thumbnail
	$thumb->jpeg_quality(75);				// [OPTIONAL] set quality for jpeg only (0 - 100) (worst - best), default = 75
	$thumb->show();							// show your thumbnail
	$thumb->save("./huhu.jpg");				// save your thumbnail to file
	----------------------------------------------
	Note :
	- GD must Enabled
	- Autodetect file extension (.jpg/jpeg, .png, .gif, .wbmp)
	  but some server can't generate .gif / .wbmp file types
	- If your GD not support 'ImageCreateTrueColor' function,
	  change one line from 'ImageCreateTrueColor' to 'ImageCreate'
	  (the position in 'show' and 'save' function)
	############################################*/
	class thumbnail
	{
		var $img;
	
		function thumbnail($imgfile)
		{
			//detect image format
			$this->img["format"]=ereg_replace(".*\.(.*)$","\\1",$imgfile);
			$this->img["format"]=strtoupper($this->img["format"]);
			if ($this->img["format"]=="JPG" || $this->img["format"]=="JPEG") {
				//JPEG
				$this->img["format"]="JPEG";
				$this->img["src"] = ImageCreateFromJPEG ($imgfile);
			} elseif ($this->img["format"]=="PNG") {
				//PNG
				$this->img["format"]="PNG";
				$this->img["src"] = ImageCreateFromPNG ($imgfile);
			} elseif ($this->img["format"]=="GIF") {
				//GIF
				$this->img["format"]="GIF";
				$this->img["src"] = ImageCreateFromGIF ($imgfile);
			} elseif ($this->img["format"]=="WBMP") {
				//WBMP
				$this->img["format"]="WBMP";
				$this->img["src"] = ImageCreateFromWBMP ($imgfile);
			} else {
				//DEFAULT
				echo "Not Supported File";
				exit();
			}
			@$this->img["lebar"] = imagesx($this->img["src"]);
			@$this->img["tinggi"] = imagesy($this->img["src"]);
			//default quality jpeg
			$this->img["quality"]=75;
		}
	
		function size_height($size=100)
		{
			//height
			$this->img["tinggi_thumb"]=$size;
			@$this->img["lebar_thumb"] = ($this->img["tinggi_thumb"]/$this->img["tinggi"])*$this->img["lebar"];
		}
	
		function size_width($size=100)
		{
			//width
			$this->img["lebar_thumb"]=$size;
			@$this->img["tinggi_thumb"] = ($this->img["lebar_thumb"]/$this->img["lebar"])*$this->img["tinggi"];
		}
	
		function size_auto($size=100)
		{
			//size
			if ($this->img["lebar"]>=$this->img["tinggi"]) {
				$this->img["lebar_thumb"]=$size;
				@$this->img["tinggi_thumb"] = ($this->img["lebar_thumb"]/$this->img["lebar"])*$this->img["tinggi"];
			} else {
				$this->img["tinggi_thumb"]=$size;
				@$this->img["lebar_thumb"] = ($this->img["tinggi_thumb"]/$this->img["tinggi"])*$this->img["lebar"];
			}
		}
	
		function jpeg_quality($quality=75)
		{
			//jpeg quality
			$this->img["quality"]=$quality;
		}
	
		function show()
		{
			//show thumb
			@Header("Content-Type: image/".$this->img["format"]);
	
			/* change ImageCreateTrueColor to ImageCreate if your GD not supported ImageCreateTrueColor function*/
			$this->img["des"] = ImageCreateTrueColor($this->img["lebar_thumb"],$this->img["tinggi_thumb"]);
				@imagecopyresized ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["lebar_thumb"], $this->img["tinggi_thumb"], $this->img["lebar"], $this->img["tinggi"]);
	
			if ($this->img["format"]=="JPG" || $this->img["format"]=="JPEG") {
				//JPEG
				imageJPEG($this->img["des"],"",$this->img["quality"]);
			} elseif ($this->img["format"]=="PNG") {
				//PNG
				imagePNG($this->img["des"]);
			} elseif ($this->img["format"]=="GIF") {
				//GIF
				imageGIF($this->img["des"]);
			} elseif ($this->img["format"]=="WBMP") {
				//WBMP
				imageWBMP($this->img["des"]);
			}
		}
	
		function save($save="")
		{
			//save thumb
			if (empty($save)) $save=strtolower("./thumb.".$this->img["format"]);
			/* change ImageCreateTrueColor to ImageCreate if your GD not supported ImageCreateTrueColor function*/
			$this->img["des"] = ImageCreateTrueColor($this->img["lebar_thumb"],$this->img["tinggi_thumb"]);
				@imagecopyresized ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["lebar_thumb"], $this->img["tinggi_thumb"], $this->img["lebar"], $this->img["tinggi"]);
	
			if ($this->img["format"]=="JPG" || $this->img["format"]=="JPEG") {
				//JPEG
				imageJPEG($this->img["des"],"$save",$this->img["quality"]);
			} elseif ($this->img["format"]=="PNG") {
				//PNG
				imagePNG($this->img["des"],"$save");
			} elseif ($this->img["format"]=="GIF") {
				//GIF
				imageGIF($this->img["des"],"$save");
			} elseif ($this->img["format"]=="WBMP") {
				//WBMP
				imageWBMP($this->img["des"],"$save");
			}
		}
	}
?>
</head>

<body>
<div id="Container">
<?php 

	// If the Heading and Options should be displayed
	if ($SHOW_HEADING_AND_OPTIONS)
	{
		// Display the Heading
		print("<div id='Heading'>");
		print("$HEADING_TITLE<br />");
		
		// If we should show the Options
		if ($SHOW_OPTIONS)
		{
			// Double check to make sure Options should be displayed
			if ($GIVE_OPEN_PICTURES_IN_OPTION || $GIVE_PICTURES_PER_PAGE_OPTION)
			{
				// If the Options should only be displayed in the Home link, make sure we are in the Home link
				if ((!$ONLY_DISPLAY_OPTIONS_IN_HOME) || ($ONLY_DISPLAY_OPTIONS_IN_HOME && $Directory == '.'))
				{
					print("<table class='Options'><tr><form method='get' action='' name='Options'>");
					
					// If the Open Pictures In option should be displayed
					if ($GIVE_OPEN_PICTURES_IN_OPTION){print("<td align='left'>Open pictures in: <input type='radio' name='OpenPicturesIn' value='_self' "); if($OpenPicturesIn == '_self') print("checked='checked'"); print(" onchange='EnableApplyButton()' />This Window <input type='radio' name='OpenPicturesIn' value='_blank' "); if($OpenPicturesIn != '_self') print("checked='checked'"); print(" onchange='EnableApplyButton()' />New Window</td>");}
					
					//  If the Pictures Per Page option should be displayed
					if ($GIVE_PICTURES_PER_PAGE_OPTION)
					{
						print("<td align='center'>Pictures per page: <select name='PicturesPerPage' size='1' onchange='EnableApplyButton()'>");
							print("<option "); if($PicturesPerPage == 10){print("selected='selected' ");} print("value='10'>10</option>");
							print("<option "); if($PicturesPerPage == 25){print("selected='selected' ");} print("value='25'>25</option>");
							print("<option "); if($PicturesPerPage == 50){print("selected='selected' ");} print("value='50'>50</option>");
							print("<option "); if($PicturesPerPage == 100){print("selected='selected' ");} print("value='100'>100</option>");
							print("<option "); if($PicturesPerPage == 200){print("selected='selected' ");} print("value='200'>200</option>");
							print("<option "); if($PicturesPerPage == 500){print("selected='selected' ");} print("value='500'>500</option>");
							print("<option "); if($PicturesPerPage == 1000){print("selected='selected' ");} print("value='1000'>1000</option>");
							// If showing all Pictures is acceptable
							if (!$DISABLE_VIEW_ALL_PICTURES_OPTION)
							{
								print("<option "); if($PicturesPerPage == 0){print("selected='selected' ");} print("value='0'>All</option>");
							}
						print("</select></td>");
					}
					// Display Apply button and also include Directory to load after apply is hit (hidden) (htmlentities() is used to allow special characters, like apostrophes, by converting them into html entities)
					print("<input type='hidden' name='Directory' value='" . EncodeSpecialCharacters($Directory) .  "' /><td><input type='submit' name='Apply' value='Apply' align='right' width='10%' disabled='disabled' /></td></form></tr></table>");
				}
			}
		}
		print("</div>");
		print("<div class='Clear'></div>");
	}
	
	// If all Thumbnails should be deleted
	if ($DELETE_THUMBNAILS)
	{
		// Delete all Thumbnails from the Home directory and all sub-directories
		DeleteThumbnails('.');
	}
?>

<?php
	// If the Directory Menu should be shown
	if ($SHOW_DIRECTORY_MENU && !$SHOW_ALL_PICTURES_AND_HIDE_DIRECTORY_MENU)
	{
		print("<div id='DirectoryMenu'>");
	
		// Table Menu heading
		print("<center class='Title'>Directory Menu</center>");
		
		// Build Home link of Menu
		$ThisFile = $_SERVER['PHP_SELF'];
		print("<a href='$ThisFile'"); if ($Directory == '.'){print("class='Selected'");} print(">Home</a><br />");
		
		// If the DirectoryMenu hasn't been built yet this session, build it
		if (!isset($_SESSION['DirectoryList']) || $BUILD_DIRECTORY_LIST_EVERY_TIME)
		{
			$_SESSION['DirectoryList'] = ReturnDirectoryList('.');
		}
		
		// Search Directory List and mark current directory as Selected (EncodeSpecialCharacters() is used to allow special characters, like apostrophes)
		$TempDirectoryList = str_replace("href='?Directory=" . EncodeSpecialCharacters($Directory) . "&PicturePage=1'", "href='?Directory=" . EncodeSpecialCharacters($Directory) . "&PicturePage=1'class='Selected'", $_SESSION['DirectoryList']);

		// Display the pre-compiled DirectoryMenu
		print($TempDirectoryList);
	
		print("</div>");
	}
?>

<div id="Pictures">
<?php
	// If we are in the Home "directory"
	if ($Directory == '.')
	{
		if ($DISPLAY_WELCOME_MESSAGE)
		{
			// Display the Welcome message
			print("$WELCOME_MESSAGE<br />");
		}
	}

	$DisplayPictures = true;
	// If we are in the Home directory and pictures should not be shown in the Home directory
	if ($Directory == '.' && !$DISPLAY_PICTURES_IN_HOME && !$SHOW_ALL_PICTURES_AND_HIDE_DIRECTORY_MENU)
	{
		$DisplayPictures = false;
	}

	// If this directories Pictures should be displayed
	if ($DisplayPictures)
	{
		// If all Pictures from all directories should be shown
		if ($SHOW_ALL_PICTURES_AND_HIDE_DIRECTORY_MENU)
		{
			// Initialize array
			$DirectoryPathsArray = array();
			
			// Get all Directory Paths
			FillDirectoryArray('.', $DirectoryPathsArray);
			
			// Store how many directories were obtained
			$NumOfDirectories = count($DirectoryPathsArray);
				
			// If some directories were returned
			if ($NumOfDirectories > 0)
			{
				// Initialize array
				$PicturePathsArray = array();
			
				// Loop through all Directory Paths in the array
				for($DirIndex = 0; $DirIndex < $NumOfDirectories; $DirIndex++)
				{
					$TempPicturePathsArray = RetrievePicturePathsArrayFromDirectory($DirectoryPathsArray[$DirIndex]);
				
					// If this directory contains Pictures
					if (count($TempPicturePathsArray['Filename']) > 0)
					{
						// Append them to the PicturePathsArray
						$PicturePathsArray = array_merge_recursive($PicturePathsArray, $TempPicturePathsArray);
					}
				}

			}
		}else
		{
			// Get the array of Picture Paths for the current directory
			$PicturePathsArray = RetrievePicturePathsArrayFromDirectory($Directory);
		}
	
		// If pictures were found
		if (count($PicturePathsArray['Filename']) > 0)
		{
			// Store the length of the array
			$PicturePathsArrayLength = count($PicturePathsArray['Filename']);
			
			// Calculate First and Last Pictures to display			
			$FirstFileToDisplay = $FileIndex = ($CurrentPicturePage - 1) * $PicturesPerPage;
			$LastFileToDisplay = ($PicturesPerPage == 0) ? $PicturePathsArrayLength : ($CurrentPicturePage * $PicturesPerPage);			
			if ($LastFileToDisplay > $PicturePathsArrayLength){$LastFileToDisplay = $PicturePathsArrayLength;}
			
			// Display the Pages table (is only displayed if needed)
			DisplayPagesTable($PicturePathsArrayLength);
			
			// Build table to hold pictures
			print("<table class='Pictures'><tr>");
			
			// Loop through all Pictures which will be displayed
			while ($FileIndex < $LastFileToDisplay)
			{
				// Get Directory and Filename of this Picture
				$PictureDirectory = $PicturePathsArray['Directory'][$FileIndex];
				$PictureFilename = $PicturePathsArray['Filename'][$FileIndex];
			
				// Create the paths to the Thumbnail and Picture
				$PicturePath = "$PictureDirectory/$PictureFilename";
				$ThumbnailDirectory = "$PictureDirectory/$THUMBNAILS_DIRECTORY_NAME";
				$ThumbnailPath = "$PictureDirectory/$THUMBNAILS_DIRECTORY_NAME/$THUMBNAIL_FILENAME_PREFIX" . $PictureFilename;
				
				// If the Thumbnails should be created in their own directory tree
				if (!$THUMBNAILS_DIRECTORIES_CREATED_IN_PICTURE_DIRECTORIES)
				{
					$ThumbnailDirectory = "$THUMBNAILS_DIRECTORY_NAME/$PictureDirectory";
					$ThumbnailPath = "$THUMBNAILS_DIRECTORY_NAME/$PictureDirectory/$THUMBNAIL_FILENAME_PREFIX" . $PictureFilename;
					
					// The mkdir() function is currently broken and can't support spaces in the directory path, so 
					// replace all spaces with underscores in the thumbnail paths
					$ThumbnailDirectory = str_replace(" ", "_", $ThumbnailDirectory);
					$ThumbnailPath = str_replace(" ", "_", $ThumbnailPath);
				}		

				// If the thumbnail doesn't exist yet, and Recreating Thumbnails is allowed
				if (!file_exists($ThumbnailPath) && (!$DELETE_THUMBNAILS || $RECREATE_THUMBNAILS_AFTER_DELETE))
				{
					// If the Thumbnails should be stored in their own directory tree
					if (!$THUMBNAILS_DIRECTORIES_CREATED_IN_PICTURE_DIRECTORIES)
					{
						// Break the Thumbnail Directory path into its individual Directories
						$Directories = explode("/", $ThumbnailDirectory);
						
						// Make sure each of the Directories in the Thumbnail Directory have been created
						$DirectoryPath = "";
						foreach($Directories as $Directory)
						{
							// Append this Directory to the Directory Path
							$DirectoryPath .= $Directory;
							
							// Create the Directory if it does not exist yet
							if (!is_dir($DirectoryPath))
							{
								mkdir($DirectoryPath);
							}
							
							// Set the Directory Path up for the next Directory to be added to it
							$DirectoryPath .= "/";
						}
					}
				
					// Create Thumbnails folder if it doesn't exist yet
					if (!is_dir($ThumbnailDirectory))
					{
						mkdir($ThumbnailDirectory);
					}
					
					// Create Thumbnail from Picture and save it in Thumbnails folder
					$thumb = new thumbnail($PicturePath);	// Prepare to generate Thumbnail from full Picture
					if ($THUMBNAIL_QUALITY) $thumb->jpeg_quality($THUMBNAIL_QUALITY);		// Set the pictures quality of the Thumbnail
					if ($THUMBNAIL_MAX_WIDTH) $thumb->size_width($THUMBNAIL_MAX_WIDTH);		// Set max width of Thumbnail
					if ($THUMBNAIL_MAX_HEIGHT) $thumb->size_height($THUMBNAIL_MAX_HEIGHT);	// Set max height of Thumbnail
					$thumb->save($ThumbnailPath);			// Save the Thumbnail
				}
				
				// If Thumbnail exists, display it
				if (file_exists($ThumbnailPath))
				{
					// If this picture should be displayed on a new row
					if ((($FileIndex - $FirstFileToDisplay) % $NUMBER_OF_THUMBNAILS_PER_ROW) == 0){print("</tr><tr>");}
				
					// Display the thumbnail as a link to the full Picture (htmlentities() is used to allow special characters, like apostrophes, by converting them into html entities, so that Paths are not cut off short if they contain one)
					print("<td><a href='" . htmlentities($PicturePath, ENT_QUOTES) . "' target='$OpenPicturesIn'><img src='" . htmlentities($ThumbnailPath, ENT_QUOTES) . "' /></a>");

					if ($SHOW_PICTURE_NUMBERS || $SHOW_FILENAMES)
					{
						print("<br />");
						if ($SHOW_PICTURE_NUMBERS){print($FileIndex + 1);}
						if ($SHOW_PICTURE_NUMBERS && $SHOW_FILENAMES){print(" - ");}
						if ($SHOW_FILENAMES){print("$PictureFilename");}
					}
					print("</td>");
				}
				
				// Increment the File counter
				$FileIndex++;
			}
			
			// Close picture table
			print("</tr></table>");
			
			// Display the Pages table (is only displayed if needed)
			DisplayPagesTable($PicturePathsArrayLength);
		}
		else
		{
			// Don't display No Pictures message in Home directory
			if ($Directory != '.')
			{
				print("There are no supported pictures to display in directory: $Directory <br />");
			}
		}
	}
?>
</div>

<?php
if ($SHOW_FOOTER)
{
	print("<div class='Clear'></div>");
	print("<div id='Footer'>Photo album provided using <a href='http://www.danskingdom.com/DansOneFilePhotoGalleryHome.php' target='_blank'>Dans One File Photo Gallery</a></div>");
}
?>
</div> <!-- Closes the Container div -->
</body>
</html>
