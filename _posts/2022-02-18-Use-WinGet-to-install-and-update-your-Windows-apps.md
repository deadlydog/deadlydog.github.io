---
title: "Use WinGet to install and update your Windows apps"
permalink: /Use-WinGet-to-install-and-update-your-Windows-apps/
#date: 2099-01-15T00:00:00-06:00
last_modified_at: 2022-04-15T00:00:00-06:00
comments_locked: false
categories:
  - Windows
  - Productivity
tags:
  - Windows
  - Productivity
---

I installed all my Windows apps on my new computer with one line using WinGet.

It was simple.
It was easy.
Here's what I did.

## What is WinGet

[WinGet](https://docs.microsoft.com/en-us/windows/package-manager/winget/) is Microsoft's native command line application installer, and it now comes pre-installed with Windows 10+.
If you have Windows 7 or 8, you'll need to manually install it via [the `App Installer` app in the Microsoft Store](https://www.microsoft.com/en-us/p/app-installer/9nblggh4nns1).

You can think of WinGet like the Microsoft Store, but for the command line, and it actually has the apps you're looking for.
Not just UWP (Universal Windows Platform) apps like the Microsoft Store has, but the apps you've been installing for 2 decades.
Apps installed via an .exe or .msi.
Win32 apps.

Not only does it support every type of app that you could install manually, the app catalog is huge; currently over 3000 apps.
There wasn't a single app I wanted that I couldn't find on WinGet.

## Head to WinGet.run

Don't believe me?
Head over to [WinGet.run](https://winget.run) and see for yourself.

Search the name of the app you're looking for and see if it's there.

Once you've found it, click the `Copy command` link and it will copy the WinGet command you can use to install that app from the command line.

![WinGet.run website search screenshot](/assets/Posts/2022-02-18-Use-WinGet-to-install-and-update-your-Windows-apps/WinGetRunWebsiteSearchScreenshot.png)

The command copied to your clipboard will look like:

```shell
winget install -e --id Spotify.Spotify
```

## Installing apps with WinGet

Open a command prompt.
You can use cmd, PowerShell, Windows Terminal, Bash, or whatever you prefer.

Paste the command you copied from WinGet.run into the command prompt, hit enter, and watch your app get installed.

![WinGet install Spotify screenshot](/assets/Posts/2022-02-18-Use-WinGet-to-install-and-update-your-Windows-apps/WinGetInstallSpotifyScreenshot.png)

You can see here that Spotify was downloaded and installed in 24 seconds.
In this case, Spotify installed without prompting me for anything.

You may get prompted by Windows UAC to allow the app installer to run if it requires elevated privileges.
This can be avoided by opening your command prompt as administrator.

![Run command prompt as administrator](/assets/Posts/2022-02-18-Use-WinGet-to-install-and-update-your-Windows-apps/RunCommandPromptAsAdministratorScreenshot.png)

Some apps show the install wizard while it installs, others don't.
From my experience, most apps that do show the install wizard don't prompt you to do anything; you can just see it working.
If you like, you can provide the `--silent` parameter to suppress showing the installer and have it install with the default options, if the app's installer supports it.

```shell
winget install -e --id Spotify.Spotify --silent
```

I keep a text file in my OneDrive of apps that I like to install anytime I wipe my computer.
That text file now looks like this:

```shell
===========================================================
On WinGet: https://winget.run
===========================================================
winget install -e --id 7zip.7zip
winget install -e --id Audacity.Audacity
winget install -e --id Microsoft.AzureCLI
winget install -e --id Lexikos.AutoHotkey
winget install -e --id Google.Chrome
winget install -e --id Discord.Discord
winget install -e --id Ditto.Ditto
winget install -e --id Docker.DockerDesktop
winget install -e --id File-New-Project.EarTrumpet
winget install -e --id voidtools.Everything
winget install -e --id Telerik.Fiddler.Classic
winget install -e --id Mozilla.Firefox
winget install -e --id Git.Git
winget install -e --id GitExtensionsTeam.GitExtensions
winget install -e --id GoLang.Go
winget install -e --id StefanTools.grepWin
winget install -e --id HandBrake.HandBrake
winget install -e --id icsharpcode.ILSpy
winget install -e --id JoachimEibl.KDiff3
winget install -e --id Notepad++.Notepad++
winget install -e --id JanDeDobbeleer.OhMyPosh
winget install -e --id Postman.Postman
winget install -e --id Microsoft.PowerShell
winget install -e --id Microsoft.PowerToys
winget install -e --id Python.Python.3
winget install -e --id RubyInstallerTeam.RubyWithDevKit
winget install -e --id Spotify.Spotify (Cannot install when running as administrator)
winget install -e --id Microsoft.SQLServerManagementStudio
winget install -e --id Valve.Steam
winget install -e --id ShareX.ShareX
winget install -e --id Toggl.TogglDesktop
winget install -e --id TortoiseGit.TortoiseGit
winget install -e --id Microsoft.VisualStudio.2022.Enterprise (Will still need to select your specific workloads afterward)
winget install -e --id Microsoft.VisualStudioCode (Will not install the File Explorer context menu shortcuts by default; have to manually install to check those boxes in the installer)
winget install -e --id VideoLAN.VLC
winget install -e --id Radionomy.Winamp (Latest version does not allow queuing songs, so better to use 5.6 from https://archive.org/download/winamp5666_full_all_redux)
winget install -e --id Microsoft.WindowsTerminal
winget install -e --id AntibodySoftware.WizTree
winget install -e --id Zoom.Zoom
```

Here I've got each install command on a separate line, but you can combine them into a single line to copy-paste into the command prompt in one shot by separating them with a semicolon, like this:

```shell
winget install -e --id 7zip.7zip;winget install -e --id Lexikos.AutoHotkey;winget install -e --id Google.Chrome
```

WinGet installs apps one at a time, so if you give it a large number of apps to install or update, it may take a while depending on how long each app takes to download and install.

## Updating apps

Keeping your apps up-to-date with WinGet is a breeze.
Just use the `winget upgrade` command.
If you don't pass any parameters to it, it will scan your computer and tell you which apps can be updated.

![WinGet upgrade command screenshot](/assets/Posts/2022-02-18-Use-WinGet-to-install-and-update-your-Windows-apps/WinGetUpgradeCommandScreenshot.png)

Notice that there are apps in this list that I didn't install with WinGet, such as "Microsoft Azure Storage Explorer".
That's right, WinGet can even upgrade apps that it didn't install!

From there you can upgrade a single app using the `--id` or `--name` parameter:

```shell
winget upgrade --id Git.Git
```

```shell
winget upgrade --name Git
```

Or you can easily upgrade every application using:

```shell
winget upgrade --all
```

![WinGet upgrade all command screenshot](/assets/Posts/2022-02-18-Use-WinGet-to-install-and-update-your-Windows-apps/WinGetUpgradeAllCommandScreenshot.png)

## More commands

[WinGet.run](https://winget.run) is handy for searching the app catalog, but you can also search right from the command line if you prefer using `winget search <app name>`

![WinGet command line search screenshot](/assets/Posts/2022-02-18-Use-WinGet-to-install-and-update-your-Windows-apps/WinGetCommandLineSearchScreenshot.png)

To see all of the commands check out [the official MS documentation](https://docs.microsoft.com/en-us/windows/package-manager/winget/), or just type `winget` on the command line and you'll get the full menu.

![WinGet menu screenshot](/assets/Posts/2022-02-18-Use-WinGet-to-install-and-update-your-Windows-apps/WinGetMenuScreenshot.png)

You can also use the `-?` flag to get more information about how to use a command, such as:

```shell
winget upgrade -?
```

## I already use Chocolatey / Scoop / etc

If you're already using an alternate Windows package manager like Chocolatey or Scoop and are happy with it, stick with it.
WinGet is still relatively new, so more mature alternatives may provide you with functionality that WinGet doesn't have yet, or have a larger catalog with more obscure apps that you use.
One feature I'm personally looking forward to is [locking an app to a specific version](https://github.com/microsoft/winget-cli/issues/476) so it doesn't get updated when using the `winget update --all` command.

I enjoy that WinGet is already installed by default, has every app I've looked for, and that I can use it to update apps that I've manually installed too.

## Conclusion

We've seen how easy it is to install apps using WinGet.
Not only is it easy to install apps, but it's also easy to update them, even if they weren't installed by WinGet.
The WinGet catalog is huge, so it likely has every app you want on it.
And one of the best parts, it's already pre-installed on Windows 10+, so there's no setup required.

### Aside

Microsoft is currently looking at allowing the Microsoft Store to install Win32 apps as well.
Hopefully once that's complete it will provide a similar experience to WinGet and pull apps from the same catalog.
It would be great since the Microsoft Store apps not only auto-update by themselves, but also roam with your account, so once you log in on a computer it installs your apps with zero effort from you.
Unfortunately that future is not a reality yet, and it's too early to speculate what that user experience will be like, so in the meantime we can use WinGet to make our lives easier.

Happy installing!

> Update: There is one app that I couldn't find on WinGet yet, which is `Paint.Net`.
> There's [a request open](https://forums.getpaint.net/topic/118574-please-add-paintnet-to-the-available-packages-for-windows-package-manager-winget/) for the app developer to considering putting it on WinGet.
