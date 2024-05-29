---
title: "Why I switched to OTBS for PowerShell brace styling"
permalink: /Why-I-switched-to-OTBS-for-PowerShell-brace-styling/
#date: 2099-01-15T00:00:00-06:00
last_modified_at: 2024-05-29
comments_locked: false
toc: false
categories:
  - PowerShell
  - Visual Studio Code
tags:
  - PowerShell
  - Visual Studio Code
---

Code formatting can be a very personal choice.
Some languages are very strict and enforce specific indentation and brace styles, while many others allow for different indentation styles to be used, including PowerShell.
Many languages end up with a defacto style that most people use, often because it is the default style in a popular editor or IDE.
You should take care when choosing your style though, as sometimes the style can make scripts harder to maintain, or even lead to bugs in your code.

## Allman and One True Brace Style (OTBS)

Before diving into PowerShell over a decade ago, I was primarily a C# developer.
The typical convention for C# has been to use [the Allman indentation style](https://en.wikipedia.org/wiki/Indentation_style#Allman_style) for code.
I love the Allman style.
To me, it's clean and makes code easier to read.
Here's an example of the Allman style:

```powershell
function ReplaceDogWithCat ([string[]] $textToPrint)
{
    foreach ($text in $textToPrint)
    {
        if ($text -eq 'dog')
        {
            Write-Output 'cat'
        }
        else
        {
            Write-Output $text
        }
    }
}
```

