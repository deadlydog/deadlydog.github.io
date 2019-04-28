---
id: 899
title: Continuously Deploy Your ClickOnce Application From Your Build Server
date: 2017-01-10T02:13:33-06:00
author: deadlydog
layout: post
guid: http://dans-blog.azurewebsites.net/?p=899
permalink: /continuously-deploy-your-clickonce-application-from-your-build-server/
categories:
  - Build
  - ClickOnce
  - Deploy
tags:
  - Build
  - ClickOnce
  - Continuous
  - Deploy
  - Publish
  - VSTS
---
ClickOnce applications are a great and easy way to distribute your applications to many users, and have the advantage of offering automatic application updates out-of-the-box. Even though ClickOnce applications are super easy to deploy from Visual Studio (literally 3 clicks, just click Build –> Publish –> Finish), you may still want to have your build system publish the updates for various reasons, such as:

  1. You don’t want you (or your team members) to have to remember to manually publish a new version all the time; even if it is very quick and easy. 
  2. If you are signing your ClickOnce application (to make your app more secure and avoid annoying Windows security warnings during every update), your team members may not have the certificate installed, or you may not want them to know the certificate password. 
  3. All of the other benefits of deploying automatically from a build server; you can be sure the project was built in Release mode, all unit tests have run and passed, you are only publishing a new version of the application from a specific branch (e.g. master), etc. 

In this post I’m going to show how to continuously deploy your ClickOnce application using Visual Studio Team Services (VSTS), but you can adopt what’s shown here to work on any build system, such as Team City or Jenkins.

&#160;

### 

### Step 1 – Configure and publish your ClickOnce application manually

Before we can publish a new version of the application from the build server, we first have to build the project to create the artifacts to publish. And even before that, we have to make sure the project is setup properly with all of the required ClickOnce metadata. You will typically do this by going into your project Properties page and going to the Publish tab. There are several other websites and blog posts that discuss configuring a project for ClickOnce deployment, including the official MSDN documentation for <a href="https://msdn.microsoft.com/en-us/library/ff699224.aspx" target="_blank">configuring</a> and <a href="https://msdn.microsoft.com/en-us/library/748fh114.aspx" target="_blank">publishing</a> ClickOnce applications, so I won’t go into it any further here.

Basically you should have your project in a state where you can easily publish a new version manually, and it is configured the way that you want (includes the necessary files, has the destination to publish to specified, specifies if the application is only available online or not, etc.). Once you’ve published your project manually and confirmed that it’s configured the way you like, we can move onto the next step.

&#160;

### Step 2 – Setup the build on your build server

On your build server you will want to configure a new vanilla build that builds your project/solution. There are a couple modifications you will need to make that are different from building a regular console app or class library project.

The first difference is that you will need to provide the “/target:Publish” argument to MSBuild when it builds your project. Here is what this looks like in VSTS:

<a href="http://dans-blog.azurewebsites.net/wp-content/uploads/2017/01/Build-MSBuildPublishTarget.png" target="_blank"><img src="http://dans-blog.azurewebsites.net/wp-content/uploads/2017/01/Build-MSBuildPublishTarget.png" /></a>

This will cause MSBuild to build the required artifacts into an “app.publish” directory. e.g. bin\Debug\app.publish.

The next difference is that you will want to copy that “app.publish” directory to your build artifacts directory. To do this, you will need to add a Copy Files step into your build process that copies the “app.publish” directory from the ClickOnce project’s bin directory to where the build artifacts are expected to be. You will want to do this before the step that publishes your build artifacts. Here is what this looks like in VSTS:

<a href="http://dans-blog.azurewebsites.net/wp-content/uploads/2017/01/Build-CopyFilesToArtifactsDirectory.png" target="_blank"><img src="http://dans-blog.azurewebsites.net/wp-content/uploads/2017/01/Build-CopyFilesToArtifactsDirectory.png" /></a>

So we copy the files into the build artifacts directory, and then the Publish Build Artifacts step at the end will copy those files to wherever you’ve specified; in my case it’s a network share.

If you like you can now run the build and see if it succeeds. If the build fails with an error relating to an expired certificate or pfx file, see <a href="http://dans-blog.azurewebsites.net/creating-a-pfx-certificate-and-applying-it-on-the-build-server-at-build-time/" target="_blank">my other blog post on importing the required certificate on the build server at build-time</a>, which involves adding one more “Import-PfxCertificate.ps1” build step before the MSBuild step.

