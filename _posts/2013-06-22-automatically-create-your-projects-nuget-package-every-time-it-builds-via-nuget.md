---
title: Automatically Create Your Project's NuGet Package Every Time It Builds, Via NuGet
date: 2013-06-22T10:16:37-06:00
permalink: /automatically-create-your-projects-nuget-package-every-time-it-builds-via-nuget/
categories:
  - Build
  - NuGet
  - PowerShell
  - Visual Studio
tags:
  - After
  - Automatic
  - Automatically
  - Build
  - Create
  - New
  - NuGet
  - pack
  - Package
  - project
  - push
  - Visual Studio
---

So you’ve got a super awesome library/assembly that you want to share with others, but you’re too lazy to actually use NuGet to package it up and upload it to the gallery; or maybe you don’t know how to create a NuGet package and don’t have the time or desire to learn. Well, my friends, now this can all be handled for you automatically.

A couple weeks ago [I posted about a new PowerShell script that I wrote](/create-and-publish-your-nuget-package-in-one-click-with-the-new-nugetpackage-powershell-script/) and put up on CodePlex, called [New-NuGetPackage PowerShell Script](https://newnugetpackage.codeplex.com/), to make creating new NuGet packages quick and easy. Well, I’ve taken that script one step further and use it in a new NuGet package called [Create New NuGet Package From Project After Each Build](https://nuget.org/packages/CreateNewNuGetPackageFromProjectAfterEachBuild/) (real creative name, right) that you can add to your Visual Studio projects. The NuGet package will, you guessed it, pack your project and its dependencies up into a NuGet package (i.e. .nupkg file) and place it in your project’s output directory beside the generated dll/exe file. Now creating your own NuGet package is as easy as adding a NuGet package to your project, which if you’ve never done before is dirt simple.

I show how to add the NuGet package to your Visual Studio project in [the New-NuGetPackage PowerShell Script documentation](https://newnugetpackage.codeplex.com/wikipage?title=NuGet%20Package%20To%20Create%20A%20NuGet%20Package%20From%20Your%20Project%20After%20Every%20Build) (hint: search for __"New NuGet Package"__ (include quotes) to find it in the VS NuGet Package Manager search results), as well as how you can push your package to the NuGet Gallery in just a few clicks.

Here’s a couple screenshots from the documentation on installing the NuGet Package:

![Navigate To Manage NuGet Packages](/assets/Posts/2013/06/NavigateToManageNugetPackages.png) ![Install NuGet Package From Package Manager](/assets/Posts/2013/06/InstallNuGetPackageFromPackageManager.png)

Here you can see the new PostBuildScripts folder it adds to your project, and that when you build your project, a new .nupkg file is created in the project’s Output directory alongside the dll/exe.

![Files Added To Project](/assets/Posts/2013/06/FilesAddedToProject.png) ![NuGet Package In Output Directory"](/assets/Posts/2013/06/NuGetPackageInOutputDirectory.png)

So now that packaging your project up in a NuGet package can be fully automated with about 30 seconds of effort, and you can push it to the [NuGet Gallery](https://nuget.org/) in a few clicks, there is no reason for you to not share all of the awesome libraries you write.

Happy coding!
