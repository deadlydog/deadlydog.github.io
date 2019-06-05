---
id: 552
title: PowerShell 2.0 vs. 3.0 Syntax Differences And More
date: 2013-10-22T11:23:21-06:00
guid: http://dans-blog.azurewebsites.net/?p=552
permalink: /powershell-2-0-vs-3-0-syntax-differences-and-more/
categories:
  - PowerShell
tags:
  - "2"
  - "2.0"
  - "3"
  - "3.0"
  - changes
  - differences
  - New
  - PowerShell
  - syntax
  - Version
---

I’m fortunate enough to work for [a great company](http://www.iqmetrix.com/) that tries to stay ahead of the curve and use newer technologies. This means that when I’m writing my PowerShell (PS) scripts I typically don’t have to worry about only using PS v2.0 compatible syntax and cmdlets, as all of our PCs have v3.0 (soon to have v4.0). This is great, until I release these scripts (or snippets from the scripts) for the general public to use; I have to keep in mind that many other people are still stuck running older versions of Windows, or not allowed to upgrade PowerShell. So to help myself release PS v2.0 compatible scripts to the general public, I’m going to use this as a living document of the differences between PowerShell 2.0 and 3.0 that I encounter (so it will continue to grow over time; read as, bookmark it). Of course there are other sites that have some of this info, but I’m going to try and compile a list of the ones that are relevant to me, in a nice simple format.

Before we get to the differences, here are some things you may want to know relating to PowerShell versions.

## How to check which version of PowerShell you are running

All PS versions:

```powershell
$PSVersionTable.PSVersion
```

## How to run/test your script against an older version of PowerShell ([source](http://technet.microsoft.com/en-us/library/hh847899.aspx))

All PS versions: use **PowerShell.exe –Version [version]** to start a new PowerShell session, where [version] is the PowerShell version that you want the session to use, then run your script in this new session. Shorthand is **PowerShell –v [version]**

```powershell
PowerShell.exe -Version 2.0
```

Note: [You can’t run PowerShell ISE in an older version of PowerShell](http://stackoverflow.com/questions/18919862/start-powershell-ise-with-the-2-0-runtime); only the Windows PowerShell console.

## PowerShell v2 and v3 Differences

### Where-Object no longer requires braces ([source](http://blogs.technet.com/b/heyscriptingguy/archive/2012/08/20/my-five-favorite-powershell-3-0-tips-and-tricks.aspx))

PS v2.0:

```powershell
Get-Service | Where { $_.Status -eq 'running' }
```

PS v3.0:

```powershell
Get-Service | Where Status -eq 'running'
```

PS V2.0 Error Message:

> Where : Cannot bind parameter ‘FilterScript’. Cannot convert the "[PropertyName]" value of the type "[Type]" to type "System.Management.Automation.ScriptBlock".

### Using local variables in remote sessions ([source](http://blogs.technet.com/b/heyscriptingguy/archive/2012/08/20/my-five-favorite-powershell-3-0-tips-and-tricks.aspx))

PS v2.0:

```powershell
$class = "win32_bios"
Invoke-Command -cn dc3 {param($class) gwmi -class $class} -ArgumentList $class
```

PS v3.0:

```powershell
$class = "win32_bios"
Invoke-Command -cn dc3 {gwmi -class $Using:class}
```

### Variable validation attributes ([source](http://blogs.technet.com/b/heyscriptingguy/archive/2012/08/20/my-five-favorite-powershell-3-0-tips-and-tricks.aspx))

PS v2.0: Validation only available on cmdlet/function/script parameters.

PS v3.0: Validation available on cmdlet/function/script parameters, and on variables.

```powershell
[ValidateRange(1,5)][int]$someLocalVariable = 1
```

### Stream redirection ([source](http://technet.microsoft.com/en-us/library/hh847746.aspx))

```text
The Windows PowerShell redirection operators use the following characters to represent each output type:
  *   All output
  1   Success output
  2   Errors
  3   Warning messages
  4   Verbose output
  5   Debug messages

NOTE: The All (*), Warning (3), Verbose (4) and Debug (5) redirection operators were introduced
  in Windows PowerShell 3.0. They do not work in earlier versions of Windows PowerShell.
```

PS v2.0: Could only redirect Success and Error output.

```powershell
# Sends errors (2) and success output (1) to the success output stream.
Get-Process none, Powershell 2>&1
```

PS v3.0: Can also redirect Warning, Verbose, Debug, and All output.

```powershell
# Function to generate each kind of output.
function Test-Output { Get-Process PowerShell, none; Write-Warning "Test!"; Write-Verbose "Test Verbose"; Write-Debug "Test Debug"}

# Write every output stream to a text file.
Test-Output *> Test-Output.txt
```

### Explicitly set parameter set variable values when not defined ([source](http://dans-blog.azurewebsites.net/always-explicitly-set-your-parameter-set-variables-for-powershell-v2-0-compatibility/))

PS v2.0 will throw an error if you try and access a parameter set parameter that has not been defined. The solution is to give it a default value when it is not defined. Specify the Private scope in case a variable with the same name exists in the global scope or an inherited scope:

```powershell
# Default the ParameterSet variables that may not have been set depending on which parameter set is being used.
# This is required for PowerShell v2.0 compatibility.
if (!(Test-Path Variable:Private:SomeStringParameter)) { $SomeStringParameter = $null }
if (!(Test-Path Variable:Private:SomeIntegerParameter)) { $SomeIntegerParameter = 0 }
if (!(Test-Path Variable:Private:SomeSwitchParameter)) { $SomeSwitchParameter = $false }
```

PS v2.0 Error Message:

> The variable ‘$[VariableName]’ cannot be retrieved because it has not been set.

### Parameter attributes require the equals sign

PS v2.0:

```powershell
[parameter(Position=1,Mandatory=$true)] [string] $SomeParameter
```

PS v3.0:

```powershell
[parameter(Position=1,Mandatory)] [string] $SomeParameter
```

PS v2.0 Error Message:

> The "=" operator is missing after a named argument.

### Cannot use String.IsNullOrWhitespace (or any other post .Net 3.5 functionality)

PS v2.0:

```powershell
[string]::IsNullOrEmpty($SomeString)
```

PS v3.0:

```powershell
[string]::IsNullOrWhiteSpace($SomeString)
```

PS v2.0 Error Message:

> IsNullOrWhitespace : Method invocation failed because [System.String] doesn’t contain a method named ‘IsNullOrWhiteSpace’.

PS v2.0 compatible version of IsNullOrWhitespace function:

```powershell
# PowerShell v2.0 compatible version of [string]::IsNullOrWhitespace.
function StringIsNullOrWhitespace([string] $string)
{
    if ($string -ne $null) { $string = $string.Trim() }
    return [string]::IsNullOrEmpty($string)
}
```

### Get-ChildItem cmdlet’s –Directory and –File switches were introduced in PS v3.0

PS v2.0:

```powershell
Get-ChildItem -Path $somePath | Where-Object { $_.PSIsContainer }   # Get directories only.
Get-ChildItem -Path $somePath | Where-Object { !$_.PSIsContainer }  # Get files only.
```

PS v3.0:

```powershell
Get-ChildItem -Path $somePath -Directory
Get-ChildItem -Path $somePath -File
```

### Other Links

- [What’s New in Windows PowerShell](http://technet.microsoft.com/en-us/library/hh857339.aspx)
