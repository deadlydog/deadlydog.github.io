---
id: 905
title: "Strongly sign your Visual Studio extension's 3rd party assemblies to avoid assembly-loading errors at runtime"
date: 2017-06-12T02:21:50-06:00
guid: http://dans-blog.azurewebsites.net/?p=905
permalink: /strongly-sign-your-visual-studio-extensions-3rd-party-assemblies-to-avoid-assembly-loading-errors-at-runtime/
categories:
  - Visual Studio
  - Visual Studio Extensions
tags:
  - assembly
  - dll
  - Visual Studio
  - Visual Studio Extensions
---

When trying to create a Visual Studio 2017 version of my [Diff All Files Visual Studio extension](https://github.com/deadlydog/VS.DiffAllFiles), I was encountering a runtime error indicating that a module could not be loaded in one of the 3rd party libraries I was referencing (LibGit2Sharp):

```text
System.TypeInitializationException occurred
  HResult=0x80131534
  Message=The type initializer for 'LibGit2Sharp.Core.NativeMethods' threw an exception.
  Source=LibGit2Sharp
  StackTrace:
   at LibGit2Sharp.Core.NativeMethods.git_repository_discover(GitBuf buf, FilePath start_path, Boolean across_fs, FilePath ceiling_dirs)
   at LibGit2Sharp.Core.Proxy.<>c__DisplayClass3e.<git_repository_discover>b__3d(GitBuf buf)
   at LibGit2Sharp.Core.Proxy.ConvertPath(Func`2 pathRetriever)
   at LibGit2Sharp.Core.Proxy.git_repository_discover(FilePath start_path)
   at LibGit2Sharp.Repository.Discover(String startingPath)
   at VS_DiffAllFiles.StructuresAndEnums.GitHelper.GetGitRepositoryPath(String path) in D:\dev\Git\VS.DiffAllFiles\VS.DiffAllFiles\GitHelper.cs:line 39

Inner Exception 1:
DllNotFoundException: Unable to load DLL 'git2-a5cf255': The specified module could not be found. (Exception from HRESULT: 0x8007007E)
```

By using the [Assembly Binding Log Viewer](https://docs.microsoft.com/en-us/dotnet/framework/tools/fuslogvw-exe-assembly-binding-log-viewer), a.k.a. fuslogvw.exe, and being sure to run it "as administrator" so I could modify the settings, I was able to see this error at runtime when it tried to load my extension:

```text
LOG: Binding succeeds. Returns assembly from C:\USERS\DAN.SCHROEDER\APPDATA\LOCAL\MICROSOFT\VISUALSTUDIO\15.0_B920D444EXP\EXTENSIONS\DANSKINGDOM\DIFF ALL FILES FOR VS2017\1.0\LibGit2Sharp.dll.
LOG: Assembly is loaded in LoadFrom load context.
WRN: Multiple versions of the same assembly were loaded into one context of an application domain:
WRN: Context: LoadFrom | Domain ID: 1 | Assembly Name: LibGit2Sharp, Version=0.23.1.0, Culture=neutral, PublicKeyToken=7cbde695407f0333
WRN: Context: LoadFrom | Domain ID: 1 | Assembly Name: LibGit2Sharp, Version=0.22.0.0, Culture=neutral, PublicKeyToken=7cbde695407f0333
WRN: This might lead to runtime failures.
WRN: It is recommended that you remove the dependency on multiple versions, and change the app.config file to point to the required version of the assembly only.
WRN: See whitepaper http://go.microsoft.com/fwlink/?LinkId=109270 for more information.
```

My extension was using the LibGit2Sharp v0.23.1.0 version, but here it said there was already v0.22.0.0 of that assembly loaded in the app domain. Looking at some of the other logs in the Assembly Binding Log Viewer from when Visual Studio was running, I could see that the [GitHub Extension for Visual Studio](https://visualstudio.github.com/) that I had installed was loading the 0.22.0.0 version into the app domain before my extension had been initiated. So the problem was that Visual Studio had already loaded an older version of the assembly that my extension depended on, so my extension was using that older version instead of the intended version. The solution to this problem was for me to give a new strong name to the LibGit2Sharp.dll that I included with my extension, so that both assemblies could be loaded into the app domain without conflicting with one another. To do this, I ran the following batch script against the LibGit2Sharp.dll file in the packages directory (replacing $(SolutionDirectory) with the path to my solution):

```shell
:: Strongly sign the LibGit2Sharp.dll, as VS Extensions want strongly signed assemblies and we want to avoid runtime version conflicts.
:: http://www.codeproject.com/Tips/341645/Referenced-assembly-does-not-have-a-strong-name
cd "$(SolutionDir)packages\LibGit2Sharp.0.23.1\lib\net40\"
"C:\Program Files (x86)\Microsoft SDKs\Windows\v10.0A\bin\NETFX 4.6.2 Tools\ildasm.exe" /all /out=LibGit2Sharp.il LibGit2Sharp.dll
"C:\Program Files (x86)\Microsoft SDKs\Windows\v10.0A\bin\NETFX 4.6.2 Tools\sn.exe" -k MyLibGit2SharpKey.snk
"C:\Windows\Microsoft.NET\Framework64\v4.0.30319\ilasm.exe" /dll /key=MyLibGit2SharpKey.snk LibGit2Sharp.il
```

This overwrote the LibGit2Sharp.dll file in the packages directory with a new custom-signed version. One caveat with this is you need to make sure you have the Windows SDK installed ([Win 10](https://developer.microsoft.com/en-us/windows/downloads/windows-10-sdk), [Win 8.1](https://developer.microsoft.com/en-us/windows/downloads/windows-8-1-sdk), or [Win 7](https://www.microsoft.com/en-ca/download/details.aspx?id=8279)) and make sure all the paths to ildasm.exe and sn.exe are correct, as the version in the file path may be different depending on which version of Windows you are running. Once I had the new custom-signed .dll file, I added it to a new location source control and then removed any project references from the old assembly and added the new references to the custom-signed assembly. This process will need to be repeated any time I update to a new version of the assembly.

There may be other better solutions to this problem, but this one worked for me. Another possible solution is to [use ILMerge](https://www.codeproject.com/Articles/9364/Merging-NET-assemblies-using-ILMerge) to [combine the required .dll files into a single .dll file](https://stackoverflow.com/questions/9376/ilmerge-best-practices); I haven't tried this method myself however, so I cannot comment on if it is better/easier or not. One thing to mention about the ILMerge method however is it requires a post-build step, either to be added to a project in Visual Studio or to your build scripts. With the method I mentioned above, you only need to take additional steps when updating the version of the 3rd party assembly being used. If you know of another way around this problem, please share it in the comments.

I'm also going to give a shout out to the community in the [Gitter Microsoft/extendvs channel](https://gitter.im/Microsoft/extendvs), as they have been very helpful in helping me diagnose problems and come up with solutions while trying to port my extension to Visual Studio 2017.

For those interested, I documented [the process I took to update my extension to support VS 2017](https://github.com/deadlydog/VS.DiffAllFiles/blob/master/docs/internal/SupportingNewVisualStudioVersions.md), and [the process required to update LibGit2Sharp to a new version](https://github.com/deadlydog/VS.DiffAllFiles/blob/master/VS.DiffAllFiles/_LibGit2Sharp/ProcessForUpdatingLibGit2Sharp.txt) in the future.

Hopefully you have found this post helpful. Happy coding!
