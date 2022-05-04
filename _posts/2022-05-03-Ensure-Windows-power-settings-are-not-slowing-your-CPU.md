---
title: "Ensure Windows power settings are not slowing your CPU"
permalink: /Ensure-Windows-power-settings-are-not-slowing-your-CPU/
#date: 2099-01-15T00:00:00-06:00
#last_modified_at: 2099-01-22
comments_locked: false
categories:
  - Windows
  - Performance
tags:
  - Windows
  - Performance
---

My computer was sometimes very slow and unresponsive.
It turned out the Windows power settings were throttling my processor speed.

This post will show you how to check if your CPU is being throttled by the Windows power settings, and how to correct it.

## Check if your CPU is throttled

The way in which the Windows power settings may be throttling your CPU is by setting the `CPU Maximum Frequency` to be less than 100%.

To quickly get a sense for what your CPU Maximum Frequency is set to, open the `Resource Monitor` app (by hitting the Windows Key and searching for `Resource Monitor`) and then navigate to the `CPU` tab.

In the Processes header near the top, you will see `CPU Usage` and `Maximum Frequency` percentage values.
These values will likely fluctuate up and down as you watch them.
On the right side of the window you will also see a `CPU - Total` graph showing the `CPU Usage` (green line), and `Maximum Frequency` (blue line) over time.

![Resource Monitor screenshot](/assets/Posts/2022-05-03-Ensure-Windows-power-settings-are-not-slowing-your-CPU/ResourceMonitorCpuFrequencyScreenshot.png)

What you are looking for is to see if the Maximum Frequency never goes above a given value (e.g. 30%), and also if the green CPU Usage line is often hitting the blue Maximum Frequency line.
This should be especially apparent if you watch it while doing something CPU intensive, like playing a game or running multiple applications at once.

For example, I noticed that my PC would often become very sluggish and slow to respond, even though Task Manager showed my CPU was not busy and the memory usage was low.
In the Resource Manager though, I saw the `CPU Maximum Frequency` never went above 30%, and that the green `CPU Usage` line was continually bumping up against the blue Maximum Frequency line.
This meant that even though my CPU wasn't particularly busy, it was being throttled to not be able to run at more than 30% of it's capacity.
Windows was throttling it, preventing it from running at it's full speed, and making my PC slow to respond.

See [this Super User question](https://superuser.com/questions/256921/what-does-the-maximum-frequency-number-mean-in-the-windows-resource-monitor) for a bit more info on the CPU Maximum Frequency.
In short, Windows may intentionally throttle your CPU to save power.
If you are not using a power saver mode though, then you may want to manually modify your current power plan's settings to have the CPU run at full power.

## Modify the CPU Max Frequency

Following [this guide](https://gigabytekingdom.com/what-is-cpu-maximum-frequency/) I was able to increase my CPU Maximum Frequency to 100%, and I'll present the steps I took here.

### Enable viewing the Maximum Processor Frequency value

In order for the `Maximum processor frequency` value to be shown in the Windows Power Options Advanced Settings, we need to first enable it.
This only needs to be done once:

1. Open a command prompt as an administrator.
   1. Hit the Windows Key and type `Command prompt`.
   1. Right click on the `Command Prompt` app and choose `Run as administrator`.
1. In the command prompt window, type the following and hit enter: `powercfg -attributes SUB_PROCESSOR 75b0ae3f-bce0-45a7-8c89-c9611c25e100 -ATTRIB_HIDE`
1. Restart your computer.

![Enable Maximum Processor Frequency setting in Power Options screenshot](/assets/Posts/2022-05-03-Ensure-Windows-power-settings-are-not-slowing-your-CPU/EnableMaxProcessorFrequencyScreenshot.png)

### View the Maximum Processor Frequency value

To view the `Maximum processor frequency` value in the Power Options:

1. Open the Windows Power and Sleep settings.
   1. Hit the Windows Key and type "Power Settings" and choose the `Power & sleep settings` app.
1. Under `Related settings` click the `Additional power settings` link.
1. In the Power Options window that opened, find which power plan you are using and click the `Change plan settings` link.
1. In the Edit Plan Settings window that opened, click the `Change advanced power settings` link.
1. In the Advanced Settings window that opened, expand the `Process power management` node and then the `Maximum processor frequency` node.
1. Here you will see the current values for the `Maximum processor frequency` when running on battery and when plugged in.

![Navigate to advanced power settings gif](/assets/Posts/2022-05-03-Ensure-Windows-power-settings-are-not-slowing-your-CPU/NavigateToAdvancedPowerSettings.gif)

Initially when I navigated here on my PC both values were set to 0, but my `CPU Maximum Frequency` in the Resource Manager was still capped at 30% for some reason.

Note that some power plans, such as power saver, may intentionally lower the `Maximum processor frequency` value in order to save power.
In my case I only had one power plan, which was `Balanced`, so I wanted it to be able to leverage the full normal processor speed.
Also, I wanted it to run at full speed whether on battery or plugged in, so I wanted to change both values.

If you want to change these values, Windows expects them to be in MHz, not percent, so you'll need to know your CPU's frequency in MHz.

### Find your CPUs frequency

To find your CPU's frequency:

1. Open `System Information` by hitting the Windows Key and searching for `System Information`.
1. In the System Information window's System Summary, locate the `Processor` item and look at the GHz or MHz value.

![System Information screenshot](/assets/Posts/2022-05-03-Ensure-Windows-power-settings-are-not-slowing-your-CPU/SystemInformationCpuFrequencyScreenshot.png)

1 GHz = 1000 MHz, so if it only lists your processor speed in GHz, you'll need to multiply it by 1000 to get the MHz value.
e.g. 2.3 GHz = 2300 MHz.

### Update the Maximum Processor Frequency value

Now that you know your processor frequency speed in MHz, you can update the values in the Advanced Power Settings window.

1. Go back to the Power Options Advanced Settings window.
1. Find your way back to the `Maximum processor frequency` node.
1. Update the `On battery` and `Plugged in` values to be equal to your CPU's frequency in MHz.
   1. Be sure not to use a value greater than your CPU's frequency, as that may overclock your CPU which can cause overheating and potentially damage your CPU hardware.
1. Hit OK to save the settings.
1. Restart your computer for the change to take effect.

![Updated Advanced Power Settings screenshot](/assets/Posts/2022-05-03-Ensure-Windows-power-settings-are-not-slowing-your-CPU/UpdatedCpuMaximumFrequencySettingScreenshot.png)

### Verifying the change worked

Head back into the Resource Monitor and verify that the `CPU Maximum Frequency` is now going higher than it was before, reaching up to 99 or 100%.

## Conclusion

We've seen how to quickly check your CPU's maximum frequency to see if it's throttling your CPU, as well as how to find your CPU's frequency speed.
We also showed how to view and edit the Windows power settings to ensure it's not throttling your processor speed, so your PC runs at full speed.

Happy computing!
