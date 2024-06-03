---
title: "Common Run commands to access various Windows settings and apps"
permalink: /Common-Run-commands-to-access-various-Windows-settings-and-apps/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - Productivity
  - Windows
tags:
  - Productivity
  - Windows
---

There are a lot of different windows for configuring various settings in Windows.
Unfortunately, finding the right window is not always easy.
The Windows Settings menu often buries settings windows several layers deep, and they are often moved between major Windows updates, so it can be hard to find them.
The Start menu search functionality isn't always consistent or reliable, especially on Windows Server, and even the Windows Settings search can be hit or miss.

For a consistent way to access many settings and apps regardless of your Windows version, you can use a command prompt or the
Run dialog box.

## Opening the Run dialog box

There are two main ways to open the Run dialog box:

1. Press <kbd>Win</kbd> + <kbd>R</kbd> on your keyboard, or
1. Press <kbd>Win</kbd> to open the Start menu, type `Run`, and press `Enter`.

The Run dialog box will look something like this:

![Run dialog box](/assets/Posts/2024-06-01-Common-Run-commands-to-access-various-Windows-settings-and-apps/windows-run-dialog-box-screenshot.png)

## Opening a command prompt

Alternatively, you can open a command prompt, such as `cmd` or `PowerShell`.
Here are two ways to do that:

1. Press <kbd>Win</kbd> + <kbd>X</kbd> to open the Windows X menu, then <kbd>i</kbd> to open PowerShell (use <kbd>A</kbd> instead to open a PowerShell Admin console).
1. Press <kbd>Win</kbd> to open the Start menu, type `cmd` or `PowerShell`, and press `Enter`.

## Common Run commands

With either a command prompt or the Run dialog box open, type in one of the following commands and press `Enter` to open the corresponding Windows setting or app.

Note: If using a command prompt, it may need to be running As Admin to open some of these settings windows.
A few commands only work from the Run dialog box and are marked as "(Run only)".

| Command                                         | Description                            |
| ----------------------------------------------- | -------------------------------------- |
| .                                               | Open current user folder (Run only)    |
| appwiz.cpl                                      | Programs and Features                  |
| charmap                                         | Character Map                          |
| cleanmgr                                        | Disk Cleanup                           |
| cmd                                             | The Command Prompt                     |
| compmgmt.msc                                    | Computer Management                    |
| control                                         | Control Panel                          |
| control admintools                              | Administrative Tools                   |
| control desktop                                 | Personalization                        |
| control folders                                 | File Explorer Options                  |
| control keyboard                                | Keyboard Properties                    |
| control mouse                                   | Mouse Properties                       |
| control printers                                | Printers and Faxes                     |
| control schedtasks                              | Task Scheduler                         |
| control userpasswords2                          | User Accounts                          |
| control /name Microsoft.NetworkAndSharingCenter | Network and Sharing Center             |
| control /name Microsoft.PowerOptions            | Power Options                          |
| control /name Microsoft.System                  | System                                 |
| control /name Microsoft.WindowsUpdate           | Windows Update                         |
| desk.cpl                                        | Display Properties                     |
| devmgmt.msc                                     | Device Manager                         |
| diskmgmt.msc                                    | Disk Management                        |
| dxdiag                                          | DirectX Diagnostic Tool                |
| eventvwr.msc                                    | Event Viewer                           |
| explorer                                        | Windows Explorer                       |
| firewall.cpl                                    | Windows Firewall                       |
| gpedit.msc                                      | Local Group Policy Editor              |
| inetcpl.cpl                                     | Internet Properties                    |
| inetmgr                                         | IIS Manager (if installed) (Run only)  |
| lusrmgr.msc                                     | Local Users and Groups                 |
| magnify                                         | Magnifier                              |
| main.cpl                                        | Mouse Settings                         |
| mdsched                                         | Windows Memory Diagnostic              |
| mmc                                             | Microsoft Management Console           |
| mmsys.cpl                                       | Sound Properties                       |
| mrt                                             | Malware Removal Tool                   |
| msconfig                                        | System Configuration                   |
| msinfo32                                        | System Information                     |
| mstsc                                           | Remote Desktop Connection              |
| ncpa.cpl                                        | Network Connections                    |
| netplwiz                                        | User Accounts                          |
| osk                                             | On-Screen Keyboard                     |
| perfmon.msc                                     | Performance Monitor                    |
| powercfg.cpl                                    | Power Options                          |
| powershell                                      | Windows PowerShell Console             |
| psr                                             | Steps Recorder                         |
| pwsh                                            | PowerShell Core Console (if installed) |
| regedit                                         | Registry Editor                        |
| resmon                                          | Resource Monitor                       |
| secpol.msc                                      | Local Security Policy                  |
| services.msc                                    | Services                               |
| snippingtool                                    | Screenshot Snipping Tool               |
| sysdm.cpl                                       | System Properties                      |
| taskmgr                                         | Task Manager                           |
| winver                                          | About Windows                          |
| wscui.cpl                                       | Security and Maintenance               |

This blog article was inspired by [this LinkedIn post](https://www.linkedin.com/feed/update/urn:li:activity:7202019020282245120/) which shows how to define many of these in a PowerShell function that can be used to search for the command you want.

## Conclusion

I hope you find this list of commands useful.
If you have any other commands that you find valuable, please share them in the comments below and I might add them to this list.

Happy running!
