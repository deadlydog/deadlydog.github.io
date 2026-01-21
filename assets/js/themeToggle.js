function CreateAndInjectLightDarkModeToggleButtonAndSetCurrentTheme() {
	let storageKey = 'theme-preference';
	let darkStylesheet = document.getElementById('theme-dark');
	let systemQuery = globalThis.matchMedia('(prefers-color-scheme: dark)');
	let storedThemeValue = null;

	try
	{
		storedThemeValue = localStorage.getItem(storageKey);
	}
	catch (error)
	{
		console.warn('Could not access localStorage to retrieve theme preference:', error);

		// If we couldn't access localStorage, assume no stored value.
		storedThemeValue = null;
	}

	let mode = storedThemeValue === 'dark' || storedThemeValue === 'light' ? storedThemeValue : 'system';
	let toggleButton = null;

	function applyStylesheet(targetMode)
	{
		if (!darkStylesheet)
		{
			return;
		}

		if (targetMode === 'system')
		{
			darkStylesheet.media = '(prefers-color-scheme: dark)';
			return;
		}

		darkStylesheet.media = targetMode === 'dark' ? 'all' : 'not all';
	}

	function getCurrentTheme()
	{
		return mode === 'system' ? (systemQuery.matches ? 'dark' : 'light') : mode;
	}

	function persistMode(targetMode)
	{
		try
		{
			if (targetMode === 'system')
			{
				localStorage.removeItem(storageKey);
			}
			else
			{
				localStorage.setItem(storageKey, targetMode);
			}
		}
		catch (error)
		{
			console.warn('Could not persist theme preference to localStorage:', error);
		}
	}

	function updateToggle()
	{
		if (!toggleButton)
		{
			return;
		}

		let theme = getCurrentTheme();
		let isDark = theme === 'dark';
		let icon = isDark ? 'fa-sun' : 'fa-moon';

		toggleButton.innerHTML = '<i class="fas ' + icon + ' theme-toggle__icon" aria-hidden="true"></i>'
		toggleButton.setAttribute('aria-pressed', isDark ? 'true' : 'false');
		toggleButton.setAttribute('aria-label', 'Toggle between light and dark mode');
	}

	function applyBaseColors(theme)
	{
		if (theme === 'dark')
		{
			document.documentElement.style.backgroundColor = '#0f172a';
			document.documentElement.style.color = '#e5e7eb';
			document.documentElement.style.colorScheme = 'dark';
		}
		else
		{
			document.documentElement.style.backgroundColor = '#ffffff';
			document.documentElement.style.color = '#111827';
			document.documentElement.style.colorScheme = 'light';
		}
	}

	function setMode(targetMode, persist)
	{
		mode = targetMode;
		applyStylesheet(targetMode);
		let theme = getCurrentTheme();
		document.documentElement.dataset.theme = theme;
		applyBaseColors(theme);

		if (persist)
		{
			persistMode(targetMode);
		}

		updateToggle();
	}

	function buildToggle()
	{
		let nav = document.getElementById('site-nav');
		if (!nav)
		{
			return null;
		}

		let button = document.createElement('button');
		button.type = 'button';
		button.className = 'theme-toggle';
		button.title = 'Toggle light and dark mode. Alt+Click to follow the system setting again.';

		button.addEventListener('click', function (event)
		{
			if (event.altKey)
			{
				setMode('system', true);
				return;
			}

			// Animate the theme toggle button since it was clicked to change state.
			button.classList.add('theme-toggle--spinning');

			let nextMode = getCurrentTheme() === 'dark' ? 'light' : 'dark';
			setMode(nextMode, true);
		});

		// Remove the spinning class after the animation ends.
		button.addEventListener('animationend', function ()
		{
			button.classList.remove('theme-toggle--spinning');
		});

		let insertBeforeNode = nav.querySelector('.search__toggle') || nav.querySelector('.greedy-nav__toggle');
		if (insertBeforeNode?.parentNode === nav)
		{
			insertBeforeNode.before(button);
		}
		else
		{
			nav.appendChild(button);
		}

		return button;
	}

	// Initialize the current theme and apply styles.
	applyStylesheet(mode);
	let initialTheme = getCurrentTheme();
	document.documentElement.dataset.theme = initialTheme;
	applyBaseColors(initialTheme);

	toggleButton = buildToggle();
	updateToggle();

	// Listen for system theme changes.
	systemQuery.addEventListener('change', function (event)
	{
		if (mode === 'system')
		{
			document.documentElement.dataset.theme = event.matches ? 'dark' : 'light';
			updateToggle();
		}
	});
}
CreateAndInjectLightDarkModeToggleButtonAndSetCurrentTheme();
