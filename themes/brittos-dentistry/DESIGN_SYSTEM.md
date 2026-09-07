# Britto's Dentistry — Brand Direction & Design System

**Version:** 1.0  
**Target:** WordPress 7.1 / PHP 8.4  
**Theme architecture:** WordPress block theme  
**Primary audience:** prospective and existing dental patients  
**Design principle:** clinical precision + human warmth

---

## 1. Brand Direction

### Brand idea

**Quiet confidence. Modern dentistry. Human care.**

Britto's Dentistry should feel contemporary and premium without feeling luxurious, cold, fashionable, or intimidating. The visual identity should communicate:

- clinical competence
- calmness
- trust
- warmth
- precision
- modern care
- human attention

### Brand personality

| Attribute | Direction |
|---|---|
| Clinical | Precise, structured, clean |
| Warm | Ivory surfaces, natural photography |
| Premium | Whitespace, restraint, typography |
| Modern | Editorial composition, subtle motion |
| Human | Real people, real clinic, conversational copy |

### Positioning

Do **not** present the clinic as a technology company.

Do **not** use futuristic medical clichés.

"Cutting edge" means the site feels current because of its typography, composition, interaction quality, accessibility, performance and restraint — not because it has excessive animation.

---

## 2. Core Visual Concept

### Precision meets warmth

The existing Britto's Dentistry logo is the primary visual anchor: dark typography paired with a warm champagne/gold tooth mark.

The website should extend that contrast:

- **Precision:** ink, grid, typography, alignment, fine borders.
- **Warmth:** porcelain backgrounds, champagne accents, sage, photography, organic curves.

### Signature motif — The Smile Curve

Use a restrained curved line inspired by the curvature of a smile/tooth.

Use it for:

- section transitions
- decorative rules
- image masks
- hover details
- CTA decoration
- testimonial backgrounds

Do not use it on every section. It is a signature, not wallpaper.

---

# 3. Color System

## Core palette

| Token | Hex | Usage |
|---|---|---|
| Ink | `#171817` | Primary text |
| Ink Deep | `#0D1110` | Dark sections, footer, primary CTA |
| Porcelain | `#F7F5F0` | Primary page background |
| White | `#FFFFFF` | Cards, forms, contrast surfaces |
| Champagne | `#C8A77B` | Brand accent |
| Champagne Soft | `#E8DDCB` | Accent backgrounds |
| Sage | `#7F9287` | Secondary accent |
| Sage Soft | `#E7ECE8` | Informational backgrounds |
| Muted | `#737873` | Secondary text |
| Border | `#DEDCD5` | Dividers and subtle borders |

### Usage ratio

Target approximately:

- 90% neutral surfaces/text
- 7% sage
- 3% champagne

Champagne must remain an accent. Never turn the site gold.

### Color rules

- Body background: Porcelain.
- Primary text: Ink.
- Dark CTA sections: Ink Deep.
- Primary button: Ink Deep with white text.
- Champagne: highlights, small rules, active states and selected details.
- Sage: supporting information, not primary branding.
- Avoid blue as a dominant color.
- Avoid gradients unless introduced deliberately for photography treatment.
- Avoid pure black except where technically required.
- Maintain WCAG AA contrast for all readable text.

---

# 4. Typography

## Typeface

Headings and interface controls:

**Instrument Sans**

Body copy:

**DM Sans**

Recommended production implementation:

1. Bundle both families locally as WOFF2 when the final font files are available.
2. Prefer variable fonts where practical.
3. Do not depend on a third-party font CDN for the production site.

Fallback for both families:

`system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif`

### Type scale

| Token | Desktop | Mobile | Weight | Line height |
|---|---:|---:|---:|---:|
| Display XL | 72px | 48px | 600 | 1.00 |
| Display | 60px | 44px | 600 | 1.05 |
| H1 | 52px | 40px | 600 | 1.08 |
| H2 | 40px | 34px | 600 | 1.10 |
| H3 | 28px | 26px | 600 | 1.20 |
| H4 | 22px | 20px | 600 | 1.25 |
| Body XL | 21px | 19px | 400 | 1.55 |
| Body Large | 18px | 17px | 400 | 1.60 |
| Body | 16px | 16px | 400 | 1.60 |
| Body Small | 14px | 14px | 400 | 1.50 |
| Label | 12px | 12px | 600 | 1.30 |

