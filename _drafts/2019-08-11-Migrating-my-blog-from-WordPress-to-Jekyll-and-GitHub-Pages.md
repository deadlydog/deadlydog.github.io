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

## What is Jekyll

You've probably heard of WordPress, but maybe not Jekyll. They are both platforms for hosting blog content. The big difference, in my eyes, is that WordPress stores all of it's data in a MySQL database, while Jekyll's data is all stored in text files. This means your entire blog contents can be stored in source control, such as Git.

Jekyll is used to compile the file contents and output all of the files for your static website, typically by running a command like `bundle exec jekyll serve`. Jekyll typically supports websites with static content, making it ideal for blogs. Jekyll leverages the [Liquid](https://shopify.github.io/liquid/) programming language for flow control (if statements, loops, etc.) and [FrontMatter](https://jekyllrb.com/docs/front-matter/) for variables. It also allows you to write you website content in Markdown, as well as HTML.

Visit [the official Jekyll site](https://jekyllrb.com/) to learn more and see how to get started with it.

## Why I migrated from WordPress to Jekyll

Reasons why I wanted to convert my blog away from WordPress:

- __The Editor__ - I wasn't a fan of the WordPress online editor, so I used [Open Live Writer](http://openlivewriter.org). It provided a nice experience, but required some plugins for code snippets and images to work properly. Also, updating posts would often result in duplicate images and attachments being uploaded.
- __Special Characters__ - Special XML characters common in code (such as `<`, `>`, and `"`) would get converted to their escape sequence when posting through Open Live Writer, and I'd always have to go into the online editor and modify my code snippets to transform things like `&lt;` back into their proper character.
- __Markdown__ - I wanted to write my posts in Markdown so that I could focus on my content, and less on it's presentation. It also meant I wouldn't need lean on a special editor or to mess with HTML. I tried a few WordPress plugins, but ultimately they didn't meet my expectations.
- __Update Nightmares__ - WordPress and the plugins constantly had new updates that needed to be applied. My main issue was often the updates would fail, leaving my blog offline until I manually fixed things up; I learned very quickly to backup all files and the database before doing any updates. The resolution often involved grabbing the new version's updated source code and manually applying the updated files to my WordPress instance over FTP, rather than using the `Update` button in the WordPress GUI. I'm not sure if this is a common problem for other WordPress users, but it was for me for several years.
- __Cost__ - I was paying $150+/year for web hosting with GoDaddy. When renewal time came around, I decided to migrate my WordPress blog from GoDaddy to Azure. This was even more expensive at ~$60/month to host the website and MySQL database, but I get $70/month free Azure credits with my Visual Studio subscription. So even though Azure was more expensive, it was technically free for me; however, it meant I couldn't use those Azure credits on other things.

Reasons why I decided to use Jekyll:

- __Source Control__ - As a software developer, using a git repo is familiar to me, and it means all changes are in source control. There's no external database dependency to manage and maintain. It also means no longer having to take backups.
- __Markdown__ - It supports writing in your posts on Markdown. Enough said.
- __Everything Is Text__ - Updating and creating posts is easy and can be done from any editor, even just with the GitHub online editor. There's no magic going on behind the scenes; everything is plain text.
- __Customization__ - There are tons of themes available for use straight away. If you don't like them, you can customize them, or create your own from scratch, assuming you know HTML, CSS, Javascript, Liquid, and FrontMatter (before this migration I hadn't heard of Liquid or FrontMatter).
- __It's Free__ - I can host my Jekyll site on GitHub Pages for free!

### Why didn't I go to some other free hosted service

There are a ton of [free blog hosting options](https://www.techradar.com/news/the-best-free-blogging-sites) out there, even programming focused ones like [dev.to](https://dev.to), so why did I decide to go this route?

I originally started my blog with [GeeksWithBlogs.net](http://geekswithblogs.net). A couple years after starting my blog, they announced that they were shutting down (although they still seem to be around today, **head-scratch*). With the thought of losing all of the hard work I had put into my blog content (even if it wasn't great), it was then that I decided I was going to move into a self-hosted blog alternative. One where even if my provider disappeared one day, I wouldn't lose all of my hard work. Luckily there was an existing process for exporting from GeeksWithBlogs to WordPress, and that's what I went with. With WordPress I would be able to have database backups and manually put my files into source control. Back then (circa 2012), this was very appealing. I wouldn't be relying on a 3rd party service anymore, and would truly own (and host) my content. I decided to self-host my WordPress site so that I wouldn't be relying on some 3rd party provider that might go under again, and so that I had more options between themes and plugins to use, while not subjecting my readers invasive advertisements.

Fast-forward 7 years; being able to natively store my blog in source control with free hosting is even more appealing.

## How I migrated from WordPress to Jekyll

If you're starting a new blog or website from scratch, using Jekyll is pretty straightforward and you can get up and running in a few minutes. I had a lot of existing content in WordPress that I wanted to migrate though, so I used the [WordPress to Jekyll Exporter plugin](https://github.com/benbalter/wordpress-to-jekyll-exporter), which worked great. I did have a problem with it at first though, in that [it kept generating a zero-byte zip file](https://github.com/benbalter/wordpress-to-jekyll-exporter/issues/145). Fortunately I was able to just roll it back from v2.3.0 to 2.2.3 and that version worked properly.

### Massaging the Jekyll data

Once I had the data out WordPress and in text files in the Jekyll file structure, there were a few changes I needed to make before it would show correctly:

- __Image / Attachment URLs__ - When I exported the site the images and attachments were exported as well, but the their links in the posts were still using an absolute URL path that pointed back to my WordPress blog URL, rather than pointing to the local image / attachment files. This meant having to go through all my posts and updating the image and attachment links to use relative paths.
- __FrontMatter Variables__ - I had to update the FrontMatter on each post to match what my theme expected. For example, each post had a `layout: post` value, but my theme expected `layout: single`.
  - (optional) Remove extra unnecessary FrontMatter variables from all of the posts. e.g. Remove the `id`, `jabber_published`, and `guid` variables, as they weren't used by my theme. They weren't hurting anything by being there, but I like to keep my files as clean as possible.
  - (optional) I also removed the `author` and `layout` variables from all my posts and instead set up default values in the `_config.yml` file. This just helps prevent duplicate text in all my posts FrontMatter.
- __Convert HTML To Markdown (optional)__ - I went through all of my posts to convert any HTML to Markdown. HTML displayed fine, I'm just a bit picky and try to stick to pure Markdown. It also ensures that my older posts will look the same as my newer ones, as some of the HTML had colors and other styles hard-coded in it that would override whatever theme I chose.

## Finding a Jekyll theme to use

There are [a lot of great Jekyll themes to choose from](https://rubygems.org/search?utf8=%E2%9C%93&query=jekyll-theme). Some things to be aware of when choosing a theme though is that some actually provide different features, such as:

- Google analytics
- Advertisements
- Support for comments
- Metadata on your posts (estimated read time, created and updated dates, etc.)

If the theme you choose doesn't support a specific feature, you can always add it in yourself, but it does require some development effort and know-how. It's great if you can find one that not only looks great, but that also natively supports everything you want.

### Different ways of leveraging a theme

This was probably one of the most confusing parts of the migration for me. There are 3 different ways you can use a theme in Jekyll, and not all themes support all 3 ways. The 3 options you have are:

1. __Fork The Theme Repository__ - Basically clone all of the source code, and then go ahead and add your posts to it and customize it however you like. This is perhaps the most straightforward approach, but it doesn't allow you to automatically take new updates to the theme; you would have to manually merge updated into your fork.
1. __Ruby Gems__ - [The official docs](https://jekyllrb.com/docs/themes/) describe this better than I ever could, but essentially you have a `Gemfile` that lists the dependencies of your site, including your theme. You run a command like `bundle update` to update your gem versions and pull the latest version of the gem's files into your project. This is how you update your site to a newer version of the theme. In the .Net world, this is similar to a NuGet package. You don't actually see the theme files in your project like when forking a theme repository though, so it helps keep your repo nice and clean with only the files you care about. Most themes support this method.
1. __GitHub Remote Repository__ - I believe this option is only available for themes hosted in GitHub (which most are), and not all themes support it. This method allows you to always be on the latest version of the theme without having to run any addition commands (e.g. `bundle update`). Rather than including the theme in your `Gemfile`, you instead include a line in your `_config.yml` that references the remote theme repo. e.g. `remote_theme: mmistakes/minimal-mistakes` to always use the latest version of the theme, or `mmistakes/minimal-mistakes@26c1989` to use a specific branch/commit/tag of the theme. This allows your site to always be on the latest version of the theme without any intervention, or use the `@branch/commit/tag` syntax to stay on a specific version until you decide to update. As with Ruby Gems, this option does not store the theme files in your repo, keeping your repo nice and clean.

I ended up using the [minimal mistakes](https://mmistakes.github.io/minimal-mistakes/) theme and the remote repository strategy.

### Troubleshooting theme issues

I first started out using the Ruby Gems approach, but ran into issues where the site wouldn't display my posts; it seemed to work with some themes (ones I didn't want to actually use), but not others. I didn't understand why at the time, but it was due to my lack of understanding of how `Liquid` and `FrontMatter` worked with the themes. Not all themes looks for the same variables; some expect your posts to have `layout: post` defined on them, others want `layout: posts`, or `layout: single`, or something else. If the posts don't have the theme's expected FrontMatter variables defined on them, they might not be recognized as themes, causing them to not be displayed, or to be displayed, but not look how you expect them to.

In addition to specific FrontMatter variables, different themes often expect to find different variables defined in your `_config.yml` file as well. While there are some standard variables that most themes expect to find, such as `name` and `description`, theme's may expect other variables as well depending on what features they offer. For example, the minimal mistakes theme expected to find a `words_per_minute` variable so that it can display the estimated reading time of a post.

With Jekyll, `Liquid` accesses variables defined in the `_config.yml` by using the `site` keyword. For example, in my theme's code it accesses the `words_per_minute` variable by using `site.words_per_minute`. Variables defined in a posts FrontMatter are accessed using the `post` keyword, such as `post.date`, where `date` is a FrontMatter variable that I define at the top of all my posts.

The moral is, if your site is not displaying how you expect it to, read the theme's documentation (if it has some), or dig right into it's code to see what variables it expects to be defined at the `site` and `post` level.

## Using Staticman to get comments working in Jekyll

There are several options for adding comments to your Jekyll site, such as [Disqus](https://disqus.com).

- Add comment support.
  - Tutorials: https://mademistakes.com/articles/jekyll-static-comments/ and https://mademistakes.com/articles/improving-jekyll-static-comments/

## Other manual changes I made to the theme

