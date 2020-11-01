param([string] $projectDirectoryPath)

Set-Location -Path $projectDirectoryPath
Write-Host "Starting blog at http://127.0.0.1:4000 by running 'bundle exec jekyll serve --draft'. It may take a minute to start, and you may need to hit enter in the terminal as sometimes VS Code waits for input for some reason..."
& bundle exec jekyll serve --draft
