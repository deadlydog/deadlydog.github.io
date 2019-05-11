---
id: 363
title: Add ability to add tabs to the end of a line in Windows PowerShell ISE
date: 2013-06-24T16:06:52-06:00
author: deadlydog
guid: http://dans-blog.azurewebsites.net/?p=363
permalink: /add-ability-to-add-tabs-to-the-end-of-a-line-in-windows-powershell-ise/
categories:
  - PowerShell
tags:
  - Editor
  - ISE
  - PowerShell
  - PowerShell ISE
  - Profile
  - Tab
  - Windows PowerShell
  - Windows PowerShell ISE
---
In the preamble of [an earlier post](http://dans-blog.azurewebsites.net/powershell-ise-multiline-comment-and-uncomment-done-right-and-other-ise-gui-must-haves/) I mentioned that one of the little things that bugs me about Windows PowerShell ISE is that you can add tabs to the start of a line, but not to the end of a line.&#160; This is likely because it would interfere with the tab-completion feature.&#160; I still like to be able to put tabs on the end of my code lines though so that I can easily line up my comments, like this:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:70b1c5eb-b0cd-433c-9684-9505ed3be5d6" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title="">
$processes = Get-Process										# Get all of the processes.
$myProcesses = $processes | Where {$_.Company -eq "MyCompany" }	# Get my company's processes.
</pre>
</div>

&#160;

We can add the functionality to allow us to insert a tab at the end of a line, but it involves modifying the PowerShell **ISE** profile, so opening that file for editing is the first step.

<u>To edit your PowerShell ISE profile:</u>

  1. Open **Windows PowerShell ISE** (not Windows PowerShell, as we want to edit the ISE profile instead of the regular PowerShell profile).
  2. In the Command window type: **psedit $profile


** If you get an error that it cannot find the path, then first type the following to create the file before trying #2 again: **New-Item $profile –ItemType File –Force**

And now that you have your PowerShell ISE profile file open for editing, you can append the following code to it:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:18075d34-2f38-48c7-9c6a-db7fe5cb98b1" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; title: ; notranslate" title="">
# Add a new option in the Add-ons menu to insert a tab.
if (!($psISE.CurrentPowerShellTab.AddOnsMenu.Submenus | Where-Object { $_.DisplayName -eq "Insert Tab" }))
{
    $psISE.CurrentPowerShellTab.AddOnsMenu.Submenus.Add("Insert Tab",{$psISE.CurrentFile.Editor.InsertText("`t")},"Ctrl+Shift+T")
}
</pre>
</div>

&#160;

This will allow you to use **Ctrl+Shift+T** to insert a tab anywhere in the editor, including at the end of a line.&#160; I wanted to use Shift+Tab, but apparently that shortcut is already used by the editor somewhere, even though it doesn’t seem to do anything when I press it.&#160; Feel free to change the keyboard shortcut to something else if you like.

I hope this helps make your PowerShell ISE experience a little better.

Happy coding!
