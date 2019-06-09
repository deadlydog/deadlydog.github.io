---
title: PowerShell function to create a password protected zip file
date: 2013-05-09T17:42:14-06:00
permalink: /powershell-function-to-create-a-password-protected-zip-file/
categories:
  - PowerShell
tags:
  - 7zip
  - Archive
  - Compress
  - Password
  - PowerShell
  - Zip
---

There are [a few different ways to create zip files in powershell](http://stackoverflow.com/questions/1153126/how-to-create-a-zip-archive-with-powershell), but not many that allow you to create one that is password protected. I found [this post that shows how to do it using 7zip](http://community.spiceworks.com/topic/263947-powershell-7-zip-password-protected-zip), so I thought I would share my modified solution.

Here is the function I wrote that uses 7zip to perform the zip, since 7zip supports using a password to zip the files. This script looks for the 7zip executable (7z.exe) in the default install locations, and if not found it will use the stand-alone 7zip executable (7za.exe) if it is in the same directory as the powershell script.

__Update__: Updated function to support multiple compression types: 7z, zip, gzip, bzip2, tar, iso, and udf.

```powershell
function Write-ZipUsing7Zip([string]$FilesToZip, [string]$ZipOutputFilePath, [string]$Password, [ValidateSet('7z','zip','gzip','bzip2','tar','iso','udf')][string]$CompressionType = 'zip', [switch]$HideWindow)
{
    # Look for the 7zip executable.
    $pathTo32Bit7Zip = "C:\Program Files (x86)\7-Zip\7z.exe"
    $pathTo64Bit7Zip = "C:\Program Files\7-Zip\7z.exe"
    $THIS_SCRIPTS_DIRECTORY = Split-Path $script:MyInvocation.MyCommand.Path
    $pathToStandAloneExe = Join-Path $THIS_SCRIPTS_DIRECTORY "7za.exe"
    if (Test-Path $pathTo64Bit7Zip) { $pathTo7ZipExe = $pathTo64Bit7Zip }
    elseif (Test-Path $pathTo32Bit7Zip) { $pathTo7ZipExe = $pathTo32Bit7Zip }
    elseif (Test-Path $pathToStandAloneExe) { $pathTo7ZipExe = $pathToStandAloneExe }
    else { throw "Could not find the 7-zip executable." }

    # Delete the destination zip file if it already exists (i.e. overwrite it).
    if (Test-Path $ZipOutputFilePath) { Remove-Item $ZipOutputFilePath -Force }

    $windowStyle = "Normal"
    if ($HideWindow) { $windowStyle = "Hidden" }

    # Create the arguments to use to zip up the files.
    # Command-line argument syntax can be found at: http://www.dotnetperls.com/7-zip-examples
    $arguments = "a -t$CompressionType ""$ZipOutputFilePath"" ""$FilesToZip"" -mx9"
    if (!([string]::IsNullOrEmpty($Password))) { $arguments += " -p$Password" }

    # Zip up the files.
    $p = Start-Process $pathTo7ZipExe -ArgumentList $arguments -Wait -PassThru -WindowStyle $windowStyle

    # If the files were not zipped successfully.
    if (!(($p.HasExited -eq $true) -and ($p.ExitCode -eq 0)))
    {
        throw "There was a problem creating the zip file '$ZipFilePath'."
    }
}
```

[Download the function script](/assets/Posts/2013/09/Write-ZipUsing7Zip.zip)

And here's some examples of how to call the function:

```powershell
Write-ZipUsing7Zip -FilesToZip "C:\SomeFolder" -ZipOutputFilePath "C:\SomeFolder.zip" -Password "password123"
Write-ZipUsing7Zip "C:\Folder\*.txt" "C:\FoldersTxtFiles.zip" -HideWindow
```

I hope you find this useful.

Happy coding!
