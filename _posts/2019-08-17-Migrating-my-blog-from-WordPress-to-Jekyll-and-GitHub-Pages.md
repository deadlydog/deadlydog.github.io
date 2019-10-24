---
title: "Migrating my blog from WordPress to Jekyll and GitHub Pages"
permalink: /Migrating-my-blog-from-WordPress-to-Jekyll-and-GitHub-Pages/
comments_locked: false
categories:
  - Jekyll
  - GitHub Pages
  - WordPress
  - Staticman
tags:
  - Jekyll
  - GitHub Pages
  - WordPress
  - Staticman
  - Migrate
---

You may have noticed my blog has a very different look now! It used to look like this.

![Old WordPress Blog Screenshot](/assets/Posts/Migrating-my-blog-from-WordPress-to-Jekyll-and-GitHub-Pages/OldWordPressBlogScreenshot.png)

I decided to migrate my blog from WordPress to Jekyll, and this blog post highlights my reasons why and some of my experiences when doing so.

## What is Jekyll

You've probably heard of WordPress, but maybe not Jekyll. They are both platforms for hosting blog content. The big difference, in my eyes, is that WordPress stores all of it's data in a MySQL database, while Jekyll's data is all stored in text files. This means your entire blog contents can be stored in source control, such as Git.

Jekyll is used to compile the file contents and output all of the files for your static website, typically by running a command like `bundle exec jekyll serve`. Jekyll typically supports websites with static content, making it ideal for blogs. Jekyll leverages the [Liquid][LiquidWebsiteUrl] programming language for flow control (if statements, loops, etc.) and [FrontMatter][FrontMatterWebsiteUrl] for variables. It also allows you to write your website content in [Markdown][MarkdownWebsiteUrl], as well as HTML.

