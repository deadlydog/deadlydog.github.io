---
id: 909
title: PowerShell Log Levels Included In TFS 2017 and VSTS Build and Release Logs
date: 2017-10-26T01:07:25-06:00
author: deadlydog
guid: http://dans-blog.azurewebsites.net/?p=909
permalink: /powershell-log-levels-included-in-tfs-2017-and-vsts-build-and-release-logs/
categories:
  - Build
  - Deploy
  - PowerShell
  - TFS
  - VSTS
tags:
  - Build
  - Deploy
  - Logs
  - PowerShell
  - Team Foundation Server
  - TFS
  - Visual Studio Team Services
  - VSTS
---
We use quite a few custom PowerShell scripts in some of our builds and releases. This led me to ask the question, which PowerShell log levels actually get written to the TFS Build and Release logs? So I did a quick test on both our on-premise TFS 2017 Update 2 installation and my personal VSTS account, and they yielded the same results.

####

#### The PowerShell Script Used To Test

I created a blank build definition and release definition, and the only thing I added to them was a PowerShell task with the following inline script:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:83b1a742-b848-46eb-bc4a-ed1b77ff0a30" class="wlWriterEditableSmartContent" style="margin: 0px; padding: 0px; float: none; display: inline;">
  <pre style=white-space:normal>

  <pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title="">
Write-Host "Host"
Write-Output "Output"
Write-Debug "Debug"
Write-Debug "Debug Forced" -Debug
Write-Information "Information"
$InformationPreference = 'Continue'
Write-Information "Information Forced"
Write-Verbose "Verbose"
Write-Verbose "Verbose Forced" -Verbose
Write-Warning "Warning"
Write-Error "Error"
throw "Throw"
</pre>
</div>

####

#### The Results

Both the build and the release logs yielded the same results. The real-time output shown in the Console resulted in:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:d6d0cf20-f0a8-4379-9995-7189481a6b0c" class="wlWriterEditableSmartContent" style="margin: 0px; padding: 0px; float: none; display: inline;">
  <pre style=white-space:normal>

  <pre class="brush: plain; title: ; notranslate" title="">
Host
Output
DEBUG: Debug Forced
Write-Debug : Windows PowerShell is in NonInteractive mode. Read and Prompt functionality is not available.
Information Forced
VERBOSE: Verbose Forced
WARNING: Warning
C:\Builds\_work\_temp\c0558237-7d53-43ca-97bf-90ed03b8247f.ps1 : Error
</pre>
</div>

<a href="http://dans-blog.azurewebsites.net/wp-content/uploads/2017/10/PowerShellConsoleOutput.png" target="_blank"><img src="http://dans-blog.azurewebsites.net/wp-content/uploads/2017/10/PowerShellConsoleOutput.png" /></a>

The more detailed information written to the log files was:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:516974e5-cfa5-44f8-968f-f614eed0e7ba" class="wlWriterEditableSmartContent" style="margin: 0px; padding: 0px; float: none; display: inline;">
  <pre style=white-space:normal>

  <pre class="brush: plain; title: ; notranslate" title="">
2017-10-26T06:31:09.5118196Z Host
2017-10-26T06:31:09.5138198Z Output
2017-10-26T06:31:09.5228183Z DEBUG: Debug Forced
2017-10-26T06:31:09.6678192Z ##[error]Write-Debug : Windows PowerShell is in NonInteractive mode. Read and Prompt functionality is not available.
At D:\a\_temp\3b089729-e7c5-484d-aa58-256b33f12e01.ps1:4 char:1
+ Write-Debug "Debug Forced" -Debug
+ ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    + CategoryInfo          : InvalidOperation: (:) [Write-Debug], PSInvalidOperationException
    + FullyQualifiedErrorId : InvalidOperation,Microsoft.PowerShell.Commands.WriteDebugCommand


2017-10-26T06:31:09.6698195Z Information Forced
2017-10-26T06:31:09.6698195Z VERBOSE: Verbose Forced
2017-10-26T06:31:09.6698195Z WARNING: Warning
2017-10-26T06:31:09.7258181Z ##[error]D:\a\_temp\3b089729-e7c5-484d-aa58-256b33f12e01.ps1 : Error
At line:1 char:1
+ . 'd:\a\_temp\3b089729-e7c5-484d-aa58-256b33f12e01.ps1'
+ ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    + CategoryInfo          : NotSpecified: (:) [Write-Error], WriteErrorException
    + FullyQualifiedErrorId : Microsoft.PowerShell.Commands.WriteErrorException,3b089729-e7c5-484d-aa58-256b33f12e01.p
   s1

Throw
At D:\a\_temp\3b089729-e7c5-484d-aa58-256b33f12e01.ps1:12 char:1
+ throw "Throw"
+ ~~~~~~~~~~~~~
    + CategoryInfo          : OperationStopped: (Throw:String) [], RuntimeException
    + FullyQualifiedErrorId : Throw
</pre>
</div>

<a href="http://dans-blog.azurewebsites.net/wp-content/uploads/2017/10/PowerShellLogOutput.png" target="_blank"><img src="http://dans-blog.azurewebsites.net/wp-content/uploads/2017/10/PowerShellLogOutput.png" /></a>

One thing to note is that while the **throw** statements are not shown in the real-time console output, they do show up in the detailed logs that are available once the task step completes.

It’s worth pointing out that even when toggling the **system.debug** variable to true, regular Debug statements were not written; only the Forced Debug statement was. Also, notice that even though the forced Debug statement was written to the log, it resulted in an error since the script is running in non-interactive mode, so you should probably avoid forcing Debug statements.

####

#### Summary

So there you have it. By default only the following statements are written to the log files:

  * Write-Host
  * Write-Output
  * Write-Warning
  * Write-Error

and if you force them, you can also have the following written to the log files as well:

  * Write-Debug
  * Write-Information
  * Write-Verbose

And lastly, **throw** statements do not show up in the real-time console output, but do show up in the detailed log information.

I hope you find this information useful. Happy coding!
