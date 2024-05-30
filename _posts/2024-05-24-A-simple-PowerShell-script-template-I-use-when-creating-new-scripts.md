---
title: "A simple PowerShell script template I use when creating new scripts"
permalink: /A-simple-PowerShell-script-template-I-use-when-creating-new-scripts/
#date: 2099-01-15T00:00:00-06:00
last_modified_at: 2024-05-29
comments_locked: false
toc: false
categories:
  - PowerShell
  - Productivity
tags:
  - PowerShell
  - Productivity
---

I spin up new PowerShell scripts all the time, whether just for quickly experimenting and testing things out, or for projects that I know will be around for a while.
There's a few basic things that I like all my scripts to have, so I've created a simple template that I use when creating new standalone scripts.

The script template provides:

- A basic script structure with `[CmdletBinding()]` enabled.
- `$InformationPreference` enabled to show `Write-Information` messages.
- Displays the time the script started and ended, and how long it took to run.
- Uses `Start-Transcript` to log the script output from the last run to a file.
  The log file will have the same name as the script, with `.LastRun.log` appended to it.
  - Logging to a transcript file will incur a small performance hit, so if you need your script to be super performant, and depending on how much output your script generates, you may want to remove this.
  - If your script outputs sensitive information such as passwords or secrets, you may want to remove this, since they could end up in the unencrypted log file.

Using a template allows me to get up and running quickly, and ensures all of my scripts have a consistent structure.

Here's the script template code:

```powershell
<#
  .SYNOPSIS
  PUT SHORT SCRIPT DESCRIPTION HERE AND ADD ANY ADDITIONAL KEYWORD SECTIONS AS NEEDED (.PARAMETER, .EXAMPLE, ETC.).
#>
[CmdletBinding()]
param (
  # PUT PARAMETER DEFINITIONS HERE AND DELETE THIS COMMENT.
)

process {
  # PUT SCRIPT CODE HERE AND DELETE THIS COMMENT.
}

begin {
  # DEFINE FUNCTIONS HERE AND DELETE THIS COMMENT.

  $InformationPreference = 'Continue'
  # $VerbosePreference = 'Continue' # Uncomment this line if you want to see verbose messages.

  # Log all script output to a file for easy reference later if needed.
  [string] $lastRunLogFilePath = "$PSCommandPath.LastRun.log"
  Start-Transcript -Path $lastRunLogFilePath

  # Display the time that this script started running.
  [DateTime] $startTime = Get-Date
  Write-Information "Starting script at '$($startTime.ToString('u'))'."
}

end {
  # Display the time that this script finished running, and how long it took to run.
  [DateTime] $finishTime = Get-Date
  [TimeSpan] $elapsedTime = $finishTime - $startTime
  Write-Information "Finished script at '$($finishTime.ToString('u'))'. Took '$elapsedTime' to run."

  Stop-Transcript
}
```