### Typography rules

- Headlines should be compact and confident.
- Avoid all-caps paragraphs.
- Labels may use uppercase with approximately `0.10em` tracking.
- Body copy should never become too narrow on desktop.
- Maximum comfortable reading width: approximately 65–75 characters.
- Use negative tracking only for large display text.
- Do not use more than three text weights in a component.

### Voice

Prefer:

> Dentistry designed around you.

Over:

> The best dental care for the whole family.

Prefer:

> Thoughtful care. Modern dentistry.

Over:

> State-of-the-art dental solutions.

Prefer concrete claims over generic superlatives.

---

# 5. Layout System

## Container

- Maximum width: `1280px`
- Desktop side padding: `32px–48px`
- Tablet side padding: `24px`
- Mobile side padding: `20px`

## Grid

Use a 12-column editorial grid on desktop.

Common compositions:

- Hero: 5 / 7
- Image + copy: 7 / 5
- Copy + image: 5 / 7
- Testimonial: 3 / 6 / 3
- Appointment: 5 / 7

Do not turn every section into an equal three-column card grid.

### Section rhythm

Preferred desktop section spacing:

- Small: `64px`
- Medium: `96px`
- Large: `128px`
- Hero / major transitions: `160px`

Mobile:

- Small: `48px`
- Medium: `64px`
- Large: `88px`
- Hero: `96px`

Use the spacing scale consistently instead of arbitrary values.

---

# 6. Shape System

## Radius

| Token | Value |
|---|---:|
| Small | 8px |
| Medium | 14px |
| Large | 22px |
| Image | 28px |

Rules:

- Buttons: 12–14px.
- Cards: 14–22px.
- Large photography: up to 28px.
- Do not use `9999px` pills except for deliberately compact tags.
- Do not round every element.

## Borders

Default:

`1px solid Border`

Prefer borders over heavy shadows.

---

# 7. Elevation

The brand is intentionally low-shadow.

Use:

- no shadow for normal content cards
- very subtle shadow for floating UI only
- stronger shadow only for overlays/modal surfaces

Avoid "glassmorphism".

Avoid huge diffuse shadows.

---

# 8. Buttons

## Primary

Appearance:

- background: Ink Deep
- text: White
- radius: 12px
- height: approximately 48–52px
- horizontal padding: 20–24px

Label examples:

- `Book an appointment →`
- `Request an appointment →`

## Secondary

Use text/link treatment:

`View treatments →`

No large outlined-button collections.

## Hover

- move arrow approximately 4–6px
- subtly alter background
- never use exaggerated scaling

## Focus

Visible high-contrast focus ring.

Never remove focus outlines without replacing them with an equally visible accessible state.

---

# 9. Photography Direction

Photography is one of the primary differentiators.

## Prefer

- real clinic interiors
- natural light
- dentist-patient interaction
- consultation
- hands and detail
- instruments used naturally
- authentic team portraits
- calm patient moments
- architectural details

## Avoid

- generic smiling stock dentists
- giant toothbrush props
- exaggerated tooth imagery
- blue medical stock photography
- fake staged treatment scenes
- excessive HDR
- over-whitened teeth
- heavy filters

### Treatment

Photography should generally have:

- warm highlights
- natural skin tones
- moderate contrast
- restrained saturation
- soft depth of field
- authentic lighting

---

# 10. Iconography

Use one coherent icon family.

Recommended:

**Lucide**

Rules:

- 1.5px stroke
- geometric/simple forms
- icons support text; they do not replace it
- no emoji as interface icons
- no mixed icon libraries

---

# 11. Navigation

Desktop:

- logo left
- primary navigation centered/right
- appointment CTA right
- restrained spacing

Recommended navigation:

- Treatments
- About
- FAQs
- Contact

Primary CTA:

**Book an appointment**

Mobile:

- simple menu button
- full-screen or large accessible menu
- CTA remains obvious
- no complex mega menu

Header should remain visually light.

---

# 12. Homepage Direction

## Section 01 — Hero

Background: Porcelain.

Composition:

- 5 columns text
- 7 columns photography
- generous vertical whitespace

Copy direction:

> Dentistry designed around you.

Supporting copy:

> Thoughtful dental care, modern dentistry and a more comfortable experience.

CTA:

> Book an appointment →

Do not use a giant tooth illustration.

---

