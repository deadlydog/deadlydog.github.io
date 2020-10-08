FROM mcr.microsoft.com/vscode/devcontainers/ruby:2

# ENV Variables required by Jekyll.
ENV LANG=en_US.UTF-8 \
	LANGUAGE=en_US:en \
	TZ=America/Chicago \
	LC_ALL=en_US.UTF-8 \
	LANG=en_US.UTF-8 \
	LANGUAGE=en_US

# Install jekyll.
RUN gem install bundler jekyll

# [Option] Install Node.js
ARG NODE_VERSION="lts/*"
RUN su vscode -c "source /usr/local/share/nvm/nvm.sh && nvm install ${NODE_VERSION} 2>&1"

# [Optional] Uncomment this section to install additional OS packages.
# RUN apt-get update \
# 	&& export DEBIAN_FRONTEND=noninteractive \
# 	&& apt-get install -y --no-install-recommends \
# 		# Install PowerShell, so we can run custom scripts.
# 		#	The PowerShell package could not be found, and using the steps provided by MS below did not work either, so not installing it for now:
# 		#	https://docs.microsoft.com/en-us/powershell/scripting/install/installing-powershell-core-on-linux?view=powershell-7#debian-10
# 		powershell \
# 		# Install Python for generating the syntax highlighting scss file if needed.
# 		python

# [Optional] Uncomment this line to install additional gems.
# RUN gem install <your-gem-names-here>

# [Optional] Uncomment this line to install global node packages.
# RUN su vscode -c "source /usr/local/share/nvm/nvm.sh && npm install -g <your-package-here>" 2>&1
