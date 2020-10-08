---
title: TFS GoToWorkItem VS command and keyboard shortcut
date: 2011-04-29T23:06:00-06:00
permalink: /tfs-gotoworkitem-vs-command-and-keyboard-shortcut/
categories:
  - Shortcuts
  - TFS
tags:
  - keyboard shortcuts
  - TFS
  - Visual Studio
---

 The button to jump directly to a work item by specifying its ID looks to be on the Work Item Tracking toolbar by default in VS / TFS 2010. This button is not on the toolbar by default in VS / TFS 2008 though. To add it yourself just go to Tools => Customize, then choose the category Team and the command Go To Work Item..., and you can drag the command into one of your existing toolbars.

If you want to setup a keyboard shortcut for the command, just go to Tools => Options => Environment => Keyboard, and the command is called Team.GotoWorkItem. I map it to <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>/</kbd> since <kbd>Ctrl</kbd> + <kbd>/</kbd> is the C# keyboard shortcut to search in a file.
