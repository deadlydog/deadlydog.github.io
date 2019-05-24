---
id: 73
title: 'Parallel MSBuild FTW &#8211; Build faster in parallel'
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
Hey everyone, I just discovered [this great post](http://www.hanselman.com/blog/FasterBuildsWithMSBuildUsingParallelBuildsAndMulticoreCPUs.aspx) yesterday that shows how to have msbuild build projects in parallel <img class="wlEmoticon wlEmoticon-smile" style="border-top-style: none; border-left-style: none; border-bottom-style: none; border-right-style: none" alt="Smile" src="http://geekswithblogs.net/images/geekswithblogs_net/deadlydog/Windows-Live-Writer/a6e410381ef2_A142/wlEmoticon-smile_2.png" />

Basically all you need to do is pass the switches “/m:[NumOfCPUsToUse] /p:BuildInParallel=true” into MSBuild.

Example to use 4 cores/processes (If you just pass in “/m” it will use all CPU cores):

MSBuild /m:4 /p:BuildInParallel=true "C:devClient.sln"

Obviously this trick will only be useful on PCs with multi-core CPUs (which we should all have by now) and solutions with multiple projects; So there’s no point using it for solutions that only contain one project. Also, testing shows that using multiple processes does not speed up Team Foundation Database deployments either in case you’re curious <img class="wlEmoticon wlEmoticon-winkingsmile" style="border-top-style: none; border-left-style: none; border-bottom-style: none; border-right-style: none" alt="Winking smile" src="http://geekswithblogs.net/images/geekswithblogs_net/deadlydog/Windows-Live-Writer/a6e410381ef2_A142/wlEmoticon-winkingsmile_2.png" />

Also, I found that if I didn’t explicitly use “/p:BuildInParallel=true” I would get many build errors (even though the [MSDN documentation](http://msdn.microsoft.com/en-us/library/bb651793.aspx) says that it is true by default).

The poster boasts compile time improvements up to 59%, but the performance boost you see will vary depending on the solution and its project dependencies. I tested with building a solution at my office, and here are my results (runs are in seconds):

<table style="border-collapse: collapse" cellspacing="0" cellpadding="0" width="394" border="0">
  <colgroup> <col style="width: 71pt" width="95" /> <col style="width: 39pt" width="52" /> <col style="width: 43pt" width="57" /> <col style="width: 41pt" width="54" /> <col style="width: 37pt" width="49" /> <col style="width: 65pt" width="87" /></colgroup> <tr style="height: 15pt">
    <td class="xl69" style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" height="20" width="94" align="center">
      <strong># of Processes</strong>
    </td>

    <td class="xl69" style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" width="52" align="center">
      <strong>1st Run</strong>
    </td>

    <td class="xl69" style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" width="57" align="center">
      <strong>2nd Run</strong>
    </td>

    <td class="xl69" style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" width="54" align="center">
      <strong>3rd Run</strong>
    </td>

    <td class="xl69" style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" width="49" align="center">
      <strong>Avg</strong>
    </td>

    <td class="xl69" style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" width="86" align="center">
      <strong>Performance</strong>
    </td>
  </tr>

  <tr style="height: 15pt">
    <td class="xl68" style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" height="20" align="right">
      <strong>1</strong>
    </td>

    <td style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      192
    </td>

    <td style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      195
    </td>

    <td style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      200
    </td>

    <td style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      195.67
    </td>

    <td class="xl65" style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      100%
    </td>
  </tr>

  <tr style="height: 15pt">
    <td class="xl68" style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" height="20" align="right">
      <strong>2</strong>
    </td>

    <td style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      155
    </td>

    <td style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      156
    </td>

    <td style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      156
    </td>

    <td style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      155.67
    </td>

    <td class="xl66" style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      79.56%
    </td>
  </tr>

  <tr style="height: 15pt">
    <td class="xl68" style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" height="20" align="right">
      <strong>4</strong>
    </td>

    <td style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      146
    </td>

    <td style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      149
    </td>

    <td style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      146
    </td>

    <td class="xl67" style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      147.00
    </td>

    <td class="xl66" style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      75.13%
    </td>
  </tr>

  <tr style="height: 15pt">
    <td class="xl68" style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" height="20" align="right">
      <strong>8</strong>
    </td>

    <td style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      136
    </td>

    <td style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      136
    </td>

    <td style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      138
    </td>

    <td style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      136.67
    </td>

    <td class="xl66" style="vertical-align: bottom; padding-top: 1px; padding-left: 1px; padding-right: 1px" align="right">
      69.85%
    </td>
  </tr>
</table>



So I updated all of our build scripts to build using 2 cores (~20% speed boost), since that gives us the biggest bang for our buck on our solution without bogging down a machine, and developers may sometimes compile more than 1 solution at a time. I’ve put the any-PC-safe batch script code at the bottom of this post.

The poster also has [a follow-up post](http://www.hanselman.com/blog/HackParallelMSBuildsFromWithinTheVisualStudioIDE.aspx) showing how to add a button and keyboard shortcut to the Visual Studio IDE to have VS build in parallel as well (so you don’t **have** to use a build script); if you do this make sure you use the .Net 4.0 MSBuild, not the 3.5 one that he shows in the screenshot. While this did work for me, I found it left an MSBuild.exe process always hanging around afterwards for some reason, so watch out (batch file doesn’t have this problem though). Also, you do get build output, but it may not be the same that you’re used to, and it doesn’t say “Build succeeded” in the status bar when completed, so I chose to not make this my default Visual Studio build option, but you may still want to.

Happy building!

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:704233d6-752d-4986-a88f-b810d349e9c4" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: bash; gutter: false; title: ; notranslate" title="">
:: Calculate how many Processes to use to do the build.
SET NumberOfProcessesToUseForBuild=1
SET BuildInParallel=false
if %NUMBER_OF_PROCESSORS% GTR 2 (
                SET NumberOfProcessesToUseForBuild=2
                SET BuildInParallel=true
)
MSBuild /maxcpucount:%NumberOfProcessesToUseForBuild% /p:BuildInParallel=%BuildInParallel% "C:\dev\Client.sln"
</pre>
</div>
