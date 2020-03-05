---
title: Create and publish your NuGet package in one click with the New-NuGetPackage PowerShell script
date: 2013-06-07T14:54:04-06:00
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

I've spent a good chunk of time investigating how NuGet.exe works and creating [a PowerShell script called New-NuGetPackage](https://newnugetpackage.codeplex.com/) to make it dirt simple to pack and push new NuGet packages.

Here's a list of some of the script's features:

- Create the .nupkg package file and optionally push the package to the NuGet Gallery (or a custom gallery).
- Can be ran from Windows Explorer (i.e. double-click it) or called via PowerShell if you want to be able to pass in specific parameters or suppress prompts.
- Can prompt user for version number and release notes (prompts are prefilled with previous version number and release notes) or can suppress all prompts.

This makes packing and pushing your NuGet packages quick and easy, whether doing it manually or integrating it into your build system. Creating NuGet packages wasn't overly complicated before, but this makes it even simpler and less tedious.

[Go to the codeplex page](https://newnugetpackage.codeplex.com/) to download the script and start automating your NuGet package creating today. The [codeplex documentation](https://newnugetpackage.codeplex.com/documentation) describes the script in much more detail, as well as step by step instructions on how to get setup to start using it.

[UPDATE] I have also used this script in a new NuGet package that will automatically create a NuGet package for your own projects without you having to do anything. [Read about it here](https://blog.danskingdom.com/automatically-create-your-projects-nuget-package-every-time-it-builds-via-nuget/). [/UPDATE]

## Additional NuGet Information

During my investigation I compiled a list of what happens when doing "nuget spec" and "nuget pack" against the various different file types (e.g. dll vs. project vs. nuspec). Someone else may find this information useful, so here it is:

```xml
Spec a Project or DLL directly (e.g. "nuget spec PathToFile"):
- Creates a partial .nuspec; still has placeholder info for some fields (e.g. Id, Dependencies).
- Creates [full file name with extension].nuspec file.
- The generated .nuspec file is meant to still be manually updated before making a package from it.

// TestProject.csproj.nuspec
<?xml version="1.0"?>
<package >
  <metadata>
    <id>C:\dev\TFS\RQ\Dev\Tools\DevOps\New-NuGetPackage\TestProject\TestProject\TestProject.csproj</id>
    <version>1.0.0</version>
    <authors>Dan Schroeder</authors>
    <owners>Dan Schroeder</owners>
    <licenseUrl>http://LICENSE_URL_HERE_OR_DELETE_THIS_LINE</licenseUrl>
    <projectUrl>http://PROJECT_URL_HERE_OR_DELETE_THIS_LINE</projectUrl>
    <iconUrl>http://ICON_URL_HERE_OR_DELETE_THIS_LINE</iconUrl>
    <requireLicenseAcceptance>false</requireLicenseAcceptance>
    <description>Package description</description>
    <releaseNotes>Summary of changes made in this release of the package.</releaseNotes>
    <copyright>Copyright 2013</copyright>
    <tags>Tag1 Tag2</tags>
    <dependencies>
      <dependency id="SampleDependency" version="1.0" />
    </dependencies>
  </metadata>
</package>
=====================================================================
Spec a DLL using "nuget spec" from the same directory:
- Creates a partial .nuspec; still has placeholder info for some fields (e.g. Id, Dependencies).
- Creates "Package.nuspec" file.
- The generated .nuspec file is meant to still be manually updated before making a package from it.

// Package.nuspec
<?xml version="1.0"?>
<package >
  <metadata>
    <id>Package</id>
    <version>1.0.0</version>
    <authors>Dan Schroeder</authors>
    <owners>Dan Schroeder</owners>
    <licenseUrl>http://LICENSE_URL_HERE_OR_DELETE_THIS_LINE</licenseUrl>
    <projectUrl>http://PROJECT_URL_HERE_OR_DELETE_THIS_LINE</projectUrl>
    <iconUrl>http://ICON_URL_HERE_OR_DELETE_THIS_LINE</iconUrl>
    <requireLicenseAcceptance>false</requireLicenseAcceptance>
    <description>Package description</description>
    <releaseNotes>Summary of changes made in this release of the package.</releaseNotes>
    <copyright>Copyright 2013</copyright>
    <tags>Tag1 Tag2</tags>
    <dependencies>
      <dependency id="SampleDependency" version="1.0" />
    </dependencies>
  </metadata>
</package>
=====================================================================
Spec a Project using "nuget spec" from the same directory:
- Creates a template .nuspec using the proper properties and dependencies pulled from the file.
- Creates [file name without extension].nuspec file.
- The generated .nuspec file can be used to pack with, assuming you are packing the Project and not the .nuspec directly.

// TestProject.nuspec
<?xml version="1.0"?>
<package >
  <metadata>
    <id>$id$</id>
    <version>$version$</version>
    <title>$title$</title>
    <authors>$author$</authors>
    <owners>$author$</owners>
    <licenseUrl>http://LICENSE_URL_HERE_OR_DELETE_THIS_LINE</licenseUrl>
    <projectUrl>http://PROJECT_URL_HERE_OR_DELETE_THIS_LINE</projectUrl>
    <iconUrl>http://ICON_URL_HERE_OR_DELETE_THIS_LINE</iconUrl>
    <requireLicenseAcceptance>false</requireLicenseAcceptance>
    <description>$description$</description>
    <releaseNotes>Summary of changes made in this release of the package.</releaseNotes>
    <copyright>Copyright 2013</copyright>
    <tags>Tag1 Tag2</tags>
  </metadata>
</package>
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
```
