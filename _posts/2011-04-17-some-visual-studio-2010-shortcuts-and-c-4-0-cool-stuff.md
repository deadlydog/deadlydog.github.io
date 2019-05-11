---
id: 12
title: 'Some Visual Studio 2010 Shortcuts and C# 4.0 Cool Stuff'
date: 2011-04-17T22:40:00-06:00
author: deadlydog
guid: https://deadlydog.wordpress.com/?p=12
permalink: /some-visual-studio-2010-shortcuts-and-c-4-0-cool-stuff/
jabber_published:
  - "1353105779"
categories:
  - 'C#'
  - Shortcuts
  - Visual Studio
tags:
  - 'C#'
  - features
  - keyboard shortcuts
  - shortcuts
  - visual studio
---
A list of some shortcus and new features to VS 2010 and C# 4.0:

<ul style="margin-bottom:0;unicode-bidi:embed;direction:ltr;margin-left:.375in;margin-top:0;" type="disc">
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Default values for parameters</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Can access parameters by name (i.e. SomeFunction(name: "Dan", age: 26);</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Can now put Labels on breakpoints and filter the breakpoints, as well as import and export breakpoints.</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Window => New Window to open up same file in two separate tabs, or can drag the splitter at the top-right corner of the edit window.</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Edit => Outlining => Hide Selection to collapse any region of code</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Alt + Mouse Left Drag for box selection instead of line selection, then just start typing; you can also use Alt+Shift+Arrow Keys to do box selection with the keyboard. <br /></span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Alt+Arrow Keys to move current line up/down.&#160; Can also select multiple lines and use Alt+Up/Down to move the whole selection up/down.</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">In NavigateTo search window (Ctrl + Comma) use capitals to search for camel casing (i.e. CE to find displayCustomerEmails) and a space to do an "and" search (i.e. "email customer" would find displayCustomerEmails).</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Ctrl + I to do an incremental search of a document, then F3 and Shift + F3 to move to next/previous matches.</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Use snippets to automatically create code and save time.</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Ctrl + Period to access VS tickler window instead of having to hover over the variable with the mouse.</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Ctrl + Alt + Spacebar to change VS to suggest variable names instead of auto completing them.</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Can right click in document to remove and sort using statements.</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Enum.TryParse() has been added to match a string or number to an enumerated type.</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Contract.Requires() and .Ensures() to ensure that function conditions are met (at compile time).</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">String.IsNullOrWhitespace(string);</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Lazy<T> for thread-safe lazy loading of variables.</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">VS => Options => Debugging => Output Window => Data Binding to give more info about errors.</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Using System.Threading.Tasks for parallel processing.<span>&#160; </span>Parallel.For() and .ForEach</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">PLINQ => myCollection.InParallel().Where(x => …..);</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">New Dynamic keyword type => just like Object except not checked at compile time.</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Ctrl+Shift+V to cycle through clipboard ring</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span style="font-size:11pt;font-family:calibri;">Alt+Ctrl+Down to access tab menu</span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    Ctrl+Shift+Up/Down to move between instances of the highlighted variable
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    Ctrl+] to move back and forth between a functions opening and closing braces (i.e. "{" and "}"). This appears to also work in XAML!
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span><font face="Calibri"><span style="font-size:10.5pt;"><span style="font-size:11pt;font-family:calibri;">Alt+Arrow Keys to move current line up/down.&#160; Can also select multiple lines and use Alt+Up/Down to move the whole selection up/down.</span></span></font></span>
  </li>
  <li style="margin-bottom:0;vertical-align:middle;margin-top:0;">
    <span><font face="Calibri"><span style="font-size:10.5pt;">Rather than selecting a whole line first, just use Ctrl+C or Ctrl+X to Copy/Cut the entire line. You can also use Shift+Delete to delete an entire line without selecting it.</span></font></span>
  </li>
</ul>