Visit [the official Jekyll site](https://jekyllrb.com/) to learn more and see how to get started with it.

## Why I migrated from WordPress to Jekyll

You may have noticed that I haven't blogged in quite a while; almost 2 years! While I was busy with work and family, another contributor was simply that the technology didn't make it _easy_ to post new content.

Reasons why I wanted to convert my blog away from WordPress:

- __The Editor__ - I wasn't a fan of the WordPress online editor, so I used [Open Live Writer](http://openlivewriter.org). It provided a nicer experience, but required some plugins for code snippets and images to work properly. Also, updating posts would often result in duplicate images and attachments being uploaded. It just wasn't a great experience, and it required a bit of work to get everything setup correctly again after reinstalling Windows. Also, when editing posts later in WordPress, I'd have to wade through generated HTML code as well instead of focusing just on my content.
- __Special Characters__ - Special XML characters common in code (such as `<`, `>`, and `"`) would get converted to their escape sequence when posting through Open Live Writer, and I'd always have to go into the online editor and modify my code snippets to transform things like `&lt;` back into their proper character.
- __Markdown__ - I wanted to write my posts in Markdown so that I could focus on my content, and less on it's presentation. It also meant I wouldn't need lean on a special editor or to mess with HTML. I tried a few WordPress plugins, but ultimately they didn't meet my expectations.
- __Update Nightmares__ - WordPress and the plugins constantly had new updates that needed to be applied. My main issue was often the updates would fail, leaving my blog offline until I manually fixed things up; I learned very quickly to backup all files and the database before doing any updates. The resolution often involved grabbing the new version's updated source code and manually applying the updated files to my WordPress instance over FTP, rather than using the `Update` button in the WordPress GUI. I'm not sure if this is a common problem for other WordPress users, but it was for me for several years.
- __Cost__ - I was paying $150+/year for web hosting with GoDaddy. When renewal time came around, I decided to migrate my WordPress blog from GoDaddy to Azure. This was even more expensive at ~$60/month to host the website and MySQL database, but I get $70/month free Azure credits with my Visual Studio subscription. So even though Azure was more expensive, it was technically free for me; however, it meant I couldn't use those Azure credits on other things.

Reasons why I decided to use Jekyll:

- __Source Control__ - As a software developer, using a git repo is familiar to me, and it means all changes are in source control. There's no external database dependency to manage and maintain. It also means no longer having to take backups.
- __Markdown__ - It supports writing in your posts in [Markdown][MarkdownWebsiteUrl]. Enough said.
- __Everything Is Text__ - Updating and creating posts is easy and can be done from any text editor, even just with the GitHub online editor. There's no magic going on behind the scenes (other than the filename convention and FrontMatter); everything is plain text.
- __Customization__ - There are tons of themes available. If you don't like them, you can customize them, or create your own from scratch, assuming you know HTML, CSS, Javascript, Liquid, and FrontMatter (before this migration I hadn't heard of [Liquid][LiquidWebsiteUrl] or [FrontMatter][FrontMatterWebsiteUrl]).
- __Preview Changes Locally__ - Jekyll allows you to host the site on your local machine to preview any changes you've made, whether it's just a new post that you've written, or large sweeping change like changing your theme. This way I can preview my changes and make sure everything is the way I want it before pushing it to the live site.
- __It's Free__ - Jekyll itself is [open source](https://github.com/jekyll/jekyll) and free.
- __Host Your Site Anywhere__ - Because Jekyll simply outputs the HTML files of your site, you can host your site wherever you like; there are no special requirements from your hosting provider (e.g. supporting MySQL).
- __Free Hosting On GitHub Pages!__ - [GitHub Pages supports Jekyll](https://help.github.com/en/articles/setting-up-your-github-pages-site-locally-with-jekyll) and allows you to host your site completely for free! [More info](https://jekyllrb.com/docs/github-pages/).

### Why didn't I go to some other free hosted service

There are a ton of [free blog hosting options](https://www.techradar.com/news/the-best-free-blogging-sites) out there, even programming focused ones like [dev.to](https://dev.to), so why did I decide to go this route?

I originally started my blog with [GeeksWithBlogs.net](http://geekswithblogs.net). A couple years after starting my blog, they announced that they were shutting down (although they still seem to be around today, \*head-scratch\*). With the thought of losing all of the hard work I had put into my blog content (even if it wasn't great), it was then that I decided I was going to move into a self-hosted blog alternative. One where even if my provider disappeared one day, I wouldn't lose all of my hard work.

Luckily there was an existing process for exporting from GeeksWithBlogs to WordPress, and that's what I went with. With WordPress I would be able to have database backups and manually put my files into source control. Back then (circa 2012), this was very appealing. I wouldn't be relying on a 3rd party service anymore, and would truly own (and host) my content. I decided to self-host my WordPress site so that I wouldn't be relying on some 3rd party provider that might go under again, and so that I had more options between themes and plugins to use, while not subjecting my readers invasive advertisements.

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

### Different ways of leveraging a Jekyll theme

This was probably one of the most confusing parts of the migration for me. There are 3 different ways you can use a theme in Jekyll, and not all themes support all 3 ways. The 3 options you have are:

1. __Fork The Theme Repository__ - Basically clone all of the source code, and then go ahead and add your posts to it and customize it however you like. This is perhaps the most straightforward approach, but it doesn't allow you to automatically take new updates to the theme; you would have to manually merge changes into your forked repository.
1. __Ruby Gems__ - [The official docs](https://jekyllrb.com/docs/themes/) describe this better than I ever could, but essentially you have a `Gemfile` that lists the dependencies of your site, including your theme. You run a command like `bundle update` to update your gem versions and pull the latest version of the gem's files into your project. This is how you update your site to a newer version of the theme. In the .Net world, this is similar to a NuGet package. You don't actually see the theme files in your project like when forking a theme repository though, so it helps keep your repo nice and clean with only the files you care about. Most themes support this method.
1. __GitHub Remote Theme Repository__ - I believe this option is only available for themes hosted in GitHub (which most are), and not all themes support it. This method allows you to always be on the latest version of the theme without having to run any addition commands (e.g. `bundle update`). Rather than including the theme in your `Gemfile`, you instead include a line in your `_config.yml` that references the remote theme repo. e.g. `remote_theme: mmistakes/minimal-mistakes` to always use the latest version of the theme, or `mmistakes/minimal-mistakes@26c1989` to use a specific branch/commit/tag of the theme. This allows your site to always be on the latest version of the theme without any intervention, or use the `@branch/commit/tag` syntax to stay on a specific version until you decide to update. As with Ruby Gems, this option does not store the theme files in your repo, keeping your repo nice and clean. GitHub Pages has several built-in themes that [you can use for your Jekyll site](https://help.github.com/en/articles/adding-a-jekyll-theme-to-your-github-pages-site) as well if you like. [More info](https://github.com/benbalter/jekyll-remote-theme).

I ended up using the [minimal mistakes][MinimalMistakesWebsiteUrl] theme and the remote theme repository strategy.

### Troubleshooting Jekyll theme issues

I first started out using the Ruby Gems approach, but ran into issues where the site wouldn't display my posts; it seemed to work with some themes (ones I didn't want to actually use), but not others. I didn't understand why at the time, but it was due to my lack of understanding of how `Liquid` and `FrontMatter` worked with the themes. Not all themes looks for the same variables; some expect your posts to have `layout: post` defined on them, others want `layout: posts`, or `layout: single`, or something else. If the posts don't have the theme's expected FrontMatter variables defined on them, they might not be recognized as themes, causing them to not be displayed, or to be displayed, but not look how you expect them to.

In addition to specific FrontMatter variables, different themes often expect to find different variables defined in your `_config.yml` file as well. While there are some standard variables that most themes expect to find, such as `name` and `description`, theme's may expect other variables as well depending on what features they offer. For example, the [minimal mistakes theme][MinimalMistakesWebsiteUrl] expected to find a `words_per_minute` variable so that it can display the estimated reading time of a post.

With Jekyll, `Liquid` accesses variables defined in the `_config.yml` by using the `site` keyword. For example, in my theme's code it accesses the `words_per_minute` variable by using `site.words_per_minute`. Variables defined in a posts FrontMatter are accessed using the `post` keyword, such as `post.date`, where `date` would be a FrontMatter variable defined at the top of my posts.

Also, some themes only enable certain features when Jekyll is running in production mode. For example, [minimal mistakes][MinimalMistakesWebsiteUrl] only displays advertisements and the comment posting form when running in production mode. Running Jekyll in production mode involves setting the `JEKYLL_ENV` variable to `production`. You can set this variable when starting your Jekyll site by using `JEKYLL_ENV=production bundle exec jekyll serve`. For some reason, this wouldn't work for me when running this command in PowerShell, and I instead had to use Bash (I'm running Windows btw). If using GitHub Pages to host your site, it will have the variable set to `production` by default.

So if your site is not displaying how you expect it to, read the theme's documentation (if it has any), or dig right into its code to see what variables it expects to be defined at the `site` and `post` level.

Lastly, I've found that sometimes the best or quickest way to troubleshoot issues is to find somebody elses website that's using the same theme or integrations as you and take a look at their code. Feel free to check out [the code used to host this blog](https://github.com/deadlydog/deadlydog.github.io).

## Running the Jekyll site on GitHub Pages

Getting your site up and running on GitHub Pages is actually super easy. GitHub has [some help docs](https://help.github.com/en/articles/using-jekyll-as-a-static-site-generator-with-github-pages) that walk you through it. The main points are:

- In your repository's `Settings` page, enable `GitHub Pages`.
- If your Jekyll site is on your User or Organization page (e.g. username.github.io), then GitHub Pages will build your site from the `master` branch.
- If your Jekyll site is on a Project page, it will build your site from the `gh-pages` branch.
- The `_config.yml` file and other Jekyll files must be at the root of the branch, or optionally [in a `docs` folder at the root of the branch](https://help.github.com/en/articles/configuring-a-publishing-source-for-github-pages).

Pretty much though, if you're able to build and serve your Jekyll site on your local computer, just push the files to your GitHub repo and it will compile and host your site for you. It may take a couple minutes for any changes to show on the GitHub Pages after you've pushed them to the GitHub repo, as it needs to compile your Jekyll site to generate the GitHub Pages files.

## Using Staticman to get comments in Jekyll

With Jekyll producing a static website and not having a database, it doesn't natively lend itself to comment submissions. Luckily, there are several options for adding comments to your Jekyll site, with one of the most popular being [Disqus](https://disqus.com). I decided to go with [Staticman][StaticmanWebsiteUrl] for my blog because, again, I didn't want to be reliant on a 3rd party; I want to own all of the content and not worry about a 3rd party going out of business, changing their pricing model, or figuring out how to export my comments out of their system at a later point in time.

With [Staticman][StaticmanWebsiteUrl], you [add a form](https://mademistakes.com/articles/jekyll-static-comments/) [to your website](https://mademistakes.com/articles/improving-jekyll-static-comments/) (if your theme doesn't provide a built-in one). When a comment is submitted, it sends the information to the Staticman API, and then Staticman will open up a pull request against your GitHub (or GitLab) repository with the information. Once the pull request is completed, the comment will now be inside of your Git repository, causing GitHub Pages to rebuild your Jekyll site and display the new comment.

The idea of how Staticman works is simple enough, and actually pretty clever. Unfortunately when I went to integrate Staticman into my Jekyll site (summer 2019), there were issues with Staticman. The main issue was that the official documentation says to use a free publicly hosted instance of the Staticman API, and gives the URL for it. The problem is that it's using a free hosting plan and is limited to a certain number of requests per day. As more and more people adopted it, it began reaching it's quota very often, resulting in frequent `409 Too many requests` errors when people tried to submit comments.

This led me down the path of hosting my own private instance of Staticman. Luckily, a few other people had already taken this route as well and [blogged](https://www.datascienceblog.net/post/other/staticman_comments/) [some](https://vincenttam.gitlab.io/post/2018-09-16-staticman-powered-gitlab-pages/2/) [nice](https://bloggerbust.ca/series/staticman-with-github-and-zeit-now/) [tutorials](https://github.com/eduardoboucas/staticman/issues/293) for various hosting providers. The unfortunate bit was the official Staticman docs had not been updated to include any of this information, so finding it involved hunting through numerous GitHub issues on the Staticman repository. Also, many of the code changes required for these hosting options had not been merged into the Staticman `dev` or `master` branches yet, so it meant using an "unofficial" Staticman branch.

For myself, I ended up using Heroku to host my Staticman instance. It's completely free, and since I'm the only one hitting the API, I shouldn't run over my API request limit. [This GitHub issue](https://github.com/eduardoboucas/staticman/issues/294) provides more information around exactly what I did and the issues I encountered and their resolutions. My main issues had to do around using ReCaptcha on comment submissions, but I got it all figured out in the end.

## Bonus section: My editor preference

While I can technically use any text editor to create blog posts, my favourite editor is VS Code, for a number of reasons:

- It has native Git support, and pushing my changes up to GitHub is a breeze.
- It has a built-in terminal, making it easy to build and serve the Jekyll site locally to preview my changes.
- It has a built-in Markdown previewer, so if I don't want to actually spin up my site locally to preview the changes, I can use the built-in editor to quickly verify that my Markdown syntax is all correct and looks the way I expect.
- It has some amazing extensions that make writing posts in markdown a great experience:
  - [Markdown All in One](https://marketplace.visualstudio.com/items?itemName=yzhang.markdown-all-in-one)
  - [markdownlint](https://marketplace.visualstudio.com/items?itemName=DavidAnson.vscode-markdownlint)
  - [Path Autocomplete](https://marketplace.visualstudio.com/items?itemName=ionutvmi.path-autocomplete)
  - [Code Spell Checker](https://marketplace.visualstudio.com/items?itemName=streetsidesoftware.code-spell-checker)
- It also has many extensions for doing web development, which is handy if you want to customize your Jekyll site:
  - [HTML Snippets](https://marketplace.visualstudio.com/items?itemName=abusaidm.html-snippets)
  - [HTML CSS Support](https://marketplace.visualstudio.com/items?itemName=ecmel.vscode-html-css)
  - [Liquid](https://marketplace.visualstudio.com/items?itemName=sissel.shopify-liquid)

## Conclusion

Hopefully this has given you some insight or tips into what you can do with your own blog. I chose Jekyll because it allows me to host the website wherever I like, and I completely own my content and don't have to worry about my hosting provider going out of business. I also chose it because GitHub Pages natively supports it, and can host my blog completely for free.

The hardest part was getting my existing content changed into a format that my chosen theme expected. I think starting fresh from scratch would be much easier, regardless of what theme you choose to go with. I'm not certain that I would recommend Jekyll for somebody non-technical (like my mom), but for software developers who like to write in Markdown it's definitely a top contender.

Happy <strike>coding</strike> blogging :)

[LiquidWebsiteUrl]: https://shopify.github.io/liquid/
[FrontMatterWebsiteUrl]: https://jekyllrb.com/docs/front-matter/
[MarkdownWebsiteUrl]: https://www.markdownguide.org/getting-started
[MinimalMistakesWebsiteUrl]: https://mmistakes.github.io/minimal-mistakes/
[StaticmanWebsiteUrl]: https://staticman.net/
