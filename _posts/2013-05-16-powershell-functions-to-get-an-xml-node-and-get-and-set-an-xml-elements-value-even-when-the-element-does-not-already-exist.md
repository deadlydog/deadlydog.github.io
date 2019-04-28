---
id: 281
title: 'Powershell functions to get an xml node, and get and set an xml element&rsquo;s value, even when the element does not already exist'
date: 2013-05-16T17:16:57-06:00
author: deadlydog
layout: post
guid: http://dans-blog.azurewebsites.net/?p=281
permalink: /powershell-functions-to-get-an-xml-node-and-get-and-set-an-xml-elements-value-even-when-the-element-does-not-already-exist/
categories:
  - PowerShell
  - XML
tags:
  - element
  - exist
  - node
  - 'null'
  - PowerShell
  - SelectNodes
  - SelectSingleNode
  - XML
---
I’m new to working with Xml through PowerShell and was so impressed when I discovered how easy it was to read an xml element’s value.&#160; I’m working with reading/writing .nuspec files for working with NuGet.&#160; Here’s a sample xml of a .nuspec xml file:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:6f27e32f-131b-479d-a0ee-45ac9f59475d" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: xml; pad-line-numbers: true; title: ; notranslate" title="">
&lt;?xml version="1.0" encoding="utf-8"?&gt;
&lt;package xmlns="http://schemas.microsoft.com/packaging/2010/07/nuspec.xsd"&gt;
  &lt;metadata&gt;
    &lt;id&gt;MyAppsId&lt;/id&gt;
    &lt;version&gt;1.0.1&lt;/version&gt;
    &lt;title&gt;MyApp&lt;/title&gt;
    &lt;authors&gt;Daniel Schroeder&lt;/authors&gt;
    &lt;owners&gt;Daniel Schroeder&lt;/owners&gt;
    &lt;requireLicenseAcceptance&gt;false&lt;/requireLicenseAcceptance&gt;
    &lt;description&gt;My App.&lt;/description&gt;
    &lt;summary&gt;My App.&lt;/summary&gt;
    &lt;tags&gt;Powershell, Application&lt;/tags&gt;
  &lt;/metadata&gt;
  &lt;files&gt;
    &lt;file src="MyApp.ps1" target="content\MyApp.ps1" /&gt;
  &lt;/files&gt;
&lt;/package&gt;
</pre>
</div>

&#160;

In PowerShell if I want to get the version element’s value, I can just do:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:41ae0bb7-2a98-4b2d-8272-9a6c9c6d8bb7" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: powershell; title: ; notranslate" title="">
# Read in the file contents and return the version node's value.
[ xml ]$fileContents = Get-Content -Path $NuSpecFilePath
return $fileContents.package.metadata.version
</pre>
</div>

&#160;

Wow, that’s super easy.&#160; And if I want to update that version number, I can just do:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:3cf516e2-54d9-4054-b6ab-8877a7034cf6" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: powershell; title: ; notranslate" title="">
# Read in the file contents, update the version node's value, and save the file.
[ xml ] $fileContents = Get-Content -Path $NuSpecFilePath
$fileContents.package.metadata.version = $NewVersionNumber
$fileContents.Save($NuSpecFilePath)
</pre>
</div>

&#160;

Holy smokes. So simple it blows my mind.&#160; So everything is great, right?&#160; Well, it is until you try and read or write to an element that doesn’t exist.&#160; If the <version> element is not in the xml, when I try and read from it or write to it, I get an error such as “Error: Property ‘version’ cannot be found on this object. Make sure that it exists.”.&#160; You would think that checking if an element exists would be straight-forward and easy right? Well, it almost is.&#160; There’s a [SelectSingleNode() function](http://msdn.microsoft.com/en-us/library/system.xml.xmlnode.selectsinglenode.aspx) that we can use to look for the element, but what I realized after a couple hours of banging my head on the wall and [stumbling across this stack overflow post](http://stackoverflow.com/questions/1766254/selectsinglenode-always-returns-null), is that in order for this function to work properly, you really need to use the overloaded method that also takes an XmlNamespaceManager; otherwise the SelectSingleNode() function always returns null.

