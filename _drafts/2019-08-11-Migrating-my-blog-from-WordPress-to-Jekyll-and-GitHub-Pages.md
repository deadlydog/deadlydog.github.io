---
title: "Migrating my blog from WordPress to Jekyll and GitHub Pages"
permalink: /Migrating-my-blog-from-WordPress-to-Jekyll-and-GitHub-Pages/
date: 2011-04-17T22:58:00-06:00
last_modified_at: 2016-03-09T16:20:02-05:00
comments_locked: false
categories:
  - Jekyll
  - GitHub Pages
  - WordPress
tags:
  - Jekyll
  - GitHub Pages
  - WordPress
  - Migrate
---

## Why I migrated from WordPress to Jekyll

- Wasn't a fan of the online editor, so I used OpenWriter (previously LiveWriter), but it required some plugins for code snippets and images. Updating posts would result in duplicate images being uploaded.
- Special XML characters common in code (such as `<`, `>`, and `"`) would get converted to their escape sequence when posting through OpenWriter, and I'd always have to go into the online editor and modify my code snippets to transform things like `&lt;` back into their proper character.
- I wanted to write my posts in MarkDown so that I don't need a special editor. I tried the WordPress plugins, but they didn't meet my expectations.

### Poor updating experience

WordPress constantly had new upgrades that needed to be applied, both for WordPress itself, and all of the plugins I used. My main issue with this was often the updates would fail for some reason, many times taking my blog offline until I manually rolled everything back; I learned very quickly to backup all site files before doing any updates. I would then need to investigate _why_ the update was failing, and manually fix things up. This often involved grabbing the plugin's updated source code and manually applying the updated files to my WordPress instance, rather than using the `Update` button in the WordPress GUI. I'm not sure if this is a common problem for other WordPress users, but it was for me.

## How I migrated from WordPress to Jekyll


## Finding a Jekyll theme to use


## Using Staticman to get comments working in Jekyll

- Add comment support.
  - Tutorials: https://mademistakes.com/articles/jekyll-static-comments/ and https://mademistakes.com/articles/improving-jekyll-static-comments/

## Other manual changes I made to the theme

