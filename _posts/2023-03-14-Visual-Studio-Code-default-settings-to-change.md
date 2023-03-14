---
title: "Change these VS Code default settings to make it even more awesome"
permalink: /Visual-Studio-Code-default-settings-to-change/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: true
categories:
  - Visual Studio Code
  - Productivity
tags:
  - Visual Studio Code
  - VS Code
  - Productivity
---

Visual Studio Code is an amazing editor that has a ton of settings, with more being introduced all the time.
If you don't keep up with all the release notes, it's easy to miss some of the new settings.
Looking through the list of settings can be overwhelming and time consuming, and it's hard to know which ones are worth changing.

This post is a collection of settings that I think are worth changing from their default values.

> I understand that most of these settings are personal preference, and you may not agree with all of my suggestions.
> I am simply listing these to make you aware of them.
> I encourage you to play around with them and see what works best for you.

I'll note that some of the settings below are mentioned even though they are on by default.
This is done for features that were not originally turned on by default when they were introduced, and you may still have them turned off in VS Code due to Settings Sync carrying forward the original setting value.

Speaking of Settings Sync, if you do not have it enabled, you should consider doing so.
It allows you to sync your settings across multiple machines, and can also sync extensions, keyboard shortcuts, user snippets and tasks, UI state, and profiles.
It is great for when you wipe your computer, or migrate to a new machine, as it brings all your customizations with you.
It used to require an extension, but is now built into VS Code.
Read more about it in [the official docs](https://code.visualstudio.com/docs/editor/settings-sync).

Alright, enough preamble, let's see the settings!

If there are other settings you think belong on this list, please let me know in the comments.

<!-- Related: You may also be interested in this post about [Visual Studio Code extensions to install](/Visual-Studio-Code-extensions-to-install/). -->

## Editor UI settings

### Show hidden characters

Make hidden characters easily visible in the editor.
This is on by default for all languages, except for Plain Text and Markdown, so we have to turn it on there explicitly.

GUI setting: `Editor › Unicode Highlight: Invisible Characters` to `true`.

JSON setting:

```json
"[markdown]": {
    "editor.unicodeHighlight.invisibleCharacters": true
},
"[plaintext]": {
    "editor.unicodeHighlight.invisibleCharacters": true
},
```

Result:

![Result of enabling the Show Invisible Characters setting](/assets/Posts/2023-03-14-Visual-Studio-Code-default-settings-to-change/show-invisible-characters-settings-result.png)

Note: There are separate settings for the `Markdown` and `PlainText` languages, which are `false` by default, so be sure to enable all of them.

![There are multiple language settings for showing invisible characters](/assets/posts/2023-03-14-Visual-Studio-Code-default-settings-to-change/show-invisible-characters-multiple-settings.png)

### Render whitespace

By default VS Code only shows a glyph for space and tab characters when you highlight them.
I prefer seeing the glyph any time there is potentially unexpected whitespace, which is typically when there is more than one whitespace character, and is what the `boundary` setting value does.
This means you will also see glyphs for indentation, but I find it helpful to see the indentation level at a glance (Python/YAML anyone 😏), and to ensure I'm not mixing tabs and spaces (because I'm weird like that ok! 😜).

GUI setting: `Editor › Render Whitespace` to `boundary`.

JSON setting:

```json
"editor.renderWhitespace": "boundary",
```

Result:

![Result of using render whitespace with shown configuration](/assets/Posts/2023-03-14-Visual-Studio-Code-default-settings-to-change/render-whitespace-setting-result.png)

### Sticky Scroll

As you scroll down through nested classes/functions/structures, the names stick to the top of the editor making it easy to see the nested scope you're currently working in.
Works in many different languages, such as JavaScript, TypeScript, C#, JSON, YAML, Markdown, etc.

GUI setting: `Editor › Sticky Scroll: Enabled` to `true`.

JSON setting:

```json
"editor.stickyScroll.enabled": true,
```

Result:

![Result of using sticky scroll in YAML](/assets/Posts/2023-03-14-Visual-Studio-Code-default-settings-to-change/sticky-scroll-setting-result.png)

You can also quickly toggle this setting on/off from the `View` menu.

### Breadcrumbs navigation

Displays a breadcrumbs navigation bar at the top of the editor for the current tab.

This setting is now on by default, but it might be turned off if you've been using VS Code with Settings Sync for a while.