If you've looked at open source PowerShell code, you've probably noticed that many people use [the One True Brace Style (OTBS)](https://en.wikipedia.org/wiki/Indentation_style#One_True_Brace) for brace styling.
Here's the same example formatted using the OTBS style:

```powershell
function ReplaceDogWithCat ([string[]] $textToPrint) {
    foreach ($text in $textToPrint) {
        if ($text -eq 'dog') {
            Write-Output 'cat'
        } else {
            Write-Output $text
        }
    }
}
```

Most fans of OTBS think that the Allman style wastes too much vertical space by putting the opening brace on a new line.
To me though, that whitespace makes the code easier to read; it visually segregates the blocks of code allowing my mind to more easily focus on a single block.

When I look at the OTBS example above, my mind initially just sees one big block of text, rather than 4 distinct parts (function, foreach, if, else).
Maybe it's because I've been using Allman for so long, but the compactness of OTBS makes it feel cluttered to me.

## Why use OTBS for PowerShell?

So if I love Allman style, why did I switch to OTBS for PowerShell?

There are two main reasons:

1. OTBS is more popular in the PowerShell community.
1. PowerShell has some restrictions that force the opening brace to be on the same line in some scenarios.

### Popularity

Regardless of what style you prefer, you should use it consistently.
If you're working on a team, it's best to agree on a common style and stick to it, otherwise you'll end up with a mix of styles in your files, which can be a bit jarring and lead to confusion.

That said, it's often best to try and use the most popular style.
It means that when others need to work on your scripts, or when you bring new members onto your team, they're more likely to be familiar with the style you're using.
They won't have to spend time trying to understand the style you're using, or updating their editor settings to match your style.

While I don't have any hard proof that OTBS is the most popular, it's what I've personally seen most often in open source projects.
Also, it's by far the most up-voted style on [Joel Bennett's GitHub discussion on the subject](https://github.com/PoshCode/PowerShellPracticeAndStyle/discussions/177), which is linked to from the VS Code settings.

### PowerShell restrictions

I've been using Allman style for PowerShell for over a decade.
In the beginning I stumbled a bit, as there are a few scenarios that require the opening brace to be on the same line.
The main examples are any cmdlet that uses a script block, such as `ForEach-Object`, `Where-Object`, `Invoke-Command`, etc.
Many statements work fine with the opening brace on a new line though.

Here are some code examples to illustrate the issue:

```powershell
# WORKS: The opening brace on a new line works fine for `foreach` loops.
foreach ($number in 1..10)
{
    $number
}

# WORKS: It works fine for `if` and `switch` statements too.
if ($number -gt 5)
{
    $number
}

# FAILS: This will halt execution to prompt for a script block.
1..10 | ForEach-Object
{
    $_
}

# WORKS: The above needs to be written like this to work correctly:
1..10 | ForEach-Object {
    $_
}

# FAILS: This will also halt and prompt the user for a script block.
1..10 | Where-Object
{
    $_ -gt 5
}

# WORKS: The above needs to be written like this to work correctly:
1..10 | Where-Object {
    $_ -gt 5
}

# FAILS: This will throw an error that no value was provided for `-ScriptBlock` parameter.
Invoke-Command -ScriptBlock
{
    Get-Process
}

# WORKS: The above needs to be written like this to work correctly:
Invoke-Command -ScriptBlock {
    Get-Process
}
```

Those last 3 examples require the opening brace `{` to be on the same line as the cmdlet.
After running into these issues for a few months, I was able to remember when an opening brace is required to be on the same line, and it has become second nature to me.
It often boils down to whether it is a PowerShell keyword (e.g. if, foreach, switch), a function vs. a cmdlet, and if you are using implicit parameters or not.

That's the problem though.
It took me months to get it engrained which scenarios require the opening brace to be on the same line.
Even though it's second nature to me now, it's not going to be for everyone, especially those new to PowerShell.
Why put others through months (years?) of pain to learn when it's ok to put the opening brace on a new line and when it's not?
Why potentially introduce bugs into your scripts, simply for the sake of a brace style?

It's important to note that VS Code / PowerShell / PSScriptAnalyzer does not give any warnings or indications that the code will fail.
It is perfectly valid syntax.
This is what makes these so dangerous.
The code will not fail until runtime.
This means if you have the problematic code in a non-happy path, the script might run completely fine for months before finally hitting that code path and failing.

This is the primary reason I've switched to OTBS for PowerShell.
I'm not the only person who works on my scripts, and I'm producing more open-source PowerShell these days.
I want to create a pit of success for others working with my code; it should be hard to do the wrong thing.
Using Allman in PowerShell means inconsistency in where the opening brace goes, creating a minefield for others to navigate.
It simply isn't worth the confusion and potential bugs it introduces.

If like me you find Allman more readable, you can experiment with inserting a blank line between blocks of code to help visually segregate them (e.g. where you used to have the opening `{` brace in it's own line) and see if that helps.

## Setting the default brace style in VS Code

Hopefully this post has illustrated why I've switched to OTBS for PowerShell, and has you considering doing the same.
You can set your default PowerShell brace/indentation style in Visual Studio Code by using the `powershell.codeFormatting.preset` setting.
Just search for `PowerShell format` in the VS Code Settings and you should find it.
Here you can see I have it set to OTBS:

![Visual Studio Code PowerShell code formatting preset setting set to OTBS](/assets/Posts/2024-05-27-Why-I-switched-to-OTBS-for-PowerShell-brace-styling/vs-code-powershell-code-formatting-setting.png)

If you're not ready to make it your default everywhere and just want to enable it for specific projects, or if you're not the only one working on your project, switch to the `Workspace` tab and update the setting there.
This will create a `.vscode/settings.json` file in your project with the setting in it:

```json
{
  "powershell.codeFormatting.preset": "OTBS"
}
```

This allows you to commit the setting to source control, ensuring that everyone who works on the project uses the same brace style.

## Updating your existing scripts

After updating VS Code's PowerShell code formatting setting, you can use the `Format Document` command in VS Code to update the brace style of the current file.

If you want to update all of the scripts in your project at once, check out [the Format Files extension](https://marketplace.visualstudio.com/items?itemName=jbockle.jbockle-format-files).

It's worth noting that VS Code will not automatically change the formatting of the problematic code statements mentioned in the examples above, whether you have Allman or OTBS configured, since it is valid syntax.
So do not expect running `Format Document` to fix those issues.

The main motivation to reformat your code to use OTBS is so others looking at the code will be more likely to always put the opening brace on the same line, since that is how the rest of the script is formatted.

## Conclusion

While code formatting is often just a personal choice, in PowerShell the brace style is not purely cosmetic.
It can easily lead to bugs if not used correctly.
To create a pit of success by not having to remember when the opening brace must be on the same line, switch to using OTBS for PowerShell.

Have you found this information helpful?
Do you disagree with my reasoning?
I'd love to hear your thoughts in the comments below.

Happy scripting!
