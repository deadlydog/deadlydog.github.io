---
title: "Set your default AutoHotkey script editor"
permalink: /Set-default-AutoHotkey-script-editor/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
categories:
  - AutoHotkey
  - Productivity
  - Editor
tags:
  - AutoHotkey
  - Productivity
  - Editor
  - AHK
---

The Windows context menu provides an `Edit Script` option for AutoHotkey `.ahk` files.
Unfortunately, it defaults to opening in Notepad, which is not a great editor for AutoHotkey scripts.

![Windows File Explorer context menu for .ahk files Edit Script option](/assets/Posts/2023-03-02-Set-default-AutoHotkey-script-editor/windows-file-explorer-context-menu-for-ahk-files-to-edit-script.png)

The same is true when using the `Edit This Script` option from the context menu of a running scripts tray icon.

![A running AutoHotkey scripts tray icon context menu](/assets/Posts/2023-03-02-Set-default-AutoHotkey-script-editor/autohotkey-tray-icon-context-menu-edit-script.png)

Fortunately there is a simple way to change the default editor for AutoHotkey scripts.

## Set the default editor for AutoHotkey scripts in the Registry

I came across [this forum post](https://www.autohotkey.com/board/topic/897-how-to-change-autohotkey-default-editor/), which led me to the solution of simply modifying the following registry key:

```text
HKEY_CLASSES_ROOT\AutoHotkeyScript\Shell\Edit\Command
```

To access the Registry Editor, press `Win`+`R` to access the Run dialog, and then type `regedit` and hit OK.
From there just navigate to the key above.

You will want to modify the `(Default)` entry.
By default it will have a value like `notepad.exe "%1"`.

You will want to replace the path to the Notepad executable with the path to your preferred editor.

I use Visual Studio Code, so this is what my registry entry looks like:

![Setting the registry entry for Visual Studio Code](/assets/Posts/2023-03-02-Set-default-AutoHotkey-script-editor/autohotkey-default-script-editor-registry-key-to-edit.png)

## Registry values to use for popular AutoHotkey script editors

[There are many editors that can be used to edit AutoHotkey scripts](https://www.the-automator.com/best-autohotkey-editors-ides/).

Below are some popular editors and their registry value to use.

When possible, I also included a .reg file that you can download and run to set the registry entry for you, so that you don't need to make manual edits in the Registry Editor as shown above.

---

[Notepad++](https://notepad-plus-plus.org/):

```text
"C:\Program Files\Notepad++\notepad++.exe" "%1"
```

[Download Notepad++ .reg file](/assets/Posts/2023-03-02-Set-default-AutoHotkey-script-editor/NotepadPlusPlusAsDefaultAhkEditor.reg)

---

[Visual Studio Code](https://code.visualstudio.com/):

```text
"C:\Users\[YOUR USERNAME GOES HERE]\AppData\Local\Programs\Microsoft VS Code\Code.exe" "%1"
```

NOTE: You need to replace `[YOUR USERNAME GOES HERE]` with your actual username.

There is no .reg file to download since the username in the executable file path will be unique to you.

---

[SciTE4AutoHotkey](https://www.autohotkey.com/scite4ahk/):

```text
"C:\Program Files\AutoHotkey\SciTE\SciTE.exe" "%1"
```

NOTE: SciTE4AutoHotkey has an option in the installer to set itself as the default editor for AutoHotkey scripts, so that you do not need to edit the registry manually.
Even if you already have SciTE4AutoHotkey installed, you can run the installer again to set it as the default editor.

[Download SciTE4AutoHotkey .reg file](/assets/Posts/2023-03-02-Set-default-AutoHotkey-script-editor/SciTE4AutoHotkeyAsDefaultAhkEditor.reg)

## Locating the executable file path

If you use a different editor and are not sure what it's executable path is, you can typically find it by:

1. Hit the Windows key to open the Start Menu.
1. Type the name of the editor you want to use.
1. Right-click on the editor and choose `Open file location`.
   ![Open the application file location from the Windows Start Menu](/assets/Posts/2023-03-02-Set-default-AutoHotkey-script-editor/open-application-file-location-from-windows-start-menu.png)
1. That should open up File Explorer.
   1. If File Explorer shows the .exe file, you can use its path.
   1. If File Explorer shows a shortcut to the application, right-click on the shortcut and choose `Properties`.
      1. In the shortcut properties window, click the `Open File Location` button to open a new File Explorer window containing the exe file.
          ![Open the shortcut file location from the shortcut properties window](/assets/Posts/2023-03-02-Set-default-AutoHotkey-script-editor/open-file-location-from-shortcut-file-properties.png)
1. Copy the path of the executable file and use that as the value for the registry key.
   ![Copy the path of the executable file from File Explorer](/assets/Posts/2023-03-02-Set-default-AutoHotkey-script-editor/copy-path-to-executable-file.png)

In this example here, the path to the executable is "C:\Users\Dan.Schroeder\AppData\Local\Programs\Microsoft VS Code\Code.exe".

## Other ways to open a script in the default editor

You can also use the following AutoHotkey code to open an ahk script in the default editor:

```ahk
filePath = C:\Path\To\AutoHotkey\Script.ahk
Run, edit %filePath%,,UseErrorLevel
if (%ErrorLevel% = ERROR)
    Run, "notepad" "%filePath%"
```

In the code above, if an error occurs trying to open the ahk script in the default editor, it will open in Notepad instead.

## Conclusion

Now when you right-click on an AutoHotkey script file and choose `Edit Script`, it will open in your preferred editor, making your life just a little more convenient.

Hopefully you've found this helpful.

Happy scripting!
