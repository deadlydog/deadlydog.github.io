param([string] $projectDirectoryPath)

Set-Location -Path $projectDirectoryPath
Write-Host "Starting blog at http://127.0.0.1:4000 by running 'bundle exec jekyll serve --draft', which will take a minute to complete. You may need to hit enter in the terminal as sometimes VS Code waits for input before running the command for some reason."
& bundle exec jekyll serve --draft
