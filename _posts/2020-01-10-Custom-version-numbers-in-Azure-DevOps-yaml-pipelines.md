---
title: "Custom version numbers in Azure DevOps yaml pipelines"
permalink: /Custom-version-numbers-in-Azure-DevOps-yaml-pipelines/
#date: 2099-01-17T00:00:00-06:00
last_modified_at: 2022-03-13
comments_locked: false
categories:
  - Azure DevOps
  - Azure Pipelines
  - Build
tags:
  - Azure DevOps
  - Azure Pipelines
  - Build
  - Continuous Integration
  - Versioning
  - Semantic Version
  - Prerelease
---

I'm a fan of [semantic versioning](https://semver.org/), especially for software meant to be consumed by other developers, such as NuGet packages.
If you want to use semantic versioning however, it means you need to have some control over the version number that gets assigned to your software by your build system.
Whether you are looking to use semantic versioning, or want to use some other version number format, in this post we will look at how to accomplish that when using yaml files for you Azure Pipeline.

## Using the classic editor

Before we look at the yaml way, if you've been using Azure DevOps for a while you may have already solved this problem in the classic build pipeline editor.
One way to do this was to use the `$(Rev:r)` syntax in your `Build number format`; for example, using `1.0.0.$(Rev:r)`.

![Azure Pipelines classic editor build number format](/assets/Posts/2020-01-10-Custom-version-numbers-in-Azure-DevOps-yaml-pipelines/AzurePipelinesClassicEditorBuildNumberFormat.png)

The `$(Rev:r)` syntax acts as a variable with an auto-incrementing value, so the first build would be `1.0.0.0`, the next would be `1.0.0.1`, then `1.0.0.2`, and so on.
Once any part of the string to the left of `$(Rev:r)` changes, the counter resets to zero.
So if you changed the Build Number Format to `1.1.0.$(Rev:r)`, the next build would have a value of `1.1.0.0`.

To access the Build Number Format value in your tasks so that you could actually use it, you would use the built-in `$(Build.BuildNumber)` variable.
For example, if you wanted to apply the version to all of your .Net projects before building the assemblies, you could do this:

![Azure Pipelines classic editor using build number format](/assets/Posts/2020-01-10-Custom-version-numbers-in-Azure-DevOps-yaml-pipelines/AzurePipelinesClassicEditorUsingBuildNumberFormat.png)

