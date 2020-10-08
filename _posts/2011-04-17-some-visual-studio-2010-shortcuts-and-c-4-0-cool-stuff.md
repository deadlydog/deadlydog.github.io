---
title: Some Visual Studio 2010 Shortcuts and C# 4.0 Cool Stuff
date: 2011-04-17T22:40:00-06:00
permalink: /some-visual-studio-2010-shortcuts-and-c-4-0-cool-stuff/
categories:
  - C#
  - Shortcuts
  - Visual Studio
tags:
  - C#
  - features
  - keyboard shortcuts
  - shortcuts
  - Visual Studio
---

A list of some shortcuts and new features to VS 2010 and C# 4.0:

- Default values for parameters
- Can access parameters by name (i.e. `SomeFunction(name: "Dan", age: 26);`)
- Can now put Labels on breakpoints and filter the breakpoints, as well as import and export breakpoints.
- Window => New Window to open up same file in two separate tabs, or can drag the splitter at the top-right corner of the edit indow.
- Edit => Outlining => Hide Selection to collapse any region of code
- <kbd>Alt</kbd> + Mouse Left Drag for box selection instead of line selection, then just start typing; you can also use Alt+Shift+Arrow eys to do box selection with the keyboard.
- <kbd>Alt</kbd> + Arrow Keys to move current line up/down. Can also select multiple lines and use <kbd>Alt</kbd> + <kbd>Up</kbd>/<kbd>Down</kbd> to move the whole selection p/down.
- In NavigateTo search window (<kbd>Ctrl</kbd> + <kbd>Comma</kbd>) use capitals to search for camel casing (i.e. CE to find displayCustomerEmails) nd a space to do an "and" search (i.e. "email customer" would find displayCustomerEmails).
- <kbd>Ctrl</kbd> + <kbd>I</kbd> to do an incremental search of a document, then <kbd>F3</kbd> and <kbd>Shift</kbd> + <kbd>F3</kbd> to move to next/previous matches.
- Use snippets to automatically create code and save time.
- <kbd>Ctrl</kbd> + <kbd>.</kbd> to access VS tickler window instead of having to hover over the variable with the mouse.
- <kbd>Ctrl</kbd> + <kbd>Alt</kbd> + <kbd>Spacebar</kbd> to change VS to suggest variable names instead of auto completing them.
- Can right click in document to remove and sort using statements.
- `Enum.TryParse()` has been added to match a string or number to an enumerated type.
- `Contract.Requires()` and `.Ensures()` to ensure that function conditions are met (at compile time).
- `String.IsNullOrWhitespace(string);`
- `Lazy<T>` for thread-safe lazy loading of variables.
- VS => Options => Debugging => Output Window => Data Binding to give more info about errors.
- Using `System.Threading.Tasks` for parallel processing. `Parallel.For()` and `.ForEach()`.
- PLINQ => `myCollection.InParallel().Where(x => ...);`
- New `Dynamic` keyword type => just like Object except not checked at compile time.
- <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>V</kbd> to cycle through clipboard ring
- <kbd>Alt</kbd> + <kbd>Ctrl</kbd> + <kbd>Down</kbd> to access tab menu
- <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>Up</kbd>/<kbd>Down</kbd> to move between instances of the highlighted variable
- <kbd>Ctrl</kbd> + <kbd>]</kbd> to move back and forth between a functions opening and closing braces (i.e. "{" and "}"). This appears to also work in XAML!
- <kbd>Alt</kbd> + <kbd>Arrow Keys</kbd> to move current line up/down. Can also select multiple lines and use <kbd>Alt</kbd> + <kbd>Up</kbd>/<kbd>Down</kbd> to move the whole selection up/down.
- Rather than selecting a whole line first, just use <kbd>Ctrl</kbd> + <kbd>C</kbd> or <kbd>Ctrl</kbd> + <kbd>X</kbd> to Copy/Cut the entire line. You can also use <kbd>Shift</kbd> + <kbd>Delete</kbd> to delete an entire line without selecting it.
