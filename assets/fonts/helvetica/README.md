# Local Helvetica webfonts

Place licensed copies of these files in this directory:

- `Helvetica.woff`
- `Helvetica-Bold.woff`
- `Helvetica-Oblique.woff`

For production, place the same files in `wp-content/uploads/ingbiro-fonts/`.
That location survives Git Updater theme replacements and has priority over the
theme-local fallback. The theme loads `local-fonts.css` only when it is present.
Font binaries and that stylesheet are intentionally ignored by Git and excluded
from public release ZIP files because the repository must not redistribute the
license.

The site falls back to Helvetica Neue, Helvetica and Arial when the local files
are unavailable.
