---
paths:
  - "assets/fonts/**"
  - "assets/scss/0-settings/_fonts.scss"
---

# Fonts

- `assets/fonts/`: only `.woff2` and `.woff` font files, nothing else (convert other formats first, e.g. with Transfonter).
- Declare them in `assets/scss/0-settings/_fonts.scss` with `font-display: swap`.
- Copied to `dist/fonts/` by gulp.
