---
title: Provide A Batch File To Run Your PowerShell Script From; Your Users Will Love You For It
date: 2013-11-16T23:16:07-06:00
permalink: /allow-others-to-run-your-powershell-scripts-from-a-batch-file-they-will-love-you-for-it/
categories:
  - PowerShell
tags:
  - .bat
  - .cmd
  - .ps1
  - batch file
  - Execution Policy
  - PowerShell
  - Profile
  - Run As Admin
---

Aside - This post has received many tangential questions in the comments. Your best bet at getting an answer to those questions is to check [Stack Overflow](https://stackoverflow.com) and/or post your question there.

A while ago in [one of my older posts](http://dans-blog.azurewebsites.net/getting-custom-tfs-checkin-policies-to-work-when-committing-from-the-command-line-i-e-tf-checkin/) I included a little gem that I think deserves it’s own dedicated post; calling PowerShell scripts from a batch file.

## Why call my PowerShell script from a batch file?

When I am writing a script for other people to use (in my organization, or for the general public) or even for myself sometimes, I will often include a simple batch file (i.e. *.bat or *.cmd file) that just simply calls my PowerShell script and then exits. I do this because even though PowerShell is awesome, not everybody knows what it is or how to use it; non-technical folks obviously, but even many of the technical folks in our organization have never used PowerShell.

Let’s list the problems with sending somebody the PowerShell script alone; The first two points below are hurdles that __every__ user stumbles over the first time they encounter PowerShell (they are there for security purposes):

1. When you double-click a PowerShell script (*.ps1 file) the default action is often to open it up in an editor, not to run it ([you can change this for your PC](http://dans-blog.azurewebsites.net/fix-problem-where-windows-powershell-cannot-run-script-whose-path-contains-spaces/)).
1. When you do figure out you need to right-click the .ps1 file and choose Open With –> Windows PowerShell to run the script, it will fail with a warning saying that the execution policy is currently configured to not allow scripts to be ran.
1. My script may require admin privileges in order to run correctly, and it can be tricky to run a PowerShell script as admin without going into a PowerShell console and running the script from there, which a lot of people won’t know how to do.
1. A potential problem that could affect PowerShell Pros is that it’s possible for them to have variables or other settings set in their PowerShell profile that could cause my script to not perform correctly; this is pretty unlikely, but still a possibility.

So imagine you’ve written a PowerShell script that you want your grandma to run (or an HR employee, or an executive, or your teenage daughter, etc.). Do you think they’re going to be able to do it? Maybe, maybe not.

_You should be kind to your users and provide a batch file to call your PowerShell script_.

The beauty of batch file scripts is that by default the script is ran when it is double-clicked (solves problem #1), and all of the other problems can be overcome by using a few arguments in our batch file.

## Ok, I see your point. So how do I call my PowerShell script from a batch file?

First, _the code I provide assumes that the batch file and PowerShell script are in the same directory_. So if you have a PowerShell script called "MyPowerShellScript.ps1" and a batch file called "RunMyPowerShellScript.cmd", this is what the batch file would contain:

```shell
@ECHO OFF
SET ThisScriptsDirectory=%~dp0
SET PowerShellScriptPath=%ThisScriptsDirectory%MyPowerShellScript.ps1
PowerShell -NoProfile -ExecutionPolicy Bypass -Command "& '%PowerShellScriptPath%'";
```

Line 1 just prevents the contents of the batch file from being printed to the command prompt (so it’s optional). Line 2 gets the directory that the batch file is in. Line 3 just appends the PowerShell script filename to the script directory to get the full path to the PowerShell script file, so this is the only line you would need to modify; _replace MyPowerShellScript.ps1 with your PowerShell script’s filename_. The 4th line is the one that actually calls the PowerShell script and contains the magic.

The __–NoProfile__ switch solves problem #4 above, and the __–ExecutionPolicy Bypass__ argument solves problem #2. But that still leaves problem #3 above, right?

### Call your PowerShell script from a batch file with Administrative permissions (i.e. Run As Admin)

If your PowerShell script needs to be run as an admin for whatever reason, the 4th line of the batch file will need to change a bit:

```shell
@ECHO OFF
SET ThisScriptsDirectory=%~dp0
SET PowerShellScriptPath=%ThisScriptsDirectory%MyPowerShellScript.ps1
PowerShell -NoProfile -ExecutionPolicy Bypass -Command "& {Start-Process PowerShell -ArgumentList '-NoProfile -ExecutionPolicy Bypass -File ""%PowerShellScriptPath%""' -Verb RunAs}";
```

We can’t call the PowerShell script as admin from the command prompt, but we can from PowerShell; so we essentially start a new PowerShell session, and then have that session call the PowerShell script using the __–Verb RunAs__ argument to specify that the script should be run as an administrator.

And voila, that’s it. Now all anybody has to do to run your PowerShell script is double-click the batch file; something that even your grandma can do (well, hopefully). So will your users really love you for this; well, no. Instead they just won’t be cursing you for sending them a script that they can’t figure out how to run. It’s one of those things that nobody notices until it doesn’t work.

So take the extra 10 seconds to create a batch file and copy/paste the above text into it; it’ll save you time in the long run when you don’t have to repeat to all your users the specific instructions they need to follow to run your PowerShell script.

_I typically use this trick for myself too when my script requires admin rights_, as it just makes running the script faster and easier.

## Bonus

One more tidbit that I often include at the end of my PowerShell scripts is the following code:

```powershell
# If running in the console, wait for input before closing.
if ($Host.Name -eq "ConsoleHost")
{
    Write-Host "Press any key to continue..."
    $Host.UI.RawUI.FlushInputBuffer()   # Make sure buffered input doesn't "press a key" and skip the ReadKey().
    $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyUp") > $null
}
```

This will prompt the user for keyboard input before closing the PowerShell console window. This is useful because it allows users to read any errors that your PowerShell script may have thrown before the window closes, or even just so they can see the "Everything completed successfully" message that your script spits out so they know that it ran correctly. Related side note: you can [change your PC to always leave the PowerShell console window open](http://dans-blog.azurewebsites.net/fix-problem-where-windows-powershell-cannot-run-script-whose-path-contains-spaces/) after running a script, if that is your preference.

I hope you find this useful. Feel free to leave comments.

Happy coding!

## Update

Several people have left comments asking how to pass parameters into the PowerShell script from the batch file.

Here is how to pass in ordered parameters:

```shell
PowerShell -NoProfile -ExecutionPolicy Bypass -Command "& '%PowerShellScriptPath%' 'First Param Value' 'Second Param Value'";
```

And here is how to pass in named parameters:

```shell
PowerShell -NoProfile -ExecutionPolicy Bypass -Command "& '%PowerShellScriptPath%' -Param1Name 'Param 1 Value' -Param2Name 'Param 2 Value'"
```

And if you are running the admin version of the script, here is how to pass in ordered parameters:

```shell
PowerShell -NoProfile -ExecutionPolicy Bypass -Command "& {Start-Process PowerShell -ArgumentList '-NoProfile -ExecutionPolicy Bypass -File """"%PowerShellScriptPath%"""" """"First Param Value"""" """"Second Param Value"""" ' -Verb RunAs}"
```

And here is how to pass in named parameters:

```shell
PowerShell -NoProfile -ExecutionPolicy Bypass -Command "& {Start-Process PowerShell -ArgumentList '-NoProfile -ExecutionPolicy Bypass -File """"%PowerShellScriptPath%"""" -Param1Name """"Param 1 Value"""" -Param2Name """"Param 2 value"""" ' -Verb RunAs}";
```

And yes, the PowerShell script name and parameters need to be wrapped in 4 double quotes in order to properly handle paths/values with spaces.
