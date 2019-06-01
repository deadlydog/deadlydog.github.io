---
id: 556
title: PowerShell Functions To Convert, Remove, and Delete IIS Web Applications
date: 2013-10-23T14:56:22-06:00
guid: http://dans-blog.azurewebsites.net/?p=556
permalink: /powershell-functions-to-convert-remove-and-delete-iis-web-applications/
categories:
  - IIS
  - PowerShell
tags:
  - Application
  - Convert
  - Create
  - Delete
  - IIS
  - PowerShell
  - Remove
  - Service
  - Web
---
I recently refactored some of our PowerShell scripts that we use to publish and remove IIS 7 web applications, creating some general functions that can be used anywhere. In this post I show these functions along with how I structure our scripts to make creating, removing, and deleting web applications for our various products fully automated and tidy. Note that these scripts require at least PowerShell v3.0 and use the [IIS Admin Cmdlets](http://technet.microsoft.com/en-us/library/ee790599.aspx) that I believe require IIS v7.0; the IIS Admin Cmdlet calls can easily be replaced though by calls to appcmd.exe, msdeploy, or any other tool for working with IIS that you want.

<div id="scid:fb3a1972-4489-4e52-abe7-25a00bb07fdf:591aa368-3c81-42fb-a66a-5b47ecc8ad72" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <p>
    <a href="/assets/Posts/2013/10/ApplicationServiceScripts2.zip" target="_blank">Download this article's code here.</a>
  </p>
</div>

I’ll blast you with the first file’s code and explain it below (ApplicationServiceUtilities.ps1).

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:0f688c6b-7e25-4296-ae09-fcc9ca2656b5" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title="">
# Turn on Strict Mode to help catch syntax-related errors.
# 	This must come after a script's/function's param section.
# 	Forces a function to be the first non-comment code to appear in a PowerShell Module.
Set-StrictMode -Version Latest

# Define the code block that will add the ApplicationServiceInformation class to the PowerShell session.
# NOTE: If this class is modified you will need to restart your PowerShell session to see the changes.
$AddApplicationServiceInformationTypeScriptBlock = {
    # Wrap in a try-catch in case we try to add this type twice.
    try {
    # Create a class to hold an IIS Application Service's Information.
    Add-Type -TypeDefinition "
        using System;

        public class ApplicationServiceInformation
        {
            // The name of the Website in IIS.
            public string Website { get; set;}

            // The path to the Application, relative to the Website root.
            public string ApplicationPath { get; set; }

            // The Application Pool that the application is running in.
            public string ApplicationPool { get; set; }

            // Whether this application should be published or not.
            public bool ConvertToApplication { get; set; }

            // Implicit Constructor.
            public ApplicationServiceInformation() { this.ConvertToApplication = true; }

            // Explicit constructor.
            public ApplicationServiceInformation(string website, string applicationPath, string applicationPool, bool convertToApplication = true)
            {
                this.Website = website;
                this.ApplicationPath = applicationPath;
                this.ApplicationPool = applicationPool;
                this.ConvertToApplication = convertToApplication;
            }
        }
    "
    } catch {}
}
# Add the ApplicationServiceInformation class to this PowerShell session.
& $AddApplicationServiceInformationTypeScriptBlock

<#
    .SYNOPSIS
    Converts the given files to application services on the given Server.

    .PARAMETER Server
    The Server Host Name to connect to and convert the applications on.

    .PARAMETER ApplicationServicesInfo
    The [ApplicationServiceInformation[]] containing the files to convert to application services.
#>
function ConvertTo-ApplicationServices
{
    [CmdletBinding()]
    param
    (
        [string] $Server,
        [ApplicationServiceInformation[]] $ApplicationServicesInfo
    )

    $block = {
	    param([PSCustomObject[]] $ApplicationServicesInfo)
        $VerbosePreference = $Using:VerbosePreference
	    Write-Verbose "Converting To Application Services..."

        # Import the WebAdministration module to make sure we have access to the required cmdlets and the IIS: drive.
        Import-Module WebAdministration 4> $null	# Don't write the verbose output.

	    # Create all of the Web Applications, making sure to first try and remove them in case they already exist (in order to avoid a PS error).
	    foreach ($appInfo in [PSCustomObject[]]$ApplicationServicesInfo)
        {
            $website = $appInfo.Website
            $applicationPath = $appInfo.ApplicationPath
            $applicationPool = $appInfo.ApplicationPool
		    $fullPath = Join-Path $website $applicationPath

            # If this application should not be converted, continue onto the next one in the list.
            if (!$appInfo.ConvertToApplication) { Write-Verbose "Skipping publish of '$fullPath'"; continue }

		    Write-Verbose "Checking if we need to remove '$fullPath' before converting it..."
		    if (Get-WebApplication -Site "$website" -Name "$applicationPath")
		    {
			    Write-Verbose "Removing '$fullPath'..."
			    Remove-WebApplication -Site "$website" -Name "$applicationPath"
		    }

            Write-Verbose "Converting '$fullPath' to an application with Application Pool '$applicationPool'..."
            ConvertTo-WebApplication "IIS:\Sites\$fullPath" -ApplicationPool "$applicationPool"
        }
    }

    # Connect to the host Server and run the commands directly o that computer.
    # Before we run our script block we first have to add the ApplicationServiceInformation class type into the PowerShell session.
    $session = New-PSSession -ComputerName $Server
    Invoke-Command -Session $session -ScriptBlock $AddApplicationServiceInformationTypeScriptBlock
    Invoke-Command -Session $session -ScriptBlock $block -ArgumentList (,$ApplicationServicesInfo)
    Remove-PSSession -Session $session
}

<#
    .SYNOPSIS
    Removes the given application services from the given Server.

    .PARAMETER Server
    The Server Host Name to connect to and remove the applications from.

    .PARAMETER ApplicationServicesInfo
    The [ApplicationServiceInformation[]] containing the applications to remove.
#>
function Remove-ApplicationServices
{
    [CmdletBinding()]
    param
    (
        [string] $Server,
        [ApplicationServiceInformation[]] $ApplicationServicesInfo
    )

    $block = {
	    param([ApplicationServiceInformation[]] $ApplicationServicesInfo)
        $VerbosePreference = $Using:VerbosePreference
	    Write-Verbose "Removing Application Services..."

        # Import the WebAdministration module to make sure we have access to the required cmdlets and the IIS: drive.
        Import-Module WebAdministration 4> $null	# Don't write the verbose output.

	    # Remove all of the Web Applications, making sure they exist first (in order to avoid a PS error).
	    foreach ($appInfo in [ApplicationServiceInformation[]]$ApplicationServicesInfo)
        {
            $website = $appInfo.Website
            $applicationPath = $appInfo.ApplicationPath
		    $fullPath = Join-Path $website $applicationPath

		    Write-Verbose "Checking if we need to remove '$fullPath'..."
		    if (Get-WebApplication -Site "$website" -Name "$applicationPath")
		    {
			    Write-Verbose "Removing '$fullPath'..."
			    Remove-WebApplication -Site "$website" -Name "$applicationPath"
		    }
        }
    }

    # Connect to the host Server and run the commands directly on that computer.
    # Before we run our script block we first have to add the ApplicationServiceInformation class type into the PowerShell session.
    $session = New-PSSession -ComputerName $Server
    Invoke-Command -Session $session -ScriptBlock $AddApplicationServiceInformationTypeScriptBlock
    Invoke-Command -Session $session -ScriptBlock $block -ArgumentList (,$ApplicationServicesInfo)
    Remove-PSSession -Session $session
}

<#
    .SYNOPSIS
    Removes the given application services from the given Server and deletes all associated files.

    .PARAMETER Server
    The Server Host Name to connect to and delete the applications from.

    .PARAMETER ApplicationServicesInfo
    The [ApplicationServiceInformation[]] containing the applications to delete.

    .PARAMETER OnlyDeleteIfNotConvertedToApplication
    If this switch is supplied and the application services are still running (i.e. have not been removed yet), the services will not be removed and the files will not be deleted.

    .PARAMETER DeleteEmptyParentDirectories
    If this switch is supplied, after the application services folder has been removed, it will recursively check parent folders and remove them if they are empty, until the Website root is reached.
#>
function Delete-ApplicationServices
{
    [CmdletBinding()]
    param
    (
        [string] $Server,
        [ApplicationServiceInformation[]] $ApplicationServicesInfo,
        [switch] $OnlyDeleteIfNotConvertedToApplication,
        [switch] $DeleteEmptyParentDirectories
    )

    $block = {
	    param([ApplicationServiceInformation[]] $ApplicationServicesInfo)
        $VerbosePreference = $Using:VerbosePreference
	    Write-Verbose "Deleting Application Services..."

        # Import the WebAdministration module to make sure we have access to the required cmdlets and the IIS: drive.
        Import-Module WebAdministration 4> $null	# Don't write the verbose output.

	    # Remove all of the Web Applications and delete their files from disk.
	    foreach ($appInfo in [ApplicationServiceInformation[]]$ApplicationServicesInfo)
        {
            $website = $appInfo.Website
            $applicationPath = $appInfo.ApplicationPath
		    $fullPath = Join-Path $website $applicationPath
            $iisSitesDirectory = "IIS:\Sites\"

		    Write-Verbose "Checking if we need to remove '$fullPath'..."
		    if (Get-WebApplication -Site "$website" -Name "$applicationPath")
		    {
                # If we should only delete the files they're not currently running as a Web Application, continue on to the next one in the list.
                if ($Using:OnlyDeleteIfNotConvertedToApplication) { Write-Verbose "'$fullPath' is still running as a Web Application, so its files will not be deleted."; continue }

			    Write-Verbose "Removing '$fullPath'..."
			    Remove-WebApplication -Site "$website" -Name "$applicationPath"
		    }

            Write-Verbose "Deleting the directory '$fullPath'..."
            Remove-Item -Path "$iisSitesDirectory$fullPath" -Recurse -Force

            # If we should delete empty parent directories of this application.
            if ($Using:DeleteEmptyParentDirectories)
            {
                Write-Verbose "Deleting empty parent directories..."
                $parent = Split-Path -Path $fullPath -Parent

                # Only delete the parent directory if it is not the Website directory, and it is empty.
                while (($parent -ne $website) -and (Test-Path -Path "$iisSitesDirectory$parent") -and ((Get-ChildItem -Path "$iisSitesDirectory$parent") -eq $null))
                {
                    $path = $parent
                    Write-Verbose "Deleting empty parent directory '$path'..."
                    Remove-Item -Path "$iisSitesDirectory$path" -Force
                    $parent = Split-Path -Path $path -Parent
                }
            }
        }
    }

    # Connect to the host Server and run the commands directly on that computer.
    # Before we run our script block we first have to add the ApplicationServiceInformation class type into the PowerShell session.
    $session = New-PSSession -ComputerName $Server
    Invoke-Command -Session $session -ScriptBlock $AddApplicationServiceInformationTypeScriptBlock
    Invoke-Command -Session $session -ScriptBlock $block -ArgumentList (,$ApplicationServicesInfo)
    Remove-PSSession -Session $session
}
</pre>
</div>

This first file contains all of the meat. At the top it declares (in C#) the **ApplicationServiceInformation** class that is used to hold the information about a web application; mainly the Website that the application should go in, the ApplicationPath (where within the website the application should be created), and the Application Pool that the application should run under. Notice that the $AddApplicationServiceInformationTypeScriptBlock script block is executed right below where it is declared, in order to actually import the ApplicationServiceInformation class type into the current PowerShell session.

There is one extra property on this class that I found I needed, but you may be able to ignore; that is the ConvertToApplication boolean. This is inspected by our ConvertTo-ApplicationServices function to tell it whether the application should actually be published or not. I required this field because we have some web services that should only be "converted to applications" in specific environments (or only on a developers local machine), but whose files we still want to delete when using the Delete-ApplicationServices function. While I could just create 2 separate lists of ApplicationServiceInformation objects depending on which function I was calling (see below), I decided to instead just include this one extra property.

Below the class declaration are our functions to perform the actual work:

  * ConvertTo-ApplicationServices: Converts the files to an application using the ConvertTo-WebApplication cmdlet.
  * Remove-ApplicationServices: Converts the application back to regular files using the Remove-WebApplication cmdlet.
  * Delete-ApplicationServices: First removes any applications, and then deletes the files from disk.

The Delete-ApplicationServices function includes an couple additional switches. The **$OnlyDeleteIfNotConvertedToApplication** switch can be used as a bit of a safety net to ensure that you only delete files for application services that are not currently running as a web application (i.e. the web application has already been removed). If this switch is omitted, the web application will be removed and the files deleted. The **$DeleteEmptyParentDirectories** switch that may be used to remove parent directories once the application files have been deleted. This is useful for us because we version our services, so they are all placed in a directory corresponding to a version number. e.g. \Website\[VersionNumber]\App1 and \Website\[VersionNumber]\App2. This switch allows the [VersionNumber] directory to be deleted automatically once the App1 and App2 directories have been deleted.

Note that I don’t have a function to copy files to the server (i.e. publish them); I assume that the files have already been copied to the server, as we currently have this as a separate step in our deployment process.

My 2nd file (ApplicationServiceLibrary.ps1) is optional and is really just a collection of functions used to return the ApplicationServiceInformation instances that I require as an array, depending on which projects I want to convert/remove/delete.

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:02e2763d-e5bb-4db8-80a9-68dafada546e" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; title: ; notranslate" title="">
# Get the directory that this script is in.
$THIS_SCRIPTS_DIRECTORY = Split-Path $script:MyInvocation.MyCommand.Path

# Include the required ApplicationServiceInformation type.
. (Join-Path $THIS_SCRIPTS_DIRECTORY ApplicationServiceUtilities.ps1)

#=================================
# Replace all of the functions below with your own.
# These are provided as examples.
#=================================

function Get-AllApplicationServiceInformation([string] $Release)
{
    [ApplicationServiceInformation[]] $appServiceInfo = @()

    $appServiceInfo += Get-RqApplicationServiceInformation -Release $Release
    $appServiceInfo += Get-PublicApiApplicationServiceInformation -Release $Release
    $appServiceInfo += Get-IntraApplicationServiceInformation -Release $Release

    return $appServiceInfo
}

function Get-RqApplicationServiceInformation([string] $Release)
{
    return [ApplicationServiceInformation[]] @(
	    (New-Object ApplicationServiceInformation -Property @{Website = "Application Services"; ApplicationPath = "$Release/Core.Reporting.Services"; ApplicationPool = "RQ Services .NET4"}),
	    (New-Object ApplicationServiceInformation -Property @{Website = "Application Services"; ApplicationPath = "$Release/Core.Services"; ApplicationPool = "RQ Core Services .NET4"}),
	    (New-Object ApplicationServiceInformation -Property @{Website = "Application Services"; ApplicationPath = "$Release/DeskIntegration.Services"; ApplicationPool = "RQ Services .NET4"}),
	    (New-Object ApplicationServiceInformation -Property @{Website = "Application Services"; ApplicationPath = "$Release/Retail.Integration.Services"; ApplicationPool = "RQ Services .NET4"}),

        # Simulator Services that are only for Dev; we don't want to convert them to an application, but do want to remove their files that got copied to the web server.
        (New-Object ApplicationServiceInformation -Property @{Website = "Application Services"; ApplicationPath = "$Release/Simulator.Services"; ApplicationPool = "Simulator Services .NET4"; ConvertToApplication = $false}))
}

function Get-PublicApiApplicationServiceInformation([string] $Release)
{
    return [ApplicationServiceInformation[]] @(
        (New-Object ApplicationServiceInformation -Property @{Website = "API Services"; ApplicationPath = "$Release/PublicAPI.Host"; ApplicationPool = "API Services .NET4"}),
	    (New-Object ApplicationServiceInformation -Property @{Website = "API Services"; ApplicationPath = "$Release/PublicAPI.Documentation"; ApplicationPool = "API Services .NET4"}))
}

function Get-IntraApplicationServiceInformation([string] $Release)
{
    return [ApplicationServiceInformation[]] @(
        (New-Object ApplicationServiceInformation -Property @{Website = "Intra Services"; ApplicationPath = "$Release"; ApplicationPool = "Intra Services .NET4"}))
}
</pre>
</div>

You can see the first thing it does is dot source the ApplicationServiceUtilities.ps1 file (<u>I assume all these scripts are in the same directory</u>). This is done in order to include the ApplicationServiceInformation type into the PowerShell session. Next I just have functions that return the various application service information that our various projects specify. I break them apart by project so that I’m able to easily publish one project separately from another, but also have a Get-All function that returns back all of the service information for when we deploy all services together. We deploy many of our projects in lock-step, so having a Get-All function makes sense for us, but it may not for you. We have many more projects and services than I show here; I just show these as an example of how you can set yours up if you choose.

One other thing you may notice is that my Get-*ApplicationServiceInformation functions take a $Release parameter that is used in the ApplicationPath; this is because our services are versioned. Yours may not be though, in which case you can omit that parameter for your Get functions (or add any additional parameters that you do need).

Lastly, to make things nice and easy, I create ConvertTo, Remove, and Delete scripts for each of our projects, as well as a scripts to do all of the projects at once. Here’s an example of what one of these scripts would look like:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:670e59ec-5546-48f0-bbb6-f0b678f78e40" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; title: ; notranslate" title="">
param
(
	[parameter(Position=0,Mandatory=$true,HelpMessage="The 3 hex-value version number of the release (x.x.x).")]
	[ValidatePattern("^\d{1,5}\.\d{1,5}\.\d{1,5}$")]
	[string] $Release
)

# Get the directory that this script is in.
$THIS_SCRIPTS_DIRECTORY = Split-Path $script:MyInvocation.MyCommand.Path

# Include the functions used to perform the actual operations.
. (Join-Path $THIS_SCRIPTS_DIRECTORY ApplicationServiceLibrary.ps1)

ConvertTo-ApplicationServices -Server "Our.WebServer.local" -ApplicationServicesInfo (Get-RqApplicationServiceInformation -Release $Release) -Verbose
</pre>
</div>

The first thing it does is prompt for the $Release version number; again, if you don’t version your services then you can omit that.

The next thing it does is dot-source the ApplicationServicesLibrary.ps1 script to make all of the Get-*ApplicationServiceInformation functions that we defined in the previous file available. I prefer to use the ApplicationServicesLibrary.ps1 file to place all of our services in a common place, and to avoid copy/pasting the ApplicationServiceInformation for each project into each Convert/Remove/Delete script; but that’s my personal choice and if you prefer to copy-paste the code into a few different files instead of having a central library file, go hard. If you omit the Library script though, then you will instead need to dot-source the ApplicationServiceUtilities.ps1 file here, since our Library script currently dot-sources it in for us.

The final line is the one that actually calls our utility function to perform the operation. It provides the web server hostname to connect to, and calls the library’s Get-*ApplicationServiceInformation to retrieve the information for the web applications that should be created. Notice too that it also provides the –Verbose switch. Some of the IIS operations can take quite a while to run and don’t generate any output, so I like to see the verbose output so I can gauge the progress of the script, but feel free to omit it.

So this sample script creates all of the web applications for our Rq product and can be ran very easily. To make the corresponding Remove and Delete scripts, I would just copy this file and replace "ConvertTo-" with "Remove-" and "Delete-" respectively. This allows you to have separate scripts for creating and removing each of your products that can easily be ran automatically or manually, fully automating the process of creating and removing your web applications in IIS.

If I need to remove the services for a bunch of versions, here is an example of how I can just create a quick script that calls my Remove Services script for each version that needs to be removed:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:1fefa83a-f405-447b-9621-6aa81b09d20f" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; title: ; notranslate" title="">
# Get the directory that this script is in.
$thisScriptsDirectory = Split-Path $script:MyInvocation.MyCommand.Path

# Remove Rq application services for versions 4.11.33 to 4.11.43.
$majorMinorVersion = "4.11"
33..43 | foreach {
    $Release = "$majorMinorVersion.$_"
    Write-Host "Removing Rq '$Release' services..."
    & "$thisScriptsDirectory\Remove-RqServices.ps1" $Release
}
</pre>
</div>

If you have any questions or suggestions feel free to leave a comment. I hope you find this useful.

Happy coding!
