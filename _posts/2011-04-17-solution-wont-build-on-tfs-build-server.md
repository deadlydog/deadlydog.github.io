---
id: 22
title: 'Solution won&#8217;t build on TFS Build Server'
date: 2011-04-17T22:58:00-06:00
author: deadlydog
layout: post
guid: https://deadlydog.wordpress.com/?p=22
permalink: /solution-wont-build-on-tfs-build-server/
jabber_published:
  - "1353106660"
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
So if you are able to build (compile) the solution on your local machine, but it won&#8217;t build on the TFS build server and you are getting an error message similar to:

1 error(s), 1 warning(s)   
$/TeamProject/Dev/RQ4/FBs/4.1.4_eSec/RQ4.Client.sln &#8211; 1 error(s), 1 warning(s), View Log File   
IntegrationfrmGetIntegrationAuthorization.xaml.cs (1138): The type or namespace name &#8216;BillingFields&#8217; could not be found (are you missing a using directive or an assembly reference?)   
C:WindowsMicrosoft.NETFramework64v4.0.30319Microsoft.Common.targets (1360): Could not resolve this reference. Could not locate the assembly "IQ.Core.Resources, Version=4.1.2.0, Culture=neutral, processorArchitecture=MSIL". Check to make sure the assembly exists on disk. If this reference is required by your code, you may get compilation errors.

Then the problem is likely one of the following:

&#160;

1 &#8211; The project/dll being reference is set to "Specific Version", so open the reference&#8217;s properties and change it to "Any Version".

&#160;

2 &#8211; The project is not set to be built under the specific configuration.&#160; Right click on the solution in the Solution Explorer, and choose Configuration Manager.&#160; All projects should be set to be build (i.e. have a checkmark), and all of their platforms should be set to the same value.&#160; Change the Active Solution Platform to the platform you use and ensure that all projects are still set to always build.

&#160;

3 &#8211; The path to the refenced project/dll is too long.&#160; Windows/.NET has a limitation where the reference path cannot be more than 260 characters, and the directory it&#8217;s in cannot be more than 248 characters.&#160; So the work around for this is usually to rename your build definition to something shorter, or if you just added a new project/namespace to the project, to shorten its name so the path stays under the limit.