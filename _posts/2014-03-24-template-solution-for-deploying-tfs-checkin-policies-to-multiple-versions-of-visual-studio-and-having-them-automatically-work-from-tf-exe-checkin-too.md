---
id: 741
title: Template Solution For Deploying TFS Checkin Policies To Multiple Versions Of Visual Studio And Having Them Automatically Work From "TF.exe Checkin" Too
date: 2014-03-24T10:55:05-06:00
guid: http://dans-blog.azurewebsites.net/?p=741
permalink: /template-solution-for-deploying-tfs-checkin-policies-to-multiple-versions-of-visual-studio-and-having-them-automatically-work-from-tf-exe-checkin-too/
categories:
  - PowerShell
  - TFS
  - Visual Studio
  - Visual Studio Extensions
tags:
  - Checkin
  - Checkin Policies
  - PowerShell
  - Source Code
  - Team Foundation
  - Team Foundation Server
  - TF
  - TF.exe
  - TFS
  - visual studio
  - VSIX
---

## Get the source code

Let’s get right to it by giving you the source code. You can [get it from the MSDN samples here](http://code.msdn.microsoft.com/windowsdesktop/Deploying-Checkin-Policies-d306493a).

## Explanation of source code and adding new checkin policies

If you open the Visual Studio (VS) solution the first thing you will likely notice is that there are 5 projects. CheckinPolicies.VS2012 simply references all of the files in CheckinPolicies.VS2013 as links (i.e. shortcut files); this is because we need to compile the CheckinPolicies.VS2012 project using TFS 2012 assemblies, and the CheckinPolicies.VS2013 project using TFS2013 assemblies, but want both projects to have all of the same checkin policies. So the projects contain all of the same files; just a few of their references are different. A copy of the references that are different between the two projects are stored in the project’s "Dependencies" folder; these are the Team Foundation assemblies that are specific to VS 2012 and 2013. Having these assemblies stored in the solution allows us to still build the VS 2012 checkin policies, even if you (or a colleague) only has VS 2013 installed.

__Update:__ To avoid having multiple CheckinPolicy.VS* projects, we could use [the msbuild targets technique that P. Kelly shows on his blog](http://blogs.msdn.com/b/phkelley/archive/2013/08/12/checkin-policy-multitargeting.aspx). However, I believe we would still need multiple deployment projects, as described below, in order to have the checkin policies work outside of Visual Studio.

The other projects are CheckinPolicyDeployment.VS2012 and CheckinPolicyDeployment.VS2013 (both of which are VSPackage projects), and CheckinPolicyDeploymentShared. The CheckinPolicyDeployment.VS2012/VS2013 projects will generate the VSIX files that are used to distribute the checkin policies, and CheckinPolicyDeploymentShared contains files/code that are common to both of the projects (the projects reference the files by linking to them).

Basically everything is ready to go. Just start adding new checkin policy classes to the CheckinPolicy.VS2013 project, and then also add them to the CheckinPolicy.VS2012 project as a link. You can add a file as a link in 2 different ways in the Solution Explorer:

1. Right-click on the CheckinPolicies.VS2012 project and choose __Add -> Existing Item...__, and then navigate to the new class file that you added to the CheckinPolicy.VS2013 project. Instead of clicking the Add button though, click the little down arrow on the side of the Add button and then choose __Add As Link__.
1. Drag and drop the file from the CheckinPolicy.VS2013 project to the CheckinPolicy.VS2012 project, but while releasing the left mouse button to drop the file, hold down the __Alt__ key; this will change the operation from adding a copy of the file to that project, to adding a shortcut file that links back to the original file.

There is a __DummyCheckinPolicy.cs__ file in the CheckinPolicies.VS2013 project that shows you an example of how to create a new checkin policy. Basically you just need to create a new public, serializable class that extends the CheckinPolicyBase class. The actual logic for your checkin policy to perform goes in the Evaluate() function. If there is a policy violation in the code that is trying to be checked in, just add a new PolicyFailure instance to the __failures__ list with the message that you want the user to see.

## Building a new version of your checkin policies

Once you are ready to deploy your policies, you will want to update the version number in the __source.extension.vsixmanifest__ file in both the CheckinPolicyDeployment.VS2012 and CheckinPolicyDeployment.VS2013 projects. Since these projects will both contain the same policies, I recommend giving them the same version number as well. Once you have updated the version number, build the solution in Release mode. From there you will find the new VSIX files at "CheckinPolicyDeployment.VS2012\bin\Release\TFS Checkin Policies VS2012.vsix" and "CheckinPolicyDeployment.VS2013\bin\Release\TFS Checkin Policies VS2013.vsix". You can then distribute them to your team; I recommend [setting up an internal VS Extension Gallery](http://blogs.msdn.com/b/visualstudio/archive/2011/10/03/private-extension-galleries-for-the-enterprise.aspx), but the poor-man’s solution is to just email the vsix file out to everyone on your team.

## Having the policies automatically work outside of Visual Studio

This is already hooked up and working in the template solution, so nothing needs to be changed there, but I will explain how it works here. A while back I blogged about [how to get your Team Foundation Server (TFS) checkin polices to still work when checking code in from the command line](http://dans-blog.azurewebsites.net/getting-custom-tfs-checkin-policies-to-work-when-committing-from-the-command-line-i-e-tf-checkin/) via the "tf checkin" command; by default when installing your checkin policies via a VSIX package (the MS recommended approach) you can only get them to work in Visual Studio. I hated that I would need to manually run the script I provided each time the checkin policies were updated, so I posted [a question on Stack Overflow about how to run a script automatically after the VSIX package installs the extension](http://stackoverflow.com/questions/18647866/run-script-during-after-vsix-install). So it turns out that you can’t do that, but what you can do is use a VSPackage instead, which still uses VSIX to deploy the extension, but then also allows us to hook into Visual Studio events to run our script when VS starts up or exits.

Here is the VSPackage class code to hook up the events and call our UpdateCheckinPoliciesInRegistry() function:

```csharp
/// <summary>
/// This is the class that implements the package exposed by this assembly.
///
/// The minimum requirement for a class to be considered a valid package for Visual Studio
/// is to implement the IVsPackage interface and register itself with the shell.
/// This package uses the helper classes defined inside the Managed Package Framework (MPF)
/// to do it: it derives from the Package class that provides the implementation of the
/// IVsPackage interface and uses the registration attributes defined in the framework to
/// register itself and its components with the shell.
/// </summary>
// This attribute tells the PkgDef creation utility (CreatePkgDef.exe) that this class is
// a package.
[PackageRegistration(UseManagedResourcesOnly = true)]
// This attribute is used to register the information needed to show this package
// in the Help/About dialog of Visual Studio.
[InstalledProductRegistration("#110", "#112", "1.0", IconResourceID = 400)]
// Auto Load our assembly even when no solution is open (by using the Microsoft.VisualStudio.VSConstants.UICONTEXT_NoSolution guid).
[ProvideAutoLoad("ADFC4E64-0397-11D1-9F4E-00A0C911004F")]
public abstract class CheckinPolicyDeploymentPackage : Package
{
    private EnvDTE.DTEEvents _dteEvents;

    /// <summary>
    /// Initialization of the package; this method is called right after the package is sited, so this is the place
    /// where you can put all the initialization code that rely on services provided by VisualStudio.
    /// </summary>
    protected override void Initialize()
    {
        base.Initialize();

        var dte = (DTE2)GetService(typeof(SDTE));
        _dteEvents = dte.Events.DTEEvents;
        _dteEvents.OnBeginShutdown += OnBeginShutdown;

        UpdateCheckinPoliciesInRegistry();
    }

    private void OnBeginShutdown()
    {
        _dteEvents.OnBeginShutdown -= OnBeginShutdown;
        _dteEvents = null;

        UpdateCheckinPoliciesInRegistry();
    }

    private void UpdateCheckinPoliciesInRegistry()
    {
        var dte = (DTE2)GetService(typeof(SDTE));
        string visualStudioVersionNumber = dte.Version;
        string customCheckinPolicyEntryName = "CheckinPolicies";

        // Create the paths to the registry keys that contains the values to inspect.
        string desiredRegistryKeyPath = string.Format("HKEY_CURRENT_USER\\Software\\Microsoft\\VisualStudio\\{0}_Config\\TeamFoundation\\SourceControl\\Checkin Policies", visualStudioVersionNumber);
        string currentRegistryKeyPath = string.Empty;
        if (Environment.Is64BitOperatingSystem)
            currentRegistryKeyPath = string.Format("HKEY_LOCAL_MACHINE\\SOFTWARE\\Wow6432Node\\Microsoft\\VisualStudio\\{0}\\TeamFoundation\\SourceControl\\Checkin Policies", visualStudioVersionNumber);
        else
            currentRegistryKeyPath = string.Format("HKEY_LOCAL_MACHINE\\SOFTWARE\\Microsoft\\VisualStudio\\{0}\\TeamFoundation\\SourceControl\\Checkin Policies", visualStudioVersionNumber);

        // Get the value that the registry should have, and the value that it currently has.
        var desiredRegistryValue = Registry.GetValue(desiredRegistryKeyPath, customCheckinPolicyEntryName, null);
        var currentRegistryValue = Registry.GetValue(currentRegistryKeyPath, customCheckinPolicyEntryName, null);

        // If the registry value is already up to date, just exit without updating the registry.
        if (desiredRegistryValue == null || desiredRegistryValue.Equals(currentRegistryValue))
            return;

        // Get the path to the PowerShell script to run.
        string powerShellScriptFilePath = Path.Combine(Path.GetDirectoryName(System.Reflection.Assembly.GetAssembly(typeof(CheckinPolicyDeploymentPackage)).Location),
            "FilesFromShared", "UpdateCheckinPolicyInRegistry.ps1");

        // Start a new process to execute the batch file script, which calls the PowerShell script to do the actual work.
        var process = new Process
        {
            StartInfo =
            {
                FileName = "PowerShell",
                Arguments = string.Format("-NoProfile -ExecutionPolicy Bypass -File \"{0}\" -VisualStudioVersion \"{1}\" -CustomCheckinPolicyEntryName \"{2}\"", powerShellScriptFilePath, visualStudioVersionNumber, customCheckinPolicyEntryName),

                // Hide the PowerShell window while we run the script.
                CreateNoWindow = true,
                UseShellExecute = false
            }
        };
        process.Start();
    }
}
```

All of the attributes on the class are put there by default, except for the "[ProvideAutoLoad("ADFC4E64-0397-11D1-9F4E-00A0C911004F")]" one; this attribute is the one that actually allows the Initialize() function to get called when Visual Studio starts. You can see in the Initialize method that we hook up an event so that the UpdateCheckinPoliciesInRegistry() function gets called when VS is closed, and we also call that function from Initialize(), which is called when VS starts up.

You might have noticed that this class is abstract. This is because the VS 2012 and VS 2013 classed need to have a unique ID attribute, so the actual VSPackage class just inherits from this one. Here is what the VS 2013 one looks like:

```csharp
[Guid(GuidList.guidCheckinPolicyDeployment_VS2013PkgString)]
public sealed class CheckinPolicyDeployment_VS2013Package : CheckinPolicyDeploymentShared.CheckinPolicyDeploymentPackage
{ }
```

The UpdateCheckinPoliciesInRegistry() function checks to see if the appropriate registry key has been updated to allow the checkin policies to run from the "tf checkin" command prompt command. If they have, then it simply exits, otherwise it calls a PowerShell script to set the keys for us. A PowerShell script is used because modifying the registry requires admin permissions, and we can easily run a new PowerShell process as admin (assuming the logged in user is an admin on their local machine, which is the case for everyone in our company).

The one variable to note here is the __customCheckinPolicyEntryName__. This corresponds to the registry key name that I’ve specified in the RegistryKeyToAdd.pkgdef file, so if you change it be sure to change it in both places. This is what the RegistryKeyToAdd.pkgdef file contains:

```csharp
// We use "\..\" in the value because the projects that include this file place it in a "FilesFromShared" folder, and we want it to look for the dll in the root directory.
[$RootKey$\TeamFoundation\SourceControl\Checkin Policies]
"CheckinPolicies"="$PackageFolder$\..\CheckinPolicies.dll"
```

And here are the contents of the UpdateCheckinPolicyInRegistry.ps1 PowerShell file. This is basically just a refactored version of the script I posted on my old blog post:

```powershell
# This script copies the required registry value so that the checkin policies will work when doing a TFS checkin from the command line.
param
(
    [parameter(Mandatory=$true,HelpMessage="The version of Visual Studio to update in the registry (i.e. '11.0' for VS 2012, '12.0' for VS 2013)")]
    [string]$VisualStudioVersion,

    [parameter(HelpMessage="The name of the Custom Checkin Policy Entry in the Registry Key.")]
    [string]$CustomCheckinPolicyEntryName = 'CheckinPolicies'
)

# Turn on Strict Mode to help catch syntax-related errors.
#   This must come after a script's/function's param section.
#   Forces a function to be the first non-comment code to appear in a PowerShell Module.
Set-StrictMode -Version Latest

$ScriptBlock = {
    function UpdateCheckinPolicyInRegistry([parameter(Mandatory=$true)][string]$VisualStudioVersion, [string]$CustomCheckinPolicyEntryName)
    {
        $status = 'Updating registry to allow checkin policies to work outside of Visual Studio version ' + $VisualStudioVersion + '.'
        Write-Output $status

        # Get the Registry Key Entry that holds the path to the Custom Checkin Policy Assembly.
        $HKCUKey = 'HKCU:\Software\Microsoft\VisualStudio\' + $VisualStudioVersion + '_Config\TeamFoundation\SourceControl\Checkin Policies'
        $CustomCheckinPolicyRegistryEntry = Get-ItemProperty -Path $HKCUKey -Name $CustomCheckinPolicyEntryName
        $CustomCheckinPolicyEntryValue = $CustomCheckinPolicyRegistryEntry.($CustomCheckinPolicyEntryName)

        # Create a new Registry Key Entry for the iQ Checkin Policy Assembly so they will work from the command line (as well as from Visual Studio).
        if ([Environment]::Is64BitOperatingSystem)
        { $HKLMKey = 'HKLM:\SOFTWARE\Wow6432Node\Microsoft\VisualStudio\' + $VisualStudioVersion + '\TeamFoundation\SourceControl\Checkin Policies' }
        else
        { $HKLMKey = 'HKLM:\SOFTWARE\Microsoft\VisualStudio\' + $VisualStudioVersion + '\TeamFoundation\SourceControl\Checkin Policies' }
        Set-ItemProperty -Path $HKLMKey -Name $CustomCheckinPolicyEntryName -Value $CustomCheckinPolicyEntryValue
    }
}

# Run the script block as admin so it has permissions to modify the registry.
Start-Process -FilePath PowerShell -Verb RunAs -ArgumentList "-NoProfile -ExecutionPolicy Bypass -Command &amp; {$ScriptBlock UpdateCheckinPolicyInRegistry -VisualStudioVersion ""$VisualStudioVersion"" -CustomCheckinPolicyEntryName ""$CustomCheckinPolicyEntryName""}"
```

While I could have just used a much smaller PowerShell script that simply set a given registry key to a given value, I chose to have some code duplication between the C# code and this script so that this script can still be used as a stand-alone script if needed.

The slight downside to using a VSPackage is that this script still won’t get called until the user closes or opens a new instance of Visual Studio, so the checkin policies won’t work immediately from the "tf checkin" command after updating the checkin policies extension, but this still beats having to remember to manually run the script.

## Conclusion

So I’ve given you a template solution that you can use without any modification to start creating your VS 2012 and VS 2013 compatible checkin policies; Just add new class files to the CheckinPolicies.VS2013 project, and then add them to the CheckinPolicies.VS2012 project as well __as links__. By using links it allows you to only have to modify checkin policy files once, and have the changes go to both the 2012 and 2013 VSIX packages. Hopefully this template solution helps you to get your TFS checkin policies up and running faster.

Happy Coding!
