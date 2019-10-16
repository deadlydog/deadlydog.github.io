---
title: "Examples of setting up Serilog in .Net Core 3 Console apps and ASP .Net Core 3"
permalink: /Examples-of-setting-up-Serilog-in-Console-apps-and-ASP-Net-Core-3/
#date: 2099-01-17T00:00:00-06:00
#last_modified_at: 2099-01-22T00:00:00-06:00
comments_locked: false
categories:
  - C#
  - Serilog
  - .Net Core
tags:
  - C#
  - Serilog
  - .Net Core
  - Logging
  - Examples
---

It had been a while since I created a new console app from scratch, and all of my previous ones had been .Net Framework console apps, so when I needed to make one recently I decided I might as well go with a .Net Core 3 console app.
I've made minor changes in other .Net Core apps at work, and figured this was a good opportunity to get my hands dirty with it.

One of the very first things I do with any app I create is get logging setup.
Since I'd be using 2 new technologies, I decided to create a small Hello World type of console app, just to play around with getting things setup.

Rather than rehashing here what I've already documented, I'll simply direct you to my [Sample.Serilog GitHub repository](https://github.com/deadlydog/Sample.Serilog).
The ReadMe explains what NuGet packages you'll need to include to use Serilog and how to setup the configuration, as well as includes links to many other resources for more information.

The git repository contains a .sln with 3 projects that each show how to log to the console and a file:

- A plain-jane .Net Core 3 console app that logs directly to Serilog without any abstractions.
- An ASP.Net Core 3 project that shows how to inject Serilog as the logger.
- A .Net Core 3 console app that uses `Microsoft.Extensions.Hosting` and `Microsoft.Extensions.DependencyInjection` to setup a DI container and inject Serilog as the logger into your classes.

While creating my sample projects, I found a lot of documentation on getting Serilog setup for ASP.Net Core, but not very many for setting it up in a console app.
Also, most of the examples I found setup the sinks in code rather than a configuration file, which I consider bad practice.
Hopefully my GitHub repo provides a little more clarity in those areas with the documentation and examples.

## Why I chose Serilog for logging

In the past with my .Net Framework apps I had defaulted to [log4net](https://logging.apache.org/log4net/release/manual/introduction.html) for work projects (as it's our standard at work) and [NLog](https://nlog-project.org/) for personal projects (as I prefer its xml configuration syntax).
For a while now though I've been wanting to try out [Serilog](https://serilog.net/).
It's main differentiator is the it is able to serialize objects when writing them in a log, so it will automatically log an instances public properties.

For example, if you have a simple class, like:

```csharp
public class Person
{
    public string FirstName { get; set; }
    public string LastName { get; set; }
}
```

In a traditional logging solution like log4Net or NLog, if you wanted to have the `FirstName` and `LastName` properties written to the log sink (e.g. console, file, database, etc.) you would have to do something like:

```csharp
var person = new Person() { FirstName = "Dan"; LastName = "Schroeder" }
_logger.Info($"The persons First Name is '{person.FirstName}' and Last Name is '{person.LastName}'.)
```

Or override the `ToString()` method of the class to output the `FirstName` and `LastName`.

Using Serilog, we can instead do:

```csharp
var person = new Person() { FirstName = "Dan"; LastName = "Schroeder" }
_logger.Information("The persons name is {@Person}.", person);
```

The output will look something like:

```text
The persons name is {"FirstName": "Dan", "LastName": "Schroeder"}.
```

And of course the timestamp, log level, and other details can be added to show up automatically in the log message.

I thought that automatic serialization of objects was pretty clever and could be useful, so I thought I'd try it out.

### Notable Serilog aspects

The important things to note for Serilog are:

1. If you want objects to be serialized before being logged, you must prefix the template variable with an `@` or `$`. In the example above it's `{@Person}`.
1. For performance reasons, you don't want to use string interpolation or concatenation in your log strings (called templates).
Instead you need to use the more traditional `string.Format(string format, params object[] args)` format.

## Other logging framework samples

I have similar .Net Framework sample solution showing `log4net`, `NLog`, and the native `System.Diagnostics.Trace` logging frameworks in action in a private git repo.
If you think you would find seeing those sample applications useful, let me know in the comments and I'll consider moving them into their own public GitHub repo, similar to my Serilog repo.

## Source code

If you haven't already, go checkout the [Sample.Serilog GitHub repository](https://github.com/deadlydog/Sample.Serilog) for full examples of setting up Serilog in console and ASP.Net Core applications.
Fork it and play around with other sinks, enrichers, and configuration changes.
If you would like anything else documented, clarified, or shown, [open an issue](https://github.com/deadlydog/Sample.Serilog/issues).

Happy coding!
