(() => {
	"use strict";

	const setting = document.querySelector("[data-ingbiro-media-setting]");
	if (!setting || !window.wp || !wp.media || !window.ingbiroThemeSettings) {
		return;
	}

	const input = setting.querySelector("[data-media-id]");
	const preview = setting.querySelector("[data-media-preview]");
	const label = setting.querySelector("[data-media-label]");
	const selectButton = setting.querySelector("[data-media-select]");
	const resetButton = setting.querySelector("[data-media-reset]");
	let frame = null;

	const mediaKind = (mime = "", url = "") => {
		if (mime.startsWith("video/")) {
			return "video";
		}
		if (mime.startsWith("image/")) {
			return "image";
		}

		const extension = url.split("?")[0].split(".").pop().toLowerCase();
		if (["webm", "mp4", "m4v", "mov", "ogv", "ogg"].includes(extension)) {
			return "video";
		}
		if (["jpg", "jpeg", "png", "gif", "webp", "avif", "svg"].includes(extension)) {
			return "image";
		}
		return "";
	};

	const renderPreview = ({ url, mime, kind, width, height, filename }) => {
		const asset = document.createElement(kind === "video" ? "video" : "img");
		asset.className = `ingbiro-media-preview__asset building-banner__${kind}`;
		asset.setAttribute("aria-hidden", "true");

		if (kind === "video") {
			asset.autoplay = true;
			asset.defaultMuted = true;
			asset.muted = true;
			asset.loop = true;
			asset.playsInline = true;
			asset.preload = "auto";
		}

		asset.src = url;
		preview.replaceChildren(asset);
		preview.style.setProperty("--ingbiro-preview-aspect", `${width || 1138} / ${height || 640}`);
		label.textContent = filename;
		if (kind === "video") {
			asset.play().catch(() => {});
		}
	};

	selectButton.addEventListener("click", () => {
		if (!frame) {
			frame = wp.media({
				title: ingbiroThemeSettings.frameTitle,
				button: { text: ingbiroThemeSettings.frameButton },
				multiple: false,
			});

			frame.on("select", () => {
				const attachment = frame.state().get("selection").first().toJSON();
				const url = attachment.url || "";
				const mime = attachment.mime || "";
				const kind = mediaKind(mime, url);
				if (!kind) {
					window.alert(ingbiroThemeSettings.unsupported);
					return;
				}

				input.value = attachment.id;
				resetButton.disabled = false;
				renderPreview({
					url,
					mime,
					kind,
					width: attachment.width,
					height: attachment.height,
					filename: attachment.filename || attachment.title || url.split("/").pop(),
				});
			});
		}

		frame.open();
	});

	resetButton.addEventListener("click", () => {
		input.value = "0";
		resetButton.disabled = true;
		renderPreview({
			url: ingbiroThemeSettings.defaultUrl,
			mime: ingbiroThemeSettings.defaultMime,
			kind: ingbiroThemeSettings.defaultKind,
			width: ingbiroThemeSettings.defaultWidth,
			height: ingbiroThemeSettings.defaultHeight,
			filename: ingbiroThemeSettings.defaultLabel,
		});
	});
})();
