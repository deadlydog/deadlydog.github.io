---
title: Add ability to add tabs to the end of a line in Windows PowerShell ISE
date: 2013-06-24T16:06:52-06:00
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

In the preamble of [an earlier post](https://blog.danskingdom.com/powershell-ise-multiline-comment-and-uncomment-done-right-and-other-ise-gui-must-haves/) I mentioned that one of the little things that bugs me about Windows PowerShell ISE is that you can add tabs to the start of a line, but not to the end of a line. This is likely because it would interfere with the tab-completion feature. I still like to be able to put tabs on the end of my code lines though so that I can easily line up my comments, like this:

```powershell
$processes = Get-Process # Get all of the processes.
$myProcesses = $processes | Where {$_.Company -eq "MyCompany" } # Get my company's processes.
```

We can add the functionality to allow us to insert a tab at the end of a line, but it involves modifying the PowerShell __ISE__ profile, so opening that file for editing is the first step.

To edit your PowerShell ISE profile:

1. Open __Windows PowerShell ISE__ (not Windows PowerShell, as we want to edit the ISE profile instead of the regular PowerShell profile).
1. In the Command window type: `psedit $profile`
   - If you get an error that it cannot find the path, then first type the following to create the file before trying #2 again: `New-Item $profile -ItemType File -Force`

And now that you have your PowerShell ISE profile file open for editing, you can append the following code to it:

```powershell
# Add a new option in the Add-ons menu to insert a tab.
if (!($psISE.CurrentPowerShellTab.AddOnsMenu.Submenus | Where-Object { $_.DisplayName -eq "Insert Tab" }))
{
    $psISE.CurrentPowerShellTab.AddOnsMenu.Submenus.Add("Insert Tab",{$psISE.CurrentFile.Editor.InsertText("`t")},"Ctrl+Shift+T")
}
```

This will allow you to use __Ctrl+Shift+T__ to insert a tab anywhere in the editor, including at the end of a line. I wanted to use Shift+Tab, but apparently that shortcut is already used by the editor somewhere, even though it doesn't seem to do anything when I press it. Feel free to change the keyboard shortcut to something else if you like.

I hope this helps make your PowerShell ISE experience a little better.

Happy coding!
