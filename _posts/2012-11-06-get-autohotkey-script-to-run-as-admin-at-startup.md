---
id: 127
title: Get AutoHotkey Script To Run As Admin At Startup
date: 2012-11-06T14:16:00-06:00
author: deadlydog
guid: https://deadlydog.wordpress.com/?p=127
permalink: /get-autohotkey-script-to-run-as-admin-at-startup/
jabber_published:
  - "1353356533"
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
**<Update>**Before you go running your script as an admin, see if [this less obtrusive fix](http://dans-blog.azurewebsites.net/get-autohotkey-to-interact-with-admin-windows-without-running-ahk-script-as-admin/) will solve your problems.**</Update>**

A few weeks back I posted [some problems with running AutoHotkey (AHK) in Windows 8, and that the solution was to run your AHK script as admin](http://dans-blog.azurewebsites.net/?p=97).&#160; I also showed how to have the script start automatically when you logged into Windows.&#160; What I didn’t realize at the time though was that the method only worked for launching my AHK script as an admin because I had disabled UAC in the registry (which prevents most Metro apps from working in Windows 8, and likely isn’t acceptable for most people).

So here is a Windows 8, UAC-friendly method to automatically launch your AHK scripts as admin at startup (also works in previous versions of Windows).&#160; The trick is to use the Task Scheduler:

1. Open the Task Scheduler (also known as “Schedule tasks” in Windows 8 Settings).

[<img title="Open Task Scheduler" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="Open Task Scheduler" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/open-task-scheduler_thumb.png" width="409" height="556" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/open-task-scheduler.png)

2. Create a new Basic Task.

3. Give it a name and description (something like “launch AutoHotkey script at login”), and then specify to have it run “When I log on”.&#160; Then specify that you want it to “Start a program”, and then point it towards your AutoHotkey script.&#160; Before you finish the wizard, check off “Open the Properties dialog for this task when I click Finish”.

[<img title="Create Basic Task in Task Scheduler" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="Create Basic Task in Task Scheduler" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/create-basic-task-in-task-scheduler_thumb1.png" width="766" height="534" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/create-basic-task-in-task-scheduler1.png)

4. When that Properties dialog opens up, go to the Conditions tab and make sure none of the checkboxes under the Power category are checked off; this will ensure the script still launches if you are on a laptop and not plugged into AC power.

[<img title="Basic Task Conditions" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="Basic Task Conditions" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/basic-task-conditions_thumb1.png" width="677" height="511" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/basic-task-conditions1.png)

5. **Now here is the important part**; To have your script “Run as admin”, on the General tab check off “Run with highest privileges”.

[<img title="Run Scheduled Task as Admin_thumb[3]" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="Run Scheduled Task as Admin_thumb[3]" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/run-scheduled-task-as-admin_thumb3_thumb.png" width="650" height="491" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/run-scheduled-task-as-admin_thumb3.png)

Now your AHK script should start automatically as soon as you log into Windows; even when UAC is enabled <img class="wlEmoticon wlEmoticon-smile" style="border-top-style: none; border-bottom-style: none; border-right-style: none; border-left-style: none" alt="Smile" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/wlemoticon-smile2.png" />

6. If your AHK script uses an **#Include** statement to include other files, you may get an error similar to this one when your task runs:

“#Include file … cannot be opened. The program will exit.”

[<img title="AHK Cannot Open Include File" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="AHK Cannot Open Include File" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/ahk-cannot-open-include-file_thumb.png" width="441" height="226" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/ahk-cannot-open-include-file.png)

The solution to this is to tell your AHK script to start in the same directory as the file that you want to include.&#160; So you will need to edit your scheduled task’s Action to specify the Start In directory.

[<img title="Task Scheduler Start In Directory" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="Task Scheduler Start In Directory" src="http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/task-scheduler-start-in-directory_thumb.png" width="1095" height="504" />](http://dans-blog.azurewebsites.net/wp-content/uploads/2012/11/task-scheduler-start-in-directory.png)

Happy coding!

==< EDIT >==

What I failed to realize earlier was that by default the Task Scheduler runs it’s programs in non-interactive mode, so they may run as the correct user, **but in a different user session**.&#160; Since most AHK scripts are interactive (i.e. they respond to user input), this means that your script may not work exactly as it should all of the time.&#160; For example, my AHK scripts were not able to launch ClickOnce applications.

The fix is to create your Scheduled Task in interactive mode.&#160; Unfortunately you cannot do this through the GUI; it must be done through the command line.&#160; So if you open up a command prompt you can use the following command to create a new interactive scheduled task:

> schtasks /Create /RU "\[Domain\]\[Username\]" /SC ONLOGON /TN "[Task Name]" /TR "[Path to program to run]" /IT /V1

for example:

> schtasks /Create /RU "Dan Schroeder" /SC ONLOGON /TN "Launch AHK Command Picker" /TR "D:AHKStuffAHKCommandPicker.ahk" /IT /V1

The /IT switch is what tells it to create the task in Interactive mode.&#160; The /V1 switch actually specifies to create the task as a Windows XP/2000/Server 2003 compatible task, and has the added benefit of making the task run as admin by default with the Start In directory specified as the directory holding the file to run (i.e. steps 5 and 6 above; you will still need to do step 4 though).

If you already have your Scheduled Task created, you can simply make it interactive with the command:

> schtasks /Change /TN “[Task Name]” /IT

I hope you find this as helpful as I did!

==</ EDIT >==
