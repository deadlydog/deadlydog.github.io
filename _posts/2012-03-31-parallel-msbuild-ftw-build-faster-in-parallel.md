---
id: 73
title: Parallel MSBuild FTW - Build faster in parallel
date: 2012-03-31T02:09:00-06:00
guid: https://deadlydog.wordpress.com/?p=73
permalink: /parallel-msbuild-ftw-build-faster-in-parallel/
jabber_published:
  - "1353118161"
categories:
  - Build
tags:
  - batch file
  - Build
  - MSBuild
  - multiple core
  - parallel
---

Hey everyone, I just discovered [this great post](http://www.hanselman.com/blog/FasterBuildsWithMSBuildUsingParallelBuildsAndMulticoreCPUs.aspx) yesterday that shows how to have msbuild build projects in parallel :-).

Basically all you need to do is pass the switches `/m:[NumOfCPUsToUse] /p:BuildInParallel=true` into MSBuild.

Example to use 4 cores/processes (If you just pass in `/m` it will use all CPU cores):

> MSBuild /m:4 /p:BuildInParallel=true "C:devClient.sln"

Obviously this trick will only be useful on PCs with multi-core CPUs (which we should all have by now) and solutions with multiple projects; So there's no point using it for solutions that only contain one project. Also, testing shows that using multiple processes does not speed up Visual Studio Database deployments either in case you're curious ;-).

Also, I found that if I didn't explicitly use `/p:BuildInParallel=true` I would get many build errors (even though the [MSDN documentation](http://msdn.microsoft.com/en-us/library/bb651793.aspx) says that it is true by default).

The poster boasts compile time improvements up to 59%, but the performance boost you see will vary depending on the solution and its project dependencies. I tested with building a solution at my office, and here are my results (runs are in seconds):

| # of Processes | 1st Run | 2nd Run | 3rd Run | Avg    | Performance|
|----------------|---------|---------|---------|--------|-----------:|
| 1              | 192     | 195     | 200     | 195.67 | 100%       |
| 2              | 155     | 156     | 156     | 155.67 | 79.56%     |
| 4              | 146     | 149     | 146     | 147.00 | 75.13%     |
| 8              | 136     | 136     | 138     | 136.67 | 69.85%     |

So I updated all of our build scripts to build using 2 cores (~20% speed boost), since that gives us the biggest bang for our buck on our solution without bogging down a machine, and developers may sometimes compile more than 1 solution at a time. I've put the any-PC-safe batch script code at the bottom of this post.

The poster also has [a follow-up post](http://www.hanselman.com/blog/HackParallelMSBuildsFromWithinTheVisualStudioIDE.aspx) showing how to add a button and keyboard shortcut to the Visual Studio IDE to have VS build in parallel as well (so you don't __have__ to use a build script); if you do this make sure you use the .Net 4.0 MSBuild, not the 3.5 one that he shows in the screenshot. While this did work for me, I found it left an MSBuild.exe process always hanging around afterwards for some reason, so watch out (batch file doesn't have this problem though). Also, you do get build output, but it may not be the same that you're used to, and it doesn't say "Build succeeded" in the status bar when completed, so I chose to not make this my default Visual Studio build option, but you may still want to.

Happy building!

```bat
:: Calculate how many Processes to use to do the build.
SET NumberOfProcessesToUseForBuild=1
SET BuildInParallel=false
if %NUMBER_OF_PROCESSORS% GTR 2 (
                SET NumberOfProcessesToUseForBuild=2
                SET BuildInParallel=true
)
MSBuild /maxcpucount:%NumberOfProcessesToUseForBuild% /p:BuildInParallel=%BuildInParallel% "C:\dev\Client.sln"
```
