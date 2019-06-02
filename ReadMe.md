# Daniel Schroeder's Programming Blog

This repository was initially cloned off of [Minimal Mistakes GitHub Pages Starter][MinimalMistakesGitHubPagesStarterRepoUrl], and then updated with my content and configuration.

We currently use the [Minimal Mistakes theme][MinimalMistakesThemeGitHubRepoUrl]. View [the documentation][MinimalMistakesThemeDocumentationUrl].

## Running Jekyll locally

### Prerequisites

1. Install [Ruby][RubyInstallerDownloadPageUrl], as well as have it install MSYS2 after installation.
   - Use `ruby -v` to see which version is installed.
1. Install the Jekyll gem using `gem install jekyll bundler`
   - Use `jekyll -v` to see which version of jekyll is installed.

### Run Jekyll locally

To run Jekyll locally, from the site directory run `bundle exec jekyll serve`.
Include the `--incremental` (or `-I`) parameter to do faster incremental builds.

If you've written a draft post in the `_drafts` directory and want it to show up on the site, use `jekyll serve --draft` to start Jekyll.

Jekyll typically runs locally at http://127.0.0.1:4000/.

### Keeping up to date

To ensure that the GitHub Pages and other gems are up-to-date, periodically run `bundle update` to update all gems.

To use a newer version of the theme, update the `_config.yml` file and change the line:

```yml
remote_theme: mmistakes/minimal-mistakes@[tag|commit|branch]
```

to use a newer tag/commit from [the theme repo][MinimalMistakesThemeGitHubRepoUrl].

Additionally, since we are overriding some files (see the Customizations section below), you may also need to update those files with newer versions if they have been changed in theme updates.

### Installing new gems and themes

Simply modify the `Gemfile` with the new gem to use, and then run `bundle install` to have it install the new gems.

Typically themes are installed from gems, and then you update the `_config.yml` file to specify the new theme to use.

Currently with the MinimalMistakes theme we are not installing it from a gem, but are instead using the remote theme method instead.

## Customizations I've made to the theme / site

Anywhere that I make custom code changes, I try to put `Dan's Customizations` in comments, so doing a search of the repo for `Dan` should find all places I've changed / overwritten code.
This doesn't include the `_config.yml` though, as we're expected to set custom settings in there.

Here's a list of places I've changed code:

- `assets/css/main.scss`: Adjusted font sizes a bit, and added class used in the masthead.html.
- `_includes/masthead.html`: Added site description below site title.
- `_includes/read-time.html`: Added post/page date in with the reading time.
- `_config.yml` file.

Here's a list of files I've added or changed:

- Everything in the `_posts` and `_drafts` directories.
- The `_pages\About.md` page.
- Everything in the `_assets\Posts` and `_assets\Site` directories.
- The `favicon.ico` image.
- The `.vscode` directory.
- The `ReadMe.md` file.

## Additional Info

### Supported code language highlighters

By default Jekyll uses `Rouge` for syntax highlighting of code blocks.
See [this page][JekyllRogueSyntaxHighlighterSupportedLanguagesUrl] for the list of supported languages.
e.g. `csharp`, `css`, `html`, `http`, `ini`, `json`, `liquid`, `markdown`, `powershell`, `sql`, `xml`, `yaml`, and many more.

We may want to look at [replacing `Rouge` with `Pygments`][HowToUsePygmentsSyntaxHighlighterWithJekyll] to get [support for more languages][JekyllPygmentsSyntaxHighlighterSupportedLanguagesUrl], like AutoHotkey and batch files. Unfortunately GitHub Pages only supports Rouge at this time (June 2019) so we have to stick with it, but that may change in the future.

### Supported Kramdown options

By default Jekyll uses `Kramdown` for markdown processing.
See [this page][JekyllKramdownOptionsDocumentationUrl] for the list of supported options that may be used in `_config.yml`.

### Site history

The site was migrated from WordPress to Jekyll in April 2019, and before WordPress it was hosted in GeeksWithBlogs.
This is why the posts before 2019 have additional front matter on them; the tool to export them from WordPress to Jekyll added it.

[MinimalMistakesGitHubPagesStarterRepoUrl]: https://github.com/mmistakes/mm-github-pages-starter
[MinimalMistakesThemeGitHubRepoUrl]: https://github.com/mmistakes/minimal-mistakes
[MinimalMistakesThemeDocumentationUrl]: https://mmistakes.github.io/minimal-mistakes/docs/quick-start-guide/
[RubyInstallerDownloadPageUrl]: https://rubyinstaller.org/downloads/
[JekyllRogueSyntaxHighlighterSupportedLanguagesUrl]: https://simpleit.rocks/ruby/jekyll/what-are-the-supported-language-highlighters-in-jekyll/
[JekyllKramdownOptionsDocumentationUrl]: https://kramdown.gettalong.org/options.html
[HowToUsePygmentsSyntaxHighlighterWithJekyll]: https://lyk6756.github.io/2016/11/22/use_pygments.html
[JekyllPygmentsSyntaxHighlighterSupportedLanguagesUrl]: https://haisum.github.io/2014/11/07/jekyll-pygments-supported-highlighters/
