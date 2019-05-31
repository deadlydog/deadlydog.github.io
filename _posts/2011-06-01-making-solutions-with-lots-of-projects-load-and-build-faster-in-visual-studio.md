---
id: 59
title: Making solutions with lots of projects load and build faster in Visual Studio
date: 2011-06-01T23:32:00-06:00
guid: https://deadlydog.wordpress.com/?p=59
permalink: /making-solutions-with-lots-of-projects-load-and-build-faster-in-visual-studio/
jabber_published:
  - "1353108790"
categories:
  - Productivity
  - Visual Studio
  - Visual Studio Extensions
tags:
  - project
  - sln
  - solution
  - unload
  - visual studio
---

I came across [this great article](http://blogs.msdn.com/b/jjameson/archive/2009/03/06/large-visual-studio-solutions-by-loading-unloading-projects.aspx) which talks about simply unloading projects from a solution to make the solution load and build faster in Visual Studio. This is great, as some of the solution files I work in contain over 300 projects and it can sometimes take a while to load. Also, because the information about which projects to load is stored in the .suo file (not the .sln file itself), this can be configured per developer so they only have to load the projects that they work in (the .suo files should never be stored in source control).

Now, unloading 300 projects one at a time would take forever, but luckily there the Visual Studio 2010 extension [PowerCommands](http://visualstudiogallery.msdn.microsoft.com/e5f41ad9-4edc-4912-bca3-91147db95b99/) allows us to quickly Load or Unload all of the projects in the solution (or a solution folder) with a couple clicks. So I typically just unload all of the projects in the solution, and then enable the ones I am working with.

![Unload Projects From Solution](/assets/Posts/2012/11/unload-projects-from-solution.png)

**The one caveat with this** is that because Visual Studio only builds the projects that are loaded, if you get your team's latest code from source control and then try to build the solution from within visual studio, **the build may fail** since it will only build the projects you have loaded, and these may depend on changes made to the other projects that you don't have loaded. So you can easily reload all of the projects in the solution, build, and then unload all the ones you don't want again, or what I prefer to do is simply [build the solution from MSBuild](http://geekswithblogs.net/deadlydog/archive/2011/06/01/setting-up-keyboard-shortcut-to-build-solution-in-msbuild.aspx), as this ignores the .suo file and builds all projects referenced in the .sln file. I often build my solution files from MSBuild anyways since it has the added benefit of not locking up my visual studio UI while building :).

Happy Coding!
