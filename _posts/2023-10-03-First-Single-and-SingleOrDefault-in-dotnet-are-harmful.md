---
title: "First, Single, and SingleOrDefault in .NET are harmful"
permalink: /First-Single-and-SingleOrDefault-in-dotnet-are-harmful/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
toc: false
categories:
  - .NET
  - C#
tags:
  - .NET
  - C#
---

I've wasted countless hours troubleshooting exceptions thrown by the .NET `First`, `Single`, and `SingleOrDefault` methods.
In this post we'll look at why these methods can be harmful, and what you should do instead.

## TL;DR

`Single`, `SingleOrDefault`, and `First` throw exceptions that are vague and unhelpful.
You are better off to write the logic yourself and include rich error/log information that will help with troubleshooting.

## What is Single, SingleOrDefault, First, and FirstOrDefault

`Single`, `SingleOrDefault`, `First`, and `FirstOrDefault` are LINQ extension methods that return the first element of a sequence that satisfies a specified condition.
The typical use cases for these methods are:

- Use `Single` to retrieve an item, ensuring that one and only one item matches the condition.
- Use `SingleOrDefault` to retrieve an item, and ensure that it is the only one that matches the condition.
  If no items match the condition a default item is returned.
- Use `First` to retrieve the first item, and throw an exception if no matches are found.
  It does not care if multiple items match the condition.
- Use `FirstOrDefault` to retrieve the first item.
  If no items match the condition a default item is returned, and it does not care if multiple items match the condition.

Here is an example of using them in C#:

```csharp
var people = new List<string> { "Alfred Archer", "Billy Baller", "Billy Bob", "Cathy Carter" };
---
var alfred = people.First(x => x.StartsWith("Alfred"));
var billy = people.First(x => x.StartsWith("Billy")); // Returns Billy Baller
var zane = people.First(x => x.StartsWith("Zane")); // Throws an exception
---
var alfred = people.FirstOrDefault(x => x.StartsWith("Alfred"));
var billy = people.FirstOrDefault(x => x.StartsWith("Billy")); // Returns Billy Baller
var zane = people.FirstOrDefault(x => x.StartsWith("Zane")); // Returns null
---
var alfred = people.Single(x => x.StartsWith("Alfred"));
var billy = people.Single(x => x.StartsWith("Billy")); // Throws an exception
var zane = people.Single(x => x.StartsWith("Zane")); // Throws an exception
---
var alfred = people.SingleOrDefault(x => x.StartsWith("Alfred"));
var billy = people.SingleOrDefault(x => x.StartsWith("Billy")); // Throws an exception
var zane = people.SingleOrDefault(x => x.StartsWith("Zane")); // Returns null
```

## Why First, Single, and SingleOrDefault are harmful

When `First` and `Single` do not find any items that match the condition, they throw the following exception:

> System.InvalidOperationException: Sequence contains no matching element

When `Single` and `SingleOrDefault` find more than one element that satisfies the condition, they throw the following exception:

> System.InvalidOperationException: Sequence contains more than one matching element

Both of these exception messages are vague and unhelpful.
Imagine seeing it in your application logs, or worse, returned to the user.
Would you know right away what the problem was and how to fix it?
Even with a stack trace, which we do not always have, it may not be obvious.

The message doesn't tell us what dataset was searched, what the condition was, or what elements satisfied the condition.
This is crucial information to know when troubleshooting what went wrong.

Not only is the exception message unhelpful, but throwing an exception is an expensive operation that is best to avoid when possible.

`FirstOrDefault` is the only method that does not throw an exception, and thus is the only one that I would recommend using.

## So should I just use FirstOrDefault instead?

Simply using `FirstOrDefault` instead of `SingleOrDefault` is not a good solution.
If you were considering using `SingleOrDefault`, then you are probably trying to validate that only a single item was returned, as more than one item returned may mean that something is wrong with your query, your data, your code, or your business logic.
If we just use `FirstOrDefault` instead, it hides the issue that multiple items matched the search criteria and we may never realize that there is a problem.

In our example above, when using `FirstOrDefault` the `billy` variable would be set to "Billy Baller" and there would be no indication that there was another "Billy" in the dataset.
This could lead to problems if our logic expects there to only be one "Billy" in the dataset and makes decisions based on that assumption.

Similarly for `First` and `Single`, simply using `FirstOrDefault` in their place without additional validation to ensure that a result was found is a recipe for disaster.

