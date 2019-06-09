---
title: Find Largest (Or Smallest) Files In A Directory Or Drive With PowerShell
date: 2014-09-08T16:38:38-06:00
permalink: /find-largest-or-smallest-files-in-a-directory-or-drive-with-powershell/
categories:
  - PowerShell
tags:
  - Biggest
  - file
  - File System
  - Largest
  - PowerShell
  - Size
  - Smallest
---

One of our SQL servers was running low on disk space and I needed to quickly find the largest files on the drive to know what was eating up all of the disk space, so I wrote this PowerShell line that I thought I would share:

```powershell
# Get all files sorted by size.
Get-ChildItem -Path 'C:\SomeFolder' -Recurse -Force -File | Select-Object -Property FullName,@{Name='SizeGB';Expression={$_.Length / 1GB}},@{Name='SizeMB';Expression={$_.Length / 1MB}},@{Name='SizeKB';Expression={$_.Length / 1KB}} | Sort-Object { $_.SizeKB } -Descending | Out-GridView
```

If you are still only running PowerShell 2.0, it will complain that it doesn't know what the -File switch is, so here's the PowerShell 2.0 compatible version (which is a bit slower):

```powershell
# Get all files sorted by size.
Get-ChildItem -Path 'C:\SomeFolder' -Recurse -Force | Where-Object { !$_.PSIsContainer } | Select-Object -Property FullName,@{Name='SizeGB';Expression={$_.Length / 1GB}},@{Name='SizeMB';Expression={$_.Length / 1MB}},@{Name='SizeKB';Expression={$_.Length / 1KB}} | Sort-Object { $_.SizeKB } -Descending | Out-GridView
```

Just change 'C:\SomeFolder' to the folder/drive that you want scanned, and it will show you all of the files in the directory and subdirectories in a GridView sorted by size, along with their size in GB, MB, and KB. The nice thing about using a GridView is that it has built in filtering, so you can quickly do things like filter for certain file types, child directories, etc.

Here is a screenshot of the resulting GridView:

![Files Sorted By Size](/assets/Posts/2014/09/FilesSortedBySize.png)

And again with filtering applied (i.e. the .bak at the top to only show backup files):

![Files Sorted By Size And Filtered](/assets/Posts/2014/09/FilesSortedBySizeAndFiltered.png)

All done with PowerShell; no external tools required.

Happy Sys-Adminning!