For completeness sake, this is what my Publish Build Artifacts step looks like in VSTS, and you’ll also notice the “Import-PfxCertificate.ps1” step before the MSBuild step as well:

<a href="http://dans-blog.azurewebsites.net/wp-content/uploads/2017/01/Build-PublishBuildArtifacts.png" target="_blank"><img src="http://dans-blog.azurewebsites.net/wp-content/uploads/2017/01/Build-PublishBuildArtifacts.png" /></a>

So we now have the ClickOnce artifacts being generated and stored in the appropriate directory. If you wanted, you could publish the build artifacts to the ClickOnce application’s final destination right now (instead of a file share as I’ve done here), but I’m going to follow best practices and separate the application “build” and “deployment” portions into their respective subsystems, as you may want separate control over when a build gets published, or maybe you don’t want to publish EVERY build; only some of them.

Hopefully at this point you are able to create a successful build, but we’re not done yet.

&#160;

### Step 3 – Publish the build artifacts to the ClickOnce application’s destination

Now that we have the build artifacts safely stored, we can publish them to the ClickOnce application’s destination. With VSTS this is done by using the Release subsystem. So create a new Release Definition and setup an Environment for it. By default it adds a “Copy and Publish Build Artifacts” step to the release definition. When configuring and using this default step, I received the error:

<pre>[error]Copy and Publish Build Artifacts task is not supported within Release</pre>

So instead I removed that default step and added a simple “Copy Files” step, and then configured it to grab the files from the file share that the build published the artifacts to, and set the destination to where the ClickOnce application was publishing to when I would do a manual publish from within Visual Studio. Here is what that step looks like in VSTS:

<a href="http://dans-blog.azurewebsites.net/wp-content/uploads/2017/01/Release-PublishBuildArtifacts.png" target="_blank"><img src="http://dans-blog.azurewebsites.net/wp-content/uploads/2017/01/Release-PublishBuildArtifacts.png" /></a>

You should be able to run this release definition and see that it is able to post a new version of your ClickOnce application. Hooray! I setup my releases to automatically publish on every build, but you can configure yours however you like.

If you make changes and commit them, the build should create new artifacts, and the release should be able to publish the new version. However, if you launch your ClickOnce application, you may be surprised that it doesn’t actually update to the latest version that you just committed and published. This is because we need one additional build step to actually update the ClickOnce version so it can detect that a new version was published. If you launch another build and publish them, you’ll see that the files being published are overwriting the previous files, instead of getting published to a new directory as they are supposed to be.

You may be wondering why we need another build step to update the ClickOnce version. Part of the build artifacts that are created are a .application manifest file, and a directory that contains the applications files, both of which have the ClickOnce version in them. Can’t we just modify the directory name and .application manifest file to increment the old version number? This was my initial thought, but the .application manifest file contains a cryptography hash to ensure that nobody has tampered with it, meaning that it needs to contain the proper version number at the time that it is generated.

&#160;

### Step 4 – One more build step to update the ClickOnce version

The ClickOnce version is defined in your project file (.csproj/.vbproj) (as the <ApplicationVersion> and <ApplicationRevision> xml elements), and is different from the assembly version that you would typically set to version your .dll/.exe files via the AssemblyInfo.cs file. Unless you manually updated the ClickOnce version in Visual Studio, it will still have its original version. When publishing the ClickOnce version in Visual Studio, Visual Studio automatically takes care of incrementing ClickOnce version’s Revision part. However, because we’re publishing from our build system now, we’ll need to come up with an alternative method. To help solve this problem, I have created <a href="https://github.com/deadlydog/Set-ProjectFilesClickOnceVersion" target="_blank">the Set-ProjectFilesClickOnceVersion GitHub repository</a>. This repository houses a single <a href="https://github.com/deadlydog/Set-ProjectFilesClickOnceVersion/releases" target="_blank">Set-ProjectFilesClickOnceVersion.ps1 PowerShell script</a> that we will want to download and add into our build process.

In order for the build system to access this Set-ProjectFilesClickOnceVersion.ps1 script you will need to check it into source control. Also, you need to make sure you add this build step before the MSBuild step. Here is what this step looks like in VSTS:

