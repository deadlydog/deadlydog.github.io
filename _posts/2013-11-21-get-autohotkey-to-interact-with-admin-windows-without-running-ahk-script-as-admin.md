---
id: 627
title: Get AutoHotkey To Interact With Admin Windows Without Running AHK Script As Admin
date: 2013-11-21T11:22:37-06:00
guid: http://dans-blog.azurewebsites.net/?p=627
permalink: /get-autohotkey-to-interact-with-admin-windows-without-running-ahk-script-as-admin/
categories:
  - AutoHotkey
  - Windows
tags:
  - Admin
  - Administrator
  - AHK
  - Application
  - AutoHotkey
  - Run As Admin
  - UAC
  - Windows
---

A while back I posted about [AutoHotkey not being able to interact with Windows 8 windows and other applications that were Ran As Admin](http://dans-blog.azurewebsites.net/autohotkey-cannot-interact-with-windows-8-windowsor-can-it/). My solution was to run your [AutoHotkey](http://www.autohotkey.com/) (AHK) script as admin as well, and I also showed how to have your AHK script start automatically with Windows, but not as an admin. Afterwards I followed that up with a post about how to [get your AHK script to run as admin on startup](http://dans-blog.azurewebsites.net/get-autohotkey-script-to-run-as-admin-at-startup/), so life was much better, but still not perfect.

## Problems with running your AHK script as admin

1. You may have to deal with the annoying UAC prompt every time you launch your script.
1. Any programs the script launches also receive administrative privileges.

\#1 is only a problem if you haven't set your AHK script to run as admin on startup as I showed in [my other blog post](http://dans-blog.azurewebsites.net/get-autohotkey-script-to-run-as-admin-at-startup/) (i.e. you are still manually launching your script) or you haven't changed your UAC settings to never prompt you with notifications (which some companies restrict) (see screenshot).

![UAC Never Notify](/assets/Posts/2013/11/UAC-Never-Notify1.png)

\#2 was a problem for me. I use [AHK Command Picker](http://ahkcommandpicker.codeplex.com/) every day. A lot. I'm a developer and in order for Visual Studio to interact with IIS it requires admin privileges, which meant that if I wanted to be able to use AHK Command Picker in Visual Studio, I had to run it as admin as well. The problem for me was that I use AHK Command Picker to launch almost all of my applications, which meant that most of my apps were now also running as an administrator. For the most part this was fine, but there were a couple programs that gave me problems running as admin. E.g. With PowerShell ISE when I double clicked on a PowerShell file to edit it, instead of opening in the current (admin) ISE instance, it would open a new ISE instance.

## There is a solution

Today I stumbled across [this post](http://www.autohotkey.com/board/topic/70449-enable-interaction-with-administrative-programs/) on the AHK community forums. Lexikos has provided an AHK script that will digitally sign the AutoHotkey executable, allowing it to interact with applications running as admin, even when your AHK script isn't.

Running his script is pretty straight forward:

1. Download and unzip his EnableUIAccess.zip file; you can also [get it here](/assets/Posts/2014/06/EnableUIAccess2.zip).
1. Double-click the EnableUIAccess.ahk script to run it, and it will automatically prompt you.
1. Read the disclaimer and click OK.
1. On the __Select Source File__ prompt choose the C:\Program Files\AutoHotkey\AutoHotkey.exe file. This was already selected by default for me. (Might be Program Files (x86) if you have 32-bit AHK installed on 64-bit Windows)
1. On the __Select Destination File__ prompt choose the same C:\Program Files\AutoHotkey\AutoHotkey.exe file again. Again, this was already selected by default for me.
1. Click Yes to replace the existing file.
1. Click Yes when prompted to Run With UI Access.

That's it. (Re)Start your AHK scripts and they should now be able to interact with Windows 8 windows and applications running as admin :-)

This is a great solution if you want your AHK script to interact with admin windows, but don't want to run your script as an admin.

## Did you know

If you do want to launch an application as admin, but don't want to run your AHK script as admin, you can use the [RunAs](http://www.autohotkey.com/docs/commands/RunAs.htm) command.

I hope you found this article useful. Feel free to leave a comment.

Happy coding!
