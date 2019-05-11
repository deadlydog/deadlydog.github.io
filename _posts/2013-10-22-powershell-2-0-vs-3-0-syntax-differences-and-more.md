---
id: 552
title: PowerShell 2.0 vs. 3.0 Syntax Differences And More
date: 2013-10-22T11:23:21-06:00
author: deadlydog
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
I’m fortunate enough to work for [a great company](http://www.iqmetrix.com/) that tries to stay ahead of the curve and use newer technologies.&#160; This means that when I’m writing my PowerShell (PS) scripts I typically don’t have to worry about only using PS v2.0 compatible syntax and cmdlets, as all of our PCs have v3.0 (soon to have v4.0).&#160; This is great, until I release these scripts (or snippets from the scripts) for the general public to use; I have to keep in mind that many other people are still stuck running older versions of Windows, or not allowed to upgrade PowerShell.&#160; So to help myself release PS v2.0 compatible scripts to the general public, I’m going to use this as a living document of the differences between PowerShell 2.0 and 3.0 that I encounter (so it will continue to grow over time; read as, bookmark it).&#160; Of course there are other sites that have some of this info, but I’m going to try and compile a list of the ones that are relevant to me, in a nice simple format.

Before we get to the differences, here are some things you may want to know relating to PowerShell versions.

### How to check which version of PowerShell you are running

All PS versions:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:eec700ae-ff7f-4e9e-ad8d-012446939105" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; gutter: false; title: ; notranslate" title="">
$PSVersionTable.PSVersion
</pre>
</div>

&#160;

### How to run/test your script against an older version of PowerShell ([source](http://technet.microsoft.com/en-us/library/hh847899.aspx))

All PS versions:&#160; use **PowerShell.exe –Version [version]** to start a new PowerShell session, where [version] is the PowerShell version that you want the session to use, then run your script in this new session.&#160; Shorthand is **PowerShell –v [version]**

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:d546ad77-ce58-4f54-9f22-e63596597160" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; gutter: false; title: ; notranslate" title="">
PowerShell.exe -Version 2.0
</pre>
</div>

Note: [You can’t run PowerShell ISE in an older version of PowerShell](http://stackoverflow.com/questions/18919862/start-powershell-ise-with-the-2-0-runtime); only the Windows PowerShell console.

&#160;

# PowerShell v2 and v3 Differences:

&#160;

### Where-Object no longer requires braces ([source](http://blogs.technet.com/b/heyscriptingguy/archive/2012/08/20/my-five-favorite-powershell-3-0-tips-and-tricks.aspx))

PS v2.0:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:ff48c6ee-b013-4ef8-9f64-6757f820d050" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; gutter: false; title: ; notranslate" title="">
Get-Service | Where { $_.Status -eq ‘running’ }
</pre>
</div>

PS v3.0:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:b17453c9-40a0-4f73-b82d-d36b53f174cd" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; gutter: false; title: ; notranslate" title="">
Get-Service | Where Status -eq ‘running
</pre>
</div>

PS V2.0 Error Message:

> <font style="background-color: #ffffff">Where : Cannot bind parameter ‘FilterScript’. Cannot convert the “[PropertyName]” value of the type “[Type]” to type “System.Management.Automation.ScriptBlock”.</font>

&#160;

### Using local variables in remote sessions ([source](http://blogs.technet.com/b/heyscriptingguy/archive/2012/08/20/my-five-favorite-powershell-3-0-tips-and-tricks.aspx))

PS v2.0:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:16fa1db7-05da-4367-a9c3-ef5736a85fe5" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; gutter: false; pad-line-numbers: true; title: ; notranslate" title="">
$class = "win32_bios"
Invoke-Command -cn dc3 {param($class) gwmi -class $class} -ArgumentList $class
</pre>
</div>

PS v3.0:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:4095dffb-ddad-4b1b-aae7-61c3840f06d8" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; gutter: false; pad-line-numbers: true; title: ; notranslate" title="">
$class = "win32_bios"
Invoke-Command -cn dc3 {gwmi -class $Using:class}
</pre>
</div>

&#160;

### Variable validation attributes ([source](http://blogs.technet.com/b/heyscriptingguy/archive/2012/08/20/my-five-favorite-powershell-3-0-tips-and-tricks.aspx))

PS v2.0: Validation only available on cmdlet/function/script parameters.

PS v3.0: Validation available on cmdlet/function/script parameters, and on variables.

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:502387f4-4082-48ac-b095-7108c174e4f2" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; gutter: false; pad-line-numbers: true; title: ; notranslate" title="">
[ValidateRange(1,5)][int]$someLocalVariable = 1
</pre>
</div>

&#160;

### Stream redirection ([source](http://technet.microsoft.com/en-us/library/hh847746.aspx))

<pre>The Windows PowerShell redirection operators use the following characters to represent each output type:
        *   All output
        1   Success output
        2   Errors
        3   Warning messages
        4   Verbose output
        5   Debug messages

NOTE: The All (*), Warning (3), Verbose (4) and Debug (5) redirection operators were introduced
       in Windows PowerShell 3.0. They do not work in earlier versions of Windows PowerShell.</pre>

&#160;

PS v2.0: Could only redirect Success and Error output.

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:dc481368-8bf5-445e-ade4-14bfbffa902f" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; gutter: false; title: ; notranslate" title="">
# Sends errors (2) and success output (1) to the success output stream.
Get-Process none, Powershell 2&gt;&1
</pre>
</div>

PS v3.0: Can also redirect Warning, Verbose, Debug, and All output.

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:0c1b5ff4-2985-4831-98d5-56afcf879d46" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; gutter: false; title: ; notranslate" title="">
# Function to generate each kind of output.
function Test-Output { Get-Process PowerShell, none; Write-Warning "Test!"; Write-Verbose "Test Verbose"; Write-Debug "Test Debug"}

# Write every output stream to a text file.
Test-Output *&gt; Test-Output.txt

</pre>
</div>

&#160;

### Explicitly set parameter set variable values when not defined ([source](http://dans-blog.azurewebsites.net/always-explicitly-set-your-parameter-set-variables-for-powershell-v2-0-compatibility/))

PS v2.0 will throw an error if you try and access a parameter set parameter that has not been defined.&#160; The solution is to give it a default value when it is not defined. Specify the Private scope in case a variable with the same name exists in the global scope or an inherited scope:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:19b50216-fb40-4017-a0cb-231a296bc9ac" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; gutter: false; pad-line-numbers: true; title: ; notranslate" title="">
# Default the ParameterSet variables that may not have been set depending on which parameter set is being used. This is required for PowerShell v2.0 compatibility.
if (!(Test-Path Variable:Private:SomeStringParameter)) { $SomeStringParameter = $null }
if (!(Test-Path Variable:Private:SomeIntegerParameter)) { $SomeIntegerParameter = 0 }
if (!(Test-Path Variable:Private:SomeSwitchParameter)) { $SomeSwitchParameter = $false }
</pre>
</div>

PS v2.0 Error Message:

> The variable ‘$[VariableName]’ cannot be retrieved because it has not been set.

&#160;

### Parameter attributes require the equals sign

PS v2.0:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:dfe8d41b-0e0a-4926-a4b2-740e2ef610b7" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; gutter: false; pad-line-numbers: true; title: ; notranslate" title="">
[parameter(Position=1,Mandatory=$true)] [string] $SomeParameter
</pre>
</div>

PS v3.0:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:84c42572-dc4c-42cd-9829-7d46d110fd47" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; gutter: false; title: ; notranslate" title="">
[parameter(Position=1,Mandatory)] [string] $SomeParameter
</pre>
</div>

PS v2.0 Error Message:

> <font style="background-color: #ffffff">The “=” operator is missing after a named argument.</font>

&#160;

### Cannot use String.IsNullOrWhitespace (or any other post .Net 3.5 functionality)

PS v2.0:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:5c520c9f-453f-485c-a5b8-9ddc7f77c3e4" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title="">
[string]::IsNullOrEmpty($SomeString)
</pre>
</div>

PS v3.0:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:695d97aa-84f5-479b-a67c-49399b3ae7e8" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; title: ; notranslate" title="">
[string]::IsNullOrWhiteSpace($SomeString)
</pre>
</div>

PS v2.0 Error Message:

> IsNullOrWhitespace : Method invocation failed because [System.String] doesn’t contain a method named ‘IsNullOrWhiteSpace’.

PS v2.0 compatible version of IsNullOrWhitespace function:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:efffa5d2-21a2-40cb-be39-c471c04ab806" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; title: ; notranslate" title="">
# PowerShell v2.0 compatible version of [string]::IsNullOrWhitespace.
function StringIsNullOrWhitespace([string] $string)
{
    if ($string -ne $null) { $string = $string.Trim() }
    return [string]::IsNullOrEmpty($string)
}
</pre>
</div>

&#160;

### Get-ChildItem cmdlet’s –Directory and –File switches were introduced in PS v3.0

PS v2.0:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:327b97a7-30e4-4d77-bd2b-4f906c496c21" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; title: ; notranslate" title="">
Get-ChildItem -Path $somePath | Where-Object { $_.PSIsContainer }	# Get directories only.
Get-ChildItem -Path $somePath | Where-Object { !$_.PSIsContainer }	# Get files only.
</pre>
</div>

PS v3.0:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:d7803f09-1d3d-455e-a365-50ea3926564f" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; gutter: false; pad-line-numbers: true; title: ; notranslate" title="">
Get-ChildItem -Path $somePath -Directory
Get-ChildItem -Path $somePath -File
</pre>
</div>

&#160;

&#160;

### Other Links

  * [What’s New in Windows PowerShell](http://technet.microsoft.com/en-us/library/hh857339.aspx)
