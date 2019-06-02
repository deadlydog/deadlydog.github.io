---
id: 374
title: Accessing PowerShell Properties and Variables with Periods (and other special characters) in their Name
date: 2013-09-05T13:09:32-06:00
guid: http://dans-blog.azurewebsites.net/?p=374
permalink: /accessing-powershell-variables-with-periods-in-their-name/
categories:
  - PowerShell
tags:
  - Dot
  - Name
  - Period
  - PowerShell
  - Property
  - Registry
  - Special Character
  - Variable
---

## TL;DR

If your PowerShell variable name contains special characters, wrap it in curly braces to get/set its value. If your PowerShell property name contains special characters, wrap it in double quotes:

```powershell
# Variable name with special characters
$VariableName.That.Contains.Periods     # This will NOT work.
${VariableName.That.Contains.Periods}   # This will work.

$env:ProgramFiles(x86)      # This will NOT work, because parentheses are special characters.
${env:ProgramFiles(x86)}    # This will work.

# Property name with special characters
$SomeObject.APropertyName.That.ContainsPeriods      # This will NOT work.
$SomeObject.{APropertyName.That.ContainsPeriods}    # This will work.
$SomeObject.'APropertyName.That.ContainsPeriods'    # This will also work.
$SomeObject."APropertyName.That.ContainsPeriods"    # This will work too.

# Property name with special characters stored in a variable
$APropertyNameWithSpecialCharacters = 'APropertyName.That.ContainsPeriods'
$SomeObject.$APropertyNameWithSpecialCharacters     # This will NOT work.
$SomeObject.{$APropertyNameWithSpecialCharacters}   # This will NOT work.
$SomeObject.($APropertynameWithSpecialCharacters)   # This will work.
$SomeObject."$APropertynameWithSpecialCharacters"   # This will also work.
$SomeObject.'$APropertynameWithSpecialCharacters'   # This will NOT work.
```

## More Information

I was recently working on a powershell script to get the values of some entries in the registry. This is simple enough:

```powershell
Get-ItemProperty -Path 'HKCU:\Software\Microsoft\VisualStudio\11.0_Config\TeamFoundation\SourceControl\Checkin Policies' -Name 'TF.iQmetrix.CheckinPolicies'
```

If we run this command, this is what we get back:

```powershell
TF.iQmetrix.CheckinPolicies : C:\Users\Dan Schroeder\AppData\Local\Microsoft\VisualStudio\11.0\Extensions\mwlu1noz.4t5\TF.iQmetrix.CheckinPolicies.dll
PSPath                      : Microsoft.PowerShell.Core\Registry::HKEY_CURRENT_USER\Software\Microsoft\VisualStudio\11.0_Config\TeamFoundation\SourceControl\Checkin Policies
PSParentPath                : Microsoft.PowerShell.Core\Registry::HKEY_CURRENT_USER\Software\Microsoft\VisualStudio\11.0_Config\TeamFoundation\SourceControl
PSChildName                 : Checkin Policies
PSDrive                     : HKCU
PSProvider                  : Microsoft.PowerShell.Core\Registry
```

So the actual value I’m after is stored in the "TF.iQmetrix.CheckinPolicies" property of the object returned by `Get-ItemProperty`; notice that this property name has periods in it. So let’s store this object in a variable to make it easier to access it’s properties, and do a quick `Get-Member` on it just to show some more details:

```powershell
$RegistryEntry = Get-ItemProperty -Path 'HKCU:\Software\Microsoft\VisualStudio\11.0_Config\TeamFoundation\SourceControl\Checkin Policies' -Name 'TF.iQmetrix.CheckinPolicies'
$RegistryEntry | Get-Member
```

And this is what `Get-Member` shows us:

