---
title: PowerShell Script To Get Path Lengths
date: 2013-10-24T17:14:59-06:00
permalink: /powershell-script-to-check-path-lengths/
categories:
  - PowerShell
tags:
  - directory
  - file
  - Length
  - Path
  - PowerShell
  - Subdirectory
---

A while ago I created a [Path Length Checker](https://pathlengthchecker.codeplex.com/) tool in C# that has a "nice" GUI, and put it up on CodePlex. One of the users reported that he was trying to use it to scan his entire C: drive, but that it was crashing. Turns out that the `System.IO.Directory.GetFileSystemEntries()` call was throwing a permissions exception when trying to access the "C:\Documents and Settings" directory. Even when running the app as admin it throws this exception. In the meantime while I am working on implementing a workaround for the app, I wrote up a quick PowerShell script that the user could use to get all of the path lengths. That is what I present to you here.

```powershell
$pathToScan = "C:\Some Folder"  # The path to scan and the the lengths for (sub-directories will be scanned as well).
$outputFilePath = "C:\temp\PathLengths.txt" # This must be a file in a directory that exists and does not require admin rights to write to.
$writeToConsoleAsWell = $true   # Writing to the console will be much slower.

# Open a new file stream (nice and fast) and write all the paths and their lengths to it.
$outputFileDirectory = Split-Path $outputFilePath -Parent
if (!(Test-Path $outputFileDirectory)) { New-Item $outputFileDirectory -ItemType Directory }
$stream = New-Object System.IO.StreamWriter($outputFilePath, $false)
Get-ChildItem -Path $pathToScan -Recurse -Force | Select-Object -Property FullName, @{Name="FullNameLength";Expression={($_.FullName.Length)}} | Sort-Object -Property FullNameLength -Descending | ForEach-Object {
    $filePath = $_.FullName
    $length = $_.FullNameLength
    $string = "$length : $filePath"

    # Write to the Console.
    if ($writeToConsoleAsWell) { Write-Host $string }

    #Write to the file.
    $stream.WriteLine($string)
}
$stream.Close()
```

Happy coding!
