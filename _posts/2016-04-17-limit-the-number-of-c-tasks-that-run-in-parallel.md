---
id: 875
title: 'Limit The Number Of C# Tasks That Run In Parallel'
date: 2016-04-17T02:46:32-06:00
author: deadlydog
guid: http://dans-blog.azurewebsites.net/?p=875
permalink: /limit-the-number-of-c-tasks-that-run-in-parallel/
categories:
  - 'C#'
tags:
  - 'C# Task Thread Throttle Limit Maximum Simultaneous Concurrent Parallel'
---
### Why I needed to throttle the number of Tasks running simultaneously

In the past few months I have come across the scenario where I wanted to run a whole bunch of Tasks (potentially thousands), but didn’t necessarily want to run all (or even a lot) of them in parallel at the same time. In my scenarios I was using the Tasks to make web requests; not CPU-heavy work, otherwise I would have opted for using Parallel.Foreach.

The first time I encountered this problem, it was because my application would be running on the cheapest VM that I could get from AWS; this meant a server with 1 slow CPU and less than 1GB of RAM.  Telling that server to spin up 100 threads simultaneously likely would not end very well. I realize that the OS determines how many threads to run at a time, so likely not all 100 threads would run concurrently, but having the ability to specify a lower maximum than the OS would use gives us more control over bulkheading our application to make sure it plays nice and does not consume too many server resources.

The second time, I needed to request information from one of our company’s own web services. The web service used pagination for retrieving a list of user. There was no endpoint that would give me all users in one shot; instead I had to request the users on page 1, page 2, page 3, etc. until I reached the last page. In this case, my concern was around DOSing (Denial of Service) our own web service. If I created 500 web request Tasks to retrieve the users from 500 pages and made all of the requests simultaneously, I risked putting a lot of stress on my web service, and potentially on my network as well.

In both of these cases I was looking for a solution that would still complete all of the Tasks I created, but would allow me to specify that a maximum of, say 5, should run at the same time.

### What the code to run a bunch of Tasks typically looks like

Let’s say you have a function that you want to run a whole bunch of times concurrently in separate Tasks:

<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:55a262de-a229-4fa2-88c5-e57d173b4912" class="wlWriterEditableSmartContent" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre><pre class="brush: csharp; title: ; notranslate" title="">
public void DoSomething(int taskNumber)
{
	Thread.Sleep(TimeSpan.FromSeconds(1));
	Console.WriteLine("Task Number: " + taskNumber);
}
</pre>
</div>


<p>
  Here is how you typically might start up 100 Tasks to do something:
</p>


<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:8bfaa58a-a1bd-419e-9aeb-869aba718a56" class="wlWriterEditableSmartContent" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre><pre class="brush: csharp; pad-line-numbers: true; title: ; notranslate" title="">
public void DoSomethingALotWithTasks()
{
	var listOfTasks = new List&amp;lt;Task&amp;gt;();
	for (int i = 0; i &amp;lt; 100; i++)
	{
		var count = i;
		// Note that we start the Task here too.
		listOfTasks.Add(Task.Run(() =&amp;gt; Something(count)));
	}
	Task.WaitAll(listOfTasks.ToArray());
}
</pre>
</div>


<h3>
  What the code to run a bunch of Tasks and throttle how many are ran concurrently looks like
</h3>


<p>
  Here is how you would run those same tasks using the throttling function I provide further down, limiting it to running at most 3 Tasks simultaneously.
</p>


<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:3506012f-3631-46d0-bf3a-8726e5df4fb9" class="wlWriterEditableSmartContent" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre><pre class="brush: csharp; title: ; notranslate" title="">
public void DoSomethingALotWithTasksThrottled()
{
	var listOfTasks = new List&amp;lt;Task&amp;gt;();
	for (int i = 0; i &amp;lt; 100; i++)
	{
		var count = i;
		// Note that we create the Task here, but do not start it.
		listOfTasks.Add(new Task(() =&amp;gt; Something(count)));
	}
	Tasks.StartAndWaitAllThrottled(listOfTasks, 3);
}
</pre>
</div>


<h3>
  Gimme the code to limit my concurrent Tasks!
</h3>


<p>
  Because I needed this solution in different projects, I created a nice generic, reusable function for it. I’m presenting the functions here, and they can also be found in <a href="https://dansutilitylibraries.codeplex.com/SourceControl/latest#DansUtilityLibraries/DansCSharpLibrary/Threading/Tasks.cs">my own personal open-source utility library here</a>.
</p>


