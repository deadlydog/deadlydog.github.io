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

There were a number of reasons why I wanted to convert my blog away from WordPress:

- I wasn't a fan of the online editor, so I used OpenWriter (previously LiveWriter), but it required some plugins for code snippets and images. Updating posts would result in duplicate images being uploaded.
- Special XML characters common in code (such as `<`, `>`, and `"`) would get converted to their escape sequence when posting through OpenWriter, and I'd always have to go into the online editor and modify my code snippets to transform things like `&lt;` back into their proper character.
- I wanted to write my posts in MarkDown so that I don't need a special editor or to mess with HTML. I tried the WordPress plugins, but they didn't meet my expectations.
- I can host my Jekyll site on GitHub pages for free. Using a git repo is familiar to me, and it means all changes are in source control and that there's no external database dependency to manage and maintain.

### Poor updating experience

WordPress constantly had new upgrades that needed to be applied, both for WordPress itself, and all of the plugins I used. My main issue with this was often the updates would fail, often resulting in taking my blog offline until I manually rolled everything back; I learned very quickly to backup all site files before doing any updates. I would then need to investigate _why_ the update was failing, and manually fix things up. This often involved grabbing the plugin's updated source code and manually applying the updated files to my WordPress instance, rather than using the `Update` button in the WordPress GUI. I'm not sure if this is a common problem for other WordPress users, but it was for me for several years.

## How I migrated from WordPress to Jekyll

I used the [WordPress to Jekyll Exporter plugin](https://github.com/benbalter/wordpress-to-jekyll-exporter), which worked great. I did have a problem with it at first though, in that [it kept generating a zero-byte zip file](https://github.com/benbalter/wordpress-to-jekyll-exporter/issues/145). Fortunately I was able to just roll it back from v2.3.0 to 2.2.3 and that version worked properly.

### Massaging the Jekyll data

Once I had the data out WordPress and in text files in the Jekyll file structure, I there were a few changes I needed to do before it would show correctly:

- Update all image links to use relative paths. When I exported the site the images were exported as well, but the image links in the posts were using an absolute URL path that pointed back to my WordPress blog, rather than pointing to the local image files.
- Update the FrontMatter on each post to match what my theme expected. For example, each post had a `layout: post` value, but my theme expected `layout: single`.
- (optional) Remove extra unnecessary FrontMatter variables from all of the posts. e.g. Remove the `id`, `jabber_published`, and `guid` variables. They weren't hurting anything by being there, but I like to keep my files as clean as possible.
- (optional) I also removed the `author` and `layout` variables from all my posts and instead set up default values in the `_config.yml` file. This just helps prevent duplicate text in all my posts.
- (optional) Update all of my posts to convert HTML to Markdown. HTML displayed fine, I'm just a bit picky and try to stick to pure Markdown.

## Finding a Jekyll theme to use


## Using Staticman to get comments working in Jekyll

- Add comment support.
  - Tutorials: https://mademistakes.com/articles/jekyll-static-comments/ and https://mademistakes.com/articles/improving-jekyll-static-comments/

## Other manual changes I made to the theme

