---
id: 572
title: PowerShell Code To Ensure Client Is Using At Least The Minimum Required PowerShell Version
date: 2013-10-25T11:48:31-06:00
guid: http://dans-blog.azurewebsites.net/?p=572
permalink: /ensure-client-is-using-at-least-the-minimum-required-powershell-version/
categories:
  - PowerShell
tags:
  - Minimum
  - PowerShell
  - Required
  - Version
---
<strike>Here’s some simple code that will throw an exception if the client running your script is not using the version of PowerShell (or greater) that is required; just change the **$REQUIRED\_POWERSHELL\_VERSION** variable value to the minimum version that the script requires.</strike>

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:b77ad9cb-8830-4443-9d5b-7bc3a9463724" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; gutter: false; pad-line-numbers: true; title: ; notranslate" title="">
# Throw an exception if client is not using the minimum required PowerShell version.
$REQUIRED_POWERSHELL_VERSION = 3.0	# The minimum Major.Minor PowerShell version that is required for the script to run.
$POWERSHELL_VERSION = $PSVersionTable.PSVersion.Major + ($PSVersionTable.PSVersion.Minor / 10)
if ($REQUIRED_POWERSHELL_VERSION -gt $POWERSHELL_VERSION)
{ throw "PowerShell version $REQUIRED_POWERSHELL_VERSION is required for this script; You are only running version $POWERSHELL_VERSION. Please update PowerShell to at least version $REQUIRED_POWERSHELL_VERSION." }
</pre>
</div>

&#8212; UPDATE {

Thanks to Robin M for pointing out that PowerShell has [the built-in #Requires statement](http://technet.microsoft.com/en-us/library/hh847765.aspx) for this purpose, so you do not need to use the code above. Instead, simply place the following code anywhere in your script to enforce the desired PowerShell version required to run the script:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:3dfcb0e0-e4f8-4326-ae25-83f0b5f7766f" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; gutter: false; pad-line-numbers: true; title: ; notranslate" title="">
#Requires -Version 3.0
</pre>
</div>

If the user does not have the minimum required version of PowerShell installed, they will see an error message like this:

> The script &#8216;foo.ps1&#8217; cannot be run because it contained a "#requires" statement at line 1 for Windows PowerShell version 3.0 which is incompatible with the installed Windows PowerShell version of 2.0.

} UPDATE &#8212;

So if your script requires, for example, PowerShell v3.0, just put this at the start of your script to have it error out right away with a meaningful error message; otherwise your script may throw other errors that mask the real issue, potentially leading the user to spend many hours troubleshooting your script, or to give up on it all together.

I’ve been bitten by this in the past a few times now, where people report issues on my Codeplex scripts where the error message seems ambiguous.&#160; So now any scripts that I release to the general public will have this check in it to give them a proper error message.&#160; I have also created a page on [PowerShell v2 vs. v3 differences](http://dans-blog.azurewebsites.net/powershell-2-0-vs-3-0-syntax-differences-and-more/) that I’m going to use to keep track of the differences that I encounter, so that I can have confidence in the minimum powershell version that I set on my scripts.&#160; I also plan on creating a v3 vs. v4 page once I start using PS v4 features more.&#160; Of course, the best test is to actually run your script in the minimum powershell version that you set, which I mention how to do on my PS v2 vs. v3 page.

Happy coding!
