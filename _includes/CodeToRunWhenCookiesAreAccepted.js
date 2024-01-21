// Call the functions that should not be ran until the user has accepted cookies.

// If the user is not viewing a post, the Post Ad function will not be defined, so only call it if it is defined.
if (typeof(RunGoogleAdsensePostAdvertisementJavaScript) === typeof(Function))
{
	console.log("RunGoogleAdsensePostAdvertisementJavaScript is defined.");
	RunGoogleAdsensePostAdvertisementJavaScript()
}

RunGoogleAdsenseFooterAdvertisementJavaScript()
RunGoogleAnalyticsJavaScript()
RunMsClarityJavaScript()
