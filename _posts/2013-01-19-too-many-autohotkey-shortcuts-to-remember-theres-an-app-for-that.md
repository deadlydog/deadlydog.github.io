---
id: 165
title: Too Many AutoHotkey Shortcuts To Remember? There’s An App For That!
date: 2013-01-19T15:59:22-06:00
guid: https://deadlydog.wordpress.com/?p=165
permalink: /too-many-autohotkey-shortcuts-to-remember-theres-an-app-for-that/
jabber_published:
  - "1358632765"
categories:
  - AutoHotkey
  - Productivity
tags:
  - AHK
  - AutoHotkey
  - Automate
  - Command
  - Command Picker
  - GUI
  - Picker
---
I love [AutoHotkey](http://www.autohotkey.com/) (AHK). Ever since I discovered it a little over a year ago I am constantly surprised and pleased with what I am able to accomplish with it.&#160; And there in lied my problem.&#160; Out of the box, AHK allows you to trigger your own scripts using hotkeys.&#160; My problem was that I had so many of these little (and some large) scripts to do so many things, that i quickly ran out of hotkeys to use that wouldn’t interfere with other application’s shortcut keys (Visual Studio anyone); also even if I had more hotkeys available, trying to remember which ones did what was a nightmare. To remedy this, I created [AHK Command Picker](http://ahkcommandpicker.codeplex.com/).

[AHK Command Picker](http://ahkcommandpicker.codeplex.com/) is really just a little UI that allows you to quickly run your scripts; so instead of using a hotkey, you just hit the Caps Lock key to bring up the UI, and then start typing for the script that you want to run.&#160; It provides Camel Case filtering to still make launching scripts super fast, and also shows your scripts in a list, allowing you to browse through them. It also allows to you easily pass parameters into your scripts, which can be used to change a script’s behaviour.&#160; Here’s a screenshot showing the UI and some of the many scripts that I have:

[<img title="AHKCommandPicker-AllCommands" style="background-image:none;padding-top:0;padding-left:0;display:inline;padding-right:0;border-width:0;" border="0" alt="AHKCommandPicker-AllCommands" src="/assets/Posts/2013/01/ahkcommandpicker-allcommands_thumb.png" width="599" height="334" />](/assets/Posts/2013/01/ahkcommandpicker-allcommands.png)

And here’s a screenshot showing it filter the list as you type:

[<img title="AHKCommandPicker-FilteredCommands" style="background-image:none;padding-top:0;padding-left:0;display:inline;padding-right:0;border-width:0;" border="0" alt="AHKCommandPicker-FilteredCommands" src="/assets/Posts/2013/01/ahkcommandpicker-filteredcommands_thumb.png" width="600" height="334" />](/assets/Posts/2013/01/ahkcommandpicker-filteredcommands.png)

One of my favourite things about AHK is the community. There are so many scripts out there that before you write your own code, you should just do a quick Google search and chances are you’ll find someone who has already written the script for you, or one that is close to your needs and can be quickly modified.&#160; I assumed this would be the case for the type of tool I was looking for.&#160; So I searched and came across HotKeyIt and Keyword Launcher, both of which were interesting and neat, but neither provided a GUI to quickly see your list of scripts so that you could launch it quickly.&#160; They still relied on you to remember your specific hotkeys; I wanted a list that I could **browse** my scripts from to easily launch them.

I love programming and learning new languages, so I thought I’d give creating the type of picker that I wanted a shot. It’s still not perfect, and I have many ideas for new features that could make it better, but using it as it is today has improved my productivity so much.&#160; I’ve shown it to some people at my work and many of them started using it and agree.

If all you are looking for is a program to quickly launch applications or open files, then I would recommend [KeyBreeze](http://www.keybreeze.com/).&#160; It’s a great little free app written in C# and is very polished (and much better than [Launchy](http://www.launchy.net/) in my opinion because you can add ANY file/app to it via the context menu). But **if you use AHK and want to be able to quickly launch your own scripts**, definitely check out [AHK Command Picker](http://ahkcommandpicker.codeplex.com/). Like myself you will probably find that you go from using AHK to launch 10 – 20 scripts, to using it to launch hundreds or even thousands of scripts, saving you time and effort constantly throughout your day.&#160; Those things that you maybe only do once or twice a month and didn’t want to dedicate a hotkey to will start showing up in your scripts list.

So go ahead and give [AHK Command Picker](http://ahkcommandpicker.codeplex.com/) a try, and be sure to let me know what you think of it and what improvements you would like to see by logging it on the Codeplex site.
