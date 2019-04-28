---
id: 76
title: VS 11 Beta merge tool is awesome, except for resolving conflicts
date: 2012-04-05T23:48:00-06:00
author: deadlydog
layout: post
guid: https://deadlydog.wordpress.com/?p=76
permalink: /vs-11-beta-merge-tool-is-awesome-except-for-resolving-conflicts/
jabber_published:
  - "1353109742"
categories:
  - Visual Studio
tags:
  - merge
  - merge tool
  - visual studio
  - Visual Studio 2012
  - VS
  - VS 11
---
If you&#8217;ve downloaded the new VS 11 Beta and done any merging, then you&#8217;ve probably seen the new diff and merge tools built into VS 11.&#160; They are awesome, and by far a vast improvement over the ones included in VS 2010.&#160; There is one problem with the merge tool though, and in my opinion it is huge. 

Basically the problem with the new VS 11 Beta merge tool is that when you are resolving conflicts after performing a merge, you cannot tell what changes were made in each file where the code is conflicting.&#160; Was the conflicting code added, deleted, or modified in the source and target branches?&#160; I don&#8217;t know (without explicitly opening up the history of both the source and target files), and the merge tool doesn&#8217;t tell me.&#160; In my opinion this is a huge fail on the part of the designers/developers of the merge tool, as it actually forces me to either spend an extra minute for every conflict to view the source and target file history, or to go back to use the merge tool in VS 2010 to properly assess which changes I should take. 

I submitted this as a bug to Microsoft, but they say that this is intentional by design. WHAT?! So they purposely crippled their tool in order to make it pretty and keep the look consistent with the new diff tool?&#160; That&#8217;s like purposely putting a little hole in the bottom of your cup for design reasons to make it look cool.&#160; Sure, the cup looks cool, but I&#8217;m not going to use it if it leaks all over the place and doesn&#8217;t do the job that it is intended for. Bah! but I digress. 

Because this bug is apparently a feature, they asked me to open up a "feature request" to have the problem fixed. Please go vote up both my [bug submission](https://connect.microsoft.com/VisualStudio/feedback/details/734678/tfs-11-beta-merge-tool-code-change-conflicts-are-not-clear) and the [feature request](http://visualstudio.uservoice.com/forums/121579-visual-studio/suggestions/2741136-change-vs-11-merge-tool-conflict-coloring-to-conve) so that this tool will actually be useful by the time the final VS 11 product is released.