<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:35855094-0bd5-42a0-a78e-f45ef50f26f8" class="wlWriterEditableSmartContent" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre><pre class="brush: csharp; pad-line-numbers: true; title: ; notranslate" title="">
/// &amp;lt;summary&amp;gt;
/// Starts the given tasks and waits for them to complete. This will run, at most, the specified number of tasks in parallel.
/// &amp;lt;para&amp;gt;NOTE: If one of the given tasks has already been started, an exception will be thrown.&amp;lt;/para&amp;gt;
/// &amp;lt;/summary&amp;gt;
/// &amp;lt;param name="tasksToRun"&amp;gt;The tasks to run.&amp;lt;/param&amp;gt;
/// &amp;lt;param name="maxActionsToRunInParallel"&amp;gt;The maximum number of tasks to run in parallel.&amp;lt;/param&amp;gt;
/// &amp;lt;param name="cancellationToken"&amp;gt;The cancellation token.&amp;lt;/param&amp;gt;
public static void StartAndWaitAllThrottled(IEnumerable&amp;lt;Task&amp;gt; tasksToRun, int maxActionsToRunInParallel, CancellationToken cancellationToken = new CancellationToken())
{
	StartAndWaitAllThrottled(tasksToRun, maxActionsToRunInParallel, -1, cancellationToken);
}

