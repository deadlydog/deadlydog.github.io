---
id: 602
title: Problems Caused By Installing Windows 8.1 Update
date: 2013-11-08T15:37:20-06:00
author: deadlydog
guid: http://dans-blog.azurewebsites.net/?p=602
permalink: /problems-caused-by-installing-windows-8-1-update/
categories:
  - Windows
  - Windows 8
tags:
  - "8.1"
  - Broken
  - Problem
  - Windows
  - Windows 8
  - Windows 8.1
---
Myself and a few co-workers have updated from Windows 8 to Windows 8.1 and have run into some weird problems.&#160; After a bit of Googling I have found that we are not alone.&#160; This is just a quick list of some things the the Windows 8.1 Update seems to have broken.&#160; I’ll update this post as I find more issues.

&#160;

### IE 11 breaks some websites

  * I found that some of the links in the website our office uploads our Escrow deposits to no longer worked in IE 11 (which 8.1 installs).&#160; Turning on the developer tools showed that it was throwing a Javascript error about an undefined function.&#160; Everything works fine in IE 10 though and no undefined errors are thrown.
  * I have also noticed that after doing a search on Google and clicking one of the links, in order to get back to the Google results page you have to click the Back button twice; the first Back-click just takes you to a blank page (when you click the Google link it directs you to an empty page, which then forwards you to the correct page).
  * [Others have complained](http://answers.microsoft.com/en-us/ie/forum/ie11_pr-windows8_1_pr/windows-81-upgrade-ie-11-not-working-properly/87224e09-2732-48c6-823d-c2099faead48) that they are experiencing problems with GMail and Silverlight after the 8.1 update.

So it may just be that IE 11 updated it’s standards to be more compliant and now many websites don’t meet the new requirements (I’m not sure); but either way, you may find that some of your favorite websites no longer work properly with IE 11, and you’ll have to wait for IE 11 or the website to make an update.

&#160;

### VPN stopped working

We use the SonicWall VPN client at my office, and I found that it no longer worked after updating to Windows 8.1.&#160; The solution was a simple uninstall, reinstall, but still, it’s just one more issue to add to the list.

&#160;

### More?

Have you noticed other things broken after doing the Windows 8.1 update? Share them in the comments below!

In my personal opinion, I would wait a while longer before updating to Windows 8.1; give Microsoft more time to fix some of these issues.&#160; Many of the new features in Windows 8.1 aren’t even noticeable yet, as many apps don’t yet take advantage of them.&#160; Also, while MS did put a Start button back in, it’s not nearly as powerful as the Windows 7 Start button, so if that’s your reason for upgrading to 8.1 just go get [Classic Shell](http://www.classicshell.net/) instead.

Hopefully Microsoft will be releasing hotfixes to get these issues addressed sooner than later.
