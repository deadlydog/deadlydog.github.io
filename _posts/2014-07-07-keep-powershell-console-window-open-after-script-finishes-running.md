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

## 1. One-time solution

Open a PowerShell console and manually run the script from the command line. I show how to do this a bit [in this post](http://dans-blog.azurewebsites.net/fix-problem-where-windows-powershell-cannot-run-script-whose-path-contains-spaces/), as the PowerShell syntax to run a script from the command-line is not straight-forward if you’ve never done it before.

The other way is to launch the PowerShell process from the Run box (Windows Key + R) or command prompt using the __-NoExit__ switch and passing in the path to the PowerShell file.

For example: __PowerShell -NoExit "C:\SomeFolder\MyPowerShellScript.ps1"__

## 2. Per-script solution

Add a line like this to the end of your script:

```powershell
Read-Host -Prompt "Press Enter to exit"
```

I typically use this following bit of code instead so that it only prompts for input when running from the PowerShell Console, and not from the PS ISE or other PS script editors (as they typically have a persistent console window integrated into the IDE).  Use whatever you prefer.

```powershell
# If running in the console, wait for input before closing.
if ($Host.Name -eq "ConsoleHost")
{
    Write-Host "Press any key to continue..."
    $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyUp") > $null
}
```

I typically use this approach for scripts that other people might end up running; if it’s a script that only I will ever be running, I rely on the global solution below.

## 3. Global solution

Adjust the registry keys used to run a PowerShell script to include the –NoExit switch to prevent the console window from closing.  Here are the two registry keys we will target, along with their default value, and the value we want them to have:

```csharp
Registry Key: HKEY_CLASSES_ROOT\Applications\powershell.exe\shell\open\command
Description: Key used when you right-click a .ps1 file and choose Open With -> Windows PowerShell.
Default Value: "C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe" "%1"
Desired Value: "C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe" "& \"%1\""

Registry Key: HKEY_CLASSES_ROOT\Microsoft.PowerShellScript.1\Shell\0\Command
Description: Key used when you right-click a .ps1 file and choose Run with PowerShell (shows up depending on which Windows OS and Updates you have installed).
Default Value: "C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe" "-Command" "if((Get-ExecutionPolicy ) -ne 'AllSigned') { Set-ExecutionPolicy -Scope Process Bypass }; & '%1'"
Desired Value: "C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe" -NoExit "-Command" "if((Get-ExecutionPolicy ) -ne 'AllSigned') { Set-ExecutionPolicy -Scope Process Bypass }; & \"%1\""
```

The Desired Values add the –NoExit switch, as well wrap the %1 in double quotes to [allow the script to still run even if it’s path contains spaces](http://dans-blog.azurewebsites.net/fix-problem-where-windows-powershell-cannot-run-script-whose-path-contains-spaces/).

If you want to open the registry and manually make the change you can, or here is the registry script that we can run to make the change automatically for us:

```csharp
Windows Registry Editor Version 5.00

[HKEY_CLASSES_ROOT\Applications\powershell.exe\shell\open\command]
@="\"C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe\" -NoExit \"& \\\"%1\\\"\""

[HKEY_CLASSES_ROOT\Microsoft.PowerShellScript.1\Shell&#92;&#48;\Command]
@="\"C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe\" -NoExit \"-Command\" \"if((Get-ExecutionPolicy ) -ne 'AllSigned') { Set-ExecutionPolicy -Scope Process Bypass }; & \\\"%1\\\"\""
```

You can copy and paste the text into a file with a .reg extension, or just [download it here](/assets/Posts/2014/07/FixRunPowerShellScriptWithSpacesInPathProblemAndLeaveConsoleOpenWhenScriptCompletes.zip).

Simply double-click the .reg file and click OK on the prompt to have the registry keys updated.  Now by default when you run a PowerShell script from File Explorer (i.e. Windows Explorer), the console window will stay open even after the script is finished executing.  From there you can just type __exit__ and hit enter to close the window, or use the mouse to click the window’s X in the top right corner.

If I have missed other common registry keys or any other information, please leave a comment to let me know.  I hope you find this useful.

Happy coding!
