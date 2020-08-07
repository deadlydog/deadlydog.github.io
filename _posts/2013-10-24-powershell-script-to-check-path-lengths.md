---
title: PowerShell Script To Get Path Lengths
date: 2013-10-24T17:14:59-06:00
last_modified_at: 2020-08-07
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
# Output the length of all files and folders in the given directory path.
[CmdletBinding()]
param
(
    [Parameter(HelpMessage = 'The directory to scan path lengths in. Subdirectories will be scanned as well.')]
    [string] $DirectoryPathToScan = 'C:\Temp',

    [Parameter(HelpMessage = 'Only paths this length or longer will be included in the results. Set this to 260 to find problematic paths in Windows.')]
    [int] $MinimumPathLengthsToShow = 0,

    [Parameter(HelpMessage = 'If the results should be written to the console or not. Can be slow if there are many results.')]
    [bool] $WriteResultsToConsole = $true,

    [Parameter(HelpMessage = 'If the results should be shown in a Grid View or not once the scanning completes.')]
    [bool] $WriteResultsToGridView = $true,

    [Parameter(HelpMessage = 'If the results should be written to a file or not.')]
    [bool] $WriteResultsToFile = $false,

    [Parameter(HelpMessage = 'The file path to write the results to when $WriteResultsToFile is true.')]
    [string] $ResultsFilePath = 'C:\Temp\PathLengths.txt'
)

# Ensure output directory exists
[string] $resultsFileDirectoryPath = Split-Path $ResultsFilePath -Parent
if (!(Test-Path $resultsFileDirectoryPath)) { New-Item $resultsFileDirectoryPath -ItemType Directory }

# Open a new file stream (nice and fast) to write all the paths and their lengths to it.
if ($WriteResultsToFile) { $fileStream = New-Object System.IO.StreamWriter($ResultsFilePath, $false) }

$filePathsAndLengths = [System.Collections.ArrayList]::new()

# Get all file and directory paths and write them if applicable.
Get-ChildItem -Path $DirectoryPathToScan -Recurse -Force |
    Select-Object -Property FullName, @{Name = "FullNameLength"; Expression = { ($_.FullName.Length) } } |
    Sort-Object -Property FullNameLength -Descending |
    ForEach-Object {

    $filePath = $_.FullName
    $length = $_.FullNameLength

    # If this path is long enough, add it to the results.
    if ($length -ge $MinimumPathLengthsToShow)
    {
        [string] $lineOutput = "$length : $filePath"

        if ($WriteResultsToConsole) { Write-Output $lineOutput }

        if ($WriteResultsToFile) { $fileStream.WriteLine($lineOutput) }

        $filePathsAndLengths.Add($_) > $null
    }
}

if ($WriteResultsToFile) { $fileStream.Close() }

if ($WriteResultsToGridView) { $filePathsAndLengths | Out-GridView -Title "Paths under '$DirectoryPathToScan' longer than '$MinimumPathLengthsToShow'." }
```

Happy coding!
