---
title: "Customize git config based on the repository path"
permalink: /Customize-git-config-based-on-the-repository-path/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22T00:00:00-06:00
comments_locked: false
categories:
  - Git
  - Productivity
tags:
  - Git
  - Productivity
---

Like many people, I use my laptop for both personal and work projects.
One thing I noticed a while back was that the commits to my work git repos were using my personal username, `deadlydog`, and email address.
That doesn't look very professional, so I got excited when I saw [this tweet from Immo Landwerth](https://twitter.com/terrajobst/status/1324481475652190208) about how he solved the problem.

His solution was to run a custom command in every git repo that would add the appropriate variables to the repo's git config.
While that works, it's recurring manual work that I wanted to avoid.
Fortunately, awesome community members replied to his tweet saying that git conditional includes could be used instead, so I investigated it, and that's what I'm going to show here.

## Using git includes

Essentially [git includes](https://git-scm.com/docs/git-config#_includes) allow you to reference another file from your `.gitconfig` file.
When you do this, it acts as if whatever text is in the included file was present in the .gitconfig file.

So if your [global .gitconfig file](https://git-scm.com/docs/git-config#FILES) contained the text:

```ini
[user]
  name = deadlydog
  email = deadlydog@hotmail.com

[include]
  path = C:/Git/WorkProjects/Work.gitconfig
```

And "Work.gitconfig" contained the text:

```ini
[user]
  name = Daniel Schroeder
  email = DanielSchroeder@Work.com
```

Then git would interpret the final configuration to be:

```ini
[user]
  name = deadlydog
  email = deadlydog@hotmail.com

[user]
  name = Daniel Schroeder
  email = DanielSchroeder@Work.com
```

In git if you define the same settings twice, whichever one was defined last would win and be used, so in this case git would mark the commits as being created by "Daniel Schroeder", not "deadlydog".

You can typically find your global .gitconfig file in your user directory.
e.g. "C:\Users\Dan.Schroeder\\.gitconfig"

## Using git conditional includes

The last piece of the puzzle is to only include that external configuration file if it's actually a work project, not a personal project.

Git allows [conditional includes](https://git-scm.com/docs/git-config#_conditional_includes) on both directory paths and branch names.
This means I can accomplish our goal by simply putting all of my work projects in a different directory than my personal projects.
I typically put all of my personal git repositories in C:\Git, and my work git repositories in C:\Git\WorkProjects.

Here is what the final global `.gitconfig` code looks like:

```ini
[user]
  name = deadlydog
  email = deadlydog@hotmail.com

# ... all of the other .gitconfig settings.

[includeIf "gitdir:C:/Git/WorkProjects/**"]
  path = C:/Git/WorkProjects/Work.gitconfig
```

There's a few things to note here:

1. You will want to add your includes to the bottom of your .gitconfig file.
This will ensure the included file settings always take precedence over what is directly in the .gitconfig file, since the last setting value defined in the .gitconfig file is what gets used.
1. If you read [the conditional include docs](https://git-scm.com/docs/git-config#_conditional_includes), it mentions that if a path ends with a trailing slash you don't need to include the `**` wildcards.
I prefer to include them to make it obvious that a wildcard match is happening, but using `"gitdir:C:/Git/WorkProjects/"` works the same.
1. You can call the include file whatever you like.
Here I called it "Work.gitconfig", but ".gitconfig", or "GitSettings.inc" would work fine too.
1. I'm running Windows, but in my .gitconfig file I use forward slashes for the directory paths.
Backslashes work, but need to be escaped, so you could use double backslashes if you prefer. e.g. C:\\\\Git\\\\WorkProjects\\\\.

### Use relative file paths

In the example above, I've placed the "Work.gitconfig" include file in the "WorkProjects" directory so it sits beside the git repositories that it will apply to, and then referenced it by an absolute path in the .gitconfig file.
If you prefer, you could place the "Work.gitconfig" file in the same directory as your global .gitconfig file, and then reference it using a relative path, like this:

```ini
[includeIf "gitdir:C:/Git/WorkProjects/**"]
  path = Work.gitconfig
```

## Conclusion

By simply keeping your personal and work git repositories in different directories, you can easily apply different git settings.
Here I've shown overriding the user's name and email, but you could use it for other things like preferring rebase instead of merge, which diff or merge tool to use, etc.
While I didn't show it, you can also include different files based on what branch the git repo is on; perhaps you want some different rules applied when on the main branch vs. a feature branch.

The advantages of using conditional includes in your global .gitconifg file instead of updating every repo's config are:

1. No manual work is necessary to apply the config change to the repos.
1. The changes are automatically applied to new repos created in the directory.
1. If I change settings in the include file, they take effect immediately in all repos.

Lastly, after writing this up I came across [this similar blog post by Eric Williams](https://www.motowilliams.com/conditional-includes-for-git-config), so if you still have questions check that out, as well as [the official git docs](https://git-scm.com/docs/git-config#_includes).

Happy coding :)
