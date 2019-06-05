---
id: 385
title: Getting Custom TFS Checkin Policies To Work When Committing From The Command Line (i.e. tf checkin)
date: 2013-09-06T15:28:08-06:00
guid: http://dans-blog.azurewebsites.net/?p=385
permalink: /getting-custom-tfs-checkin-policies-to-work-when-committing-from-the-command-line-i-e-tf-checkin/
categories:
  - PowerShell
  - TFS
tags:
  - Checkin
  - Checkin Policies
  - command line
  - Custom
  - Git-Tf
  - Internal error
  - PowerShell
  - TF
  - TFS
---

__Update:__ I show how to have your checkin policies automatically update the registry keys shown in this blog post [on this newer blog post](http://dans-blog.azurewebsites.net/template-solution-for-deploying-tfs-checkin-policies-to-multiple-versions-of-visual-studio-and-having-them-automatically-work-from-tf-exe-checkin-too/). If you are not the person creating the checkin policies though, then you will still need to use the technique shown in this post.

I frequently check code into TFS from the command line, instead of from Visual Studio (VS), for a number of reasons:

1. I prefer the VS 2010 style of checkin window over the VS 2012 one, and the 2010 style window is still displayed when checking in from the command line.
1. I use [AutoHotkey](http://www.autohotkey.com/) to pop the checkin window via a keyboard shortcut, so I don't need to have VS open to check files in (or navigate to the pending changes window within VS).
   - Aside: Just add this one line to your AutoHotkey script for this functionality. This sets the hotkey to Ctrl+Windows+C to pop the checkin window, but feel free to change it to something else.

    > ^#C UP::Run, tf checkin

1. Other programs, such as [Git-Tf](http://gittf.codeplex.com/) and the Windows Explorer shell extension, call the TFS checkin window via the command line, so you don't have the option to use the VS checkin pending changes window.

## The Problem

The problem is that if you are using a VSIX package to deploy your custom checkin policies, the custom checkin policies will only work when checking code in via the VS GUI, and not when doing it via the command line. If you try and do it via the command line, the checkin window spits an "Internal error" for each custom checkin policy that you have, so your policies don't run and you have to override them.

![Internal Error In Checkin Policies](/assets/Posts/2013/09/InternalErrorInCheckinPolicies.png)

P. Kelly mentions this problem [on his blog post](http://blogs.msdn.com/b/phkelley/archive/2013/08/12/checkin-policy-multitargeting.aspx?wa=wsignin1.0), and has some other great information around custom checkin policies in TFS.

The old [TFS 2010 Power Tools](http://visualstudiogallery.msdn.microsoft.com/c255a1e4-04ba-4f68-8f4e-cd473d6b971f) had [a feature for automatically distributing the checkin policies to your team](http://www.codewrecks.com/blog/index.php/2010/12/04/distributing-visual-studio-addin-for-the-team/), but unfortunately this feature was removed from the [TFS 2012 Power Tools](http://visualstudiogallery.msdn.microsoft.com/b1ef7eb2-e084-4cb8-9bc7-06c3bad9148f). Instead, the Microsoft recommended way to distribute your custom checkin policies is now [through a VSIX package](http://msdn.microsoft.com/en-us/library/ff363239.aspx), which is nice because it can use the Extension And Updates functionality built into VS and automatically notify users of updates (without requiring users to install the TFS Power Tools). The problem is that VSIX packages are sandboxed and are not able to update the necessary registry key to make custom checkin policies work from the command line. I originally posted [this question on the MSDN forums](http://social.msdn.microsoft.com/Forums/vstudio/en-US/611a113b-b144-4ccd-8e9b-ca41306d23e2/custom-checkin-policies-do-not-work-when-using-tfexe-checkin-from-command-line), then I logged [a bug about this on the Connect site](https://connect.microsoft.com/VisualStudio/feedback/details/788787/visual-studio-2012-custom-checkin-policies-do-not-work-when-using-tf-exe-checkin-from-command-line), but MS closed it as "By Design" :-(. Maybe if it gets enough up-votes though they will re-open it (so please go up-vote it).

## The Workaround

The good news though is that there is a work around. You simply need to copy your custom checkin policy entry from the key:

> "HKEY\_CURRENT\_USER\Software\Microsoft\VisualStudio\11.0_Config\TeamFoundation\SourceControl\Checkin Policies"

to:

> "HKEY\_LOCAL\_MACHINE\SOFTWARE\Wow6432Node\Microsoft\VisualStudio\11.0\TeamFoundation\SourceControl\Checkin Policies" (omit the Wow6432Node on 32-bit Windows).

## Not Perfect, but Better

The bad news is that every developer (who uses the command line checkin window) will need to copy this registry value on their local machine. Furthermore, they will need to do it every time they update their checkin policies to a new version.

While this sucks, I've made it a bit better by creating a little powershell script to automate this task for you; here it is:

```powershell
# This script copies the required registry value so that the checkin policies will work when doing a TFS checkin from the command line.

# Turn on Strict Mode to help catch syntax-related errors.
#   This must come after a script's/function's param section.
#   Forces a function to be the first non-comment code to appear in a PowerShell Module.
Set-StrictMode -Version Latest

$ScriptBlock = {
    # The name of the Custom Checkin Policy Entry in the Registry Key.
    $CustomCheckinPolicyEntryName = 'YourCustomCheckinPolicyEntryNameGoesHere'

    # Get the Registry Key Entry that holds the path to the Custom Checkin Policy Assembly.
    $CustomCheckinPolicyRegistryEntry = Get-ItemProperty -Path 'HKCU:\Software\Microsoft\VisualStudio\11.0_Config\TeamFoundation\SourceControl\Checkin Policies' -Name $CustomCheckinPolicyEntryName
    $CustomCheckinPolicyEntryValue = $CustomCheckinPolicyRegistryEntry.($CustomCheckinPolicyEntryName)

    # Create a new Registry Key Entry for the iQ Checkin Policy Assembly so they will work from the command line (as well as from Visual Studio).
    if ([Environment]::Is64BitOperatingSystem)
    { $HKLMKey = 'HKLM:\SOFTWARE\Wow6432Node\Microsoft\VisualStudio\11.0\TeamFoundation\SourceControl\Checkin Policies' }
    else
    { $HKLMKey = 'HKLM:\SOFTWARE\Microsoft\VisualStudio\11.0\TeamFoundation\SourceControl\Checkin Policies' }
    Set-ItemProperty -Path $HKLMKey -Name $CustomCheckinPolicyEntryName -Value $CustomCheckinPolicyEntryValue
}

# Run the script block as admin so it has permissions to modify the registry.
Start-Process -FilePath PowerShell -Verb RunAs -ArgumentList "-Command $ScriptBlock"
```

[Download The Scripts](/assets/Posts/2013/09/UpdateCheckinPolicyInRegistry.zip)

Note that you will need to update the script to change __YourCustomCheckinPolicyEntryNameGoesHere__ to your specific entry's name. Also, the "[Environment]::Is64BitOperatingSystem" check requires PowerShell V3; if you have lower than PS V3 [there are other ways to check if it is a 64-bit machine or not](http://social.technet.microsoft.com/Forums/windowsserver/en-US/5dfeb3ab-6265-40cd-a4ac-05428b9db5c3/determine-32-or-64bit-os).

If you have developers that aren't familiar with how to run a PowerShell script, then you can include the following batch script (.cmd/.bat file extension) in the same directory as the PowerShell script, and they can run this instead by simply double-clicking it to call the PowerShell script:

```shell
SET ThisScriptsDirectory=%~dp0
SET PowerShellScriptPath=%ThisScriptsDirectory%UpdateCheckinPolicyInRegistry.ps1

:: Run the powershell script to copy the registry key into other areas of the registry so that the custom checkin policies will work when checking in from the command line.
PowerShell -NoProfile -ExecutionPolicy Bypass -Command "& '%PowerShellScriptPath%'"
```

Note that this batch script assumes you named the PowerShell script "UpdateCheckinPolicyInRegistry.ps1", so if you use a different file name be sure to update it here too.

Your developers will still need to run this script every time after they update their checkin policies, but it's easier and less error prone than manually editing the registry. If they want to take it a step further they could even setup a Scheduled Task to run the script once a day or something, or even implement it as a Group Policy so it automatically happens for everyone, depending on how often your company updates their checkin policies and how many developers you have.

Ideally I would like to simply be able to run this script during/after the VSIX installer. I have posted [a question on Stack Overflow](http://stackoverflow.com/questions/18647866/run-script-during-after-vsix-install) to see if this is possible, but from everything I've read so far it doesn't look like it; maybe in the next generation of VSIX though. If you have any other ideas on how to automate this, I would love to hear them.

Happy coding!
