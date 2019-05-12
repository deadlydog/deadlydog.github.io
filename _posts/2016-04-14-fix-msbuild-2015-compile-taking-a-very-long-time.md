---
id: 873
title: Fix MSBuild 2015 Compile Taking A Very Long Time
date: 2016-04-14T00:32:24-06:00
guid: http://dans-blog.azurewebsites.net/?p=873
permalink: /fix-msbuild-2015-compile-taking-a-very-long-time/
categories:
  - Build
  - MSBuild
tags:
  - MSBuild msbuild.exe Slow Hangs Build Compile
---
I created the [Invoke-MsBuild PowerShell Module](https://invokemsbuild.codeplex.com/) (also [available in the PowerShell Gallery](https://www.powershellgallery.com/packages/Invoke-MsBuild/)), and recently added support to use the Visual Studio 2015 version of MsBuild.exe, when available. After doing so, I noticed that sometimes the build would take a very long time to complete; a solution that typically would take 10 seconds to compile was all of a sudden taking 10 minutes. When changing Invoke-MsBuild back to defaulting to the Visual Studio 2013 version of MsBuild.exe the problem went away. I thought that maybe there was just something strange with my workstation, however after updating Invoke-MsBuild on our build servers at my work we saw the same thing there.

Luckily, a fellow by the name of Jens Doose contacted me saying that he was experiencing the same problem when using Invoke-MsBuild, and also that he had fixed it. The solution ended up being that when calling MsBuild.exe, we had to specify an additional command-line argument of <font style="background-color: #ffffff"><strong>/p:UseSharedConfiguration=false</strong>.</font>

> <font style="background-color: #ffffff">msbuild.exe someSolution.sln /p:Configuration=Release /p:UseSharedConfiguration=false</font>

I’m not sure what the other implications of providing this UseSharedConfiguration parameter are as I can’t find any documentation of it online, and I’m not really sure how Jens came across it. It does seem to solve the problem of compiling take a long time though, and I haven’t noticed any other side effects, so I’m going to stick with it.

If you run into the same problem with msbuild.exe and this helps you out, leave a comment to let me know. Happy coding!
