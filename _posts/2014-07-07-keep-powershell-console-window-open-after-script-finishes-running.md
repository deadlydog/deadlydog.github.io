---
id: 790
title: Keep PowerShell Console Window Open After Script Finishes Running
date: 2014-07-07T15:57:14-06:00
guid: http://dans-blog.azurewebsites.net/?p=790
permalink: /keep-powershell-console-window-open-after-script-finishes-running/
categories:
  - PowerShell
tags:
  - Close
  - Console
  - Explorer
  - File Explorer
  - Finish
  - Open
  - PowerShell
  - Windows Explorer
---
I originally included this as a small bonus section at the end of [my other post about fixing the issue of not being able to run a PowerShell script whose path contains a space](http://dans-blog.azurewebsites.net/fix-problem-where-windows-powershell-cannot-run-script-whose-path-contains-spaces/), but thought this deserved its own dedicated post.

When running a script by double-clicking it, or by right-clicking it and choosing Run With PowerShell or Open With Windows PowerShell, if the script completes very quickly the user will see the PowerShell console appear very briefly and then disappear.  If the script gives output that the user wants to see, or if it throws an error, the user won’t have time to read the text.  We have 3 solutions to fix this so that the PowerShell console stays open after the script has finished running:

### 1. One-time solution

Open a PowerShell console and manually run the script from the command line. I show how to do this a bit [in this post](http://dans-blog.azurewebsites.net/fix-problem-where-windows-powershell-cannot-run-script-whose-path-contains-spaces/), as the PowerShell syntax to run a script from the command-line is not straight-forward if you’ve never done it before.

The other way is to launch the PowerShell process from the Run box (Windows Key + R) or command prompt using the **-NoExit** switch and passing in the path to the PowerShell file.

For example: **PowerShell -NoExit &#8220;C:\SomeFolder\MyPowerShellScript.ps1&#8221;**

### 2. Per-script solution

Add a line like this to the end of your script:

<div class="wlWriterEditableSmartContent" id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:261caff8-aec6-44f8-872a-48c86f3a4aa3" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title="">
Read-Host -Prompt “Press Enter to exit”
</pre>
</div>

I typically use this following bit of code instead so that it only prompts for input when running from the PowerShell Console, and not from the PS ISE or other PS script editors (as they typically have a persistent console window integrated into the IDE).  Use whatever you prefer.

<div class="wlWriterEditableSmartContent" id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:4a1f40b9-9866-4b76-af7e-302babc8326a" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre class="brush: powershell; title: ; notranslate" title="">
# If running in the console, wait for input before closing.
if ($Host.Name -eq "ConsoleHost")
{
	Write-Host "Press any key to continue..."
	$Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyUp") > $null
}
</pre>
</div>

I typically use this approach for scripts that other people might end up running; if it’s a script that only I will ever be running, I rely on the global solution below.

### 3. Global solution

Adjust the registry keys used to run a PowerShell script to include the –NoExit switch to prevent the console window from closing.  Here are the two registry keys we will target, along with their default value, and the value we want them to have:

<div class="wlWriterEditableSmartContent" id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:b994dfe5-5f5f-4b73-bf05-6bbf791539c8" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre class="brush: plain; title: ; notranslate" title="">
Registry Key: HKEY_CLASSES_ROOT\Applications\powershell.exe\shell\open\command
Description: Key used when you right-click a .ps1 file and choose Open With -> Windows PowerShell.
Default Value: "C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe" "%1"
Desired Value: "C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe" "& \"%1\""

Registry Key: HKEY_CLASSES_ROOT\Microsoft.PowerShellScript.1\Shell&#92;&#48;\Command
Description: Key used when you right-click a .ps1 file and choose Run with PowerShell (shows up depending on which Windows OS and Updates you have installed).
Default Value: "C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe" "-Command" "if((Get-ExecutionPolicy ) -ne 'AllSigned') { Set-ExecutionPolicy -Scope Process Bypass }; & '%1'"
Desired Value: "C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe" -NoExit "-Command" "if((Get-ExecutionPolicy ) -ne 'AllSigned') { Set-ExecutionPolicy -Scope Process Bypass }; & \"%1\""
</pre>
</div>

The Desired Values add the –NoExit switch, as well wrap the %1 in double quotes to [allow the script to still run even if it’s path contains spaces](http://dans-blog.azurewebsites.net/fix-problem-where-windows-powershell-cannot-run-script-whose-path-contains-spaces/).

If you want to open the registry and manually make the change you can, or here is the registry script that we can run to make the change automatically for us:

<div class="wlWriterEditableSmartContent" id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:2ec8a40c-ea08-40ca-8b65-912150c69ab8" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre class="brush: plain; title: ; notranslate" title="">
Windows Registry Editor Version 5.00

[HKEY_CLASSES_ROOT\Applications\powershell.exe\shell\open\command]
@="\"C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe\" -NoExit \"& \\\"%1\\\"\""

[HKEY_CLASSES_ROOT\Microsoft.PowerShellScript.1\Shell&#92;&#48;\Command]
@="\"C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe\" -NoExit \"-Command\" \"if((Get-ExecutionPolicy ) -ne 'AllSigned') { Set-ExecutionPolicy -Scope Process Bypass }; & \\\"%1\\\"\""
</pre>
</div>

You can copy and paste the text into a file with a .reg extension, or just

<div class="wlWriterEditableSmartContent" id="scid:fb3a1972-4489-4e52-abe7-25a00bb07fdf:607599ef-0541-44ef-ab33-1f27db68044b" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <p>
    <a href="/assets/Posts/2014/07/FixRunPowerShellScriptWithSpacesInPathProblemAndLeaveConsoleOpenWhenScriptCompletes.zip" target="_blank">download it here.</a>
  </p>
</div>

Simply double-click the .reg file and click OK on the prompt to have the registry keys updated.  Now by default when you run a PowerShell script from File Explorer (i.e. Windows Explorer), the console window will stay open even after the script is finished executing.  From there you can just type **exit** and hit enter to close the window, or use the mouse to click the window’s X in the top right corner.

If I have missed other common registry keys or any other information, please leave a comment to let me know.  I hope you find this useful.

Happy coding!
