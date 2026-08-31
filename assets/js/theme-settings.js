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
	const playbackSettings = document.querySelector("[data-video-playback-settings]");
	let frame = null;

	const readPlaybackSettings = () => ({
		autoplay: playbackSettings?.querySelector('[data-video-setting="autoplay"]')?.checked ?? true,
		loop: playbackSettings?.querySelector('[data-video-setting="loop"]')?.checked ?? true,
		muted: playbackSettings?.querySelector('[data-video-setting="muted"]')?.checked ?? true,
		controls: playbackSettings?.querySelector('[data-video-setting="controls"]')?.checked ?? false,
		playsInline: playbackSettings?.querySelector('[data-video-setting="playsinline"]')?.checked ?? true,
		preload: playbackSettings?.querySelector('[data-video-setting="preload"]')?.value || "auto",
	});

	const applyPlaybackSettings = (video) => {
		if (!video) {
			return;
		}

		const settings = readPlaybackSettings();
		video.autoplay = settings.autoplay;
		video.defaultMuted = settings.muted;
		video.muted = settings.muted;
		video.loop = settings.loop;
		video.controls = settings.controls;
		video.playsInline = settings.playsInline;
		video.preload = settings.preload;

		if (settings.autoplay) {
			video.play().catch(() => {});
		} else {
			video.pause();
		}
	};

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

		asset.src = url;
		preview.replaceChildren(asset);
		preview.style.setProperty("--ingbiro-preview-aspect", `${width || 1138} / ${height || 640}`);
		label.textContent = filename;
		applyPlaybackSettings(kind === "video" ? asset : null);
	};

	playbackSettings?.addEventListener("change", () => {
		applyPlaybackSettings(preview.querySelector("video"));
	});

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
