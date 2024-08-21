---
title: "A PowerShell function to easily retry any code"
permalink: /A-PowerShell-function-to-easily-retry-any-code/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - PowerShell
tags:
  - PowerShell
---

Performing retries to make your code more resilient is a common pattern.
By leveraging a PowerShell `ScriptBlock`, we can create a function to avoid constantly rewriting the same retry logic again and again.

## TL;DR

This post shows how you can build a PowerShell function to easily retry any PowerShell code that produces a terminating or non-terminating error.
If you want to skip the explanation and evolution of the code, [jump to the bottom of this post](#more-examples) to see the final function and examples, or [view them on my GitHub gist here](https://gist.github.com/deadlydog/620808036d309c8fa2606f32e5ef2f42).

## Traditional retry logic

Here's the traditional way that you might write some code with retry logic:

```powershell
[int] $maxNumberOfAttempts = 5
[int] $numberOfAttempts = 0
while ($true) {
  try {
    Do-Something
    break # Break out of the while-loop since the command succeeded.
  } catch {
    $numberOfAttempts++

    if ($numberOfAttempts -ge $maxNumberOfAttempts) {
      throw
    }

    Start-Sleep -Seconds 3
  }
}
```

You can see that the code above will attempt to perform the `Do-Something` command up to 5 times, waiting 3 seconds between each attempt.
If all 5 attempts fail, it will throw the exception.

## The basic concept of a retry function

Now suppose later in your code you need to call `Do-AnotherThing`, and then `Do-SomeOtherThing`, and you wanted retry logic for those as well.
Rather than repeating all of the above code, we can wrap it in a function and pass in the `ScriptBlock` to execute.

Here is what the function might look like:

```powershell
function Invoke-ScriptBlockWithRetries {
  param
  (
    [scriptblock] $ScriptBlock,
    [int] $MaxNumberOfAttempts = 5
  )

  [int] $numberOfAttempts = 0
  while ($true) {
    try {
      Invoke-Command -ScriptBlock $ScriptBlock
      break # Break out of the while-loop since the command succeeded.
    } catch {
      $numberOfAttempts++

      if ($numberOfAttempts -ge $MaxNumberOfAttempts) {
        throw
      }

      Start-Sleep -Seconds 3
    }
  }
}
```

You can see that the code is pretty much identical, except the function takes in the `ScriptBlock` to execute and the maximum number of attempts as parameters, and instead of calling `Do-Something` directly, we use `Invoke-Command` to execute the `ScriptBlock`.

With this function defined, we can now execute our commands, with retries, like this:

```powershell
# Use positional parameters and the default maximum number of attempts.
Invoke-ScriptBlockWithRetries { Do-Something }

# Use named parameters and specify the maximum number of attempts.
Invoke-ScriptBlockWithRetries -ScriptBlock { Do-AnotherThing } -MaxNumberOfAttempts 10

# You can also capture the output of the script block.
$resultOfDoSomeOtherThing = Invoke-ScriptBlockWithRetries -ScriptBlock { Do-SomeOtherThing }

# The script block can contain multiple lines of code, and can be defined as a variable.
[scriptblock] $action = {
  [string] $fileLocation = "C:\temp\file.txt"
  [string] $fileContents = Get-Content -Path $fileLocation
  [string] $newFileLocation = "C:\temp\newfile.txt"
  [string] $newFileContents = $fileContents -replace "old", "new"
  Set-Content -Path $newFileLocation -Value $newFileContents
  Write-Output "Successfully replaced 'old' with 'new' in file '$fileLocation' and saved it to '$newFileLocation'."
}
Invoke-ScriptBlockWithRetries -ScriptBlock $action -MaxNumberOfAttempts 3
```

### Retrying non-terminating errors too

You may have noticed a potential problem with our `Invoke-ScriptBlockWithRetries` function.
It will only retry terminating errors; that is, exceptions that are `throw`n, but not non-terminating errors, such as `Write-Error` when the error action is `Continue` (the default).

On potential solution is to convert all non-terminating errors to terminating errors by using the `$ErrorActionPreference` variable at the start of your script:

```powershell
$ErrorActionPreference = "Stop"
```

This will affect the entire script though, and is likely not what you want.
Another alternative is to use the `-ErrorAction` parameter of the specific cmdlets that might produce non-terminating errors.
In our previous example, you could do this:

```powershell
Set-Content -Path $newFileLocation -Value $newFileContents -ErrorAction Stop
```

This would ensure that any errors written by the `Set-Content` cmdlet would be treated as terminating errors (e.g. thrown exceptions) and would get retried.
Having to add `-ErrorAction Stop` to every cmdlet is tedious and error prone though.

A better way we can address this is to add a check for non-terminating errors and throw them if they occur, by making use of the `-ErrorVariable` parameter on our `Invoke-Command`:

```powershell
function Invoke-ScriptBlockWithRetries {
  param
  (
    [scriptblock] $ScriptBlock,
    [int] $MaxNumberOfAttempts = 5
  )

  [int] $numberOfAttempts = 0
  while ($true) {
    try {
      Invoke-Command -ScriptBlock $ScriptBlock -ErrorVariable nonTerminatingErrors

      # Check for non-terminating errors and throw them so they get retried.
      if ($nonTerminatingErrors) {
        throw $nonTerminatingErrors
      }

      break # Break out of the while-loop since the command succeeded.
    } catch {
      $numberOfAttempts++

      if ($numberOfAttempts -ge $MaxNumberOfAttempts) {
        throw
      }

      Start-Sleep -Seconds 3
    }
  }
}
```

Now both terminating and non-terminating errors will be retried 🙂

## Making the function more flexible

Now that we have a nice reusable function, let's improve it to make it more flexible and use parameter validation.
We can add parameters so the user can configure how long to wait between retries, whether to use exponential backoff, provide a list of errors that should not be retried, and whether to retry non-terminating errors or not:

```powershell
function Invoke-ScriptBlockWithRetries {
  [CmdletBinding()]
  param (
    [Parameter(Mandatory = $true, HelpMessage = "The script block to execute.")]
    [ValidateNotNull()]
    [scriptblock] $ScriptBlock,

    [Parameter(Mandatory = $false, HelpMessage = "The maximum number of times to attempt the script block when it returns an error.")]
    [ValidateRange(1, [int]::MaxValue)]
    [int] $MaxNumberOfAttempts = 5,

    [Parameter(Mandatory = $false, HelpMessage = "The number of milliseconds to wait between retry attempts.")]
    [ValidateRange(1, [int]::MaxValue)]
    [int] $MillisecondsToWaitBetweenAttempts = 3000,

    [Parameter(Mandatory = $false, HelpMessage = "If true, the number of milliseconds to wait between retry attempts will be multiplied by the number of attempts.")]
    [switch] $ExponentialBackoff = $false,

    [Parameter(Mandatory = $false, HelpMessage = "List of error messages that should not be retried. If the error message contains one of these strings, the script block will not be retried.")]
    [ValidateNotNull()]
    [string[]] $ErrorsToNotRetry = @(),

    [Parameter(Mandatory = $false, HelpMessage = "If true, only terminating errors (e.g. thrown exceptions) will cause the script block will be retried. By default, non-terminating errors will also trigger the script block to be retried.")]
    [switch] $DoNotRetryNonTerminatingErrors = $false
  )

  [int] $numberOfAttempts = 0
  while ($true) {
    try {
      Invoke-Command -ScriptBlock $ScriptBlock -ErrorVariable nonTerminatingErrors

      if ($nonTerminatingErrors -and (-not $DoNotRetryNonTerminatingErrors)) {
        throw $nonTerminatingErrors
      }

      break # Break out of the while-loop since the command succeeded.
    } catch {
      $numberOfAttempts++

      [string] $errorMessage = $_.Exception.ToString()
      [string] $errorDetails = $_.ErrorDetails
      Write-Verbose "Attempt number '$numberOfAttempts' of '$MaxNumberOfAttempts' failed.`nError: $errorMessage `nErrorDetails: $errorDetails"

      if ($numberOfAttempts -ge $MaxNumberOfAttempts) {
        throw
      }

      # If the errorMessage contains one of the errors that should not be retried, then throw the error.
      foreach ($errorToNotRetry in $ErrorsToNotRetry) {
        if ($errorMessage -like "*$errorToNotRetry*" -or $errorDetails -like "*$errorToNotRetry*") {
          Write-Verbose "The string '$errorToNotRetry' was found in the error message, so not retrying."
          throw
        }
      }

      [int] $millisecondsToWait = $MillisecondsToWaitBetweenAttempts
      if ($ExponentialBackoff) {
        $millisecondsToWait = $MillisecondsToWaitBetweenAttempts * $numberOfAttempts
      }
      Write-Verbose "Waiting '$millisecondsToWait' milliseconds before next attempt."
      Start-Sleep -Milliseconds $millisecondsToWait
    }
  }
}
```

Here is a contrived example of how you might use the updated function:

```powershell
# Simulate some code that might fail in various ways.
[scriptblock] $flakyAction = {
  $random = Get-Random -Minimum 0 -Maximum 10
  if ($random -lt 2) {
    Write-Output "Success"
  } elseif ($random -lt 4) {
    Write-Error "Error"
  } elseif ($random -lt 6) {
    Write-Error "Error DoNotRetry"
  } elseif ($random -lt 8) {
    throw "Exception"
  } else {
    throw "Exception DoNotRetry"
  }
}

# Call our scriptblock, ensuring any exceptions or errors containing "DoNotRetry" are not retried, but all others are.
# Use the -Verbose parameter to see additional details about any failures.
Invoke-ScriptBlockWithRetries -ScriptBlock $flakyAction -MaxNumberOfAttempts 10 -MillisecondsToWaitBetweenAttempts 100 -ExponentialBackoff -ErrorsToNotRetry "DoNotRetry" -Verbose
```

And here is the output you might see when running the above code, where it first fails with an exception, and then fails with an error, and succeeds on the 3rd attempt:

```plaintext
VERBOSE: Attempt number '1' of '10' failed.
Error: System.Management.Automation.RuntimeException: Exception
ErrorDetails:
VERBOSE: Waiting '100' milliseconds before next attempt.
Invoke-ScriptBlockWithRetries: C:\dev\Git\iQ\RQ.ClientCancellationProcess\Test.ps1:89:1
Line |
  89 |  Invoke-ScriptBlockWithRetries -ScriptBlock $flakyAction -MaxNumberOfA …
     |  ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     | Error
VERBOSE: Attempt number '2' of '10' failed.
Error: System.Management.Automation.RuntimeException: Error
ErrorDetails:
VERBOSE: Waiting '200' milliseconds before next attempt.
Success
```

If you do not want non-terminating errors to be retried (e.g. the `Write-Error` cases in the `$flakyAction` above), provide the `-DoNotRetryNonTerminatingErrors` switch parameter.

You may have noticed that the verbose output includes the error message and error details.
This is because some cmdlets, such as `Invoke-WebRequest`, sometimes put the error message in the ErrorDetails property.

### More examples

Here are some more practical examples:

#### Example 1: Stop a service if it exists

This will only retry if the service "SomeService" exists.
If it doesn't, the error message "Cannot find any service with service name 'SomeService'." would be returned and the function won't bother retrying.

```powershell
Invoke-ScriptBlockWithRetries { Stop-Service -Name "SomeService" } -ErrorsToNotRetry 'Cannot find any service with service name'
```

#### Example 2: Validate a web request was successful

```powershell
[scriptblock] $exampleThatReturnsData = {
  Invoke-WebRequest -Uri 'https://google.com'
}
$result = Invoke-ScriptBlockWithRetries -ScriptBlock $exampleThatReturnsData -MaxNumberOfAttempts 3

if ($result.StatusCode -eq 200) {
  Write-Output "Success"
}
```

NOTE: PowerShell 6+ have built-in Retry parameters on the [Invoke-WebRequest](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.utility/invoke-webrequest) and [Invoke-RestMethod](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.utility/invoke-restmethod) cmdlets that could be used instead of this function.

#### Example 3: Dealing with failures

If you want to take additional actions on failures that still occur after all of the retries, you can catch the exception and handle it as needed.

```powershell
[string] $nonExistentWebAddress = 'https://SomeAddressThatDoesNotExist.com'

[scriptblock] $exampleThatWillAlwaysFail = {
  Invoke-WebRequest -Uri $nonExistentWebAddress
}

try {
  Invoke-ScriptBlockWithRetries -ScriptBlock $exampleThatWillAlwaysFail -MillisecondsToWaitBetweenAttempts 100
} catch {
  $exceptionMessage = $_.Exception.Message
  Write-Error "An error occurred calling '$nonExistentWebAddress': $exceptionMessage"
  throw
}
```

### Example 4: Do not retry expected errors

There may be certain errors that are expected in certain situations, or where you know a retry will not help.
To save time, specify not to retry on these errors:

```powershell
[scriptblock] $exampleThatReturnsData = {
  Invoke-RestMethod -Uri 'https://api.google.com'
}

[string[]] $noRetryMessages = @(
  '400 (Bad Request)'
  '401 (Unauthorized)'
  '404 (Not Found)'
)

Invoke-ScriptBlockWithRetries -ScriptBlock $exampleThatReturnsData -ErrorsToNotRetry $noRetryMessages
```

### Example 5: Perform multiple actions

Because we use a scriptblock, we can perform multiple actions and if any of them fail, the entire scriptblock will be retried:

```powershell
[scripblock] $getDataAndWriteItToAFileAndSendSlackMessage = {
  [string] $data = Invoke-RestMethod -Uri 'https://SomeApi.com/data'
  $data | Set-Content -Path 'C:\temp\data.txt'
  Send-SlackMessage -Message "Data was successfully retrieved and saved to 'C:\temp\data.txt'." -Channel '#general'
}

Invoke-ScriptBlockWithRetries -ScriptBlock $getDataAndWriteItToAFileAndSendSlackMessage
```

## Even more options

One caveat with the above implementation is that non-terminating errors will be thrown as terminating errors if they are still failing after all of the retries, which may not be the desired behavior.
I typically prefer to have all persisting errors thrown, as it allows for a single way to handle any errors produced by the script block (i.e. with a try-catch block).

For those that do not want this behaviour, I offer the following implementation that is not quite as straightforward, but provides a `DoNotThrowNonTerminatingErrors` parameter that allows for non-terminating errors to not be thrown if they are still failing after all of the retries:

```powershell
function Invoke-ScriptBlockWithRetries {
  [CmdletBinding(DefaultParameterSetName = 'RetryNonTerminatingErrors')]
  param (
    [Parameter(Mandatory = $true, HelpMessage = "The script block to execute.")]
    [ValidateNotNull()]
    [scriptblock] $ScriptBlock,

    [Parameter(Mandatory = $false, HelpMessage = "The maximum number of times to attempt the script block when it returns an error.")]
    [ValidateRange(1, [int]::MaxValue)]
    [int] $MaxNumberOfAttempts = 5,

    [Parameter(Mandatory = $false, HelpMessage = "The number of milliseconds to wait between retry attempts.")]
    [ValidateRange(1, [int]::MaxValue)]
    [int] $MillisecondsToWaitBetweenAttempts = 3000,

    [Parameter(Mandatory = $false, HelpMessage = "If true, the number of milliseconds to wait between retry attempts will be multiplied by the number of attempts.")]
    [switch] $ExponentialBackoff = $false,

    [Parameter(Mandatory = $false, HelpMessage = "List of error messages that should not be retried. If the error message contains one of these strings, the script block will not be retried.")]
    [ValidateNotNull()]
    [string[]] $ErrorsToNotRetry = @(),

    [Parameter(Mandatory = $false, ParameterSetName = 'IgnoreNonTerminatingErrors', HelpMessage = "If true, only terminating errors (e.g. thrown exceptions) will cause the script block will be retried. By default, non-terminating errors will also trigger the script block to be retried.")]
    [switch] $DoNotRetryNonTerminatingErrors = $false,

    [Parameter(Mandatory = $false, ParameterSetName = 'RetryNonTerminatingErrors', HelpMessage = "If true, any non-terminating errors that occur on the final retry attempt will not be thrown as a terminating error.")]
    [switch] $DoNotThrowNonTerminatingErrors = $false
  )

  [int] $numberOfAttempts = 0
  while ($true) {
    try {
      Invoke-Command -ScriptBlock $ScriptBlock -ErrorVariable nonTerminatingErrors

      if ($nonTerminatingErrors -and (-not $DoNotRetryNonTerminatingErrors)) {
        throw $nonTerminatingErrors
      }

      break # Break out of the while-loop since the command succeeded.
    } catch {
      [bool] $shouldRetry = $true
      $numberOfAttempts++

      [string] $errorMessage = $_.Exception.ToString()
      [string] $errorDetails = $_.ErrorDetails
      Write-Verbose "Attempt number '$numberOfAttempts' of '$MaxNumberOfAttempts' failed.`nError: $errorMessage `nErrorDetails: $errorDetails"

      if ($numberOfAttempts -ge $MaxNumberOfAttempts) {
        $shouldRetry = $false
      }

      if ($shouldRetry) {
        # If the errorMessage contains one of the errors that should not be retried, then do not retry.
        foreach ($errorToNotRetry in $ErrorsToNotRetry) {
          if ($errorMessage -like "*$errorToNotRetry*" -or $errorDetails -like "*$errorToNotRetry*") {
            Write-Verbose "The string '$errorToNotRetry' was found in the error message, so not retrying."
            $shouldRetry = $false
            break # Break out of the foreach-loop since we found a match.
          }
        }
      }

      if (-not $shouldRetry) {
        [bool] $isNonTerminatingError = $_.TargetObject -is [System.Collections.ArrayList]
        if ($isNonTerminatingError -and $DoNotThrowNonTerminatingErrors) {
          break # Just break out of the while-loop since the error was already written to the error stream.
        } else {
          throw # Throw the error so it's obvious one occurred.
        }
      }

      [int] $millisecondsToWait = $MillisecondsToWaitBetweenAttempts
      if ($ExponentialBackoff) {
        $millisecondsToWait = $MillisecondsToWaitBetweenAttempts * $numberOfAttempts
      }
      Write-Verbose "Waiting '$millisecondsToWait' milliseconds before next attempt."
      Start-Sleep -Milliseconds $millisecondsToWait
    }
  }
}
```

You can also [find this implementation and examples on my GitHub gist here](https://gist.github.com/deadlydog/620808036d309c8fa2606f32e5ef2f42).

## Conclusion

By using a function like `Invoke-ScriptBlockWithRetries`, you can make your scripts more resilient to failures and avoid repeating the same retry logic over and over.

This is a function that I use in many of my scripts.
Feel free to use it as-is, or update it to suit your needs.
Have feedback or suggestions?
Let me know by leaving a comment below.

Happy coding!
