---
id: 539
title: PowerShell Functions To Delete Old Files And Empty Directories
date: 2013-10-15T17:38:12-06:00
guid: http://dans-blog.azurewebsites.net/?p=539
permalink: /powershell-functions-to-delete-old-files-and-empty-directories/
categories:
  - PowerShell
tags:
  - Date
  - Delete
  - Directories
  - directory
  - file
  - Files
  - Old
  - Older
  - PowerShell
  - Remove
  - Time
---
I thought I’d share some PowerShell (PS) functions that I wrote for some clean-up scripts at work.&#160; I use these functions to delete files older than a certain date. Note that these functions require PS v3.0; slower PS v2.0 compatible functions are given at the end of this article.

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:1ae951c3-9a90-495d-b4ae-fc601ee7a3dc" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title="">
# Function to remove all empty directories under the given path.
# If -DeletePathIfEmpty is provided the given Path directory will also be deleted if it is empty.
# If -OnlyDeleteDirectoriesCreatedBeforeDate is provided, empty folders will only be deleted if they were created before the given date.
# If -OnlyDeleteDirectoriesNotModifiedAfterDate is provided, empty folders will only be deleted if they have not been written to after the given date.
function Remove-EmptyDirectories([parameter(Mandatory)][ValidateScript({Test-Path $_})][string] $Path, [switch] $DeletePathIfEmpty, [DateTime] $OnlyDeleteDirectoriesCreatedBeforeDate = [DateTime]::MaxValue, [DateTime] $OnlyDeleteDirectoriesNotModifiedAfterDate = [DateTime]::MaxValue, [switch] $OutputDeletedPaths, [switch] $WhatIf)
{
    Get-ChildItem -Path $Path -Recurse -Force -Directory | Where-Object { (Get-ChildItem -Path $_.FullName -Recurse -Force -File) -eq $null } |
        Where-Object { $_.CreationTime -lt $OnlyDeleteDirectoriesCreatedBeforeDate -and $_.LastWriteTime -lt $OnlyDeleteDirectoriesNotModifiedAfterDate } |
        ForEach-Object { if ($OutputDeletedPaths) { Write-Output $_.FullName } Remove-Item -Path $_.FullName -Force -WhatIf:$WhatIf }

    # If we should delete the given path when it is empty, and it is a directory, and it is empty, and it meets the date requirements, then delete it.
    if ($DeletePathIfEmpty -and (Test-Path -Path $Path -PathType Container) -and (Get-ChildItem -Path $Path -Force) -eq $null -and
        ((Get-Item $Path).CreationTime -lt $OnlyDeleteDirectoriesCreatedBeforeDate) -and ((Get-Item $Path).LastWriteTime -lt $OnlyDeleteDirectoriesNotModifiedAfterDate))
    { if ($OutputDeletedPaths) { Write-Output $Path } Remove-Item -Path $Path -Force -WhatIf:$WhatIf }
}

# Function to remove all files in the given Path that were created before the given date, as well as any empty directories that may be left behind.
function Remove-FilesCreatedBeforeDate([parameter(Mandatory)][ValidateScript({Test-Path $_})][string] $Path, [parameter(Mandatory)][DateTime] $DateTime, [switch] $DeletePathIfEmpty, [switch] $OutputDeletedPaths, [switch] $WhatIf)
{
    Get-ChildItem -Path $Path -Recurse -Force -File | Where-Object { $_.CreationTime -lt $DateTime } |
		ForEach-Object { if ($OutputDeletedPaths) { Write-Output $_.FullName } Remove-Item -Path $_.FullName -Force -WhatIf:$WhatIf }
    Remove-EmptyDirectories -Path $Path -DeletePathIfEmpty:$DeletePathIfEmpty -OnlyDeleteDirectoriesCreatedBeforeDate $DateTime -OutputDeletedPaths:$OutputDeletedPaths -WhatIf:$WhatIf
}

# Function to remove all files in the given Path that have not been modified after the given date, as well as any empty directories that may be left behind.
function Remove-FilesNotModifiedAfterDate([parameter(Mandatory)][ValidateScript({Test-Path $_})][string] $Path, [parameter(Mandatory)][DateTime] $DateTime, [switch] $DeletePathIfEmpty, [switch] $OutputDeletedPaths, [switch] $WhatIf)
{
    Get-ChildItem -Path $Path -Recurse -Force -File | Where-Object { $_.LastWriteTime -lt $DateTime } |
	ForEach-Object { if ($OutputDeletedPaths) { Write-Output $_.FullName } Remove-Item -Path $_.FullName -Force -WhatIf:$WhatIf }
    Remove-EmptyDirectories -Path $Path -DeletePathIfEmpty:$DeletePathIfEmpty -OnlyDeleteDirectoriesNotModifiedAfterDate $DateTime -OutputDeletedPaths:$OutputDeletedPaths -WhatIf:$WhatIf
}