/// &amp;lt;summary&amp;gt;
/// Starts the given tasks and waits for them to complete. This will run the specified number of tasks in parallel.
/// &amp;lt;para&amp;gt;NOTE: If a timeout is reached before the Task completes, another Task may be started, potentially running more than the specified maximum allowed.&amp;lt;/para&amp;gt;
/// &amp;lt;para&amp;gt;NOTE: If one of the given tasks has already been started, an exception will be thrown.&amp;lt;/para&amp;gt;
/// &amp;lt;/summary&amp;gt;
/// &amp;lt;param name="tasksToRun"&amp;gt;The tasks to run.&amp;lt;/param&amp;gt;
/// &amp;lt;param name="maxActionsToRunInParallel"&amp;gt;The maximum number of tasks to run in parallel.&amp;lt;/param&amp;gt;
/// &amp;lt;param name="timeoutInMilliseconds"&amp;gt;The maximum milliseconds we should allow the max tasks to run in parallel before allowing another task to start. Specify -1 to wait indefinitely.&amp;lt;/param&amp;gt;
/// &amp;lt;param name="cancellationToken"&amp;gt;The cancellation token.&amp;lt;/param&amp;gt;
public static void StartAndWaitAllThrottled(IEnumerable&amp;lt;Task&amp;gt; tasksToRun, int maxActionsToRunInParallel, int timeoutInMilliseconds, CancellationToken cancellationToken = new CancellationToken())
{
	// Convert to a list of tasks so that we don't enumerate over it multiple times needlessly.
	var tasks = tasksToRun.ToList();

	using (var throttler = new SemaphoreSlim(maxActionsToRunInParallel))
	{
		var postTaskTasks = new List&amp;lt;Task&amp;gt;();

		// Have each task notify the throttler when it completes so that it decrements the number of tasks currently running.
		tasks.ForEach(t =&amp;gt; postTaskTasks.Add(t.ContinueWith(tsk =&amp;gt; throttler.Release())));

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
</pre>
</div>


<p>
  Above I have them defined as static functions on my own Tasks class, but you can define them however you like. Notice that the functions also Start the Tasks, so <strong>you should not start them before passing them into these functions</strong>, otherwise an exception will be thrown when it tries to restart a Task. The last thing to note is you will need to include the <strong>System.Threading</strong> and <strong>System.Threading.Tasks</strong> namespaces.
</p>


<p>
  Here are the async equivalents of the above functions, to make it easy to not block the UI thread while waiting for your tasks to complete:
</p>


<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:cbe26fbe-3ac2-463d-8d5d-52680e365064" class="wlWriterEditableSmartContent" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre><pre class="brush: csharp; title: ; notranslate" title="">
/// &amp;lt;summary&amp;gt;
/// Starts the given tasks and waits for them to complete. This will run, at most, the specified number of tasks in parallel.
/// &amp;lt;para&amp;gt;NOTE: If one of the given tasks has already been started, an exception will be thrown.&amp;lt;/para&amp;gt;
/// &amp;lt;/summary&amp;gt;
/// &amp;lt;param name="tasksToRun"&amp;gt;The tasks to run.&amp;lt;/param&amp;gt;
/// &amp;lt;param name="maxTasksToRunInParallel"&amp;gt;The maximum number of tasks to run in parallel.&amp;lt;/param&amp;gt;
/// &amp;lt;param name="cancellationToken"&amp;gt;The cancellation token.&amp;lt;/param&amp;gt;
public static async Task StartAndWaitAllThrottledAsync(IEnumerable&amp;lt;Task&amp;gt; tasksToRun, int maxTasksToRunInParallel, CancellationToken cancellationToken = new CancellationToken())
{
	await StartAndWaitAllThrottledAsync(tasksToRun, maxTasksToRunInParallel, -1, cancellationToken);
}

/// &amp;lt;summary&amp;gt;
/// Starts the given tasks and waits for them to complete. This will run the specified number of tasks in parallel.
/// &amp;lt;para&amp;gt;NOTE: If a timeout is reached before the Task completes, another Task may be started, potentially running more than the specified maximum allowed.&amp;lt;/para&amp;gt;
/// &amp;lt;para&amp;gt;NOTE: If one of the given tasks has already been started, an exception will be thrown.&amp;lt;/para&amp;gt;
/// &amp;lt;/summary&amp;gt;
/// &amp;lt;param name="tasksToRun"&amp;gt;The tasks to run.&amp;lt;/param&amp;gt;
/// &amp;lt;param name="maxTasksToRunInParallel"&amp;gt;The maximum number of tasks to run in parallel.&amp;lt;/param&amp;gt;
/// &amp;lt;param name="timeoutInMilliseconds"&amp;gt;The maximum milliseconds we should allow the max tasks to run in parallel before allowing another task to start. Specify -1 to wait indefinitely.&amp;lt;/param&amp;gt;
/// &amp;lt;param name="cancellationToken"&amp;gt;The cancellation token.&amp;lt;/param&amp;gt;
public static async Task StartAndWaitAllThrottledAsync(IEnumerable&amp;lt;Task&amp;gt; tasksToRun, int maxTasksToRunInParallel, int timeoutInMilliseconds, CancellationToken cancellationToken = new CancellationToken())
{
	// Convert to a list of tasks so that we don't enumerate over it multiple times needlessly.
	var tasks = tasksToRun.ToList();

	using (var throttler = new SemaphoreSlim(maxTasksToRunInParallel))
	{
		var postTaskTasks = new List&amp;lt;Task&amp;gt;();

		// Have each task notify the throttler when it completes so that it decrements the number of tasks currently running.
		tasks.ForEach(t =&amp;gt; postTaskTasks.Add(t.ContinueWith(tsk =&amp;gt; throttler.Release())));

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
</pre>
</div>


<h4>
  <Update date=2016-04-29>
</h4>


<p>
  Shortly after publishing this post I discovered <a href="https://msdn.microsoft.com/en-us/library/system.threading.tasks.parallel.invoke%28v=vs.110%29.aspx">Parallel.Invoke</a>, which can also throttle the number of threads, but takes <a href="https://msdn.microsoft.com/en-us/library/system.action%28v=vs.110%29.aspx">Actions</a> as input instead of <a href="https://msdn.microsoft.com/en-us/library/system.threading.tasks.task%28v=vs.110%29.aspx">Tasks</a>. Here’s an example of how to limit the number of threads using Parallel.Invoke:
</p>


<div id="scid:C89E2BDB-ADD3-4f7a-9810-1B7EACF446C1:4f5ad34c-d3ce-41b8-bd3c-764942d52656" class="wlWriterEditableSmartContent" style="float: none; margin: 0px; display: inline; padding: 0px;">
  <pre><pre class="brush: csharp; pad-line-numbers: true; title: ; notranslate" title="">
public static void DoSomethingALotWithActionsThrottled()
{
	var listOfActions = new List&amp;lt;Action&amp;gt;();
	for (int i = 0; i &amp;lt; 100; i++)
	{
		var count = i;
		// Note that we create the Action here, but do not start it.
		listOfActions.Add(() =&amp;gt; DoSomething(count));
	}

	var options = new ParallelOptions {MaxDegreeOfParallelism = 3};
	Parallel.Invoke(options, listOfActions.ToArray());
}
</pre>
</div>


<p>
  Notice that you define the max number of threads to run simultaneously by using the <a href="https://msdn.microsoft.com/en-us/library/system.threading.tasks.paralleloptions%28v=vs.110%29.aspx">ParallelOptions</a> classes MaxDegreeOfParallelism property, which also accepts a CancellationToken if needed. This method is nice because it doesn’t require having the additional custom code; it’s all built into .Net. However, it does mean dealing with Actions instead of Tasks, which isn’t a bad thing at all, but you may have a personal preference of which one you prefer to work with. Also, Tasks can offer additional functionality, such as <a href="https://msdn.microsoft.com/en-us/library/dd270696%28v=vs.110%29.aspx">ContinueWith()</a>, and Parallel.Invoke does not provide an asynchronous version, but my functions do. According to <a href="https://msdn.microsoft.com/en-us/library/ff963549.aspx">this MSDN page</a>, Parallel.Invoke uses Task.WaitAll() under the hood, so they should be equivalent performance-wise, and there shouldn’t be any situations where using one is preferable over the other. <a href="https://msdn.microsoft.com/en-us/library/dd537609%28v=vs.110%29.aspx">This other MSDN page</a> goes into detail about Tasks, and also mentions using Parallel.Invoke near the start.
</p>


<h4>
  </Update>
</h4>


<p>
  And if you don’t believe me that this works, you can <a href="http://dans-blog.azurewebsites.net/wp-content/uploads/2016/04/LimitNumberOfSimultaneousTasksExample.zip">take a look at this sample project and run the code for yourself</a>.
</p>


<p>
  I hope you find this information useful. Happy coding!
</p>
