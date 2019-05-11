---
id: 55
title: Setting up keyboard shortcuts to build solutions in MSBuild
date: 2011-06-01T13:32:00-06:00
author: deadlydog
guid: https://deadlydog.wordpress.com/?p=55
permalink: /setting-up-keyboard-shortcuts-to-build-solutions-in-msbuild/
jabber_published:
  - "1353108513"
categories:
  - AutoHotkey
  - Build
  - Shortcuts
tags:
  - AHK
  - AutoHotkey
  - Build
  - keyboard shortcuts
  - MSBuild
---
One of the greatest benefits of building your solution flies in MSBuild (vs in Visual Studio directly) is that it doesn&#8217;t lock up the Visual Studio UI, which can be a huge pain if you have a large solution that takes several minutes (or longer) to build.&#160; Building your solution in MSBuild leaves you free to inspect code in Visual Studio while your solution is building.&#160; The only problem is that to do this you have to open a command prompt and type the command + path every time to build.

If you want to be able to right-click on a solution file and build it in MSBuild from the Windows Explorer context menu, check out [MSBuildShellExtension](http://msbuildshellex.codeplex.com/) (it&#8217;s free).&#160; Being able to build right from Windows Explorer (without having to even open Visual Studio) is cool and may be enough to passify you, but I wanted to be able to build my solution file at anytime from anywhere on my PC with a keyboard shortcut.

Below I outline how I&#8217;ve setup my system to build my solution files in MSBuild with a quick keystroke.&#160; Setup only takes a few minutes and requires [AutoHotkey](http://www.autohotkey.com/) to be installed (it&#8217;s free and awesome).

<u>**Step 1**</u> &#8211; Install [AutoHotkey](http://www.autohotkey.com/).

<u>**Step 2**</u> – Create a shortcut to the Visual Studio Command Prompt (2010), move it directly to the C: drive, and make sure it is called “Visual Studio Command Prompt (2010)” (it is referenced at this location with this name by the AutoHotkey script in the following steps, but can be changed if needed).

[<img title="VS2010CommandPrompt" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="VS2010CommandPrompt" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/vs2010commandprompt_thumb.png" width="897" height="674" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/vs2010commandprompt.png)

[<img title="CDrive" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="CDrive" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/cdrive_thumb.png" width="823" height="440" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/cdrive.png)

<u>**Step 3**</u> – Create your AutoHotkey script……luckily, you don’t have to create it from scratch; you can use mine as your template and just adjust it to your liking ![](http://geekswithblogs.net/Providers/BlogEntryEditor/FCKeditor/editor/images/smiley/msn/regular_smile.gif). <span>So copy and paste the script in the textbox below into a new text file, and then save it with the extension ".ahk", which is the AutoHotkey script extension (so you can just call it "AutoHotkeyScript.ahk" for example).&#160; You will need to modify the code directory paths and solution file names in the script to match yours to build your solutions, but I&#8217;ve commented the script fairly thoroughly so it&#8217;s easy to see what it&#8217;s doing. <br /></span>

In my office we have both a Client solution and a Server solution, so I have the script setup to build the client solution with WindowsKey+C and the server solution with WindowsKey+S. We also work in multiple branches, so I have global variables near the top of the script that I can set to true to quickly switch between Development, QA, and Release branches.<span>&#160; </span>I also have WindowsKey+U configured to open the code directory and WindowsKey+Q to open the database directory.<span>&#160; </span>Obviously you can change the keyboard mappings to your liking; these are just the ones that I prefer.<span>&#160; </span>As a side note here, just be aware that these will override the default windows key shortcuts; so in my case WindowsKey+U no longer opens up the Windows Ease of Access Center window.

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:369ffdc3-cb6b-45dd-8120-244ba384d3d7" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: bash; title: ; notranslate" title="">
; IMPORTANT INFO ABOUT GETTING STARTED: Lines that start with a
; semicolon, such as this one, are comments.  They are not executed.

; This script has a special filename and path because it is automatically
; launched when you run the program directly.  Also, any text file whose
; name ends in .ahk is associated with the program, which means that it
; can be launched simply by double-clicking it.  You can have as many .ahk
; files as you want, located in any folder.  You can also run more than
; one ahk file simultaneously and each will get its own tray icon.

; Make it so only one instance of this script can run at a time (and reload the script if another instance of it tries to run)
#SingleInstance force

;==========================================================
; Global Variables - Path settings, customization, etc.
;==========================================================

; Set one of these to &quot;true&quot; to build from the Staging or Release branches, otherwise we'll use the development branch.
_UsingTFSStagingBranch := false
_UsingTFSReleaseCandidate := false

; Specify the Code Folder containing the Solution files to build
if (_UsingTFSReleaseCandidate == true)
{
    ; The directory of the current build's Code folder
    _CodeFolder := &quot;C:\dev\TFS\RQ4TeamProject\Release\RQ4\4.2.0\&quot;
}
else if (_UsingTFSStagingBranch == true)
{
    ; The directory of the current build's Code folder
    _CodeFolder := &quot;C:\dev\TFS\RQ4TeamProject\Staging\RQ4\&quot;
}
else
{
    ; The directory of the current build's Code folder
    _CodeFolder := &quot;C:\dev\TFS\RQ4TeamProject\Dev\RQ4\Core\&quot;
}

; Path to the database folder
_DatabaseFolder := &quot;C:\dev&quot;

; The path to the Visual Studio Command Prompt link
_VSCommandPromptPath := &quot;C:\Visual Studio Command Prompt (2010).lnk&quot;
_VSCommandPromptWindowName := &quot;Administrator: Visual Studio Command Prompt (2010)&quot;

; The position I want the MS Build window to move to when opened
_MSBuildWindowPositionX := 400
_MSBuildWindowPositionY := 270

; The MSBuild command to use
_MSBuildCommand := &quot;msbuild&quot; ; /verbosity:minimal&quot;


;==========================================================
; WindowsKey+C - Build the Client.sln
;==========================================================
#c UP::

; Make sure the keys have been released before continuing to avoid accidental commands
KeyWait LWin
;KeyWait c

;BuildSolution(_CodeFolder . &quot;RQ4.Client.sln&quot;)
BuildSolution(&quot;RQ4.Client.sln&quot;)

return

;==========================================================
; WindowsKey+S - Build the Server.sln
;==========================================================
#s UP::

; Make sure the keys have been released before continuing to avoid accidental commands
KeyWait LWin
;KeyWait s

BuildSolution(&quot;RQ4.Server.sln&quot;)

return

;==========================================================
; WindowsKey+B - Build the Server.sln then Client.sln
;==========================================================
#b UP::

; Make sure the keys have been released before continuing to avoid accidental commands
KeyWait LWin
;KeyWait b

BuildSolution(&quot;RQ4.Server.sln&quot;)
BuildSolution(&quot;RQ4.Client.sln&quot;)

return

;==========================================================
; WindowsKey+U - Open the Code folder
;==========================================================
#u UP::Run %_CodeFolder%

;==========================================================
; WindowsKey+Q - Open the Database folder
;==========================================================
#q UP::Run %_DatabaseFolder%


;==========================================================
; Functions
;==========================================================
BuildSolution(solutionPath)
{
    ; Let this function know that all variables except the passed in parameters are global variables.
    global

    ; If the Visual Studio Command Prompt is already open
    if WinExist(_VSCommandPromptWindowName)
    {
        ; Put it in focus
        WinActivate
    }
    ; Else the VS Command Prompt is not already open
    else
    {
        ; So open the Visual Studio 2008 Command Prompt
        Run %_VSCommandPromptPath%

        ; Make sure this window is in focus before sending commands
        WinWaitActive, %_VSCommandPromptWindowName%

        ; If the window wasn't opened for some reason
        if Not WinExist(_VSCommandPromptWindowName)
        {
            ; Display an error message that the VS Command Prompt couldn't be opened
            MsgBox, There was a problem opening %_VSCommandPromptPath%

            ; Exit, returning failure
            return false
        }
    }

    ; Make sure this window is in focus before sending commands
    WinWaitActive, %_VSCommandPromptWindowName%

    ; Move the window to the position I like it to be
    WinMove, _MSBuildWindowPositionX, _MSBuildWindowPositionY

    ; Set it to the correct directory
    SendInput cd %_CodeFolder% {Enter}

    ;MsgBox %solutionPath%  ; Message box to display the Solution Path for debugging purposes

    ; Build the solution file
    SendInput %_MSBuildCommand% %solutionPath% {Enter}

    ; Return success
    return true
}
</pre>
</div>

<u>**Step 4**</u> – Have your AutoHotkey script automatically start when you login to Windows, so that you don’t have to manually launch it all the time.<span></span>

<u>Method 1:</u>

This method is the easiest, but I discovered it after Method 2 (below).&#160; Simply open up the Windows Start Menu, navigate to the Startup folder within All Programs, right-click on it and choose Open All Users.&#160; Then simply paste a shortcut to your AutoHotkey script in this folder.&#160; That&#8217;s it; the script will now launch whenever any user logs into Windows.&#160; If you only want the script to run when **you** log into Windows (no other users), then just choose Open instead of Open All Users when right-clicking on the Startup folder.

&#160;[<img title="index" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="index" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/index_thumb.png" width="422" height="561" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/index.png)[<img title="index2" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="index2" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/index2_thumb.png" width="709" height="518" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/index2.png)

<span></span>

<span><u>Method 2:</u></span>

Open the Windows Task Scheduler and create a new Basic Task.<span>&#160; </span>Give it a name and description (something like “launch AutoHotkey script at login”), and then specify to have it run “When I log on”.<span>&#160; </span>Then specify that you want it to “Start a program”, and then point it towards the AutoHotkey script you created in Step 3.<span>&#160; </span>Before you finish the wizard, check off “Open the Properties dialog for this task when I click Finish”.<span>&#160; </span>When that Properties dialog opens up, go to the Conditions tab and make sure none of the checkboxes under the Power category are checked off; this will ensure the script still launches if you are on a laptop and not plugged into AC power.&#160; If you need your script to “Run as admin”, then on the General tab check off “Run with highest privileges”; this may be required for your script to perform certain actions the require admin privileges, so you can check it off just to be safe.

[<img title="Open Task Scheduler(1)" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="Open Task Scheduler(1)" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/open-task-scheduler1_thumb.png" width="379" height="516" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/open-task-scheduler1.png)

&#160;[<img title="Create Basic Task in Task Scheduler" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="Create Basic Task in Task Scheduler" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/create-basic-task-in-task-scheduler_thumb.png" width="707" height="493" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/create-basic-task-in-task-scheduler.png)

&#160;

[<img title="Basic Task Conditions" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="Basic Task Conditions" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/basic-task-conditions_thumb.png" width="748" height="566" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/basic-task-conditions.png)

[<img title="Run Scheduled Task as Admin_2" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="Run Scheduled Task as Admin_2" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/run-scheduled-task-as-admin_2_thumb.png" width="750" height="567" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/run-scheduled-task-as-admin_2.png)

And that&#8217;s it.&#160; Now you can build your solution file in MSBuild with a quick keystroke from anywhere on your computer.&#160; I have chosen to use the Windows Key for my shortcut keys, but you don&#8217;t have to; you can use whatever keyboard shortcut you want.&#160; And feel free to modify the script I provided to do whatever else you want; AutoHotkey is very powerful and can be used for so many things, so be sure to [checkout their website](http://www.autohotkey.com/) for more examples of scripts and what it can do.&#160; For example lots of people use it to automatically spell-correct as they type, or to automatically expand abbreviations (so I could type DS and hit tab, and have it expand to Daniel Schroeder, or type MyAddress and have it put my address in).

Happy Coding!
