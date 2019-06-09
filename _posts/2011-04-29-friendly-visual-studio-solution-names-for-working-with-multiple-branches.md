---
title: Friendly Visual Studio solution names for working with multiple branches
date: 2011-04-29T23:16:00-06:00
permalink: /friendly-visual-studio-solution-names-for-working-with-multiple-branches/
categories:
  - Visual Studio
  - Visual Studio Extensions
tags:
  - Friendly Name
  - solution
  - Visual Studio
  - VSCommands
---

If you have the latest version of the Visual Studio 2010 extension [VSCommands](http://vscommands.com) you can give your solutions friendly names that display in the window’s title bar.  This is nice when you are working in different branches, so that you can differentiate which solution you are actually looking at.  I wrote the following regex to put the Branch Name after the Solution name, so for example if you have the client solution open in both Dev and Release, one will be called "Client.sln – Dev" and the other "Client.sln – Release".

To use this, in Visual Studio go to Tools -> Options -> VSCommands 2010-> IDE Enhancements and then paste in the following:

Friendly Name: `{solutionName} - {branchName}`

Friendly Name – Solution Path Regex: `.*\(?<branchName>.*)\(?<solutionName>.*(?:.sln))`

![Window title 1](/assets/Posts/2012/11/windowtitle1.png)

![Window title 2](/assets/Posts/2012/11/windowtitle2.png)

Happy coding!

### -- Update --

Here is the new regex that I prefer to use instead now which shows the directories that the solution is sitting in:

Friendly Name: `{solutionName} - {dir1}{dir2}{dir3}`

Regex: `.*\(?<dir1>.*)\(?<dir2>.*)\(?<dir3>.*)\(?<solutionName>.*(.sln)Z)`

### -- Update 2 for VS 2012 --

These are the settings that I like to use for VS Commands 11 for VS 2012:

Branch Name Regex: `.*\(?<dir1>.*)\(?<dir2>.*)\(?<branchDirectoryName>.*)\(?<solutionFileName>.*(.sln)Z)`

Branch Name Pattern: `{branchDirectoryName} Branch`

Git Branch Name Pattern: `{git:head} Branch`

Main Window Title Pattern: `{solutionFileName} - {dir1}{dir2}{branchDirectoryName} ({sln:activeConfig}|{sln:activePlatform})`

Solution Explorer Window Title Pattern: `" - {solutionFileName} • {vsc:branchName}"` (without the quotes)
