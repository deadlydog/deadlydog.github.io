---
title: "Catch and display unhandled exceptions in your WPF app"
permalink: /Catch-and-display-unhandled-exceptions-in-your-WPF-app/
#date: 2099-01-17T00:00:00-06:00
#last_modified_at: 2099-01-22T00:00:00-06:00
comments_locked: false
categories:
  - WPF
  - C#
tags:
  - WPF
  - C#
  - Exception
  - Error
---

When creating a WPF application, one of the best things you can do upfront is add some code to catch any unhandled exceptions.
There are [numerous ways unhandled exceptions can be caught](https://stackoverflow.com/a/1472562/602585), and [this Stack Overflow answer](https://stackoverflow.com/a/46804709/602585) shows how you can nicely handle them.
One thing I especially like about that answer is they also capture the application's assembly name and version in the error message being logged, which can be helpful if there are multiple versions of your app in the wild.

I thought I would show a sample here though that handles the exceptions a bit differently, allowing the user to choose and try and keep the app alive when applicable, rather than it hard crashing.
This is what the `App.xaml.cs` file looks like:

```csharp
using System;
using System.Diagnostics;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Threading;

namespace WpfApplicationCatchUnhandledExceptionsExample
{
    public partial class App : Application
    {
        public App() : base()
        {
            SetupUnhandledExceptionHandling();
        }

        private void SetupUnhandledExceptionHandling()
        {
            // Catch all unhandled exceptions.
            AppDomain.CurrentDomain.UnhandledException += CurrentDomain_UnhandledException; // Caught from all threads in the AppDomain.
            TaskScheduler.UnobservedTaskException += TaskScheduler_UnobservedTaskException; // Caught from each AppDomain that uses a task scheduler for async operations.
            Dispatcher.UnhandledException += Dispatcher_UnhandledException; // Caught from a single specific UI dispatcher thread.

            // Typically only need to catch this OR the Dispatcher.UnhandledException.
            // Handling both can result in the exception getting handled twice.
            //Application.Current.DispatcherUnhandledException += Current_DispatcherUnhandledException;   // Caught from the main UI dispatcher thread.
        }

        private void CurrentDomain_UnhandledException(object sender, UnhandledExceptionEventArgs args)
        {
            ShowUnhandledException(args.ExceptionObject as Exception, "AppDomain.CurrentDomain.UnhandledException", false);
        }

        private void TaskScheduler_UnobservedTaskException(object sender, UnobservedTaskExceptionEventArgs args)
        {
            ShowUnhandledException(args.Exception, "TaskScheduler.UnobservedTaskException", false);
        }

        private void Dispatcher_UnhandledException(object sender, System.Windows.Threading.DispatcherUnhandledExceptionEventArgs args)
        {
            // If we are debugging, let Visual Studio handle the exception and take us to the code that threw it.
            if (!Debugger.IsAttached)
            {
                args.Handled = true;
                ShowUnhandledException(args.Exception, "Dispatcher.UnhandledException", true);
            }
        }

        private void Current_DispatcherUnhandledException(object sender, DispatcherUnhandledExceptionEventArgs args)
        {
            // If we are debugging, let Visual Studio handle the exception and take us to the code that threw it.
            if (!Debugger.IsAttached)
            {
                args.Handled = true;
                ShowUnhandledException(args.Exception, "Application.Current.DispatcherUnhandledException", true);
            }
        }

        void ShowUnhandledException(Exception e, string unhandledExceptionType, bool promptUserForShutdown)
        {
            var messageBoxTitle = $"Unexpected Error Occurred: {unhandledExceptionType}";
            var messageBoxMessage = $"The following exception occurred:\n\n{e}";
            var messageBoxButtons = MessageBoxButton.OK;

            if (promptUserForShutdown)
            {
                messageBoxMessage += "\n\nNormally the app would die now. Should we let it die?";
                messageBoxButtons = MessageBoxButton.YesNo;
            }

            // Let the user decide if the app should die or not (if applicable).
            if (MessageBox.Show(messageBoxMessage, messageBoxTitle, messageBoxButtons) == MessageBoxResult.Yes)
            {
                Application.Current.Shutdown();
            }
        }
    }
}
```

As a best practice you should be logging these exceptions somewhere as well, such as to a file on the local machine or to a centralized logging storage mechanism, as shown in the Stack Overflow answer mentioned earlier.

Explicitly displaying the message to the user as well, as shown here, can be very helpful, as it then does not require the user to pull the error message out of some file on their machine, or for you to hunt down the users specific exception in a sea of other user exceptions (depending on what centralized logging mechanism you are using).
With this approach, the user can just do a `Ctrl`+`C` to copy the message box text, or take a screenshot of the error, and email it to you.

The message box may look a bit crude for professionally polished apps, but I think is fine for apps distributed internally in your organization.
Of course you could use some other UI mechanism to display the error to the user, and may want to change the message box message wording.
Also, I would hope that unhandled exceptions in your app are not very common, so they shouldn't see the message box too often.

I'll note though that the `TaskScheduler.UnobservedTaskException` exceptions often do not crash the app.
In fact, often times those exceptions will simply be lost and the app will silently keep on working (although maybe not properly).
For those errors, you may want to _just_ log the exception and not display it to the user.
That's your call depending on the apps target audience and what you feel is best.

I've found this technique useful for some internal tools I've made, and thought I'd share.

Happy coding!
