---
id: 333
title: 'PowerShell ISE: Multi-line Comment and Uncomment Done Right, and other ISE GUI must haves'
date: 2013-06-19T17:42:07-06:00
guid: http://dans-blog.azurewebsites.net/?p=333
permalink: /powershell-ise-multiline-comment-and-uncomment-done-right-and-other-ise-gui-must-haves/
categories:
  - PowerShell
  - Productivity
tags:
  - Addon
  - Comment
  - ISE
  - keyboard shortcuts
  - multi line
  - multiline
  - PowerShell
  - PowerShell ISE
  - Profile
  - PS
  - PS ISE
  - Uncomment
  - Windows PowerShell ISE
---
I’ve written some code that you can add to your ISE profile that adds keyboard shortcuts to quickly comment and uncomment lines in PowerShell ISE. So you can quickly turn this:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:5d350a70-cbe6-463e-a3ff-77edd19c556e" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; title: ; notranslate" title="">
This is some
	code and here is
some more code.
</pre>
</div>

into this:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:1daa462a-e68d-4520-9f23-31ddf6dc6b7c" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; title: ; notranslate" title="">
#This is some
#	code and here is
#some more code.
</pre>
</div>

and back again.

Feel free to skip the Preamble and get right to the good stuff.



## Preamble

I’ve only been writing PowerShell (PS) for about 6 months now, and have a love-hate relationship with it. It is simply a wonderful tool…once you understand how it works and have learnt some of the nuances. I’ve gotten hung up for hours on end with things that should be simple, but aren’t. For example, if you have an array of strings, but the array actually only contains a single string, when you go to iterate over the array instead of giving you the string it will iterator over the characters in the string….but if you have multiple strings in your array then everything works fine (btw the trick is you have to explicitly cast your array to a string array when iterating over it). This is only one small example, but I’ve found I’ve hit many little Gotcha’s like this since I started with PS. So PS is a great tool, but has a deceptively steep learning curve in my opinion; it’s easy to get started with it, especially if you have a .Net background, but there are many small roadblocks that just shouldn’t be there. Luckily, we have [Stack Overflow](http://stackoverflow.com/) <img class="wlEmoticon wlEmoticon-smile" style="border-top-style: none; border-left-style: none; border-bottom-style: none; border-right-style: none" alt="Smile" src="/assets/Posts/2013/06/wlEmoticon-smile.png" />

Anyways, as a PS newb one of the first things I did was go look for a nice editor to work in; <u>intellisense was a must</u>. First I tried PowerShell ISE v3 since it comes with Windows out of the box, but was quickly turned off at how featureless the GUI is. Here’s a quick list of lacking UI components that immediately turned me off of ISE’s Script Pane:

  1. No keyboard shortcut to quickly comment/uncomment code ([go up-vote to get this added](https://connect.microsoft.com/PowerShell/feedback/details/711231/ise-v3-need-to-be-able-to-comment-a-series-of-lines-in-a-block-of-code)).
  2. No “Save All Files” keyboard shortcut ([go up-vote to get this added](https://connect.microsoft.com/PowerShell/feedback/details/790577/windows-powershell-ise-implement-a-save-all-files-feature-and-tie-it-to-ctrl-shift-s)).
  3. No ability to automatically reopen files that were open when I closed ISE; there’s the Recent Documents menu, but that’s an extra 10 clicks every time I open ISE ([go up-vote to get this added](https://connect.microsoft.com/PowerShell/feedback/details/790578/windows-powershell-ise-add-ability-to-save-load-session-state)).
  4. Can not split the tab windows to show two files side by side ([go up-vote to get this added](https://connect.microsoft.com/PowerShell/feedback/details/790581/windows-powershell-ise-add-ability-to-show-multiple-editors-side-by-side)).
  5. Can not drag a tab out of ISE to show it on another monitor ([go up-vote to get this added](https://connect.microsoft.com/PowerShell/feedback/details/698161/powershell-ise-pane-breakout-for-multi-monitor-use)).
  6. Can not enter tabs on the end of lines; I do this all of the time to line up my comments placed on the end of the code line. I’m guessing this is “by design” though to allow the tab-completion to work (I show a workaround for this [in this post](http://dans-blog.azurewebsites.net/add-ability-to-add-tabs-to-the-end-of-a-line-in-windows-powershell-ise/)).
  7. Find/Replace window does not have an option to wrap around the end of the file; it will only search down or up depending on if the Search Up checkbox is checked ([go up-vote to get this added](https://connect.microsoft.com/PowerShell/feedback/details/790583/windows-powershell-ise-add-wrap-around-option-to-find-replace-dialogues)).
  8. Can’t simply use Ctrl+F3 to search for the current/selected word/text; you have to use the actual Find window ([go up-vote to get this added](https://connect.microsoft.com/PowerShell/feedback/details/790584/windows-powershell-ise-add-keyboard-shortcuts-for-finding-text-in-a-file)).
  9. When you perform an undo/redo, the caret and view don’t jump to the text being undone/redone, so if the text being changed is outside of the viewable area you can’t see what is being changed ([up-vote to get this fixed](https://connect.microsoft.com/PowerShell/feedback/details/790586/windows-powershell-ise-caret-and-view-do-not-jump-to-text-being-undone-redone)).
 10.  Can not re-arrange tabs; you have to close and reopen them if you want to change their order ([go up-vote to get this added](https://connect.microsoft.com/PowerShell/feedback/details/790585/windows-powershell-ise-add-ability-to-rearrange-tabs)).
 11.  The intellisense sometimes becomes intermittent or stops all together and you have to restart ISE ([go up-vote to get this fixed](https://connect.microsoft.com/PowerShell/feedback/details/772736/powershell-ise-v3-rtm-intellisense-does-not-show-up-consistently)).
 12.  Double-clicking a cmdlet or variable name does not select the entire cmdlet/variable name; e.g. doesn’t fully select “Get-Help” or “$variable” ([go up-vote to get this added](https://connect.microsoft.com/PowerShell/feedback/details/790661/windows-powershell-ise-double-click-should-select-entire-cmdlet-variable-name)).

It took me all of 5 minutes to say “ISE is not a mature enough editor for me”; I guess I’ve been spoiled by working in Visual Studio for so many years. So I went and found [PowerGUI](http://powergui.org/index.jspa), which was pretty good and I liked it quite a bit at first. It’s been a while since I’ve used it so honestly I can’t remember all of the reasons why I decided to switch away from it. I remember one problem of having to constantly start a new PS session in order to pick up changes to functions that I made (I think they had a button for that at least), as well as intellisense not being reliable, and having problems with debugging. Anyways, I decided to switch to [PowerShellPlus](http://www.idera.com/productssolutions/freetools/powershellplus) and was much happier with it. It still wasn’t perfect; I still had problems with intellisense and debugging, but I was still happy. I especially liked that I could search for and download other people’s script easily from it, which is great for learning. As I kept using it though, it kept taking longer and longer to load. After about 3 months I found myself waiting about a minute for it to open, and then once it was open, another 15 seconds or so to open all of my previously open tabs; and I have an SSD. So I thought I would give ISE another shot, mainly because it is already installed by default and I now know that I can customize it somewhat with the add-ons.



## Other Must Have ISE GUI Add-ons

After looking for not too long, I found posts on [the PowerShell Team’s blog](http://blogs.msdn.com/b/powershell/) which address the [Save All](http://blogs.msdn.com/b/powershell/archive/2010/06/05/save-all-powershell-ise-files-for-thor-s-sake.aspx) and [Save/Restore ISE State](http://blogs.msdn.com/b/powershell/archive/2010/06/05/export-and-import-powershell-ise-opened-files.aspx) issues (#2 and #3 in my list above). These are must haves, and I provide them alongside my code in the last section below.



## Why My Implementation Is Better

**<u>Other solutions and why they suck:</u>**

So of course before writing my own multiline comment/uncomment code I went searching for an existing solution, and I did find two. The first one was recommended by [Ed Wilson (aka Hey, Scripting Guy!)](http://blogs.technet.com/b/heyscriptingguy/) at the bottom of [this post](http://blogs.technet.com/b/heyscriptingguy/archive/2010/11/19/automatically-add-comments-in-powershell-work-with-text-files-and-customize-office-communicator.aspx). He recommended using the [PowerShellPack](http://archive.msdn.microsoft.com/PowerShellPack). I downloaded it, added it to my PS profile, and gave it a try. I was instantly disappointed. The [other solution I found](http://gallery.technet.microsoft.com/PowerShell-ISE-Add-On-to-38ec9dab) was by Clatonh (a Microsoft employee). Again, I added his code to my ISE profile to try it out, and was disappointed.

Here are the problems with their solutions:

  1. If you only have part of a line selected, it places the comment character at the beginning of your selection, not at the beginning of the line (undesirable, both).
  2. If you don’t have any text selected, nothing gets commented out (undesirable, both).
  3. If you have any blank lines selected in your multiline selection, it removes them (unacceptable, PowerShellPack only).
  4. It uses block comments (i.e. <# … #>)! (unacceptable (block comments are the devil), Clatonh’s solution only) I’m not sure if the PowerShellPack problems are because it was written for PS v2 and I’m using v3 on Windows 8, but either way that was unacceptable for me.

You might be wondering why #4 is on my list and why I hate block comments so much. Block comments themselves aren’t _entirely_ a bad idea; the problem is that 99% of editors (including PS ISE) don’t handle nested block comments properly. For example, if I comment out 3 lines in a function using block comments, and then later go and comment out the entire function using block comments, I’ll get a compiler error (or in PS’s case, a run-time error); this is because the first closing “#>” tag will be considered the closing tag for both the 1st and 2nd opening “<#” tags; so everything between the 1st and 2nd closing “#>” tag won’t actually be commented out. Because of this it is just easier to avoid block comments all together, even for that paragraph of comment text you are about to write (you do comment your code, right?).

**<u>My Solution:</u>**

  1. Uses single line comments (no block comments!).
  2. Places the comment character at the beginning of the line, even if you have middle of line selected.
  3. Comments out the line that the caret is on if no text is selected.
  4. Preserves blank lines, and doesn’t comment them out.



## Show Me The Code

Before I give you the code, we are going to want to add it to your PowerShell **ISE** profile, so we need to open that file.

<u>To edit your PowerShell ISE profile:</u>

  1. Open **Windows PowerShell ISE** (not Windows PowerShell, as we want to edit the ISE profile instead of the regular PowerShell profile).
  2. In the Command window type: **psedit $profile


** If you get an error that it cannot find the path, then first type the following to create the file before trying #2 again: **New-Item $profile –ItemType File –Force**

And now that you have your PowerShell ISE profile file open for editing, here’s the code to append to it in order to get the comment/uncomment commands and keyboard shortcuts (or keep reading and get ALL the code from further down). You will then need to restart PowerShell ISE for the new commands to show up and work. I’ll mention too that I’ve only tested this on Windows 8 with PowerShell v3.0.

<div id="scid:fb3a1972-4489-4e52-abe7-25a00bb07fdf:58862c22-befe-4b77-bb11-5c61d2cc7aa1" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <p>
    <a href="/assets/Posts/2013/09/CommentSelectedLines.zip" target="_blank">Download The Code</a>
  </p>
</div>

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:82ea387e-6fc6-4b8f-8b45-0fa739aea0be" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title="">
# Define our constant variables.
[string]$NEW_LINE_STRING = "`r`n"
[string]$COMMENT_STRING = "#"

function Select-EntireLinesInIseSelectedTextAndReturnFirstAndLastSelectedLineNumbers([bool]$DoNothingWhenNotCertainOfWhichLinesToSelect = $false)
{
&lt;#
    .SYNOPSIS
    Exands the selected text to make sure the entire lines are selected.
    Returns $null if we can't determine with certainty which lines to select and the

    .DESCRIPTION
    Exands the selected text to make sure the entire lines are selected.

    .PARAMETER DoNothingWhenNotCertainOfWhichLinesToSelect
    Under the following edge case we can't determine for sure which lines in the file are selected.
    If this switch is not provided and the edge case is encountered, we will guess and attempt to select the entire selected lines, but we may guess wrong and select the lines above/below the selected lines.
    If this switch is provided and the edge case is encountered, no lines will be selected.

    Edge Case:
    - When the selected text occurs multiple times in the document, directly above or below the selected text.

    Example:
    abc
    abc
    abc

    - If only the first two lines are selected, when you run this command it may comment out the 1st and 2nd lines correctly, or it may comment out the 2nd and 3rd lines, depending on
    if the caret is on the 1st line or 2nd line when selecting the text (i.e. the text is selected bottom-to-top vs. top-to-bottom).
    - Since the lines are typically identical for this edge case to occur, you likely won't really care which 2 of the 3 lines get selected, so it shouldn't be a big deal.
    But if it bugs you, you can provide this switch.

    .OUTPUT
    PSObject. Returns a PSObject with the properties FirstLineNumber and LastLineNumber, which correspond to the first and last line numbers of the selected text.
#&gt;

    # Backup all of the original info before we modify it.
    [int]$originalCaretLine = $psISE.CurrentFile.Editor.CaretLine
    [string]$originalSelectedText = $psISE.CurrentFile.Editor.SelectedText
    [string]$originalCaretLineText = $psISE.CurrentFile.Editor.CaretLineText

    # Assume only one line is selected.
    [int]$textToSelectFirstLine = $originalCaretLine
    [int]$textToSelectLastLine = $originalCaretLine

    #------------------------
    # Before we process the selected text, we need to make sure all selected lines are fully selected (i.e. the entire line is selected).
    #------------------------

    # If no text is selected, OR only part of one line is selected (and it doesn't include the start of the line), select the entire line that the caret is currently on.
    if (($psISE.CurrentFile.Editor.SelectedText.Length -le 0) -or !$psISE.CurrentFile.Editor.SelectedText.Contains($NEW_LINE_STRING))
    {
        $psISE.CurrentFile.Editor.SelectCaretLine()
    }
    # Else the first part of one line (or the entire line), or multiple lines are selected.
    else
    {
        # Get the number of lines in the originally selected text.
        [string[]] $originalSelectedTextArray = $originalSelectedText.Split([string[]]$NEW_LINE_STRING, [StringSplitOptions]::None)
        [int]$numberOfLinesInSelectedText = $originalSelectedTextArray.Length

        # If only one line is selected, make sure it is fully selected.
        if ($numberOfLinesInSelectedText -le 1)
        {
            $psISE.CurrentFile.Editor.SelectCaretLine()
        }
        # Else there are multiple lines selected, so make sure the first character of the top line is selected (so that we put the comment character at the start of the top line, not in the middle).
        # The first character of the bottom line will always be selected when multiple lines are selected, so we don't have to worry about making sure it is selected; only the top line.
        else
        {
            # Determine if the caret is on the first or last line of the selected text.
            [bool]$isCaretOnFirstLineOfSelectedText = $false
            [string]$firstLineOfOriginalSelectedText = $originalSelectedTextArray[0]
            [string]$lastLineOfOriginalSelectedText = $originalSelectedTextArray[$originalSelectedTextArray.Length - 1]

            # If the caret is definitely on the first line.
            if ($originalCaretLineText.EndsWith($firstLineOfOriginalSelectedText) -and !$originalCaretLineText.StartsWith($lastLineOfOriginalSelectedText))
            {
                $isCaretOnFirstLineOfSelectedText = $true
            }
            # Else if the caret is definitely on the last line.
            elseif ($originalCaretLineText.StartsWith($lastLineOfOriginalSelectedText) -and !$originalCaretLineText.EndsWith($firstLineOfOriginalSelectedText))
            {
                $isCaretOnFirstLineOfSelectedText = $false
            }
            # Else we need to do further analysis to determine if the caret is on the first or last line of the selected text.
            else
            {
                [int]$numberOfLinesInFile = $psISE.CurrentFile.Editor.LineCount

                [string]$caretOnFirstLineText = [string]::Empty
                [int]$caretOnFirstLineArrayStartIndex = ($originalCaretLine - 1) # -1 because array starts at 0 and file lines start at 1.
                [int]$caretOnFirstLineArrayStopIndex = $caretOnFirstLineArrayStartIndex + ($numberOfLinesInSelectedText - 1) # -1 because the starting line is inclusive (i.e. if we want 1 line the start and stop lines should be the same).

                [string]$caretOnLastLineText = [string]::Empty
                [int]$caretOnLastLineArrayStopIndex = ($originalCaretLine - 1)  # -1 because array starts at 0 and file lines start at 1.
                [int]$caretOnLastLineArrayStartIndex = $caretOnLastLineArrayStopIndex - ($numberOfLinesInSelectedText - 1) # -1 because the stopping line is inclusive (i.e. if we want 1 line the start and stop lines should be the same).

                # If the caret being on the first line would cause us to go "off the file", then we know the caret is on the last line.
                if (($caretOnFirstLineArrayStartIndex -lt 0) -or ($caretOnFirstLineArrayStopIndex -ge $numberOfLinesInFile))
                {
                    $isCaretOnFirstLineOfSelectedText = $false
                }
                # If the caret being on the last line would cause us to go "off the file", then we know the caret is on the first line.
                elseif (($caretOnLastLineArrayStartIndex -lt 0) -or ($caretOnLastLineArrayStopIndex -ge $numberOfLinesInFile))
                {
                    $isCaretOnFirstLineOfSelectedText = $true
                }
                # Else we still don't know where the caret is.
                else
                {
                    [string[]]$filesTextArray = $psISE.CurrentFile.Editor.Text.Split([string[]]$NEW_LINE_STRING, [StringSplitOptions]::None)

                    # Get the text of the lines where the caret is on the first line of the selected text.
                    [string[]]$caretOnFirstLineTextArray = @([string]::Empty) * $numberOfLinesInSelectedText # Declare an array with the number of elements required.
                    [System.Array]::Copy($filesTextArray, $caretOnFirstLineArrayStartIndex, $caretOnFirstLineTextArray, 0, $numberOfLinesInSelectedText)
                    $caretOnFirstLineText = $caretOnFirstLineTextArray -join $NEW_LINE_STRING

                    # Get the text of the lines where the caret is on the last line of the selected text.
                    [string[]]$caretOnLastLineTextArray = @([string]::Empty) * $numberOfLinesInSelectedText # Declare an array with the number of elements required.
                    [System.Array]::Copy($filesTextArray, $caretOnLastLineArrayStartIndex, $caretOnLastLineTextArray, 0, $numberOfLinesInSelectedText)
                    $caretOnLastLineText = $caretOnLastLineTextArray -join $NEW_LINE_STRING

                    [bool]$caretOnFirstLineTextContainsOriginalSelectedText = $caretOnFirstLineText.Contains($originalSelectedText)
                    [bool]$caretOnLastLineTextContainsOriginalSelectedText = $caretOnLastLineText.Contains($originalSelectedText)

                    # If the selected text is only within the text of when the caret is on the first line, then we know for sure the caret is on the first line.
                    if ($caretOnFirstLineTextContainsOriginalSelectedText -and !$caretOnLastLineTextContainsOriginalSelectedText)
                    {
                        $isCaretOnFirstLineOfSelectedText = $true
                    }
                    # Else if the selected text is only within the text of when the caret is on the last line, then we know for sure the caret is on the last line.
                    elseif ($caretOnLastLineTextContainsOriginalSelectedText -and !$caretOnFirstLineTextContainsOriginalSelectedText)
                    {
                        $isCaretOnFirstLineOfSelectedText = $false
                    }
                    # Else if the selected text is in both sets of text, then we don't know for sure if the caret is on the first or last line.
                    elseif ($caretOnFirstLineTextContainsOriginalSelectedText -and $caretOnLastLineTextContainsOriginalSelectedText)
                    {
                        # If we shouldn't do anything since we might comment out text that is not selected by the user, just exit this function and return null.
                        if ($DoNothingWhenNotCertainOfWhichLinesToSelect)
                        {
                            return $null
                        }
                    }
                    # Else something went wrong and there is a flaw in this logic, since the selected text should be in one of our two strings, so let's just guess!
                    else
                    {
                        Write-Error "WHAT HAPPENED?!?! This line should never be reached. There is a flaw in our logic!"
                        return $null
                    }
                }
            }

            # Assume the caret is on the first line of the selected text, so we want to select text from the caret's line downward.
            $textToSelectFirstLine = $originalCaretLine
            $textToSelectLastLine = $originalCaretLine + ($numberOfLinesInSelectedText - 1) # -1 because the starting line is inclusive (i.e. if we want 1 line the start and stop lines should be the same).

            # If the caret is actually on the last line of the selected text, we want to select text from the caret's line upward.
            if (!$isCaretOnFirstLineOfSelectedText)
            {
                $textToSelectFirstLine = $originalCaretLine - ($numberOfLinesInSelectedText - 1) # -1 because the stopping line is inclusive (i.e. if we want 1 line the start and stop lines should be the same).
                $textToSelectLastLine = $originalCaretLine
            }

            # Re-select the text, making sure the entire first and last lines are selected. +1 on EndLineWidth because column starts at 1, not 0.
            $psISE.CurrentFile.Editor.Select($textToSelectFirstLine, 1, $textToSelectLastLine, $psISE.CurrentFile.Editor.GetLineLength($textToSelectLastLine) + 1)
        }
    }

    # Return the first and last line numbers selected.
    $selectedTextFirstAndLastLineNumbers = New-Object PSObject -Property @{
        FirstLineNumber = $textToSelectFirstLine
        LastLineNumber = $textToSelectLastLine
    }
    return $selectedTextFirstAndLastLineNumbers
}

function CommentOrUncommentIseSelectedLines([bool]$CommentLines = $false, [bool]$DoNothingWhenNotCertainOfWhichLinesToSelect = $false)
{
    $selectedTextFirstAndLastLineNumbers = Select-EntireLinesInIseSelectedTextAndReturnFirstAndLastSelectedLineNumbers $DoNothingWhenNotCertainOfWhichLinesToSelect

    # If we couldn't determine which lines to select, just exit without changing anything.
    if ($selectedTextFirstAndLastLineNumbers -eq $null) { return }

    # Get the text lines selected.
    [int]$selectedTextFirstLineNumber = $selectedTextFirstAndLastLineNumbers.FirstLineNumber
    [int]$selectedTextLastLineNumber = $selectedTextFirstAndLastLineNumbers.LastLineNumber

    # Get the Selected Text and convert it into an array of strings so we can easily process each line.
    [string]$selectedText = $psISE.CurrentFile.Editor.SelectedText
    [string[]] $selectedTextArray = $selectedText.Split([string[]]$NEW_LINE_STRING, [StringSplitOptions]::None)

    # Process each line of the Selected Text, and save the modified lines into a text array.
    [string[]]$newSelectedTextArray = @()
    $selectedTextArray | foreach {
        # If the line is not blank, add a comment character to the start of it.
        [string]$lineText = $_
        if ([string]::IsNullOrWhiteSpace($lineText)) { $newSelectedTextArray += $lineText }
        else
        {
            # If we should be commenting the lines out, add a comment character to the start of the line.
            if ($CommentLines)
            { $newSelectedTextArray += "$COMMENT_STRING$lineText" }
            # Else we should be uncommenting, so remove a comment character from the start of the line if it exists.
            else
            {
                # If the line begins with a comment, remove one (and only one) comment character.
                if ($lineText.StartsWith($COMMENT_STRING))
                {
                    $lineText = $lineText.Substring($COMMENT_STRING.Length)
                }
                $newSelectedTextArray += $lineText
            }
        }
    }

    # Join the text array back together to get the new Selected Text string.
    [string]$newSelectedText = $newSelectedTextArray -join $NEW_LINE_STRING

    # Overwrite the currently Selected Text with the new Selected Text.
    $psISE.CurrentFile.Editor.InsertText($newSelectedText)

    # Fully select all of the lines that were modified. +1 on End Line's Width because column starts at 1, not 0.
    $psISE.CurrentFile.Editor.Select($selectedTextFirstLineNumber, 1, $selectedTextLastLineNumber, $psISE.CurrentFile.Editor.GetLineLength($selectedTextLastLineNumber) + 1)
}

function Comment-IseSelectedLines([switch]$DoNothingWhenNotCertainOfWhichLinesToComment)
{
&lt;#
    .SYNOPSIS
    Places a comment character at the start of each line of the selected text in the current PS ISE file.
    If no text is selected, it will comment out the line that the caret is on.

    .DESCRIPTION
    Places a comment character at the start of each line of the selected text in the current PS ISE file.
    If no text is selected, it will comment out the line that the caret is on.

    .PARAMETER DoNothingWhenNotCertainOfWhichLinesToComment
    Under the following edge case we can't determine for sure which lines in the file are selected.
    If this switch is not provided and the edge case is encountered, we will guess and attempt to comment out the selected lines, but we may guess wrong and comment out the lines above/below the selected lines.
    If this switch is provided and the edge case is encountered, no lines will be commented out.

    Edge Case:
    - When the selected text occurs multiple times in the document, directly above or below the selected text.

    Example:
    abc
    abc
    abc

    - If only the first two lines are selected, when you run this command it may comment out the 1st and 2nd lines correctly, or it may comment out the 2nd and 3rd lines, depending on
    if the caret is on the 1st line or 2nd line when selecting the text (i.e. the text is selected bottom-to-top vs. top-to-bottom).
    - Since the lines are typically identical for this edge case to occur, you likely won't really care which 2 of the 3 lines get commented out, so it shouldn't be a big deal.
    But if it bugs you, you can provide this switch.
#&gt;
    CommentOrUncommentIseSelectedLines -CommentLines $true -DoNothingWhenNotCertainOfWhichLinesToSelect $DoNothingWhenNotCertainOfWhichLinesToComment
}

function Uncomment-IseSelectedLines([switch]$DoNothingWhenNotCertainOfWhichLinesToUncomment)
{
&lt;#
    .SYNOPSIS
    Removes the comment character from the start of each line of the selected text in the current PS ISE file (if it is commented out).
    If no text is selected, it will uncomment the line that the caret is on.

    .DESCRIPTION
    Removes the comment character from the start of each line of the selected text in the current PS ISE file (if it is commented out).
    If no text is selected, it will uncomment the line that the caret is on.

    .PARAMETER DoNothingWhenNotCertainOfWhichLinesToUncomment
    Under the following edge case we can't determine for sure which lines in the file are selected.
    If this switch is not provided and the edge case is encountered, we will guess and attempt to uncomment the selected lines, but we may guess wrong and uncomment out the lines above/below the selected lines.
    If this switch is provided and the edge case is encountered, no lines will be uncommentet.

    Edge Case:
    - When the selected text occurs multiple times in the document, directly above or below the selected text.

    Example:
    abc
    abc
    abc

    - If only the first two lines are selected, when you run this command it may uncomment the 1st and 2nd lines correctly, or it may uncomment the 2nd and 3rd lines, depending on
    if the caret is on the 1st line or 2nd line when selecting the text (i.e. the text is selected bottom-to-top vs. top-to-bottom).
    - Since the lines are typically identical for this edge case to occur, you likely won't really care which 2 of the 3 lines get uncommented, so it shouldn't be a big deal.
    But if it bugs you, you can provide this switch.
#&gt;
    CommentOrUncommentIseSelectedLines -CommentLines $false -DoNothingWhenNotCertainOfWhichLinesToSelect $DoNothingWhenNotCertainOfWhichLinesToUncomment
}


#==========================================================
# Add ISE Add-ons.
#==========================================================

# Add a new option in the Add-ons menu to comment all selected lines.
if (!($psISE.CurrentPowerShellTab.AddOnsMenu.Submenus | Where-Object { $_.DisplayName -eq "Comment Selected Lines" }))
{
    $psISE.CurrentPowerShellTab.AddOnsMenu.Submenus.Add("Comment Selected Lines",{Comment-IseSelectedLines},"Ctrl+K")
}

# Add a new option in the Add-ons menu to uncomment all selected lines.
if (!($psISE.CurrentPowerShellTab.AddOnsMenu.Submenus | Where-Object { $_.DisplayName -eq "Uncomment Selected Lines" }))
{
    $psISE.CurrentPowerShellTab.AddOnsMenu.Submenus.Add("Uncomment Selected Lines",{Uncomment-IseSelectedLines},"Ctrl+Shift+K")
}
</pre>
</div>

As you can see by the code at the bottom, the keyboard shortcut to comment lines is **Ctrl+K** and to uncomment it is **Ctrl+Shift+K**. Feel free to change these if you like. I wanted to use the Visual Studio keyboard shortcut keys of Ctrl+K,Ctrl+C and Ctrl+K,Ctrl+U, but it looks like multi-sequence keyboard shortcuts aren’t supported. I figured that anybody who uses Visual Studio or SQL Server Management Studio would be able to stumble across this keyboard shortcut and would like it.



**<u>Ok, it’s not perfect:</u>**

If you’re still reading then you deserve to know about the edge case bug with my implementation. If you actually read through the functions’ documentation in the code you will see this mentioned there as well.

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:d91d2e85-b69d-4773-bd6d-48fb9f6ad4b4" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: plain; title: ; notranslate" title="">
Edge Case:
    - When the selected text occurs multiple times in the document, directly above or below the selected text.

    Example:
    abc
    abc
    abc

    - If only the first two lines are selected, when you run this command it may comment out the 1st and 2nd lines correctly, or it may comment out the 2nd and 3rd lines, depending on
    if the caret is on the 1st line or 2nd line when selecting the text (i.e. the text is selected bottom-to-top vs. top-to-bottom).
    - Since the lines are typically identical for this edge case to occur, you likely won't really care which 2 of the 3 lines get uncommented, so it shouldn't be a big deal.
</pre>
</div>

Basically the problem is that I change the selected text to ensure that the entire lines are selected (so that I can put the comment character at the start of the line). The PS ISE API doesn’t tell me the selected text’s starting and ending lines, so I have to try and infer it from the line the caret is on, but the caret can be on either the first or the last line of the selected text. So if text that is identical to the selected text appears directly above or below the selected text, I can’t know for sure if the caret is on the first line of the selected text, or the last line, so I just make a guess. If this bothers you there is a switch you can provide so that it won’t comment out any lines at all if this edge case is hit.



## Show Me ALL The Code

Ok, so I mentioned a couple other must-have ISE add-ons above. Here’s the code to add to your ISE profile that includes my comment/uncomment code, as well as the Save All files and Save/Restore ISE State functionality provided by [the PowerShell Team](http://blogs.msdn.com/b/powershell/). This includes a couple customizations that I made; namely adding a Save ISE State And Exit command (Alt+Shift+E) and having the ISE State automatically load when PS ISE starts (I didn’t change the functions they provided that do the actual work at all). So if you want your last session to be automatically reloaded, you just have to get in the habit of closing ISE with Alt+Shift+E (again, you can change this keyboard shortcut if you want).

<div id="scid:fb3a1972-4489-4e52-abe7-25a00bb07fdf:671096ee-632f-4329-af4d-3a4c79a23e5c" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <p>
    <a href="/assets/Posts/2013/09/CommentSelectedLinesPlusOthers.zip" target="_blank">Download The Code</a>
  </p>
</div>

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:86dcfab3-093a-4c30-91e5-7242127eda3e" class="wlWriterEditableSmartContent" style="float: none; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; margin: 0px; display: inline; padding-right: 0px">
  <pre style=white-space:normal>

  <pre class="brush: powershell; pad-line-numbers: true; title: ; notranslate" title="">
#==========================================================
# Functions used by the script.
#==========================================================

function Save-AllISEFiles
{
&lt;#
.SYNOPSIS
    Saves all ISE Files except for untitled files. If You have multiple PowerShellTabs, saves files in all tabs.
#&gt;
    foreach($tab in $psISE.PowerShellTabs)
    {
        foreach($file in $tab.Files)
        {
            if(!$file.IsUntitled)
            {
                $file.Save()
            }
        }
    }
}

function Export-ISEState
{
&lt;#
.SYNOPSIS
    Stores the opened files in a serialized xml so that later the same set can be opened

.DESCRIPTION
    Creates an xml file with all PowerShell tabs and file information

.PARAMETER fileName
    The name of the project to create a new version from. This will also be the name of the new project, but with a different version

.EXAMPLE
    Stores current state into c:\temp\files.isexml
    Export-ISEState c:\temp\files.isexml
#&gt;

    Param
    (
        [Parameter(Position=0, Mandatory=$true)]
        [ValidateNotNullOrEmpty()]
        [string]$fileName
    )

    # We are exporting a "tree" worth of information like this:
    #
    #  SelectedTabDisplayName: PowerShellTab 1
    #  SelectedFilePath: c:\temp\a.ps1
    #  TabInformation:
    #      PowerShellTab 1:
    #           File 1:
    #                FullPath:     c:\temp\a.ps1
    #                FileContents: $null
    #           File 2:
    #                FullPath:     Untitled.ps1
    #                FileContents: $a=0...
    #       PowerShellTab 2:
    #       ...
    #  Hashtables and arraylists serialize rather well with export-clixml
    #  We will keep the list of PowerShellTabs in one ArrayList and the list of files
    #  and contents(for untitled files) inside each tab in a couple of ArrayList.
    #  We will use Hashtables to group the information.
    $tabs=new-object collections.arraylist

    # before getting file information, save all untitled files to make sure their latest
    # text is on disk
    Save-AllISEFiles

    foreach ($tab in $psISE.PowerShellTabs)
    {
        $files=new-object collections.arraylist
        $filesContents=new-object collections.arraylist
        foreach($file in $tab.Files)
        {
            # $null = will avoid $files.Add from showing in the output
            $null = $files.Add($file.FullPath)

            if($file.IsUntitled)
            {
                # untitled files are not yet on disk so we will save the file contents inside the xml
                # export-clixml performs the appropriate escaping for the contents to be inside the xml
                $null = $filesContents.Add($file.Editor.Text)
            }
            else
            {
                # titled files get their content from disk
                $null = $filesContents.Add($null)
            }
        }
        $simpleTab=new-object collections.hashtable

        # The DisplayName of a PowerShellTab can only be change with scripting
        # we want to maintain the chosen name
        $simpleTab["DisplayName"]=$tab.DisplayName

        # $files and $filesContents is the information gathered in the foreach $file above
        $simpleTab["Files"]=$files
        $simpleTab["FilesContents"]=$filesContents

        # add to the list of tabs
        $null = $tabs.Add($simpleTab)

    }

    # tabsToSerialize will be a hashtable with all the information we want
    # it is the "root" of the information to be serialized in the hashtable we store...
    $tabToSerialize=new-object collections.hashtable

    # the $tabs information gathered in the foreach $tab above...
    $tabToSerialize["TabInformation"] = $tabs

    # ...and the selected tab and file.
    $tabToSerialize["SelectedTabDisplayName"] = $psISE.CurrentPowerShellTab.DisplayName
    $tabToSerialize["SelectedFilePath"] = $psISE.CurrentFile.FullPath

    # now we just export it to $fileName
    $tabToSerialize | export-clixml -path $fileName
}


function Import-ISEState
{
&lt;#
.SYNOPSIS
    Reads a file with ISE state information about which files to open and opens them

.DESCRIPTION
    Reads a file created by Export-ISEState with the PowerShell tabs and files to open

.PARAMETER fileName
    The name of the file created with Export-ISEState

.EXAMPLE
    Restores current state from c:\temp\files.isexml
    Import-ISEState c:\temp\files.isexml
#&gt;

    Param
    (
        [Parameter(Position=0, Mandatory=$true)]
        [ValidateNotNullOrEmpty()]
        [string]$fileName
    )


    # currentTabs is used to keep track of the tabs currently opened.
    # If "PowerShellTab 1" is opened and $fileName contains files for it, we
    # want to open them in "PowerShellTab 1"
    $currentTabs=new-object collections.hashtable
    foreach ($tab in $psISE.PowerShellTabs)
    {
        $currentTabs[$tab.DisplayName]=$tab
    }

    $tabs=import-cliXml -path $fileName

    # those will keep track of selected tab and files
    $selectedTab=$null
    $selectedFile=$null

    foreach ($tab in $tabs.TabInformation)
    {
        $newTab=$currentTabs[$tab.DisplayName]
        if($newTab -eq $null)
        {
            $newTab=$psISE.PowerShellTabs.Add()
            $newTab.DisplayName=$tab.DisplayName
        }
        #newTab now has a brand new or a previouslly existing PowerShell tab with the same name as the one in the file

        # if the tab is the selected tab save it for later selection
        if($newTab.DisplayName -eq $tabs.SelectedTabDisplayName)
        {
            $selectedTab=$newTab
        }

        # currentUntitledFileContents keeps track of the contents for untitled files
        # if you already have the content in one of your untitled files
        # there is no reason to add the same content again
        # this will make sure calling import-ISEState multiple times
        # does not keep on adding untitled files
        $currentUntitledFileContents=new-object collections.hashtable
        foreach ($newTabFile in $newTab.Files)
        {
            if($newTabFile.IsUntitled)
            {
                $currentUntitledFileContents[$newTabFile.Editor.Text]=$newTabFile
            }
        }

        # since we will want both file and fileContents we need to use a for instead of a foreach
        for($i=0;$i -lt $tab.Files.Count;$i++)
        {
            $file = $tab.Files[$i]
            $fileContents = $tab.FilesContents[$i]

            #fileContents will be $null for titled files
            if($fileContents -eq $null)
            {
                # the overload of Add taking one string opens the file identified by the string
                $newFile = $newTab.Files.Add($file)
            }
            else # the file is untitled
            {
                #see if the content is already present in $newTab
                $newFile=$currentUntitledFileContents[$fileContents]

                if($newFile -eq $null)
                {
                    # the overload of Add taking no arguments creates a new untitled file
                    # The number for untitled files is determined by the application so we
                    # don't try to keep the untitled number, we just create a new untitled.
                    $newFile = $newTab.Files.Add()

                    # and here we restore the contents
                    $newFile.Editor.Text=$fileContents
                }
            }

            # if the file is the selected file in the selected tab save it for later selection
            if(($selectedTab -eq $newTab) -and ($tabs.SelectedFilePath -eq $file))
            {
                $selectedFile = $newFile
            }
        }
    }

    #finally we selected the PowerShellTab that was selected and the file that was selected on it.
    $psISE.PowerShellTabs.SetSelectedPowerShellTab($selectedTab)
    if($selectedFile -ne $null)
    {
        $selectedTab.Files.SetSelectedFile($selectedFile)
    }
}

# Define our constant variables.
[string]$NEW_LINE_STRING = "`r`n"
[string]$COMMENT_STRING = "#"

function Select-EntireLinesInIseSelectedTextAndReturnFirstAndLastSelectedLineNumbers([bool]$DoNothingWhenNotCertainOfWhichLinesToSelect = $false)
{
&lt;#
    .SYNOPSIS
    Exands the selected text to make sure the entire lines are selected.
    Returns $null if we can't determine with certainty which lines to select and the

    .DESCRIPTION
    Exands the selected text to make sure the entire lines are selected.

    .PARAMETER DoNothingWhenNotCertainOfWhichLinesToSelect
    Under the following edge case we can't determine for sure which lines in the file are selected.
    If this switch is not provided and the edge case is encountered, we will guess and attempt to select the entire selected lines, but we may guess wrong and select the lines above/below the selected lines.
    If this switch is provided and the edge case is encountered, no lines will be selected.

    Edge Case:
    - When the selected text occurs multiple times in the document, directly above or below the selected text.

    Example:
    abc
    abc
    abc

    - If only the first two lines are selected, when you run this command it may comment out the 1st and 2nd lines correctly, or it may comment out the 2nd and 3rd lines, depending on
    if the caret is on the 1st line or 2nd line when selecting the text (i.e. the text is selected bottom-to-top vs. top-to-bottom).
    - Since the lines are typically identical for this edge case to occur, you likely won't really care which 2 of the 3 lines get selected, so it shouldn't be a big deal.
    But if it bugs you, you can provide this switch.

    .OUTPUT
    PSObject. Returns a PSObject with the properties FirstLineNumber and LastLineNumber, which correspond to the first and last line numbers of the selected text.
#&gt;

    # Backup all of the original info before we modify it.
    [int]$originalCaretLine = $psISE.CurrentFile.Editor.CaretLine
    [string]$originalSelectedText = $psISE.CurrentFile.Editor.SelectedText
    [string]$originalCaretLineText = $psISE.CurrentFile.Editor.CaretLineText

    # Assume only one line is selected.
    [int]$textToSelectFirstLine = $originalCaretLine
    [int]$textToSelectLastLine = $originalCaretLine

    #------------------------
    # Before we process the selected text, we need to make sure all selected lines are fully selected (i.e. the entire line is selected).
    #------------------------

    # If no text is selected, OR only part of one line is selected (and it doesn't include the start of the line), select the entire line that the caret is currently on.
    if (($psISE.CurrentFile.Editor.SelectedText.Length -le 0) -or !$psISE.CurrentFile.Editor.SelectedText.Contains($NEW_LINE_STRING))
    {
        $psISE.CurrentFile.Editor.SelectCaretLine()
    }
    # Else the first part of one line (or the entire line), or multiple lines are selected.
    else
    {
        # Get the number of lines in the originally selected text.
        [string[]] $originalSelectedTextArray = $originalSelectedText.Split([string[]]$NEW_LINE_STRING, [StringSplitOptions]::None)
        [int]$numberOfLinesInSelectedText = $originalSelectedTextArray.Length

        # If only one line is selected, make sure it is fully selected.
        if ($numberOfLinesInSelectedText -le 1)
        {
            $psISE.CurrentFile.Editor.SelectCaretLine()
        }
        # Else there are multiple lines selected, so make sure the first character of the top line is selected (so that we put the comment character at the start of the top line, not in the middle).
        # The first character of the bottom line will always be selected when multiple lines are selected, so we don't have to worry about making sure it is selected; only the top line.
        else
        {
            # Determine if the caret is on the first or last line of the selected text.
            [bool]$isCaretOnFirstLineOfSelectedText = $false
            [string]$firstLineOfOriginalSelectedText = $originalSelectedTextArray[0]
            [string]$lastLineOfOriginalSelectedText = $originalSelectedTextArray[$originalSelectedTextArray.Length - 1]

            # If the caret is definitely on the first line.
            if ($originalCaretLineText.EndsWith($firstLineOfOriginalSelectedText) -and !$originalCaretLineText.StartsWith($lastLineOfOriginalSelectedText))
            {
                $isCaretOnFirstLineOfSelectedText = $true
            }
            # Else if the caret is definitely on the last line.
            elseif ($originalCaretLineText.StartsWith($lastLineOfOriginalSelectedText) -and !$originalCaretLineText.EndsWith($firstLineOfOriginalSelectedText))
            {
                $isCaretOnFirstLineOfSelectedText = $false
            }
            # Else we need to do further analysis to determine if the caret is on the first or last line of the selected text.
            else
            {
                [int]$numberOfLinesInFile = $psISE.CurrentFile.Editor.LineCount

                [string]$caretOnFirstLineText = [string]::Empty
                [int]$caretOnFirstLineArrayStartIndex = ($originalCaretLine - 1) # -1 because array starts at 0 and file lines start at 1.
                [int]$caretOnFirstLineArrayStopIndex = $caretOnFirstLineArrayStartIndex + ($numberOfLinesInSelectedText - 1) # -1 because the starting line is inclusive (i.e. if we want 1 line the start and stop lines should be the same).

                [string]$caretOnLastLineText = [string]::Empty
                [int]$caretOnLastLineArrayStopIndex = ($originalCaretLine - 1)  # -1 because array starts at 0 and file lines start at 1.
                [int]$caretOnLastLineArrayStartIndex = $caretOnLastLineArrayStopIndex - ($numberOfLinesInSelectedText - 1) # -1 because the stopping line is inclusive (i.e. if we want 1 line the start and stop lines should be the same).

                # If the caret being on the first line would cause us to go "off the file", then we know the caret is on the last line.
                if (($caretOnFirstLineArrayStartIndex -lt 0) -or ($caretOnFirstLineArrayStopIndex -ge $numberOfLinesInFile))
                {
                    $isCaretOnFirstLineOfSelectedText = $false
                }
                # If the caret being on the last line would cause us to go "off the file", then we know the caret is on the first line.
                elseif (($caretOnLastLineArrayStartIndex -lt 0) -or ($caretOnLastLineArrayStopIndex -ge $numberOfLinesInFile))
                {
                    $isCaretOnFirstLineOfSelectedText = $true
                }
                # Else we still don't know where the caret is.
                else
                {
                    [string[]]$filesTextArray = $psISE.CurrentFile.Editor.Text.Split([string[]]$NEW_LINE_STRING, [StringSplitOptions]::None)

                    # Get the text of the lines where the caret is on the first line of the selected text.
                    [string[]]$caretOnFirstLineTextArray = @([string]::Empty) * $numberOfLinesInSelectedText # Declare an array with the number of elements required.
                    [System.Array]::Copy($filesTextArray, $caretOnFirstLineArrayStartIndex, $caretOnFirstLineTextArray, 0, $numberOfLinesInSelectedText)
                    $caretOnFirstLineText = $caretOnFirstLineTextArray -join $NEW_LINE_STRING

                    # Get the text of the lines where the caret is on the last line of the selected text.
                    [string[]]$caretOnLastLineTextArray = @([string]::Empty) * $numberOfLinesInSelectedText # Declare an array with the number of elements required.
                    [System.Array]::Copy($filesTextArray, $caretOnLastLineArrayStartIndex, $caretOnLastLineTextArray, 0, $numberOfLinesInSelectedText)
                    $caretOnLastLineText = $caretOnLastLineTextArray -join $NEW_LINE_STRING

                    [bool]$caretOnFirstLineTextContainsOriginalSelectedText = $caretOnFirstLineText.Contains($originalSelectedText)
                    [bool]$caretOnLastLineTextContainsOriginalSelectedText = $caretOnLastLineText.Contains($originalSelectedText)

                    # If the selected text is only within the text of when the caret is on the first line, then we know for sure the caret is on the first line.
                    if ($caretOnFirstLineTextContainsOriginalSelectedText -and !$caretOnLastLineTextContainsOriginalSelectedText)
                    {
                        $isCaretOnFirstLineOfSelectedText = $true
                    }
                    # Else if the selected text is only within the text of when the caret is on the last line, then we know for sure the caret is on the last line.
                    elseif ($caretOnLastLineTextContainsOriginalSelectedText -and !$caretOnFirstLineTextContainsOriginalSelectedText)
                    {
                        $isCaretOnFirstLineOfSelectedText = $false
                    }
                    # Else if the selected text is in both sets of text, then we don't know for sure if the caret is on the first or last line.
                    elseif ($caretOnFirstLineTextContainsOriginalSelectedText -and $caretOnLastLineTextContainsOriginalSelectedText)
                    {
                        # If we shouldn't do anything since we might comment out text that is not selected by the user, just exit this function and return null.
                        if ($DoNothingWhenNotCertainOfWhichLinesToSelect)
                        {
                            return $null
                        }
                    }
                    # Else something went wrong and there is a flaw in this logic, since the selected text should be in one of our two strings, so let's just guess!
                    else
                    {
                        Write-Error "WHAT HAPPENED?!?! This line should never be reached. There is a flaw in our logic!"
                        return $null
                    }
                }
            }

            # Assume the caret is on the first line of the selected text, so we want to select text from the caret's line downward.
            $textToSelectFirstLine = $originalCaretLine
            $textToSelectLastLine = $originalCaretLine + ($numberOfLinesInSelectedText - 1) # -1 because the starting line is inclusive (i.e. if we want 1 line the start and stop lines should be the same).

            # If the caret is actually on the last line of the selected text, we want to select text from the caret's line upward.
            if (!$isCaretOnFirstLineOfSelectedText)
            {
                $textToSelectFirstLine = $originalCaretLine - ($numberOfLinesInSelectedText - 1) # -1 because the stopping line is inclusive (i.e. if we want 1 line the start and stop lines should be the same).
                $textToSelectLastLine = $originalCaretLine
            }

            # Re-select the text, making sure the entire first and last lines are selected. +1 on EndLineWidth because column starts at 1, not 0.
            $psISE.CurrentFile.Editor.Select($textToSelectFirstLine, 1, $textToSelectLastLine, $psISE.CurrentFile.Editor.GetLineLength($textToSelectLastLine) + 1)
        }
    }

    # Return the first and last line numbers selected.
    $selectedTextFirstAndLastLineNumbers = New-Object PSObject -Property @{
        FirstLineNumber = $textToSelectFirstLine
        LastLineNumber = $textToSelectLastLine
    }
    return $selectedTextFirstAndLastLineNumbers
}

function CommentOrUncommentIseSelectedLines([bool]$CommentLines = $false, [bool]$DoNothingWhenNotCertainOfWhichLinesToSelect = $false)
{
    $selectedTextFirstAndLastLineNumbers = Select-EntireLinesInIseSelectedTextAndReturnFirstAndLastSelectedLineNumbers $DoNothingWhenNotCertainOfWhichLinesToSelect

    # If we couldn't determine which lines to select, just exit without changing anything.
    if ($selectedTextFirstAndLastLineNumbers -eq $null) { return }

    # Get the text lines selected.
    [int]$selectedTextFirstLineNumber = $selectedTextFirstAndLastLineNumbers.FirstLineNumber
    [int]$selectedTextLastLineNumber = $selectedTextFirstAndLastLineNumbers.LastLineNumber

    # Get the Selected Text and convert it into an array of strings so we can easily process each line.
    [string]$selectedText = $psISE.CurrentFile.Editor.SelectedText
    [string[]] $selectedTextArray = $selectedText.Split([string[]]$NEW_LINE_STRING, [StringSplitOptions]::None)

    # Process each line of the Selected Text, and save the modified lines into a text array.
    [string[]]$newSelectedTextArray = @()
    $selectedTextArray | foreach {
        # If the line is not blank, add a comment character to the start of it.
        [string]$lineText = $_
        if ([string]::IsNullOrWhiteSpace($lineText)) { $newSelectedTextArray += $lineText }
        else
        {
            # If we should be commenting the lines out, add a comment character to the start of the line.
            if ($CommentLines)
            { $newSelectedTextArray += "$COMMENT_STRING$lineText" }
            # Else we should be uncommenting, so remove a comment character from the start of the line if it exists.
            else
            {
                # If the line begins with a comment, remove one (and only one) comment character.
                if ($lineText.StartsWith($COMMENT_STRING))
                {
                    $lineText = $lineText.Substring($COMMENT_STRING.Length)
                }
                $newSelectedTextArray += $lineText
            }
        }
    }

    # Join the text array back together to get the new Selected Text string.
    [string]$newSelectedText = $newSelectedTextArray -join $NEW_LINE_STRING

    # Overwrite the currently Selected Text with the new Selected Text.
    $psISE.CurrentFile.Editor.InsertText($newSelectedText)

    # Fully select all of the lines that were modified. +1 on End Line's Width because column starts at 1, not 0.
    $psISE.CurrentFile.Editor.Select($selectedTextFirstLineNumber, 1, $selectedTextLastLineNumber, $psISE.CurrentFile.Editor.GetLineLength($selectedTextLastLineNumber) + 1)
}

function Comment-IseSelectedLines([switch]$DoNothingWhenNotCertainOfWhichLinesToComment)
{
&lt;#
    .SYNOPSIS
    Places a comment character at the start of each line of the selected text in the current PS ISE file.
    If no text is selected, it will comment out the line that the caret is on.

    .DESCRIPTION
    Places a comment character at the start of each line of the selected text in the current PS ISE file.
    If no text is selected, it will comment out the line that the caret is on.

    .PARAMETER DoNothingWhenNotCertainOfWhichLinesToComment
    Under the following edge case we can't determine for sure which lines in the file are selected.
    If this switch is not provided and the edge case is encountered, we will guess and attempt to comment out the selected lines, but we may guess wrong and comment out the lines above/below the selected lines.
    If this switch is provided and the edge case is encountered, no lines will be commented out.

    Edge Case:
    - When the selected text occurs multiple times in the document, directly above or below the selected text.

    Example:
    abc
    abc
    abc

    - If only the first two lines are selected, when you run this command it may comment out the 1st and 2nd lines correctly, or it may comment out the 2nd and 3rd lines, depending on
    if the caret is on the 1st line or 2nd line when selecting the text (i.e. the text is selected bottom-to-top vs. top-to-bottom).
    - Since the lines are typically identical for this edge case to occur, you likely won't really care which 2 of the 3 lines get commented out, so it shouldn't be a big deal.
    But if it bugs you, you can provide this switch.
#&gt;
    CommentOrUncommentIseSelectedLines -CommentLines $true -DoNothingWhenNotCertainOfWhichLinesToSelect $DoNothingWhenNotCertainOfWhichLinesToComment
}

function Uncomment-IseSelectedLines([switch]$DoNothingWhenNotCertainOfWhichLinesToUncomment)
{
&lt;#
    .SYNOPSIS
    Removes the comment character from the start of each line of the selected text in the current PS ISE file (if it is commented out).
    If no text is selected, it will uncomment the line that the caret is on.

    .DESCRIPTION
    Removes the comment character from the start of each line of the selected text in the current PS ISE file (if it is commented out).
    If no text is selected, it will uncomment the line that the caret is on.

    .PARAMETER DoNothingWhenNotCertainOfWhichLinesToUncomment
    Under the following edge case we can't determine for sure which lines in the file are selected.
    If this switch is not provided and the edge case is encountered, we will guess and attempt to uncomment the selected lines, but we may guess wrong and uncomment out the lines above/below the selected lines.
    If this switch is provided and the edge case is encountered, no lines will be uncommentet.

    Edge Case:
    - When the selected text occurs multiple times in the document, directly above or below the selected text.

    Example:
    abc
    abc
    abc

    - If only the first two lines are selected, when you run this command it may uncomment the 1st and 2nd lines correctly, or it may uncomment the 2nd and 3rd lines, depending on
    if the caret is on the 1st line or 2nd line when selecting the text (i.e. the text is selected bottom-to-top vs. top-to-bottom).
    - Since the lines are typically identical for this edge case to occur, you likely won't really care which 2 of the 3 lines get uncommented, so it shouldn't be a big deal.
    But if it bugs you, you can provide this switch.
#&gt;
    CommentOrUncommentIseSelectedLines -CommentLines $false -DoNothingWhenNotCertainOfWhichLinesToSelect $DoNothingWhenNotCertainOfWhichLinesToUncomment
}


#==========================================================
# Add ISE Add-ons.
#==========================================================

# Add a new option in the Add-ons menu to save all files.
if (!($psISE.CurrentPowerShellTab.AddOnsMenu.Submenus | Where-Object { $_.DisplayName -eq "Save All" }))
{
    $psISE.CurrentPowerShellTab.AddOnsMenu.Submenus.Add("Save All",{Save-AllISEFiles},"Ctrl+Shift+S")
}

$ISE_STATE_FILE_PATH = Join-Path (Split-Path $profile -Parent) "IseState.xml"

# Add a new option in the Add-ons menu to export the current ISE state.
if (!($psISE.CurrentPowerShellTab.AddOnsMenu.Submenus | Where-Object { $_.DisplayName -eq "Save ISE State" }))
{
    $psISE.CurrentPowerShellTab.AddOnsMenu.Submenus.Add("Save ISE State",{Export-ISEState $ISE_STATE_FILE_PATH},"Alt+Shift+S")
}

# Add a new option in the Add-ons menu to export the current ISE state and exit.
if (!($psISE.CurrentPowerShellTab.AddOnsMenu.Submenus | Where-Object { $_.DisplayName -eq "Save ISE State And Exit" }))
{
    $psISE.CurrentPowerShellTab.AddOnsMenu.Submenus.Add("Save ISE State And Exit",{Export-ISEState $ISE_STATE_FILE_PATH; exit},"Alt+Shift+E")
}

# Add a new option in the Add-ons menu to import the ISE state.
if (!($psISE.CurrentPowerShellTab.AddOnsMenu.Submenus | Where-Object { $_.DisplayName -eq "Load ISE State" }))
{
    $psISE.CurrentPowerShellTab.AddOnsMenu.Submenus.Add("Load ISE State",{Import-ISEState $ISE_STATE_FILE_PATH},"Alt+Shift+L")
}

# Add a new option in the Add-ons menu to comment all selected lines.
if (!($psISE.CurrentPowerShellTab.AddOnsMenu.Submenus | Where-Object { $_.DisplayName -eq "Comment Selected Lines" }))
{
    $psISE.CurrentPowerShellTab.AddOnsMenu.Submenus.Add("Comment Selected Lines",{Comment-IseSelectedLines},"Ctrl+K")
}

# Add a new option in the Add-ons menu to uncomment all selected lines.
if (!($psISE.CurrentPowerShellTab.AddOnsMenu.Submenus | Where-Object { $_.DisplayName -eq "Uncomment Selected Lines" }))
{
    $psISE.CurrentPowerShellTab.AddOnsMenu.Submenus.Add("Uncomment Selected Lines",{Uncomment-IseSelectedLines},"Ctrl+Shift+K")
}

#==========================================================
# Perform script tasks.
#==========================================================

# Automatically load our saved session if we just opened ISE and have a default blank session.
# Because this may remove the default "Untitled1.ps1" file, try and have this execute before any other code so the file is removed before the user can start typing in it.
if (($psISE.PowerShellTabs.Count -eq 1) -and ($psISE.CurrentPowerShellTab.Files.Count -eq 1) -and ($psISE.CurrentPowerShellTab.Files[0].IsUntitled))
{
    # Remove the default "Untitled1.ps1" file and then load the session.
    if (!$psISE.CurrentPowerShellTab.Files[0].IsRecovered) { $psISE.CurrentPowerShellTab.Files.RemoveAt(0) }
    Import-ISEState $ISE_STATE_FILE_PATH
}

# Clear the screen so we don't see any output when opening a new session.
Clear-Host
</pre>
</div>



Hopefully this post makes your ISE experience a little better. Feel free to comment and let me know if you like this or find any problems with it. Know of any other must-have ISE add-ons? Let me know.

Happy coding!
