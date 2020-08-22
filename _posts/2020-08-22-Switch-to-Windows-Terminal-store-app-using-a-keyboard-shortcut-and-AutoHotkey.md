---
title: "Switch to Windows Terminal store app using a keyboard shortcut and AutoHotkey"
permalink: /Switch-to-Windows-Terminal-store-app-using-a-keyboard-shortcut-and-AutoHotkey/
#date: 2099-01-17T00:00:00-06:00
#last_modified_at: 2099-01-22T00:00:00-06:00
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

I recently decided to try out the new [Windows Terminal](https://github.com/microsoft/terminal) to see how it compared to [ConEmu](https://conemu.github.io), which is my usual console.
The recommended way to get the Windows Terminal is to [download it from the Microsoft Store](https://www.microsoft.com/en-us/p/windows-terminal/9n0dx20hk701) so that it can automatically update itself as new versions are released.

While the Windows Terminal is not as mature and feature rich as ConEmu, I did enjoy it, and it's being actively worked on with plenty of features set to come down the road.
I'd also recommend following [Scott Hanselman's post about how to make it look nicer](https://www.hanselman.com/blog/HowToMakeAPrettyPromptInWindowsTerminalWithPowerlineNerdFontsCascadiaCodeWSLAndOhmyposh.aspx).

## Launch the Windows Terminal from the command line

I decided to try it as my default terminal for a while.
I currently have a keyboard shortcut setup to quickly and easily launch ConEmu, and wanted to do the same for Windows Terminal so it was never more than a keystroke away.
However, because the Windows Terminal is installed as a Microsoft Store app, the location of the executable isn't very obvious, and it is likely to change every time the app is updated.
Luckily, I found [this wonderful post describing how to launch Microsoft Store apps from the command line](https://answers.microsoft.com/en-us/windows/forum/windows_10-windows_store/starting-windows-10-store-app-from-the-command/836354c5-b5af-4d6c-b414-80e40ed14675).

From there, I was able to track down that you can launch the Windows Terminal store app using:

```cmd
explorer.exe shell:AppsFolder\Microsoft.WindowsTerminal_8wekyb3d8bbwe!App
```

## Launch the Windows Terminal via keyboard shortcut

The above post mentions that you can simply navigate to `shell:AppsFolder`, find the Windows Terminal app, right-click on it, and choose `Create shortcut`.
This will put a shortcut to the application on your desktop, and like any shortcut file in Windows, you can right-click on it, go to `Properties`, and assign it a `Shortcut key` that can be used to launch the application.

While this will allow you to launch the Windows Terminal with a keyboard shortcut, I don't use this method because every time you do the keyboard shortcut it opens a new instance of the Windows Terminal, which isn't what I want.

## Switch to the Windows Terminal via keyboard shortcut and AutoHotkey

To be able to both launch the Windows Terminal, as well as simply switch to the Windows Terminal window if it's already open, I use [AutoHotkey](https://www.autohotkey.com).

I've [blogged about AutoHotkey in the past](https://blog.danskingdom.com/categories/#autohotkey), and if you've never used it you really should check it out.

In an new or existing AutoHotkey script, you can define this function to launch the Windows Terminal, or put it in focus if it's already open:

```csharp
SwitchToWindowsTerminal()
{
  windowHandleId := WinExist("ahk_exe WindowsTerminal.exe")
  windowExistsAlready := windowHandleId > 0

  ; If the Windows Terminal is already open, put it in focus.
  if (windowExistsAlready = true)
  {
    WinActivate, "ahk_id %windowHandleId%"
    WinShow, "ahk_id %windowHandleId%"
  }
  ; Else it's not already open, so launch it.
  else
  {
    ; How to find the Package Family Name and App ID of a Windows Store app to launch it from the command line: https://answers.microsoft.com/en-us/windows/forum/windows_10-windows_store/starting-windows-10-store-app-from-the-command/836354c5-b5af-4d6c-b414-80e40ed14675
    Run, explorer.exe shell:AppsFolder\Microsoft.WindowsTerminal_8wekyb3d8bbwe!App
  }
}

; Use Ctrl+Shift+C to launch/restore the Windows Terminal.
^+c::SwitchToWindowsTerminal()
```

The last line in the script defines the keyboard shortcut and has it call the function.

Here I'm using `Ctrl`+`Shift`+`C` (^+c) for my keyboard shortcut, but you could use something else like `Windows Key`+`C` (#c) or `Ctrl`+`Alt`+`C` (^!c).
Check out the [AutoHotkey key list](https://www.autohotkey.com/docs/KeyList.htm) for other non-obvious key symbols.

If you've never used AutoHotkey before and want to get this working, all you need to do is:

1. Install [AutoHotkey](https://www.autohotkey.com)
1. Create a new text file with a `.ahk` extension
1. Copy-paste the above script into the file
1. Double click the file to run it.

Once that's done, you should be able to use your keyboard shortcut to switch to Windows Terminal.

You'll also likely want to have your script startup automatically with Windows so that you don't have to manually run it all the time.
This is as easy as dropping the .ahk file (or a shortcut to it) in your `shell:Startup` directory.
Windows will automatically run all files in this directory every time you log in.

> Shameless Plug: You can also checkout my open source project [AHK Command Picker](https://github.com/deadlydog/AHKCommandPicker) that allows you to use a GUI picker instead of having to remember a ton of keyboard shortcuts.

## Conclusion

As a software developer, I'm constantly in and out of the terminal for running Git and PowerShell commands.
I can now use `Ctrl`+`Shift`+`C` to switch to the Windows Terminal at anytime, no matter what other application currently has focus, and not having to reach for the mouse.

I hope you've found this information helpful.

Happy command lining :)
