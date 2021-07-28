---
title: "Find which Windows process has file in use"
permalink: /Find-which-Windows-process-has-file-in-use/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22T00:00:00-06:00
comments_locked: false
categories:
  - Windows
tags:
  - Windows
  - File
  - Directory
  - Lock
  - In use
---

When a file or directory is in use by a process (application), you will not be able to delete or modify the file / directory.
Unfortunately it's not always obvious which process is using the file.
Luckily, there is an easy way to find out without the need of 3rd party tools, which is what we'll look at in this post.

If you prefer videos, you can [see the same content in this video](https://youtu.be/g4TGzaBER1U).

## Error message

If you attempt to use File Explorer to delete a file that is in use by another process, you will get an error message similar to:

> File In Use
>
> The action can't be completed because the file is open in [process name]
>
> Close the file and try again.

![File-in-use delete error message screenshot](/assets/Posts/2021-07-28-Find-which-Windows-process-has-file-in-use/FileInUseDeleteErrorMessageScreenshot.png)

If you attempt to delete the file in a different application, the wording of the error message may be different.

Similarly, if you attempt to modify a file that is in use by another process, you'll likely get an error about not being able to write to the file because it's in use or locked by another process.
Same thing if you try and rename or delete a directory that is in use by another process.
The exact wording of the error message will vary depending on the application being used to update / delete the file / directory.

### Reproduce the above error message

To reproduce the above error, I used the following PowerShell script and then attempted to delete the file in File Explorer:

```powershell
[string] $tempFilePath = "C:\Temp\FileLock\Temp.txt"
$tempFile = [System.IO.File]::Open($tempFilePath, [System.IO.FileMode]::OpenOrCreate)
# $tempFile.Dispose() # Close file when done reading/writing
```

Normally you would close the file using the Dispose method when done reading / writing, but I commented that out so the file lock would remain in place.

## Finding which process is using the file / directory

Windows has a built-in way to see which processes are actively using a file or directory, using the `Resource Monitor`.

To open the `Resource Monitor`, you can simply hit the <kbd>Windows Key</kbd> and search for it.
It can also be found in the Task Manager's `Performance` tab.

![Task Manager Resource Monitor screenshot](/assets/Posts/2021-07-28-Find-which-Windows-process-has-file-in-use/TaskManagerResourceMonitorScreenshot.png)

Simply open the `Resource Monitor`, go to the `CPU` tab, and enter the file or directory path you want to check into the `Search Handles` box, making sure to not include quotes around the path.

![Resource Monitor enter path screenshot](/assets/Posts/2021-07-28-Find-which-Windows-process-has-file-in-use/ResourceMonitorEnterPathScreenshot.png)

If a process has a handle to the file or directory, you should see results:

![Resource Monitor process found screenshot](/assets/Posts/2021-07-28-Find-which-Windows-process-has-file-in-use/ResourceMonitorProcessFoundScreenshot.png)]

Note that it shows you the PID (Process ID) of the process that has the handle to the file or directory.
This is important because you may have several processes with the same name in Task Manager, so you can use the PID to identify which process is using the file or directory.

From there you can use the `Task Manager` to find out more about the process, or simply right-click the process in the `Resource Monitor` window and select `End Process` to kill the process and release the lock on the file / directory.

Once that is done you should be able to modify or delete the file or directory.

Happy process hunting :)
