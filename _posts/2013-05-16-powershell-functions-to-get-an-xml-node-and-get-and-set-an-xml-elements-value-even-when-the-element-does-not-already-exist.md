---
id: 281
title: Powershell functions to get an xml node, and get and set an xml element&rsquo;s value, even when the element does not already exist
date: 2013-05-16T17:16:57-06:00
last_modified_at: 2017-01-07T00:00:00-00:00
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

I'm new to working with Xml through PowerShell and was so impressed when I discovered how easy it was to read an xml element's value. I'm working with reading/writing .nuspec files for working with NuGet. Here's a sample xml of a .nuspec xml file:

```xml
<?xml version="1.0" encoding="utf-8"?>
<package xmlns="http://schemas.microsoft.com/packaging/2010/07/nuspec.xsd">
  <metadata>
    <id>MyAppsId</id>
    <version>1.0.1</version>
    <title>MyApp</title>
    <authors>Daniel Schroeder</authors>
    <owners>Daniel Schroeder</owners>
    <requireLicenseAcceptance>false</requireLicenseAcceptance>
    <description>My App.</description>
    <summary>My App.</summary>
    <tags>Powershell, Application</tags>
  </metadata>
  <files>
    <file src="MyApp.ps1" target="content\MyApp.ps1" />
  </files>
</package>
```

In PowerShell if I want to get the version element's value, I can just do:

```powershell
# Read in the file contents and return the version node's value.
[ xml ]$fileContents = Get-Content -Path $NuSpecFilePath
return $fileContents.package.metadata.version
```

Wow, that's super easy. And if I want to update that version number, I can just do:

```powershell
# Read in the file contents, update the version node's value, and save the file.
[ xml ] $fileContents = Get-Content -Path $NuSpecFilePath
$fileContents.package.metadata.version = $NewVersionNumber
$fileContents.Save($NuSpecFilePath)
```

Holy smokes. So simple it blows my mind. So everything is great, right? Well, it is until you try and read or write to an element that doesn't exist. If the `<version>` element is not in the xml, when I try and read from it or write to it, I get an error such as "Error: Property 'version' cannot be found on this object. Make sure that it exists.". You would think that checking if an element exists would be straight-forward and easy right? Well, it almost is. There's a [SelectSingleNode() function](http://msdn.microsoft.com/en-us/library/system.xml.xmlnode.selectsinglenode.aspx) that we can use to look for the element, but what I realized after a couple hours of banging my head on the wall and [stumbling across this stack overflow post](http://stackoverflow.com/questions/1766254/selectsinglenode-always-returns-null), is that in order for this function to work properly, you really need to use the overloaded method that also takes an XmlNamespaceManager; otherwise the SelectSingleNode() function always returns null.

So basically you need an extra 2 lines in order to setup an XmlNamespaceManager every time you need to look for a node. This is a little painful, so instead I created this function that will get you the node if it exists, and return $null if it doesn't:

```powershell
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
```

And you would call this function like so:

```powershell
# Read in the file contents and return the version node's value.
[ xml ]$fileContents = Get-Content -Path $NuSpecFilePath
$node = Get-XmlNode -XmlDocument $fileContents -NodePath "package.metadata.version"
if ($node -eq $null) { return $null }
return $fileContents.package.metadata.version
```

So if the node doesn't exist (i.e. is $null), I return $null instead of trying to access the non-existent element.

So by default this Get-XmlNode function uses the xml's root namespace, which is what we want 95% of the time. It also takes a NodeSeparatorCharacter that defaults to a period. While Googling for answers I saw that many people use the the syntax "$fileContents/package/metadata/version" instead of "$fileContents.package.metadata.version". I prefer the dot notation, but for those who like the slash just override the NodeSeparatorCharacter with a slash.

---

### Update 1

Later I found that I also wanted the ability to return back multiple xml nodes; that is, if multiple "version" elements were defined I wanted to get them all, not just the first one. This is simple; instead of using .SelectSingleNode() we can use .SelectNodes(). In order to avoid duplicating code, I broke the code to get the Xml Namespace Manager and Fully Qualified Node Path out into their own functions. Here is the rewritten code, with the new Get-XmlNodes function:

```powershell
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
```

Note the comma in the return statement of the Get-XmlNamespaceManager function. It took me a while to discover [why things broke without it](http://stackoverflow.com/questions/17498320/powershell-changes-return-objects-type).

---

So once I had this, I decided that I might as well make functions for easily getting and setting the text values of an xml element, which is what is provided here:

```powershell
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
        $element.AppendChild($textNode) > $null

        # Try and get the parent node.
        $parentNodePath = $ElementPath.SubString(0, $ElementPath.LastIndexOf($NodeSeparatorCharacter))
        $parentNode = Get-XmlNode -XmlDocument $XmlDocument -NodePath $parentNodePath -NamespaceURI $NamespaceURI -NodeSeparatorCharacter $NodeSeparatorCharacter

        if ($parentNode)
        {
            $parentNode.AppendChild($element) > $null
        }
        else
        {
            throw "$parentNodePath does not exist in the xml."
        }
    }
}
```

The Get-XmlElementsTextValue function is pretty straight forward; return the value if it exists, otherwise return null. The Set-XmlElementsTextValue is a little more involved because if the element does not exist already, we need to create the new element and attach it as a child to the parent element.

Here's an example of calling Get-XmlElementsTextValue:

```powershell
# Read in the file contents and return the version element's value.
[ xml ]$fileContents = Get-Content -Path $NuSpecFilePath
return Get-XmlElementsTextValue -XmlDocument $fileContents -ElementPath "package.metadata.version"
```

And an example of calling Set-XmlElementsTextValue:

```powershell
# Read in the file contents, update the version element's value, and save the file.
[ xml ]$fileContents = Get-Content -Path $NuSpecFilePath
Set-XmlElementsTextValue -XmlDocument $fileContents -ElementPath "package.metadata.version" -TextValue $NewVersionNumber
$fileContents.Save($NuSpecFilePath)
```

Note that these 2 functions depend on the Get-XmlNode function provided above.

---

### Update 2 - January 7, 2016

I have had multiple people ask me for similar functions for getting and setting an element's Attribute value as well, so here are the corresponding functions for that:

```powershell
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
        $node.Attributes.SetNamedItem($attribute) > $null
    }
    # Else the node doesn't exist yet, so create it with the given attribute value.
    else
    {
        # Create the new element with the given value.
        $elementName = $ElementPath.SubString($ElementPath.LastIndexOf($NodeSeparatorCharacter) + 1)
        $element = $XmlDocument.CreateElement($elementName, $XmlDocument.DocumentElement.NamespaceURI)
        $element.SetAttribute($AttributeName, $NamespaceURI, $AttributeValue) > $null

        # Try and get the parent node.
        $parentNodePath = $ElementPath.SubString(0, $ElementPath.LastIndexOf($NodeSeparatorCharacter))
        $parentNode = Get-XmlNode -XmlDocument $XmlDocument -NodePath $parentNodePath -NamespaceURI $NamespaceURI -NodeSeparatorCharacter $NodeSeparatorCharacter

        if ($parentNode)
        {
            $parentNode.AppendChild($element) > $null
        }
        else
        {
            throw "$parentNodePath does not exist in the xml."
        }
    }
}
```

---

Rather than copy-pasting, you can [download all of the functions shown here](/assets/Posts/2014/01/PowerShellFunctionsToGetAndSetXml.zip).

I hope you find this useful and that it saves you some time. Happy coding!
