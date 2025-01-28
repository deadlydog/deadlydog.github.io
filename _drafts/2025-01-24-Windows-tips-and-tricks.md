---
title: "Windows tips and tricks"
permalink: /Windows-tips-and-tricks/
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
Also, if you have any favorites that I don't have listed here, please let me know in the comments below.

## Things I modify after a fresh Windows install

Some commands shown below may need to be ran from an elevated PowerShell prompt (i.e. run PowerShell as an Administrator), otherwise you may get an error like `Requested registry access is not allowed`.

> NOTE: Modifying the registry can be dangerous if you don't know what you're doing, so be sure to [backup your registry](https://www.tweaking.com/how-to-backup-whole-registry-in-windows-10-step-by-step-guide/) before making any changes.

I include PowerShell commands instead of manual steps for modifying the registry to help ensure that only the intended keys are modified.

### Disable searching the web in the Start Menu search

When I search from the Start Menu, I want it to only search my local machine.

From a PowerShell prompt, run the following command to disable web search in the Start Menu:

```powershell
Set-ItemProperty -Path "HKCU:\SOFTWARE\Microsoft\Windows\CurrentVersion\Search" -Name "BingSearchEnabled" -Value 0 -Type DWord
```

To revert back to the default behavior, run:

```powershell
Set-ItemProperty -Path "HKCU:\SOFTWARE\Microsoft\Windows\CurrentVersion\Search" -Name "BingSearchEnabled" -Value 1 -Type DWord
```

Here are screenshots before and after disabling the web search:

![Before disabling web search](/assets/Posts/2025-01-24-Windows-tips-and-tricks/start-menu-search-before-disabling-web-search.png)

![After disabling web search](/assets/Posts/2025-01-24-Windows-tips-and-tricks/start-menu-search-after-disabling-web-search.png)

Note that it also disables Copilot, which may or may not be desired.

### Always open "More options" context menu in Windows 11+

Windows 11 modified the right-click context menu to only show a few options by default, and you have to click "More options" to see the rest.
I prefer the previous Windows behavior where all options are shown by default.

From a PowerShell prompt, run the following command to always show all options in the context menu:

```powershell
reg.exe add "HKCU\Software\Classes\CLSID\{86ca1aa0-34aa-4e8b-a509-50c905bae2a2}\InprocServer32" /f /ve
```

To revert back to the default behavior, run:

```powershell
reg.exe delete "HKCU\Software\Classes\CLSID\{86ca1aa0-34aa-4e8b-a509-50c905bae2a2}" /f
```

[Source and more info](https://www.elevenforum.com/t/disable-show-more-options-context-menu-in-windows-11.1589/)

Below are screenshots of the context menu before and after running the command.

Default right-click context menu:

![Default right-click context menu](/assets/Posts/2025-01-24-Windows-tips-and-tricks/right-click-context-menu-default.png)

Right-click context menu with all options shown:

![Right-click context menu with all options shown](/assets/Posts/2025-01-24-Windows-tips-and-tricks/right-click-context-menu-with-all-options-shown.png)

### Speed up the right-click context menu

When you right-click on a file or folder, the `Send to` menu discovers all of the apps and connected devices you can send the file to, before it actually shows the menu.
We can change this so that it does not enumerate devices until you hover over the `Send to` menu.

From a PowerShell prompt, run the following command:

```powershell
Set-ItemProperty -Path "HKLM:\SOFTWARE\Microsoft\Windows\CurrentVersion\Explorer" -Name "DelaySendToMenuBuild" -Value 1 -Type DWord
```

[Source](https://www.winhelponline.com/blog/hidden-registry-settings-sendto-menu-windows-7/)



## Keyboard shortcuts



## Task bar shortcuts

There are a few shortcuts you can use with the task bar:

- <kbd>Shift</kbd> + <kbd>Left-click</kbd>: Opens another instance of the application
- <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>Left-click</kbd>: Opens another instance of the application as an administrator
