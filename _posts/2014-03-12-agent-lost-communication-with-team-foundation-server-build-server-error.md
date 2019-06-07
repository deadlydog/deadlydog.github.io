---
id: 727
title: '"Agent lost communication with Team Foundation Server" TFS Build Server Error'
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

> Exception Message: The build failed because the build server that hosts build agent TFS-BuildController001 - Agent4 lost communication with Team Foundation Server. (type FaultException`1)

This error message would appear randomly; some builds would pass, others would fail, and when they did fail with this error message it was often at different parts in the build process.

After a bit of digging I found [this post](http://social.technet.microsoft.com/Forums/windowsserver/en-US/cd99a033-e787-4b7a-9a50-8e02af8d7047/visual-studio-keeps-losing-connection-to-team-foundation-server?forum=winservergen) and [this one](http://social.msdn.microsoft.com/Forums/vstudio/en-US/6d33f92e-2a61-4584-976e-3c865cdde72c/tfs-2010-sp1-build-process-fails-with-team-foundation-services-are-not-available-from-server?forum=tfsbuild), which discussed different error messages around their build process failing with some sort of error around the build controller losing connection to the TFS server. They talked about different fixes relating to DNS issues and load balancing, so we had our network team update our DNS records and flush the cache, but were still getting the same errors.

We have several build controllers, and I noticed that the problem was only happening on two of the three, so our network team updated the __hosts__ file on the two with the problem to match the entries in the one that was working fine, and boom, everything started working properly again 🙂

So the problem was that the hosts file on those two build controller machines somehow got changed.

The hosts file can typically be found at "C:\Windows\System32\Drivers\etc\hosts", and here is an example of what we now have in our hosts file for entries (just the two entries):

```text
12.345.67.89    TFS-Server.OurDomain.local
12.345.67.89    TFS-Server
```

If you too are running into this TFS Build Server error I hope this helps.