## Section 02 — Trust strip

A quiet horizontal band.

Possible real metrics:

- years of experience
- patient rating
- patients cared for
- treatment areas

Only publish verifiable numbers.

If no legitimate metrics exist, use principles instead:

> Experienced care · Modern dentistry · Patient first · Comfortable visits

---

## Section 03 — Brand story

Large image + editorial copy.

Headline direction:

> A better kind of dental experience.

Keep copy human and specific.

---

## Section 04 — Treatments

Use an editorial treatment index instead of generic cards.

Example:

```text
01  Preventive Care                 →
02  Restorative Dentistry           →
03  Cosmetic Dentistry              →
04  Pediatric Dentistry             →
05  Orthodontics                    →
```

On desktop hover/focus, a relevant image may appear.

The interaction must remain usable without hover.

---

## Section 05 — Dentist / team

Large portrait + philosophy.

Headline direction:

> A dentist who takes time to listen.

Show:

- name
- credentials
- areas of expertise
- short personal philosophy

Avoid exaggerated claims.

---

## Section 06 — Patient stories

Large quote.

Example structure:

```text
“[Authentic patient quote]”

— Patient name, where permission exists

01 / 05
```

Do not fabricate reviews.

Do not publish patient identity or photography without appropriate permission.

---

## Section 07 — FAQ

Simple typography + dividers.

Example:

```text
Do you accept new patients?                  +
What should I bring to my first visit?       +
How often should I have a checkup?           +
Do you treat children?                       +
```

No bulky accordion cards.

---

## Section 08 — Appointment CTA

Background: Ink Deep.

Headline:

> Ready when you are.

Supporting copy:

> Tell us a little about what you need and our team will help you find the right next step.

CTA:

> Book an appointment →

---

# 13. Treatment Page

Recommended hierarchy:

1. Breadcrumb
2. Treatment title
3. Short introduction
4. Hero image
5. What it involves
6. Who it is for
7. What to expect
8. FAQs
9. Related treatments
10. Appointment CTA

Do not make treatment pages thin SEO landing pages.

Each page should genuinely help a patient understand the treatment.

---

# 14. FAQ Component

Structure:

- question
- answer
- divider
- open/close control

Accessibility:

- button semantics
- `aria-expanded`
- keyboard accessible
- focus-visible
- reduced-motion support

FAQ schema must only represent content actually visible to users.

---

# 15. Appointment Form

Fields should remain minimal.

Recommended:

- Name
- Email
- Phone
- Preferred date
- Preferred time
- Treatment / reason for visit
- Optional short message

Explicitly discourage sensitive medical history in the free-text field.

Security requirements:

- WordPress nonce
- server-side validation
- sanitization
- capability-independent public processing
- honeypot
- rate limiting
- strict date/time validation
- safe mail handling
- generic failure messages
- no sensitive medical data in logs

Use authenticated SMTP/transactional email in production.

---

# 16. Motion

Motion is subtle and functional.

### Reveal

- opacity: `0 → 1`
- translateY: `16px → 0`
- duration: `500–700ms`

### Hover

- `150–250ms`

### Image reveal

Use restrained clip/mask transitions.

### Rules

- no scroll-jacking
- no mandatory animation
- no excessive parallax
- no bouncing UI
- no animation on every element
- respect `prefers-reduced-motion`

---

# 17. Accessibility

Target:

**WCAG 2.2 AA**

Required:

- semantic HTML
- logical heading hierarchy
- visible keyboard focus
- accessible labels
- adequate contrast
- minimum touch target approximately 44px
- reduced-motion support
- descriptive image alt text
- no color-only information
- keyboard-accessible accordions and menus
- accessible error/success messaging

Accessibility is part of the product quality, not a final QA pass.

---

# 18. Responsive Behavior

## Desktop ≥ 1200px

- 12-column grid
- 1280px max content
- large editorial type
- hover interactions allowed
- generous section spacing

## Tablet 768–1199px

- reduce type by approximately 10–15%
- 2-column compositions where useful
- collapse complex 12-column layouts
- retain generous whitespace

## Mobile < 768px

- single-column layout
- 20px page padding
- 40–48px headline sizes
- 48–64px section spacing
- no hover-only interactions
- horizontal overflow prohibited
- images remain prominent
- sticky appointment CTA may be considered, but only if it does not obstruct content

---

