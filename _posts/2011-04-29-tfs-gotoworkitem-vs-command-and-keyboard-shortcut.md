---
id: 31
title: TFS GoToWorkItem VS command and keyboard shortcut
date: 2011-04-29T23:06:00-06:00
author: deadlydog
layout: post
guid: https://deadlydog.wordpress.com/?p=31
permalink: /tfs-gotoworkitem-vs-command-and-keyboard-shortcut/
jabber_published:
  - "1353107208"
categories:
  - Shortcuts
  - TFS
tags:
  - keyboard shortcuts
  - TFS
  - visual studio
---
<p class="MsoNormal">
  The button to jump directly to a work item by specifying its ID looks to be on the Work Item Tracking toolbar by default in VS / TFS 2010.&#160; This button is not on the toolbar by default in VS / TFS 2008 though.&#160; To add it yourself just go to Tools => Customize, then choose the category Team and the command Go To Work Item&#8230;, and you can drag the command into one of your existing toolbars.
</p>

<p class="MsoNormal">
  If you want to setup a keyboard shortcut for the command, just go to Tools => Options => Environment => Keyboard, and the command is called Team.GotoWorkItem.&#160; I map it to Ctrl+Shift+/ since Ctrl+/ is the C# keyboard shortcut to search in a file.
</p>