I am a huge fan of [Richard Fennell's Manifest Versioning Build Tasks Azure DevOps extension](https://marketplace.visualstudio.com/items?itemName=richardfennellBM.BM-VSTS-Versioning-Task), which is what is being used in the above screenshot to version all of the .Net assemblies with our version number.

> NOTE: You'll need to have the extension installed in order to use the `richardfennellBM.BM-VSTS-Versioning-Task.Version-Assemblies-Task.VersionAssemblies@2` task shown in yaml snippets below.
> You may be able to simply use `VersionAssemblies@2`, but it conflicts with other extensions I have installed so I use the fully qualified name here to avoid the ambiguity error.

## Simple yaml solution

Microsoft is moving away from the classic editor and investing in yaml pipelines.
To accomplish the same thing as described in the above classic editor scenario is very easy to do in yaml, and the code would look like this:

```yaml
name: '1.0.0.$(Rev:r)'

steps:
- task: richardfennellBM.BM-VSTS-Versioning-Task.Version-Assemblies-Task.VersionAssemblies@2
  displayName: Version the assemblies
  inputs:
    Path: '$(Build.SourcesDirectory)'
    VersionNumber: '$(Build.BuildNumber)'
    InjectVersion: true
    FilenamePattern: 'AssemblyInfo.*'
    OutputVersion: 'OutputedVersion'
```

In the yaml definition, [the `name` element corresponds to the `Build number format` of the classic editor](https://docs.microsoft.com/en-us/azure/devops/pipelines/process/run-number?view=azure-devops&tabs=yaml), but in both yaml and the classic editor the `$(Build.BuildNumber)` variable is used to access the value.

## A bit more advanced yaml

Having seen the simple yaml solution, there's a few things we should mention:

- The `$(Rev:r)` auto-incrementing syntax is only valid for the `name` element; you cannot use it in any other variables or fields.
- The `name` (i.e. `Build number format`) is what shows up on your Azure Pipeline's build summary page.
If you want to show more information in the build's title, such as the git branch the build was made from or the date it was created, then this solution won't work; something like `1.0.0.1_master_2020-01-15` is not a valid version number that can be assigned to assemblies.

To overcome this problem we can make use of yaml variables and [the `counter` function](https://docs.microsoft.com/en-us/azure/devops/pipelines/process/expressions?view=azure-devops#counter).
This function provides the same functionality as the `$(Rev:r)` syntax, where you give it a prefix and if that prefix changes, the auto-incrementing integer will reset.
In addition, this function let's us set the seed value of the auto-incrementing integer, so we can have it start from something other than zero if we want.

So now we can generate our version number and version our assemblies using the yaml below.
Note that I've switched from a 4 part version number to a 3 part one to show off how you might do semantic versioning.

```yaml
name: '$(BuildDefinitionName)_$(SourceBranchName)_$(Date:yyyyMMdd)_$(Rev:.r)'

variables:
  version.MajorMinor: '1.2' # Manually adjust the version number as needed for semantic versioning. Revision is auto-incremented.
  version.Revision: $[counter(variables['version.MajorMinor'], 0)]
  versionNumber: '$(version.MajorMinor).$(version.Revision)'

steps:
- task: richardfennellBM.BM-VSTS-Versioning-Task.Version-Assemblies-Task.VersionAssemblies@2
  displayName: Version the assemblies
  inputs:
    Path: '$(Build.SourcesDirectory)'
    VersionNumber: '$(versionNumber)'
    InjectVersion: true
    FilenamePattern: 'AssemblyInfo.*'
    OutputVersion: 'OutputedVersion'
```

You can see now that we've introduced some variables:

- `version.MajorMinor` is the one that you would manually adjust.
- `version.Revision` will auto-increment with each build, and reset back to zero when `version.MajorMinor` is changed.
- `versionNumber` is the full 3-part semantic version, and is used in the assembly versioning task.

You may have noticed that the `name` was changed quite a bit as well.
It now shows the name of the build definition, the git branch that the build used, the date the build was made, and a revision number.
The revision number is still appended to ensure that multiple builds made from the same branch on the same day have different names.
There are [some other tokens](https://docs.microsoft.com/en-us/azure/devops/pipelines/process/run-number?view=azure-devops&tabs=yaml#tokens) that `name` supports as well.

## Showing the version number in the build name

One major issue with the yaml solution above, in my opinion, is that the name of the build no longer includes the version number in it.

Unfortunately, getting the version number into the `name` isn't as simple as just doing:

```yaml
name: '$(BuildDefinitionName)_$(SourceBranchName)_$(Date:yyyyMMdd)_$(versionNumber)'
```

This is because our [custom yaml variables are processed at runtime](https://docs.microsoft.com/en-us/azure/devops/pipelines/process/variables?view=azure-devops&tabs=yaml%2Cbatch#understand-variable-syntax), and the `name` is evaluated before then.

To work around this issue, we can use [the UpdateBuildNumber command](https://docs.microsoft.com/en-us/azure/devops/pipelines/scripts/logging-commands?view=azure-devops&tabs=bash#updatebuildnumber-override-the-automatically-generated-build-number), as in the following yaml:

```yaml
name: 'Set dynamically below in a task'

variables:
  version.MajorMinor: '1.2' # Manually adjust the version number as needed for semantic versioning. Revision is auto-incremented.
  version.Revision: $[counter(variables['version.MajorMinor'], 0)]
  versionNumber: '$(version.MajorMinor).$(version.Revision)'

steps:
- task: PowerShell@2
  displayName: Set the name of the build (i.e. the Build.BuildNumber)
  inputs:
    targetType: 'inline'
    script: |
      [string] $buildName = "$(versionNumber)_$(Build.SourceBranchName)"
      Write-Host "Setting the name of the build to '$buildName'."
      Write-Host "##vso[build.updatebuildnumber]$buildName"

- task: richardfennellBM.BM-VSTS-Versioning-Task.Version-Assemblies-Task.VersionAssemblies@2
  displayName: Version the assemblies
  inputs:
    Path: '$(Build.SourcesDirectory)'
    VersionNumber: '$(versionNumber)'
    InjectVersion: true
    FilenamePattern: 'AssemblyInfo.*'
    OutputVersion: 'OutputedVersion'
```

There are a couple things to notice in this yaml.
First, I changed the `name` to indicate that it will be dynamically updated.
Second, I added another task to the steps for setting the name of the build.
I'm a fan of PowerShell so I used a PowerShell task, but you could use Bash too (the syntax would be different though).

In the 3 lines of PowerShell you can see that I create a string of what I want the build name to be.
Here I opted to just include the version number and the git branch the build used, but you could use any of [the other predefined variables](https://docs.microsoft.com/en-us/azure/devops/pipelines/build/variables?view=azure-devops&tabs=yaml) as well.
Notice though that the predefined variables used here (i.e. `$(Build.SourceBranchName)`) is different than those used directly in the `name` element (i.e. `$(SourceBranchName)`), as the `name` only supports special tokens evaluated before runtime.

When the build is first queued, it's name will show up as `Set dynamically below in a task` until the PowerShell step to update it is executed.
Because of this, you may choose to have it show something else, like in the other examples above.
If you do this, I would add a comment to the `name` saying that it gets updated in a task below.

## Creating prerelease version numbers

At the start of this post I mentioned that I'm a fan of semantic versioning.
Part of semantic versioning is supporting prerelease versions.
While not everything supports prerelease versions, such as .Net assemblies, many things do, such as NuGet package versions.

Defining your prerelease version can be as simple as defining a new variable, like so:

```yaml
variables:
  version.MajorMinor: '1.2' # Manually adjust the version number as needed for semantic versioning. Revision is auto-incremented.
  version.Revision: $[counter(variables['version.MajorMinor'], 0)]
  versionNumber: '$(version.MajorMinor).$(version.Revision)'
  prereleaseVersionNumber: '$(versionNumber)-$(Build.SourceVersion)'
```

Where `$(Build.SourceVersion)` is the git commit SHA being built.

I typically like to include the date and time in my prerelease version number.
Unfortunately, there isn't a predefined variable that can be used to access the current date and time, so it takes a bit of extra effort.

Here is some yaml code that I typically use for my prerelease versions:

```yaml
variables:
  version.MajorMinor: '1.2' # Manually adjust the version number as needed for semantic versioning. Revision is auto-incremented.
  version.Revision: $[counter(variables['version.MajorMinor'], 0)]
  versionNumber: '$(version.MajorMinor).$(version.Revision)'
  prereleaseVersionNumber: 'Set dynamically below in a task'

steps:
- task: PowerShell@2
  displayName: Set the prereleaseVersionNumber variable value
  inputs:
    targetType: 'inline'
    script: |
      [string] $dateTime = (Get-Date -Format 'yyyyMMddTHHmmss')
      [string] $prereleaseVersionNumber = "$(versionNumber)-ci$dateTime"
      Write-Host "Setting the prerelease version number variable to '$prereleaseVersionNumber'."
      Write-Host "##vso[task.setvariable variable=prereleaseVersionNumber]$prereleaseVersionNumber"

- task: VersionPowerShellModule@2
  displayName: Update PowerShell Module Manifests version for Prerelease version
  inputs:
    Path: 'powerShell/Module/Directory/Path'
    VersionNumber: '$(prereleaseVersionNumber)'
    InjectVersion: true
```

Here I've introduced a new `prereleaseVersionNumber` variable, as well as a PowerShell task step to set it.
The first line of the PowerShell gets the date and time in a format acceptable for prerelease semantic versions.
The second line then builds the complete prerelease version number, appending `-ci$dateTime` to the regular version number.
I use `ci` to indicate that it's from a continuous integration build, but you don't need to.
The fourth line then assigns the value back to the `prereleaseVersionNumber` yaml variable so it can be used in later tasks.

In this example I'm using the prereleaseVersionNumber to version a PowerShell module, as it supports prerelease version numbers.

## Extras

If you need a unique ID in your version number, you can use the `$(Build.BuildId)` [predefined variable](https://docs.microsoft.com/en-us/azure/devops/pipelines/build/variables?view=azure-devops&tabs=yaml).
This is an auto-incrementing integer that Azure DevOps increments after any build in your Azure DevOps organization; not just in your specific build pipeline.
No two builds created in your Azure DevOps should ever have the same Build ID.

## Ready to use code

Above I've shown you a few different variations of ways to do version numbers in yaml templates.
Hopefully I explained it well enough that you understand how to customize it for your specific needs.
That said, here's a few code snippets that are ready for direct copy-pasting into your yaml files, where you can then use the variables in any pipeline tasks.

Specifying a 3-part version number with an auto-incrementing revision:

```yaml
name: 'Set dynamically below in a task'

variables:
  version.MajorMinor: '1.0' # Manually adjust the version number as needed for semantic versioning. Revision is auto-incremented.
  version.Revision: $[counter(variables['version.MajorMinor'], 0)]
  versionNumber: '$(version.MajorMinor).$(version.Revision)'

steps:
- task: PowerShell@2
  displayName: Set the name of the build (i.e. the Build.BuildNumber)
  inputs:
    targetType: 'inline'
    script: |
      [string] $buildName = "$(versionNumber)_$(Build.SourceBranchName)"
      Write-Host "Setting the name of the build to '$buildName'."
      Write-Host "##vso[build.updatebuildnumber]$buildName"
```

Specifying a 4-part version number with an auto-incrementing revision:

```yaml
name: 'Set dynamically below in a task'

variables:
  version.MajorMinor: '1.0' # Manually adjust the version number as needed. Revision is auto-incremented.
  version.Revision: $[counter(variables['version.MajorMinor'], 0)]
  versionNumber: '$(version.MajorMinor).$(version.Revision).$(Build.BuildId)'

steps:
- task: PowerShell@2
  displayName: Set the name of the build (i.e. the Build.BuildNumber)
  inputs:
    targetType: 'inline'
    script: |
      [string] $buildName = "$(versionNumber)_$(Build.SourceBranchName)"
      Write-Host "Setting the name of the build to '$buildName'."
      Write-Host "##vso[build.updatebuildnumber]$buildName"
```

Specifying a 3-part version number with an auto-incrementing revision, along with a prerelease version number that includes the date and time of the build and the Git commit SHA:

```yaml
name: 'Set dynamically below in a task'

variables:
  version.MajorMinor: '1.0' # Manually adjust the version number as needed for semantic versioning. Revision is auto-incremented.
  version.Revision: $[counter(variables['version.MajorMinor'], 0)]
  versionNumber: '$(version.MajorMinor).$(version.Revision)'
  prereleaseVersionNumber: 'Set dynamically below in a task'

steps:
- task: PowerShell@2
  displayName: Set the name of the build (i.e. the Build.BuildNumber)
  inputs:
    targetType: 'inline'
    script: |
      [string] $buildName = "$(versionNumber)_$(Build.SourceBranchName)"
      Write-Host "Setting the name of the build to '$buildName'."
      Write-Host "##vso[build.updatebuildnumber]$buildName"

- task: PowerShell@2
  displayName: Set the prereleaseVersionNumber variable value
  inputs:
    targetType: 'inline'
    script: |
      [string] $dateTime = (Get-Date -Format 'yyyyMMddTHHmmss')
      [string] $prereleaseVersionNumber = "$(versionNumber)-ci$dateTime+$(Build.SourceVersion)"
      Write-Host "Setting the prerelease version number variable to '$prereleaseVersionNumber'."
      Write-Host "##vso[task.setvariable variable=prereleaseVersionNumber]$prereleaseVersionNumber"
```

With the above, you can do things like determine whether to use the `versionNumber` or the `prereleaseVersionNumber` variables depending on if the `$(Build.SourceBranchName)` is the default branch (e.g. `main` or `master`) or a feature branch.
The below example shows one way of how to do this, and sets the `versionNumber` variable to the `stableVersionNumber` if building the `main` branch, or to the `prereleaseVersionNumber` if building any other branch.

```yaml
name: 'Set dynamically below in a task'

variables:
  version.MajorMinor: '1.0' # Manually adjust the version number as needed for semantic versioning. Revision is auto-incremented.
  version.Revision: $[counter(variables['version.MajorMinor'], 0)]
  stableVersionNumber: '$(version.MajorMinor).$(version.Revision)'
  prereleaseVersionNumber: 'Set dynamically below in a task'
  versionNumber: 'Set dynamically below in a task' # Will be set to the stableVersionNumber or prereleaseVersionNumber based on the branch.
  isMainBranch: $[eq(variables['Build.SourceBranch'], 'refs/heads/main')] # Determine if we're building the 'main' branch or not.

steps:
- task: PowerShell@2
  displayName: Set the prereleaseVersionNumber variable value
  inputs:
    targetType: 'inline'
    script: |
      [string] $dateTime = (Get-Date -Format 'yyyyMMddTHHmmss')
      [string] $prereleaseVersionNumber = "$(stableVersionNumber)-ci$dateTime+$(Build.SourceVersion)"
      Write-Host "Setting the prerelease version number variable to '$prereleaseVersionNumber'."
      Write-Host "##vso[task.setvariable variable=prereleaseVersionNumber]$prereleaseVersionNumber"

- task: PowerShell@2
  displayName: Set the versionNumber to the stable or prerelease version number based on if the 'main' branch is being built or not
  inputs:
    targetType: 'inline'
    script: |
      [bool] $isMainBranch = $$(isMainBranch)
      [string] $versionNumber = "$(prereleaseVersionNumber)"
      if ($isMainBranch)
      {
        $versionNumber = "$(stableVersionNumber)"
      }
      Write-Host "Setting the version number to use to '$versionNumber'."
      Write-Host "##vso[task.setvariable variable=versionNumber]$versionNumber"

- task: PowerShell@2
  displayName: Set the name of the build (i.e. the Build.BuildNumber)
  inputs:
    targetType: 'inline'
    script: |
      [string] $buildName = "$(versionNumber)_$(Build.SourceBranchName)"
      Write-Host "Setting the name of the build to '$buildName'."
      Write-Host "##vso[build.updatebuildnumber]$buildName"
```

You can also leverage [expressions](https://docs.microsoft.com/en-us/azure/devops/pipelines/process/expressions?view=azure-devops#conditionally-set-a-task-input) to determine at runtime what inputs to provide to later tasks (e.g. the `stableVersionNumber` or the `prereleaseVersionNumber`), or if a tasks should run at all by placing a [condition](https://docs.microsoft.com/en-us/azure/devops/pipelines/process/conditions?view=azure-devops&tabs=yaml) on it.

If you like you could combine all 3 PowerShell tasks into a single task for brevity.
I prefer to keep them separated for clarity.

## Conclusion

Yaml builds are the future of Azure Pipelines.
I enjoy them because you get your build definition stored in source control with your code, it's easy to copy-paste the yaml to other projects (you can also use yaml templates, a topic for another post ;) ), and it makes showing off examples in blogs and gists easier.

With so many great reasons to start using yaml for your Azure Pipelines builds, I hope this information helps you get your version numbers and build names setup how you like them.

I'd also like to throw a shout out to [Andrew Hoefling's blog post](https://www.andrewhoefling.com/Blog/Post/azure-pipelines-custom-build-numbers-in-yaml-templates) that introduced me to the `counter` function and helped me get started with using custom version numbers in my yaml builds.

Happy versioning!
