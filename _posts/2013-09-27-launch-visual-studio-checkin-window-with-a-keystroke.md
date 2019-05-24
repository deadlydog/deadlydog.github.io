---
id: 491
title: Launch Visual Studio Checkin Window With A Keystroke
date: 2013-09-27T11:18:30-06:00
guid: http://dans-blog.azurewebsites.net/?p=491
permalink: /launch-visual-studio-checkin-window-with-a-keystroke/
categories:
  - Productivity
  - TFS
  - Visual Studio
tags:
  - Checkin
  - Commit
  - Hotkey
  - Key
  - keyboard shortcuts
  - Shelve
  - Shortcut
  - TFS
  - visual studio
---
A few weeks ago I blogged about [how you can get custom TFS checkin policies to work when committing from the command line](http://dans-blog.azurewebsites.net/getting-custom-tfs-checkin-policies-to-work-when-committing-from-the-command-line-i-e-tf-checkin/). In that post, I had a quick aside about how you can launch the checkin window (i.e. pending changes) with a quick keystroke using [AutoHotkey](http://www.autohotkey.com/). I realize that many people don’t use AutoHotkey (although you really should; it can save you a lot of time), so I thought I would show how you can accomplish this task without AutoHotkey. It’s quite simple really, and it allows you to launch the VS Checkin window from anywhere, even if you don’t have Visual Studio open.



### Steps To Launch VS Checkin Window From The Visual Studio Command Prompt

  1. Open the Visual Studio Command Prompt. To do this, just hit the windows key and type **Developer Command Prompt For VS2012** if using VS 2012, or **Visual Studio Command Prompt (2010)** if using VS 2010.
  2. In the VS Command Prompt, change to a directory that is in your TFS workspace mapping. e.g. **cd C:\Dev\TFS**
  3. Type **tf checkin** and hit enter**.**



### Steps To Launch VS Checkin Window With A Shortcut Key

  1. Right click on your desktop and choose **New** –> **Shortcut** to create a new shortcut file.
    [<img title="CreateShortcutOnDesktop" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="CreateShortcutOnDesktop" src="/assets/Posts/2013/09/CreateShortcutOnDesktop_thumb1.png" width="482" height="444" />](/assets/Posts/2013/09/CreateShortcutOnDesktop1.png)
  2. Have the shortcut point to the TF executable. This can be found at **"C:\Program Files (x86)\Microsoft Visual Studio 11.0\Common7\IDE\TF.exe"**.
    [**<img title="PathToTfExeForShortcut" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="PathToTfExeForShortcut" src="/assets/Posts/2013/09/PathToTfExeForShortcut_thumb.png" width="600" height="446" />**](/assets/Posts/2013/09/PathToTfExeForShortcut.png)
    </li>

      * Enter the name for your shortcut file, such as VS Checkin.
        [<img title="NameShortcut" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="NameShortcut" src="/assets/Posts/2013/09/NameShortcut_thumb.png" width="600" height="446" />](/assets/Posts/2013/09/NameShortcut.png)
        </li>

          * Now that the shortcut has been created on your desktop, right-click on it and go the the properties (or use alt+enter). You will want to: </ol>

          * add **checkin** to the very end of the Target,
          * change the Start In directory to a directory you have mapped in your TFS workspace,
          * and assign it a Shortcut Key.
            [<img title="CheckinShortcutPropertiesToChange" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="CheckinShortcutPropertiesToChange" src="/assets/Posts/2013/10/CheckinShortcutPropertiesToChange_thumb.png" width="599" height="662" />](/assets/Posts/2013/10/CheckinShortcutPropertiesToChange.png)



        ### Results

        That’s it. Go ahead and try your shortcut key. I’ll wait. You should see something like this:

        [<img title="VsCheckinWindow" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="VsCheckinWindow" src="/assets/Posts/2013/09/VsCheckinWindow_thumb.png" width="600" height="376" />](/assets/Posts/2013/09/VsCheckinWindow.png)

        Notice that it pops both a command prompt window and the actual checkin window. If you don’t have any pending changes, then the command prompt window will simply open and close.



        ### More Information and Caveats

        <u></u>

        <u>Old Style Checkin Window</u>

        You probably noticed in the screenshot above that even though I’m using Visual Studio 2012, it still pops the old VS 2010 style of checkin window. I actually prefer this popped out window to the VS 2012 pending changes pane, and I know [a lot of people agree](http://visualstudio.uservoice.com/forums/121579-visual-studio/suggestions/2654486-vs11-bring-back-the-old-pending-changes-window).



        <u>Getting The Shortcut Off Your Desktop</u>

        Above I had you create the shortcut on your desktop, but you might not want to have it clutter up your desktop. Unfortunately if you move it to some other folder you will find that the shortcut key no longer works. For some reason **I have found that for the shortcut key to work the shortcut file must either be on your desktop, or in the Start Menu**. If you are using Windows 7 you can simply drag and drop the shortcut into the Programs section of the Start Menu. For us Windows 8.0 folks the Start Menu is gone, so just manually move the shortcut to **“C:\ProgramData\Microsoft\Windows\Start Menu\Programs”**.

        You may find that after moving the shortcut file the shortcut key no longer works. You just have to go back into the Shortcut file properties, assign it a new Shortcut Key, hit Apply, and then assign the original Shortcut Key back.



        <u>Shelving Changes In TFS Using The Old Style Shelve Window</u>

        If you are using TFS and want to shelve your changes instead of checking them in, you can access the old Shelve Pending Changes window in the same way. In step 4 above, instead of adding **checkin** as the TF.exe argument, add **shelve**. To launch it from the Visual Studio Command Prompt, type **tf shelve** instead of **tf checkin**.



        <u>Getting Custom TFS Checkin Policies To Work</u>

        As you may have guessed from the very start of the article, custom TFS checkin policies don’t run in this checkin window by default; they will throw errors. Fortunately for you I have already created [a registry file that you can run after each checkin policy update](http://dans-blog.azurewebsites.net/getting-custom-tfs-checkin-policies-to-work-when-committing-from-the-command-line-i-e-tf-checkin/) that you do which will rectify this problem.



        I hope this helps you be more productive. Happy coding!
