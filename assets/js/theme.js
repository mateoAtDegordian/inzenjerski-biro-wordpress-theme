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
		const targetTypingDuration = reducedMotion.matches ? 1400 : 2500;
		const minimumDelay = reducedMotion.matches ? 14 : 28;
		const maximumDelay = reducedMotion.matches ? 30 : 58;
		const baseDelay = Math.round(
			Math.min(maximumDelay, Math.max(minimumDelay, targetTypingDuration / text.length))
		);
		const typeNextCharacter = () => {
			characterIndex += 1;
			visibleText.textContent = text.slice(0, characterIndex);

			if (characterIndex < text.length) {
				const character = text.charAt(characterIndex - 1);
				const delay = /[.:,!?]/.test(character) ? baseDelay * 2 : baseDelay;
				window.setTimeout(typeNextCharacter, delay);
				return;
			}

			title.classList.remove("is-typing");
			title.classList.add("is-typed");
		};

		window.setTimeout(typeNextCharacter, reducedMotion.matches ? 80 : 160);
	});

	const revealTargets = new Set(
		document.querySelectorAll(
			[
				".page-main > section > .container",
				".page-main > section.container",
				".event-single__hero > .container",
				".event-single > section:not(.event-content) > .container",
			].join(",")
		)
	);

	document.querySelectorAll(".event-content__body").forEach((body) => {
		const eventBlocks = body.querySelectorAll(":scope > .event-block");
		if (!eventBlocks.length) {
			revealTargets.add(body);
			return;
		}

		eventBlocks.forEach((block) => {
			Array.from(block.children)
				.filter((child) => !child.classList.contains("event-program-gears"))
				.forEach((child) => revealTargets.add(child));
		});
	});

	if ("IntersectionObserver" in window) {
		const playReveal = (target) => {
			if (typeof target.animate !== "function") {
				target.classList.add("is-visible");
				return;
			}

			const revealX = target.style.getPropertyValue("--reveal-x") || "0px";
			const revealY = target.style.getPropertyValue("--reveal-y") || "28px";
			const revealDelay = Number.parseInt(target.style.getPropertyValue("--reveal-delay"), 10) || 0;

			target.classList.add("is-waapi-reveal", "is-visible");
			const animation = target.animate(
				[
					{
						offset: 0,
						opacity: 0.02,
						filter: "blur(10px)",
						transform: `translate3d(${revealX}, ${revealY}, 0) scale(0.97)`,
					},
					{
						offset: 0.48,
						opacity: 0.76,
						filter: "blur(3px)",
					},
					{
						offset: 1,
						opacity: 1,
						filter: "blur(0)",
						transform: "translate3d(0, 0, 0) scale(1)",
					},
				],
				{
					duration: 1280,
					delay: revealDelay,
					easing: "cubic-bezier(0.22, 0.55, 0.2, 1)",
					fill: "both",
				}
			);

			animation.finished
				.then(() => {
					target.style.opacity = "1";
					target.style.filter = "blur(0)";
					target.style.transform = "translate3d(0, 0, 0) scale(1)";
					animation.cancel();
				})
				.catch(() => {});
		};

		const revealObserver = new IntersectionObserver(
			(entries) => {
				entries.forEach((entry) => {
					if (!entry.isIntersecting) {
						return;
					}

					playReveal(entry.target);
					revealObserver.unobserve(entry.target);
				});
			},
			{
				rootMargin: "0px 0px -6% 0px",
				threshold: 0.05,
			}
		);

		const preparedTargets = [];
		const horizontalDistance = window.matchMedia("(max-width: 760px)").matches ? 34 : 56;
		revealTargets.forEach((target, index) => {
			const targetRect = target.getBoundingClientRect();
			const isInitiallyVisible = targetRect.top < window.innerHeight * 0.92 && targetRect.bottom > 0;

			/*
			 * Content in the initial viewport must remain fully visible on first
			 * paint. Reveal motion is reserved for sections reached by scrolling.
			 */
			if (isInitiallyVisible) {
				return;
			}

			const direction = index % 3;
			const revealX = direction === 0 ? -horizontalDistance : direction === 1 ? horizontalDistance : 0;
			const revealY = direction === 2 ? 34 : 18;

			target.style.setProperty("--reveal-x", `${revealX}px`);
			target.style.setProperty("--reveal-y", `${revealY}px`);
			target.style.setProperty("--reveal-delay", `${Math.min(direction, 2) * 85}ms`);
			target.classList.add("scroll-reveal");

			preparedTargets.push(target);
		});

		/*
		 * Two frames guarantee that the hidden/offset state is painted before
		 * IntersectionObserver can add is-visible. Without this, fast browsers
		 * may collapse both states into one frame and make sections simply pop in.
		 */
		window.requestAnimationFrame(() => {
			window.requestAnimationFrame(() => {
				preparedTargets.forEach((target) => revealObserver.observe(target));
			});
		});
	} else {
		revealTargets.forEach((target) => target.classList.add("is-visible"));
	}

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
