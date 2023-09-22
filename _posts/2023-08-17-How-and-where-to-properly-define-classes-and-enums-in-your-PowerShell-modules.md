---
title: "How and where to properly define classes and enums in your PowerShell modules"
permalink: /How-and-where-to-properly-define-classes-and-enums-in-your-PowerShell-modules/
#date: 2099-01-15T00:00:00-06:00
last_modified_at: 2023-09-22
comments_locked: false
toc: false
categories:
  - PowerShell
tags:
  - PowerShell
---

I recently created a PowerShell script module that defines classes and enums.
Everything worked fine locally, but broke when I tested the module on a build server.
This was odd, as the module did not have any dependencies.

In this post I'll explain what I did, how to properly define PowerShell classes and enums in your PowerShell script modules to avoid issues for yourself and consumers of your modules, and why you may want to consider using C# classes and enums instead.

## TL;DR: Just tell me the proper way to do it

With the current latest PowerShell version, v7.3.6, there are still nuances to using PowerShell native classes and enums in modules.
The easiest way to avoid the issues they pose is to simply not use them.
Instead, use C# classes and enums in your PowerShell modules.

C# classes and enums:

- Can be defined in their own files and dot-sourced into the psm1 file.
- Allow module consumers to use `Import-Module` and still have full access to the class/enum types.

If you decide to still use PowerShell native classes and enums:

1. Define your classes and enums directly in the `.psm1` file.
   Do _NOT_ define them in a separate file and include them in the psm1 file via dot-sourcing or any other method.
