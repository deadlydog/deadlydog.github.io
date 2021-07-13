---
title: "Master the Windows copy-paste clipboard"
permalink: /Master-the-Windows-copy-paste-clipboard/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22T00:00:00-06:00
comments_locked: false
categories:
  - Windows
  - Productivity
tags:
  - Windows
  - Productivity
---

In this post we'll cover:

- What is the Windows clipboard.
- The basics of using the Windows clipboard for copying and pasting ([video](https://www.youtube.com/watch?v=sZsSSVS7gY0)).
- Look at new clipboard features introduced in Windows 10 ([video](https://www.youtube.com/watch?v=Ob2FHVPyXhc)).
- Be more productive by using free 3rd party tools that take the clipboard to the next level ([video](https://www.youtube.com/watch?v=bBvKvJfWw2c)).

I've provided links to videos that cover these topics as well if you prefer to learn that way.

## What is the clipboard

The Windows clipboard is simply a place that you can `copy` information to, and `paste` to get it out again.

You've likely used the clipboard before, but may not have known what it was called.
Any time you copy or cut, you are placing information from an application on the clipboard, and when you paste you're copying that information from the clipboard back out into an application.

You can copy pretty much any type of information into the clipboard, including text, images, and files/directories.

## Windows clipboard basics

The basic clipboard operations are:

- Copy: Copy information from an application onto the clipboard.
- Cut: Copy information from an application onto the clipboard, and also remove that information from the application.
- Paste: Copy information from the clipboard into an application.

You can typically find these operations in an application's context menu by selecting some text/images/files and right-clicking with the mouse.

A much quicker method is to use the keyboard shortcuts.

- Copy: <kbd>Ctrl</kbd> + <kbd>C</kbd>
- Cut: <kbd>Ctrl</kbd> + <kbd>X</kbd>
- Paste: <kbd>Ctrl</kbd> + <kbd>V</kbd>

Sometimes an application may not have these operations in their mouse context menu, or may not even provide a context menu at all, but the keyboard shortcuts work in most cases.

## Windows 10 clipboard enhancements

Before Windows 10, there was no way of seeing what was in the clipboard, if anything, besides to try pasting it.
Also, the clipboard could only hold the last information that was copied; i.e. one item.

Windows 10 introduced a new feature called the Windows Clipboard History.
This feature includes the following benefits:

- Can hold the last 25 items copied. Older items roll off the clipboard and cannot be accessed any more.
- Allows you to visualize the clipboard and pick which item you want to paste.
- Allows you to "Pin" items so they don't roll off the clipboard.
- Allows you to share your clipboard between other devices.

You can access the clipboard settings by hitting the <kbd>Windows Key</kbd> and searching for `Clipboard settings`.

![Windows 10 Clipboard Settings](/assets/Posts/2021-07-07-Master-the-Windows-copy-paste-clipboard/Windows10ClipboardSettingsScreenshot.png)

As the shown in the screenshot, all you need to do to access Clipboard History feature is hit <kbd>Windows Key</kbd> + <kbd>V</kbd>.
If the Clipboard History feature is not enabled, the window will prompt you to turn it on.

Once enabled, just start copying things.
If you want to paste the last thing you copied, then use <kbd>Ctrl</kbd> + <kbd>V</kbd> as usual to paste.
If you want to access something you copied earlier though, use <kbd>Windows Key</kbd> + <kbd>V</kbd> and the Clipboard History window will appear and show you the last items you copied.
From there, choose the item that you want and it will be pasted into your application.

![Windows 10 Clipboard History Screenshot](/assets/Posts/2021-07-07-Master-the-Windows-copy-paste-clipboard/Windows10ClipboardHistoryScreenshot.png)

Nice.

And, it's built right into Windows 10, so there's no need to download or install anything extra; it's just there.

### What's lacking in the Windows 10 Clipboard History

While it's definitely an improvement, the Clipboard History feature is far from perfect.
Here's some downsides:

- Only holds the last 25 copied items, which isn't enough in my opinion.
- All 25 items are cleared every time you restart your computer.
- Cannot search in the Clipboard History.
- Images don't always show up; seems to depend how they are copied or which application they were copied from.
- The Clipboard History window cannot be moved or resized.

Hopefully these limitations will be addressed in later Windows updates, but at the moment they exist.

## Free 3rd party clipboard manager apps that are way better

While the Windows 10 Clipboard History was only introduced in the past couple of years, 3rd party apps have been doing similar things for over 10 years. This means they've had a lot of time to introduce great features that really enhance the clipboard, such as:

- Configuring how many items to save in the clipboard manager.
- Searching for items in the clipboard manager.
- Favourite items for easy access later.
- Pasting items as plain text to remove formatting.
- Customizing additional keyboard shortcuts for quick access to additional functionality.

There's a great comparison of various clipboard manager applications and their features on [the ClipAngel wiki](https://sourceforge.net/p/clip-angel/wiki/Description/).

## Ditto clipboard manager

While there are many clipboard managers out there, my favourite is the [Ditto Clipboard Manager](https://ditto-cp.sourceforge.io), which is free and can be installed from their website or [from the Microsoft store](https://www.microsoft.com/en-us/store/p/ditto-cp/9nblggh3zbjq).
I like Ditto because it provides a minimalistic user interface, while still providing a ton of options so you can configure it just the way you like.

You can open Ditto by using a customizable keyboard shortcut (default is <kbd>Ctrl</kbd> + <kbd>`</kbd>), by double-clicking the tray icon (which looks like double quotes on a blue square ![Ditto Tray Icon Context Menu Image](/assets/Posts/2021-07-07-Master-the-Windows-copy-paste-clipboard/DittoTrayIconImage.png)), or by right-clicking the tray icon and selecting `Show Quick Paste`.

![Ditto Tray Icon Context Menu Screenshot](/assets/Posts/2021-07-07-Master-the-Windows-copy-paste-clipboard/DittoTrayIconContextMenuScreenshot.png)

Here's a screenshot of what my Ditto window currently looks like:

![Ditto Main Window Screenshot](/assets/Posts/2021-07-07-Master-the-Windows-copy-paste-clipboard/DittoMainWindowScreenshot.png)

You can see that it displays previews for both text and images.
If you hover your mouse over an item you'll get a larger preview of that item, as well as some extra information, such as when it was copied to the clipboard.

![Ditto Tooltip Screenshot](/assets/Posts/2021-07-07-Master-the-Windows-copy-paste-clipboard/DittoMainWindowTooltipScreenshot.png)

If you want to see the entire text, or the image in it's original size, you can right-click on the item and choose `View Full Description`, or press <kbd>F3</kbd>.

![Ditto Full Image Preview Screenshot](/assets/Posts/2021-07-07-Master-the-Windows-copy-paste-clipboard/DittoMainWindowFullImagePreviewScreenshot.png)

The window can be moved around and is resizable, so if you want to see more or less items, you can resize the window.

The best feature though is the ability to search for items.
Just open the Ditto window and start typing.

![Ditto Search Screenshot](/assets/Posts/2021-07-07-Master-the-Windows-copy-paste-clipboard/DittoMainWindowSearchScreenshot.png)

I use this feature all the time, typically to find something that I copied earlier that day or a few days ago.
It's even been a life saver at recovering something that I copied months ago.

### Ditto options

Ditto has so many options, I'm not going to even try to list them all here.
I will however show you the ones that I feel are noteworthy, and ones that I change from their default values.
You can access the Options window from the `...` on the bottom-right of the main window, or by right-clicking the tray icon and selecting `Options`.

#### General tab

On the `General` tab, the options of note are:

- Start Ditto on System Startup: Launch Ditto automatically so you don't have to.
- Maximum Number of Saved Copies: The maximum number of items to save in the clipboard manager. Use this to ensure the database doesn't grow too large. I typically increase this quite a bit as I like being able to find items from months ago.
  - You can check the `Database Path` file to see how large the database is. Depending on how large you set this, the database may consume GB of disk space.
- Theme: Ditto supports both light and dark themes.
- Popup Position: Do you want the window to appear where your typing cursor is, where your mouse is, or where you last moved it to.

![Ditto Options General Tab Screenshot](/assets/Posts/2021-07-07-Master-the-Windows-copy-paste-clipboard/DittoOptionsGeneralTabScreenshot.png)

If you click on the `Advanced` button, you'll find a lot more options. One that I typically change is:

- Paste Clip in active window after selection: Default is true, but I prefer this as false so that I have to manually use <kbd>Ctrl</kbd> + <kbd>V</kbd> to paste into an application.

In there you'll also find buttons for running custom scripts whenever you copy or paste an item.
I haven't personally played around with it yet, but it looks interesting and links to a wiki page with examples.

#### Keyboard Shortcuts tab

On the `Keyboard Shortcuts` tab the options of note are:

- Activate Ditto: The keyboard shortcut to open Ditto. The default is <kbd>Ctrl</kbd> + <kbd>`</kbd>, but I've modified mine to be <kbd>Alt</kbd> + <kbd>`</kbd> because I use <kbd>Ctrl</kbd> + <kbd>`</kbd> to open the terminal in Visual Studio and Visual Studio Code.
- Text Only Paste: This is a great one, as it will paste the item with all formatting removed, including font, color, size, hyperlinks, etc. No more copying to Notepad to remove formatting!

![Ditto Options Keyboard Shortcuts Tab Screenshot](/assets/Posts/2021-07-07-Master-the-Windows-copy-paste-clipboard/DittoOptionsKeyboardShortcutsTabScreenshot.png)

#### Quick Paste Keyboard tab

On the `Quick Paste Keyboard` tab you'll find tons of configurable hotkeys.
The ones I want to mention are `Paste Position 1` - `Paste Position 10`, as these allow you to quickly select one of the first 10 items using the keyboard when you're in Ditto.
This can save you from reaching for the mouse and having to click on the item you want to select.

![Ditto Options Quick Paste Keyboard Tab Screenshot](/assets/Posts/2021-07-07-Master-the-Windows-copy-paste-clipboard/DittoOptionsQuickPasteKeyboardTabScreenshot.png)

## ClipAngel clipboard manager

Another great free alternative is [ClipAngel](https://sourceforge.net/projects/clip-angel).






Happy copy-pasting :)
