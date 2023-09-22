---
title: "PowerShell class definition pros, cons, and performance comparison"
permalink: /PowerShell-class-definition-pros-cons-and-performance-comparison/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - PowerShell
  - Performance
tags:
  - PowerShell
  - Performance
---

There are 3 different approaches that can be taken to using classes and enums in PowerShell.
In this article we will compare the pros, cons, and performance of each approach.

## The 3 approaches

- __PowerShell classes:__ Introduced in PowerShell 5.0, PowerShell has native support for classes.
- __Inline C# classes:__ C# classes defined in a string in PowerShell and compiled and imported at runtime.
- __Compiled assembly C# classes:__ C# classes defined in a .cs file, compiled to a .dll, and the .dll imported at runtime.

### PowerShell class example

```powershell
class Person {
    [string] $Name
    [int] $Age
}
```

[This blog post](https://xainey.github.io/2016/powershell-classes-and-concepts/) is a great overview of PowerShell classes.

### Inline C# class example

```powershell
Add-Type -Language CSharp -TypeDefinition @'
public class Person {
    public string Name { get; set; }
    public int Age { get; set; }
}
'@
```

When using this method, to make editing easier and still get syntax highlighting and intellisense, I recommend putting the C# code in a .cs file and importing it with:

```powershell
[string] $csharpCode = Get-Content -Path "C:\Path\To\Person.cs" -Raw
Add-Type -Language CSharp -TypeDefinition $csharpCode
```

### Compiled assembly C# class example

```csharp
public class Person {
    public string Name { get; set; }
    public int Age { get; set; }
}
```

The C# code is then compiled using Visual Studio / MSBuild / dotnet.exe to create a dll file, and the dll is imported in PowerShell with:

```powershell
Add-Type -Path "C:\Path\To\Person.dll"
```

## Performance comparison

I created [this PowerShell.Experiment.ClassPerformanceComparison repository](https://github.com/deadlydog/PowerShell.Experiment.ClassPerformanceComparison) to test and compare the 3 different ways to define and import classes and enums.
The test defines identical classes that are imported using each method, and the time required to import each class is measured.
It defines a very basic class, and a slightly larger and more complex class, and duplicates each class 3 times just with a different name, so there are 6 classes in total that are imported.
For more details, see the repo and the code.

The results of the test are below:

| Class  | PowerShell Classes | Inline C# Classes | Compiled Assembly C# Classes |
| ------ | ------------------ | ----------------- | ---------------------------- |
| Basic1 | 89ms               | 764ms             | 10ms                         |
| Basic2 | 10ms               | 25ms              | 9ms                          |
| Basic3 | 9ms                | 36ms              | 8ms                          |
| Large1 | 13ms               | 230ms             | 8ms                          |
| Large2 | 11ms               | 55ms              | 8ms                          |
| Large3 | 12ms               | 28ms              | 8ms                          |

### PowerShell class results

The first PowerShell class declared is a little slow to import, but subsequent declarations are much faster.
I suspect this is due to PowerShell loading some assemblies into memory that it needs to import a PowerShell class/enum.
Once those assemblies are loaded, subsequent imports are much faster.

It appears that the size of the class may have a bit of impact on the import time, but it is not significant.

### Inline C# class results

Inline C# classes are very slow to import.
I suspect this is because the C# code is compiled at runtime before being loaded into memory.
The first import is especially slow.
Also, any dependencies that the class references need to be loaded into memory.
The Large class references some types not referenced by the Basic class, so I think that is why the initial Large class import is slow even after the Basic class has already been loaded.

### Compiled assembly C# class results

Compiled assembly C# classes are the fastest to import every time, since the code has been pre-compiled.

## Other considerations

In [this post](https://blog.danskingdom.com/How-and-where-to-properly-define-classes-and-enums-in-your-PowerShell-modules/) I looked at the usability implications of using PowerShell classes vs Inline C# classes.
You can read the post for more details, but the takeaways are:

- If creating a module with PowerShell classes, you _must_ define them directly in the .psm1 file; they cannot be defined in another file and dot-sourced into the .psm1 file.
- If creating a module with PowerShell classes, consumers of the module must use `using module YourModule` instead of `Import-Module YourModule` to be able to reference to the class and enum types, otherwise PowerShell will give an error that it cannot find the type.

C# classes and enums, whether inline or compiled, do not have these limitations.

### PowerShell class considerations

- In addition to the module limitations mentioned above, PowerShell classes were introduced in PowerShell 5.0, so we can't use them if we want to support PowerShell 3.0 and 4.0.

### Inline C# class considerations

- [Windows PowerShell only supports C# 5.0](https://stackoverflow.com/a/40789694/602585), so we can't use any newer language features.

### Compiled assembly C# class considerations

- You must compile the C# classes into an assembly, which is an extra development step.
- The assembly must be compiled against .NET Standard 2.0 so that it can be used in both Windows PowerShell and PowerShell Core.
  This means [it supports C# v7.3](https://learn.microsoft.com/en-us/dotnet/csharp/language-reference/configure-language-version), but not newer features.

See [the list of C# versions and their features](https://learn.microsoft.com/en-us/dotnet/csharp/whats-new/csharp-version-history) to know which C# features you can use.

## My recommendation

If you are not creating a module, but instead just writing a script, I recommend using PowerShell classes.
It avoids context switching between languages and is still very fast to run.

If you are creating a module and don't intend for consumers of the module to use the classes and enums; that is, they will only be referenced internally by your module, then using PowerShell classes may still be fine.

If you are creating a module and intend to expose your classes and enums for consumers to use, then I recommend using C# classes and enums.

If the module load time is not a concern, then inline C# classes may be fine.
If you add many inline C# classes though, it could take several seconds to load the module.

If module load time is a concern, then I recommend using compiled assembly C# classes and enums.
Using a compiled assembly gives you the benefit of compiler checking and being able to use other development tools if you like.
However, it also adds additional development complexity as you need to compile the assembly after any code changes and before you can use it in PowerShell.
This is the approach I took in my [tiPS PowerShell module](https://github.com/deadlydog/PowerShell.tiPS), as it is intended to be added to a user's PowerShell profile and would be loaded every session, so you can look at that module for an example.

## Create your module in C# instead of PowerShell

It is possible to create modules and cmdlets entirely in C# instead of PowerShell.
This gives you all of the benefits that come with writing C#: strongly typed code, compiler checking, great development tools, etc.
For more information on how to do this, [check out my other blog post](https://blog.danskingdom.com/Create-and-test-PowerShell-Core-cmdlets-in-CSharp/).

## Conclusion

In this article we've seen the pros, cons, and performance of the 3 different ways to define and import classes and enums in PowerShell.
I hope this helps you decide which approach is best for your scenario.

If you have any questions or comments, please leave them below.

Happy coding!
