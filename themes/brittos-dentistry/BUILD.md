# Theme Asset Build

This theme now uses a lightweight build pipeline with esbuild.

## Goals
- Group component styles into maintainable partials.
- Concatenate and minify CSS and JavaScript for production delivery.

## Source Layout
- CSS entry: assets/src/css/site.css
- JS entry: assets/src/js/site.js
- Component partials: assets/css/components/

## Build Commands
- Install dependencies:
  npm install

- Build production bundles:
  npm run build

- Build CSS only:
  npm run build:css

- Build JS only:
  npm run build:js

- Watch mode:
  npm run watch:css
  npm run watch:js

## Output
- Minified CSS bundle: assets/dist/css/site.min.css
- Minified JS bundle: assets/dist/js/site.min.js

## Runtime Loading Behavior
The theme enqueues bundled dist files when both are present.
If dist files are missing, it falls back to source CSS/JS files.

This lets production stay optimized while preserving a safe fallback during development.
