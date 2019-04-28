---
id: 822
title: Find Largest (Or Smallest) Files In A Directory Or Drive With PowerShell
date: 2014-09-08T16:38:38-06:00
author: deadlydog
layout: post
guid: http://dans-blog.azurewebsites.net/?p=822
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

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:5775a5a4-bc2a-4a22-8fbf-d45ffc0aae87" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title="">
# Get all files sorted by size.
Get-ChildItem -Path 'C:\SomeFolder' -Recurse -Force -File | Select-Object -Property FullName,@{Name='SizeGB';Expression={$_.Length / 1GB}},@{Name='SizeMB';Expression={$_.Length / 1MB}},@{Name='SizeKB';Expression={$_.Length / 1KB}} | Sort-Object { $_.SizeKB } -Descending | Out-GridView
</pre>
</div>

If you are still only running PowerShell 2.0, it will complain that it doesn&#8217;t know what the -File switch is, so here&#8217;s the PowerShell 2.0 compatible version (which is a bit slower):

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:5775a5a4-bc2a-4a22-8fbf-d45ffc0aae87" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title="">
# Get all files sorted by size.
Get-ChildItem -Path 'C:\SomeFolder' -Recurse -Force | Where-Object { !$_.PSIsContainer } | Select-Object -Property FullName,@{Name='SizeGB';Expression={$_.Length / 1GB}},@{Name='SizeMB';Expression={$_.Length / 1MB}},@{Name='SizeKB';Expression={$_.Length / 1KB}} | Sort-Object { $_.SizeKB } -Descending | Out-GridView
</pre>
</div>

Just change ‘C:\SomeFolder’ to the folder/drive that you want scanned, and it will show you all of the files in the directory and subdirectories in a GridView sorted by size, along with their size in GB, MB, and KB. The nice thing about using a GridView is that it has built in filtering, so you can quickly do things like filter for certain file types, child directories, etc.

Here is a screenshot of the resulting GridView:

[<img title="FilesSortedBySize" style="border-top: 0px; border-right: 0px; background-image: none; border-bottom: 0px; padding-top: 0px; padding-left: 0px; border-left: 0px; display: inline; padding-right: 0px" border="0" alt="FilesSortedBySize" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2014/09/FilesSortedBySize_thumb.png" width="600" height="490" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2014/09/FilesSortedBySize.png)

&#160;

And again with filtering applied (i.e. the .bak at the top to only show backup files):

[<img title="FilesSortedBySizeAndFiltered" style="border-top: 0px; border-right: 0px; background-image: none; border-bottom: 0px; padding-top: 0px; padding-left: 0px; border-left: 0px; display: inline; padding-right: 0px" border="0" alt="FilesSortedBySizeAndFiltered" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2014/09/FilesSortedBySizeAndFiltered_thumb.png" width="600" height="491" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2014/09/FilesSortedBySizeAndFiltered.png)

All done with PowerShell; no external tools required.

Happy Sys-Adminning!