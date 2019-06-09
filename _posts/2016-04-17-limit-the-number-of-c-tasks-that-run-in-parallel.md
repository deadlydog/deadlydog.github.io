---
title: Limit The Number Of C# Tasks That Run In Parallel
date: 2016-04-17T02:46:32-06:00
last_modified_at: 2016-04-29T00:00:00-00:00
permalink: /limit-the-number-of-c-tasks-that-run-in-parallel/
categories:
  - C#
tags:
  - C#
  - Task
  - Thread
  - Throttle
  - Limit
  - Maximum
  - Simultaneous
  - Concurrent
  - Parallel
---

## Why I needed to throttle the number of Tasks running simultaneously

In the past few months I have come across the scenario where I wanted to run a whole bunch of Tasks (potentially thousands), but didn’t necessarily want to run all (or even a lot) of them in parallel at the same time. In my scenarios I was using the Tasks to make web requests; not CPU-heavy work, otherwise I would have opted for using Parallel.Foreach.

The first time I encountered this problem, it was because my application would be running on the cheapest VM that I could get from AWS; this meant a server with 1 slow CPU and less than 1GB of RAM.  Telling that server to spin up 100 threads simultaneously likely would not end very well. I realize that the OS determines how many threads to run at a time, so likely not all 100 threads would run concurrently, but having the ability to specify a lower maximum than the OS would use gives us more control over bulkheading our application to make sure it plays nice and does not consume too many server resources.

The second time, I needed to request information from one of our company’s own web services. The web service used pagination for retrieving a list of user. There was no endpoint that would give me all users in one shot; instead I had to request the users on page 1, page 2, page 3, etc. until I reached the last page. In this case, my concern was around DOSing (Denial of Service) our own web service. If I created 500 web request Tasks to retrieve the users from 500 pages and made all of the requests simultaneously, I risked putting a lot of stress on my web service, and potentially on my network as well.

In both of these cases I was looking for a solution that would still complete all of the Tasks I created, but would allow me to specify that a maximum of, say 5, should run at the same time.

## What the code to run a bunch of Tasks typically looks like

Let’s say you have a function that you want to run a whole bunch of times concurrently in separate Tasks:

```csharp
public void DoSomething(int taskNumber)
{
    Thread.Sleep(TimeSpan.FromSeconds(1));
    Console.WriteLine("Task Number: " + taskNumber);
}
```

Here is how you typically might start up 100 Tasks to do something:

```csharp
public void DoSomethingALotWithTasks()
{
    var listOfTasks = new List<Task>();
    for (int i = 0; i < 100; i++)
    {
        var count = i;
        // Note that we start the Task here too.
        listOfTasks.Add(Task.Run(() => Something(count)));
    }
    Task.WaitAll(listOfTasks.ToArray());
}
```

## What the code to run a bunch of Tasks and throttle how many are ran concurrently looks like

Here is how you would run those same tasks using the throttling function I provide further down, limiting it to running at most 3 Tasks simultaneously.

```csharp
public void DoSomethingALotWithTasksThrottled()
{
    var listOfTasks = new List<Task>();
    for (int i = 0; i < 100; i++)
    {
        var count = i;
        // Note that we create the Task here, but do not start it.
        listOfTasks.Add(new Task(() => Something(count)));
    }
    Tasks.StartAndWaitAllThrottled(listOfTasks, 3);
}
```

## Gimme the code to limit my concurrent Tasks!

