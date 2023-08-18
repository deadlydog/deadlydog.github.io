---
title: "Update your terminal prompt and font in Windows Terminal, VS Code, and Visual Studio"
permalink: /Update-your-terminal-prompt-and-font-in-Windows-Terminal-and-VS-Code-and-Visual-Studio/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - Editor
  - PowerShell
  - Visual Studio
  - Visual Studio Code
  - Windows Terminal
tags:
  - Editor
  - PowerShell
  - Visual Studio
  - Visual Studio Code
  - Windows Terminal
---

I use [Oh My Posh](https://ohmyposh.dev) to improve my PowerShell terminal's prompt appearance and show additional helpful information.
There are [many themes to choose from](https://ohmyposh.dev/docs/themes), and I have even created [my own theme](https://github.com/deadlydog/Oh-My-Posh.DeadlydogTheme) that you are welcome to use.
Mine looks like this:

![Oh My Posh deadlydog theme screenshot](/assets/Posts/2023-06-29-Update-your-terminal-prompt-and-font-in-Windows-Terminal-and-VS-Code-and-Visual-Studio/deadlydog-theme-screenshot.png)

You can see it displays additional helpful information, like the current directory, the current git branch and status, how long it took the last command to complete, and more.

To use a terminal prompt theme you need to have a font installed that supports all the icons that the theme uses, and then configure your terminal to use that font.

Even if you do not want to use a prompt theme, you may still want to update your terminal font, so let's see how to do that.

## Download and install a font

The first step is to install a font that supports all the icons you want to use.
You can head over to <https://www.nerdfonts.com/font-downloads> and find a font that you like and supports the icons you want.
I personally like the `CaskaydiaCove Nerd Font`.

Once you've downloaded and unzipped the font, you can double-click the appropriate .ttf file to open it in the Windows Font Viewer, and then click the `Install` button to install it.

Here is a screenshot of installing the `CaskaydiaCoveNerdFontMono-Regular.ttf` file font:

![Installing a font](/assets/Posts/2023-06-29-Update-your-terminal-prompt-and-font-in-Windows-Terminal-and-VS-Code-and-Visual-Studio/install-font-screenshot.png

You can to install several fonts if you like so that you have a few to choose from in your applications.

I personally like `Mono` fonts for my code and terminal, as I find them easier to read and better for aligning code.
Mono fonts use the same width for all characters (e.g. the `i` character will take the same amount of space as the `w` character).
I will be configuring my terminal to use the `CaskaydiaCove Nerd Font Mono` font in the examples below.

## Update Windows Terminal to use the new font

To update the Windows Terminal font:

1. Open Windows Terminal.
1. Open the Settings via the tab dropdown menu, or by pressing <kbd>Ctrl</kbd>+<kbd>,</kbd>.
1. (Optional) I recommend setting the `Default terminal application` to Windows Terminal.
   This will make it so that when Windows launches a terminal app, such as `cmd`, `Windows PowerShell`, `PowerShell 7`, or the Visual Studio debugging console, it will launch in Windows Terminal instead of their default (old-style) console.
   ![Setting Windows Terminal as the default terminal application](/assets/Posts/2023-06-29-Update-your-terminal-prompt-and-font-in-Windows-Terminal-and-VS-Code-and-Visual-Studio/set-windows-terminal-as-default-terminal.png)
1. Select the profile that you want to change the font for.
   If you want to change the default font for all profiles, then select the `Defaults` profile.
1. Click into the `Appearance` section.
   ![Select default profile appearance](/assets/Posts/2023-06-29-Update-your-terminal-prompt-and-font-in-Windows-Terminal-and-VS-Code-and-Visual-Studio/access-windows-terminal-defaults-appearance-screenshot.png)
1. Change the `Font face` to the font you want to use.
   ![Select font face](/assets/Posts/2023-06-29-Update-your-terminal-prompt-and-font-in-Windows-Terminal-and-VS-Code-and-Visual-Studio/windows-terminal-set-font-screenshot.png)
1. Save the changes.
1. Repeat for any other profiles you want to change the font for.

Windows terminal should now be using your new font 🙌.

## Update the VS Code terminal to use the new font

To update the VS Code terminal font:

1. Open Visual Studio Code.
1. Navigate to `File` -> `Preferences` -> `Settings`, or press <kbd>Ctrl</kbd>+<kbd>,</kbd> to open the Settings window.
1. Search for `font family`.
1. You will likely notice the `Editor: Font Family` setting near the top.
   You can change this if you like, but it will change the code editor font, not the terminal font.
   ![Change VS Code editor font family](/assets/Posts/2023-06-29-Update-your-terminal-prompt-and-font-in-Windows-Terminal-and-VS-Code-and-Visual-Studio/vs-code-change-editor-font-screenshot.png)
1. Scroll down to the `Terminal > Integrated: Font Family` setting, and update it to the font you want to use.
   ![Change VS Code terminal font family](/assets/Posts/2023-06-29-Update-your-terminal-prompt-and-font-in-Windows-Terminal-and-VS-Code-and-Visual-Studio/vs-code-change-terminal-font-screenshot.png)
1. Save the changes.

The VS Code terminal should now be using your new font 🙌.

## Update the Visual Studio terminal to use the new font

To update the Visual Studio terminal font:

1. Open Visual Studio.
1. Navigate to `Tools` -> `Options` to open the Options window.
1. Navigate to the `Environment` -> `Fonts and Colors` section.
1. By default it will be on the `Text Editor` setting.
    You can change the font for these items if you like, but it will change the code editor font, not the terminal font.
1. Change the setting to `Terminal`.
1. Update the `Font` setting to the font you want to use.
   ![Change Visual Studio terminal font](/assets/Posts/2023-06-29-Update-your-terminal-prompt-and-font-in-Windows-Terminal-and-VS-Code-and-Visual-Studio/set-visual-studio-terminal-font-screenshot.png)
1. Click the OK button to save the changes.

The Visual Studio terminal should now be using your new font 🙌.

## Conclusion

You now know about Oh My Posh, terminal prompt themes, and how to update the fonts in Windows Terminal, VS Code, and Visual Studio.
I hope you found this post helpful.

Happy coding!