I also have the template stored as [a GitHub gist here](https://gist.github.com/deadlydog/d04b5d43170a90d8bc0143373d90010f), which may be more up-to-date than the code in this post.

I often use this template to create standalone scripts that I run directly, rather than calling them from other scripts (I would typically create a module instead for those types of reusable functions).
This is the reason that I set the `$InformationPreference` and `$VerbosePreference` in the `begin` block and start a transcript.
If you're calling the script from another script, you likely want to remove those lines and rely on the calling script to pass in the preference parameters via the CmdletBinding and start the transcript itself.

I typically define all of my functions in the `begin` block, and then call them from the `process` block as needed.
I also put the `process` block before the `begin` and `end` blocks.
This helps keep the primary script code (in the `process` block) front-and-center at the top of the script, and makes it easier to see what the script is doing at a glance.
If I need to see the details of a function I can use <kbd>F12</kbd> to jump to the function definition.
Often times I just want a high-level overview of what the script is doing though, and reading the `process` block provides that.
I like to think of the `process` block like the table of contents or list of chapters in a book; it gives you a quick high-level summary of everything in the book and allows you to jump straight to the section you're interested in, without having to read the entire book.

In a traditional script without a `begin` block, you would need to define all of your functions before you call them, meaning you'd have to scroll down and hunt for where the script code actually starts executing commands.
Following the convention of defining functions in the `begin` block, you can just look at the `process` block to see where the script actually starts executing non-boilerplate code.

Here is a contrived example of how a script using this template might look:

```powershell
<#
  .SYNOPSIS
  Writes the specified text to a file.

  .DESCRIPTION
  This script writes a given text to a specified file, creating the directory if it doesn't exist.

  .PARAMETER TextToWriteToFile
  The text to write to the file.

  .PARAMETER FilePath
  The file path to write the text to, overwriting the file if it already exists.

  .EXAMPLE
  .\Script.ps1 -TextToWriteToFile "Sample Text" -FilePath "C:\Temp\Test.txt"

  .NOTES
  Ensure that you have the necessary permissions to write to the specified file path.
#>
[CmdletBinding()]
param (
  [Parameter(Mandatory = $false, HelpMessage = 'The text to write to the file.')]
  [string] $TextToWriteToFile = 'Hello, World!',

  [Parameter(Mandatory = $false, HelpMessage = 'The file path to write the text to.')]
  [string] $FilePath = "$PSScriptRoot\Test.txt"
)

process {
  Ensure-DirectoryExists -directoryPath (Split-Path -Path $FilePath -Parent)

  Write-Information "Writing the text '$TextToWriteToFile' to the file '$FilePath'."
  Write-TextToFile -text $TextToWriteToFile -filePath $FilePath
}

begin {
  function Ensure-DirectoryExists ([string] $directoryPath) {
    if (-not (Test-Path -Path $directoryPath -PathType Container))
    {
      Write-Information "Creating directory '$directoryPath'."
      New-Item -Path $directoryPath -ItemType Directory -Force > $null
    }
  }

  function Write-TextToFile ([string] $text, [string] $filePath) {
    if (Test-Path -Path $filePath -PathType Leaf) {
      Write-Warning "File '$filePath' already exists. Overwriting it."
    }

    Set-Content -Path $filePath -Value $text -Force
  }

  $InformationPreference = 'Continue'
  # $VerbosePreference = 'Continue' # Uncomment this line if you want to see verbose messages.

  # Log all script output to a file for easy reference later if needed.
  [string] $lastRunLogFilePath = "$PSCommandPath.LastRun.log"
  Start-Transcript -Path $lastRunLogFilePath

  # Display the time that this script started running.
  [DateTime] $startTime = Get-Date
  Write-Information "Starting script at '$($startTime.ToString('u'))'."
}

end {
  # Display the time that this script finished running, and how long it took to run.
  [DateTime] $finishTime = Get-Date
  [TimeSpan] $elapsedTime = $finishTime - $startTime
  Write-Information "Finished script at '$($finishTime.ToString('u'))'. Took '$elapsedTime' to run."

  Stop-Transcript
}
```

Were you able to understand what the script does just by reading the `param` and `process` blocks at the top?
Hopefully you didn't need to read the entire script to understand that it simply writes the provided text to the provided file path.
This is the benefit of putting the `process` block at the top of the script, and functions in the `begin` block.
Also, often times the code in the `begin` and `end` blocks are complimentary to each other, so it makes sense to keep them together.

You may argue that the Synopsis in the comment-based help provided that information as well, and in this simple trivial example you are correct.
The comment-based help should only describe the goal of the script and how to use it, not the individual steps of how it accomplishes that goal, which is what the `process` block can provide.
I'll note though that the `process` block will only give you that nice high-level overview of the script steps if you write your code for it.
If you just dump all of the code directly in the `process` block you won't have a nice overview; if you break the steps out into well named functions though, it can be easy to read through and understand the high-level script steps.
Not everyone will agree wants to write their code this way though, and that's fine; it's just what I've found works well for me and my team, and makes our scripts easier to reason about and maintain.

This template is just my own personal preference, and I'm sure there are other things you may want to add to it.
Feel free to use it as-is, or modify it to suit your own needs.
I personally like to keep my template minimal and only include the things that I find myself adding to every script.
You may want to create an "all the bells and whistles" template to start from, or different templates for different types of scripts (e.g. a different template for non-standalone scripts).

The main point is having a boilerplate template to start from can save you time and help ensure that all of your scripts have a consistent feel.

Do you use a template for your scripts?
Are you going to start after reading this?
Is there anything you would add or change in the template I provided?
Let me know in the comments below!

Hopefully you found this useful.
Happy scripting!
