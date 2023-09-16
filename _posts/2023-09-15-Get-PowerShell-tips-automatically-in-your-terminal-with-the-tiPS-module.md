---
title: "Get PowerShell tips automatically in your terminal with the tiPS module"
permalink: /Get-PowerShell-tips-automatically-in-your-terminal-with-the-tiPS-module/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - PowerShell
  - Learning
tags:
  - PowerShell
  - Learning
---

There are a lot of great PowerShell tips out there, but we often don't proactively look for them.
It's hard to know what you don't know, and easy to miss out on some great tips that could make your life easier.
The [tiPS module](https://www.powershellgallery.com/packages/tiPS) can be used to display a PowerShell tip on demand, or automatically when you open a new PowerShell session, helping to broaden your PowerShell knowledge and stay up-to-date.

## Installation

Install and configure the tiPS module by running the following commands in a PowerShell terminal:

```powershell
Install-Module -Name tiPS -Scope CurrentUser
Add-TiPSImportToPowerShellProfile
Set-TiPSConfiguration -AutomaticallyWritePowerShellTip Daily -AutomaticallyUpdateModule Weekly
```

## Display a tip

To display a PowerShell tip, simply run the `Write-PowerShellTip` command, or use its alias `tips`.

## Demo

Here's a quick demo of installing tiPS and getting tips on demand, and then configuring tiPS to automatically display a tip every time you open a new PowerShell session:

![tiPS demo](/assets/Posts/2023-09-15-Get-PowerShell-tips-automatically-in-your-terminal-with-the-tiPS-module/InstallAndConfigureTiPSModule.gif)

While the demo shows displaying a new tip on every PowerShell session, I recommend configuring it to show a new tip daily so that you don't get too distracted by tips while doing your day-to-day work.

## More info

tiPS is cross-platform, so it works on Windows, macOS, and Linux.
It supports both Windows PowerShell (e.g. v5.1) and PowerShell Core (e.g. v7+).

tiPS is open source and intended to be community driven.
If you have a PowerShell tip, module, blog post, or community event that you think others would find useful, submit a pull request to have it added.

Checkout [the tiPS GitHub repo](https://github.com/deadlydog/PowerShell.tiPS) for more information.

Happy scripting!
