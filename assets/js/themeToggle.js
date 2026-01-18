(function () {
	var storageKey = 'theme-preference';
	var darkStylesheet = document.getElementById('theme-dark');
	var systemQuery = window.matchMedia('(prefers-color-scheme: dark)');
	var stored = null;

	try
	{
		stored = localStorage.getItem(storageKey);
	}
	catch (error)
	{
		stored = null;
	}

	var mode = stored === 'dark' || stored === 'light' ? stored : 'system';
	var toggleButton = null;

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

	function currentTheme()
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
			return;
		}
	}

	function updateToggle()
	{
		if (!toggleButton)
		{
			return;
		}

		var theme = currentTheme();
		var isDark = theme === 'dark';
		var icon = isDark ? 'fa-sun' : 'fa-moon';
		var iconColor = isDark ? '#f7e025' : '#1200e6';

		toggleButton.innerHTML = '<i class="fas ' + icon + ' theme-toggle__icon" aria-hidden="true" style="color:' + iconColor + ';"></i>'
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
		var theme = currentTheme();
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
		var nav = document.getElementById('site-nav');
		if (!nav)
		{
			return null;
		}

		var button = document.createElement('button');
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

			var nextMode = currentTheme() === 'dark' ? 'light' : 'dark';
			setMode(nextMode, true);
		});

		var insertBeforeNode = nav.querySelector('.search__toggle') || nav.querySelector('.greedy-nav__toggle');
		if (insertBeforeNode && insertBeforeNode.parentNode === nav)
		{
			nav.insertBefore(button, insertBeforeNode);
		}
		else
		{
			nav.appendChild(button);
		}

		return button;
	}

	// Initialize the current theme and apply styles.
	applyStylesheet(mode);
	var initialTheme = currentTheme();
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
})();
