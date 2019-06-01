---
id: 97
title: AutoHotkey cannot interact with Windows 8...or can it!
date: 2012-09-10T13:50:00-06:00
guid: https://deadlydog.wordpress.com/?p=97
permalink: /autohotkey-cannot-interact-with-windows-8-windowsor-can-it/
jabber_published:
  - "1353354696"
categories:
  - AutoHotkey
  - Windows 8
tags:
  - AHK
  - AutoHotkey
  - Run As Admin
  - Run at Startup
  - Windows 8
---

__Update__: Before you go running your script as an admin, see if [this less obtrusive fix](http://dans-blog.azurewebsites.net/get-autohotkey-to-interact-with-admin-windows-without-running-ahk-script-as-admin/) will solve your problems.

If you’ve installed Windows 8 and are trying to use AutoHotkey (AHK) to interact with some of the Windows 8 Windows (such as the Control Panel for example), or with apps that need to be Ran As Administrator, then you’ve likely [become very frustrated as I did](http://www.autohotkey.com/community/viewtopic.php?f=1&t=92147) to discover that AHK can not send any commands (keyboard or mouse input) to these windows. This was a huge concern as I often need to run Visual Studio as an administrator and wanted my hotkeys and hotstrings to work in Visual Studio. After a day of fighting I finally realized the answer (and it’s pretty obvious once you think about it). If you want AHK to be able to interact with Windows 8 Windows or apps running as administrator, then __you also need to have your AHK script Run As Administrator__.

If you are like me then you probably have your AHK scripts set to run automatically at login, which means you don’t have the opportunity to right-click on the script and manually tell it to Run As Administrator. Luckily the work around is simple.

First, if you want to have your AHK script (or any program for that matter) run when you log in, create a shortcut to the application and place the shortcut in:

> C:\Users\[User Name]\AppData\Roaming\Microsoft\Windows\Start Menu\Programs\Startup

Note that you will need to replace "[User Name]" with your username, and that "AppData" is a hidden folder so you’ll need to turn on viewing hidden folders to see it (you can also type "shell:startup" in the Windows Explorer path to jump directly to this folder). So by placing that shortcut there Windows will automatically run your script when you log on. Now, to get it to run as an administrator by default, right-click on the shortcut and go to Properties. Under the Shortcut tab, click on the "Advanced..." button and check off "Run as administrator". That’s it. Now when you log onto Windows your script will automatically start up, running as an administrator; allowing it to interact with any application and window like you had expected it to in the first place.

==< EDIT >==

This method works for running AHK scripts that _don’t_ require admin privileges at startup. It only works for running AHK scripts as admin at Windows startup if you have [disabled UAC in the registry in Windows 8](http://www.eightforums.com/system-security/2434-disable-uac-completely.html), which you likely do not want to do (and I had done at the time of writing this article, but have since switched it back on). For a better, UAC-friendly solution to running your AHK scripts as admin at startup, [see my newer post](http://dans-blog.azurewebsites.net/get-autohotkey-script-to-run-as-admin-at-startup/) to actually get your AHK script to run as admin at startup.

If you do need your AHK script to run as admin and plan on manually double-clicking your AHK script to launch it though, then you can still use this trick of create a shortcut and setting it to Run As Admin in order to avoid having to right-click the AHK script and choose Run As Admin.

==</ EDIT >==

![Shortcut file advanced properties](/assets/Posts/2012/11/image_thumb.png)

Happy coding!
