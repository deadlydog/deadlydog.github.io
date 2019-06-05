---
id: 585
title: Always Explicitly Set Your Parameter Set Variables For PowerShell v2.0 Compatibility
date: 2013-10-28T11:25:32-06:00
guid: http://dans-blog.azurewebsites.net/?p=585
permalink: /always-explicitly-set-your-parameter-set-variables-for-powershell-v2-0-compatibility/
categories:
  - PowerShell
tags:
  - Compatible
  - parameter
  - Parameter Set
  - PowerShell
  - Variable
  - Version
---

## What are parameter sets anyways?

[Parameter sets](http://msdn.microsoft.com/en-us/library/windows/desktop/dd878348%28v=vs.85%29.aspx) were introduced in PowerShell v2.0 and are useful for enforcing mutually exclusive parameters on a cmdlet. [Ed Wilson has a good little article](http://blogs.technet.com/b/heyscriptingguy/archive/2011/06/30/use-parameter-sets-to-simplify-powershell-commands.aspx) explaining what parameter sets are and how to use them. Essentially they allow us to write a single cmdlet that might otherwise have to be written as 2 or more cmdlets that took different parameters. For example, instead of having to create Process-InfoFromUser, Process-InfoFromFile, and Process-InfoFromUrl cmdlets, we could create a single Process-Info cmdlet that has 3 mutually exclusive parameters, [switch]$PromptUser, [string]$FilePath, and [string]$Url. If the cmdlet is called with more than one of these parameters, it throws an error.

You could just be lazy and not use parameter sets and allow all 3 parameters to be specified and then just use the first one, but the user won’t know which one of the 3 they provided will be used; they might assume that all 3 will be used. This would also force the user to have to read the documentation (assuming you have provided it). Using parameter sets enforces makes it clear to the user which parameters are able to be used with other parameters. Also, most PowerShell editors process parameter sets to have the intellisense properly show the parameters that can be used with each other.

## Ok, parameter sets sound awesome, I want to use them! What's the problem?

The problem I ran into was in my [Invoke-MsBuild module that I put on CodePlex](https://invokemsbuild.codeplex.com/), I had a [switch]$PassThru parameter that was part of a parameter set. Within the module I had:

```powershell
if ($PassThru) { do something... }
else { do something else... }
```

This worked great for me during my testing since I was using PowerShell v3.0. The problem arose once I released my code to the public; I received an issue from a user who was getting the following error message:

> Invoke-MsBuild : Unexpected error occurred while building "[path]\my.csproj": The variable '$PassThru' cannot be retrieved because it has not been set.
>
> At build.ps1:84 char:25
>
>   * $result = Invoke-MsBuild <<<< -Path "[path]\my.csproj" -BuildLogDirectoryPath "$scriptPath" -Params "/property:Configuration=Release"

After some investigation I determined the problem was that they were using PowerShell v2.0, and that my script uses Strict Mode. I use __Set-StrictMode -Version Latest__ in all of my scripts to help me catch any syntax related errors and to make sure my scripts will in fact do what I intend them to do. While you could simply not use strict mode and __you__ wouldn’t have a problem, I don’t recommend that; if others are going to call your cmdlet (or you call it from a different script), there’s a good chance they may have Strict Mode turned on and your cmdlet may break for them.

## So should I not use parameter sets with PowerShell v2.0? Is there a fix?

You absolutely SHOULD use parameter sets whenever you can and it makes sense, and yes there is a fix. If you require your script to run on PowerShell v2.0, there is just one extra step you need to take, which is to explicitly set the values for any parameters that use a parameter set [and don’t exist](http://stackoverflow.com/questions/3159949/in-powershell-how-do-i-test-whether-or-not-a-specific-variable-exists-in-global). Luckily we can use the __Test-Path__ cmdlet to test if a variable has been defined in a specific scope or not.

Here is an example of how to detect if a variable is not defined in the Private scope and set its default value. We specify the scope in case a variable with the same name exists outside of the cmdlet in the global scope or an inherited scope.

```powershell
# Default the ParameterSet variables that may not have been set depending on which parameter set is being used. This is required for PowerShell v2.0 compatibility.
if (!(Test-Path Variable:Private:SomeStringParameter)) { $SomeStringParameter = $null }
if (!(Test-Path Variable:Private:SomeIntegerParameter)) { $SomeIntegerParameter = 0 }
if (!(Test-Path Variable:Private:SomeSwitchParameter)) { $SomeSwitchParameter = $false }
```

If you prefer, instead of setting a default value for the parameter you could just check if it is defined first when using it in your script. I like this approach however, because I can put this code right after my cmdlet parameters so I’m modifying all of my parameter set properties in one place, and I don’t have to remember to check if the variable is defined later when writing the body of my cmdlet; otherwise I’m likely to forget to do the "is defined" check, and will likely miss the problem since I do most of my testing in PowerShell v3.0.

Another approach rather than checking if a parameter is defined or not, is to [check which Parameter Set Name is being used](http://blogs.msdn.com/b/powershell/archive/2008/12/23/powershell-v2-parametersets.aspx); this will implicitly let you know which parameters are defined.

```powershell
switch ($PsCmdlet.ParameterSetName)
{
    "SomeParameterSetName" { Write-Host "You supplied the Some variable."; break}
    "OtherParameterSetName" { Write-Host "You supplied the Other variable."; break}
}
```

I still prefer to default all of my parameters, but you may prefer this method.

I hope you find this useful. Check out my other [article for more PowerShell v2.0 vs. v3.0 differences](http://dans-blog.azurewebsites.net/powershell-2-0-vs-3-0-syntax-differences-and-more/).

Happy coding!
