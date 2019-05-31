---
id: 310
title: Fix Problem Where Windows PowerShell Cannot Run Script Whose Path Contains Spaces
date: 2013-05-28T12:58:25-06:00
guid: http://dans-blog.azurewebsites.net/?p=310
permalink: /fix-problem-where-windows-powershell-cannot-run-script-whose-path-contains-spaces/
categories:
  - PowerShell
tags:
  - Console
  - Context Menu
  - Default
  - Double Click
  - error
  - Execute
  - Explorer
  - File Explorer
  - Open With
  - Path
  - PowerShell
  - Registry
  - Right Click
  - script
  - Space
  - Spaces
  - Windows Explorer
  - Windows PowerShell
---
Most people will likely find the “Run script path with spaces from File Explorer” (to be able to double click a PS script whose path contains spaces to run it) section below the most helpful. Most of the other content in this post can be found elsewhere, but I provide it for context and completeness.



## Make running (instead of editing) the default PowerShell script action

The default Windows action when you double click on a PowerShell script is to open it in an editor, rather than to actually run the script. If this bugs you, it’s easy enough to fix. Just right-click on the script, go to “Open with” –> “Choose default program...”, and then select Windows PowerShell, making sure the “Use this app for all .ps1 files” option is checked (this might be called “Always use the selected program to open this kind of file” or something else depending on which version of Windows you are using).

[<img title="ChooseDefaultPowerShellApplication" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="ChooseDefaultPowerShellApplication" src="/assets/Posts/2013/05/ChooseDefaultPowerShellApplication_thumb.png" width="600" height="309" />](/assets/Posts/2013/05/ChooseDefaultPowerShellApplication.png) [<img title="MakeWindowsPowerShellDefaultApplication" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="MakeWindowsPowerShellDefaultApplication" src="/assets/Posts/2013/05/MakeWindowsPowerShellDefaultApplication_thumb.png" width="312" height="309" />](/assets/Posts/2013/05/MakeWindowsPowerShellDefaultApplication.png)

If you don’t mind opening in an editor as the default action, then to run the script you can just right-click on the script and choose “Open with” –> “Windows PowerShell”. This is probably how 90% of people run their PowerShell scripts; power uses might run their scripts directly from the PowerShell command prompt.



## Error message when trying to run a script whose path contains spaces

So the problem that the 90% of people are likely to encounter is that as soon as the script path has a space in it (either in the filename itself or in the directory path the file resides in), they will see the powershell console flash some red text at them for about 1/10th of a second before it closes, and they will be wondering why the script did not run; or worse, they won’t know that it didn’t run (see the “Keep PowerShell Console Open” section below). If they are lucky enough to press Print Screen at the right moment, or decide to open up a PowerShell console and run from there, they might see an error message similar to this:

[<img title="Powershell Invalid Path Error Message" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; float: right; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="Powershell Invalid Path Error Message" align="right" src="/assets/Posts/2013/05/Powershell-Invalid-Path-Error-Message_thumb.png" width="600" height="96" />](/assets/Posts/2013/05/Powershell-Invalid-Path-Error-Message.png)

“The term ‘C:\My’ is not recognized as the name of a cmdlet, function, script file, or operable program. Check the spelling of the name, or if a path was included, verify that the path is correct and try again.”

So the path to the script I was trying to run is "C:\My Folder\My PowerShell Script.ps1", but from the error you can see that it cut the path off at the first space.

#####

## Run script path with spaces from PowerShell console

So the typical work around for this is to open a PowerShell console and run the script by enclosing the path in double quotes.

<p align="right">
  <strong>Windows 8 Pro Tip:</strong> You can open the PowerShell console at your current directory in File Explorer by choosing File –> Open Windows PowerShell.
</p>

[<img title="Open Powershell From File Explorer" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; float: right; padding-top: 0px; padding-left: 0px; margin: 0px 0px 0px 25px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="Open Powershell From File Explorer" align="right" src="/assets/Posts/2013/05/Open-Powershell-From-File-Explorer_thumb.png" width="600" height="347" />](/assets/Posts/2013/05/Open-Powershell-From-File-Explorer.png)

If you simply try to run the script by enclosing the path to the script in double quotes you will just see the path spit back at you in the console, instead of actually running the script.

[<img title="Try to run script with spaces the wrong way" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="Try to run script with spaces the wrong way" src="/assets/Posts/2013/05/Try-to-run-script-with-spaces-the-wrong-way_thumb.png" width="600" height="163" />](/assets/Posts/2013/05/Try-to-run-script-with-spaces-the-wrong-way.png)

