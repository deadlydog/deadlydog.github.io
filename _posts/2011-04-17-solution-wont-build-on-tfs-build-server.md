---
title: Solution won't build on TFS Build Server
date: 2011-04-17T22:58:00-06:00
permalink: /solution-wont-build-on-tfs-build-server/
categories:
  - Build
  - TFS
tags:
  - Build
  - Build Server
  - compile
  - solution
  - TFS
---

If you are able to build (compile) the solution on your local machine, but it won't build on the TFS build server and you are getting an error message similar to:

```text
1 error(s), 1 warning(s)
$/TeamProject/Dev/RQ4/FBs/4.1.4_eSec/RQ4.Client.sln - 1 error(s), 1 warning(s), View Log File
IntegrationfrmGetIntegrationAuthorization.xaml.cs (1138): The type or namespace name 'BillingFields' could not be found (are you missing a using directive or an assembly reference?)
C:WindowsMicrosoft.NETFramework64v4.0.30319Microsoft.Common.targets (1360): Could not resolve this reference. Could not locate the assembly "IQ.Core.Resources, Version=4.1.2.0, Culture=neutral, processorArchitecture=MSIL". Check to make sure the assembly exists on disk. If this reference is required by your code, you may get compilation errors.
```

Then the problem is likely one of the following:

1. The project/dll being reference is set to "Specific Version", so open the reference's properties and change it to "Any Version".
1. The project is not set to be built under the specific configuration. Right click on the solution in the Solution Explorer, and choose Configuration Manager. All projects should be set to be build (i.e. have a checkmark), and all of their platforms should be set to the same value. Change the Active Solution Platform to the platform you use and ensure that all projects are still set to always build.
1. The path to the referenced project/dll is too long. Windows/.NET has a limitation where the reference path cannot be more than 260 characters, and the directory it's in cannot be more than 248 characters. So the work around for this is usually to rename your build definition to something shorter, or if you just added a new project/namespace to the project, to shorten its name so the path stays under the limit.
