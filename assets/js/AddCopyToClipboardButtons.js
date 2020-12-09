
var allCodeElements = document.querySelectorAll("code");
allCodeElements.forEach((codeElement, index, parent) => {

	if (!IsFencedCodeBlock(codeElement))
		return;

	// Assign the <code> element a unique ID so we can reference it when clicked.
	var codeElementId = `dynamicCodeElement${index}`
	codeElement.setAttribute("id", codeElementId);

	AddCopyCodeSnippetButtonToCodeElement(codeElement, codeElementId)
});

function IsFencedCodeBlock(codeElement)
{
	// Inline code snippets have a class name, but code blocks do not.
	return (codeElement.className ? false : true);
}

function AddCopyCodeSnippetButtonToCodeElement(codeElement, codeElementId)
{
	// Instead of using a real button we use an <i> element, as per Font Awesome's instructions: https://fontawesome.com/v3.2.1/examples/
	var buttonElement = document.createElement("i");
	buttonElement.addEventListener('click', function () { CopyElementTextToClipboard(codeElementId) });
	buttonElement.classList = 'CopyCodeSnippetToClipboardButton Tooltip far fa-copy'	// https://fontawesome.com/icons/copy?style=regular
	codeElement.prepend(buttonElement);

	// Add the button tooltip.
	var tooltipElement = document.createElement("span");
	tooltipElement.textContent = "Copy code snippet to clipboard"
	tooltipElement.classList = "TooltipText";
	buttonElement.appendChild(tooltipElement);
}

function CopyElementTextToClipboard(domElementId)
{
	var element = document.getElementById(domElementId);
	if (!element)
	{
		console.error(`Could not find the element to copy text to clipboard for. Specified element ID was '${domElementId}'.`);
	}

	var textToCopyToClipboard = element.innerText

	navigator.clipboard.writeText(textToCopyToClipboard).then(
		function ()
		{
			console.log('Copying to clipboard was successful!');
		},
		function (error)
		{
			console.error('Could not copy text to clipboard.', error);
		}
	);
}
