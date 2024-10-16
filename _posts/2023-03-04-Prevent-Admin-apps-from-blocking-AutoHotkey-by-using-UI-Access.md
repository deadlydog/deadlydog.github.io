---
title: "Prevent Admin apps from blocking AutoHotkey by always using UI Access"
permalink: /Prevent-Admin-apps-from-blocking-AutoHotkey-by-using-UI-Access/
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

When an app running as Admin has focus, you may not be able to trigger your AutoHotkey (AHK) scripts, hotkeys, and hotstrings.
This is a security feature of Windows where non-elevated apps cannot interact with elevated apps.

This can be frustrating and I have [blogged about other ways](/get-autohotkey-to-interact-with-admin-windows-without-running-ahk-script-as-admin/) to get around this issue in the past, but the method described in this post is now my preferred approach.

[The AutoHotkey documentation](https://www.autohotkey.com/docs/v1/FAQ.htm#uac) explains the issue in more detail, as well as common ways to work around it.
The most common workaround is to [run your AHK script with UI Access](https://www.autohotkey.com/docs/v1/Program.htm#Installer_uiAccess).
By default, the AutoHotkey installer will add a `Run with UI Access` context menu item to AHK scripts.
If you use this context menu item to run your script (by right-clicking the AHK script and choosing `Run with UI Access`), it will run with UI Access and you will be able to trigger your AHK scripts when an Admin app has focus.

![Run AHK script with UI Access](/assets/Posts/2023-03-04-Prevent-Admin-apps-from-blocking-AutoHotkey-by-using-UI-Access/run-ahk-script-with-ui-access.png)

This solution addresses the issue, but it is not ideal for a couple reasons:

1. You need to remember to launch your scripts in this special way using the context-menu.
   I typically just want to double click a script, or use the Enter key to run it.
1. It makes launching AHK scripts from the command line and other applications more difficult and less portable, since you need to use the full path of the `AutoHotkeyU64_UIA.exe` executable to launch your AHK scripts, rather than simply letting Windows run it using the default application.
   e.g. Using the `Run` command in AutoHotkey, `Start-Process` in PowerShell, `start` in CMD, etc.

## Always use UI Access when running AHK scripts

To work around this inconvenience, we can simply change the default executable that is used to run AHK scripts so that they always run with UI Access.
The executable file path we want to update is stored in the following registry key:

```text
HKEY_CLASSES_ROOT\AutoHotkeyScript\Shell\Open\Command
```

The default registry value typically looks like this:

![Default AHK open command registry value](/assets/Posts/2023-03-04-Prevent-Admin-apps-from-blocking-AutoHotkey-by-using-UI-Access/default-autohotkey-open-command-registry-value.png)

__NOTE:__ Be aware that updating or reinstalling AutoHotkey will restore the registry value to its default value, so you will need to perform the action below again after updating AutoHotkey.

### Automatically update the registry value

You can use the following AutoHotkey script to automatically update the registry value:

```csharp
; Ensure the script is running as Admin, as it must be admin to modify the registry.
full_command_line := DllCall("GetCommandLine", "str")
if not (A_IsAdmin or RegExMatch(full_command_line, " /restart(?!\S)"))
{
  try
  {
    if A_IsCompiled
      Run *RunAs "%A_ScriptFullPath%" /restart
    else
      Run *RunAs "%A_AhkPath%" /restart "%A_ScriptFullPath%"
  }
  ExitApp
}

; Use the UI Access executable as the default executable to run AHK scripts.
; This copies the command value used to run AHK scripts with UI Access to the default Open command value.
RegRead, UiAccessCommandKeyValue, HKEY_CLASSES_ROOT\AutoHotkeyScript\Shell\uiAccess\Command
RegWrite, REG_SZ, HKEY_CLASSES_ROOT\AutoHotkeyScript\Shell\Open\Command,, %UiAccessCommandKeyValue%
```

You can [download the script here](/assets/Posts/2023-03-04-Prevent-Admin-apps-from-blocking-AutoHotkey-by-using-UI-Access/Always-start-AutoHotkey-with-UI-Access.ahk).

Simply run the AHK script and it will update the registry value for you.

You may want to keep that script handy somewhere on your hard drive, as you will need to run it again if you ever update AutoHotkey.

### Manually update the registry value

If you prefer to manually modify the registry instead of using the script above, do the following to update the default executable file path in the registry:

1. Press `Windows Key`+`R` to access the Run dialog.
1. Type `regedit` and hit OK to open the Registry Editor.
1. Navigate to `HKEY_CLASSES_ROOT\AutoHotkeyScript\Shell\uiAccess\Command`.
1. Double-click the `(Default)` value to edit it, copy its value data, then click Cancel.

   ![Copy the UI Access registry value data](/assets/Posts/2023-03-04-Prevent-Admin-apps-from-blocking-AutoHotkey-by-using-UI-Access/default-autohotkey-uiaccess-command-registry-value.png)

1. Navigate to `HKEY_CLASSES_ROOT\AutoHotkeyScript\Shell\Open\Command`.
1. Double-click the `(Default)` value to edit it, paste the value data you copied from the `uiAccess` key, then click OK.

   ![Modify the Open registry value data](/assets/Posts/2023-03-04-Prevent-Admin-apps-from-blocking-AutoHotkey-by-using-UI-Access/updated-autohotkey-open-command-registry-value.png)

Now all of your AHK scripts will run with UI Access by default, and you won't have to worry about launching them in a special way.

## Caveats to running AutoHotkey scripts with UI Access

[The AutoHotkey documentation](https://www.autohotkey.com/docs/v1/Program.htm#Installer_uiAccess) points out the following limitations of running AutoHotkey scripts with UI Access:

> - UIA is only effective if the file is in a trusted location; i.e. a Program Files sub-directory.
> - UIA.exe files created on one computer cannot run on other computers without first installing the digital certificate which was used to sign them.
> - UIA.exe files cannot be started via CreateProcess due to security restrictions. ShellExecute can be used instead. Run tries both.
> - UIA.exe files cannot be modified, as it would invalidate the file's digital signature.
> - Because UIA programs run at a different "integrity level" than other programs, they can only access objects registered by other UIA programs. For example, ComObjActive("Word.Application") will fail because Word is not marked for UI Access.
> - The script's own windows can't be automated by non-UIA programs/scripts for security reasons.
> - Running a non-UIA script which uses a mouse hook (even as simple as #InstallMouseHook) may prevent all mouse hotkeys from working when the mouse is pointing at a window owned by a UIA script, even hotkeys implemented by the UIA script itself. A workaround is to ensure UIA scripts are loaded last.
> - UIA prevents the Gui +Parent option from working on an existing window if the new parent is always-on-top and the child window is not.

I have not run into any issues, but if one of these limitations affects you then you may not want to always run your AHK scripts with UI Access.

To return AutoHotkey to its original behavior, reset the registry key back to its original value, or simply reinstall/repair AutoHotkey using [the installer](https://www.autohotkey.com).

## Conclusion

I was getting frustrated that I was not able to use [AHK Command Picker](https://github.com/deadlydog/AHKCommandPicker) when a windows running as Admin was in focus.

> Shameless Plug: [AHK Command Picker](https://github.com/deadlydog/AHKCommandPicker) is my free open-source project that allows you to use a GUI picker instead of having to remember a ton of keyboard shortcuts for your AutoHotkey scripts and hotkeys.
> It is essentially a quick launcher for any AutoHotkey code.
> Check it out if you are interested!

Running AHK with UI Access by default has fixed the issue for me, and I hope this post helps you too.

Happy scripting!
