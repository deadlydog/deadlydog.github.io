---
id: 24
title: Friendly visual studio solution names for working with multiple branches
date: 2011-04-29T23:16:00-06:00
author: deadlydog
layout: post
guid: https://deadlydog.wordpress.com/?p=24
permalink: /friendly-visual-studio-solution-names-for-working-with-multiple-branches/
jabber_published:
  - "1353106746"
categories:
  - Visual Studio
  - Visual Studio Extensions
tags:
  - Friendly Name
  - solution
  - visual studio
  - VSCommands
---
<p class="MsoNormal">
  If you have the latest version of the Visual Studio 2010 extension <a href="http://vscommands.com/">VSCommands</a> you can give your solutions friendly names that display in the window’s title bar.  This is nice when you are working in different branches, so that you can differentiate which solution you are actually looking at.  I wrote the following regex to put the Branch Name after the Solution name, so for example if you have the client solution open in both Dev and Release, one will be called “Client.sln – Dev” and the other “Client.sln – Release”.
</p>

<p class="MsoNormal">
  To use this, in Visual Studio go to Tools -> Options -> VSCommands 2010-> IDE Enhancements and then paste in the following (without the quotes):
</p>

<p class="MsoNormal">
  Friendly Name: “{solutionName} &#8211; {branchName}”
</p>

<p class="MsoNormal">
  Friendly Name – Solution Path Regex: &#8220;.*\(?<branchName>.*)\(?<solutionName>.*(?:.sln))&#8221;
</p>

<p class="MsoNormal">
      <a href="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/windowtitle1.png"><img style="background-image:none;padding-top:0;padding-left:0;display:inline;padding-right:0;border-width:0;" title="WindowTitle1" alt="WindowTitle1" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/windowtitle1_thumb.png" width="362" height="64" border="0" /></a>
</p>

<p class="MsoNormal">
  <a href="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/windowtitle2.png"><img style="background-image:none;padding-top:0;padding-left:0;display:inline;padding-right:0;border-width:0;" title="WindowTitle2" alt="WindowTitle2" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/windowtitle2_thumb.png" width="381" height="67" border="0" /></a>
</p>

<p class="MsoNormal">
  Happy coding!
</p>

<p class="MsoNormal">
  <strong>&#8212; Update &#8212;</strong>
</p>

<p class="MsoNormal">
  Here is the new regex that I prefer to use instead now which shows the directories that the solution is sitting in:
</p>

<p class="MsoNormal">
  Friendly Name: “{solutionName} &#8211; {dir1}{dir2}{dir3}”
</p>

<p class="MsoNormal">
  Regex: “.*\(?<dir1>.*)\(?<dir2>.*)\(?<dir3>.*)\(?<solutionName>.*(.sln)Z)”
</p>

<p class="MsoNormal">
  <strong>&#8212; Update 2 for VS 2012 &#8212;</strong>
</p>

<p class="MsoNormal">
  These are the settings that I like to use for VS Commands 11 for VS 2012:
</p>

<p class="MsoNormal">
  Branch Name Regex: “.*\(?<dir1>.*)\(?<dir2>.*)\(?<branchDirectoryName>.*)\(?<solutionFileName>.*(.sln)Z)”
</p>

Branch Name Pattern: “{branchDirectoryName} Branch”

Git Branch Name Pattern: “{git:head} Branch”

Main Window Title Pattern: “{solutionFileName} &#8211; {dir1}{dir2}{branchDirectoryName} ({sln:activeConfig}|{sln:activePlatform})”

Solution Explorer Window Title Pattern: “ &#8211; {solutionFileName} • {vsc:branchName}”