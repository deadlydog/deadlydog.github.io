---
title: "Resolve PowerShell paths that do not exist"
permalink: /Resolve-PowerShell-paths-that-do-not-exist/
#date: 2099-01-15T00:00:00-06:00
last_modified_at: 2023-10-03
comments_locked: false
toc: false
categories:
  - PowerShell
tags:
  - PowerShell
---

In this post we'll see how to resolve a file or directory path, even if it does not exist.

## Backstory

While testing my [tiPS PowerShell module](https://github.com/deadlydog/PowerShell.tiPS) with Pester, I ran into a scenario where I wanted to mock out a function which returned a path to a configuration file.
Pester has a built-in PSDrive ([see MS docs](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.management/new-psdrive)) called `TestDrive`, which is a temporary drive that is created for the duration of the test ([see the docs here](https://pester.dev/docs/usage/testdrive)) and automatically cleaned up when the tests complete.
So rather than hardcoding a local path on disk, you can use a path like `TestDrive:/config.json`.

The problem was that the .NET methods, like `System.IO.File.WriteAllText()` and `System.IO.File.ReadAllText()`, do not work with the `TestDrive:` PSDrive, as they are unable to resolve the path.
The `Set-Content` and `Get-Content` cmdlets work fine with `TestDrive:`, but I wanted to use the .NET methods for performance reasons.

I thought an easy fix would be to just use `Resolve-Path` or `Join-Path -Resolve` to get the full path, but they return an error when the file or directory does not exist.
I did not want to manually create the file in my mock or my test, as I wanted my Pester test to ensure the module created the file properly.

## The solution

This is when I stumbled upon the `GetUnresolvedProviderPathFromPSPath` method, which can be used to resolve a file or directory path, even if it does not exist.
Here is an example of how to use it:

```powershell
[string] $configPath = $ExecutionContext.SessionState.Path.GetUnresolvedProviderPathFromPSPath('TestDrive:/config.json')
```

This resolved the path to the Windows temp directory on the C drive, and I was able to use it with the .NET `System.IO.File` methods.

This method works with any path, not just PSDrive paths.
For example, it also resolves this non-existent path:

```powershell
$ExecutionContext.SessionState.Path.GetUnresolvedProviderPathFromPSPath("$Env:Temp/FileThatDoesNotExist.txt")
```

to `C:\Users\DAN~1.SCH\AppData\Local\Temp\FileThatDoesNotExist.txt`.

## A better solution for Pester PSDrive paths

Later I fully read [the Pester TestDrive documentation](https://pester.dev/docs/usage/testdrive#working-with-net-objects) and found that it actually has a built-in `$TestDrive` variable that is compatible with the .NET methods 🤦‍♂️.
So instead of using `TestDrive:/config.json`, I could just use `$TestDrive/config.json`.
I ended up changing my Pester mock to use this instead, as it is much cleaner:

```powershell
[string] $configPath = "$TestDrive/config.json"
```

Oh well, I learned something new about PowerShell, so it was worth it.
I'm sure I'll run into another situation down the road where `GetUnresolvedProviderPathFromPSPath` will come in handy.
Hopefully you've learned something new too.

Happy coding!