The trick is that you have to put “& “ before the script path to actually run the script. Also, if you are trying to run a script from the current directory without using the full path, you will need to put “.\” before the relative script filename.

[<img title="Run PowerShell script the right way" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="Run PowerShell script the right way" src="/assets/Posts/2013/05/Run-PowerShell-script-the-right-way_thumb.png" width="600" height="165" />](/assets/Posts/2013/05/Run-PowerShell-script-the-right-way.png)

#####

## Run script path with spaces from File Explorer

So when we are in the PowerShell console we can manually type the path enclosed in double quotes, but what do we do when simply trying to run the file from File Explorer (i.e. Windows Explorer in Windows 7 and previous) by double clicking it?

**The answer:** Edit the registry to pass the file path to powershell.exe with the path enclosed in quotes.

The problem is that the “HKEY\_CLASSES\_ROOT\Applications\powershell.exe\shell\open\command” registry key value looks like this:

> "C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe" "%1"

but we want it to look like this:

> "C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe" "& \"%1\""



So if you want to go manually edit that key by hand you can, or you can simply download the registry script below and then double click the .reg file to have it update the registry key value for you (choose Yes when asked if you want to continue).

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:be029cc3-84d6-4665-b3e8-b0f73437eb88" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: plain; gutter: false; pad-line-numbers: true; title: ; notranslate" title="">
Windows Registry Editor Version 5.00

[HKEY_CLASSES_ROOT\Applications\powershell.exe\shell\open\command]
@="\"C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe\" \"& \\\"%1\\\"\""

</pre>
</div>

<div id="scid:fb3a1972-4489-4e52-abe7-25a00bb07fdf:4b67d617-163b-4eab-893d-590ef905aa2e" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <p>
    <a href="/assets/Posts/2013/09/FixRunPowerShellScriptWithSpacesInPathProblem.zip" target="_blank">Download Registry Script</a>
  </p>
</div>

IMHO this seems like a bug with the PowerShell installer (and Windows since PowerShell is built into Windows 7 and up), so please go [up-vote the bug I submitted to get this fixed](https://connect.microsoft.com/PowerShell/feedback/details/788806/powershell-script-cannot-be-ran-outside-of-console-if-path-contains-spaces).

So now you can run your PowerShell scripts from File Explorer regardless of whether their path contains spaces or not <img class="wlEmoticon wlEmoticon-smile" style="border-top-style: none; border-left-style: none; border-bottom-style: none; border-right-style: none" alt="Smile" src="/assets/Posts/2013/05/wlEmoticon-smile.png" />. For those interested, t[his is the post](http://superuser.com/questions/445925/how-to-add-item-to-right-click-menu-when-not-selecting-a-folder-or-file) that got me thinking about using the registry to fix this problem.



## Bonus: Keep PowerShell console open when script is ran from File Explorer

**Update** - This Bonus section now has its own [updated dedicated post here](http://dans-blog.azurewebsites.net/keep-powershell-console-window-open-after-script-finishes-running/) that you should use instead.

When running a script by double-clicking it, if the script completes very quickly the user will see the PowerShell console appear very briefly and then disappear. If the script gives output that the user wants to see, or if it throws an error, the user won’t have time to read the text. The typical work around is to open the PowerShell console and manually run the script. The other option is to adjust our new registry key value a bit.

So to keep the PowerShell console window open after the script completes, we just need to change our new key value to use the –NoExit switch:

> "C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe" –NoExit "& \"%1\""

And here is the .reg script with the –NoExit switch included:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:94269297-b2fd-4114-a6e1-a3092ee9b03a" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: plain; gutter: false; title: ; notranslate" title="">
Windows Registry Editor Version 5.00

[HKEY_CLASSES_ROOT\Applications\powershell.exe\shell\open\command]
@="\"C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe\" -NoExit \"& \\\"%1\\\"\""
</pre>
</div>

<div id="scid:fb3a1972-4489-4e52-abe7-25a00bb07fdf:ce1b2d90-4d4e-4142-9a47-c685f5ecdb7f" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <p>
    <a href="/assets/Posts/2013/09/FixRunPowerShellScriptWithSpacesInPathProblemAndLeaveConsoleOpenWhenScriptCompletes.zip" target="_blank">Download Registry Script</a>
  </p>
</div>

I hope you find this information as useful as I did. Happy coding!
