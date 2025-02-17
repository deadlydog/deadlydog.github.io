---
title: "Reclaim disk space by cleaning all your Git repos with PowerShell"
permalink: /Reclaim-disk-space-by-cleaning-all-your-Git-repos-with-PowerShell/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - Git
  - PowerShell
  - Productivity
tags:
  - Git
  - PowerShell
  - Productivity
---

Developers often have tens or hundreds of Git repositories on their machines.
When we are done with a project, we often forget to clean up the repository, leaving temporary files like build artifacts, logs, packages, modules, and other files taking up valuable disk space.

The [git clean command](https://git-scm.com/docs/git-clean) can be used to remove untracked files and free up disk space.
However, running `git clean -xfd` in each repository manually can be a hassle.
Luckily, we can automate this process using PowerShell.

## Clean all repos using the GitClean PowerShell module

To make it easy to clean all your Git repositories, I created [the GitClean PowerShell module](https://github.com/deadlydog/PowerShell.GitClean).

Assuming all of your Git repositories are located under a common root folder, `C:\dev\Git` for example, you can install the module and clean all your repos with the following PowerShell code:

```powershell
Install-Module -Name GitClean
Invoke-GitClean -RootDirectoryPath 'C:\dev\Git'
```

![Screenshot of running the Invoke-GitClean command](/assets/Posts/2025-02-17-Reclaim-disk-space-by-cleaning-all-your-Git-repos-with-PowerShell/invoke-gitclean-with-root-directory-and-informational-messages-parameters.png)

In the screenshot above you can see it found 262 Git repositories, cleaned 260 of them, skipped cleaning 2 of them because they had untracked files that would have been lost, and freed up 33.4 GB of disk space.
Easy 😊

No more node_modules of old projects taking up all my disk space 😅

![meme of node_modules directory being huge](/assets/Posts/2025-02-17-Reclaim-disk-space-by-cleaning-all-your-Git-repos-with-PowerShell/node_modules-size-meme.jpeg)

## Alternative PowerShell one-liner

Not everyone likes installing modules on their system, so as an alternative, here is a simple PowerShell script you can run to clean all your Git repositories:

```powershell
[string] $rootDirectoryPath = 'C:\dev\Git'

# Find all '.git' directories below the given path.
Get-ChildItem -Path $rootDirectoryPath -Recurse -Depth 3 -Force -Directory -Filter '.git' |
    # Get the directory path of the Git repository.
    ForEach-Object {
        $gitDirectoryPath = $_.FullName
        $gitRepositoryDirectoryPath = Split-Path -Path $gitDirectoryPath -Parent
        Write-Output $gitRepositoryDirectoryPath
    } |
    # Only include Git repositories that do not have untracked files.
    Where-Object {
        $gitOutput = Invoke-Expression "git -C ""$_"" status" | Out-String
        [bool] $hasUntrackedFiles = $gitOutput.Contains('Untracked files')
        if ($hasUntrackedFiles) {
            Write-Warning "Skipping Git repository with untracked files: $_"
        }
        Write-Output (-not $hasUntrackedFiles)
    } |
    # Clean the Git repository.
    ForEach-Object {
        Write-Output "Cleaning Git repository: $_"
        Invoke-Expression "git -C ""$_"" clean -xdf"
    }
```

You can [download the script here](/assets/Posts/2025-02-17-Reclaim-disk-space-by-cleaning-all-your-Git-repos-with-PowerShell/CleanGitRepositories.ps1).

This won't give you the nice progress bars and detailed information that the GitClean module does, but it will get the job done.

## Conclusion

It's easy to forget to clean up old Git repositories.
When you do remember to, the GitClean module can make it quick and painless.
You might even want to create a scheduled task or cron job to run it automatically every month to keep your disk space in check.

I hope you find this module useful.
Happy coding! 😊