<a href="http://dans-blog.azurewebsites.net/wp-content/uploads/2017/01/Build-SetProjectFilesClickOnceVersion.png" target="_blank"><img src="http://dans-blog.azurewebsites.net/wp-content/uploads/2017/01/Build-SetProjectFilesClickOnceVersion.png" width="660" height="255" /></a>

The Script Filename points to the location of the Set-ProjectFilesClickOnceVersion.ps1 script in my repository. Also note that I set the Working Folder to the directory that contains the .csproj project file. This is necessary since we are dealing with relative paths, not absolute ones.

For the Arguments I provide the name of the project file that corresponds to my ClickOnce application, as well as the VSTS Build ID. The Build ID is a globally unique ID that gets auto-incremented with every build, and most (all?) build systems have this. The script will translate this Build ID into the Build and Revision version number parts to use for the ClickOnce version, to ensure that they are always increasing in value. So you should never need to manually modify the ClickOnce version, and every build should generate a version number greater than the last, allowing the ClickOnce application to properly detect when a newer version has been published.

Maybe you update your application’s version number on every build, and want your ClickOnce version to match that version. If this is the case, then use the –Version parameter instead of –BuildSystemsBuildId. The other alternative is to use the –IncrementProjectFilesRevision switch to simply increment the ClickOnce Revision that is already stored in the project file. If you use this switch, you need to be sure to check the project file back into source control so that the Revision is properly incremented again on subsequent builds. I like to avoid my build system checking files into source control wherever possible, so I have opted for the BuildSystemsBuildId parameter here.

The last argument I have included is the UpdateMinimumRequiredVersionToCurrentVersion parameter, and it is optional. If provided, this parameter will change the ClickOnce application’s Minimum Required Version to be the same as the new version, forcing the application to always update to the latest version when it detects that an update is available. I typically use this setting for all of my ClickOnce applications. If you still plan on publishing your ClickOnce applications from Visual Studio, but want this functionality, simply install the <a href="https://www.nuget.org/packages/AutoUpdateProjectsMinimumRequiredClickOnceVersion" target="_blank">AutoUpdateProjectsMinimumRequiredClickOnceVersion NuGet Package</a>.

That’s it! Now if you launch another build it should create new artifacts with a larger ClickOnce version number, and once published your application should update to the new version.

&#160;

### Bonus – Displaying the ClickOnce version in your application

The ClickOnce application version number may be different than your assembly version number, or perhaps you don’t update your product’s version number on every publish; it’s easy to forget. I typically like to use <a href="http://semver.org/" target="_blank">semantic versioning</a> for my projects, which only involves the first 3 version parts. This leaves the last version part, the Revision number, available for me to set as I please. I will typically use the ClickOnce Revision in my application’s displayed version number. This makes it easy to tell which actual release a client has installed in the event that the product version was not updated between releases. The code to do it is fairly simple.

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:e407b4b7-b7a6-4431-aa04-8b7e1110b834" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal> 
  
  <pre class="brush: csharp; pad-line-numbers: true; title: ; notranslate" title="">
public Version ApplicationVersion = new Version("1.11.2");

private void MainWindow_Loaded(object sender, RoutedEventArgs e)
{
	// If this is a ClickOnce deployment, append the ClickOnce Revision to the version number (as it gets updated with each publish).
	if (ApplicationDeployment.IsNetworkDeployed)
		ApplicationVersion = new Version(ApplicationVersion.Major, ApplicationVersion.Minor, ApplicationVersion.Build, ApplicationDeployment.CurrentDeployment.CurrentVersion.Revision);

	// Display the Version as part of the window title.
	wndMainWindow.Title += ApplicationVersion;
}
</pre>
</div>

<a href="https://gist.github.com/deadlydog/17292d3cd5e6f81409025d843184078e" target="_blank">Here</a> I hard-code the product version that I want displayed in my application in a variable called ApplicationVersion. When the WPF application is launched, I obtain the ClickOnce Revision and append it onto the end of the version number. I then display the version in my application’s window title, but you might want to show it somewhere else, such as in an About window. If you want, you could even display both the full application version and full ClickOnce version.

&#160;

I hope this blog has helped you further your automation-foo. Happy coding!