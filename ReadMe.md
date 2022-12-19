# Daniel Schroeder's Programming Blog

Welcome to the home of my personal programming blog.
If you're looking for the actual blog and not the code that generates it, you can find it at [blog.danskingdom.com](https://blog.danskingdom.com).

This repository was initially cloned off of [Minimal Mistakes GitHub Pages Starter][MinimalMistakesGitHubPagesStarterRepoUrl], and then updated with my content and configuration.

I currently use the [Minimal Mistakes theme][MinimalMistakesThemeGitHubRepoUrl]. View [the documentation][MinimalMistakesThemeDocumentationUrl].

## Running Jekyll locally

### Prerequisites

1. Install [Ruby][RubyInstallerDownloadPageUrl] v2.5 (some gems don't support v2.6 yet), as well as have it install MSYS2 after installation.
   - Use `ruby -v` to see which version is installed.
1. Install the Jekyll gem using `gem install jekyll bundler`
   - Use `jekyll -v` to see which version of jekyll is installed.
   - You may need to restart your computer before the VS Code terminal recognizes Ruby.
1. Run `bundle install` in the repository directory to install all of the required gems.

### Run Jekyll locally

To run Jekyll locally, from the site directory run `bundle exec jekyll serve`.

You can include the `--incremental` (or `-I`) parameter to do faster incremental builds.
e.g. `bundle exec jekyll serve --incremental`.
However, this will not rebuild the site when making site-wide changes, such as changes to the layout or styles.

Some features are only enabled for `production` builds (e.g. comments, advertisements).
To build in production mode, you must create/set an environment variable `JEKYLL_ENV=production` before starting jekyll.
You can start Jekyll in production mode using either of these one-liner commands:

- PowerShell - `$Env:JEKYLL_ENV='production'; bundle exec jekyll serve`
- Bash - `JEKYLL_ENV=production bundle exec jekyll serve`

If you've written a draft post in the `_drafts` directory and want it to show up on the site, use `bundle exec jekyll serve --incremental --draft` to start Jekyll.

Jekyll typically runs locally at <http://127.0.0.1:4000/>.

### Keeping up to date

To ensure that the GitHub Pages and other gems are up-to-date, periodically run `bundle update` to update all gems.

To use a newer version of the theme, update the `_config.yml` file and change the line:

```yml
remote_theme: mmistakes/minimal-mistakes@[tag|commit|branch]
```

to use a newer tag/commit from [the theme repo][MinimalMistakesThemeGitHubRepoUrl].

Additionally, since we are overriding some files (see the Customizations section below), you may also need to update those files with newer versions if they have been changed in theme updates.

#### Updating to a newer version of the theme

To update to a newer version of [the minimal-mistakes theme][MinimalMistakesThemeGitHubRepoUrl], do the following:

1. Find the latest stable tag from [the theme's changelog](https://github.com/mmistakes/minimal-mistakes/blob/master/CHANGELOG.md).
1. Update the `_config.yml` file's `remote_theme` attribute to use that tag.
1. Checkout [the minimal-mistakes theme][MinimalMistakesThemeGitHubRepoUrl] repo for that specific tag.
1. In this repo, find all of the files that have `Dan's Customizations` in them.
You will want to copy the code from the files of the official minimal-mistakes repo into the files in this repo, being sure to keep the customizations sections though, if still necessary.
e.g. Copy all of the code from the official footer.html file into this repo's footer.html file, but keep the `Dan's Customizations` section.
1. Test the site locally to ensure it still works as expected.
1. Commit the changes with an appropriate comment and push them to GitHub.

### Installing new gems and themes

Simply modify the `Gemfile` with the new gem to use, and then run `bundle install` to have it install the new gems.

Typically themes are installed from gems, and then you update the `_config.yml` file to specify the new theme to use.

Currently with the MinimalMistakes theme we are not installing it from a gem, but are instead using the remote theme method instead.

## Customizations I've made to the theme / site

Anywhere that I make custom code changes, I try to put `Dan's Customizations` in comments, so doing a search of the repo for `Dan` should find all places I've changed / overwritten code.
This doesn't include the `_config.yml` though, as we're expected to set custom settings in there.

Here's a list of places I've changed code. This typically meant copying the file from the forked minimal-mistakes repo and overriding parts of it:

- `_data/navigation.yml`: Added Home navigation menu item and commented out Tags.
- `_includes/comments.html`: Added logic to sort comments by date.
- `_includes/footer.html`: Added code to the bottom of the file that:
  - Adds advertisements.
  - Adds a Donate section.
  - Dynamically adds a "Copy to clipboard" button to all code blocks.
  - Add verification link for my Mastodon profile.
- `_layouts/home.html`: Added id to 'Recent Posts' heading so we can adjust whitespace around it.
- `_config.yml` file.
- `assets/css/main.scss`:
  - Adjusted font sizes a bit.
  - Added class used for the site description in the masthead.html.
  - Adjusted colors used for inline code text.
  - Added styling for <kbd> elements.
  - Added styles for custom Donate sections.

Here's a list of files I've added:

- Everything in the `_posts` and `_drafts` directories.
- The `_pages\About.md` page.
- The `_pages\.well-known\webfinger.json` page for Mastodon aliases (see [this post](https://hachyderm.io/@shanselman/109537851479089923) and [website](https://webfinger.net)).
- Everything in the `_assets\Posts`, `_assets\Site`, and `_assets\js` directories.
- Everything in the `_data\comments` directory.
- The `favicon.ico` image.
- The `.vscode` directory.
- The `ReadMe.md` file.
- The `ads.txt` file with my Google AdSense info.
- The `CNAME` file was added automatically by GitHub when setting up GitHub Pages to use my custom domain name.
- The `Scripts` directory and all files in it for making some operations easier.

## Additional Info

This site uses [Staticman](https://github.com/eduardoboucas/staticman) to handle comments.
You can find the code and documentation for my Staticman instance [here](https://github.com/deadlydog/deadlydog.github.io-staticman).

### Supported code language highlighters

By default Jekyll uses `Rouge` for syntax highlighting of code blocks.
See [this page][JekyllRogueSyntaxHighlighterSupportedLanguagesUrl] for the list of supported languages.
e.g. `csharp`, `css`, `html`, `http`, `ini`, `json`, `liquid`, `markdown`, `powershell`, `sql`, `text`, `xml`, `yaml`, and many more.

We may want to look at [replacing `Rouge` with `Pygments`][HowToUsePygmentsSyntaxHighlighterWithJekyll] to get [support for more languages][JekyllPygmentsSyntaxHighlighterSupportedLanguagesUrl], like AutoHotkey and batch files. Unfortunately GitHub Pages only supports Rouge at this time (June 2019) so we have to stick with it, but that may change in the future. There are open issues to add more languages to Rouge though, like [Batch][RougeBatchSyntaxHighlightingSupportIssueUrl] and [AutoHotkey][RougeAutoHotkeySyntaxHighlightingSupportIssueUrl] files.

### Supported Kramdown options

By default Jekyll uses `Kramdown` for markdown processing.
See [this page][JekyllKramdownOptionsDocumentationUrl] for the list of supported options that may be used in `_config.yml`.

To show line numbers in code blocks, rather than using the traditional ``` syntax, do this instead (replacing 'powershell' with the appropriate language):

```liquid
{% highlight powershell linenos %}
Your code goes here
{% endhighlight %}
```

### Other sites using the same theme

Here are some other sites using the same theme, so we can see how they've configured theirs and customizations they've made:

- https://mathieubuisson.github.io/ - [GitHub code](https://github.com/MathieuBuisson/MathieuBuisson.github.io)

### Site history

The site was migrated from WordPress to Jekyll in April 2019, and before WordPress it was hosted in GeeksWithBlogs.
This is why the posts before 2019 have additional front matter on them; the tool to export them from WordPress to Jekyll added it.

Some tools I used to convert the site from WordPress to Jekyll:

- [WordPress to Jekyll Exporter][WordPressToJekyllExporterPluginUrl] - Used to migrate all of the posts and uploads to a Jekyll format.
- [wordpress-comments-jekyll-staticman][WordPressCommentsToJekyllStaticmanToolUrl] - After exporting your WordPress blog comments to an xml using the native export functionality, use this tool to convert the xml file into Staticman-format comments for use in Jekyll.

[MinimalMistakesGitHubPagesStarterRepoUrl]: https://github.com/mmistakes/mm-github-pages-starter
[MinimalMistakesThemeGitHubRepoUrl]: https://github.com/mmistakes/minimal-mistakes
[MinimalMistakesThemeDocumentationUrl]: https://mmistakes.github.io/minimal-mistakes/docs/quick-start-guide/
[RubyInstallerDownloadPageUrl]: https://rubyinstaller.org/downloads/
[JekyllRogueSyntaxHighlighterSupportedLanguagesUrl]: https://simpleit.rocks/ruby/jekyll/what-are-the-supported-language-highlighters-in-jekyll/
[JekyllKramdownOptionsDocumentationUrl]: https://kramdown.gettalong.org/options.html
[RougeBatchSyntaxHighlightingSupportIssueUrl]: https://github.com/rouge-ruby/rouge/issues/252
[RougeAutoHotkeySyntaxHighlightingSupportIssueUrl]: https://github.com/rouge-ruby/rouge/issues/1136
[HowToUsePygmentsSyntaxHighlighterWithJekyll]: https://lyk6756.github.io/2016/11/22/use_pygments.html
[JekyllPygmentsSyntaxHighlighterSupportedLanguagesUrl]: https://haisum.github.io/2014/11/07/jekyll-pygments-supported-highlighters/
[WordPressToJekyllExporterPluginUrl]: https://wordpress.org/plugins/jekyll-exporter
[WordPressCommentsToJekyllStaticmanToolUrl]: https://github.com/arthurlacoste/wordpress-comments-jekyll-staticman