So basically you need an extra 2 lines in order to setup an XmlNamespaceManager every time you need to look for a node.&#160; This is a little painful, so instead I created this function that will get you the node if it exists, and return $null if it doesn’t:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:4d033fed-aa0d-49a4-ac82-0d0b933bfcea" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: powershell; title: ; notranslate" title="">
function Get-XmlNode([ xml ]$XmlDocument, [string]$NodePath, [string]$NamespaceURI = "", [string]$NodeSeparatorCharacter = '.')
{
	# If a Namespace URI was not given, use the Xml document's default namespace.
	if ([string]::IsNullOrEmpty($NamespaceURI)) { $NamespaceURI = $XmlDocument.DocumentElement.NamespaceURI }	
	
	# In order for SelectSingleNode() to actually work, we need to use the fully qualified node path along with an Xml Namespace Manager, so set them up.
	$xmlNsManager = New-Object System.Xml.XmlNamespaceManager($XmlDocument.NameTable)
	$xmlNsManager.AddNamespace("ns", $NamespaceURI)
	$fullyQualifiedNodePath = "/ns:$($NodePath.Replace($($NodeSeparatorCharacter), '/ns:'))"
	
	# Try and get the node, then return it. Returns $null if the node was not found.
	$node = $XmlDocument.SelectSingleNode($fullyQualifiedNodePath, $xmlNsManager)
	return $node
}
</pre>
</div>

&#160;

And you would call this function like so:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:dddfccaa-4f4d-4596-90f4-ddf9006b7ae7" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: powershell; title: ; notranslate" title="">
# Read in the file contents and return the version node's value.
[ xml ]$fileContents = Get-Content -Path $NuSpecFilePath
$node = Get-XmlNode -XmlDocument $fileContents -NodePath "package.metadata.version"
if ($node -eq $null) { return $null }
return $fileContents.package.metadata.version
</pre>
</div>

&#160;

So if the node doesn’t exist (i.e. is $null), I return $null instead of trying to access the non-existent element.

So by default this Get-XmlNode function uses the xml’s root namespace, which is what we want 95% of the time.&#160; It also takes a NodeSeparatorCharacter that defaults to a period.&#160; While Googling for answers I saw that many people use the the syntax “$fileContents/package/metadata/version” instead of “$fileContents.package.metadata.version”.&#160; I prefer the dot notation, but for those who like the slash just override the NodeSeparatorCharacter with a slash.

<font color="#00ff00"><Update></font>

Later I found that I also wanted the ability to return back multiple xml nodes; that is, if multiple “version” elements were defined I wanted to get them all, not just the first one.&#160; This is simple; instead of using .SelectSingleNode() we can use .SelectNodes().&#160; In order to avoid duplicating code, I broke the code to get the Xml Namespace Manager and Fully Qualified Node Path out into their own functions.&#160; Here is the rewritten code, with the new Get-XmlNodes function:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:8b5955e6-e281-457a-8df0-b2aca9ba40d1" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title="">
function Get-XmlNamespaceManager([ xml ]$XmlDocument, [string]$NamespaceURI = "")
{
    # If a Namespace URI was not given, use the Xml document's default namespace.
	if ([string]::IsNullOrEmpty($NamespaceURI)) { $NamespaceURI = $XmlDocument.DocumentElement.NamespaceURI }	
	
	# In order for SelectSingleNode() to actually work, we need to use the fully qualified node path along with an Xml Namespace Manager, so set them up.
	[System.Xml.XmlNamespaceManager]$xmlNsManager = New-Object System.Xml.XmlNamespaceManager($XmlDocument.NameTable)
	$xmlNsManager.AddNamespace("ns", $NamespaceURI)
    return ,$xmlNsManager		# Need to put the comma before the variable name so that PowerShell doesn't convert it into an Object[].
}

function Get-FullyQualifiedXmlNodePath([string]$NodePath, [string]$NodeSeparatorCharacter = '.')
{
    return "/ns:$($NodePath.Replace($($NodeSeparatorCharacter), '/ns:'))"
}

