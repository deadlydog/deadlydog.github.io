---
title: "Windows settings I always adjust"
permalink: /Windows-settings-I-always-adjust/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: true
categories:
  - Windows
  - Productivity
  - Shortcuts
tags:
  - Windows
  - Productivity
  - Shortcuts
---

Windows has a ton of native features and keyboard shortcuts that can make it more pleasant and efficient to use.
Below are some of my favorites.

As I discover more I'll add them here, so be sure to check back from time to time.
Also, if you have a favourite that I don't have listed here, please let me know in the comments below.

## Windows settings

### Show hidden files and folders and file extensions

By default, File Explorer will not show hidden files and folders, nor show file extensions for known file types.
I don't like Windows hiding things from me, so I always enable these settings.

Below are screenshots showing how a directory in File Explorer might look before and after making the changes:

![Before showing hidden files and file extensions](/assets/Posts/2025-02-07-Windows-settings-I-always-adjust/directory-before-showing-hidden-files-and-file-extensions.png)

![After showing hidden files and file extensions](/assets/Posts/2025-02-07-Windows-settings-I-always-adjust/directory-after-showing-hidden-files-and-file-extensions.png)

To enable the settings:

1. Open File Explorer Options.
   - You can search for `File Explorer Options` in the Start Menu, or find it in File Explorer by clicking the `View` tab, then `Options` on the right side (Windows 10), or the `...` button then `Options` (Windows 11).
1. In the Folder Options window, click on the `View` tab.
1. Under `Advanced settings`, find the following settings:
   - Enable `Show hidden files, folders, and drives`
   - Disable `Hide extensions for known file types`
1. Click `Apply` to apply it to the current folder.
1. Click `Apply to Folders` to apply it to all folders.

![Screenshot showing how to enable showing hidden files and file extensions](/assets/Posts/2025-02-07-Windows-settings-I-always-adjust/steps-to-show-hidden-files-and-file-extensions.png)

The last step of clicking the `Apply to Folders` button is important so that you don't have to do this for every folder you open.

### Show where the mouse cursor is with the <kbd>Ctrl</kbd> key

If you have multiple monitors, it's easy to lose the mouse cursor.
Windows has a feature that when you press the <kbd>Ctrl</kbd> key, it will show you where the mouse cursor is by briefly circling it.

![Windows showing where the mouse cursor is](/assets/Posts/2025-02-07-Windows-settings-I-always-adjust/show-where-mouse-cursor-is.gif)

To enable this feature:

1. Use the Start Menu to search for `Mouse settings`.
1. In the Mouse settings window, open the `Additional mouse settings`, which should pop up the `Mouse Properties` window.
1. Click on the `Pointer Options` tab.
1. Enable `Show location of pointer when I press the CTRL key`.

![Mouse properties window showing how to enable showing the mouse cursor location](/assets/Posts/2025-02-07-Windows-settings-I-always-adjust/mouse-properties-window-enabling-show-location-of-pointer-with-ctrl-key.png)

Bonus: See the Microsoft PowerToys section at the end to allow spotlighting the mouse cursor when you press the <kbd>Ctrl</kbd> key twice.

### Make the mouse cursor larger and change the color

Having a large resolution on a small monitor can make it difficult to see the mouse cursor.
I like to enlarge my cursor a bit, and change the color to make it stand out so it's easier to see.

To adjust your mouse cursor settings, use the Start Menu to search for `Mouse pointer size`.
There you can adjust the size and color of the mouse cursor.

![Windows mouse cursor settings](/assets/Posts/2025-02-07-Windows-settings-I-always-adjust/windows-mouse-pointer-size-settings.png)

### Shift the Start Menu to the left in the taskbar

### Never group taskbar icons

## Windows features

### Show multiple time zones in the taskbar clock

### Use the Windows Sandbox to test software and settings safely

## Registry tweaks that don't have a GUI setting

