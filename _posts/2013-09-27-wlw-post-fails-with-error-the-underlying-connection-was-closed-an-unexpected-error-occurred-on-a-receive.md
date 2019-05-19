---
id: 509
title: 'WLW Post Fails With Error &ldquo;The underlying connection was closed: An unexpected error occurred on a receive.&rdquo;'
date: 2013-09-27T12:34:41-06:00
guid: http://dans-blog.azurewebsites.net/?p=509
permalink: /wlw-post-fails-with-error-the-underlying-connection-was-closed-an-unexpected-error-occurred-on-a-receive/
categories:
  - Windows Live Writer
  - WordPress
tags:
  - error
  - Network Connection Error
  - Windows Live Writer
  - WLW
  - WordPress
---
When trying to upload [my last blog post](http://dans-blog.azurewebsites.net/launch-visual-studio-checkin-window-with-a-keystroke/) from Windows Live Writer (WLW) to WordPress (WP) I received the following error:

&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;-

Network Connection Error

Error attempting to connect to blog at:

<http://dans-blog.azurewebsites.net/xmlrpc.php>

The underlying connection was closed. An unexpected error occurred on a receive.

&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;-

[<img title="WLWNetworkConnectionError" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="WLWNetworkConnectionError" src="/assets/Posts/2013/09/WLWNetworkConnectionError_thumb.png" width="368" height="247" />](/assets/Posts/2013/09/WLWNetworkConnectionError.png)

&#160;

I had no problem uploading to my blog a couple weeks earlier and hadn’t done any updates or changed anything, so I thought this was strange.&#160; After waiting a day, thinking maybe GoDaddy (my WP host) was having issues, I was still getting the error.&#160; After Googling I found many others reporting this error with varying degrees of success fixing it.&#160; So after trying some suggestions that worked for others (change WLW blog URL from http to https, edit the WP xmlrpc.php file, delete and recreate blog account in WLW, reboot, etc.) I was still getting this same error.

So I decided to try posting a new “test” post, and low and behold it worked.&#160; So it appeared the problem was something with the content of my article.&#160; So I started removing chunks of content from the article and trying to post.&#160; Eventually I found that the problem was being caused by the string “In that post” in the first paragraph of the post.&#160; I thought that maybe some weird hidden characters maybe got in there somehow, but after reviewing the article’s Source I could see that it was just plain old text.&#160; I deleted the sentence and retyped it, but it still didn’t work.&#160; If I just removed “In that post” from the sentence then everything worked fine; very strange&#160; After more playing around, I found that if I just added a comma to the end and made it “In that post,”, that also fixed the problem.&#160; So that’s how I’ve left it.

I don’t know what is special about the string “In that post”;&#160; I created another test article with that string in it and was able to post it without any problems.&#160; Just a weird one-off WLW-WP problem I guess.

&#160;

### Moral of the story

If you run into this same error, before you go muddling with config files and recreating your blog account, just try posting a quick “test” article.&#160; If it works, then the problem is somewhere in your article’s content, so start stripping pieces away until you are able to get it to post successfully and narrow down the culprit.&#160; Also, if you don’t want to publish a half-baked article while you are tracking down the problem, you can do a Save Post Draft To Blog instead of a full Publish to see if you are still getting the error

Happy coding!

&#160;

### &#8212; Update &#8212;

I’ve ran into this problem again when trying to post [this](http://dans-blog.azurewebsites.net/get-autohotkey-to-interact-with-admin-windows-without-running-ahk-script-as-admin/) article.&#160; 3 different spots in the article were causing the problem.&#160; Here is the source of the article with what broke it, and what worked:

1. This broke:

> <li>Click Yes when prompted to < strong > Run With UI Access < / strong > . </li>

(I had to add spaces around all of the 3 characters <, >, and / in the strong tags to get it to post here)

This worked:

> <li>Click Yes when prompted to Run With UI Access.</li>

&#160;

2. This broke:

> <p>Today I stumbled across <a href="<http://www.autohotkey.com/board/topic/70449-enable-interaction-with-administrative-programs/">>this post on the AHK community forums < / a > .&#160;

(I had to add spaces around the each character of the closing </a> tag to get it to post here)

This worked:

> <p>Today I stumbled across <a href="<http://www.autohotkey.com/board/topic/70449-enable-interaction-with-administrative-programs/">>this post</a> on the AHK community forums.&#160;

&#160;

3. This broke:

> the <a href="<http://www.autohotkey.com/docs/commands/RunAs.htm">>RunAs command < / a > .</p>

(Again, I had to add spaces around each character in the closing </a> tag to get it to post here)

This worked:

> the <a href="<http://www.autohotkey.com/docs/commands/RunAs.htm">>RunAs</a> command.</p>

&#160;

I can reproduce this issue every time on that article, and also on this one (which is why I had to change the problem code slightly so I could get it to post here).&#160; So unlike my first encounter with this problem, these ones all seem to be problems parsing html markup tags; specifically the </> characters.&#160; I’m not sure if this is a problem with Windows Live Writer or WordPress, but it is definitely a frustrating bug.&#160; I’m running Windows 8 x64 and the latest versions of WLW and WP.

If you have any thoughts please comment below.
