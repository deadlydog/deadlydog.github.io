---
id: 71
title: Path Too Long for Team Foundation Database Project Build
date: 2012-02-05T23:46:00-06:00
guid: https://deadlydog.wordpress.com/?p=71
permalink: /path-too-long-for-team-foundation-database-project-build/
jabber_published:
  - "1353109456"
categories:
  - Database
  - TFS
  - Visual Studio
tags:
  - error
  - path too long
  - SQLCMD
  - Team Foundation Database
  - TFS
  - visual studio
---

Arrggghhhh TFS and builds! Such a love-hate relationship! So we have our TFS builds setup to both compile our C# projects as well as compile and deploy our Team Foundation (TF) Database (DB) projects. One day I started getting the following file path too long error message on our build server:

```text
$/RQ4TeamProject/Prototypes/BuildProcessTests/RQ4.Database.sln - 1 error(s), 69 warning(s), View Log File
C:Program Files (x86)MSBuildMicrosoftVisualStudiov10.0TeamDataMicrosoft.Data.Schema.TSqlTasks.targets (80): The "SqlSetupDeployTask" task failed unexpectedly. Microsoft.Data.Schema.Build.BuildFailedException: The specified path, file name, or both are too long. The fully qualified file name must be less than 260 characters, and the directory name must be less than 248 characters. &#8212;> System.IO.PathTooLongException: The specified path, file name, or both are too long. The fully qualified file name must be less than 260 characters, and the directory name must be less than 248 characters. at System.IO.PathHelper.Append(Char value) at System.IO.Path.NormalizePath(String path, Boolean fullCheck, Int32 maxPathLength) at System.IO.FileStream.Init(String path, FileMode mode, FileAccess access, Int32 rights, Boolean useRights, FileShare share, Int32 bufferSize, FileOptions options, SECURITY_ATTRIBUTES secAttrs, String msgPath, Boolean bFromProxy, Boolean useLongPath) at System.IO.FileStream..ctor(String path, FileMode mode, FileAccess access, FileShare share, Int32 bufferSize, FileOptions options, String msgPath, Boolean bFromProxy) at System.IO.FileStream..ctor(String path, FileMode mode, FileAccess access, FileShare share, Int32 bufferSize, FileOptions options) at System.IO.StreamReader..ctor(String path, Encoding encoding, Boolean detectEncodingFromByteOrderMarks, Int32 bufferSize) at System.IO.StreamReader..ctor(String path, Boolean detectEncodingFromByteOrderMarks) at Microsoft.Data.Schema.Sql.Build.SqlPrePostDeploymentModifier.GenerateMergedSqlCmdFiles(DeploymentContributorConfigurationSetup setup, DeploymentContributorConfigurationFile configFile) at Microsoft.Data.Schema.Sql.Build.SqlPrePostDeploymentModifier.OnEstablishDeploymentConfiguration(DeploymentContributorConfigurationSetup setup) at Microsoft.Data.Schema.Build.DeploymentContributor.EstablishDeploymentConfiguration(DeploymentContributorConfigurationSetup setup) &#8212; End of inner exception stack trace &#8212; at Microsoft.Data.Schema.Build.DeploymentContributor.EstablishDeploymentConfiguration(DeploymentContributorConfigurationSetup setup) at Microsoft.Data.Schema.Build.DeploymentProjectBuilder.VerifyConfiguration() at Microsoft.Data.Schema.Tasks.DBSetupDeployTask.BuildDeploymentProject(ErrorManager errors, ExtensionManager em) at Microsoft.Data.Schema.Tasks.DBSetupDeployTask.Execute() at Microsoft.Build.BackEnd.TaskExecutionHost.Microsoft.Build.BackEnd.ITaskExecutionHost.Execute() at Microsoft.Build.BackEnd.TaskBuilder.ExecuteInstantiatedTask(ITaskExecutionHost taskExecutionHost, TaskLoggingContext taskLoggingContext, TaskHost taskHost, ItemBucket bucket, TaskExecutionMode howToExecuteTask, Boolean& taskResult)
```

Naturally I said, "Ok, our TF DB project isn&#8217;t compiling because a path is too long. Somebody must have checked in a stored procedure with a really long name". After viewing the history of the branch I was trying to build however, I didn&#8217;t see anything that stuck out. So for fun I thought I would shorten the Build Definition name&#8217;s length and build again. Viola, like most path issues with TFS this fixed the issue (this is because the build definition name is often used in the path that TFS moves/builds files to). However, we have many queries setup that match the specific Build Definition name (since it&#8217;s used in the "Integrated in Build" work item value), so shortening it wasn&#8217;t a long term solution. As an added frustration bonus, I discovered our build definition name was only 1 character too long!

