---
id: 175
title: Force your ClickOnce app to update without prompting user – Now on NuGet!
date: 2013-03-08T14:26:10-06:00
guid: https://deadlydog.wordpress.com/?p=175
permalink: /force-your-clickonce-app-to-update-without-prompting-user-now-on-nuget/
jabber_published:
  - "1362774373"
categories:
  - ClickOnce
  - NuGet
tags:
  - Automatic
  - ClickOnce
  - Latest
  - Minimum
  - NuGet
  - Required
  - Update
  - Version
---

A while ago [I blogged about a powershell script I made](http://dans-blog.azurewebsites.net/?p=82) that would automatically update your ClickOnce project file’s Minimum Required Version to the latest version of your application so that users would not be presented with the "There is an update for this application. Do you want to install it?" prompt; instead the application would just automatically download and install the update.  This is nice because it’s one less prompt the end-user has to see, and from a security standpoint it means that your users will be forced to always use the latest version of the app.

There was a bit of setup to get this to work.  You had to make sure the powershell script was in the proper directory and add a post-build event to your project.  I’m happy to announce that I’ve significantly updated the powershell script with more features and bug fixes, and __you can now install it from NuGet, which will handle all of the setup for you__.  So now making sure that your end-users are always using the latest version of your application is as easy as adding the [AutoUpdateProjectsMinimumRequiredClickOnceVersion NuGet package](https://nuget.org/packages/AutoUpdateProjectsMinimumRequiredClickOnceVersion) to your ClickOnce project.

![ManageNuget](/assets/Posts/2013/03/managenuget.png)

![Install](/assets/Posts/2013/03/install.png)

![File Added](/assets/Posts/2013/03/fileadded.png)

As you can see in the last screenshot, it adds a new "PostBuildScripts" folder to your project that contains the powershell script that is ran from the project’s post-build event.

A couple caveats to note.  Because this uses PowerShell it means that it can only be ran on Windows machines (sorry Mac and Unix).  Also, because the powershell script modifies the .csproj/.vbproj file outside of Visual Studio, the first time you do a build after creating a new ClickOnce version, if you have any files from that project open you will be prompted to reload the project.  In order to prevent this from closing your open tabs, I recommend installing [Scott Hanselman’s Workspace Reloader Visual Studio extension](http://visualstudiogallery.msdn.microsoft.com/6705affd-ca37-4445-9693-f3d680c92f38).

I’ve also created [a CodePlex site to host this open source project](http://aupmrcov.codeplex.com/).

I hope you find this helpful.  Feel free to leave a comment.