## So what should I do instead?

One solution is to use `Where` instead of `Single` and `SingleOrDefault`, and then explicitly validate that only a single item was returned.
This allows us to return a rich error message, as well as avoid the expensive exception being thrown if we want.

Here is one example of how we could do this:

```csharp
var people = new List<string> { "Alfred Archer", "Billy Baller", "Billy Bob", "Cathy Carter" };

var name = "Billy";
var persons = people.Where(x => x.StartsWith(name)).ToList();
if (persons.Count > 1)
{
    throw new TooManyPeopleFoundException(
      $"Expected to only find one person named '{name}', but found {persons.Count} in our people list: {string.Join(", ", persons)}");
}
```

You can see that the exception thrown contains much more information to help troubleshoot the problem, such as:

- We used a specific type of exception, `TooManyPeopleFoundException`.
- We include the condition that was used to find the people. e.g. person named {name}.
- We include the number of items found. e.g. {persons.Count}
- We include the data source that was searched. e.g. our people list.
- We include all of the matching items that were found. e.g. Billy Baller, Billy Bob.

If we also wanted to ensure that at least one item was found, we could add the following check:

```csharp
if (persons.Count == 0)
{
    throw new PersonNotFoundException($"Could not find a person named '{name}' in our people list.");
}
```

This is just one example of how you could implement this.
You might choose to create your own SingleItemOrDefault helper method or extension method that performs the operations and adds the information to the exception.
You might not want to throw an exception at all, but instead use the Result pattern to return a failed result with the rich information.

The above shows how to avoid using `Single` and `SingleOrDefault`.
Let's see how we can avoid using `First` as well.

Rather than using `First` we can leverage `FirstOrDefault`.
`FirstOrDefault` is preferred over `Where(...).ToList()` and checking `Count == 0` to avoid iterating over the entire collection when a match exists.
Here's an example of using `FirstOrDefault` instead of `First`:

```csharp
var name = "Zane";
var person = people.FirstOrDefault(x => x.StartsWith(name));
if (person == null)
{
    throw new PersonNotFoundException($"Could not find a person named '{name}' in our people list.");
}
```

You will need to be mindful of the default value for the type you are working with.
Object types will be null, but value types will be their default value.

The important thing is that you do not rely on the default `InvalidOperationException`, and that your error message includes all the information that will help you troubleshoot any issues.
This is a good general rule to follow for any error logging you perform.
Depending on the sensitivity of the data, you may need to be careful about which information you include in the error.

## Why don't I just catch the exception and add information?

While you could do something like this:

```csharp
var name = "Billy";
try
{
    var person = people.SingleOrDefault(x => x.StartsWith(name));
}
catch (InvalidOperationException ex)
{
    var persons = people.Where(x => x.StartsWith(name)).ToList();
    throw new TooManyPeopleFoundException(
      $"Expected to only find one person named '{name}', but found {persons.Count} in our people list: {string.Join(", ", persons)}", ex);
}
```

This is not performant.
We've already mentioned that throwing exceptions is expensive, and this code now throws two.
Also, in order to get the useful information to include in the error message it has to run the `Where` query anyways, so why not just do that in the first place?

## Conclusion

You might look at the recommended code and wonder if the extra few lines of code are worth it.
I guarantee you it is.
You can even write a helper or extension method to make the pattern easier to use.

Spending an extra few minutes to add detailed information to your errors will save you and your team hours of troubleshooting in the future.
There are some scenarios where it is impossible to ever solve the root issue without this extra information, such as when validating ephemeral data that no longer exists by the time you get to troubleshooting.

Over the past decade I have seen people advocate for `First`, `Single`, and `SingleOrDefault`.
In theory they are a good idea, but the current .NET implementation leads to more problems than it is worth.
Until the method is updated to at least allow you to provide a custom error message that includes extra information, I always caution against using them and instead recommend writing the logic yourself.

I even went so far as to create a checkin policy that would prevent developers from committing code that used `First`, `Single`, and `SingleOrDefault` in our flagship product.
That should give you an idea of how many developer and support staff hours were wasted tracking down "Sequence contains no matching element" and "Sequence contains more than one matching element" errors.
Similar logic could be implemented today as a Roslyn analyzer.

I hope you've found this post helpful, and that it saves future you countless hours of troubleshooting.

Happy coding!
