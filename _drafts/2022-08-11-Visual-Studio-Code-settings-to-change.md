---
title: "Visual Studio Code settings to change"
permalink: /Visual-Studio-Code-settings-to-change/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
categories:
  - Visual Studio Code
  - Productivity
tags:
  - Visual Studio Code
  - VS Code
  - Productivity
---

VS Code is an awesome editor.
Make it even more awesome by changing these default settings values.

<!-- Related: You may also be interested in this post about [Visual Studio Code extensions to install](/Visual-Studio-Code-extensions-to-install/). -->

## Show hidden characters

- Description: Make hidden characters easily visible in the editor.
- Setting: `Editor › Unicode Highlight: Invisible Characters`
- Set value to: `true`

JSON setting:

  ```json
  "[markdown]": {
      "editor.unicodeHighlight.invisibleCharacters": true
  },
  "[plaintext]": {
      "editor.unicodeHighlight.invisibleCharacters": true
  }
  ```

Result:

![Result of enabling the Show Invisible Characters setting](/assets/Posts/2022-08-15-Visual-Studio-Code-settings-to-change/show-invisible-characters-settings-result.png)

Note: There are separate settings for the `Markdown` and `PlainText` languages, which are `false` by default, so be sure to enable all of them.

![There are multiple language settings for showing invisible characters](/assets/posts/2022-08-15-Visual-Studio-Code-settings-to-change/show-invisible-characters-multiple-settings.png)

## Sticky scroll

- Description: As you scroll down through nested classes/functions/structures, the names stick to the top of the editor making it easy to see the nested scope you're currently working in.
  Works in many different languages, such as JavaScript, TypeScript, C#, JSON, YAML, etc.
  YAML shown in screenshot below.
- Setting: `Editor › Hover: Sticky`
- Set value to: `true`

JSON setting:

```json
"editor.experimental.stickyScroll.enabled": true,
```

Result:

![Result of using sticky scroll in YAML](/assets/Posts/2022-08-15-Visual-Studio-Code-settings-to-change/sticky-scroll-setting-result.png)
