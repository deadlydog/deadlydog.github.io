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

## The problem

I love GitHub, but one thing that I and most developers hate is that the pages that show source code (Pull requests, commits, blobs) are locked to a fixed width, and it’s only about 900 pixels. Most developers have widescreen monitors, so their code lines are typically longer than 900 pixels. This can make viewing code on GitHub painful because you have to constantly horizontally scroll to see a whole line of code. I honestly can’t believe that after years GitHub still hasn’t fixed this. It either means that the GitHub developers don’t dogfood their own product, or the website designers (not programmers) have the final say on how the site looks, in which case they don’t know their target audience very well. Anyways, I digress.

## My solution

To solve this problem I wrote a GreaseMonkey user script 2 years ago that expands the code section on GitHub to fill the width of your browser, and it works great. The problem was that [GreaseMonkey is a FireFox-only extension](https://addons.mozilla.org/en-US/firefox/addon/greasemonkey/). Luckily, these days most browsers have a GreaseMonkey equivalent:

Internet Explorer has one called [Trixie](http://www.pcworld.com/product/952510/trixie.html).

Chrome has one called [TamperMonkey](https://chrome.google.com/webstore/detail/tampermonkey/dhdgffkkebhmkfjojejmpbldmpobfkfo). Chrome also supports user scripts natively so you can [install them without TamperMonkey](http://stackoverflow.com/a/13672143/602585), but TamperMonkey helps with the install/uninstall/managing of them.

So if you have GreaseMonkey or an equivalent installed, then you can simply go ahead and [install my user script for free](https://greasyfork.org/scripts/1711-make-github-pull-request-commit-and-blob-pages-full-width) and start viewing code on GitHub in widescreen glory.

Alternatively, I have also released a free Chrome extension in the Chrome Web Store called [Make GitHub Pages Full Width](https://chrome.google.com/webstore/detail/make-github-pages-full-wi/dfpgjcidmobcpaoolhgchdcmdgenbaoa). When you install it from the store you get all of the added Store benefits, such as having the extension sync across all of your PCs, automatically getting it installed again after you format your PC, etc.

## Results

If you install the extension and a code page doesn’t expand it’s width to fit your page, just refresh the page. If anybody knows how to fix this issue please let me know.

And to give you an idea of what the result looks like, here are 2 screenshots; one without the extension installed (top, notice some text goes out of view), and one with it (bottom).

![Without Full Width](/assets/Posts/2014/05/WithoutFullWidth.png)

![With Full Width](/assets/Posts/2014/05/WithFullWidth.png)

Happy coding!
