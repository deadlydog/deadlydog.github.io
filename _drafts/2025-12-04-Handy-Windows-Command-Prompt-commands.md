---
title: "Handy Windows Command Prompt commands"
permalink: /Handy-Windows-Command-Prompt-commands/
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

Below are some handy Windows Command Prompt commands that can help you troubleshoot and manage your system more effectively.

For commands that produce a lot of output, consider redirecting the output to a text file for easier reading.
You can do this by appending `> filename.txt` to the command.
Another option is appending `| clip` when using PowerShell to copy the output directly to the clipboard so you can paste it in a text editor.

| Command                   | Description                                                                                                                                                                                                                 |
| ------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `chkdsk`                  | Checks the file system for logical and physical errors. Can fix some issues automatically using `/f` or `/r` parameters.                                                                                                    |
| `gpupdate /force`         | Forces an immediate update of Group Policy settings. Useful for applying changes without waiting for the next automatic refresh.                                                                                            |
| `ipconfig`                | Displays the current network configuration, including IP address, subnet mask, and default gateway.                                                                                                                         |
| `ipconfig /displaydns`    | Shows the contents of the DNS resolver cache.                                                                                                                                                                               |
| `ipconfig /flushdns`      | Clears the DNS resolver cache, which can help resolve certain connectivity issues.                                                                                                                                          |
| `powercfg /batteryreport` | Generates a detailed battery report for laptops, showing past usage and battery health.                                                                                                                                     |
| `netstat -anob`           | Displays active network connections, their ports, and the associated app name and process ID (PID) using the port. Useful for identifying suspicious connections, and finding which application is using a particular port. |
| `sfc /scannow`            | Scans all protected system files and replaces corrupted files with a cached copy.                                                                                                                                           |
| `whoami`                  | Displays the current logged-in user name along with domain information.                                                                                                                                                     |
| `whoami /groups`          | Lists all the groups the current user belongs to.                                                                                                                                                                           |
| `winget upgrade`          | Lists all installed applications that have available updates via Windows Package Manager.                                                                                                                                   |
| `winget upgrade --all`    | Upgrades all installed applications that have available updates via Windows Package Manager.                                                                                                                                |
| `wmic product get name`   | Lists all installed software on the system. Useful for inventory or troubleshooting purposes.                                                                                                                               |

![Example image](/assets/Posts/2025-12-04-Handy-Windows-Command-Prompt-commands/image-name.png)
