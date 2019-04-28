---
id: 71
title: Path Too Long for Team Foundation Database Project Build
date: 2012-02-05T23:46:00-06:00
author: deadlydog
layout: post
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
<p class="MsoNormal">
  Arrggghhhh TFS and builds!&#160; Such a love-hate relationship!&#160; So we have our TFS builds setup to both compile our C# projects as well as compile and deploy our Team Foundation (TF) Database (DB) projects.&#160; One day I started getting the following file path too long error message on our build server:
</p>

$/RQ4TeamProject/Prototypes/BuildProcessTests/RQ4.Database.sln &#8211; 1 error(s), 69 warning(s), View Log File   
C:Program Files (x86)MSBuildMicrosoftVisualStudiov10.0TeamDataMicrosoft.Data.Schema.TSqlTasks.targets (80): The "SqlSetupDeployTask" task failed unexpectedly. Microsoft.Data.Schema.Build.BuildFailedException: The specified path, file name, or both are too long. The fully qualified file name must be less than 260 characters, and the directory name must be less than 248 characters. &#8212;> System.IO.PathTooLongException: The specified path, file name, or both are too long. The fully qualified file name must be less than 260 characters, and the directory name must be less than 248 characters.&#160;&#160;&#160; at System.IO.PathHelper.Append(Char value)&#160;&#160;&#160; at System.IO.Path.NormalizePath(String path, Boolean fullCheck, Int32 maxPathLength)&#160;&#160;&#160; at System.IO.FileStream.Init(String path, FileMode mode, FileAccess access, Int32 rights, Boolean useRights, FileShare share, Int32 bufferSize, FileOptions options, SECURITY_ATTRIBUTES secAttrs, String msgPath, Boolean bFromProxy, Boolean useLongPath)&#160;&#160;&#160; at System.IO.FileStream..ctor(String path, FileMode mode, FileAccess access, FileShare share, Int32 bufferSize, FileOptions options, String msgPath, Boolean bFromProxy)&#160;&#160;&#160; at System.IO.FileStream..ctor(String path, FileMode mode, FileAccess access, FileShare share, Int32 bufferSize, FileOptions options)&#160;&#160;&#160; at System.IO.StreamReader..ctor(String path, Encoding encoding, Boolean detectEncodingFromByteOrderMarks, Int32 bufferSize)&#160;&#160;&#160; at System.IO.StreamReader..ctor(String path, Boolean detectEncodingFromByteOrderMarks)&#160;&#160;&#160; at Microsoft.Data.Schema.Sql.Build.SqlPrePostDeploymentModifier.GenerateMergedSqlCmdFiles(DeploymentContributorConfigurationSetup setup, DeploymentContributorConfigurationFile configFile)&#160;&#160;&#160; at Microsoft.Data.Schema.Sql.Build.SqlPrePostDeploymentModifier.OnEstablishDeploymentConfiguration(DeploymentContributorConfigurationSetup setup)&#160;&#160;&#160; at Microsoft.Data.Schema.Build.DeploymentContributor.EstablishDeploymentConfiguration(DeploymentContributorConfigurationSetup setup)&#160;&#160;&#160; &#8212; End of inner exception stack trace &#8212;&#160;&#160;&#160; at Microsoft.Data.Schema.Build.DeploymentContributor.EstablishDeploymentConfiguration(DeploymentContributorConfigurationSetup setup)&#160;&#160;&#160; at Microsoft.Data.Schema.Build.DeploymentProjectBuilder.VerifyConfiguration()&#160;&#160;&#160; at Microsoft.Data.Schema.Tasks.DBSetupDeployTask.BuildDeploymentProject(ErrorManager errors, ExtensionManager em)&#160;&#160;&#160; at Microsoft.Data.Schema.Tasks.DBSetupDeployTask.Execute()&#160;&#160;&#160; at Microsoft.Build.BackEnd.TaskExecutionHost.Microsoft.Build.BackEnd.ITaskExecutionHost.Execute()&#160;&#160;&#160; at Microsoft.Build.BackEnd.TaskBuilder.ExecuteInstantiatedTask(ITaskExecutionHost taskExecutionHost, TaskLoggingContext taskLoggingContext, TaskHost taskHost, ItemBucket bucket, TaskExecutionMode howToExecuteTask, Boolean& taskResult) 

Naturally I said, "Ok, our TF DB project isn&#8217;t compiling because a path is too long. Somebody must have checked in a stored procedure with a really long name".&#160; After viewing the history of the branch I was trying to build however, I didn&#8217;t see anything that stuck out.&#160; So for fun I thought I would shorten the Build Definition name&#8217;s length and build again.&#160; Viola, like most path issues with TFS this fixed the issue (this is because the build definition name is often used in the path that TFS moves/builds files to).&#160; However, we have many queries setup that match the specific Build Definition name (since it&#8217;s used in the "Integrated in Build" work item value), so shortening it wasn&#8217;t a long term solution.&#160; As an added frustration bonus, I discovered our build definition name was only 1 character too long! 

