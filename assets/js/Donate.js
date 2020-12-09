var donateModal = document.getElementById("DonateModal");
var donateModalOpenButton = document.getElementById("DonateModalOpenButton");
var donateModalCloseButton = document.getElementById("DonateModalCloseButton");

// When the user clicks the open button, open the modal.
donateModalOpenButton.onclick = function(event)
{
	if (DonateModalIsOpen())
	{
		CloseDonateModal();
	}
	else
	{
		OpenDonateModal();
		event.stopPropagation();	// Prevent the window event handler from closing the modal instantly.
	}
}

// When the user clicks the close button, close the modal.
donateModalCloseButton.onclick = function(event)
{
	CloseDonateModal();
}

// When the user clicks anywhere outside the modal, close the modal.
window.onclick = function(event)
{
	// Must check both the modal and it's children.
	var userClickedOutsideOfModal = (event.target !== donateModal && !donateModal.contains(event.target))

	if (DonateModalIsOpen() && userClickedOutsideOfModal)
	{
		CloseDonateModal();
	}
}

function OpenDonateModal()
{
	donateModal.style.display = "block";
}

function CloseDonateModal()
{
	donateModal.style.display = "none";
}

function DonateModalIsOpen()
{
	return donateModal.style.display == "block";
}
