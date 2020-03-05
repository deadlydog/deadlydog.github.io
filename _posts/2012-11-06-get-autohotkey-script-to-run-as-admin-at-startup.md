---
title: Get AutoHotkey Script To Run As Admin At Startup
date: 2012-11-06T14:16:00-06:00
permalink: /get-autohotkey-script-to-run-as-admin-at-startup/
categories:
  - AutoHotkey
  - Windows 8
tags:
  - AHK
  - AutoHotkey
  - Run As Admin
  - Run at Startup
  - Scheduled Tasks
  - Task Scheduler
  - Windows 8
---

__Update__: Before you go running your script as an admin, see if [this less obtrusive fix](https://blog.danskingdom.com/get-autohotkey-to-interact-with-admin-windows-without-running-ahk-script-as-admin/) will solve your problems.

A few weeks back I posted [some problems with running AutoHotkey (AHK) in Windows 8, and that the solution was to run your AHK script as admin](https://blog.danskingdom.com/autohotkey-cannot-interact-with-windows-8-windowsor-can-it/). I also showed how to have the script start automatically when you logged into Windows. What I didn't realize at the time though was that the method only worked for launching my AHK script as an admin because I had disabled UAC in the registry (which prevents most Metro apps from working in Windows 8, and likely isn't acceptable for most people).

So here is a Windows 8, UAC-friendly method to automatically launch your AHK scripts as admin at startup (also works in previous versions of Windows). The trick is to use the Task Scheduler:

1. Open the Task Scheduler (also known as "Schedule tasks" in Windows 8 Settings).
    ![Open Task Scheduler](/assets/Posts/2012/11/open-task-scheduler.png)
1. Create a new Basic Task.
1. Give it a name and description (something like "launch AutoHotkey script at login"), and then specify to have it run "When I log on". Then specify that you want it to "Start a program", and then point it towards your AutoHotkey script. Before you finish the wizard, check off "Open the Properties dialog for this task when I click Finish".
    ![Create Basic Task in Task Scheduler](/assets/Posts/2012/11/create-basic-task-in-task-scheduler1.png)
1. When that Properties dialog opens up, go to the Conditions tab and make sure none of the checkboxes under the Power category are checked off; this will ensure the script still launches if you are on a laptop and not plugged into AC power.
    ![Basic Task Conditions](/assets/Posts/2012/11/basic-task-conditions1.png)
1. __Now here is the important part__: To have your script "Run as admin", on the General tab check off "Run with highest privileges".

    ![Run Scheduled Task as Admin](/assets/Posts/2012/11/run-scheduled-task-as-admin_thumb3.png)

    Now your AHK script should start automatically as soon as you log into Windows; even when UAC is enabled :-).
1. If your AHK script uses an __#Include__ statement to include other files, you may get an error similar to this one when your task runs:

    > #Include file ... cannot be opened. The program will exit.

    ![AHK Cannot Open Include File](/assets/Posts/2012/11/ahk-cannot-open-include-file.png)

    The solution to this is to tell your AHK script to start in the same directory as the file that you want to include. So you will need to edit your scheduled task's Action to specify the Start In directory.

    ![Task Scheduler Start In Directory](/assets/Posts/2012/11/task-scheduler-start-in-directory.png)

Happy coding!

## Update

What I failed to realize earlier was that by default the Task Scheduler runs it's programs in non-interactive mode, so they may run as the correct user, __but in a different user session__. Since most AHK scripts are interactive (i.e. they respond to user input), this means that your script may not work exactly as it should all of the time. For example, my AHK scripts were not able to launch ClickOnce applications.

The fix is to create your Scheduled Task in interactive mode. Unfortunately you cannot do this through the GUI; it must be done through the command line. So if you open up a command prompt you can use the following command to create a new interactive scheduled task:

> schtasks /Create /RU "\[Domain\]\[Username\]" /SC ONLOGON /TN "[Task Name]" /TR "[Path to program to run]" /IT /V1

for example:

> schtasks /Create /RU "Dan Schroeder" /SC ONLOGON /TN "Launch AHK Command Picker" /TR "D:AHKStuffAHKCommandPicker.ahk" /IT /V1

The /IT switch is what tells it to create the task in Interactive mode. The /V1 switch actually specifies to create the task as a Windows XP/2000/Server 2003 compatible task, and has the added benefit of making the task run as admin by default with the Start In directory specified as the directory holding the file to run (i.e. steps 5 and 6 above; you will still need to do step 4 though).

If you already have your Scheduled Task created, you can simply make it interactive with the command:

> schtasks /Change /TN "[Task Name]" /IT

I hope you find this as helpful as I did!