# 19. WordPress Architecture

## Brittos Core plugin owns

- Treatment CPT
- FAQ CPT
- Testimonial CPT
- Treatment taxonomy
- Clinic settings
- Appointment processing
- Schema/data logic
- Data validation

## Britto's Dentistry theme owns

- Header
- Footer
- Templates
- Template parts
- Patterns
- Presentation
- Typography
- Colors
- Layout
- Motion
- Responsive styling

### Rule

The plugin must not depend on:

- theme directory names
- theme-specific files
- theme-specific CSS
- theme-specific image paths
- theme functions

The theme may consume plugin data, but the plugin must remain presentation-independent.

---

# 20. WordPress Editor Governance

This is a client site, so do not expose unlimited design freedom.

The editor should primarily expose the curated:

- color palette
- typography scale
- spacing scale
- layout widths
- border radius
- approved blocks

Avoid letting editors create arbitrary brand-breaking colors and typography.

Use `theme.json` as the design-system source of truth.

WordPress' current theme documentation supports configuring these global settings and styles through `theme.json`. The current reference documents Version 3 as the latest schema, and WordPress 7.1 adds further theme.json capabilities. See the official reference before using newly introduced properties. 

---

# 21. Component Inventory

### Global

- Header
- Mobile navigation
- Footer
- Button
- Text link
- Breadcrumb
- Section heading
- Divider

### Content

- Treatment index item
- Treatment card
- Testimonial
- FAQ item
- Doctor profile
- Trust metric
- Image/text editorial block

### Conversion

- Appointment CTA
- Appointment form
- Contact details
- Phone CTA

### Decorative

- Smile Curve
- Number marker
- Image frame

Do not create components merely because a visual element exists once.

---

# 22. Design Anti-Patterns

Never introduce these without an explicit design review:

- generic dental blue gradient
- giant tooth graphics
- emoji icons
- excessive pills
- excessive cards
- excessive shadows
- glassmorphism
- stock-photo dentist clichés
- autoplay video with sound
- huge text blocks
- scroll hijacking
- gratuitous 3D
- noisy animated backgrounds
- fake testimonials
- fabricated statistics
- medical claims without evidence
- inaccessible hover-only content

---

# 23. Performance Rules

Target:

- excellent Core Web Vitals
- minimal JavaScript
- no frontend framework unless demonstrably necessary
- lazy-load below-the-fold images
- responsive images using WordPress image functions
- modern image formats where appropriate
- locally hosted fonts
- preload only genuinely critical assets
- avoid render-blocking third-party scripts
- no unnecessary icon libraries
- no large animation libraries

The site should feel fast before it feels impressive.

---

# 24. Implementation Rules for Claude Code

1. Treat this document as the source of truth.
2. Do not invent a second design system.
3. Do not introduce a generic dental template.
4. Do not change the color palette without explicit approval.
5. Do not add a new font without explicit approval.
6. Use WordPress core blocks where they are sufficient.
7. Use custom patterns before custom blocks.
8. Use vanilla JavaScript for small interactions.
9. Keep business logic in Brittos Core.
10. Keep visual logic in the theme.
11. Do not hard-code clinic data into templates.
12. Do not fabricate content, statistics or testimonials.
13. Preserve WCAG 2.2 AA requirements.
14. Respect `prefers-reduced-motion`.
15. Test mobile layouts before considering a component complete.
16. Prefer fewer, stronger components over a large component library.
17. Keep the design system token-driven through `theme.json`.
18. Validate `theme.json` against the current WordPress schema before committing.
19. Do not use deprecated WordPress APIs.
20. Target PHP 8.4.

---

# 25. Definition of Done

A page is not complete until:

- desktop is visually coherent
- tablet is coherent
- mobile is coherent
- keyboard navigation works
- focus states are visible
- forms are accessible
- content does not overflow horizontally
- images have appropriate dimensions/alt text
- animations respect reduced motion
- colors meet contrast requirements
- no console errors exist
- no PHP warnings/notices exist
- no unnecessary JavaScript is loaded
- WordPress editor preview reasonably matches frontend
- no theme-specific assumptions exist in the plugin
- design tokens are reused instead of duplicated

---

## Final design principle

**Make Britto's Dentistry feel expensive because it is considered — not because it is decorated.**

Whitespace, typography, photography, restraint, accessibility and interaction quality should do most of the work.
