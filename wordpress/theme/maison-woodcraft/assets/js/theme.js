/**
 * Maison Woodcraft — front-end behaviour.
 *
 * Replaces the small amount of JavaScript the original React site used:
 *   - fixed header that becomes solid on scroll (Header.tsx),
 *   - mobile hamburger menu (Header.tsx),
 *   - FAQ accordion (Kitchens.tsx / Wardrobes.tsx),
 *   - project category filter + "View Project" modal (Projects.tsx),
 *   - gallery lightbox,
 *   - AJAX quote/contact form submission (the original forms only pretended).
 *
 * No dependencies, no build step.
 */
(function () {
	'use strict';

	var config = window.mwTheme || {};

	/* ---------------------------------------------------------------------
	 * Header: solid on scroll + mobile menu
	 * ------------------------------------------------------------------ */
	function initHeader() {
		var header = document.querySelector('[data-mw-header]');
		if (!header) {
			return;
		}

		var burger = header.querySelector('[data-mw-burger]');
		var panel = header.querySelector('[data-mw-mobile-menu]');
		var overHero = document.body.classList.contains('mw-header-over-hero');

		function syncSolid() {
			var scrolled = window.scrollY > 24;
			var menuOpen = panel && panel.classList.contains('is-open');
			var solid = scrolled || !overHero || menuOpen;
			header.classList.toggle('is-solid', solid);
			document.body.classList.toggle('mw-header-solid', solid);
		}

		syncSolid();
		window.addEventListener('scroll', syncSolid, { passive: true });

		if (burger && panel) {
			burger.addEventListener('click', function () {
				var open = panel.classList.toggle('is-open');
				burger.classList.toggle('is-open', open);
				burger.setAttribute('aria-expanded', open ? 'true' : 'false');
				syncSolid();
			});

			panel.addEventListener('click', function (event) {
				if (event.target.closest('a')) {
					panel.classList.remove('is-open');
					burger.classList.remove('is-open');
					burger.setAttribute('aria-expanded', 'false');
					syncSolid();
				}
			});
		}
	}

	/* ---------------------------------------------------------------------
	 * FAQ accordion
	 * ------------------------------------------------------------------ */
	function initFaq() {
		document.querySelectorAll('[data-mw-faq]').forEach(function (faq) {
			faq.querySelectorAll('.mw-faq__question').forEach(function (button) {
				button.addEventListener('click', function () {
					var item = button.closest('.mw-faq__item');
					var answer = item ? item.querySelector('.mw-faq__answer') : null;
					var icon = button.querySelector('.mw-faq__icon');
					if (!answer) {
						return;
					}

					var isOpen = button.getAttribute('aria-expanded') === 'true';
					button.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
					answer.hidden = isOpen;
					if (icon) {
						icon.innerHTML = isOpen ? '+' : '\u2212';
					}
				});
			});
		});
	}

	/* ---------------------------------------------------------------------
	 * Projects: category filter + modal
	 * ------------------------------------------------------------------ */
	function initProjectFilters() {
		document.querySelectorAll('[data-mw-filters]').forEach(function (bar) {
			var grid = document.querySelector('[data-mw-project-grid]');
			if (!grid) {
				return;
			}

			bar.querySelectorAll('[data-mw-filter]').forEach(function (button) {
				button.addEventListener('click', function () {
					var category = button.getAttribute('data-mw-filter');

					bar.querySelectorAll('[data-mw-filter]').forEach(function (other) {
						other.classList.toggle('is-active', other === button);
					});

					grid.querySelectorAll('[data-mw-category]').forEach(function (card) {
						var match = category === 'All' || card.getAttribute('data-mw-category') === category;
						card.style.display = match ? '' : 'none';
					});
				});
			});
		});
	}

	function initProjectModal() {
		var modal = document.querySelector('[data-mw-modal]');
		if (!modal) {
			return;
		}

		var dialog = modal.querySelector('.mw-modal__dialog');
		var fields = {
			image: modal.querySelector('.mw-modal__media img'),
			category: modal.querySelector('[data-mw-modal-category]'),
			title: modal.querySelector('[data-mw-modal-title]'),
			location: modal.querySelector('[data-mw-modal-location]'),
			text: modal.querySelector('[data-mw-modal-text]'),
			materials: modal.querySelector('[data-mw-modal-materials]'),
			materialsText: modal.querySelector('[data-mw-modal-materials-text]')
		};

		function close() {
			modal.hidden = true;
			document.body.classList.remove('mw-modal-open');
		}

		function open(card) {
			var data;
			try {
				data = JSON.parse(card.getAttribute('data-mw-project') || '{}');
			} catch (error) {
				return;
			}

			if (fields.image) {
				fields.image.src = data.image || '';
				fields.image.alt = data.title || '';
			}
			if (fields.category) {
				fields.category.textContent = data.category || '';
			}
			if (fields.title) {
				fields.title.textContent = data.title || '';
			}
			if (fields.location) {
				fields.location.textContent = data.location || '';
			}
			if (fields.text) {
				fields.text.textContent = data.text || '';
			}
			if (fields.materials) {
				fields.materials.hidden = !data.materials;
			}
			if (fields.materialsText) {
				fields.materialsText.textContent = data.materials || '';
			}

			modal.hidden = false;
			document.body.classList.add('mw-modal-open');
			if (dialog) {
				dialog.focus();
			}
		}

		document.addEventListener('click', function (event) {
			var trigger = event.target.closest('[data-mw-view-project]');
			if (trigger) {
				open(trigger.closest('[data-mw-project]'));
				return;
			}

			if (event.target.closest('[data-mw-modal-close]')) {
				close();
				return;
			}

			if (event.target === modal) {
				close();
			}
		});

		document.addEventListener('keydown', function (event) {
			if ('Escape' === event.key && !modal.hidden) {
				close();
			}
		});
	}

	/* ---------------------------------------------------------------------
	 * Gallery lightbox
	 * ------------------------------------------------------------------ */
	function initLightbox() {
		var links = document.querySelectorAll('[data-mw-lightbox]');
		if (!links.length) {
			return;
		}

		var overlay = document.createElement('div');
		overlay.className = 'mw-lightbox';
		overlay.hidden = true;
		overlay.innerHTML = '<button type="button" class="mw-lightbox__close" aria-label="Close">&times;</button><img src="" alt="">';
		document.body.appendChild(overlay);

		var image = overlay.querySelector('img');

		function close() {
			overlay.hidden = true;
			image.src = '';
		}

		links.forEach(function (link) {
			link.addEventListener('click', function (event) {
				event.preventDefault();
				image.src = link.getAttribute('href');
				var inner = link.querySelector('img');
				image.alt = inner ? inner.alt : '';
				overlay.hidden = false;
			});
		});

		overlay.addEventListener('click', function (event) {
			if (event.target === overlay || event.target.closest('.mw-lightbox__close')) {
				close();
			}
		});

		document.addEventListener('keydown', function (event) {
			if ('Escape' === event.key && !overlay.hidden) {
				close();
			}
		});
	}

	/* ---------------------------------------------------------------------
	 * Forms: AJAX submission with a no-JS fallback
	 * ------------------------------------------------------------------ */
	function initForms() {
		document.querySelectorAll('[data-mw-form]').forEach(function (form) {
			var button = form.querySelector('button[type="submit"]');
			var originalLabel = button ? button.innerHTML : '';
			var message = form.querySelector('[data-mw-form-message]');

			function show(type, text) {
				if (!message) {
					return;
				}
				message.className = 'mw-form__message mw-form__message--' + type + ' is-visible';
				message.textContent = text;
			}

			form.addEventListener('submit', function (event) {
				if (!config.restUrl || typeof window.fetch !== 'function') {
					return; // Let the browser POST to admin-post.php.
				}

				event.preventDefault();

				var data = new FormData(form);
				form.classList.add('is-busy');
				if (button) {
					button.innerHTML = (config.i18n && config.i18n.sending) || 'Sending…';
				}

				fetch(config.restUrl, {
					method: 'POST',
					headers: config.nonce ? { 'X-WP-Nonce': config.nonce } : {},
					credentials: 'same-origin',
					body: data
				})
					.then(function (response) {
						return response.json().then(function (body) {
							return { ok: response.ok, body: body };
						});
					})
					.then(function (result) {
						var payload = result.body || {};

						if (result.ok && payload.success) {
							var wrapper = form.parentNode;
							var success = document.createElement('div');
							success.className = 'mw-form-success' + (form.classList.contains('mw-form--contact') ? ' mw-form-success--compact' : '');

							var titleText = form.getAttribute('data-mw-success-title') || 'Thank You';
							var title = document.createElement('h2');
							title.className = 'mw-form-success__title';
							title.textContent = titleText;

							var text = document.createElement('p');
							text.className = 'mw-form-success__text';
							text.textContent = payload.message || '';

							success.appendChild(title);
							success.appendChild(text);

							var whatsapp = form.querySelector('[data-mw-whatsapp-success]');
							if (whatsapp) {
								success.appendChild(whatsapp.cloneNode(true));
							}

							wrapper.replaceChild(success, form);
							success.scrollIntoView({ behavior: 'smooth', block: 'center' });
							return;
						}

						show('error', payload.message || (config.i18n && config.i18n.error) || 'Something went wrong.');
						form.classList.remove('is-busy');
						if (button) {
							button.innerHTML = originalLabel;
						}
					})
					.catch(function () {
						show('error', (config.i18n && config.i18n.error) || 'Something went wrong.');
						form.classList.remove('is-busy');
						if (button) {
							button.innerHTML = originalLabel;
						}
					});
			});
		});
	}

	function ready(fn) {
		if (document.readyState !== 'loading') {
			fn();
		} else {
			document.addEventListener('DOMContentLoaded', fn);
		}
	}

	ready(function () {
		initHeader();
		initFaq();
		initProjectFilters();
		initProjectModal();
		initLightbox();
		initForms();
	});
})();
