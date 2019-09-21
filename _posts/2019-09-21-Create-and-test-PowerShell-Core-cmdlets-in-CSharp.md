---
title: "Create and test PowerShell Core cmdlets in C#"
permalink: /Create-and-test-PowerShell-Core-cmdlets-in-CSharp/
#date: 2099-01-17T00:00:00-06:00
#last_modified_at: 2099-01-22T00:00:00-06:00
comments_locked: false
categories:
  - C#
  - PowerShell
  - Testing
  - xUnit
tags:
  - C#
  - PowerShell
  - Testing
  - xUnit
---

Creating PowerShell Core cmdlets in C# is actually quite easy, especially when you have a great blog post [like this one](https://www.red-gate.com/simple-talk/dotnet/net-development/using-c-to-create-powershell-cmdlets-the-basics/) to reference.
There is also some official [MS documentation](https://docs.microsoft.com/en-us/powershell/developer/cmdlet/how-to-write-a-simple-cmdlet) [as well](https://docs.microsoft.com/en-us/powershell/developer/module/how-to-write-a-powershell-binary-module).
What those posts don't cover is unit testing your C# cmdlets.

## Creating a PowerShell Core cmdlet

I'm not going to entirely rehash what's in that awesome blog post, but I'll share the highlights in case it goes offline in the future:

1. Create a new C# `Class Library (.Net Standard)` project in Visual Studio.
1. Add the [PowerShellStandard.Library NuGet package](https://www.nuget.org/packages/PowerShellStandard.Library/) to the project.
1. Create a class for your cmdlet and have it inherit from the `System.Management.Automation.Cmdlet` class.
1. Add a `Cmdlet` attribute to your class that describes the cmdlet verb and name.
1. Add an `OutputType` attribute to your class that describes the type of object it returns (optional, but recommended).
1. Put `Parameter` attributes on any input parameters your cmdlet uses.
1. Override the `ProcessRecord` function to process each item in the pipeline, and optionally also the `BeginProcessing` (to do initialization), `EndProcessing` (to do finalization), and `StopProcessing` (to handle abnormal termination) functions.

Here is an example of a minimal PowerShell Core cmdlet function:

```csharp
using System.Management.Automation;
using System.Text;

namespace PowerShellCmdletInCSharpExample
{
    [Cmdlet(VerbsCommon.Get, "RepeatedPhrase")]
    [OutputType(typeof(string))]
    public class GetRepeatedPhraseCmdlet : Cmdlet
    {
        [Parameter(Position = 0, Mandatory = true, ValueFromPipeline = true, ValueFromPipelineByPropertyName = true)]
        [Alias("Word")]
        [ValidateNotNullOrEmpty()]
        public string Phrase { get; set; }

        [Parameter(Position = 1, Mandatory = true, ValueFromPipelineByPropertyName = true)]
        [Alias("Repeat")]
        public int NumberOfTimesToRepeatPhrase { get; set; }

        protected override void ProcessRecord()
        {
            base.ProcessRecord();

            var result = new StringBuilder();
            for (int i = 0; i < NumberOfTimesToRepeatPhrase; i++)
            {
                result.Append(Phrase);
            }

            WriteObject(result.ToString()); // This is what actually "returns" output.
        }
    }
}
```

## Testing your PowerShell Core cmdlet in C# with xUnit

The information I had trouble tracking down was how to unit test my PowerShell Core C# cmdlets.
Well, actually, that's not exactly true.
As I pointed out in [my Stack Overflow question](https://stackoverflow.com/questions/56696574/how-to-unit-test-a-powershell-core-binary-cmdlet-in-c-sharp), I found several blog posts describing how to unit test C# PowerShell Core cmdlets. The problem was, none of the methods they described worked :(.

Most of the posts I found said to use the `PowerShellLibrary.Standard` NuGet package in your test project for your testing, which made sense since that's the NuGet package we use in the real project.
However, I discovered that invoking the PowerShell Core cmdlet with that NuGet package always resulted in exceptions.
The solution (at least for .Net Core 2.2) is to instead use the [Microsoft.PowerShell.SDK NuGet package](https://www.nuget.org/packages/Microsoft.PowerShell.SDK/) in your test project.

So to create your test project:

1. Add a new C# `xUnit Test Project (.Net Core)` project to your solution.
1. Include the `Microsoft.PowerShell.SDK` NuGet package in your test project.
1. Add a reference to the project you created above where your cmdlet is defined.

Now you can create a new class and call the `Invoke()` method on your cmdlet to exercise it.
PowerShell cmdlets have the option of returning multiple objects, so to make sure you read all of the cmdlet output, you'll want to enumerate over all of the results.
One example of how you might do this could be:

```csharp
var results = new List<string>();
var enumerator = cmdlet.Invoke().GetEnumerator();
while (enumerator.MoveNext())
{
    var result = enumerator.Current as string;
    results.Add(result);
}
```

This works, but is a bit verbose to include in ever test.
Luckily during a code review, a co-worker pointed out that I could condense that code down into the following:

```csharp
var results = cmdlet.Invoke().OfType<string>().ToList();
```

That's much more condensed and readable.
Here is an example of a full xUnit test that ensures a cmdlet returns back the expected string:

```csharp
[Fact]
public void ShouldReturnThePhraseRepeatedTheCorrectNumberOfTimes()
{
    // Arrange.
    var phrase = "A test phrase.";
    int numberOfTimesToRepeat = 3;
    var cmdlet = new GetRepeatedPhraseCmdlet()
    {
        Phrase = phrase,
        NumberOfTimesToRepeatPhrase = numberOfTimesToRepeat
    };
    var expectedResult = "A test phrase.A test phrase.A test phrase.";

    // Act.
    var results = cmdlet.Invoke().OfType<string>().ToList();

    // Assert.
    Assert.Equal(results.First(), expectedResult);
    Assert.True(results.Count == 1);
}
```

And here is another example that ensures another cmdlet returns back the expected string array:

```csharp
[Fact]
public void ShouldReturnThePhraseRepeatedTheCorrectNumberOfTimes()
{
    // Arrange.
    var phrase = "A test phrase.";
    int numberOfTimesToRepeat = 3;
    var cmdlet = new GetRepeatedPhraseCollectionCmdlet()
    {
        Phrase = phrase,
        NumberOfTimesToRepeatPhrase = numberOfTimesToRepeat
    };
    var expectedResult = Enumerable.Repeat(phrase, numberOfTimesToRepeat);

    // Act.
    var results = cmdlet.Invoke().OfType<string>().ToList();

    // Assert.
    Assert.Equal(results, expectedResult);
}
```

## Downloadable example

I've created [a small sample solution on GitHub](https://github.com/deadlydog/PowerShellCmdletInCSharpExample) that you can check out.
It shows how to create a couple basic PowerShell Core cmdlets, as well as a test project to test both cmdlets using xUnit.

## Conclusion

Hopefully you don't fall into the same traps I did when trying to test my PowerShell Core cmdlets in C#.
In this post I've shown you the basics of what is needed to create PowerShell Core cmdlets, as well as how to test them.
I've also provided a sample app that you can poke around in for any details that I may have missed in this post.

Happy coding!