function Get-XmlNode([ xml ]$XmlDocument, [string]$NodePath, [string]$NamespaceURI = "", [string]$NodeSeparatorCharacter = '.')
{
	$xmlNsManager = Get-XmlNamespaceManager -XmlDocument $XmlDocument -NamespaceURI $NamespaceURI
	[string]$fullyQualifiedNodePath = Get-FullyQualifiedXmlNodePath -NodePath $NodePath -NodeSeparatorCharacter $NodeSeparatorCharacter
	
	# Try and get the node, then return it. Returns $null if the node was not found.
	$node = $XmlDocument.SelectSingleNode($fullyQualifiedNodePath, $xmlNsManager)
	return $node
}

function Get-XmlNodes([ xml ]$XmlDocument, [string]$NodePath, [string]$NamespaceURI = "", [string]$NodeSeparatorCharacter = '.')
{
	$xmlNsManager = Get-XmlNamespaceManager -XmlDocument $XmlDocument -NamespaceURI $NamespaceURI
	[string]$fullyQualifiedNodePath = Get-FullyQualifiedXmlNodePath -NodePath $NodePath -NodeSeparatorCharacter $NodeSeparatorCharacter

	# Try and get the nodes, then return them. Returns $null if no nodes were found.
	$nodes = $XmlDocument.SelectNodes($fullyQualifiedNodePath, $xmlNsManager)
	return $nodes
}
</pre>
</div>

Note the comma in the return statement of the Get-XmlNamespaceManager function.&#160; It took me a while to discover [why things broke without it](http://stackoverflow.com/questions/17498320/powershell-changes-return-objects-type).

<font color="#00ff00"></Update></font>

So once I had this, I decided that I might as well make functions for easily getting and setting the text values of an xml element, which is what is provided here:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:c273612b-310d-4496-a773-7a8feb780e4f" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: powershell; title: ; notranslate" title="">
function Get-XmlElementsTextValue([ xml ]$XmlDocument, [string]$ElementPath, [string]$NamespaceURI = "", [string]$NodeSeparatorCharacter = '.')
{
	# Try and get the node.	
	$node = Get-XmlNode -XmlDocument $XmlDocument -NodePath $ElementPath -NamespaceURI $NamespaceURI -NodeSeparatorCharacter $NodeSeparatorCharacter
	
	# If the node already exists, return its value, otherwise return null.
	if ($node) { return $node.InnerText } else { return $null }
}

function Set-XmlElementsTextValue([ xml ]$XmlDocument, [string]$ElementPath, [string]$TextValue, [string]$NamespaceURI = "", [string]$NodeSeparatorCharacter = '.')
{
	# Try and get the node.	
	$node = Get-XmlNode -XmlDocument $XmlDocument -NodePath $ElementPath -NamespaceURI $NamespaceURI -NodeSeparatorCharacter $NodeSeparatorCharacter
	
	# If the node already exists, update its value.
	if ($node)
	{ 
		$node.InnerText = $TextValue
	}
	# Else the node doesn't exist yet, so create it with the given value.
	else
	{
		# Create the new element with the given value.
		$elementName = $ElementPath.SubString($ElementPath.LastIndexOf($NodeSeparatorCharacter) + 1)
 		$element = $XmlDocument.CreateElement($elementName, $XmlDocument.DocumentElement.NamespaceURI)		
		$textNode = $XmlDocument.CreateTextNode($TextValue)
		$element.AppendChild($textNode) &gt; $null
		
		# Try and get the parent node.
		$parentNodePath = $ElementPath.SubString(0, $ElementPath.LastIndexOf($NodeSeparatorCharacter))
		$parentNode = Get-XmlNode -XmlDocument $XmlDocument -NodePath $parentNodePath -NamespaceURI $NamespaceURI -NodeSeparatorCharacter $NodeSeparatorCharacter
		
		if ($parentNode)
		{
			$parentNode.AppendChild($element) &gt; $null
		}
		else
		{
			throw "$parentNodePath does not exist in the xml."
		}
	}
}
</pre>
</div>

&#160;

The Get-XmlElementsTextValue function is pretty straight forward; return the value if it exists, otherwise return null.&#160; The Set-XmlElementsTextValue is a little more involved because if the element does not exist already, we need to create the new element and attach it as a child to the parent element.

