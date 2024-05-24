---
title: "A simple PowerShell script template I use when creating new scripts"
permalink: /A-simple-PowerShell-script-template-I-use-when-creating-new-scripts/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
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
There's a few basic things that I like all my scripts to have, so I've created a simple template that I use when creating new scripts.

The script template provides:

- A basic script structure with `[CmdletBinding()]` enabled.
- `$InformationPreference` enabled to show `Write-Information` messages.
- Displays the time the script started and ended, and how long it took to run.
- Uses `Start-Transcript` to log the script output from the last run to a file.
  The log file will have the same name as the script, with `.LastRun.log` appended to it.

Using a template allows me to get up and running quickly, and ensures all of my scripts have a consistent structure.

Here's the script template code:

```powershell
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

I typically define all of my functions in the `begin` block, and then call them from the `process` block as needed.
This helps keep the primary script code front-and-center at the top of the script, and makes it easier to see what the script is doing at a glance.
If I need to see the details of a function I can use <kbd>F12</kbd> to jump to the function definition.
Often times I just want a high-level overview of what the script is doing though, and reading the `process` block provides that.

In a traditional script without a `begin` block, you would need to define all of your functions before you call them, meaning you'd have to scroll down and hunt for where the script code actually starts executing commands.
Following the convention of defining functions in the `begin` block, you can just look at the `process` block to see where the script code starts executing.

Here is a contrived example of how a script using this template might look:

```powershell
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

This template is just my own personal preference, and I'm sure there are other things you may want to add to it.
Feel free to use it as-is, or modify it to suit your own needs.

The main point is having a boilerplate template to start from can save you time and help ensure that all of your scripts have a consistent feel.

Do you use a template for your scripts?
Are you going to start after reading this?
Is there anything you would add or change in the template I provided?
Let me know in the comments below!

Hopefully you found this useful.
Happy coding!
