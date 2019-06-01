---
id: 474
title: Have Your NuGet Package Install Itself As A Development Dependency
date: 2013-09-18T17:30:26-06:00
guid: http://dans-blog.azurewebsites.net/?p=474
permalink: /have-your-nuget-package-install-itself-as-a-development-dependency/
categories:
  - NuGet
  - PowerShell
tags:
  - Dependency
  - developmentDependency
  - Install.ps1
  - NuGet
  - nuspec
  - Package
  - PowerShell
---
### The Problem

I recently [blogged about](http://dans-blog.azurewebsites.net/automatically-create-your-projects-nuget-package-every-time-it-builds-via-nuget/) a [NuGet package I made](https://www.nuget.org/packages/CreateNewNuGetPackageFromProjectAfterEachBuild/) that allows you to easily turn your own projects into a NuGet package, making it easy to share your work with the world.  One problem I ran into with this was that if somebody used my NuGet package to create their package, their NuGet package listed my NuGet package as a dependency.  This meant that when they distributed their package to others, it would install both their package and mine.  Obviously this is undesirable, since their library has no dependency on my package; my package was meant purely to help them with the development process.

Unfortunately there wasn’t much I could do about this; that is, until the release of [NuGet 2.7](https://nuget.codeplex.com/) which came out a few weeks ago.  You can see from [the release notes](http://blog.nuget.org/20130814/nuget-2.7-release-candidate.html) that they added a new **developmentDependency** attribute that can be used.  This made things a bit better because it allowed users who installed my package to go into their project’s **packages.config** file, find the element corresponding to my package, and add the _developmentDependency=”true”_ attribute to it.

So this was better, but still kinda sucked because it required users to do this step manually, and most of them likely aren’t even aware of the problem or that there was a fix for it.  When users (and myself) install a package they want it to just work; which is why I created a fix for this.

&nbsp;

### The Fix

**Update** - As of NuGet 2.8 there is a built-in way to do the fix below. [See this post for more info](http://stackoverflow.com/a/24216882/602585).

The nice thing about NuGet packages is that you can define PowerShell scripts that can run when users install and uninstall your packages, as is [documented near the bottom of this page](http://docs.nuget.org/docs/creating-packages/creating-and-publishing-a-package).  I’ve created a PowerShell script that will automatically go in and adjust the project’s packages.config file to mark your package as a development dependency.  This means there is no extra work for the user to do.

The first thing you need to do (if you haven’t already) is include an **Install.ps1** script in your NuGet package’s .nuspec file.  If you don’t currently use a .nuspec file, check out [this page for more information](http://docs.nuget.org/docs/creating-packages/creating-and-publishing-a-package).  I also include a sample .nuspec file at the end of this post for reference.  The line to add to your .nuspec file will look something like this:

> <file src=&#8221;NuGetFiles\Install.ps1&#8243; target=&#8221;tools\Install.ps1&#8243; />

and then the contents of Install.ps1 should look like this:

<div class="wlWriterEditableSmartContent" id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:3ef21848-5642-4285-83ad-0b5c23cbca0c" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title="">
param($installPath, $toolsPath, $package, $project)

# Edits the project's packages.config file to make sure the reference to the given package uses the developmentDependency="true" attribute.
function Set-PackageToBeDevelopmentDependency($PackageId, $ProjectDirectoryPath)
{
    function Get-XmlNamespaceManager($XmlDocument, [string]$NamespaceURI = "")
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

    function Get-XmlNodes($XmlDocument, [string]$NodePath, [string]$NamespaceURI = "", [string]$NodeSeparatorCharacter = '.')
    {
	    $xmlNsManager = Get-XmlNamespaceManager -XmlDocument $XmlDocument -NamespaceURI $NamespaceURI
	    [string]$fullyQualifiedNodePath = Get-FullyQualifiedXmlNodePath -NodePath $NodePath -NodeSeparatorCharacter $NodeSeparatorCharacter

	    # Try and get the nodes, then return them. Returns $null if no nodes were found.
	    $nodes = $XmlDocument.SelectNodes($fullyQualifiedNodePath, $xmlNsManager)
	    return $nodes
    }

    # Get the path to the project's packages.config file.
    Write-Debug "Project directory is '$ProjectDirectoryPath'."
    $packagesConfigFilePath = Join-Path $ProjectDirectoryPath "packages.config"

    # If we found the packages.config file, try and update it.
    if (Test-Path -Path $packagesConfigFilePath)
    {
        Write-Debug "Found packages.config file at '$packagesConfigFilePath'."

        # Load the packages.config xml document and grab all of the <package> elements.
        $xmlFile = New-Object System.Xml.XmlDocument
        $xmlFile.Load($packagesConfigFilePath)
        $packageElements = Get-XmlNodes -XmlDocument $xmlFile -NodePath "packages.package"

        Write-Debug "Packages.config contents before modification are:`n$($xmlFile.InnerXml)"

        if (!($packageElements))
        {
            Write-Debug "Could not find any <package> elements in the packages.config xml file '$packagesConfigFilePath'."
            return
        }

        # Add the developmentDependency attribute to the NuGet package's entry.
        $packageElements | Where-Object { $_.id -eq $PackageId } | ForEach-Object { $_.SetAttribute("developmentDependency", "true") }

        # Save the packages.config file back now that we've changed it.
        $xmlFile.Save($packagesConfigFilePath)
    }
    # Else we coudn't find the packages.config file for some reason, so error out.
    else
    {
        Write-Debug "Could not find packages.config file at '$packagesConfigFilePath'."
    }
}

# Set this NuGet Package to be installed as a Development Dependency.
Set-PackageToBeDevelopmentDependency -PackageId $package.Id -ProjectDirectoryPath ([System.IO.Directory]::GetParent($project.FullName))
</pre>
</div>

<div class="wlWriterEditableSmartContent" id="scid:fb3a1972-4489-4e52-abe7-25a00bb07fdf:c59cbb6d-6406-432e-8277-d43f188acaeb" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <p>
    <a href="/assets/Posts/2013/09/Install.zip" target="_blank">Download File</a>
  </p>
</div>

And that’s it.  Basically this script will be ran after your package is installed, and it will parse the project’s packages.config xml file looking for the element with your package’s ID, and then it will add the developmentDependency=”true” attribute to that element.  And of course, if you want to add more code to the end of the file to do additional work, go ahead.

So now your users won’t have to manually edit their packages.config file, and your user’s users won’t have additional, unnecessary dependencies installed.

&nbsp;

### More Info

As promised, here is a sample .nuspec file for those of you that are not familiar with them and what they should look like.  This is actually the .nuspec file I use for my package mentioned at the start of this post.  You can see that I include the Install.ps1 file near the bottom of the file.

<div class="wlWriterEditableSmartContent" id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:79e24873-4c96-40f7-8bed-e1937aba585e" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre class="brush: xml; title: ; notranslate" title="">
<?xml version="1.0" encoding="utf-8"?>
<package xmlns="http://schemas.microsoft.com/packaging/2011/08/nuspec.xsd">
  <metadata>
    <id>CreateNewNuGetPackageFromProjectAfterEachBuild</id>
    <version>1.4.2</version>
    <title>Create New NuGet Package From Project After Each Build</title>
    <authors>Daniel Schroeder,iQmetrix</authors>
    <owners>Daniel Schroeder,iQmetrix</owners>
    <licenseUrl>https://newnugetpackage.codeplex.com/license</licenseUrl>
    <projectUrl>https://newnugetpackage.codeplex.com/wikipage?title=NuGet%20Package%20To%20Create%20A%20NuGet%20Package%20From%20Your%20Project%20After%20Every%20Build</projectUrl>
    <requireLicenseAcceptance>false</requireLicenseAcceptance>
    <description>Automatically creates a NuGet package from your project each time it builds. The NuGet package is placed in the project's output directory.
	If you want to use a .nuspec file, place it in the same directory as the project's project file (e.g. .csproj, .vbproj, .fsproj).
	This adds a PostBuildScripts folder to your project to house the PowerShell script that is called from the project's Post-Build event to create the NuGet package.
	If it does not seem to be working, check the Output window for any errors that may have occurred.</description>
    <summary>Automatically creates a NuGet package from your project each time it builds.</summary>
    <releaseNotes>Updated to use latest version of New-NuGetPackage.ps1.</releaseNotes>
    <copyright>Daniel Schroeder 2013</copyright>
    <tags>Auto Automatic Automatically Build Pack Create New NuGet Package From Project After Each Build On PowerShell Power Shell .nupkg new nuget package NewNuGetPackage New-NuGetPackage</tags>
  </metadata>
  <files>
    <file src="..\New-NuGetPackage.ps1" target="content\PostBuildScripts\New-NuGetPackage.ps1" />
    <file src="Content\NuGet.exe" target="content\PostBuildScripts\NuGet.exe" />
    <file src="Content\BuildNewPackage-RanAutomatically.ps1" target="content\PostBuildScripts\BuildNewPackage-RanAutomatically.ps1" />
    <file src="Content\UploadPackage-RunManually.ps1" target="content\PostBuildScripts\UploadPackage-RunManually.ps1" />
    <file src="Content\UploadPackage-RunManually.bat" target="content\PostBuildScripts\UploadPackage-RunManually.bat" />
    <file src="tools\Install.ps1" target="tools\Install.ps1" />
    <file src="tools\Uninstall.ps1" target="tools\Uninstall.ps1" />
  </files>
</package>
</pre>
</div>

&nbsp;

Happy coding!
