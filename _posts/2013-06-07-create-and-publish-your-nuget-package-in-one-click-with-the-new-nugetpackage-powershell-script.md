---
id: 325
title: Create and publish your NuGet package in one click with the New-NuGetPackage PowerShell script
date: 2013-06-07T14:54:04-06:00
guid: http://dans-blog.azurewebsites.net/?p=325
permalink: /create-and-publish-your-nuget-package-in-one-click-with-the-new-nugetpackage-powershell-script/
categories:
  - NuGet
  - PowerShell
tags:
  - assembly
  - Create
  - dll
  - New
  - NuGet
  - pack
  - Package
  - PowerShell
  - project
  - script
  - spec
---
I’ve spent a good chunk of time investigating how nuget.exe works and creating [a PowerShell script called New-NuGetPackage](https://newnugetpackage.codeplex.com/) to make it dirt simple to pack and push new NuGet packages.

Here’s a list of some of the script’s features:

  * Create the .nupkg package file and optionally push the package to the NuGet Gallery (or a custom gallery).
  * Can be ran from Windows Explorer (i.e. double-click it) or called via PowerShell if you want to be able to pass in specific parameters or suppress prompts.
  * Can prompt user for version number and release notes (prompts are prefilled with previous version number and release notes) or can suppress all prompts.

This makes packing and pushing your NuGet packages quick and easy, whether doing it manually or integrating it into your build system. Creating NuGet packages wasn’t overly complicated before, but this makes it even simpler and less tedious.

[Go to the codeplex page](https://newnugetpackage.codeplex.com/) to download the script and start automating your NuGet package creating today. The [codeplex documentation](https://newnugetpackage.codeplex.com/documentation) describes the script in much more detail, as well as step by step instructions on how to get setup to start using it.

[UPDATE] I have also used this script in a new NuGet package that will automatically create a NuGet package for your own projects without you having to do anything. [Read about it here](http://dans-blog.azurewebsites.net/automatically-create-your-projects-nuget-package-every-time-it-builds-via-nuget/). [/UPDATE]



## Additional NuGet Information

During my investigation I compiled a list of what happens when doing “nuget spec” and “nuget pack” against the various different file types (e.g. dll vs. project vs. nuspec). Someone else may find this information useful, so here it is:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:d1994009-f7ca-4489-a2ad-b8857adc884d" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: plain; pad-line-numbers: true; title: ; notranslate" title="">
Spec a Project or DLL directly (e.g. "nuget spec PathToFile"):
- Creates a partial .nuspec; still has placeholder info for some fields (e.g. Id, Dependencies).
- Creates [full file name with extension].nuspec file.
- The generated .nuspec file is meant to still be manually updated before making a package from it.

// TestProject.csproj.nuspec
&lt;?xml version="1.0"?&gt;
&lt;package &gt;
  &lt;metadata&gt;
    &lt;id&gt;C:\dev\TFS\RQ\Dev\Tools\DevOps\New-NuGetPackage\TestProject\TestProject\TestProject.csproj&lt;/id&gt;
    &lt;version&gt;1.0.0&lt;/version&gt;
    &lt;authors&gt;Dan Schroeder&lt;/authors&gt;
    &lt;owners&gt;Dan Schroeder&lt;/owners&gt;
    &lt;licenseUrl&gt;http://LICENSE_URL_HERE_OR_DELETE_THIS_LINE&lt;/licenseUrl&gt;
    &lt;projectUrl&gt;http://PROJECT_URL_HERE_OR_DELETE_THIS_LINE&lt;/projectUrl&gt;
    &lt;iconUrl&gt;http://ICON_URL_HERE_OR_DELETE_THIS_LINE&lt;/iconUrl&gt;
    &lt;requireLicenseAcceptance&gt;false&lt;/requireLicenseAcceptance&gt;
    &lt;description&gt;Package description&lt;/description&gt;
    &lt;releaseNotes&gt;Summary of changes made in this release of the package.&lt;/releaseNotes&gt;
    &lt;copyright&gt;Copyright 2013&lt;/copyright&gt;
    &lt;tags&gt;Tag1 Tag2&lt;/tags&gt;
    &lt;dependencies&gt;
      &lt;dependency id="SampleDependency" version="1.0" /&gt;
    &lt;/dependencies&gt;
  &lt;/metadata&gt;
&lt;/package&gt;
=====================================================================
Spec a DLL using "nuget spec" from the same directory:
- Creates a partial .nuspec; still has placeholder info for some fields (e.g. Id, Dependencies).
- Creates "Package.nuspec" file.
- The generated .nuspec file is meant to still be manually updated before making a package from it.

// Package.nuspec
&lt;?xml version="1.0"?&gt;
&lt;package &gt;
  &lt;metadata&gt;
    &lt;id&gt;Package&lt;/id&gt;
    &lt;version&gt;1.0.0&lt;/version&gt;
    &lt;authors&gt;Dan Schroeder&lt;/authors&gt;
    &lt;owners&gt;Dan Schroeder&lt;/owners&gt;
    &lt;licenseUrl&gt;http://LICENSE_URL_HERE_OR_DELETE_THIS_LINE&lt;/licenseUrl&gt;
    &lt;projectUrl&gt;http://PROJECT_URL_HERE_OR_DELETE_THIS_LINE&lt;/projectUrl&gt;
    &lt;iconUrl&gt;http://ICON_URL_HERE_OR_DELETE_THIS_LINE&lt;/iconUrl&gt;
    &lt;requireLicenseAcceptance&gt;false&lt;/requireLicenseAcceptance&gt;
    &lt;description&gt;Package description&lt;/description&gt;
    &lt;releaseNotes&gt;Summary of changes made in this release of the package.&lt;/releaseNotes&gt;
    &lt;copyright&gt;Copyright 2013&lt;/copyright&gt;
    &lt;tags&gt;Tag1 Tag2&lt;/tags&gt;
    &lt;dependencies&gt;
      &lt;dependency id="SampleDependency" version="1.0" /&gt;
    &lt;/dependencies&gt;
  &lt;/metadata&gt;
&lt;/package&gt;
=====================================================================
Spec a Project using "nuget spec" from the same directory:
- Creates a template .nuspec using the proper properties and dependencies pulled from the file.
- Creates [file name without extension].nuspec file.
- The generated .nuspec file can be used to pack with, assuming you are packing the Project and not the .nuspec directly.

// TestProject.nuspec
&lt;?xml version="1.0"?&gt;
&lt;package &gt;
  &lt;metadata&gt;
    &lt;id&gt;$id$&lt;/id&gt;
    &lt;version&gt;$version$&lt;/version&gt;
    &lt;title&gt;$title$&lt;/title&gt;
    &lt;authors&gt;$author$&lt;/authors&gt;
    &lt;owners&gt;$author$&lt;/owners&gt;
    &lt;licenseUrl&gt;http://LICENSE_URL_HERE_OR_DELETE_THIS_LINE&lt;/licenseUrl&gt;
    &lt;projectUrl&gt;http://PROJECT_URL_HERE_OR_DELETE_THIS_LINE&lt;/projectUrl&gt;
    &lt;iconUrl&gt;http://ICON_URL_HERE_OR_DELETE_THIS_LINE&lt;/iconUrl&gt;
    &lt;requireLicenseAcceptance&gt;false&lt;/requireLicenseAcceptance&gt;
    &lt;description&gt;$description$&lt;/description&gt;
    &lt;releaseNotes&gt;Summary of changes made in this release of the package.&lt;/releaseNotes&gt;
    &lt;copyright&gt;Copyright 2013&lt;/copyright&gt;
    &lt;tags&gt;Tag1 Tag2&lt;/tags&gt;
  &lt;/metadata&gt;
&lt;/package&gt;
=====================================================================
Pack a Project (without accompanying template .nuspec):
- Does not generate a .nuspec file; just creates the .nupkg file with proper properties and dependencies pulled from project file.
- Throws warnings about any missing data in the project file (e.g. Description, Author), but still generates the package.

=====================================================================
Pack a Project (with accompanying template .nuspec):
- Expects the [file name without extension].nuspec file to exist in same directory as project file, otherwise it doesn't use a .nuspec file for the packing.
- Throws errors about any missing data in the project file if the .nuspec uses tokens (e.g. $description$, $author$) and these aren't defined in the project, so the package is not generated.

=====================================================================
Cannot pack a .dll directly

=====================================================================
Pack a .nuspec:
- Creates the .nupkg file with properties and dependencies defined in .nuspec file.
- .nuspec file cannot have any placeholder values (e.g. $id$, $version$).

</pre>
</div>
