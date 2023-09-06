---
title: "PowerShell intellisense on the command line"
permalink: /PowerShell-intellisense-on-the-command-line/
date: 2020-11-07T00:00:00-06:00
last_modified_at: 2023-09-06T00:00:00-06:00
comments_locked: false
categories:
  - PowerShell
  - Productivity
  - Shortcuts
tags:
  - PowerShell
  - Productivity
  - Shortcuts
---

If you use PowerShell then this tip is for you; if you don't already know it, it's game changer!

You may already know that you can use <kbd>Tab</kbd> to autocomplete cmdlet names and parameters in the console.
If there's more than one possible match, you can continue hitting <kbd>Tab</kbd> to cycle through them, and <kbd>Shift</kbd> + <kbd>Tab</kbd> to cycle in reverse order.

![Screencast showing tab completion](/assets/Posts/2020-11-07-PowerShell-intellisense-on-the-command-line/PowerShellTabCompletion.gif)

Things get even better by using [PSReadLine](https://github.com/PowerShell/PSReadLine).
Not only can you autocomplete cmdlets and parameters, but you can see all of the options available in a nice menu that you can navigate using the arrow keys.
You activate this menu by using <kbd>Ctrl</kbd> + <kbd>Space</kbd>.

![Screencast showing PSReadLine menu completion](/assets/Posts/2020-11-07-PowerShell-intellisense-on-the-command-line/PowerShellMenuComplete.gif)

Notice that when browsing the cmdlet parameters it also displays the parameter's type.
e.g. It shows that `-Path` is a `[string[]]`, and `-Force` is a `[switch]`.
Very helpful!

This works for cmdlets, parameters, variables, methods, and more.
Basically anything that you can tab complete, you can also see in the menu.

For example, type `Get-Pro` then <kbd>Ctrl</kbd> + <kbd>Space</kbd> to see all cmdlets that start with `Get-Pro`.
If you have the variables `$variable1` and `$variable2`, then type `$var` and <kbd>Ctrl</kbd> + <kbd>Space</kbd> to see both variables in the menu.
Type `[string]::` and <kbd>Ctrl</kbd> + <kbd>Space</kbd> to see all the methods available on the `[string]` type.
Anytime you want autocomplete, just hit <kbd>Ctrl</kbd> + <kbd>Space</kbd> and you'll see all the options available.

If you're on a Mac, then apparently the <kbd>Ctrl</kbd> + <kbd>Space</kbd> trigger won't work; I don't have a Mac, so I can't confirm.
However, do not fret.
You can achieve the same functionality by adding this PowerShell code to [your PowerShell profile](https://docs.microsoft.com/en-us/powershell/module/microsoft.powershell.core/about/about_profiles):

```powershell
Set-PSReadLineKeyHandler -Chord Shift+Spacebar -Function MenuComplete
```

Now you should be able to activate the menu by using <kbd>Shift</kbd> + <kbd>Spacebar</kbd>.
Of course you can choose a different keyboard combination if you like, including overriding the default <kbd>Tab</kbd> behaviour with this, and this trick also works on Windows if you don't like the default <kbd>Ctrl</kbd> + <kbd>Space</kbd> trigger.

A shout-out to Steve Lee of the PowerShell team for showing me the Menu Complete functionality [in this tweet](https://twitter.com/Steve_MSFT/status/1324192341310124033).

I hope you've found this information helpful.

Happy command lining :)