1. When importing a module that uses classes or enums into your scripts, use the `using module` command ([MS docs](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.core/about/about_using#module-syntax)), not `Import-Module`.

If you do not follow these 2 rules with PowerShell native classes/enums, then you may run into build or runtime errors like:

> Unable to find type [MyClass/MyEnum]

I also discuss the pros and cons of the different ways to define classes and enums in PowerShell [in this post](https://blog.danskingdom.com/PowerShell-class-definition-pros-cons-and-performance-comparison/).

## What is a PowerShell native class and enum?

Let's ensure we are on the same page with regard to PowerShell native [classes](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.core/about/about_classes) and [enums](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.core/about/about_enum).
PowerShell native classes and enums were introduced in PowerShell 5, and are a way to define strongly-typed objects in PowerShell.

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

I mostly use classes for passing around strongly-typed data objects (rather than a `hashtable` or `PSCustomObject`), so I can guarantee consistency between all of the objects.

I love using enums where possible for properties with a limited set of specific values, as it makes the code more readable and less error-prone, and PowerShell offers autocompletion when using them.

## Backstory: How I defined classes in my module and encountered problems

Rather than defining all of my functions directly in the module's `psm1` file and ending up with 2000+ line file, I thought I would follow a common code organization convention and define each of my functions in a separate file, as mentioned in [this blog post](https://tomasdeceuninck.github.io/2018/04/17/PowerShellModuleStructure.html).
This has a number of benefits, such as making it easier to find the code you are looking for and reducing merge conflicts.

Similarly, I decided to put each of my `class` and `enum` definitions in their own files.
[This open-source PowerShell module template](https://github.com/MSAdministrator/TemplatePowerShellModule) follows the convention of putting each function and type in their own file, and [showed how to include class files](https://github.com/MSAdministrator/TemplatePowerShellModule/blob/8e5510fc2f172c59f06fa91d4c4a754fd521ed0a/TemplatePowerShellModule/ModuleName.psm1#L1) with the `using module` command.
So following that example, I put my classes and enums in their own files and included them in my module's `psm1` file with the `using module` command.
I created some Pester tests and verified that everything worked properly.

My next step was to setup a CI/CD pipeline to automatically build and publish my module to the PowerShell Gallery.
I created a GitHub Action workflow to run all of the Pester tests before packaging up the module.
Strangely, the tests failed with the following error:

> Unable to find type [MyClass/MyEnum]

## Experimenting and reaching out for help

It was very strange that the Pester tests passed on my local machine, but failed on the build server.
I created [this small sample repo](https://github.com/deadlydog/PowerShell.Experiment.ClassInModule) and reproduced the issue.
From there, I created [this Stack Overflow question](https://stackoverflow.com/questions/76886628/powershell-module-with-class-defined-in-separate-file-fails-pester-tests-in-gith) and reached out [on Twitter (X) here](https://twitter.com/deadlydog/status/1690106592182591490?s=20) and [Mastodon here](https://hachyderm.io/@deadlydog/110873007402908403) for help.
The Mastodon PowerShell community is strong and offered some great suggestions and explanations.

## Experiment results

I kept experimenting with [my sample repo](https://github.com/deadlydog/PowerShell.Experiment.ClassInModule), and created a Dev Container to try and eliminate any anomalies that may be due to my local machine.
The tests are explained in more detail in the repo's ReadMe, and you can virw the source code of the tests performed.

PowerShell version 7.2.13 was used to produce these results.
To ensure my local machine was not impacting the results, all results shown below are from running the tests in GitHub Actions, on both Windows and Linux agents.

### Referencing the PowerShell class/enum in the module

To include a class/enum that I created within the module, I tried 3 different methods:

1. With "using module" in the psm1 file: `using module .\Classes\MyClass.psm1`
   - This test is done without using Export-ModuleMember like a real separate module would; it just has the class/enum definition in it.
1. With dot-sourcing in the psm1 file: `. "$PSScriptRoot\Classes\MyClass.ps1`
1. Defining the class/enum directly in the psm1 file, instead of in its own file.

The results of using the different methods to reference a PowerShell native class/enum in the script module are as follows:

|                                              | Class/Enum can be used by module functions | Class/Enum type can be used outside of module |
| -------------------------------------------- | ------------------------------------------ | --------------------------------------------- |
| Class/Enum file included with `using module` | ❌                                          | ❌                                             |
| Class/Enum file included with dot-sourcing   | ✔️                                          | ❌                                             |
| Class/Enum defined in the psm1 file          | ✔️                                          | ✔️                                             |

If I use `using module` to include the file with the class defined in it, then the class cannot be used by the module functions, and the class type cannot be used outside of the module.
Simply put, it does not work at all (strangely though it worked when testing on my local machine 🤷‍♂️, so it seems unreliable at best).

If I dot-source the file with the class, then the class can be used by the module functions, but the class type cannot be referenced outside of the module.
Anytime the class name is referenced you get the `Unable to find type` error.

If I define the class in the psm1 file, then the class can be used by the module functions, and the class type can be used outside of the module.

Enums behaved the same as classes in all of the tests that were performed.

### Referencing the module from a script

I also tested the 2 different ways a module can be imported into a script; with `Import-Module` and `using module`.
An important distinction between the two is that `Import-Module` is a cmdlet, while `using module` is a language keyword, like `if` or `foreach`.
The two are fundamentally different, and behave differently when importing modules.

The results below assume the class/enum is referenced directly in the psm1 file for script modules, as that is the recommended approach to take after seeing the results from the previous section.

|                                      | Class/Enum can be used by module functions | Class/Enum type can be used outside of module |
| ------------------------------------ | ------------------------------------------ | --------------------------------------------- |
| Module imported with `Import-Module` | ✔️                                          | ❌                                             |
| Module imported with `using module`  | ✔️                                          | ✔️                                             |

If you use `Import-Module` to import the module, you can use the class/enum values implicitly, and autocomplete will work.
By implicitly, I mean that you can retrieve a class/enum instance from a module function, pass the instance around, modify the class properties, and pass it back into module function parameters.

You cannot use the class/enum type explicitly outside of the module though.
That is, you cannot create a new instance of the class, or reference the enum values directly, such as performing a `switch` statement on them.
As soon as you need to reference the class/enum name in your script (e.g. `[MyClass]` or `[MyEnum]`), you will get the `Unable to find type` error.

The only way to be able to reference the class/enum name outside of the module is to import the module with `using module`.
I did not explicitly test this using a binary module, but I expect the results would be the same.

### Use a C# class/enum in the module instead

Rather than using the PowerShell native classes and enums, we can define C# classes and enums inline in PowerShell as a string.
This even works in pre-PowerShell 5 versions.
It is a bit ugly, as you lose syntax highlighting and editor intellisense and checks, and you need to write the code as C# instead of PowerShell, but it does work.

Here is what the equivalent definition of the example PowerShell native class and enum shown earlier would look like when defining them as a C# class and enum in your PowerShell script or module:

```csharp
Add-Type -Language CSharp -TypeDefinition @"
  public class MyClass {
      public string Name { get; set; }
      public int Age { get; set; }
  }

  public enum MyEnum {
      Value1,
      Value2
  }
"@
```

We could optionally put our class and enum in a namespace as well (e.g. `MyNamespace`), and then reference their types in PowerShell like `[MyNamespace.MyClass]` and `[MyNamespace.MyEnum]`.
Using namespaces can help avoid naming conflicts for common class names.

Using C# classes and enums as shown above instead of the PowerShell native class/enum, the results are as follows:

|                                              | C# Class/Enum can be used by module functions | C# Class/Enum type can be used outside of module |
| -------------------------------------------- | --------------------------------------------- | ------------------------------------------------ |
| Class/Enum file included with `using module` | ✔️                                             | ✔️                                                |
| Class/Enum file included with dot-sourcing   | ✔️                                             | ✔️                                                |
| Class/Enum defined in the psm1 file          | ✔️                                             | ✔️                                                |

|                                      | C# Class/Enum can be used by module functions | C# Class/Enum type can be used outside of module |
| ------------------------------------ | --------------------------------------------- | ------------------------------------------------ |
| Module imported with `Import-Module` | ✔️                                             | ✔️                                                |
| Module imported with `using module`  | ✔️                                             | ✔️                                                |

You can see that using C# classes/enums is much more flexible than using the PowerShell native classes/enums.
They allow us to define the classes/enums in their own files, and allow end-users to use `Import-Module` and still have full access to the class/enum types.
The downsides are that they are a bit ugly as inline strings, and you need to know the C# syntax instead of PowerShell syntax.

## Related information

### PSFramework

[PSFramework](https://psframework.org) has some class-specific export options that you may be able to leverage when using PSFramework, as described in [this message](https://ruhr.social/@callidus2000/110876292213359073).
Even those solutions are not perfect though, as described in [this reply](https://fosstodon.org/@jaykul/110879072765963081).
I do not like having to depend on additional modules unless necessary, and prefer to use the PowerShell native methods since they will work everywhere.

### PowerShell documentation

The PowerShell [class docs](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.core/about/about_classes?view=powershell-7.3#importing-classes-from-a-powershell-module) and [using docs](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.core/about/about_using?view=powershell-7.3&source=docs#module-syntax) say:

> The using module statement imports classes from the root module (ModuleToProcess) of a script module or binary module.
> It doesn't consistently import classes defined in nested modules or classes defined in scripts that are dot-sourced into the module.
> Classes that you want to be available to users outside of the module should be defined in the root module.

Knowing what I know now, this makes sense.
When I first read it though, I thought when it said "the module" and "root module" it just meant any files in the module directory that may get pulled into the psm1 file, not specifically just the psm1 file.
I created [this PR](https://github.com/MicrosoftDocs/PowerShell-Docs/pull/10343) to clarify this in the docs.

### PowerShell classes are a bit of a mess

As you can see from the need of this article, as well as all of the possible answers to [this Stack Overflow question](https://stackoverflow.com/questions/31051103/how-to-export-a-class-in-a-powershell-v5-module) on how to export classes from a module, working with PowerShell native classes in modules is not as clear and straightforward as it could be.

Also, old class versions are kept in memory until the PowerShell session is restarted, which can make developing with classes a bit of a pain.
[Checkout this post](https://blog.danskingdom.com/Automatically-create-new-sessions-to-improve-PowerShell-development-with-classes-in-VS-Code/) for how to have VS Code automatically create a new PowerShell session for you when you debug your script, so you don't have to manually restart your session every time you make a change to your class.

Classes and enums are extremely useful, so I hope that PowerShell will continue to improve the experience of using them.

### Create your module in C# instead of PowerShell

It is possible to create modules and cmdlets entirely in C# instead of PowerShell.
This allows you to structure all of your code files however you want.
For more information on how to do this, [check out my other blog post](https://blog.danskingdom.com/Create-and-test-PowerShell-Core-cmdlets-in-CSharp/).

### PowerShell class definition comparison

I discuss the pros and cons of the different ways to define classes and enums in PowerShell [in this post](https://blog.danskingdom.com/PowerShell-class-definition-pros-cons-and-performance-comparison/).

## Conclusion

In this post I've shown that to avoid headaches when using PowerShell native classes or enums in your PowerShell script modules, you should always:

1. Define the class/enum directly in the `.psm1` file.
1. Import the module with `using module` instead of `Import-Module`.

If you do not want to deal with the limitations of using PowerShell native classes/enums in your modules, then you can define them as C# classes/enums instead, avoiding potential problems, allowing you to put each class/enum in their own file, and providing a nicer experience for consumers of your module since they can still use the typical `Import-Module` command.

PowerShell 7.3.6 is the latest version at the time of writing this post.
Due of the nuances around using PowerShell native classes and enums in modules, they don't quite feel like a complete first-class citizen yet.
Hopefully later versions of PowerShell will improve the language to make using PowerShell native classes and enums in modules easier and more straightforward with all the benefits of using C# classes/enums.

Happy scripting!
