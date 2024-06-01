---
title: "Common Run commands to access various Windows settings and apps"
permalink: /Common-Run-commands-to-access-various-Windows-settings-and-apps/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - Productivity
tags:
  - Productivity
---

The Windows search functionality isn't exactly reliable, especially on Windows Server.
You can memorize how to get to various configuration windows from the Windows Settings menu, but they change and move things around with every major Windows update.
For a consistent way to access various settings and apps, regardless of your Windows version you can use the Run dialog box.

## Opening the Run dialog box

There are two ways to open the Run dialog box:

1. Press <kbd>Win</kbd> + <kbd>R</kbd> on your keyboard, or
1. Press <kbd>Win</kbd> to open the Start menu, type `Run`, and press `Enter`.

The Run dialog box will look something like this:

![Run dialog box](/assets/Posts/2024-06-01-Common-Run-commands-to-access-various-Windows-settings-and-apps/windows-run-dialog-box-screenshot.png)

## Common Run commands

With the Run dialog box open, type in one of the following commands and press `Enter` to open the corresponding Windows setting or app:

| Command                                                             | Description                            |
| ------------------------------------------------------------------- | -------------------------------------- |
| .                                                                   | Open the folder of the current user    |
| appwiz.cpl                                                          | Programs and Features                  |
| charmap                                                             | Windows Character Table                |
| chkdsk                                                              | Check Disk Utility                     |
| cleanmgr                                                            | Disk Cleanup                           |
| cmd                                                                 | The Command Prompt                     |
| compmgmt.msc                                                        | Computer Management                    |
| control                                                             | Control Panel                          |
| control admintools                                                  | Administrative Tools                   |
| control desktop                                                     | Personalization                        |
| control folders                                                     | File Explorer Options                  |
| control folders                                                     | Folder Options                         |
| control keyboard                                                    | Keyboard Properties                    |
| control mouse                                                       | Mouse Properties                       |
| control printers                                                    | Printers and Faxes                     |
| control schedtasks                                                  | Task Scheduler                         |
| control userpasswords2                                              | User Accounts                          |
| control.exe /name Microsoft.NetworkAndSharingCenter                 | Network and Sharing Center             |
| control.exe /name Microsoft.PowerOptions                            | Power Options                          |
| control.exe /name Microsoft.System                                  | System                                 |
| control.exe /name Microsoft.SystemProperties                        | System Properties                      |
| control.exe /name Microsoft.SystemPropertiesAdvanced                | Advanced System Properties             |
| control.exe /name Microsoft.SystemPropertiesDataExecutionPrevention | Data Execution Prevention              |
| control.exe /name Microsoft.SystemPropertiesHardware                | Hardware                               |
| control.exe /name Microsoft.SystemPropertiesPerformance             | Performance Options                    |
| control.exe /name Microsoft.SystemPropertiesProtection              | System Protection                      |
| control.exe /name Microsoft.SystemPropertiesRemote                  | Remote                                 |
| control.exe /name Microsoft.WindowsDefender                         | Windows Security                       |
| control.exe /name Microsoft.WindowsUpdate                           | Windows Update                         |
| desk.cpl                                                            | Display Properties                     |
| devmgmt.msc                                                         | Device Manager                         |
| diskmgmt.msc                                                        | Disk Management                        |
| dxdiag                                                              | DirectX Diagnostic Tool                |
| eventvwr.msc                                                        | Event Viewer                           |
| explorer                                                            | Windows Explorer                       |
| firewall.cpl                                                        | Windows Firewall                       |
| gpedit.msc                                                          | Local Group Policy Editor              |
| inetcpl.cpl                                                         | Internet Properties                    |
| magnify                                                             | Magnifier                              |
| main.cpl                                                            | Mouse Settings                         |
| mdsched                                                             | Windows memory checker                 |
| mmc                                                                 | Microsoft Management Console           |
| mmsys.cpl                                                           | Sound Properties                       |
| mrt                                                                 | Malware Removal Tool                   |
| msconfig                                                            | System Configuration                   |
| msinfo32                                                            | System Information                     |
| mstsc                                                               | Remote Desktop Service                 |
| ncpa.cpl                                                            | Network Connections                    |
| netplwiz                                                            | User accounts                          |
| osk                                                                 | On-Screen Keyboard                     |
| perfmon.msc                                                         | Performance Monitor                    |
| powercfg.cpl                                                        | Power Options                          |
| powershell                                                          | Windows PowerShell Console             |
| psr                                                                 | Steps recorder                         |
| pwsh                                                                | PowerShell Core Console (if installed) |
| regedit                                                             | Registry Editor                        |
| resmon                                                              | Resource Monitor                       |
| secpol.msc                                                          | Local Security Policy                  |
| services.msc                                                        | Services                               |
| shutdown                                                            | Windows Shutdown                       |
| snippingtool                                                        | Screenshot snipping tool               |
| sysdm.cpl                                                           | System Properties                      |
| taskmgr                                                             | Task Manager                           |
| winver                                                              | About Windows                          |
| wscui.cpl                                                           | Security and Maintenance               |
| wuaucpl.cpl                                                         | Windows Update                         |

I've bolded the ones that I personally use the most.

This blog article was inspired by [this LinkedIn post](https://www.linkedin.com/feed/update/urn:li:activity:7202019020282245120/) which shows how to define these in a PowerShell function that can be used to search for the command you want.
