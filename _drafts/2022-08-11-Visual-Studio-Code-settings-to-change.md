---
title: "Change these VS Code default settings to make it even more awesome"
permalink: /Visual-Studio-Code-settings-to-change/
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

Visual Studio Code is an awesome editor.
Make it even more awesome by changing these settings from their default values.

If there are other settings you think belong on this list, please let me know in the comments.

<!-- Related: You may also be interested in this post about [Visual Studio Code extensions to install](/Visual-Studio-Code-extensions-to-install/). -->

## Show hidden characters

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

![Result of enabling the Show Invisible Characters setting](/assets/Posts/2022-08-15-Visual-Studio-Code-settings-to-change/show-invisible-characters-settings-result.png)

Note: There are separate settings for the `Markdown` and `PlainText` languages, which are `false` by default, so be sure to enable all of them.

![There are multiple language settings for showing invisible characters](/assets/posts/2022-08-15-Visual-Studio-Code-settings-to-change/show-invisible-characters-multiple-settings.png)

## Enable Sticky Scroll

As you scroll down through nested classes/functions/structures, the names stick to the top of the editor making it easy to see the nested scope you're currently working in.
Works in many different languages, such as JavaScript, TypeScript, C#, JSON, YAML, etc.

GUI setting: `Editor › Hover: Sticky` to `true`.

JSON setting:

```json
"editor.experimental.stickyScroll.enabled": true,
```

Result:

![Result of using sticky scroll in YAML](/assets/Posts/2022-08-15-Visual-Studio-Code-settings-to-change/sticky-scroll-setting-result.png)

You can also quickly toggle this setting on/off from the `View` menu.

## Trim trailing whitespace when saving file

Automatically remove trailing whitespace at the end of any lines when the file is saved.

GUI setting: `Files: Trim Trailing Whitespace` to `true`.

JSON setting:

```json
"files.trimTrailingWhitespace": true,
```

Note: When working in source controlled files, this can sometimes make the file diff very large if the diff viewer is not configured to ignore whitespace changes.

## Default language for new files

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

## Default language for code-runner

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

## Save files automatically

If you're tired of constantly hitting <kbd>Ctrl</kbd>+<kbd>S</kbd> to save your files, you can enable auto-save.

I personally like having it save files automatically after 1 second, but you can also choose to have it save when the editor or VS Code lose focus.
Choose whatever works best for you.

GUI setting: `Files: Auto Save` to `afterDelay`, and `Files: Auto Save Delay` to `1000`.

JSON setting:

```json
"files.autoSave": "afterDelay",
"files.autoSaveDelay": 1000,
```

## Change the default integrated terminal

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
