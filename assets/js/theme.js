(() => {
	"use strict";

	const toggle = document.querySelector(".menu-toggle");
	const navigation = document.querySelector(".site-nav");

	if (toggle && navigation) {
		toggle.addEventListener("click", () => {
			const isOpen = navigation.classList.toggle("is-open");
			toggle.setAttribute("aria-expanded", String(isOpen));
			document.body.classList.toggle("menu-open", isOpen);
		});

		navigation.addEventListener("click", (event) => {
			if (event.target.closest("a")) {
				navigation.classList.remove("is-open");
				toggle.setAttribute("aria-expanded", "false");
				document.body.classList.remove("menu-open");
			}
		});
	}

	document.querySelectorAll(".accordion").forEach((accordion) => {
		const items = accordion.querySelectorAll(".accordion-item");

		items.forEach((item) => {
			const button = item.querySelector(".accordion-item__button");
			const panel = item.querySelector(".accordion-item__panel");

			if (!button || !panel) {
				return;
			}

			button.addEventListener("click", () => {
				const willOpen = !item.classList.contains("is-open");

				if (accordion.dataset.single !== "false") {
					items.forEach((otherItem) => {
						otherItem.classList.remove("is-open");
						const otherButton = otherItem.querySelector(".accordion-item__button");
						if (otherButton) {
							otherButton.setAttribute("aria-expanded", "false");
						}
					});
				}

				item.classList.toggle("is-open", willOpen);
				button.setAttribute("aria-expanded", String(willOpen));
			});
		});
	});

	document.querySelectorAll("[data-video-placeholder]").forEach((button) => {
		button.addEventListener("click", () => {
			const message = button.closest(".portal-video, .home-hero__media")?.querySelector(".video-message");
			if (message) {
				message.hidden = false;
			}
		});
	});

	const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

	document.querySelectorAll("[data-ambient-video]").forEach((video) => {
		const syncPlayback = () => {
			if (reducedMotion.matches || document.hidden) {
				video.pause();
				if (reducedMotion.matches) {
					video.currentTime = 0;
				}
				return;
			}

			const playback = video.play();
			if (playback && typeof playback.catch === "function") {
				playback.catch(() => {});
			}
		};

		syncPlayback();
		document.addEventListener("visibilitychange", syncPlayback);
		if (typeof reducedMotion.addEventListener === "function") {
			reducedMotion.addEventListener("change", syncPlayback);
		}
	});

	const typewriterTitles = new Set(document.querySelectorAll("[data-typewriter]"));
	const primaryTitle = document.querySelector("main h1");
	if (primaryTitle) {
		typewriterTitles.add(primaryTitle);
	}

	typewriterTitles.forEach((title) => {
		const text = title.innerText.trim();
		if (!text) {
			return;
		}

		const visibleText = document.createElement("span");
		const titleHeight = title.getBoundingClientRect().height;
		visibleText.setAttribute("aria-hidden", "true");
		title.setAttribute("aria-label", text.replace(/\s+/g, " "));
		if (titleHeight > 0) {
			title.style.minHeight = `${Math.ceil(titleHeight)}px`;
		}
		title.textContent = "";
		title.append(visibleText);
		title.classList.add("is-typing");
		title.classList.add("typewriter-title");

		let characterIndex = 0;
		const typeNextCharacter = () => {
			characterIndex += 1;
			visibleText.textContent = text.slice(0, characterIndex);

			if (characterIndex < text.length) {
				const character = text.charAt(characterIndex - 1);
				const baseDelay = reducedMotion.matches ? 28 : 58;
				const delay = /[.:,!?]/.test(character) ? baseDelay * 2.6 : baseDelay;
				window.setTimeout(typeNextCharacter, delay);
				return;
			}

			title.classList.remove("is-typing");
			title.classList.add("is-typed");
		};

		window.setTimeout(typeNextCharacter, reducedMotion.matches ? 180 : 420);
	});

	document.querySelectorAll("[data-image-stack]").forEach((stack) => {
		const cards = Array.from(stack.querySelectorAll(".about-motion__card"));
		if (cards.length < 2) {
			cards.forEach((card, index) => card.classList.toggle("is-active", index === 0));
			return;
		}

		stack.classList.toggle("is-reduced-motion", reducedMotion.matches);
		const requestedInterval = Number.parseInt(stack.dataset.interval || "3600", 10);
		const interval = reducedMotion.matches ? Math.max(4200, requestedInterval) : Math.max(2400, requestedInterval);
		let activeIndex = Math.max(0, cards.findIndex((card) => card.classList.contains("is-active")));
		let timer = null;
		let previewTimer = null;
		let hasPreviewed = false;

		const showNext = () => {
			const current = cards[activeIndex];
			activeIndex = (activeIndex + 1) % cards.length;
			const next = cards[activeIndex];

			current.classList.remove("is-active");
			current.classList.add("is-leaving");
			next.classList.remove("is-leaving");
			next.classList.add("is-active");
			window.setTimeout(() => current.classList.remove("is-leaving"), 900);
		};

		const stop = () => {
			if (timer) {
				window.clearInterval(timer);
				timer = null;
			}
			if (previewTimer) {
				window.clearTimeout(previewTimer);
				previewTimer = null;
			}
		};
		const start = () => {
			if (!timer && !document.hidden) {
				if (!hasPreviewed) {
					hasPreviewed = true;
					previewTimer = window.setTimeout(showNext, reducedMotion.matches ? 900 : 520);
				}
				timer = window.setInterval(showNext, interval);
			}
		};

		if ("IntersectionObserver" in window) {
			const observer = new IntersectionObserver(
				(entries) => {
					if (entries.some((entry) => entry.isIntersecting)) {
						start();
					} else {
						stop();
					}
				},
				{ threshold: 0.2 }
			);
			observer.observe(stack);
		} else {
			start();
		}

		document.addEventListener("visibilitychange", () => {
			if (document.hidden) {
				stop();
			} else {
				start();
			}
		});
	});

	/*
	 * Forminator emits this event only after a successful AJAX submission.
	 * It gives analytics and future integrations one stable, form-agnostic
	 * event without coupling the theme to a specific analytics vendor.
	 */
	if (window.jQuery) {
		window.jQuery(document).on("forminator:form:submit:success", (event) => {
			const form = event.target?.closest?.(".forminator-custom-form") || event.target;
			const wrapper = form?.closest?.("[data-form-key]");
			const formId = form?.dataset?.formId || form?.dataset?.id || "";
			const detail = {
				event: "ingbiro_form_submit",
				form_id: formId,
				form_key: wrapper?.dataset?.formKey || window.ingbiroForms?.formKeys?.[formId] || "",
				page_path: window.location.pathname,
			};

			window.dataLayer = window.dataLayer || [];
			window.dataLayer.push(detail);
			window.dispatchEvent(new CustomEvent("ingbiro:form:submitted", { detail }));
		});
	}
})();
