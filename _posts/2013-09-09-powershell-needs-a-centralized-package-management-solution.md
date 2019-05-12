---
id: 399
title: PowerShell Needs A Centralized Package Management Solution
date: 2013-09-09T14:20:06-06:00
guid: http://dans-blog.azurewebsites.net/?p=399
permalink: /powershell-needs-a-centralized-package-management-solution/
categories:
  - PowerShell
tags:
  - Automatic
  - Centralize
  - Centralized Package Management
  - Download
  - Package
  - PowerShell
---
**TL;DR** – PowerShell needs centralized package management.&#160; Please [go up-vote this request to have it added to PowerShell](https://connect.microsoft.com/PowerShell/feedback/details/800050/centralized-package-management-for-powershell).

* * *

I love PowerShell, and I love writing reusable PowerShell modules.&#160; They work great when I am writing scripts for myself.&#160; The problem comes in when I write a script that depends on some modules, and I then want to share that script with others.&#160; I basically have 2 options:

  1. Track down all of the module files that the script depends on, zip them all up, and send them to the recipient along with instructions such as, “Navigate to this folder on your PC, create a new folder with this name, copy file X to this location, rinse, repeat…”.
  2. Track down all of the module files that the script depends on and copy-paste their contents directly into the top of the script file, so I just send the user one very large file.

Neither of these solutions are ideal.&#160; Maybe I’m missing something?&#160; In my opinion, PowerShell really needs centralized package management; something similar to [Ruby Gems](http://rubygems.org/) would be great.&#160; Basically a website where users can upload their scripts with a unique ID, and then in their PowerShell script at the top of the file just list the modules that the script depends on.&#160; If the modules are not installed on that PC yet, then they would automatically be downloaded and installed.&#160; This would make PowerShell so much more convenient, and I believe it would help drive more users to write reusable modules and avoid duplicating modules that have already been written (likely better) by others.

In order for this to work though, it has to be baked directly into the PowerShell architecture by the PowerShell team; it’s not something that a 3rd party could do.&#160; So to try and bring this feature request to Microsoft’s attention, I have create [a Suggestion on the MS Connect site.&#160; Please go up-vote it](https://connect.microsoft.com/PowerShell/feedback/details/800050/centralized-package-management-for-powershell).

Before thinking to create a feature request for this (duh), I spammed some of my favourite PowerShell Twitter accounts (@[JamesBru](http://twitter.com/JamesBru) @[ShayLevy](http://twitter.com/ShayLevy) @[dfinke](http://twitter.com/dfinke) @[PowerShellMag](http://twitter.com/PowerShellMag) @[StevenMurawski](http://twitter.com/StevenMurawski) @[JeffHicks](http://twitter.com/JeffHicks) @[ScriptingGuys](http://twitter.com/ScriptingGuys)) to bring it to their attention and get their thoughts; sorry about that guys!&#160; This blog’s comments are a better forum than Twitter for discussing these types of things.

If you have thoughts on centralized package management for PowerShell, or have a better solution for dealing with distributing scripts that depend on modules, please leave a comment below. Thanks.

Happy coding!

[Update]

While PowerShell does not provide a native module management solution, Joel “Jaykul” Bennett [has written one](http://poshcode.org/PoshCode.psm1) and all of the modules are hosted at <http://poshcode.org/>, although I believe it can download modules from other sources as well (e.g. GitHub or any other URL).&#160; One place that it cannot download files from is CodePlex since CodePlex does not provide direct download links to the latest versions of files or to their download links (it is done through Javascript).&#160; Please go up-vote [this issue](https://codeplex.codeplex.com/workitem/26859) and [this issue](https://codeplex.codeplex.com/workitem/25828) to try and get this restriction removed.
