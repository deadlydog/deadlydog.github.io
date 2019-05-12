---
id: 82
title: 'Force ClickOnce applications to automatically update without prompting user &#8211; Automatically update MinimumRequiredVersion using PowerShell'
date: 2012-08-15T13:28:00-06:00
guid: https://deadlydog.wordpress.com/?p=82
permalink: /force-clickonce-applications-to-automatically-update-without-prompting-user-automatically-update-minimumrequiredversion-using-powershell/
jabber_published:
  - "1353350952"
categories:
  - ClickOnce
  - PowerShell
tags:
  - Automate
  - ClickOnce
  - PowerShell
  - visual studio
---
Today I was thinking about using a ClickOnce application in my build process.  The problem is, when using an installed ClickOnce application (as opposed to an online one) if an update to the ClickOnce application is published, the application prompts the user to Accept or Skip downloading and applying the new update.  This would cause a problem for my automated builds as it would end up waiting forever for a user to click Accept.  [This post lead me to the answer](http://stackoverflow.com/questions/1638066/clickonce-skip-asking-for-update-or-fail-lauch-if-skip-is-selected), which is:

“If your application is an installed application, you can force updates by using the MinimumRequiredVersion attribute. If you publish your application using Visual Studio, you can set this property from the Updates Dialog.”

Just for clarification, the dialog he mentions can be found in Visual Studio in Project Properties->Publish->Updates&#8230;  Ok, great.  This will allow the prompt to be suppressed, which is also useful if you don’t want to allow users to skip updates.

There is still a problem however.  **Every time I publish a new version of the tool I have to remember to go in and update the MinimumRequiredVersion**.  If I forget to do this and then publish another release, the prompt will be back and will ruin my automated builds.

To get around this I created a PowerShell script that keeps the MinimumRequiredVersion up to date, and I call it from a Post-Build event.  This allows me to never have to worry about manually setting the Minimum Required Version, since it gets updated automatically after every successful build.

<EDIT>

I’ve improved upon the powershell script below and created [a NuGet package that handles all of the setup/installation for you](https://nuget.org/packages/AutoUpdateProjectsMinimumRequiredClickOnceVersion), as described [in my newer post](http://dans-blog.azurewebsites.net/?p=175).

</EDIT>

Here is the powershell script:

<pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title=""># Script finds the current ClickOnce version in a project's .csproj file, and updates the MinimumRequiredVersion to be this same version.
# This can be used to force a ClickOnce application to update automatically without prompting the user.

[Parameter(Position=0, HelpMessage="Comma separated paths of the .csproj files to process")]
Param([string]$projectFilePaths)

# If a path to a project file was not provided, grab all of the project files in the same directory as this script.
if (-not($projectFilePaths))
{
# Get the directory that this script is in.
$scriptDirectory = Split-Path $MyInvocation.MyCommand.Path -Parent

# Create comma-separated list of project file paths.
Get-Item "$scriptDirectory\*.csproj" | foreach { $projectFilePaths += "$_,"}
$projectFilePaths = $projectFilePaths.TrimEnd(',')
}

# Catch any unhandled exceptions, write its error message, and exit the process with a non-zero error code to indicate failure.
trap
{
[string]$errorMessage = [string]$_
[int]$exitCode = 1

# If this is one of our custom exceptions, strip the error code off of the front.
if ([string]$errorMessage.SubString(0, 1) -match "\d")
{
$exitCode = [string]$errorMessage.SubString(0, 1)
$errorMessage = [string]$errorMessage.SubString(1)
}

Write-Error $errorMessage
EXIT [int]$exitCode
}

Function UpdateProjectsMinimumRequiredClickOnceVersion
{
Param
(
[Parameter(Mandatory=$true, Position=0, HelpMessage="The project file (.csproj) to update.")]
[string]$projectFilePath
)
if (-not([System.IO.File]::Exists($projectFilePath))) { throw "2Cannot find project file to update at the path: '$projectFilePath'" }

# Build the regular expressions to find the information we will need.
$rxMinimumRequiredVersionTag = New-Object System.Text.RegularExpressions.Regex "\&lt;MinimumRequiredVersion\&gt;(?&lt;Version&gt;.*?)\&lt;/MinimumRequiredVersion\&gt;", SingleLine
$rxApplicationVersionTag = New-Object System.Text.RegularExpressions.Regex "\&lt;ApplicationVersion\&gt;(?&lt;Version&gt;\d+\.\d+\.\d+\.).*?\&lt;/ApplicationVersion\&gt;", SingleLine
$rxApplicationRevisionTag = New-Object System.Text.RegularExpressions.Regex "\&lt;ApplicationRevision\&gt;(?&lt;Revision&gt;[0-9]+)\&lt;/ApplicationRevision\&gt;", SingleLine
$rxVersionNumber = [regex] "\d+\.\d+\.\d+\.\d+"

# Read the file contents in.
$text = [System.IO.File]::ReadAllText($projectFilePath)

# Get the current Minimum Required Version, and the Version that it should be.
$oldMinimumRequiredVersion = $rxMinimumRequiredVersionTag.Match($text).Groups["Version"].Value
$majorMinorBuild = $rxApplicationVersionTag.Match($text).Groups["Version"].Value
$revision = $rxApplicationRevisionTag.Match($text).Groups["Revision"].Value
$newMinimumRequiredVersion = [string]$majorMinorBuild + $revision

# If there was a problem constructing the new version number, throw an error.
if (-not $rxVersionNumber.Match($newMinimumRequiredVersion).Success)
{
throw "3'$projectFilePath' does not appear to have any ClickOnce deployment settings in it."
}

# If we couldn't find the old Minimum Required Version, throw an error.
if (-not $rxVersionNumber.Match($oldMinimumRequiredVersion).Success)
{
throw "4'$projectFilePath' is not currently set to enforce a MinimumRequiredVersion. To fix this in Visual Studio go to Project Properties-&gt;Publish-&gt;Updates... and check off 'Specify a minimum required version for this application'."
}

# Only write to the file if it is not already up to date.
if ($newMinimumRequiredVersion -eq $oldMinimumRequiredVersion)
{
Write "The Minimum Required Version of '$projectFilePath' is already up-to-date on version '$newMinimumRequiredVersion'."
}
else
{
# Update the file contents and write them back to the file.
$text = $rxMinimumRequiredVersionTag.Replace($text, "&lt;MinimumRequiredVersion&gt;" + $newMinimumRequiredVersion + "&lt;/MinimumRequiredVersion&gt;")
[System.IO.File]::WriteAllText($projectFilePath, $text)
Write "Updated Minimum Required Version of '$projectFilePath' from '$oldMinimumRequiredVersion' to '$newMinimumRequiredVersion'"
}
}

# Process each of the project files in the comma-separated list.
$projectFilePaths.Split(",") | foreach { UpdateProjectsMinimumRequiredClickOnceVersion $_ }
</pre>

The script was actually very small at first, but after commenting it and adding some proper error handling it is fairly large now.

So copy-paste the powershell script text into a new file, such as “UpdateClickOnceVersion.ps1”, and add this file to your project somewhere.

The next step now is to call this script from the Post Build event, so in Visual Studio go into your ClickOnce project’s properties and go to the Build Events tab.  In the Post-build event command line put the following:

<pre class="brush: bash; pad-line-numbers: true; title: ; notranslate" title="">REM Update the ClickOnce MinimumRequiredVersion so that it auto-updates without prompting
PowerShell set-executionpolicy remotesigned
PowerShell "$(ProjectDir)UpdateClickOnceVersion.ps1" "$(ProjectPath)"
</pre>

The first line is just a comment.  The second line may be a security concern, so you might want to remove it.  Basically by default PowerShell is not allowed to run any scripts, so trying to run our script above would result in an error.  To fix this we change the execution policy to allow remotesigned scripts to be ran.  I’m not going to pretend to understand why powershell requires this (as I just started learning it today), but that line only needs to be ran on a PC once, so if you want to remove it from here and just run it from PowerShell manually instead (if you haven’t already ran it before), feel free to do so.  I just include it here so that other developers who build this tool in the future don’t have to worry about this setting.

The third line is where we are actually calling the powershell script, passing the path to the .csproj file to update as a parameter.  I added the powershell script to my project at the root level (so it sits right beside the .csproj file), but you can put the powershell script wherever you like.  Also, you don’t even have to include it in the project if you don’t want to, but I chose to so that it is easily visible for other developers when in Visual Studio, and so that it implicitly gets added to source control.  If you want to put the script in a folder instead of in the root of the project directory feel free; just remember to properly update the path in the post-build events.

So after going through and adding all of my nice error messages to the powershell script, I realized that if there is a problem with the script Visual Studio does not forward the error message to the Output window like I hoped it would; it just spits out the text in the Post-build event window and says an error occurred; which doesn’t really tell us anything.  So if you find you are getting errors, copy-paste that third line into PowerShell, replace the macro variables for their absolute values, and run it there.  Powershell should then give you a much more informative error message.

One last comment about this process is that because the powershell script modifies the .csproj outside of visual studio, after you publish a new version and build, the script will write to that .csproj file and visual studio will give you a prompt that the project was modified outside of visual studio and will want to reload it.  You can choose to reload it (which will close all file tabs for that project), or choose to ignore it; it’s up to you.  This is the one minor annoyance I haven’t been able to find a way around, but it’s still better than having to remember to update the Minimum Required Version manually after every new version of the tool I publish.

I hope you find this post useful, and I appreciate any comments; good or bad.  Happy coding!