GUI setting: `Breadcrumbs: Enabled` to `true`.

JSON setting:

```json
"breadcrumbs.enabled": true,
```

Result:

![Result of using breadcrumbs navigation](/assets/Posts/2023-03-14-Visual-Studio-Code-default-settings-to-change/breadcrumbs-navigation-setting-result.png)

### Minimap for vertical scrolling

The minimap is a small preview of the entire file that is displayed on the right side of the editor, replacing the traditional vertical scrollbar.

GUI setting: `Editor › Minimap: Enabled` to `true`.

JSON setting:

```json
"editor.minimap.enabled": true
```

In addition to just enabling the minimap, you can also customize it with many different settings.
If you search for `minimap` in the settings, you will see the many different settings that you can configure.
The minimap configuration is a very personal preference, so I recommend trying out different settings to see what works best for you.

Below are the non-default minimap JSON settings that I use:

```json
"editor.minimap.showSlider": "always",
"editor.minimap.maxColumn": 100,
"editor.minimap.renderCharacters": false,
"editor.minimap.size": "fill",
```

Result:

![Result of using the minimap for vertical scrolling with shown configuration](/assets/Posts/2023-03-14-Visual-Studio-Code-default-settings-to-change/minimap-setting-result.png)

### Cursor blinking

You can control if the caret (typing cursor) blinks or not, as well as the animation it uses for the blink.
This is just a subtle UI tweak that you might not even notice, but I like it.
[This other blog](https://dev.to/chris__sev/animating-your-vs-code-cursor-w-cursor-blinking-1p30) shows gifs of the different animations that you can choose from.
I personally like the `expand` animation, but you can choose whatever you prefer.

GUI setting: `Editor: Cursor Blinking` to `expand`.

JSON setting:

```json
"editor.cursorBlinking": "expand",
```

### Smooth scrolling

You can enable smooth scrolling, which adds a slight animation to vertically scrolling.
I find this makes scrolling less jarring and easier to differentiate which direction you are scrolling.

GUI setting: `Editor: Smooth Scrolling` to `true`.

JSON setting:

```json
"editor.smoothScrolling": true,
```

### Smooth caret animation

This is similar to the [Smooth scrolling setting](#smooth-scrolling), only for the caret (the typing cursor).
This adds a slight animation to the caret when it moves, making it easier to follow, rather than it just jumping to the new location.

GUI setting: `Editor: Cursor Smooth Caret Animation` to `true`.

JSON setting:

```json
"editor.cursorSmoothCaretAnimation": "on",
```

### Pinned tab size

You can set the tab size for pinned tabs to be smaller if you like.
I find if I've pinned a tab, I often know what it is and don't need to see the full filename in the tab.
You can save some horizontal space by shrinking the tab size for pinned tabs.

You will still see the full filename in the tooltip when you hover the mouse over the tab, and in the [breadcrumbs navigation bar](#breadcrumbs-navigation) when you have the file selected.

GUI setting: `Workbench › Editor: Pinned Tab Sizing` to `shrink`.

JSON setting:

```json
"workbench.editor.pinnedTabSizing": "shrink"
```

Result:

![Result of using pinned tab sizing with shown configuration](/assets/Posts/2023-03-14-Visual-Studio-Code-default-settings-to-change/pinned-tab-size-setting-result.png)

### Editor decoration colors

In addition to using glyphs in the tab name to indicate things like the file has been modified, VS Code will also color the tab text.

This setting is now on by default, but it might be turned off if you've been using VS Code with Settings Sync for a while.

GUI setting: `Workbench › Editor › Decorations: Colors` to `true`.

JSON setting:

```json
"workbench.editor.decorations.colors": true
```

### Wrap tabs

When you have many tabs open you can have them wrap and create another row, rather than having a horizontal scrollbar appear and needing to scroll the tabs into view.
This uses more vertical space, but you can see all of your open tabs at once.
This will create multiple rows of tabs if needed.

GUI setting: `Workbench › Editor: Wrap Tabs` to `true`.

JSON setting:

```json
"workbench.editor.wrapTabs": true,
```

Result:

![Screenshot of how many tabs look with wrap tabs enabled](/assets/Posts/2023-03-14-Visual-Studio-Code-default-settings-to-change/wrap-tabs-setting-result.png)

### Bracket pair colorization

This setting will colorize matching brackets, which can make it easier to see which opening bracket matches which closing bracket.

There used to be several extensions that provided this functionality, but now it is built into VS Code.

This setting is now on by default, but it might be turned off if you've been using VS Code with Settings Sync for a while.

GUI setting: `Editor › Bracket Pair Colorization: Enabled` to `true`.

JSON setting:

```json
"editor.bracketPairColorization.enabled": true,
```

Result (notice how it's easy to see which opening bracket matches which closing bracket):

![Screenshot of bracket pair colorization in action](/assets/Posts/2023-03-14-Visual-Studio-Code-default-settings-to-change/bracket-pair-colorization-setting-result.png)

### Bracket pair guides

This setting will add a vertical or horizontal line to the editor to help you see which pair of brackets you are currently modifying code for.

GUI setting: `Editor › Guides: Bracket Pairs` to `active`.

JSON setting:

```json
"editor.guides.bracketPairs": "active",
```

Result:

![Gif of bracket pair guides in action](/assets/Posts/2023-03-14-Visual-Studio-Code-default-settings-to-change/bracket-pair-guides-setting-result.gif)

### Editor font

You can change the font used in the editor.
This is a very personal preference, and may require you to install the font on your machine.

I personally like the `CaskaydiaCove Nerd Font` font.
Another popular one is `FiraCode`.
Both of these fonts support ligatures, and can be downloaded for free from [Nerd Fonts](https://www.nerdfonts.com/font-downloads).

You can specify multiple fonts, and VS Code will use the first one that is available on your machine.

GUI setting: `Editor: Font Family` to `'CaskaydiaCove Nerd Font',Consolas, 'Courier New', monospace`.

JSON setting:

```json
"editor.fontFamily": "'CaskaydiaCove Nerd Font',Consolas, 'Courier New', monospace",
```

### Editor font ligatures

If your editor font supports it, you can enable font ligatures.
Font ligatures are a way to combine multiple characters into a single glyph, such as `==` becoming `≡`, or `->` becoming `→`.

I personally do not like font ligatures, but I know many people do.

GUI setting: `Editor: Font Ligatures`, which must be edited in the settings.json file.

JSON setting:

```json
"editor.fontLigatures": true,
```

## Editor files and formatting

### Trim trailing whitespace when saving file

Automatically remove trailing whitespace at the end of any lines when the file is saved.

GUI setting: `Files: Trim Trailing Whitespace` to `true`.

JSON setting:

```json
"files.trimTrailingWhitespace": true,
```

Note: When working in source controlled files, this can sometimes make the file diff very large if the diff viewer is not configured to ignore whitespace changes.

### Insert final newline

VS Code can automatically add a final newline to the end of the file when you save it.
This is especially helpful if you are working with text files that will be accessed on unix, as [unix requires a final newline to be present on text files](https://unix.stackexchange.com/a/18789/351983).

GUI setting: `Files: Insert Final Newline` to `true`.

JSON setting:

```json
"files.insertFinalNewline": true
```

### Trim final newlines

While we typically want a final newline, having more than one final newline looks sloppy.
This setting will automatically trim any final newlines from the end of the file when you save it.

GUI setting: `Files: Trim Final Newlines` to `true`.

JSON setting:

```json
"files.trimFinalNewlines": true,
```

### Format on paste

When you paste text into the editor, VS Code can automatically format the text for you according to any linters or formatting rules you have configured.
This is especially useful when pasting code snippets from the internet.

GUI setting: `Editor › Format On Paste` to `true`.

JSON setting:

```json
"editor.formatOnPaste": true,
```

### Format on save and format save mode

VS Code can automatically format a file when you save it, applying linting rules and other formatting rules.

GUI setting: `Format On Save` to `true`.

JSON setting:

```json
"editor.formatOnSave": true
```

Along with this setting, you can specify the format mode.
By default it will format the entire file.
I prefer to only format the lines that I have changed, which is what the `modifications` setting does.
This is especially helpful when working with files in source control and you do not want your pull request diff to include a ton of formatting changes, which may hide the actual changes you made.

GUI setting: `Format On Save Mode` to `modifications`.

JSON setting:

```json
"editor.formatOnSaveMode": "modifications"
```

### Default language for new files

When you create a new tab in VS Code, but have not saved it yet, VS Code does not know the file type and what language it is and thus can not provide services like syntax highlighting, intellisense, etc.
You can provide a default language for new tabs that are not saved yet to get these services.

I work a lot in PowerShell, so I set the default language to `powershell`.
You may want to set it to something like `javascript`, `typescript`, `python`, `csharp`, `markdown`, etc.
The supported language identifiers are [listed here](https://code.visualstudio.com/docs/languages/identifiers#_known-language-identifiers).

GUI setting: `Files: Default Language` to `powershell`.

JSON setting:

```json
"files.defaultLanguage": "powershell",
```

### Default language for code-runner

This setting will be used when you select some text, right-click, and select `Run Code` from the context menu.

This is handy when you open a new tab and have not yet saved the file (so VS Code does not know the file type and language), but want to run a portion of the code.
It is also useful when you are working in a markdown file and want to run a code snippet.

I work a lot in PowerShell, so I set the default language to `powershell`.
You may want to set it to something like `javascript`, `typescript`, `python`, `csharp`, `markdown`, etc.
The supported language identifiers are [listed here](https://code.visualstudio.com/docs/languages/identifiers#_known-language-identifiers).

GUI setting: `Code-running: Default Language` to `powershell`.

JSON setting:

```json
"code-runner.defaultLanguage": "powershell",
```

### Save files automatically

If you're tired of constantly hitting <kbd>Ctrl</kbd>+<kbd>S</kbd> to save your files, you can enable auto-save.

I personally like having it save files automatically after 1 second, but you can also choose to have it save when the editor or VS Code lose focus.
Choose whatever works best for you.

GUI setting: `Files: Auto Save` to `afterDelay`, and `Files: Auto Save Delay` to `1000`.

JSON setting:

```json
"files.autoSave": "afterDelay",
"files.autoSaveDelay": 1000,
```

### Automatically use find-in-selection if multiple lines are selected

If you select multiple lines of text, VS Code can automatically toggle on the `Find in Selection` option of the `Find` command.

GUI setting: `Editor › Find: Auto Find In Selection` to `multiline`.

JSON setting:

```json
"editor.find.autoFindInSelection": "multiline"
```

## Terminal settings

### Change the default integrated terminal, and add other terminals

If you use the integrated terminal, you can configure the default terminal to be something other than `cmd.exe` on Windows or `bash` on Linux/macOS.
I prefer PowerShell, so I set it as my default.

I use Windows, so I edit the `Windows` profile settings.
Use the `Linux` and `Osx` profiles accordingly for those platforms.

GUI setting: `Terminal › Integrated › Default Profile: Windows` to `PowerShell`.

JSON setting:

```json
"terminal.integrated.defaultProfile.windows": "PowerShell"
```

If the terminal that you want to use is not in the list, you can add it to your list of terminals that are available using the setting below.

GUI setting: `Terminal › Integrated › Profiles: Windows`, which must be edited in the settings.json file.

JSON setting (example):

```json
"terminal.integrated.profiles.windows": {
  "PowerShell": {
    "source": "PowerShell",
    "icon": "terminal-powershell"
  },
  "Command Prompt": {
    "path": [
      "${env:windir}\\Sysnative\\cmd.exe",
      "${env:windir}\\System32\\cmd.exe"
    ],
    "args": [],
    "icon": "terminal-cmd"
  },
  "Git Bash": {
    "source": "Git Bash"
  }
}
```

## Git settings

### Git merge editor

When you are resolving merge conflicts, VS Code will open the visual merge editor to help you resolve the conflicting lines.

GUI setting: `Git: Merge Editor` to `true`.

JSON setting:

```json
"git.mergeEditor": true,
```

## Markdown settings

### Markdown validation

VS Code can validate your markdown files for some common errors, such as broken links to pages in your repo.

GUI setting: `Markdown › Validation: Enabled` to `true`.

JSON setting:

```json
"markdown.validate.enabled": true,
```

<!-- Also do terminal settings -->

<!-- Maybe group the settings by category. e.g. Terminal, workbench, editor, etc. -->

## Conclusion

I hope you've found these default setting adjustments useful.

If you have suggestions for other default settings that I missed, please let me know in the comments below.

I plan to update this post as I find more useful settings to tweak, and as new ones are introduced, so be sure to check back from time to time.

Happy coding!
