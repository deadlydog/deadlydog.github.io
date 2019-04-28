# Daniel Schroeder's Programming Blog

This repository was initially cloned off of [Minimal Mistakes GitHub Pages Starter](https://github.com/mmistakes/mm-github-pages-starter), and then updated with my content and configuration.

## Running Jekyll locally

### Prerequisites

1. Install [Ruby](https://rubyinstaller.org/downloads/), as well as have it install MSYS2 after installation.
   - Use `ruby -v` to see which version is installed.
1. Install the Jekyll gem using `gem install jekyll bundler`
   - Use `jekyll -v` to see which version of jekyll is installed.

### Run Jekyll locally

To run Jekyll locally, from the site directory run `bundle exec jekyll serve`.

If you've written a draft post in the `_drafts` directory and want it to show up on the site, use `jekyll serve --draft` to start Jekyll.

Jekyll typically runs locally at http://127.0.0.1:4000/.

### Keeping up to date

To ensure that the GitHub Pages and other gems are up-to-date, periodically run `bundle update` to update all gems.

### Installing new gems and themes

Simply modify the `Gemfile` with the new gem to use, and then run `bundle install` to have it install the new gems.

Typically themes are installed from gems, and then you update the `_config.yml` file to specify the new theme to use.