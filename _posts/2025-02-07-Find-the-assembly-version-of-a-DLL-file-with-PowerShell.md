---
title: "Find the assembly version of a DLL file with PowerShell"
permalink: /Find-the-assembly-version-of-a-DLL-file-with-PowerShell/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - .NET
  - PowerShell
tags:
  - .NET
  - PowerShell
---

Sometimes you cannot find the assembly version of a DLL by simply looking at the file properties in File Explorer.
In this case, you can use PowerShell to find the assembly version of a DLL file.

## TL;DR

Here's the PowerShell command to find the assembly version of a DLL file:

```powershell
[System.Reflection.AssemblyName]::GetAssemblyName("C:\path\to\your.dll")
```

The object returned will contain many properties, with the assembly `Version` and `Name` written the to the terminal by default.

![Using PowerShell to get the assembly version of a DLL file](/assets/Posts/2025-02-07-Find-the-assembly-version-of-a-DLL-file-with-PowerShell/use-powershell-to-get-assembly-version.png)

Since we are just calling a .NET method, you could also do this as C# code in a console app.

```csharp
var assembly = System.Reflection.AssemblyName.GetAssemblyName("C:\\path\\to\\your.dll");
System.Console.WriteLine(assembly.Version);
```

## Background

I was having a problem where the version of the `System.Memory.dll` assembly being copied to my application artifacts was different than the version referenced by my NuGet packages, causing my application to crash at runtime.
NuGet automatically included an assembly binding redirect in my `app.config` file, but the version in the binding redirect was not the same as the version of the assembly being copied into the artifacts.
The assembly binding redirects were referencing version `4.0.2.0`, but the version of the assembly being copied into the artifacts was `4.0.1.2` (although I didn't know this yet).

Here is the assembly binding redirect that was automatically added to my `app.config` file by NuGet:

```xml
<assemblyBinding xmlns="urn:schemas-microsoft-com:asm.v1">
  <dependentAssembly>
    <assemblyIdentity name="System.Memory" publicKeyToken="cc7b13ffcd2ddd51" culture="neutral" />
    <bindingRedirect oldVersion="0.0.0.0-4.0.2.0" newVersion="4.0.2.0" />
  </dependentAssembly>
</assemblyBinding>
```

Often times you can find the assembly version of a DLL by simply looking at the file properties in File Explorer, under the `Details` tab.
However, in this case, neither version of the `System.Memory.dll` assemblies was showing their proper assembly version in the file properties.

![The file properties of both versions of System.Memory.dll](/assets/Posts/2025-02-07-Find-the-assembly-version-of-a-DLL-file-with-PowerShell/system.memory.dll-file-properties.png)

You can see that the `File version` and `Product version` fields of the assemblies in the NuGet package and in the artifact are not showing the actual assembly version of the DLL; either `4.0.2.0` or `4.0.1.2`.

So I could see that the assembly versions were different, but wasn't sure what the actual assembly versions were in order to update the assembly binding redirect.

PowerShell to the rescue!

![Using PowerShell to get the assembly versions of both assemblies](/assets/Posts/2025-02-07-Find-the-assembly-version-of-a-DLL-file-with-PowerShell/use-powershell-to-get-both-assembly-versions.png)

Now that I knew the actual assembly versions, I could update the assembly binding redirect in my `app.config` file to match the version of the assembly being copied into the artifacts.
Specifically, `newVersion="4.0.1.2`.

```xml
<assemblyBinding xmlns="urn:schemas-microsoft-com:asm.v1">
  <dependentAssembly>
    <assemblyIdentity name="System.Memory" publicKeyToken="cc7b13ffcd2ddd51" culture="neutral" />
    <bindingRedirect oldVersion="0.0.0.0-4.0.2.0" newVersion="4.0.1.2" />
  </dependentAssembly>
</assemblyBinding>
```

This fixed the runtime crash and got everything back on track in time for my release.

### A proper fix

While updating the assembly binding redirect to use the older version of the assembly fixed the issue, it's kind of a hacky solution.
If we update the NuGet package to a newer version, NuGet will automatically update the assembly binding redirect version and things will break again.
A better solution would be to find out why the older version of the assembly was being copied into the artifacts and fix it.

I had already confirmed that all projects in the solution were referencing the same version of the `System.Memory` NuGet package, so I knew that wasn't the issue.
So I decided to build my projects and then use PowerShell to find all of the `System.Memory.dll` assemblies and report their assembly version.

Here is the PowerShell script I used:

```powershell
# Find all System.Memory.dll assemblies in the repository.
[string] $directoryPath = 'C:\MyProjectRepo'
[string[]] $assemblyFilePaths =
  Get-ChildItem -Path $directoryPath -Recurse -Filter 'System.Memory.dll' |
  Select-Object -ExpandProperty FullName

# Get the assembly version of each assembly, and add the file path to the object.
$assemblies = @()
foreach ($filePath in $assemblyFilePaths)
{
  $assembly = [System.Reflection.AssemblyName]::GetAssemblyName($filePath)
  $assembly | Add-Member -MemberType NoteProperty -Name FilePath -Value $filePath
  $assemblies += $assembly
}

# Output the assembly versions and file paths, sorted by version then file path.
$assemblies | Select-Object Version, FilePath | Sort-Object Version, FilePath
```

The output of the script looked something like this:

```plaintext
Version FilePath
------- --------
4.0.1.2 C:\MyProjectRepo\Project1.Tests\bin\Debug\System.Memory.dll
4.0.1.2 C:\MyProjectRepo\Project2.Tests\bin\Debug\System.Memory.dll
4.0.1.2 C:\MyProjectRepo\Project3.Tests\bin\Debug\System.Memory.dll
...
4.0.2.0 C:\MyProjectRepo\Project1\bin\Debug\System.Memory.dll
4.0.2.0 C:\MyProjectRepo\Project2\bin\Debug\System.Memory.dll
4.0.2.0 C:\MyProjectRepo\Project3\bin\Debug\System.Memory.dll
...
```

You can see that all of the test projects were bringing in the older version of the assembly, while the main projects were bringing in the newer version.
The test projects did not reference the `System.Memory` NuGet package, so when they needed the `System.Memory.dll` assembly they must have just been copying the older version from the GAC (Global Assembly Cache).
Then somehow the assembly from one of the test projects was being copied into the artifacts; unfortunately, this particular solution has a non-standard build process.

The solution was to add a reference to the `System.Memory` NuGet package to each of the test projects that needed it so they would bring in the correct version of the assembly.
I then reverted the change I had made to the assembly binding redirect in the `app.config` file, and now it can be automatically managed by NuGet again.

## Conclusion

If you came here just wanting to know how to get the assembly version of a DLL file, I hope the TL;DR got you going quickly.
If you read the entire post, I hope you found the background information helpful and maybe learned something new.

Happy coding!
