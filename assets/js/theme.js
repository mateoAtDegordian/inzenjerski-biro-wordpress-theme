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
