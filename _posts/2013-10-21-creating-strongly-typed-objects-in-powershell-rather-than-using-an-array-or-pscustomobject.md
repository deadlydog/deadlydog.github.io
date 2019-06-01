---
id: 548
title: Creating Strongly Typed Objects In PowerShell, Rather Than Using An Array Or PSCustomObject
date: 2013-10-21T14:20:17-06:00
guid: http://dans-blog.azurewebsites.net/?p=548
permalink: /creating-strongly-typed-objects-in-powershell-rather-than-using-an-array-or-pscustomobject/
categories:
  - PowerShell
tags:
  - Add-Type
  - array
  - C#
  - object
  - PowerShell
  - PSCustomObject
  - strongly typed
---
I recently [read a great article](http://www.happysysadm.com/2013/10/powershell-hashtables-dictionaries-and.html) that explained how to create hashtables, dictionaries, and PowerShell objects. I already knew a bit about these, but this article gives a great comparison between them, when to use each of them, and how to create them in the different versions of PowerShell.

Right now I’m working on refactoring some existing code into some general functions for creating, removing, and destroying IIS applications ([read about it here](http://dans-blog.azurewebsites.net/powershell-functions-to-convert-remove-and-delete-iis-web-applications/)). At first, I thought that this would be a great place to use PSCustomObject, as in order to perform these operations I needed 3 pieces of information about a website; the Website name, the Application Name (essentially the path to the application under the Website root), and the Application Pool that the application should run in.



### Using an array

So initially the code I wrote just used an array to hold the 3 properties of each application service:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:b3bb5f05-399d-47b2-8023-882c6cf90c21" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title="">
# Store app service info as an array of arrays.
$AppServices = @(
	("MyWebsite", "$Version/Reporting.Services", "Services .NET4"),
	("MyWebsite", "$Version/Core.Services", "Services .NET4"),
	...
)

# Remove all of the Web Applications.
foreach ($appInfo in $AppServices )
{
	$website = $appInfo[0]
	$appName = $appInfo[1]
	$appPool = $appInfo[2]
	...
}

</pre>
</div>

There is nothing "wrong" with using an array to store the properties; it works. However, now that I am refactoring the functions to make them general purpose to be used by other people/scripts, this does have one very undesirable limitation; The properties must always be stored in the correct order in the array (i.e. Website in position 0, App Name in 1, and App Pool in 2). Since the list of app services will be passed into my functions, this would require the calling script to know to put the properties in this order. Boo.

Another option that I didn’t consider when I originally wrote the script was to use an associative array, but it has the same drawbacks as using a PSCustomObject discussed below.



### Using PSCustomObject

So I thought let’s use a PSCustomObject instead, as that way the client does not have to worry about the order of the information; as long as their PSCustomObject has Website, ApplicationPath, and ApplicationPool properties then we’ll be able to process it. So I had this:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:fb1b994a-747b-466d-a19a-ce3cae0ba948" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; title: ; notranslate" title="">
[PSCustomObject[]] $applicationServicesInfo = @(
	[PSCustomObject]@{Website = "MyWebsite"; ApplicationPath = "$Version/Reporting.Services"; ApplicationPool = "Services .NET4"},
	[PSCustomObject]@{Website = "MyWebsite"; ApplicationPath = "$Version/Core.Services"; ApplicationPool = "Services .NET4},
	...
)

function Remove-ApplicationServices
{
	param([PSCustomObject[]] $ApplicationServicesInfo)

	# Remove all of the Web Applications.
	foreach ($appInfo in [PSCustomObject[]]$ApplicationServicesInfo)
	{
		$website = $appInfo.Website
		$appPath = $appInfo.ApplicationPath
		$appPool = $appInfo.ApplicationPool
		...
	}
}
</pre>
</div>

I liked this better as the properties are explicitly named, so there’s no guess work about which information the property contains, but it’s still not great. One thing that I don’t have here (and really should), is validation to make sure that the passed in PSCustomObjects actually have Website, ApplicationPath, and ApplicationPool properties on them, otherwise an exception will be thrown when I try to access them. So with this approach I would still need to have documentation and validation to ensure that the client passes in a PSCustomObject with those properties.



### Using a new strongly typed object

I frequently read other PowerShell blog posts and recently [stumbled across this one](http://blogs.technet.com/b/heyscriptingguy/archive/2013/10/19/weekend-scripter-use-powershell-and-pinvoke-to-remove-stubborn-files.aspx). In the article he mentions creating a new compiled type by passing a string to the [Add-Type cmdlet](http://technet.microsoft.com/en-us/library/hh849914.aspx); essentially writing C# code in his PowerShell script to create a new class. I knew that you could use Add-Type to import other assemblies, but never realized that you could use it to import an assembly that doesn’t actually exist (i.e. a string in your PowerShell script). This is freaking amazing! So here is what my new solution looks like:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:a9c9738d-d96d-4e84-b30a-0b3b4a84cc03" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; title: ; notranslate" title="">
try {	# Wrap in a try-catch in case we try to add this type twice.
# Create a class to hold an IIS Application Service's Information.
Add-Type -TypeDefinition @"
	using System;

	public class ApplicationServiceInformation
	{
		// The name of the Website in IIS.
		public string Website { get; set;}

		// The path to the Application, relative to the Website root.
		public string ApplicationPath { get; set; }

		// The Application Pool that the application is running in.
		public string ApplicationPool { get; set; }

		// Implicit Constructor.
		public ApplicationServiceInformation() { }

		// Explicit constructor.
		public ApplicationServiceInformation(string website, string applicationPath, string applicationPool)
		{
			this.Website = website;
			this.ApplicationPath = applicationPath;
			this.ApplicationPool = applicationPool;
		}
	}
"@
} catch {}

$anotherService = New-Object ApplicationServiceInformation
$anotherService.Website = "MyWebsite"
$anotherService.ApplicationPath = "$Version/Payment.Services"
$anotherService.ApplicationPool = "Services .NET4"

[ApplicationServiceInformation[]] $applicationServicesInfo = @(
	(New-Object ApplicationServiceInformation("MyWebsite", "$Version/Reporting.Services", "Services .NET4")),
	(New-Object ApplicationServiceInformation -Property @{Website = "MyWebsite"; ApplicationPath = "$Version/Core.Services"; ApplicationPool = "Services .NET4}),
	$anotherService,
	...
)

function Remove-ApplicationServices
{
	param([ApplicationServiceInformation[]] $ApplicationServicesInfo)

	# Remove all of the Web Applications.
	foreach ($appInfo in [ApplicationServiceInformation[]]$ApplicationServicesInfo)
	{
		$website = $appInfo.Website
		$appPath = $appInfo.ApplicationPath
		$appPool = $appInfo.ApplicationPool
		...
	}
}
</pre>
</div>

I first create a simple container class to hold the application service information, and now all of my properties are explicit like with the PSCustomObject, but also I’m guaranteed the properties will exist on the object that is passed into my function. From there I declare my array of ApplicationServiceInformation objects, and the function that we can pass them into. Note that I wrap each New-Object call in parenthesis, otherwise PowerShell parses it incorrectly and will throw an error.

As you can see from the snippets above and below, there are several different ways that we can initialize a new instance of our ApplicationServiceInformation class:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:46119ce9-6231-4efe-992b-a1e8c4545501" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; title: ; notranslate" title="">
$service1 = New-Object ApplicationServiceInformation("Explicit Constructor", "Core.Services", ".NET4")

$service2 = New-Object ApplicationServiceInformation -ArgumentList ("Explicit Constructor ArgumentList", "Core.Services", ".NET4")

$service3 = New-Object ApplicationServiceInformation -Property @{Website = "Using Property"; ApplicationPath = "Core.Services"; ApplicationPool = ".NET4"}

$service4 = New-Object ApplicationServiceInformation
$service4.Website = "Properties added individually"
$service4.ApplicationPath = "Core.Services"
$service4.ApplicationPool = "Services .NET4"
</pre>
</div>



### Caveats

  * Note that I wrapped the call to Add-Type in a Try-Catch block. This is to prevent PowerShell from throwing an error if the type tries to get added twice. It’s sort of a hacky workaround, [but there aren’t many good alternatives](http://stackoverflow.com/questions/16552801/how-do-i-conditionally-add-a-class-with-add-type-typedefinition-if-it-isnt-add), since you cannot unload an assembly.
  * This means that while developing if you make any changes to the class, <u>you’ll have to restart your PowerShell session for the changes to be applied</u>, since the Add-Type cmdlet will only work properly the first time that it is called in a session.

I hope you found something in here useful.

Happy coding!