Because I needed this solution in different projects, I created a nice generic, reusable function for it. I’m presenting the functions here, and they can also be found in [my own personal open-source utility library here](https://dansutilitylibraries.codeplex.com/SourceControl/latest#DansUtilityLibraries/DansCSharpLibrary/Threading/Tasks.cs).

```csharp
/// <summary>
/// Starts the given tasks and waits for them to complete. This will run, at most, the specified number of tasks in parallel.
/// <para>NOTE: If one of the given tasks has already been started, an exception will be thrown.</para>
/// </summary>
/// <param name="tasksToRun">The tasks to run.</param>
/// <param name="maxActionsToRunInParallel">The maximum number of tasks to run in parallel.</param>
/// <param name="cancellationToken">The cancellation token.</param>
public static void StartAndWaitAllThrottled(IEnumerable<Task> tasksToRun, int maxActionsToRunInParallel, CancellationToken cancellationToken = new CancellationToken())
{
    StartAndWaitAllThrottled(tasksToRun, maxActionsToRunInParallel, -1, cancellationToken);
}

/// <summary>
/// Starts the given tasks and waits for them to complete. This will run the specified number of tasks in parallel.
/// <para>NOTE: If a timeout is reached before the Task completes, another Task may be started, potentially running more than the specified maximum allowed.</para>
/// <para>NOTE: If one of the given tasks has already been started, an exception will be thrown.</para>
/// </summary>
/// <param name="tasksToRun">The tasks to run.</param>
/// <param name="maxActionsToRunInParallel">The maximum number of tasks to run in parallel.</param>
/// <param name="timeoutInMilliseconds">The maximum milliseconds we should allow the max tasks to run in parallel before allowing another task to start. Specify -1 to wait indefinitely.</param>
/// <param name="cancellationToken">The cancellation token.</param>
public static void StartAndWaitAllThrottled(IEnumerable<Task> tasksToRun, int maxActionsToRunInParallel, int timeoutInMilliseconds, CancellationToken cancellationToken = new CancellationToken())
{
    // Convert to a list of tasks so that we don't enumerate over it multiple times needlessly.
    var tasks = tasksToRun.ToList();

    using (var throttler = new SemaphoreSlim(maxActionsToRunInParallel))
    {
        var postTaskTasks = new List<Task>();

        // Have each task notify the throttler when it completes so that it decrements the number of tasks currently running.
        tasks.ForEach(t => postTaskTasks.Add(t.ContinueWith(tsk => throttler.Release())));

        // Start running each task.
        foreach (var task in tasks)
        {
            // Increment the number of tasks currently running and wait if too many are running.
            throttler.Wait(timeoutInMilliseconds, cancellationToken);

            cancellationToken.ThrowIfCancellationRequested();
            task.Start();
        }

        // Wait for all of the provided tasks to complete.
        // We wait on the list of "post" tasks instead of the original tasks, otherwise there is a potential race condition where the throttler&amp;amp;#39;s using block is exited before some Tasks have had their "post" action completed, which references the throttler, resulting in an exception due to accessing a disposed object.
        Task.WaitAll(postTaskTasks.ToArray(), cancellationToken);
    }
}
```

Above I have them defined as static functions on my own Tasks class, but you can define them however you like. Notice that the functions also Start the Tasks, so __you should not start them before passing them into these functions__, otherwise an exception will be thrown when it tries to restart a Task. The last thing to note is you will need to include the `System.Threading` and `System.Threading.Tasks` namespaces.

Here are the async equivalents of the above functions, to make it easy to not block the UI thread while waiting for your tasks to complete:

```csharp
/// <summary>
/// Starts the given tasks and waits for them to complete. This will run, at most, the specified number of tasks in parallel.
/// <para>NOTE: If one of the given tasks has already been started, an exception will be thrown.</para>
/// </summary>
/// <param name="tasksToRun">The tasks to run.</param>
/// <param name="maxTasksToRunInParallel">The maximum number of tasks to run in parallel.</param>
/// <param name="cancellationToken">The cancellation token.</param>
public static async Task StartAndWaitAllThrottledAsync(IEnumerable<Task> tasksToRun, int maxTasksToRunInParallel, CancellationToken cancellationToken = new CancellationToken())
{
    await StartAndWaitAllThrottledAsync(tasksToRun, maxTasksToRunInParallel, -1, cancellationToken);
}

/// <summary>
/// Starts the given tasks and waits for them to complete. This will run the specified number of tasks in parallel.
/// <para>NOTE: If a timeout is reached before the Task completes, another Task may be started, potentially running more than the specified maximum allowed.</para>
/// <para>NOTE: If one of the given tasks has already been started, an exception will be thrown.</para>
/// </summary>
/// <param name="tasksToRun">The tasks to run.</param>
/// <param name="maxTasksToRunInParallel">The maximum number of tasks to run in parallel.</param>
/// <param name="timeoutInMilliseconds">The maximum milliseconds we should allow the max tasks to run in parallel before allowing another task to start. Specify -1 to wait indefinitely.</param>
/// <param name="cancellationToken">The cancellation token.</param>
public static async Task StartAndWaitAllThrottledAsync(IEnumerable<Task> tasksToRun, int maxTasksToRunInParallel, int timeoutInMilliseconds, CancellationToken cancellationToken = new CancellationToken())
{
    // Convert to a list of tasks so that we don't enumerate over it multiple times needlessly.
    var tasks = tasksToRun.ToList();

    using (var throttler = new SemaphoreSlim(maxTasksToRunInParallel))
    {
        var postTaskTasks = new List<Task>();

        // Have each task notify the throttler when it completes so that it decrements the number of tasks currently running.
        tasks.ForEach(t => postTaskTasks.Add(t.ContinueWith(tsk => throttler.Release())));

        // Start running each task.
        foreach (var task in tasks)
        {
            // Increment the number of tasks currently running and wait if too many are running.
            await throttler.WaitAsync(timeoutInMilliseconds, cancellationToken);

            cancellationToken.ThrowIfCancellationRequested();
            task.Start();
        }

        // Wait for all of the provided tasks to complete.
        // We wait on the list of "post" tasks instead of the original tasks, otherwise there is a potential race condition where the throttler&amp;amp;#39;s using block is exited before some Tasks have had their "post" action completed, which references the throttler, resulting in an exception due to accessing a disposed object.
        await Task.WhenAll(postTaskTasks.ToArray());
    }
}
```

And if you don’t believe me that this works, you can [take a look at this sample project and run the code for yourself](/assets/Posts/2016/04/LimitNumberOfSimultaneousTasksExample.zip).

## Update: 2016-04-29

Shortly after publishing this post I discovered [Parallel.Invoke](https://msdn.microsoft.com/en-us/library/system.threading.tasks.parallel.invoke%28v=vs.110%29.aspx), which can also throttle the number of threads, but takes [Actions](https://msdn.microsoft.com/en-us/library/system.action%28v=vs.110%29.aspx) as input instead of [Tasks](https://msdn.microsoft.com/en-us/library/system.threading.tasks.task%28v=vs.110%29.aspx). Here’s an example of how to limit the number of threads using Parallel.Invoke:

```csharp
public static void DoSomethingALotWithActionsThrottled()
{
    var listOfActions = new List<Action>();
    for (int i = 0; i < 100; i++)
    {
        var count = i;
        // Note that we create the Action here, but do not start it.
        listOfActions.Add(() => DoSomething(count));
    }

    var options = new ParallelOptions {MaxDegreeOfParallelism = 3};
    Parallel.Invoke(options, listOfActions.ToArray());
}
```

Notice that you define the max number of threads to run simultaneously by using the [ParallelOptions](https://msdn.microsoft.com/en-us/library/system.threading.tasks.paralleloptions%28v=vs.110%29.aspx) classes MaxDegreeOfParallelism property, which also accepts a CancellationToken if needed. This method is nice because it doesn’t require having the additional custom code; it’s all built into .Net. However, it does mean dealing with Actions instead of Tasks, which isn’t a bad thing at all, but you may have a personal preference of which one you prefer to work with. Also, Tasks can offer additional functionality, such as [ContinueWith()](https://msdn.microsoft.com/en-us/library/dd270696%28v=vs.110%29.aspx), and Parallel.Invoke does not provide an asynchronous version, but my functions do. According to [this MSDN page](https://msdn.microsoft.com/en-us/library/ff963549.aspx), Parallel.Invoke uses Task.WaitAll() under the hood, so they should be equivalent performance-wise, and there shouldn’t be any situations where using one is preferable over the other. [This other MSDN page](https://msdn.microsoft.com/en-us/library/dd537609%28v=vs.110%29.aspx) goes into detail about Tasks, and also mentions using Parallel.Invoke near the start.

I hope you find this information useful. Happy coding!
