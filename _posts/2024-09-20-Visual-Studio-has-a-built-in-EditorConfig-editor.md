---
title: "Visual Studio has a built-in EditorConfig editor"
permalink: /Visual-Studio-has-a-built-in-EditorConfig-editor/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - Visual Studio
  - Productivity
  - Editor
  - IDE
tags:
  - Visual Studio
  - Productivity
  - Editor
  - IDE
  - EditorConfig
---

Somehow I missed it, but for years Visual Studio has had a built-in EditorConfig editor.
I would typically edit my .editorconfig file in VS Code using just text, but Visual Studio's editor provides a nice UI for it so you don't have to remember all the setting names.

![Visual Studio EditorConfig editor for whitespace settings](/assets/Posts/2024-09-20-Visual-Studio-has-a-built-in-EditorConfig-editor/Visual-Studio-EditorConfig-editor-whitespace.png)

![Visual Studio EditorConfig editor for code style settings](/assets/Posts/2024-09-20-Visual-Studio-has-a-built-in-EditorConfig-editor/Visual-Studio-EditorConfig-editor-code-style.png)

You can see that in addition to the typical whitespace settings, it also lists many language-specific settings as well, allowing everyone on your team to have consistent settings for the repository.
There are too many settings to fit them all in the screenshots, and new ones are periodically added with new versions of C#, .NET, and other languages.

The GUI is great for quickly seeing all of the available settings and their possible values.
You can still view the .editorconfig file's plain text by using the `View.ViewCode` shortcut (<kbd>F7</kbd>).

One thing I don't particularly like is that as soon as you open the file in the GUI editor, it automatically adds every setting to the text file.
This balloons my typical ~10 line .editorconfig file to around 100 lines.
I would prefer it if it only added settings that were changed from their default value (like how VS Code does with it's settings.json file), but since it's a file that typically isn't viewed or changed often, it's not too big of a deal.

The EditorConfig GUI is available in Visual Studio 2022 (and possibly earlier versions, but I'm not sure).
You can read more about the EditorConfig support in Visual Studio in [the official documentation](https://learn.microsoft.com/en-us/visualstudio/ide/create-portable-custom-editor-options).

I hope you found this useful.
Happy coding!
