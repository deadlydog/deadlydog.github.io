# This script will find all open staticman comment pull requests in the given repository and close them after confirmation.`
param
(
	[Parameter(Mandatory = $true, HelpMessage = 'A GitHub Personal Access Token that has permissions to read and close pull requests.')]
	[string] $GitHubPat,

	[Parameter(Mandatory = $false, HelpMessage = 'The username that contains the repository to inspect for open staticman comment pull requests.')]
	[string] $GitHubUsername = 'deadlydog',

	[Parameter(Mandatory = $false, HelpMessage = 'The repository to look for staticman comment pull requests in.')]
	[string] $GitHubRepository = 'deadlydog.github.io'
)

Process
{
	[hashtable] $requestHeaders = Get-GitHubRequestHeaders -gitHubPat $GitHubPat

	$openPullRequests = Get-OpenStaticmanPullRequests -gitHubUsername $GitHubUsername -gitHubRepository $GitHubRepository -requestHeaders $requestHeaders

	if ($openPullRequests.Count -le 0)
	{
		Write-Information "There are no open staticman comment pull requests on the '$GitHubUsername\$GitHubRepository' repository, so exiting."
		break
	}

	[bool] $userWantsToCloseOpenPullRequests = Prompt-UserIfTheyWantToCloseOpenPullRequests -openPullRequests $openPullRequests

	if (!$userWantsToCloseOpenPullRequests)
	{
		Write-Warning "User answered with '$answer', so not closing any pull requests and just exiting."
		break
	}

	Close-OpenGitHubPullRequests -openPullRequests $openPullRequests -gitHubUsername $GitHubUsername -gitHubRepository $GitHubRepository -requestHeaders $requestHeaders
}

Begin
{
	$InformationPreference = 'Continue'

	[string] $StaticmanCommentPrefix = "Dear human,`n`nHere's a new entry for your approval. :tada:`n`nMerge the pull request to accept it, or close it to send it away.`n`n:heart: Your friend [Staticman](https://staticman.net) :muscle:`n`n---`n"

	function Get-GitHubRequestHeaders([string] $gitHubPat)
	{
		[hashtable] $requestHeaders = @{
			'Content-Type' = 'application/json'
			Accept = 'application/json'
			Authorization = 'Basic ' + [Convert]::ToBase64String([Text.Encoding]::ASCII.GetBytes(":$($gitHubPat)"))
		}
		return $requestHeaders
	}

	function Get-OpenStaticmanPullRequests([string] $gitHubUsername, [string] $gitHubRepository, [hashtable] $requestHeaders)
	{
		[string] $getPullRequestsUrl = "https://api.github.com/repos/$gitHubUsername/$gitHubRepository/pulls?state=open&page=1&per_page=100"

		$openPullRequests = Invoke-RestMethod -Method 'Get' -Uri $getPullRequestsUrl -Headers $requestHeaders

		$staticmanPullRequests = $openPullRequests | Where-Object { $_.body.Contains($StaticmanCommentPrefix) }

		[int] $openCommentsPullRequestsCount = $staticmanPullRequests.Count
		Write-Information "There are '$openCommentsPullRequestsCount' open Staticman comment pull requests."

		return $staticmanPullRequests
	}

	function Prompt-UserIfTheyWantToCloseOpenPullRequests($openPullRequests)
	{
		$pullRequestConfirmationDetails = $openPullRequests |
			Select-Object -Property Number, @{ name = 'Name'; expression = { $_.title.Replace('New comment by ', '') }}, @{ name = 'Comment'; expression = { $_.body.Replace($StaticmanCommentPrefix, '') }}

		$pullRequestConfirmationDetails | Out-GridView -Wait

		[string] $answer = Read-Host -Prompt 'Do you want to close all of the open Staticman comment pull requests (y/n)'

		[bool] $userWantsToDeletePrs = $answer.StartsWith('y', [System.StringComparison]::OrdinalIgnoreCase)
		return $userWantsToDeletePrs
	}

	function Close-OpenGitHubPullRequests($openPullRequests, [string] $gitHubUsername, [string] $gitHubRepository, [hashtable] $requestHeaders)
	{
		$openPullRequests | ForEach-Object {
			[string] $pullRequestUrl = $_.Url
			[string] $gitBranch = $_.head.ref
			Write-Information "Closing pull request '$pullRequestUrl' and deleting it's branch '$gitBranch'."
			Close-PullRequest -pullRequestUrl $pullRequestUrl -requestHeaders $requestHeaders
			Delete-GitHubBranch -gitBranch $gitBranch -gitHubUsername $GitHubUsername -gitHubRepository $GitHubRepository -requestHeaders $requestHeaders
		}
	}

	function Close-PullRequest([string] $pullRequestUrl, [hashtable] $requestHeaders)
	{
		[string] $closePullRequestBody = @{
			state = 'closed'
		} | ConvertTo-Json -Depth 99

		Invoke-RestMethod -Method 'Patch' -Uri $pullRequestUrl -Headers $requestHeaders -Body $closePullRequestBody > $null
	}

	function Delete-GitHubBranch([string] $gitBranch, [string] $gitHubUsername, [string] $gitHubRepository)
	{
		[string] $branchUrl = "https://api.github.com/repos/$gitHubUsername/$gitHubRepository/git/refs/heads/$gitBranch"
		Invoke-RestMethod -Method 'Delete' -Uri $branchUrl -Headers $requestHeaders
	}

	# Display the time that this script started running.
	[datetime] $startTime = Get-Date
	Write-Verbose "Starting script at '$startTime'." -Verbose
}

End
{
	# Display the time that this script finished running, and how long it took to run.
	[datetime] $finishTime = Get-Date
	[timespan] $elapsedTime = $finishTime - $startTime
	Write-Verbose "Finished script at '$finishTime'. Took '$elapsedTime' to run." -Verbose
}
