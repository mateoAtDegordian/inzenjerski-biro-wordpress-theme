(() => {
	"use strict";

	const toggle = document.querySelector(".menu-toggle");
	const navigation = document.querySelector(".site-nav");
	const siteHeader = document.querySelector(".site-header");

	if (siteHeader) {
		const updateHeader = () => siteHeader.classList.toggle("is-scrolled", window.scrollY > 16);
		updateHeader();
		window.addEventListener("scroll", updateHeader, { passive: true });
	}

	if (toggle && navigation) {
		const toggleLabel = toggle.querySelector(".screen-reader-text");
		const setMenuState = (isOpen) => {
			navigation.classList.toggle("is-open", isOpen);
			toggle.setAttribute("aria-expanded", String(isOpen));
			document.body.classList.toggle("menu-open", isOpen);

			if (toggleLabel) {
				toggleLabel.textContent = isOpen
					? toggle.dataset.closeLabel || "Close menu"
					: toggle.dataset.openLabel || "Open menu";
			}
		};

		toggle.addEventListener("click", () => {
			setMenuState(!navigation.classList.contains("is-open"));
		});

		navigation.addEventListener("click", (event) => {
			if (event.target.closest("a")) {
				setMenuState(false);
			}
		});

		document.addEventListener("keydown", (event) => {
			if (event.key === "Escape" && navigation.classList.contains("is-open")) {
				setMenuState(false);
				toggle.focus();
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

	const videoDialog = document.querySelector("[data-video-dialog]");
	const dialogVideo = videoDialog?.querySelector(".video-dialog__video");
	let lastVideoTrigger = null;

	const closeVideo = () => {
		if (!videoDialog || !dialogVideo) {
			return;
		}

		dialogVideo.pause();
		dialogVideo.removeAttribute("src");
		dialogVideo.load();
		videoDialog.hidden = true;
		document.body.classList.remove("video-dialog-open");
		const inlineVideo = lastVideoTrigger?.closest(".cinematic-scroll__media")?.querySelector("[data-cinematic-video]");
		if (inlineVideo && inlineVideo.getBoundingClientRect().bottom > 0 && inlineVideo.getBoundingClientRect().top < window.innerHeight) {
			inlineVideo.play().catch(() => {});
		}
		lastVideoTrigger?.focus();
	};

	document.querySelectorAll("[data-video-open]").forEach((button) => {
		button.addEventListener("click", () => {
			if (!videoDialog || !dialogVideo || !button.dataset.videoSrc) {
				return;
			}

			lastVideoTrigger = button;
			button.closest(".cinematic-scroll__media")?.querySelector("[data-cinematic-video]")?.pause();
			dialogVideo.src = button.dataset.videoSrc;
			videoDialog.hidden = false;
			document.body.classList.add("video-dialog-open");
			videoDialog.querySelector(".video-dialog__close")?.focus();
			dialogVideo.play().catch(() => {});
		});
	});

	videoDialog?.querySelectorAll("[data-video-close]").forEach((button) => {
		button.addEventListener("click", closeVideo);
	});

	document.addEventListener("keydown", (event) => {
		if (event.key === "Escape" && videoDialog && !videoDialog.hidden) {
			closeVideo();
		}
	});

	/*
	 * Expand the two primary media frames to the available viewport while the
	 * user moves through their sticky scroll stages, then reverse the motion on
	 * exit. Gutenberg's English video hero is wrapped at runtime so existing CMS
	 * content does not need to be overwritten.
	 */
	const cinematicTargets = Array.from(
		document.querySelectorAll(".home-hero__media, .portal-video, .modular-hero__video")
	);

	if (cinematicTargets.length) {
		const clamp = (value, minimum = 0, maximum = 1) => Math.min(maximum, Math.max(minimum, value));
		const mix = (start, end, amount) => start + (end - start) * amount;
		const smootherStep = (value) => {
			const progress = clamp(value);
			return progress * progress * progress * (progress * (progress * 6 - 15) + 10);
		};
		const easeOutCubic = (value) => 1 - Math.pow(1 - clamp(value), 3);
		const states = cinematicTargets.map((media) => {
			let stage = media.closest("[data-cinematic-scroll]");

			if (!stage) {
				stage = document.createElement("div");
				stage.className = "cinematic-scroll";
				stage.dataset.cinematicScroll = "";

				const sticky = document.createElement("div");
				sticky.className = "cinematic-scroll__sticky";
				media.before(stage);
				stage.append(sticky);
				sticky.append(media);
			}

			media.classList.add("cinematic-scroll__media");
			stage.closest("section")?.classList.add("has-cinematic-scroll");
			if (!media.querySelector(".cinematic-scroll__hint")) {
				const hint = document.createElement("span");
				hint.className = "cinematic-scroll__hint";
				hint.setAttribute("aria-hidden", "true");
				hint.textContent = "SCROLL";
				media.append(hint);
			}

			return {
				stage,
				media,
				sticky: stage.querySelector(".cinematic-scroll__sticky"),
				currentProgress: null,
				targetProgress: 0,
				metrics: null,
			};
		});
		let animationFrame = 0;
		const headerProperties = [
			"--ing-active-header-height",
			"--ing-active-logo-column",
			"--ing-active-header-gap",
			"--ing-active-logo-width",
			"--ing-active-logo-height",
			"--ing-active-nav-font-size",
			"--ing-active-contact-height",
			"--ing-active-contact-font-size",
		];

		const resetCinematicHeader = () => {
			headerProperties.forEach((property) => siteHeader?.style.removeProperty(property));
		};

		const renderCinematicHeader = (expansion, viewportWidth) => {
			if (!siteHeader || viewportWidth <= 900) {
				resetCinematicHeader();
				return;
			}

			const compactLayout = viewportWidth <= 1120;
			const normalLogoWidth = compactLayout ? 210 : 240;
			const normalHeaderGap = compactLayout ? 24 : 40;
			const normalNavSize = compactLayout ? 16 : 18;
			const normalContactSize = compactLayout ? 14 : 17;

			siteHeader.style.setProperty("--ing-active-header-height", `${mix(118, 68, expansion)}px`);
			siteHeader.style.setProperty("--ing-active-logo-column", `${mix(normalLogoWidth, compactLayout ? 150 : 170, expansion)}px`);
			siteHeader.style.setProperty("--ing-active-header-gap", `${mix(normalHeaderGap, compactLayout ? 18 : 24, expansion)}px`);
			siteHeader.style.setProperty("--ing-active-logo-width", `${mix(normalLogoWidth, compactLayout ? 140 : 155, expansion)}px`);
			siteHeader.style.setProperty("--ing-active-logo-height", `${mix(63, 42, expansion)}px`);
			siteHeader.style.setProperty("--ing-active-nav-font-size", `${mix(normalNavSize, compactLayout ? 13 : 15, expansion)}px`);
			siteHeader.style.setProperty("--ing-active-contact-height", `${mix(40, 34, expansion)}px`);
			siteHeader.style.setProperty("--ing-active-contact-font-size", `${mix(normalContactSize, compactLayout ? 12 : 14, expansion)}px`);
		};

		const measure = () => {
			resetCinematicHeader();
			const viewportWidth = document.documentElement.clientWidth;
			const viewportHeight = window.innerHeight;
			const normalHeaderHeight = viewportWidth <= 900 ? 92 : 118;
			const compactHeaderHeight = 68;
			const headerOffset = Math.max(0, siteHeader?.getBoundingClientRect().top || 0);
			const headerBottom = headerOffset + normalHeaderHeight;
			const headerInner = document.querySelector(".site-header__inner");
			const contentWidth = Math.min(
				viewportWidth,
				headerInner?.getBoundingClientRect().width || viewportWidth
			);
			const sideInset = Math.max(0, (viewportWidth - contentWidth) / 2);
			const stickyHeight = Math.max(320, viewportHeight - headerBottom);
			const baseHeight = viewportWidth <= 620 ? 270 : viewportWidth <= 1200 ? 400 : 490;
			const initialRadius = viewportWidth <= 620 ? 12 : 16;
			const stageMultiplier = viewportWidth <= 900 ? 1.85 : 2.15;
			const entranceLead = clamp(viewportHeight * 0.36, 260, 360);

			states.forEach((state) => {
				state.stage.style.setProperty("--cinematic-header-height", `${headerBottom}px`);
				state.stage.style.setProperty("--cinematic-sticky-height", `${stickyHeight}px`);
				if (viewportWidth <= 900) {
					state.stage.style.removeProperty("height");
				} else {
					state.stage.style.height = `${Math.max(980, stickyHeight * stageMultiplier)}px`;
				}
				state.stage.style.marginBottom = viewportWidth <= 900
					? "0px"
					: `${Math.min(0, baseHeight - stickyHeight)}px`;
				const scrollRange = Math.max(1, state.stage.offsetHeight - stickyHeight);
				const fullTimeline = scrollRange + entranceLead;
				state.metrics = {
					viewportWidth,
					viewportHeight,
					headerOffset,
					normalHeaderHeight,
					compactHeaderHeight,
					stickyHeight,
					entranceLead,
					entranceEnd: entranceLead / fullTimeline,
					exitStart: scrollRange / fullTimeline,
					timelineLength: fullTimeline,
					baseHeight: Math.min(baseHeight, stickyHeight),
					baseWidth: Math.max(0, viewportWidth - sideInset * 2),
					sideInset,
					initialRadius,
				};
			});
		};

		const readProgress = (state) => {
			const stageRect = state.stage.getBoundingClientRect();
			const stickyTop = Number.parseFloat(
				getComputedStyle(state.stage).getPropertyValue("--cinematic-header-height")
			) || 0;
			return clamp((stickyTop + state.metrics.entranceLead - stageRect.top) / state.metrics.timelineLength);
		};

		const renderState = (state) => {
			const progress = state.currentProgress;
			const metrics = state.metrics;

			if (metrics.viewportWidth <= 900) {
				resetCinematicHeader();
				["left", "width", "height", "border-radius"].forEach((property) => state.media.style.removeProperty(property));
				state.stage.style.removeProperty("--cinematic-media-y");
				state.stage.dataset.cinematicProgress = "0.000";
				state.stage.dataset.cinematicExpansion = "0.000";
				state.stage.classList.add("is-ready");
				return;
			}

			const entranceExpansion = easeOutCubic(progress / metrics.entranceEnd);
			const exitExpansion = 1 - smootherStep((progress - metrics.exitStart) / (1 - metrics.exitStart));
			const expansion = Math.min(entranceExpansion, exitExpansion);
			const currentHeaderHeight = mix(metrics.normalHeaderHeight, metrics.compactHeaderHeight, expansion);
			const currentHeaderBottom = metrics.headerOffset + currentHeaderHeight;
			const currentStickyHeight = Math.max(320, metrics.viewportHeight - currentHeaderBottom);
			const mediaHeight = mix(metrics.baseHeight, currentStickyHeight, expansion);
			const mediaWidth = mix(metrics.baseWidth, metrics.viewportWidth, expansion);
			const mediaLeft = mix(metrics.sideInset, 0, expansion);
			const virtualStageTop = currentHeaderBottom + metrics.entranceLead - progress * metrics.timelineLength;
			const entranceLift = Math.min(0, currentHeaderBottom - virtualStageTop) * entranceExpansion;

			renderCinematicHeader(expansion, metrics.viewportWidth);
			state.stage.style.setProperty("--cinematic-header-height", `${currentHeaderBottom}px`);
			state.stage.style.setProperty("--cinematic-sticky-height", `${currentStickyHeight}px`);
			state.media.style.left = `${mediaLeft}px`;
			state.media.style.width = `${mediaWidth}px`;
			state.media.style.height = `${mediaHeight}px`;
			state.media.style.borderRadius = `${mix(metrics.initialRadius, 0, expansion)}px`;
			state.stage.style.setProperty("--cinematic-media-y", `${entranceLift}px`);
			state.stage.dataset.cinematicProgress = progress.toFixed(3);
			state.stage.dataset.cinematicExpansion = expansion.toFixed(3);
			state.stage.classList.add("is-ready");
		};

		const animate = () => {
			let needsAnotherFrame = false;

			states.forEach((state) => {
				state.targetProgress = readProgress(state);
				if (state.currentProgress === null) {
					state.currentProgress = state.targetProgress;
				} else {
					const difference = state.targetProgress - state.currentProgress;
					if (Math.abs(difference) > 0.0005) {
						state.currentProgress += difference * 0.24;
						needsAnotherFrame = true;
					} else {
						state.currentProgress = state.targetProgress;
					}
				}
				renderState(state);
			});

			animationFrame = needsAnotherFrame ? window.requestAnimationFrame(animate) : 0;
		};

		const requestRender = () => {
			if (!animationFrame) {
				animationFrame = window.requestAnimationFrame(animate);
			}
		};

		measure();
		animate();
		window.addEventListener("scroll", requestRender, { passive: true });
		window.addEventListener("resize", () => {
			measure();
			states.forEach((state) => {
				state.currentProgress = null;
			});
			requestRender();
		}, { passive: true });

		const inlineVideos = states
			.map((state) => state.media.querySelector("[data-cinematic-video]"))
			.filter(Boolean);

		if (inlineVideos.length && "IntersectionObserver" in window) {
			const playbackObserver = new IntersectionObserver(
				(entries) => {
					entries.forEach((entry) => {
						const video = entry.target;
						if (entry.isIntersecting && !document.hidden) {
							video.play().catch(() => {});
						} else {
							video.pause();
						}
					});
				},
				{ rootMargin: "25% 0px", threshold: 0.01 }
			);

			inlineVideos.forEach((video) => playbackObserver.observe(video));
			document.addEventListener("visibilitychange", () => {
				if (document.hidden) {
					inlineVideos.forEach((video) => video.pause());
				} else {
					inlineVideos.forEach((video) => {
						if (video.getBoundingClientRect().bottom > 0 && video.getBoundingClientRect().top < window.innerHeight) {
							video.play().catch(() => {});
						}
					});
				}
			});
		}
	}

	const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
	const legalContent = document.querySelector(".legal-page__content");

	if (legalContent) {
		Array.from(legalContent.children)
			.filter((element) => element.tagName === "H2")
			.forEach((heading, index) => {
				const details = document.createElement("details");
				const summary = document.createElement("summary");
				summary.innerHTML = heading.innerHTML;
				details.className = "legal-accordion";
				details.open = index === 0;
				heading.before(details);
				details.append(summary);

				let sibling = heading.nextSibling;
				heading.remove();
				while (sibling && !(sibling.nodeType === Node.ELEMENT_NODE && sibling.tagName === "H2")) {
					const next = sibling.nextSibling;
					details.append(sibling);
					sibling = next;
				}
			});
	}

	const typewriterTitles = new Set(document.querySelectorAll("[data-typewriter]"));
	const primaryTitle = document.querySelector("main h1");
	if (primaryTitle && !legalContent && !primaryTitle.matches("[data-no-typewriter]")) {
		typewriterTitles.add(primaryTitle);
	}

	typewriterTitles.forEach((title) => {
		if (title.matches("[data-no-typewriter]")) {
			return;
		}

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

	document.querySelectorAll(".building-banner-shell").forEach((shell) => {
		const banner = shell.querySelector(".building-banner");
		if (!banner) {
			return;
		}

		if (!("IntersectionObserver" in window)) {
			banner.classList.add("is-revealed");
			return;
		}

		const bannerObserver = new IntersectionObserver(
			(entries) => {
				if (entries.some((entry) => entry.isIntersecting)) {
					banner.classList.add("is-revealed");
					bannerObserver.disconnect();
				}
			},
			{ threshold: 0.18 }
		);
		/*
		 * Observe the un-clipped shell. A fully clipped child can report a zero
		 * intersection area in WebKit and never start its reveal animation.
		 */
		bannerObserver.observe(shell);
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
	if (window.ingbiroForms?.language === "en" && window.ingbiroForms?.translations) {
		const formMessageSelector = ".forminator-error-message, .forminator-response-message";
		const translations = window.ingbiroForms.translations;

		const translateFormMessage = (element) => {
			const walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT);
			let textNode = walker.nextNode();

			while (textNode) {
				const value = textNode.nodeValue || "";
				const trimmed = value.trim();

				if (trimmed && translations[trimmed]) {
					textNode.nodeValue = value.replace(trimmed, translations[trimmed]);
				}

				textNode = walker.nextNode();
			}
		};

		const translateFormMessages = (root) => {
			if (root.nodeType !== Node.ELEMENT_NODE) {
				return;
			}

			const parentMessage = root.closest(formMessageSelector);
			if (parentMessage) {
				translateFormMessage(parentMessage);
			}

			if (root.matches(formMessageSelector)) {
				translateFormMessage(root);
			}

			root.querySelectorAll(formMessageSelector).forEach(translateFormMessage);
		};

		translateFormMessages(document.body);

		new MutationObserver((mutations) => {
			mutations.forEach((mutation) => {
				if (mutation.type === "characterData" && mutation.target.parentElement) {
					translateFormMessages(mutation.target.parentElement);
				}

				mutation.addedNodes.forEach((node) => {
					if (node.nodeType === Node.ELEMENT_NODE) {
						translateFormMessages(node);
					}
				});
			});
		}).observe(document.body, { characterData: true, childList: true, subtree: true });
	}

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
