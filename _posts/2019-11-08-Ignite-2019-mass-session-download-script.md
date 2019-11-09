---
title: "Ignite 2019 mass session download script"
permalink: /Ignite-2019-mass-session-download-script/
#date: 2099-01-17T00:00:00-06:00
#last_modified_at: 2099-01-22T00:00:00-06:00
comments_locked: false
categories:
  - Learning
tags:
  - Ignite
  - Learning
---

The Microsoft Ignite 2019 conference has ended, and you know what that means.
Time to mass download all the sessions you missed!

In previous years you'd have to seek out a community developed script; this year though, Microsoft is providing it for you :)

If you look below any of the session videos, such as [Scott Hanselman's Dev Keynote](https://myignite.techcommunity.microsoft.com/sessions/81591?source=schedule), you'll notice a link that hasn't been there in previous years.
The [Get the bulk session resource download script](https://myignite.techcommunity.microsoft.com/Download-Resources.zip) link.

![Screenshot](/assets/Posts/2019-11-08-Ignite-2019-mass-session-download-script/IgniteApplicationDevKeynoteScreenshot.png)

In case that download link disappears in the future, you can also [grab it directly from me here](/assets/Posts/2019-11-08-Ignite-2019-mass-session-download-script/Download-Resources.zip).

The nice thing about this script is it will also download the session's slide deck if it exists.
You can either download EVERY session, or use the `-sessionCodes` command-line parameter to specify just the IDs of the sessions you want to download.
There's a readme file included in the zip that specifies how the script can be used.
All of the sessions and there IDs [can be found on the session catalog here](https://myignite.techcommunity.microsoft.com/sessions).

For example, this is the PowerShell command I used to download just the sessions that I'm interested in and wasn't able to attend (because there was too much other awesome content at the same time).

```powershell
.\Download-Resources.ps1 -directory C:\Temp -sessionCodes "BRK3064,BRK2203,BRK3066,POWA10,BRK2076,BRK2001,BRK2046,TK05,BRK2375,BRK2075,BRK2166,BRK3119,BRK3176,BRK3098,BRK2077,OPS40,MOD50,BRK2377,BRK2074,BRK4006,BRK3318,BRK3316"
```

Happy learning!
