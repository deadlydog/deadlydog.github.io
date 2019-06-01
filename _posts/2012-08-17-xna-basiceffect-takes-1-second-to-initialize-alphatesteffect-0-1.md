---
id: 93
title: XNA BasicEffect takes ~1 second to initialize, AlphaTestEffect ~0.1
date: 2012-08-17T13:47:00-06:00
guid: https://deadlydog.wordpress.com/?p=93
permalink: /xna-basiceffect-takes-1-second-to-initialize-alphatesteffect-0-1/
jabber_published:
  - "1353354495"
categories:
  - XNA
tags:
  - AlphaEffect
  - BasicEffect
  - Performance
  - XNA
---

This is a general message for all XNA developers that in XNA 4 it takes typically 1 - 1.5 seconds to create a new BasicEffect object, while only about 100 milliseconds to create an AlphaTestEffect object. So if your load times are horrible and you use lots of BasicEffect objects, that may be why. I've posted this on the [XNA Community Forums](http://xboxforums.create.msdn.com/forums/p/107905/635257.aspx#635257) as well; I thought it was worth sharing with other XNA devs ;-)