```powershell
   TypeName: System.Management.Automation.PSCustomObject

Name                        MemberType   Definition
----                        ----------   ----------
Equals                      Method       bool Equals(System.Object obj)
GetHashCode                 Method       int GetHashCode()
GetType                     Method       type GetType()
ToString                    Method       string ToString()
PSChildName                 NoteProperty System.String PSChildName=Checkin Policies
PSDrive                     NoteProperty System.Management.Automation.PSDriveInfo PSDrive=HKCU
PSParentPath                NoteProperty System.String PSParentPath=Microsoft.PowerShell.Core\Registry::HKEY_CURRENT_USER\Software\Microsoft\VisualStudio\11.0_Config\TeamFoundation\SourceControl
PSPath                      NoteProperty System.String PSPath=Microsoft.PowerShell.Core\Registry::HKEY_CURRENT_USER\Software\Microsoft\VisualStudio\11.0_Config\TeamFoundation\SourceControl\Checkin Policies
PSProvider                  NoteProperty System.Management.Automation.ProviderInfo PSProvider=Microsoft.PowerShell.Core\Registry
TF.iQmetrix.CheckinPolicies NoteProperty System.String TF.iQmetrix.CheckinPolicies=C:\Users\Dan Schroeder\AppData\Local\Microsoft\VisualStudio\11.0\Extensions\mwlu1noz.4t5\TF.iQmetrix.CheckinPolicies.dll
```

So in PowerShell ISE I type `$RegistryEntry.` and intellisense pops up showing me that TF.iQmetrix.CheckinPolicies is indeed a property on this object that I can access.

![PowerShell ISE Intellisense](/assets/Posts/2013/09/PowerShell-ISE-Intellisense.png)

So I try and display the value of that property to the console using:

```powershell
$RegistryEntry = Get-ItemProperty -Path 'HKCU:\Software\Microsoft\VisualStudio\11.0_Config\TeamFoundation\SourceControl\Checkin Policies' -Name 'TF.iQmetrix.CheckinPolicies'
$RegistryEntry.TF.iQmetrix.CheckinPolicies
```

But nothing is displayed :-(

While PowerShell ISE does color-code the line `$RegistryEntry.TF.iQmetrix.CheckinPolicies` to have the object color different than the property color, if you just look at it in plain text, something clearly looks off about it. How does PowerShell know that the property name is "TF.iQmetrix.CheckinPolicies", and not that "TF" is a property with an "iQmetrix" property on it, with a "CheckinPolicies" property on that. Well, it doesn’t.

I did some Googling and looked on StackOverflow, but couldn’t a solution to this problem. I found slightly related [posts involving environmental variables with periods in their name](http://stackoverflow.com/questions/9984065/cannot-resolve-environment-variables-in-powershell-with-periods-in-them), but that solution did not work in this case. So after some random trial-and-error I stumbled onto the solution. You have to wrap the property name in curly braces:

```powershell
$RegistryEntry.TF.iQmetrix.CheckinPolicies      # This is WRONG. Nothing will be returned.
$RegistryEntry.{TF.iQmetrix.CheckinPolicies}    # This is RIGHT. The property's value will returned.
```

I later refactored my script to store the "TF.iQmetrix.CheckinPolicies" name in a variable and found that I couldn’t use the curly braces anymore. After more trial-and-error I discovered that using parentheses instead works:

```powershell
$EntryName = 'TF.iQmetrix.CheckinPolicies'

$RegistryEntry.$EntryName       # This is WRONG. Nothing will be returned.
$RegistryEntry.{$EntryName}     # This is WRONG. Nothing will be returned.
$RegistryEntry.($EntryName)     # This is RIGHT. The property's value will be returned.
$RegistryEntry."$EntryName"     # This is RIGHT too. The property's value will be returned.
```

So there you have it. If for some reason you have a variable or property name that contains periods, wrap it in curly braces, or parenthesis if you are storing it in a variable.

Hopefully this makes it’s way to the top of the Google search results so you don’t waste as much time on it as I did.

Happy coding!
