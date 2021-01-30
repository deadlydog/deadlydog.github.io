---
title: "Bring up the Windows Terminal in a keystroke"
permalink: /Bring-up-the-Windows-Terminal-in-a-keystroke/
#date: 2099-01-17T00:00:00-06:00
last_modified_at: 2020-08-27
comments_locked: false
categories:
  - Windows Terminal
  - AutoHotkey
  - Microsoft Store
  - Productivity
  - Shortcuts
tags:
  - Windows Terminal
  - AutoHotkey
  - Microsoft Store
  - AHK
  - Keyboard shortcuts
---

I decided to try out the new [Windows Terminal](https://github.com/microsoft/terminal) to see how it compared to [ConEmu](https://conemu.github.io), which is my usual console.
The recommended way to get the Windows Terminal is to [download it from the Microsoft Store](https://www.microsoft.com/en-us/p/windows-terminal/9n0dx20hk701) so that it can automatically update itself as new versions are released.

While the Windows Terminal is not as mature and feature rich as ConEmu, I did enjoy it, and it's being actively worked on with plenty of features coming down the road.
I'd also recommend following [Scott Hanselman's post about how to make it look nicer](https://www.hanselman.com/blog/HowToMakeAPrettyPromptInWindowsTerminalWithPowerlineNerdFontsCascadiaCodeWSLAndOhmyposh.aspx).

One feature I missed right away was ConEmu allows you to set a keyboard shortcut to put it in focus.
As a software developer, I'm constantly in and out of the terminal, and being able to get to it in a keystroke is very convenient.

## Method 1: Pin Windows Terminal to the taskbar

The easiest way to get to the Windows Terminal using a keyboard shortcut is to pin it to the taskbar.
Not only does it make it easy to click on with the mouse, but you can also use the <kbd>Windows Key</kbd> + <kbd>[number]</kbd> keyboard shortcut to launch it or put it in focus.

For example, if on your taskbar from left to right you have: Edge, Chrome, Windows Terminal, then you could use <kbd>Windows Key</kbd> + <kbd>3</kbd> to launch the Windows Terminal, or put it in focus if it's already open.
Similarly, <kbd>Windows Key</kbd> + <kbd>1</kbd> would launch Edge, and <kbd>Windows Key</kbd> + <kbd>2</kbd> would launch Chrome.

This is a simple solution and it works, but the reason I'm not a fan of it is:

1. If I reorder the windows on the taskbar then the keyboard shortcut changes.
1. This method only works for the first 10 items on the taskbar. i.e. you can't do <kbd>Windows Key</kbd> + <kbd>11</kbd>`.
1. I find it awkward to use the Windows Key with any numbers greater than 4.

So let's continue exploring other options.

## Method 2: Launch Windows Terminal from the command line

The Windows Terminal is installed as a Microsoft Store app, so the location of the executable isn't very obvious, and it is likely to change every time the app is updated.
This can make launching it via other applications or scripts tough.
Luckily, I found [this wonderful post describing how to launch Microsoft Store apps from the command line](https://answers.microsoft.com/en-us/windows/forum/windows_10-windows_store/starting-windows-10-store-app-from-the-command/836354c5-b5af-4d6c-b414-80e40ed14675).

From there, I was able to track down that you can launch the Windows Terminal store app using:

```text
explorer.exe shell:AppsFolder\Microsoft.WindowsTerminal_8wekyb3d8bbwe!App
```

> Update: It turns out you can also simply run `wt` from the command line to launch the Windows Terminal, as `wt.exe` gets added to the Windows PATH when the Windows Terminal is installed.

Now that we know how to launch it from the command line, you can use this from any custom scripts or application launchers you might use.
This is in fact what I show how to do from AutoHotkey further below.

While this command allows us to launch Windows Terminal, it doesn't allow us to put it in focus if it's already open, so let's continue.

## Method 3: Launch Windows Terminal via a custom keyboard shortcut

The above post mentions that you can simply navigate to `shell:AppsFolder`, find the Windows Terminal app, right-click on it, and choose `Create shortcut`.
This will put a shortcut to the application on your desktop, and like any shortcut file in Windows, you can right-click on it, go to `Properties`, and assign it a `Shortcut key` that can be used to launch the application.

While this will allow you to launch the Windows Terminal with a custom keyboard shortcut, like the previous method, it opens a new instance of the Windows Terminal every time, which isn't what I want, so let's continue.

## Method 4: Switch to Windows Terminal via keyboard shortcut and AutoHotkey

To use a custom keyboard shortcut to both launch the Windows Terminal, as well as simply switch to the Windows Terminal window if it's already open, I use [AutoHotkey](https://www.autohotkey.com).
I've [blogged about AutoHotkey in the past](https://blog.danskingdom.com/categories/#autohotkey), and if you've never used it you really should check it out.

In a new or existing AutoHotkey script, you can define this function and hotkey:

```csharp
SwitchToWindowsTerminal()
{
  windowHandleId := WinExist("ahk_exe WindowsTerminal.exe")
  windowExistsAlready := windowHandleId > 0

  ; If the Windows Terminal is already open, determine if we should put it in focus or minimize it.
  if (windowExistsAlready = true)
  {
    activeWindowHandleId := WinExist("A")
    windowIsAlreadyActive := activeWindowHandleId == windowHandleId

    if (windowIsAlreadyActive)
    {
      ; Minimize the window.
      WinMinimize, "ahk_id %windowHandleId%"
    }
    else
    {
      ; Put the window in focus.
      WinActivate, "ahk_id %windowHandleId%"
      WinShow, "ahk_id %windowHandleId%"
    }
  }
  ; Else it's not already open, so launch it.
  else
  {
    Run, explorer.exe shell:AppsFolder\Microsoft.WindowsTerminal_8wekyb3d8bbwe!App
  }
}

; Hotkey to use Ctrl+Shift+C to launch/restore the Windows Terminal.
^+c::SwitchToWindowsTerminal()
```

The last line in the script defines the keyboard shortcut and has it call the function.

Here I'm using <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>C</kbd> (^+c) for my keyboard shortcut, but you could use something else like <kbd>Windows Key</kbd> + <kbd>C</kbd> (#c) or <kbd>Ctrl</kbd> + <kbd>Alt</kbd> + <kbd>C</kbd> (^!c).
Check out the [AutoHotkey key list](https://www.autohotkey.com/docs/KeyList.htm) for other non-obvious key symbols.

You may have also noticed in the code that if the window is already in focus, we minimize it.
This allows me to easily switch to and away from the Windows Terminal using the same shortcut keys.
Using <kbd>Alt</kbd> + <kbd>Tab</kbd> would also work to switch back to your previous application.

### Getting the AutoHotkey script running

If you've never used AutoHotkey before and want to get this working, all you need to do is:

1. Install [AutoHotkey](https://www.autohotkey.com)
1. Create a new text file with a `.ahk` extension
1. Copy-paste the above script into the file
1. Double click the file to run it.

Once that's done, you should be able to use your keyboard shortcut to switch to Windows Terminal.

You'll also likely want to have your script startup automatically with Windows so that you don't have to manually run it all the time.
This is as easy as dropping the .ahk file (or a shortcut to it) in your `shell:Startup` directory.
Windows will automatically run all files in this directory every time you log in.

For more detailed instructions and information, [see this post](/Get-up-and-running-with-AutoHotkey/)

## Conclusion

As a software developer, I'm constantly in and out of the terminal for running Git and PowerShell commands.
If you're ok simply pinning Windows Terminal to the taskbar in a dedicated position and don't mind the preset keyboard shortcut, then roll with that.
For myself, using AutoHotkey I can now use <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>C</kbd> to switch to and away from the Windows Terminal at anytime, no matter what other application currently has focus, and not having to reach for the mouse.

> Shameless Plug: You can also checkout my open source project [AHK Command Picker](https://github.com/deadlydog/AHKCommandPicker) that allows you to use a GUI picker instead of having to remember a ton of keyboard shortcuts.

I hope you've found this information helpful.

Happy command lining :)
