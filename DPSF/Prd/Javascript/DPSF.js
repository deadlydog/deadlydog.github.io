// Global variables
var gImgElementID = null;	// The ID of the img element to update
var gGIFSrc = null;			// The GIF that the img element should display

// Swaps the img from displaying a JPG to displaying a GIF
function SwapJPGToGIF(_ElementID)
{
	// Get a handle to the Img to update
	var hImg = document.getElementById(_ElementID);

	// If the JPG is being shown
	if (hImg.src.search(/.jpg/i) != -1)
	{
		// Save the Src to the GIF
		gGIFSrc = hImg.src.replace(/.jpg/i, ".gif");
		
		// Save the Element's ID
		gImgElementID = _ElementID;
		
		// Swap the image to show the loading image
		hImg.src = "Images/Transparent.png";

		// Call the function to show the GIF instead of the loading image.
		// NOTE: We use setTimeout so that the display is updated to show the loading image
		// before switching to showing the GIF, so the loading image shows while the GIF loads.
		setTimeout("_SwapJPGToGIF()", 0);
	}
	// Else the GIF is being displayed already
	else
	{
		// Cause the animation to restart from the beginning
		hImg.src = hImg.src;
	}
}

function _SwapJPGToGIF()
{
	// Update the img to show the GIF
	document.getElementById(gImgElementID).src = gGIFSrc;
}

// Toggles the given element's Visible property.
// Returns false if the element does not exist.
function ToggleVisibility(_ElementID, _bMakeVisible)
{
	// Get a handle to the Element
	var hElement = document.getElementById(_ElementID);
	
	// If we did not get a handle to the Element
	if (hElement == null)
	{
		// Return that the specified ElementID doesn't exist
		return false;	
	}
	
	// If the Visibility property to use is specified
	if (_bMakeVisible != null)
	{
		// If the Element should be visible
		if (_bMakeVisible)
		{
			// Make it visible
			hElement.style.display = "block";
		}
		// Else it should not be visible
		else
		{
			// So make it not visible
			hElement.style.display = "none";
		}
	}
	// Else the Visibility property to use was not specified, so toggle the current Visibility
	else
	{
		// If the Element is not visibile
		if (hElement.style.display == "none")
		{
			// Make it visible
			hElement.style.display = "block";
		}
		// Else the Element is visible
		else
		{
			// So make it not visible
			hElement.style.display = "none";
		}
	}
	
	// Return that the update was successful
	return true;
}

function ToggleVisibilityOfAllFAQAnswers()
{
	// Get if the first Answer is visible or not
	var hElement = document.getElementById("Answer0");
	var bMakeAllAnswersVisible = hElement.style.display == "none" ? true : false;	
	
	// Loop through and toggle each answer until we reach one that doesn't exist
	var iCount = 0;	
	while (ToggleVisibility("Answer" + iCount, bMakeAllAnswersVisible))
	{
		// Increment the counter to update the next Answer
		iCount++;			
	}
	
	iCount = 0;	
	while (ToggleVisibility("CodeAnswer" + iCount, bMakeAllAnswersVisible))
	{
		// Increment the counter to update the next Answer
		iCount++;			
	}
}

