---
id: 897
title: Creating A .PFX Certificate And Applying It On The Build Server At Build Time
date: 2016-12-01T02:46:41-06:00
guid: http://dans-blog.azurewebsites.net/?p=897
permalink: /creating-a-pfx-certificate-and-applying-it-on-the-build-server-at-build-time/
categories:
  - Build
  - ClickOnce
  - UWP
  - Visual Studio
tags:
  - Build Server
  - Certificate
  - compile
  - PFX
---

There are many project types that require a .pfx file in order to build and/or publish successfully, such as ClickOnce and UWP (Universal Windows Platform) applications. When you build these projects locally in Visual Studio everything is fine and dandy, but when you try to build them on a build server (such as part of a Continuous Integration build) the build server may fail with an error like:

> Error APPX0108: The certificate specified has expired. For more information about renewing certificates, see <http://go.microsoft.com/fwlink/?LinkID=241478>.

> error MSB4018: The "ResolveKeySource" task failed unexpectedly.
> System.InvalidOperationException: Showing a modal dialog box or form when the application is not running in UserInteractive mode is not a valid operation. Specify the ServiceNotification or DefaultDesktopOnly style to display a notification from a service application.

> Cannot import the following key file: companyname.pfx. The key file may be password protected.

These errors will often occur when you are still using the temporary file that was generated automatically when the project was created in Visual Studio, or when using a .pfx file that is password protected.

If your .pfx file is password protected, we can import the certificate at build time, as shown further below. The nice thing about this approach is it does not require you (or one of your company admins) to manually install the certificate on every build server, [as](http://stackoverflow.com/questions/1056997/team-foundation-server-build-with-password-protected-codesigning-fails) [many](http://stackoverflow.com/questions/4025316/signing-assemblies-with-pfx-files-in-msbuild-team-build-and-tfs) [other](http://stackoverflow.com/questions/2815366/cannot-import-the-keyfile-blah-pfx-error-the-keyfile-may-be-password-protec) [sites](http://chamindac.blogspot.ca/2014/02/tfs-build-with-password-protected-pfx.html) [suggest](https://blogs.msdn.microsoft.com/nagarajp/2005/11/08/using-password-protected-signing-keys-in-teambuild/). This way you don't have to bother your admin or get unexpected broken builds when a new build server is added to the pool.

If your .pfx file has expired, you need to remove the current pfx file and add a new (password-protected) one in its place.

## Creating a new .pfx file for a UWP application

To create a new password protected .pfx file in a UWP application,

1. open the _Package.appxmanifest_ file that resides in the root of the project and
1. go to the _Packaging_ tab.
1. In here, click the _Choose Certificate..._ button.
1. In the _Configure Certificate..._ dropdown, choose an existing certificate that you have, or create a new test certificate and provide a password for it. This should create the certificate and add the new .pfx file to your project's root.

![Create Pfx Certificate In UWP App](/assets/Posts/2016/12/Create-Pfx-Certificate-In-UWP-App.png)

## Creating a new .pfx file for a ClickOnce application

Creating a pfx file for a ClickOnce application is similar, but instead you want to

1. open up the project's _Properties_ window (by right-clicking on the project) and
1. go to the _Signing_ tab.
1. Check off the box to _Sign the ClickOnce manifests_ and then
1. choose the certificate you want to use, or create your own password-protected test certificate.

![Create Pfx Certificate In ClickOnce App](/assets/Posts/2016/12/Create-Pfx-Certificate-In-ClickOnce-App.png)

## Applying the .pfx file before building on the build server

Now that the project has a pfx certificate, we will need to update our build server to apply it before attempting to build the solution, otherwise a certificate related error will likely be thrown.

Before building the solution, we will want to apply the certificate using this PowerShell script, _Import-PfxCertificate.ps1_:

```powershell
param($PfxFilePath, $Password)

$absolutePfxFilePath = Resolve-Path -Path $PfxFilePath
Write-Output "Importing store certificate &#39;$absolutePfxFilePath&#39;..."

Add-Type -AssemblyName System.Security
$cert = New-Object System.Security.Cryptography.X509Certificates.X509Certificate2
$cert.Import($absolutePfxFilePath, $Password, [System.Security.Cryptography.X509Certificates.X509KeyStorageFlags]::PersistKeySet)
$store = new-object system.security.cryptography.X509Certificates.X509Store -argumentlist "MY", CurrentUser
$store.Open([System.Security.Cryptography.X509Certificates.OpenFlags]::"ReadWrite")
$store.Add($cert)
$store.Close()
```

You can [download the Import-PfxCertificate Powershell script from here](https://gist.github.com/deadlydog/9f87fba75d611b4f1757af7767aa2d05).

To do this you will need to save this _Import-PfxCertificate.ps1_ file in your version control repository so that the build system has access to it. In my projects I have a root _buildTools_ directory that I store these types of build scripts in. In your build system, you will need to configure a new step to run the PowerShell script before the step to actually build your solution. When you call the script you will need to pass it the path to the .pfx file (which should also be checked into source control), and the password for the .pfx file. If your build system supports using "hidden"/"private" variables, then it is recommended you use them to keep the .pfx file password a secret.

Here I am using VSTS (Visual Studio Team Services), but the same steps should generally apply to any build system (e.g. TeamCity). I have created a new build step called _Apply Store Certificate_ that calls the Import-PfxCertificate.ps1 PowerShell script. This step is set to occur before the _Build solution_ step, and provides the required parameters to the script; the path to the .pfx file and the certificates password (as a hidden variable that I configured in the build system). Notice that I also set the _Working folder_ that the script will run from to the directory that the .pfx resides in, so that the script will be able to resolve the absolute file path.

![VSTS Build Pfx Certificate Step](/assets/Posts/2016/12/VSTS-Build-Pfx-Certificate-Step.png)

Your build system should now be able to apply the certificate before building the solution, avoiding the certificate errors and hopefully resulting in a successful build.

Hopefully this post has helped you out. Happy coding!
