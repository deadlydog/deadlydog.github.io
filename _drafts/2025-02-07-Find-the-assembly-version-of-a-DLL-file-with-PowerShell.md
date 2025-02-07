---
title: "Find the assembly version of a DLL file with PowerShell"
permalink: /Find-the-assembly-version-of-a-DLL-file-with-PowerShell/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - .NET
  - PowerShell
tags:
  - .NET
  - PowerShell
---

Sometimes you cannot find the assembly version of a DLL by simply looking at the file properties in File Explorer.
In this case, you can use PowerShell (with .NET) to find the assembly version of a DLL file.

## TL;DR

Here's the PowerShell command to find the assembly version of a DLL file:

```powershell
[System.Reflection.AssemblyName]::GetAssemblyName("C:\path\to\your.dll")
```

The object returned will contain many properties, with the assembly `Version` and `Name` written the to the terminal by default.

## Background



![Example image](/assets/Posts/2025-02-07-Find-the-assembly-version-of-a-DLL-file-with-PowerShell/image-name.png)

Posts in this _drafts directory will not show up on the website unless you build using `--draft` when compiling:

> bundle exec jekyll serve --incremental --draft
