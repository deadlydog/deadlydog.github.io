---
id: 767
title: Browser Extensions To Expand GitHub Code Pages To Fill The Full Width Of Your Browser
date: 2014-05-27T14:26:46-06:00
guid: http://dans-blog.azurewebsites.net/?p=767
permalink: /browser-extensions-to-expand-github-code-pages-to-fill-the-full-width-of-your-browser/
categories:
  - Browser
  - Productivity
tags:
  - Browser
  - Code
  - Extension
  - Fill
  - Full
  - GitHub
  - Review
  - View
  - Width
---
###

### The problem

I love GitHub, but one thing that I and most developers hate is that the pages that show source code (Pull requests, commits, blobs) are locked to a fixed width, and it’s only about 900 pixels.&#160; Most developers have widescreen monitors, so their code lines are typically longer than 900 pixels.&#160; This can make viewing code on GitHub painful because you have to constantly horizontally scroll to see a whole line of code.&#160; I honestly can’t believe that after years GitHub still hasn’t fixed this.&#160; It either means that the GitHub developers don’t dogfood their own product, or the website designers (not programmers) have the final say on how the site looks, in which case they don’t know their target audience very well.&#160; Anyways, I digress.

### My solution

To solve this problem I wrote a GreaseMonkey user script 2 years ago that expands the code section on GitHub to fill the width of your browser, and it works great. The problem was that [GreaseMonkey is a FireFox-only extension](https://addons.mozilla.org/en-US/firefox/addon/greasemonkey/).&#160; Luckily, these days most browsers have a GreaseMonkey equivalent:

Internet Explorer has one called [Trixie](http://www.pcworld.com/product/952510/trixie.html).

Chrome has one called [TamperMonkey](https://chrome.google.com/webstore/detail/tampermonkey/dhdgffkkebhmkfjojejmpbldmpobfkfo). Chrome also supports user scripts natively so you can [install them without TamperMonkey](http://stackoverflow.com/a/13672143/602585), but TamperMonkey helps with the install/uninstall/managing of them.

So if you have GreaseMonkey or an equivalent installed, then you can simply go ahead and [install my user script for free](https://greasyfork.org/scripts/1711-make-github-pull-request-commit-and-blob-pages-full-width) and start viewing code on GitHub in widescreen glory.

Alternatively, I have also released a free Chrome extension in the Chrome Web Store called [Make GitHub Pages Full Width](https://chrome.google.com/webstore/detail/make-github-pages-full-wi/dfpgjcidmobcpaoolhgchdcmdgenbaoa).&#160; When you install it from the store you get all of the added Store benefits, such as having the extension sync across all of your PCs, automatically getting it installed again after you format your PC, etc.

### Results

If you install the extension and a code page doesn’t expand it’s width to fit your page, just refresh the page.&#160; If anybody knows how to fix this issue please let me know.

And to give you an idea of what the result looks like, here are 2 screenshots; one without the extension installed (top, notice some text goes out of view), and one with it (bottom).

[<img title="WithoutFullWidth" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="WithoutFullWidth" src="/assets/Posts/2014/05/WithoutFullWidth_thumb.png" width="600" height="327" />](/assets/Posts/2014/05/WithoutFullWidth.png)

[<img title="WithFullWidth" style="border-left-width: 0px; border-right-width: 0px; background-image: none; border-bottom-width: 0px; padding-top: 0px; padding-left: 0px; display: inline; padding-right: 0px; border-top-width: 0px" border="0" alt="WithFullWidth" src="/assets/Posts/2014/05/WithFullWidth_thumb.png" width="600" height="327" />](/assets/Posts/2014/05/WithFullWidth.png)

Happy coding!
