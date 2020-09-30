---
title: "Close those superfluous Zoom windows automatically"
permalink: /Close-those-superfluous-Zoom-windows-automatically/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22T00:00:00-06:00
comments_locked: false
categories:
  - Zoom
  - AutoHotkey
  - Productivity
tags:
  - Zoom
  - AutoHotkey
  - Productivity
  - AHK
---

We've been using Zoom for a while at my office, and I've noticed that anytime I join a meeting I need to go and close windows/tabs afterward.

Anytime you join a Zoom meeting via a link, whether via an Outlook meeting or URL sent in a DM, the flow is:

1. Click the meeting URL link.
1. It opens a tab in your browser.
1. This then launches the Zoom app.
1. Which then opens the actual Zoom meeting window.

Steps 2 and 3 are really unnecessary, and I find myself always having to go and close them after I've joined the meeting.

To help eliminate this tedious constant closing of windows and browser tabs, I've created the following AutoHotkey script:

```csharp
#SingleInstance, Force
#Persistent

CloseZoomWindowsAfterJoiningAMeeting()
{
  browserWindowTitleToMatch := "Launch Meeting - Zoom"

  ; If a browser tab to join a meeting exists.
  IfWinExist, %browserWindowTitleToMatch%
  {
    ; Put the browser tab window in focus.
    WinActivate, %browserWindowTitleToMatch%

    ; Close the browser tab (Ctrl + F4).
    SendInput, ^{F4}

    ; Wait until the URL opened the Zoom app to join the meeting.
    WinWait, ahk_class ZPPTMainFrmWndClassEx,,5000

    ; Close the Zoom app.
    IfWinExist, ahk_class ZPPTMainFrmWndClassEx
    {
      WinClose, ahk_class ZPPTMainFrmWndClassEx
    }
  }
}
SetTimer, CloseZoomWindowsAfterJoiningAMeeting, 250
```

If you're not familiar with AutoHotkey or how to use it, check out [this post](/Get-up-and-running-with-AutoHotkey) to get familiar with it and how you can automate away many daily annoyances like this one.

If programming code scares you, or you're just feeling lazy, go ahead and [download the executable](/assets/Posts/2020-09-29-Close-those-superfluous-Zoom-windows-automatically/CloseZoomWindowsAfterJoiningMeeting.exe) that will run [this script](/assets/Posts/2020-09-29-Close-those-superfluous-Zoom-windows-automatically/CloseZoomWindowsAfterJoiningMeeting.ahk) for you.
You'll likely still want to check out [how to automatically run it automatically when you log into Windows](/Get-up-and-running-with-AutoHotkey/#run-scripts-automatically-at-startup) though.

Happy Zooming!