</pre>
</div>

<div id="scid:fb3a1972-4489-4e52-abe7-25a00bb07fdf:c2c16c8f-2fb3-43c5-9185-f9386261beae" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <p>
    <a href="http://dans-blog.azurewebsites.net/wp-content/uploads/2014/01/Remove-FilesOlderThan.zip" target="_blank">Download File</a>
  </p>
</div>

The **Remove-EmptyDirectories** function removes all empty directories under the given path, and optionally (via the DeletePathIfEmpty switch) the path directory itself if it is empty after cleaning up the other directories. It also takes a couple parameters that may be specified if you only want to delete the empty directories that were created before a certain date, or that haven’t been written to since a certain date.

The **Remove-FilesCreatedBeforeDate** and **Remove-FilesNotModifiedAfterDate** functions are very similar to each other.&#160; They delete all files under the given path whose Created Date or Last Written To Date, respectfully, is less than the given DateTime.&#160; They then call the Remove-EmptyDirectories function with the provided date to clean up any left over empty directories.

To call the last 2 functions, just provide the path to the file/directory that you want it to delete if older than the given date-time.&#160; Here are some examples of calling all the functions:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:0c7dde8c-4381-4077-936d-b33119373a95" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; title: ; notranslate" title="">
# Delete all files created more than 2 days ago.
Remove-FilesCreatedBeforeDate -Path "C:\Some\Directory" -DateTime ((Get-Date).AddDays(-2)) -DeletePathIfEmpty

# Delete all files that have not been updated in 8 hours.
Remove-FilesNotModifiedAfterDate -Path "C:\Another\Directory" -DateTime ((Get-Date).AddHours(-8))

# Delete a single file if it is more than 30 minutes old.
Remove-FilesCreatedBeforeDate -Path "C:\Another\Directory\SomeFile.txt" -DateTime ((Get-Date).AddMinutes(-30))

# Delete all empty directories in the Temp folder, as well as the Temp folder itself if it is empty.
Remove-EmptyDirectories -Path "C:\SomePath\Temp" -DeletePathIfEmpty

# Delete all empty directories created after Jan 1, 2014 3PM.
Remove-EmptyDirectories -Path "C:\SomePath\WithEmpty\Directories" -OnlyDeleteDirectoriesCreatedBeforeDate ([DateTime]::Parse("Jan 1, 2014 15:00:00"))

# See what files and directories would be deleted if we ran the command.
Remove-FilesCreatedBeforeDate -Path "C:\SomePath\Temp" -DateTime (Get-Date) -DeletePathIfEmpty -WhatIf

# Delete all files and directories in the Temp folder, as well as the Temp folder itself if it is empty, and output all paths that were deleted.
Remove-FilesCreatedBeforeDate -Path "C:\SomePath\Temp" -DateTime (Get-Date) -DeletePathIfEmpty -OutputDeletedPaths

</pre>
</div>

Notice that I am using Get-Date to get the current date and time, and then **subtracting** the specified amount of time from it in order to get a date-time relative to the current time; you can use any valid DateTime though, such as a hard-coded date of January 1st, 2014 3PM.

I use these functions in some scripts that we run nightly via a scheduled task in Windows.&#160; Hopefully you find them useful too.

&#160;

### PowerShell v2.0 Compatible Functions