<p class="MsoNormal">
  The first thing I did was make a <a href="http://pathlengthchecker.codeplex.com">Path Length Checker</a> program so I could see how long the file paths (files and directories) really were on the build server.<span>&#160; </span>Oddly enough, the longest paths were 40 characters short of the maximum limit described by the error message.
</p>

<p class="MsoNormal">
  So I took a look at our database folder structure and saw that it really was wasting a lot of characters.&#160; This is what the path to one of our stored procedure folders looks like: "..DatabaseSchema ObjectsSchemasdboProgrammabilityStored ProceduresProcs1".&#160; I figured that I would just rename some of these folders in Visual Studio and that should be good&#8230;&#8230;..OMG never try this while connected to TFS!&#160; I got a popup warning for every single file under the directory I was renaming (thousands of them), with something along the lines of "Cannot access file X, or it is locked&#8230;..blah blah. Please press OK".&#160; So after holding down the enter key for a over an hour to get past all these prompts it finally finished.&#160; When I reviewed the changes to check in, I saw that many duplicate folders had been created, and there were miscellaneous files all over the place; some got moved, some never; what a mess.&#160; So I went ahead and reverted my changes.
</p>

So I thought, "Ok, let&#8217;s try this again, but first going offline so as not to connect to TFS".&#160; So I disabled my internet connection and opened the database solution (this is the only way that I know of to work "offline" in TFS 🙁 ).&#160; I then tried to change the high level folder "Schema Objects" to just "Schema".&#160; Nope, Visual Studio complained that the folder was locked and couldn&#8217;t be changed.&#160; I thought to myself, "TFS makes all non-checked out files read-only, and I&#8217;m offline so it can&#8217;t check them out.&#160; That must be the problem".&#160; So I opened up explorer and made all of the files and folders writable and tried again. Nope, no deal; same error message. 

So I thought, "Alright, let&#8217;s try doing a low level directory instead".&#160; It seems that VS would only let me rename a directory that didn&#8217;t contain other directories.&#160; So I renamed the "Procs1" folder to just "1".&#160; I no longer got the warning prompt for every file, but it was still pretty slow and I could watch VS process every file in the Solution Explorer window.&#160; After about 10 minutes it finally finished.&#160; So I checked in my changes and tried building again.&#160; Nope, same error message as before about the path being too long. 

So I said screw this.&#160; I opened up the TFS Source Control Explorer and renamed the folder from there.&#160; It worked just fine.&#160; I then had to open up the Database.dbproj file in a text editor and do a find and replace to replace "Schema Objects" with "Schema".&#160; This worked for refactoring the folder structure quickly, but I was still getting the "path too long" error message on the build server. Arrrrgg!

<p class="MsoNormal">
  So I went back to the build, set the verbosity to “diagnostic” and launched another build (which failed again with the path too long error).<span>&#160; </span>Looking through the error message I noticed that it did complete building the DB schema, and went on to failing on building the Pre/Post deployment scripts.<span>&#160; </span>Looking back to my original error message and reading it more carefully I noticed this line, “Microsoft.Data.Schema.Sql.Build.SqlPrePostDeploymentModifier.GenerateMergedSqlCmdFiles”.<span>&#160; </span>So now I was pretty sure the problem was in the pre and post deployment scripts.
</p>

<p class="MsoNormal">
  Now, we have a very custom process for our database scripts, and part of this process involves using SQLCMD mode to include other script files into our pre and post deployment files when they are generated; it basically makes it look like the referenced script’s contents were in the pre/post deployment script the entire time.&#160; This is necessary for us so that developers don&#8217;t have to look through pre and post deployment scripts that are tens of thousands of lines long.<span>&#160; </span>It turns out that while none of these referenced script files themselves had a path that was over the limit, somehow during the generation of the pre/post deployment scripts it was making the path even longer.<span>&#160; </span>I looked through our referenced scripts and saw a few particularly long ones.<span>&#160; </span>So I refactored them to shorten the file names, and presto the build worked!<span>&#160; </span>Hooray!
</p>

<p class="MsoNormal">
  I’m guessing that the reason the build wouldn’t give me an actual filename when it encountered the error is because SQLCMD mode was dynamically referencing those scripts at build time, so to the build it just looked like the pre and post deployment scripts were each thousands of lines long, when in fact they are only maybe 50 lines long, but they "include" other files, and those file references must be used at build time.
</p>

<p class="MsoNormal">
  So the morals of this story are:
</p>

<p class="MsoNormal">
  <p class="MsoNormal">
    1. If VS is blowing chunks when you try to rename a folder (especially when connected to TFS), don&#8217;t do it through VS.&#160; Instead modify the folder structure outside of VS and then manually edit the .csproj/.dbproj/.vbproj files to mirror the changes.
  </p>
  
  <p class="MsoNormal">
    2. Whenever you are stumped on a build error, go back and THOROUGHLY read the ENTIRE error message.
  </p>
  
  <p class="MsoNormal">
    3. Be careful when using compile-time language features to reference/include external files.
  </p>