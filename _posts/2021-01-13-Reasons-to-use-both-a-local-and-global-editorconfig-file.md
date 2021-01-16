---
title: "Reasons to use both a local and global editorconfig file"
permalink: /Reasons-to-use-both-a-local-and-global-editorconfig-file/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22T00:00:00-06:00
comments_locked: false
categories:
  - Productivity
  - Editor
  - IDE
tags:
  - Productivity
  - Editor
  - IDE
  - EditorConfig
---

## What is EditorConfig

<img alt="Editor icons" src="/assets/Posts/2021-01-13-Reasons-to-use-both-a-local-and-global-editorconfig-file/EditorIcons.png" class="right" />

[EditorConfig](https://editorconfig.org) is a project that aims to define common editor configuration outside of your editor.
The settings are instead stored in a `.editorconfig` file, which can be committed to your source control repository, or live outside of it.
These settings include things like if tabs or spaces should be preferred, if whitespace should be trimmed off every line, what file encoding to use, and more; [see the full list here](https://github.com/editorconfig/editorconfig/wiki/EditorConfig-Properties).

A few years ago EditorConfig started to become very popular and receive mass adoption.
Today, [pretty much every major editor and IDE](https://editorconfig.org/#download) either natively support EditorConfig, or has a plugin for it.
Some even extend the native EditorConfig property list, such as how [Visual Studio allows you to specify .Net coding conventions in it](https://docs.microsoft.com/en-us/dotnet/fundamentals/code-analysis/code-style-rule-options#example-editorconfig-file).

While EditorConfig hasn't ended the infamous tabs vs. spaces debate, it has at least made it easy to achieve consistency within a repository or project.
[Scott Hanselman](https://www.hanselman.com/blog/tabs-vs-spaces-a-peaceful-resolution-with-editorconfig-in-visual-studio-plus-net-extensions) and [others](https://devblog.dymel.pl/2018/01/29/tabs-vs-spaces-editorconfig/) have blogged about this.
Not having to worry about which repositories use tabs and which use spaces, what file encoding to use, which end-of-line character to use, etc. and just have it automatically use the right settings for the current project is wonderful.

## The problem

<img alt="Code" src="/assets/Posts/2021-01-13-Reasons-to-use-both-a-local-and-global-editorconfig-file/Code.jpg" class="left" />

That said, there's still one issue I come across, which is that teams tend to put presentation-only properties in the .editorconfig file that gets committed to source control.
The biggest offender is the `indent_size` property used with an `indent_style` of `tabs`, as well as the `tab_width` property.
This property does not affect the physical contents of the file, and is solely a personal preference presentation setting.
Some people might like their tabs represented as 4 spaces, while others like it as 2 to save on horizontal space, while others might prefer 8 for accessibility reasons.

As mentioned earlier, some editors have extended the list of EditorConfig properties.
For example, Visual Studio allows you to specify if Visual Studio should suggest transforming a simple one-line method into an expression bodied method.
Some users may find that helpful, while others may find it annoying and want to change it from a suggestion to being silent (so Visual Studio doesn't underline it with a blue squiggle).
If I add that property to my .editorconfig file and set it to silent, other team members may miss out on a feature they love.

## My solution

So how do we solve this problem?
The answer is to use 2 .editorconfig files.
A local .editorconfig file that contains team settings and gets committed to source control in your repository, and a global .editorconfig file for personal settings that lives in a directory above all of your repositories, outside of source control (you can still keep it in source control _somewhere_, just not in every repository).

The global .editorconfig file:

- Lives in a directory above all of your repositories.
- Can contain any properties you like; both presentation-only properties and properties that modify file contents.

The local .editorconfig file:

- Gets committed to source control in your repository.
- __Should not contain any presentation-only properties__, such as tab width; only include properties that affect actual file contents, and that you want enforced in the repository.
- Should have `root = false` defined so that presentation-only (and other) properties can be inherited from the global .editorconfig file.

Here is an example of my personal global .editorconfig file [(gist)](https://gist.github.com/deadlydog/f83de31269f6f9982d26cfbd70bbf50f):

```ini
# This .editorconfig file should live outside of all repositories (and thus not be committed to source control) in
# a parent directory, as it includes personal preference presentation settings, like a tab's `indent_size`.
# v1.2

root = true

[*]
indent_style = tab
end_of_line = crlf
trim_trailing_whitespace = true
insert_final_newline = true
indent_size = 4

[*.{html,xml,config,json}]
indent_size = 2

[*.{md,psd1,pp,yml,yaml}]
indent_style = space
indent_size = 2
```

And of my default local .editorconfig file [(gist)](https://gist.github.com/deadlydog/bd000162e85c155b243a712c16f7411c) that I drop in my git repositories:

```ini
# This file should only include settings that affect the physical contents of the file, not just how it appears in an editor.
# Do not include personal preference presentation settings like a tab's `indent_size` in this file; those should be specified
# in a parent .editorconfig file outside of the repository.
# v1.4

# Ensure that personal preference presentation settings can be inherited from parent .editorconfig files.
root = false

[*]
indent_style = tab
end_of_line = crlf
trim_trailing_whitespace = true
insert_final_newline = true

[*.{md,psd1,pp,yml,yaml}]
indent_style = space
indent_size = 2
```

Those gist links essentially act as the source control for my personal .editorconfig files.

Notice that the local .editorconfig file has `root = false` defined, and does not include an `indent_size` when `indent_style = tab`, while the global .editorconfig file does.

You may also notice that aside from these 2 properties, the files are very similar.
That is because these are _my files_ and they reflect _my preferences_.
I might clone an open source git repo, or one that a different team in my office maintains, and their local .editorconfig file may look very different from my global one, but I can be sure that their local .editorconfig file settings will be used instead of my global ones.

### Why this works

EditorConfig works using an inheritance model.
That is:

> When opening a file, EditorConfig plugins look for a file named .editorconfig in the directory of the opened file and in every parent directory.
> A search for .editorconfig files will stop if the root filepath is reached or an EditorConfig file with root=true is found.
>
> EditorConfig files are read top to bottom and the most recent rules found take precedence.
> Properties from matching EditorConfig sections are applied in the order they were read, so properties in closer files take precedence.

<img alt="Globe" src="/assets/Posts/2021-01-13-Reasons-to-use-both-a-local-and-global-editorconfig-file/Globe.jpg" class="right" />

This means that properties found in an .editorconfig file closer to the file will override ones found further away from the file.

So if you wanted, you could place your global .editorconfig file at `C:\.editorconfig` and it would apply to any file you open in your editor, whether they are part of a git repository or not.
Any properties defined in your repository's local .editorconfig file will override the global ones.

I keep all of my git repositories under `C:\dev\Git`, so my global .editorconfig file lives in that directory.

![Editorconfig files in File Explorer](/assets/Posts/2021-01-13-Reasons-to-use-both-a-local-and-global-editorconfig-file/EditorconfigFilesInFileExplorer.png)

## The benefits

Benefits of using a global .editorconfig file include:

- Everyone's personal preference presentation-only properties can be respected, so long as those properties aren't overridden in the local .editorconfig file (if they are, you can likely remove them).
- When working in a repository that does not have an .editorconfig file, I still get all of _my_ personal properties applied.
- Depending on which language I'm working in, I use different editors / IDEs.
  Because I always have _at least_ my global .editorconfig file being applied, I no longer have to worry about configuring each editor the same way for all of the different file types; the .editorconfig file handles that for me.

![Hooray](/assets/Posts/2021-01-13-Reasons-to-use-both-a-local-and-global-editorconfig-file/Hooray.gif)

## Conclusion

I've been using this strategy of both a global and local .editorconfig file for a couple years now and have found it works well for me.
I haven't read about it or seen it elsewhere though, so I thought I'd share.

What are your thoughts on this approach?
Do you think you'll try it?
If you do, let me know how you find it.
Leave me a comment below.

Happy editing :)
