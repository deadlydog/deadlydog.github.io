<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Daniel Schroeder's Website - Games I've Made</title>
<?php include "_HTMLHead.php"; // Includes the Head information ?>
</head>

<body>
<?php $SelectedTab = "Games"; ?>
<?php include '_CommonSiteElements.php'; ?>

<div id="Content">

<h1>Games I've Made</h1>

<ul>
<li><a href="#PitchGames">Daniel Schroeder's Pitch Games</a></li>
<li><a href="#OceanExplorer">Ocean Explorer</a></li>
<li><a href="#Blasteroid">Blasteroid</a></li>
<li><a href="#Connex">Connex</a></li>
</ul>

<a name="PitchGames"></a>
<h2>Daniel Schroeder's Pitch Games (2008)</h2>
<p>Dan's Pitch Games is a series of mini-games where you control the game using the pitch of your voice. I created this game in 2008 while taking the CS827 graduate audio class. 
The mini-games include single player pong, a pitch matching game where you must try and match the pitch of your voice to the pitch shown on the screen, and a bow 
and arrow game where you must hit a target with an arrow where the pitch of your voice controls the ark of the arrow, and the amplitude of your voice controls 
how much power the arrow is fired with. This game was programmed in C# and simply uses GDI+ for the graphics, so DirectX does not need to be installed to play it. 
It uses PD (Pure Data) to get the player's voice input from a microphone and to detect their pitch, and then transfers this information to the C# code using 
OSC (Open Sound Control).</p>

<p>Screenshot and downloads for Daniel Schroeder's Pitch Games to be posted soon</p>

<a name="OceanExplorer"></a>
<h2>Ocean Explorer (2007)</h2>
<p>I developed Ocean Explorer for my CS809 university class in 2007 using C++ and the Ogre3D graphics engine. Basically you control a submarine and swim around a 3D ocean completing objectives 
while avoiding the shark.  The fish flock in groups and the shark uses a simple AI where if the shark is hungry it will hunt fish, and if it is full it will hunt your submarine. 
There are 5 levels, but I still have to add music and sounds to the game.  It is still fully playable though, so download the 
executable if you like and check it out.  <b>To play the game, simply go into the "bin\release" folder and run "Assignment.exe"</b>.  There are also instructions for how to run 
the game from the source code in Visual Studio 2005 in the "How To Run Game.txt" file.</p>
<center><img src="Images/Ocean Explorer.jpg" /></center>
<p>Download the <a href="Games/Ocean Explorer.zip">Executable</a> or the <a href="Games/Ocean Explorer Source Code.zip">Source Code</a></p>

<a name="Blasteroid"></a>
<h2>Blasteroid (2005)</h2>
<p>Blasteroid was the second game I created. I started it in 2004, and continued to work on it into 2005. This game is a Space Invaders type of game. The game currently has 3 levels, 
but the levels are loaded from script files and more levels could easily be added. Also, the enemies use a simple AI system that I developed to determine when to 
attack your ship and when to flee, although it may be tough to see this as the screen often quickly fills up with enemies. 
Download it and check it out. If you don't want to 'install' the game, you can just download the Executable file, although
the fonts may not display correctly.  Most of the graphics and sound effects were made by me, but the music and some of the graphics are from 3rd party sources. 
You will need at least DirectX 8.1 to play this one. You can also download the C++ source code to check out my code if you want.</p>
<center><img src="Images/Blasteroid.jpg" /></center>
<p>Download the <a href="Games/Blasteroid Installer.exe">Installer</a>, the <a href="Games/Blasteroid.zip">Executable</a>, or the <a href="Games/Blasteroid Source Code.zip">Source Code</a></p>

<a name="Connex"></a>
<h2>Connex (2003)</h2>
<p>Connex was the very first game I ever created, back in 2003. It's pretty much a tetris clone with some minor additions, such as an Extreme mode where every 5 - 10 seconds the block
you are placing suddenly turns into a different type of block. I made it using C++ and DirectX 7, 
so you will need to have at least Direct X 7 installed to play it. I never used any external pictures of any kind, just rectangles 
and color so it looks sort of lame, but hey it works. If you want to see the code I wrote to make the game, download the source code. I did 
not code the Game Engines myself though, I used the ones from Andre LaMothe's "Tricks of the Windows Game Programming Gurus" book. 
Also, all of the sounds and music for this game were from 3rd party sources as well.</p>
<center><img src="Images/Connex.jpg" /></center>
<p>Download the <a href="Games/Connex.zip">Executable</a> or the <a href="Games/Connex Source Code.zip">Source Code</a></p>

</div>

<?php include '_Footer.php'; ?>
</body>
</html>