As promised, here are the slower PS v2.0 compatible functions.&#160; The main difference is that they use $_.PSIsContainer in the Where-Object clause rather than using the –File / –Directory Get-ChildItem switches.&#160; The Measure-Command cmdlet shows that using the switches is about 3x faster than using the where clause, but since we are talking about milliseconds here you likely won’t notice the difference unless you are traversing a large file tree (which I happen to be for my scripts that we use to clean up TFS builds).

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:6a0118b4-0589-49cd-b423-a0f24369b872" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; title: ; notranslate" title="">
# Function to remove all empty directories under the given path.
# If -DeletePathIfEmpty is provided the given Path directory will also be deleted if it is empty.
# If -OnlyDeleteDirectoriesCreatedBeforeDate is provided, empty folders will only be deleted if they were created before the given date.
# If -OnlyDeleteDirectoriesNotModifiedAfterDate is provided, empty folders will only be deleted if they have not been written to after the given date.
function Remove-EmptyDirectories([parameter(Mandatory=$true)][ValidateScript({Test-Path $_})][string] $Path, [switch] $DeletePathIfEmpty, [DateTime] $OnlyDeleteDirectoriesCreatedBeforeDate = [DateTime]::MaxValue, [DateTime] $OnlyDeleteDirectoriesNotModifiedAfterDate = [DateTime]::MaxValue, [switch] $OutputDeletedPaths, [switch] $WhatIf)
{
    Get-ChildItem -Path $Path -Recurse -Force | Where-Object { $_.PSIsContainer -and (Get-ChildItem -Path $_.FullName -Recurse -Force | Where-Object { !$_.PSIsContainer }) -eq $null } |
        Where-Object { $_.CreationTime -lt $OnlyDeleteDirectoriesCreatedBeforeDate -and $_.LastWriteTime -lt $OnlyDeleteDirectoriesNotModifiedAfterDate } |
        ForEach-Object { if ($OutputDeletedPaths) { Write-Output $_.FullName } Remove-Item -Path $_.FullName -Force -WhatIf:$WhatIf }

    # If we should delete the given path when it is empty, and it is a directory, and it is empty, and it meets the date requirements, then delete it.
    if ($DeletePathIfEmpty -and (Test-Path -Path $Path -PathType Container) -and (Get-ChildItem -Path $Path -Force) -eq $null -and
        ((Get-Item $Path).CreationTime -lt $OnlyDeleteDirectoriesCreatedBeforeDate) -and ((Get-Item $Path).LastWriteTime -lt $OnlyDeleteDirectoriesNotModifiedAfterDate))
    { if ($OutputDeletedPaths) { Write-Output $Path } Remove-Item -Path $Path -Force -WhatIf:$WhatIf }
}

# Function to remove all files in the given Path that were created before the given date, as well as any empty directories that may be left behind.
function Remove-FilesCreatedBeforeDate([parameter(Mandatory=$true)][ValidateScript({Test-Path $_})][string] $Path, [parameter(Mandatory)][DateTime] $DateTime, [switch] $DeletePathIfEmpty, [switch] $OutputDeletedPaths, [switch] $WhatIf)
{
    Get-ChildItem -Path $Path -Recurse -Force | Where-Object { !$_.PSIsContainer -and $_.CreationTime -lt $DateTime } |
		ForEach-Object { if ($OutputDeletedPaths) { Write-Output $_.FullName } Remove-Item -Path $_.FullName -Force -WhatIf:$WhatIf }
    Remove-EmptyDirectories -Path $Path -DeletePathIfEmpty:$DeletePathIfEmpty -OnlyDeleteDirectoriesCreatedBeforeDate $DateTime -OutputDeletedPaths:$OutputDeletedPaths -WhatIf:$WhatIf
}

# Function to remove all files in the given Path that have not been modified after the given date, as well as any empty directories that may be left behind.
function Remove-FilesNotModifiedAfterDate([parameter(Mandatory=$true)][ValidateScript({Test-Path $_})][string] $Path, [parameter(Mandatory)][DateTime] $DateTime, [switch] $DeletePathIfEmpty, [switch] $OutputDeletedPaths, [switch] $WhatIf)
{
    Get-ChildItem -Path $Path -Recurse -Force | Where-Object { !$_.PSIsContainer -and $_.LastWriteTime -lt $DateTime } |
	ForEach-Object { if ($OutputDeletedPaths) { Write-Output $_.FullName } Remove-Item -Path $_.FullName -Force -WhatIf:$WhatIf }
    Remove-EmptyDirectories -Path $Path -DeletePathIfEmpty:$DeletePathIfEmpty -OnlyDeleteDirectoriesNotModifiedAfterDate $DateTime -OutputDeletedPaths:$OutputDeletedPaths -WhatIf:$WhatIf
}

</pre>
</div>

<div id="scid:fb3a1972-4489-4e52-abe7-25a00bb07fdf:b8e621ed-daea-4ac4-b565-fb8f9cd997e6" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <p>
    <a href="http://dans-blog.azurewebsites.net/wp-content/uploads/2014/01/Remove-FilesOlderThanPSv2.zip" target="_blank">Download PSv2 File</a>
  </p>
</div>

Happy coding!