The first thing I did was make a [Path Length Checker](http://pathlengthchecker.codeplex.com) program so I could see how long the file paths (files and directories) really were on the build server. Oddly enough, the longest paths were 40 characters short of the maximum limit described by the error message.

So I took a look at our database folder structure and saw that it really was wasting a lot of characters. This is what the path to one of our stored procedure folders looks like: "..DatabaseSchema ObjectsSchemasdboProgrammabilityStored ProceduresProcs1". I figured that I would just rename some of these folders in Visual Studio and that should be good........OMG never try this while connected to TFS! I got a popup warning for every single file under the directory I was renaming (thousands of them), with something along the lines of "Cannot access file X, or it is locked.....blah blah. Please press OK". So after holding down the enter key for a over an hour to get past all these prompts it finally finished. When I reviewed the changes to check in, I saw that many duplicate folders had been created, and there were miscellaneous files all over the place; some got moved, some never; what a mess. So I went ahead and reverted my changes.

So I thought, "Ok, let&#8217;s try this again, but first going offline so as not to connect to TFS". So I disabled my internet connection and opened the database solution (this is the only way that I know of to work "offline" in TFS 🙁 0; I then tried to change the high level folder "Schema Objects" to just "Schema0; Nope, Visual Studio complained that the folder was locked and couldn&#8217;t be change0; I thought to myself, "TFS makes all non-checked out files read-only, and I&#8217;m offline so it can&#8217;t check them ou0; That must be the problem0; So I opened up explorer and made all of the files and folders writable and tried again. Nope, no deal; same error message.

So I thought, "Alright, let&#8217;s try doing a low level directory instead". It seems that VS would only let me rename a directory that didn&#8217;t contain other directories. So I renamed the "Procs1" folder to just "1". I no longer got the warning prompt for every file, but it was still pretty slow and I could watch VS process every file in the Solution Explorer window. After about 10 minutes it finally finished. So I checked in my changes and tried building again. Nope, same error message as before about the path being too long.

So I said screw this. I opened up the TFS Source Control Explorer and renamed the folder from there. It worked just fine. I then had to open up the Database.dbproj file in a text editor and do a find and replace to replace "Schema Objects" with "Schema". This worked for refactoring the folder structure quickly, but I was still getting the "path too long" error message on the build server. Arrrrgg!

So I went back to the build, set the verbosity to “diagnostic” and launched another build (which failed again with the path too long error). Looking through the error message I noticed that it did complete building the DB schema, and went on to failing on building the Pre/Post deployment scripts. Looking back to my original error message and reading it more carefully I noticed this line, “Microsoft.Data.Schema.Sql.Build.SqlPrePostDeploymentModifier.GenerateMergedSqlCmdFiles�n> So now I was pretty sure the problem was in the pre and post deployment scripts.

Now, we have a very custom process for our database scripts, and part of this process involves using SQLCMD mode to include other script files into our pre and post deployment files when they are generated; it basically makes it look like the referenced script’s contents were in the pre/post deployment script the entire tim0; This is necessary for us so that developers don&#8217;t have to look through pre and post deployment scripts that are tens of thousands of lines long. It turns out that while none of these referenced script files themselves had a path that was over the limit, somehow during the generation of the pre/post deployment scripts it was making the path even longer. I looked through our referenced scripts and saw a few particularly long ones. So I refactored them to shorten the file names, and presto the build worked! Hooray!

I’m guessing that the reason the build wouldn’t give me an actual filename when it encountered the error is because SQLCMD mode was dynamically referencing those scripts at build time, so to the build it just looked like the pre and post deployment scripts were each thousands of lines long, when in fact they are only maybe 50 lines long, but they "include" other files, and those file references must be used at build time.

So the morals of this story are:

1. If VS is blowing chunks when you try to rename a folder (especially when connected to TFS), don&#8217;t do it through VS. Instead modify the folder structure outside of VS and then manually edit the .csproj/.dbproj/.vbproj files to mirror the changes.
1. Whenever you are stumped on a build error, go back and THOROUGHLY read the ENTIRE error message.
1. Be careful when using compile-time language features to reference/include external files.