> NOTE: Modifying the registry can be dangerous if you don't know what you're doing, so be sure to [backup your registry](https://www.tweaking.com/how-to-backup-whole-registry-in-windows-10-step-by-step-guide/) before making any changes.

I include PowerShell commands instead of manual steps for modifying the registry to help ensure that only the intended keys are modified.

Some commands shown below may need to be ran from an elevated PowerShell prompt (i.e. run PowerShell as an Administrator), otherwise you may get an error like `Requested registry access is not allowed`.

Also, you may need to restart your computer for some changes to take effect.

### Disable searching the web in the Start Menu search

When I search from the Start Menu, I want it to only search my local machine.

Here are screenshots before and after disabling the web search:

![Before disabling web search](/assets/Posts/2025-02-07-Windows-settings-I-always-adjust/start-menu-search-before-disabling-web-search.png)

![After disabling web search](/assets/Posts/2025-02-07-Windows-settings-I-always-adjust/start-menu-search-after-disabling-web-search.png)

From a PowerShell prompt, run the following command to disable web search in the Start Menu:

```powershell
Set-ItemProperty -Path "HKCU:\SOFTWARE\Microsoft\Windows\CurrentVersion\Search" -Name "BingSearchEnabled" -Value 0 -Type DWord
```

To revert back to the default behavior, run:

```powershell
Set-ItemProperty -Path "HKCU:\SOFTWARE\Microsoft\Windows\CurrentVersion\Search" -Name "BingSearchEnabled" -Value 1 -Type DWord
```

Note that it also disables Copilot, which may or may not be desired.

### Always open "More options" context menu in Windows 11+

Windows 11 modified the right-click context menu to only show a few options by default, and you have to click "Show more options" to see the rest.
I prefer the previous Windows behavior where all options are shown by default.

Below are screenshots of the context menu before and after running the command.

Default right-click context menu:

![Default right-click context menu](/assets/Posts/2025-02-07-Windows-settings-I-always-adjust/right-click-context-menu-default.png)

Right-click context menu with all options shown:

![Right-click context menu with all options shown](/assets/Posts/2025-02-07-Windows-settings-I-always-adjust/right-click-context-menu-with-all-options-shown.png)

From a PowerShell prompt, run the following command to always show all options in the context menu:

```powershell
reg.exe add "HKCU\Software\Classes\CLSID\{86ca1aa0-34aa-4e8b-a509-50c905bae2a2}\InprocServer32" /f /ve
```

To revert back to the default behavior, run:

```powershell
reg.exe delete "HKCU\Software\Classes\CLSID\{86ca1aa0-34aa-4e8b-a509-50c905bae2a2}" /f
```

[Source and more info](https://www.elevenforum.com/t/disable-show-more-options-context-menu-in-windows-11.1589/)

> NOTE: Windows 11 updated to allow pressing <kbd>Shift</kbd> + <kbd>Right-click</kbd> to show all options in the context menu, so you may prefer to do that over using this registry tweak.

### Speed up the right-click context menu

When you right-click on a file or folder, the `Send to` menu discovers all of the apps and connected devices you can send the file to, before it actually shows the menu.
We can change this so that it does not enumerate devices until you hover over the `Send to` menu.

From a PowerShell prompt, run the following command:

```powershell
Set-ItemProperty -Path "HKLM:\SOFTWARE\Microsoft\Windows\CurrentVersion\Explorer" -Name "DelaySendToMenuBuild" -Value 1 -Type DWord
```

[Source and more info](https://www.winhelponline.com/blog/hidden-registry-settings-sendto-menu-windows-7/)

### Disable delay of startup apps

Windows puts a delay of 10 seconds before it starts launching apps in the Startup folder.

To disable this delay, from a PowerShell prompt, run the following command:

```powershell
$registryPath = "HKCU:\SOFTWARE\Microsoft\Windows\CurrentVersion\Explorer\Serialize"
if (-not (Test-Path $registryPath)) {
    New-Item -Path $registryPath -Force
}
Set-ItemProperty -Path $registryPath -Name "Startupdelayinmsec" -Value 0 -Type DWord
```

To re-enable the delay, run:

```powershell
Remove-ItemProperty -Path "HKCU:\SOFTWARE\Microsoft\Windows\CurrentVersion\Explorer\Serialize" -Name "Startupdelayinmsec"
```

[Source and more info](https://www.elevenforum.com/t/enable-or-disable-delay-of-running-startup-apps-in-windows-11.9144/)

## Bonus: Microsoft PowerToys

I was planning to keep this post to only native Windows features and settings, but I couldn't resist including Microsoft PowerToys.
PowerToys is kind of Microsoft's way of quickly experimenting and iterating on features that _should_ be in Windows, but aren't.
If a feature gets popular enough, it may be included in Windows natively.

Microsoft PowerToys is an application that adds a ton of great features to Windows, and it is being updated all the time.

See the [Microsoft PowerToys docs](https://learn.microsoft.com/en-us/windows/powertoys/) for extensive documentation on all of the features, and how to install and configure them.

A few of my favourite features are:

- Always On Top: Keep a window always on top of others.
- Find My Mouse: Spotlight the mouse cursor when you press the <kbd>Ctrl</kbd> key twice.
- File Locksmith: Easily unlock files that are in use by other processes.
- Image Resizer: Resize images quickly and easily.
- Color Picker: Get the RGB, HEX, and HSL values of any color on the screen.

You can install PowerToys by running the following command in a terminal, such as PowerShell:

```shell
winget install --id Microsoft.PowerToys --source winget
```

## Conclusion

I hope you found some of these settings and features helpful.
Have a favourite or one that I didn't mention?
Let me know in the comments!

Happy customizing!
