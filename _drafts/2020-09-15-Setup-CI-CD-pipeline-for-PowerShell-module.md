---
title: "Template post"
permalink: /Setup-CI-CD-pipeline-for-PowerShell-module/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22T00:00:00-06:00
comments_locked: false
categories:
  - Blog
tags:
  - Should all
  - Start with
  - Capitals
---

In the previous post I showed how you can [create a PowerShell Core module in C# and unit test it with xUnit](/Create-and-test-PowerShell-Core-cmdlets-in-CSharp/).
In this post, we're going to look at setting up Continuous Integration (CI) to version, build, and unit test the assemblies.
We'll then see how to run Pester integration tests against the PowerShell module.
Finally, we'll see how to create the PowerShell module NuGet package so that it can easily be published to the public (or your private) PowerShell gallery.
In this post we'll be using Azure DevOps for our build platform, but much of the information applies to other build systems as well.