Here’s an example of calling Get-XmlElementsTextValue:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:5bedc9c7-664d-47de-abee-d2955a57dbb3" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: powershell; title: ; notranslate" title="">
# Read in the file contents and return the version element's value.
[ xml ]$fileContents = Get-Content -Path $NuSpecFilePath
return Get-XmlElementsTextValue -XmlDocument $fileContents -ElementPath "package.metadata.version"
</pre>
</div>

&#160;

And an example of calling Set-XmlElementsTextValue:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:9cf4a8b5-149a-44e6-83c8-af0706d63fe7" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title="">
# Read in the file contents, update the version element's value, and save the file.
[ xml ]$fileContents = Get-Content -Path $NuSpecFilePath
Set-XmlElementsTextValue -XmlDocument $fileContents -ElementPath "package.metadata.version" -TextValue $NewVersionNumber
$fileContents.Save($NuSpecFilePath)
</pre>
</div>

&#160;

Note that these 2 functions depend on the Get-XmlNode function provided above.

<font color="#00ff00"><Update2 &#8211; January 7, 2016></font>

I have had multiple people ask me for similar functions for getting and setting an element&#8217;s Attribute value as well, so here are the corresponding functions for that:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:8b5955e6-e281-457a-8df0-b2aca9ba40d1" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title="">
function Get-XmlElementsAttributeValue([ xml ]$XmlDocument, [string]$ElementPath, [string]$AttributeName, [string]$NamespaceURI = "", [string]$NodeSeparatorCharacter = '.')
{
	# Try and get the node. 
	$node = Get-XmlNode -XmlDocument $XmlDocument -NodePath $ElementPath -NamespaceURI $NamespaceURI -NodeSeparatorCharacter $NodeSeparatorCharacter
	
	# If the node and attribute already exist, return the attribute's value, otherwise return null.
	if ($node -and $node.$AttributeName) { return $node.$AttributeName } else { return $null }
}

function Set-XmlElementsAttributeValue([ xml ]$XmlDocument, [string]$ElementPath, [string]$AttributeName, [string]$AttributeValue, [string]$NamespaceURI = "", [string]$NodeSeparatorCharacter = '.')
{
	# Try and get the node. 
	$node = Get-XmlNode -XmlDocument $XmlDocument -NodePath $ElementPath -NamespaceURI $NamespaceURI -NodeSeparatorCharacter $NodeSeparatorCharacter
	
	# If the node already exists, create/update its attribute's value.
	if ($node)
	{ 
		$attribute = $XmlDocument.CreateNode([System.Xml.XmlNodeType]::Attribute, $AttributeName, $NamespaceURI)
		$attribute.Value = $AttributeValue
		$node.Attributes.SetNamedItem($attribute) &gt; $null
	}
	# Else the node doesn't exist yet, so create it with the given attribute value.
	else
	{
		# Create the new element with the given value.
		$elementName = $ElementPath.SubString($ElementPath.LastIndexOf($NodeSeparatorCharacter) + 1)
		$element = $XmlDocument.CreateElement($elementName, $XmlDocument.DocumentElement.NamespaceURI)
		$element.SetAttribute($AttributeName, $NamespaceURI, $AttributeValue) &gt; $null
		
		# Try and get the parent node.
		$parentNodePath = $ElementPath.SubString(0, $ElementPath.LastIndexOf($NodeSeparatorCharacter))
		$parentNode = Get-XmlNode -XmlDocument $XmlDocument -NodePath $parentNodePath -NamespaceURI $NamespaceURI -NodeSeparatorCharacter $NodeSeparatorCharacter
		
		if ($parentNode)
		{
			$parentNode.AppendChild($element) &gt; $null
		}
		else
		{
			throw "$parentNodePath does not exist in the xml."
		}
	}
}
</pre>
</div>

<font color="#00ff00"></Update2></font>

<div id="scid:fb3a1972-4489-4e52-abe7-25a00bb07fdf:186577a3-bb06-480a-8b8e-c8dfe0e42e51" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <p>
    Rather than copy-pasting, you can <a href="http://dans-blog.azurewebsites.net/wp-content/uploads/2014/01/PowerShellFunctionsToGetAndSetXml.zip" target="_blank">download all of the functions shown here.</a>
  </p>
</div>

I hope you find this useful and that it saves you some time.&#160; Happy coding!