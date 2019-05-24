---
id: 727
title: '&ldquo;Agent lost communication with Team Foundation Server&rdquo; TFS Build Server Error'
date: 2014-03-12T11:26:51-06:00
guid: http://dans-blog.azurewebsites.net/?p=727
permalink: /agent-lost-communication-with-team-foundation-server-build-server-error/
categories:
  - Build
  - TFS
tags:
  - Build
  - Build Controller
  - Build Server
  - Communication
  - Connection
  - error
  - Hosts File
  - Lost
  - Team Foundation
  - Team Foundation Server
  - TFS
---
We had recently started getting lots of error messages similar to the following on our TFS Build Servers:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:1d42ef9f-955d-4299-8f37-e99e9b4e06b4" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: plain; pad-line-numbers: true; title: ; notranslate" title="">
Exception Message: The build failed because the build server that hosts build agent TFS-BuildController001 - Agent4 lost communication with Team Foundation Server. (type FaultException`1)
</pre>
</div>

This error message would appear randomly; some builds would pass, others would fail, and when they did fail with this error message it was often at different parts in the build process.

After a bit of digging I found [this post](http://social.technet.microsoft.com/Forums/windowsserver/en-US/cd99a033-e787-4b7a-9a50-8e02af8d7047/visual-studio-keeps-losing-connection-to-team-foundation-server?forum=winservergen) and [this one](http://social.msdn.microsoft.com/Forums/vstudio/en-US/6d33f92e-2a61-4584-976e-3c865cdde72c/tfs-2010-sp1-build-process-fails-with-team-foundation-services-are-not-available-from-server?forum=tfsbuild), which discussed different error messages around their build process failing with some sort of error around the build controller losing connection to the TFS server. They talked about different fixes relating to DNS issues and load balancing, so we had our network team update our DNS records and flush the cache, but were still getting the same errors.

We have several build controllers, and I noticed that the problem was only happening on two of the three, so our network team updated the **hosts** file on the two with the problem to match the entries in the one that was working fine, and boom, everything started working properly again 🙂

So the problem was that the hosts file on those two build controller machines somehow got changed.

The hosts file can typically be found at "C:\Windows\System32\Drivers\etc\hosts", and here is an example of what we now have in our hosts file for entries (just the two entries):

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:73f6e5bb-0bc3-40d3-a757-3a89164ef8a1" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: plain; title: ; notranslate" title="">
12.345.67.89	TFS-Server.OurDomain.local
12.345.67.89	TFS-Server
</pre>
</div>

If you too are running into this TFS Build Server error I hope this helps.
