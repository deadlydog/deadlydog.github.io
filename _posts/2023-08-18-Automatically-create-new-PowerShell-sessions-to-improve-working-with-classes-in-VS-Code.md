---
title: "Automatically create new sessions to improve PowerShell development with classes in VS Code"
permalink: /Automatically-create-new-sessions-to-improve-PowerShell-development-with-classes-in-VS-Code/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - PowerShell
  - Visual Studio Code
tags:
  - PowerShell
  - Visual Studio Code
---

Developing PowerShell that references classes and binary modules can be a bit painful, as in order to load a new version of the class or module, you need to restart the PowerShell session.
Having to manually restart PowerShell every time you make a change to a class or binary module gets old pretty fast.
Luckily, the Visual Studio Code PowerShell extension has a feature that can help with this.

Once you've installed [the PowerShell extension](https://marketplace.visualstudio.com/items?itemName=ms-vscode.PowerShell) in VS Code, you can enable the `PowerShell › Debugging: Create Temporary Integrated Console` setting.

![Screenshot of the Visual Studio Code setting to enable PowerShell temporary integrated console](/assets/Posts/2023-08-18-Automatically-create-new-PowerShell-sessions-to-improve-working-with-classes-in-VS-Code/vs-code-setting-to-enable-powershell-temporary-integrated-console.png)

Now every time you hit F5 to run a PowerShell script, it will first create a new PowerShell terminal and then run the script in that terminal, ensuring no previous variables are in memory and that the latest version of your classes and binary modules are loaded.
No more manually restarting your PowerShell sessions when developing classes or binary modules!

I hope you find this useful.
For more Visual Studio Code settings you might want to tweak, [check out this post](https://blog.danskingdom.com/Visual-Studio-Code-default-settings-to-change/).

Happy coding!
