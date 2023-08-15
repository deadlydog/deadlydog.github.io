---
title: "Where to properly define classes and enums in your PowerShell modules"
permalink: /Where-to-properly-define-classes-and-enums-in-your-PowerShell-modules/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - PowerShell
tags:
  - PowerShell
---

I recently created a PowerShell module that defines classes and enums.
Everything worked fine locally, but broke when I tested the module on a build server.
This was odd, as the module did not have any dependencies.

In this post I'll explain what I did, and how to properly define classes and enums in your PowerShell modules.

## TL;DR: Just tell me the proper way to do it

Define your classes and enums directly in the `.psm1` file.
Do _NOT_ define them in a separate file and include them in the psm1 file via dot-sourcing or any other method.

When importing a module that uses classes or enums into your scripts, use the `using module` command ([MS docs](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.core/about/about_using?view=powershell-7.3#module-syntax)), not `Import-Module`.

If you do not follow these rules, then you may run into build or runtime errors like:

> Unable to find type [MyClass/MyEnum]

## What is a PowerShell class and enum?

Let's ensure we are on the same page with regard to PowerShell classes and enums.
Here is an example of a basic PowerShell class definition:

```powershell
class MyClass {
    [string] $Name
    [int] $Age
}
```

And an example of a PowerShell enum definition:

```powershell
enum MyEnum {
    Value1
    Value2
}
```

See [this amazing blog post on PowerShell classes](https://xainey.github.io/2016/powershell-classes-and-concepts/) for more information about how to use them.
These is also [this blog post](https://stephanevg.github.io/powershell/class/module/DATA-How-To-Write-powershell-Modules-with-classes/) that goes over some of the pros and cons of using classes.

I mostly use classes for passing around strongly-typed data objects (rather than a hashtable or PSCustomObject), so I can guarantee consistency between all of the objects.
I love using enums where possible for properties with a limited set of specific values, as it makes the code more readable and less error-prone, and PowerShell offers autocompletion when using them.

## The problem

Rather than defining all of my functions directly in the module's `psm1` file and ending up with 2000+ line file, I thought I would follow a common code organization convention and define each of my functions in a separate file, as mentioned in [this blog post](https://tomasdeceuninck.github.io/2018/04/17/PowerShellModuleStructure.html)
This has a number of benefits, such as making it easier to find the code you are looking for and reducing merge conflicts.

Similarly, I decided to put each of my `class` and `enum` definitions in their own files.
[This open-source PowerShell module template](https://github.com/MSAdministrator/TemplatePowerShellModule) follows the convention of putting each function and type in their own file, and [showed how to include class files](https://github.com/MSAdministrator/TemplatePowerShellModule/blob/8e5510fc2f172c59f06fa91d4c4a754fd521ed0a/TemplatePowerShellModule/ModuleName.psm1#L1) with the `using module` command.
So following that example, I put my classes and enums in their own files and included them in my module's `psm1` file with the `using module` command.
I created some Pester tests and verified that everything worked properly.

My next step was to setup a CI/CD pipeline to automatically build and publish my module to the PowerShell Gallery.
I created a GitHub Action workflow to run all of the Pester tests before packaging up the module.
Strangely, the tests failed with the following error:

> Unable to find type [MyClass/MyEnum]

## Reaching out for help and experimenting




## The results



## Conclusion




If you use Import-Module you can use the class/enum in function parameters, and autocomplete will work. Even with it defined in the psm1 file though, you cannot define a variable of the class/enum type. You need to use `using module` for that.

If you use dot-sourcing, you can use the class/enum type as output from your functions, but it cannot be used for parameters of any functions; you get the `Unable to find type` error.
