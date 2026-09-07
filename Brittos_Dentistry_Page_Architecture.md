# Britto's Dentistry — Page Architecture & Implementation Specification

**Target:** WordPress 7.1 + PHP 8.4  
**Architecture:** WordPress Block Theme + Brittos Core plugin + controlled Gutenberg content  
**Primary public destinations:** Home, About, Treatments, Contact

## 1. Purpose

This document is the implementation contract for the public page architecture of Britto's Dentistry.

The goal is a website that is high-performance, accessibility-first, SEO-rich without spam, secure by default, excellent on Core Web Vitals/Lighthouse, modern and interactive, clinically trustworthy, distinctive, maintainable, and WordPress-native.

Brand principle:

> **Quiet confidence. Modern dentistry. Human care.**

Because this is a **single-dentist clinic**, the information architecture is intentionally compact. Do not create unnecessary pages simply to make the website appear larger.

## 2. Public Information Architecture

```text
/
├── /about/
├── /treatments/
└── /contact/
```

Primary navigation:

```text
BRITTO'S

Treatments
About
Contact

                         Book an appointment
```

Do not create separate Team, Testimonials, Reviews, Services, Mission, Philosophy, Doctor, or Resources pages unless real content or future requirements justify them.

## 3. Responsibility Boundaries

### Brittos Core plugin owns data and functionality

- Treatment CPT
- Treatment taxonomies
- Treatment metadata
- FAQ data/relationships where structured data is needed
- Related-treatment relationships
- Dynamic functionality
- Form processing/integration logic
- Security-sensitive server-side logic
- Validation and sanitization
- Required REST/API registration

The plugin must not contain presentation-specific styling.

### Britto's Dentistry theme owns presentation

- Templates
- Template parts
- Patterns
- `theme.json`
- CSS
- Typography
- Responsive layout
- Components
- Light/Dark mode
- Animation/interaction
- Accessibility presentation
- Semantic page composition

The theme must not become the data layer.

## 4. Hybrid Theme + Gutenberg Model

Use Gutenberg as the native content system, but do not turn the site into a free-form page builder.

Guideline:

```text
70–80% Theme-controlled structure
20–30% Gutenberg-controlled editorial content
```

### Theme controls

- Page hierarchy
- Header/navigation/footer
- Hero structure
- Section spacing and containers
- Typography/colors
- Dark/Light mode
- Buttons
- Treatment cards/facts
- FAQ presentation
- Related treatments
- Appointment CTA
- Responsive behavior
- Motion/focus/image treatment

### Gutenberg controls

Primarily genuine editorial content:

- Paragraphs
- Headings
- Lists
- Quotes
- Supporting images
- Simple editorial groups where useful

Do not allow arbitrary redesign of the brand structure.

---

# 5. Homepage `/`

## Purpose

Answer:

> **Why should I trust Dr. Britto's Dentistry?**

Introduce the clinic, establish trust, show selected treatments, communicate the dentist's approach, and provide a clear appointment path.

Recommended structure:

```text
Header
↓
Hero
↓
Clinic introduction
↓
Featured treatments
↓
Why Britto's
↓
Dentist introduction
↓
Patient reviews
↓
Appointment CTA
↓
Footer
```

### Hero

The Hero is a theme-controlled, full-bleed visual experience designed to establish the
brand immediately while remaining performant, accessible, and resilient.

Theme-controlled:

- Eyebrow
- H1
- Short supporting statement
- Primary CTA
- Optional secondary link
- Hero visual system

The Hero visual system must support:

- Optimized background image
- Optional atmospheric background video
- Poster/fallback image
- Mobile-specific image where appropriate
- Graceful fallback when video cannot or should not play

#### Background Video

Video is progressive enhancement, never a requirement for the Hero experience.

If used:

- Video must be short, subtle, and atmospheric rather than promotional
- `muted`
- `autoplay`
- `loop`
- `playsinline`
- No audio
- No video controls
- No video-dependent content
- Prefer compressed modern formats
- Keep the video payload aggressively optimized
- Avoid 4K or unnecessarily high-resolution video
- Do not block initial rendering while waiting for video
- The poster/fallback image must provide the complete Hero experience
- Do not require JavaScript to display the Hero
- Respect `prefers-reduced-motion`
- When reduced motion is requested, use the static poster/fallback image instead
- If video fails to load, the Hero must seamlessly remain an image-based Hero

#### Responsive Visual Strategy

Desktop may use:

```text
Optimized video
        ↓
Poster image fallback


### Clinic introduction

Short explanation of who Britto's is and its care approach.

Theme controls composition; Gutenberg may control editorial copy.

### Featured treatments

Query Treatment CPT entries. Show approximately 4–6 curated treatments.

Theme controls cards and interaction. Never hard-code treatment names.

### Why Dr. Britto's

Use verified, meaningful differentiators such as thoughtful care, communication, modern clinical approach, comfort, or continuity of care.

Avoid generic medical icon grids and invented claims.

### Dentist introduction

Brief introduction with portrait, name, role, verified credentials/experience, and link to `/about/`.

No separate Team page.

### Patient reviews

Reviews belong on the homepage as trust signals.

Do not fabricate reviews or misleadingly manipulate them. Prefer server-rendered/static content where practical. Link to the external review source where appropriate.

### Appointment CTA

End with a calm, prominent CTA:

> **Book an appointment**

Recommended target:

`/contact/#appointment`

---

# 6. About `/about/`

## Purpose

Answer:

> **Who is behind Britto's Dentistry, and how does the clinic approach care?**

Combine clinic and dentist information.

Recommended structure:

```text
Header
↓
About Hero
↓
The Clinic
↓
The Dentist
↓
Approach / Philosophy
↓
Credentials / Experience
↓
Clinic Photography Gallery
↓
Appointment CTA
↓
Footer
```

### The Clinic

Gutenberg-controlled editorial content:

- Clinic story
- Patient expectations
- Care philosophy
- Experience

Avoid marketing clichés.

### The Dentist

Hybrid:

Structured:
- Name
- Role
- Verified credentials
- Experience
- Profile image

Editorial:
- Biography
- Professional philosophy
- Areas of interest
- Patient-care approach

Only publish verified claims.

### Credentials / experience

Do not invent statistics, awards, certifications, years, or qualifications.

### Photography

Prefer authentic clinic/dentist photography. Use correct alt text and responsive optimized images.

---

# 7. Treatments `/treatments/`

## Purpose

Answer:

> **What does Britto's Dentistry offer, and what does each treatment mean for the patient?**

The Treatments page is the primary service-discovery and educational destination of the website.

Because Britto's Dentistry is a single-dentist clinic, the initial public experience should remain intentionally compact.

Initially, all treatments (specifically Category-wise) should live on one consolidated public page:

```text
/treatments/

## Treatment data model

Conceptually:

```text
Treatment
├── Title
├── Short description
├── Featured image
├── Treatment category
├── Typical visits
├── Duration
├── Recovery / aftercare summary
├── Editorial content
├── FAQs
├── Before & After cases
├── Related treatments
└── Featured flag
```

Only implement fields with genuine value. Do not build a page-builder-like field system.

## Recommended page structure

```text
Header
↓
Treatment Hero
↓
Treatment category navigation
↓
Preventive Dentistry
    ↓
    Treatment entries
↓
Restorative Dentistry
    ↓
    Treatment entries
↓
Cosmetic Dentistry
    ↓
    Treatment entries
↓

Selected Before & After results
↓
Treatment FAQs
↓
Appointment CTA
↓
Footer
```

### Treatment Hero

Theme-controlled:

- Treatment name
- Short description
- Optional category
- Optional image
- Appointment CTA

### Treatment navigation

Provide an accessible way to move between treatment categories/sections. Only use categories that reflect real clinic offerings.

### Treatment content

Gutenberg-controlled editorial content:

- What the treatment is
- When it may be recommended
- What patients can expect
- Preparation
- Aftercare
- Important considerations

Theme controls presentation.

### Treatment facts

Structured metadata rendered consistently by the theme:

```text
Typical visits
1–3

Duration
Varies

Recovery
Treatment dependent
```

Never invent values. Omit empty fields.

### FAQs

Treatment-specific. Use semantic disclosure/accordion patterns with keyboard accessibility.

Use structured data only when current search-engine guidelines permit it and the visible content genuinely supports it. Do not generate schema merely to chase rich results.

### Future-proofing

Keep `single-treatment.html` available architecturally if useful, but do not expose individual treatment URLs until content volume or search intent justifies them.

If the treatment catalog becomes large, the same CPT model can later support:

```text
/treatments/root-canal/
/treatments/dental-implants/
```

without rebuilding the content model.

---

# 8. Contact `/contact/`

## Purpose

Answer:

> **How do I reach Britto's Dentistry or request an appointment?**

Recommended structure:

```text
Header
↓
Contact Hero
↓
Contact information
↓
Location / map
↓
Opening hours
↓
Appointment form
↓
What happens next
↓
Footer
```

### Contact information

- Address
- Phone
- Email if applicable
- Opening hours

Use semantic links such as `tel:` and `mailto:`.

### Map

Avoid loading a heavy third-party map on initial load.

Prefer a lightweight preview/static map and load the interactive map only after user interaction if needed.

### Appointment form

Collect the minimum necessary information, for example:

- Name
- Phone/email
- Preferred appointment date/time
- Treatment/concern
- Message

Do not collect sensitive medical information unless there is a justified, secure requirement.

### Form security

Server-side:

- Validate every field
- Sanitize input
- Use nonces
- Apply spam/rate limiting
- Never trust client-side validation
- Escape output
- Avoid unnecessary storage
- Avoid logging sensitive patient information

Progressively enhance; the basic form should remain usable without JavaScript where practical.

---

# 9. Global Header

Recommended:

```text
Logo
Treatments
About
Contact
Book an appointment
Theme toggle
```

Mobile:

```text
Logo
Theme toggle
Menu
```

Requirements:

- Semantic header/nav
- Skip link
- Keyboard accessible
- Visible focus
- No layout shift
- No unnecessary JS
- Appointment CTA prominent but restrained

---

# 10. Global Footer

Include:

- Clinic identity
- Address
- Phone
- Opening hours
- Primary navigation
- Appointment CTA
- Privacy/legal links as required
- Copyright

Avoid excessive navigation.

---

# 11. Light / Dark Mode

Support explicit:

```text
Light
Dark
```

Storage:

```text
localStorage key: brittos-theme
allowed values: light | dark
```

First visit respects `prefers-color-scheme`.

Once explicitly selected, stored preference wins over future OS changes.

## No FOUC / flicker

Resolve the theme before visual paint:

1. Read localStorage.
2. Accept only `light` or `dark`.
3. Otherwise inspect `prefers-color-scheme`.
4. Set `html[data-theme]` immediately.
5. Then allow the visual theme to render.

Do not wait for `DOMContentLoaded`.

Keep the bootstrap tiny and synchronous.

Use semantic variables for brand directions and design system(colors)
```

Dark Mode must be a carefully designed warm palette, not an inversion of Light Mode.

---

# 12. Typography

Use locally hosted variable WOFF2 fonts.

### Headings / display

**Instrument Sans**

### Body / UI

**DM Sans**

Use the existing variable WOFF2 files:

```text
instrument-sans-latin-wght-normal.woff2
instrument-sans-latin-wght-italic.woff2

dm-sans-latin-wght-normal.woff2
dm-sans-latin-wght-italic.woff2
```

Do not use Google Fonts.

Do not load italic files unless actually required.

Use `font-weight` for the variable weight axis.

---

# 13. Performance Architecture

Performance is a first-class requirement.

Target:

- Excellent Lighthouse scores
- Excellent Core Web Vitals
- Minimal JS
- Minimal third-party resources
- Minimal CSS
- Optimized images
- Stable layout

## JavaScript

Default to no JS unless necessary.

Use small vanilla JS for:

- Mobile menu
- Theme toggle
- Necessary disclosure interactions
- Progressive enhancement

Do not add jQuery, React, Vue, Alpine, or animation libraries without a compelling requirement.

## Images

Use WordPress image APIs.

- Responsive sizes
- Correct dimensions
- Modern formats where appropriate
- Lazy-load below fold
- Do not lazy-load true LCP image
- Explicit dimensions
- Meaningful alt text

## Fonts

- Local WOFF2
- Load only necessary variants
- `font-display: swap`
- Preload only genuinely critical fonts after measurement

## Third-party resources

Treat third-party scripts as expensive.

Avoid or defer maps, analytics, social/review widgets, chat, and marketing trackers unless justified.

---

# 14. Accessibility

Target:

**WCAG 2.2 AA**

Required:

- Semantic HTML
- Correct heading hierarchy
- Keyboard navigation
- Visible focus
- Skip link
- Accessible mobile navigation
- Accessible theme toggle
- Proper form labels/errors
- Sufficient contrast
- Reduced-motion support
- Meaningful alt text
- Correct link/button semantics
- No keyboard traps

Prefer native HTML semantics over unnecessary ARIA.

---

# 15. SEO Architecture

SEO should follow excellent information architecture and useful content, not keyword stuffing.

### Homepage

Broad local/brand intent.

### About

Clinic identity, dentist identity, credentials, trust, local relevance.

### Treatments

Treatment-related informational intent with original, clinically accurate content.

### Contact

Strong local/business intent.

Structured data may include appropriate organization/local-business/dentist information, address, hours and contact details.

Only output schema representing accurate, visible, verifiable information.

Never create fake reviews, ratings, services, credentials, or medical claims.

---

# 16. Security

Assume hostile input.

### PHP

- Sanitize
- Validate
- Escape
- Nonces for state-changing requests
- Capability checks
- `$wpdb->prepare()` for required database queries
- Minimize REST exposure
- Protect private metadata
- Avoid unsafe file operations

### Frontend

- No unsafe `innerHTML`
- No arbitrary localStorage injection
- No unsanitized query-string rendering
- No secrets in JS

---

# 17. Interaction Design

The site should feel interactive without feeling like an application.

Prefer:

- Subtle hover states
- Clear focus transitions
- Minimal image movement
- Restrained reveal animations
- Elegant mobile navigation
- Theme transition

Avoid:

- Scroll-jacking
- Heavy parallax
- Cursor effects
- Animated gradients
- Continuous background motion
- Loading screens
- Fake page transitions
- Excessive glassmorphism

Interaction should communicate confidence, not spectacle.

---

# 18. Design Language

The brand should remain coherent across every page and mode.

Core characteristics:

- Warm neutrals
- Champagne accent
- Sage secondary accent
- Deep ink
- Generous whitespace
- Editorial typography
- Restrained borders
- Subtle elevation
- Authentic photography
- Strong hierarchy

Avoid:

- Generic dental blue
- Stock-template appearance
- Excessive rounded cards/pills
- Giant tooth illustrations
- Generic medical iconography
- Noisy gradients
- Futuristic medical clichés

---

# 19. Suggested Theme Structure

Adapt this to the existing repository; do not create duplicate architecture blindly.

```text
themes/brittos-dentistry/
│
├── assets/
│   ├── fonts/
│   ├── images/
│   ├── css/
│   └── js/
│
├── parts/
│   ├── header.html
│   └── footer.html
│
├── patterns/
│   ├── hero
│   ├── featured-treatments
│   ├── clinic-intro
│   ├── dentist-intro
│   ├── reviews
│   ├── appointment-cta
│   ├── treatment-hero
│   ├── treatment-facts
│   ├── treatment-faq
│   └── contact-form
│
├── templates/
│   ├── index.html
│   ├── front-page.html
│   ├── page.html
│   ├── single-treatment.html
│   ├── archive-treatment.html
│   └── 404.html
│
├── theme.json
├── style.css
└── functions.php
```

Adjust based on actual project structure.

---

# 20. Template Strategy

Homepage:

```text
front-page.html
```

About/contact:

Use `page.html` unless a genuinely distinct template is required.

Treatments:

```text
archive-treatment.html
```

Individual Treatment:

```text
single-treatment.html
```

Keep individual treatment capability future-ready but do not force separate public pages prematurely.

---

# 21. Gutenberg Rules

The editor should see a curated experience.

Prefer:

- Core blocks
- Theme patterns
- Locked structural patterns
- Restricted color palette
- Restricted typography
- Theme-provided spacing
- Semantic content

Avoid:

- Page-builder behavior
- Arbitrary colors
- Arbitrary font sizes
- Arbitrary negative margins
- Inline styling
- Excessive custom blocks

Create a custom dynamic block only when a reusable component genuinely requires dynamic data/functionality.

---

# 22. Content Accuracy

Never invent:

- Medical claims
- Treatment outcomes
- Duration/recovery
- Qualifications
- Awards
- Years of experience
- Reviews
- Statistics
- Facilities
- Technologies
- Certifications

Use placeholders or explicit content requirements until verified information is supplied by the clinic.

---

# 23. Mobile-first

Design and test mobile first.

Check:

- Header
- Navigation
- Hero
- Treatment navigation
- Content sections
- Forms
- Reviews
- CTA
- Footer
- Light/Dark mode
- Touch targets

No essential interaction may depend on hover.

---

# 24. Lighthouse / QA Definition of Done

Do not consider a high Lighthouse score sufficient by itself.

## Performance

Check:

- LCP
- CLS
- INP
- TTFB
- image payload
- CSS payload
- JS payload
- third-party requests

## Accessibility

Check:

- Lighthouse
- keyboard-only
- screen-reader spot checks
- contrast
- focus
- reduced motion

## SEO

Check:

- crawlable URLs
- titles/descriptions
- canonical URLs
- sitemap
- structured data
- heading hierarchy
- internal links
- alt text

## Security

Check:

- form abuse
- nonce validation
- capability checks
- sanitization/escaping
- REST exposure
- malformed input
- invalid localStorage values

## Visual

Test:

```text
Light + Dark
Desktop + Tablet + Mobile
```

Look for contrast problems, broken hierarchy, incorrect form states, accidental light-mode colors in dark mode, image issues, layout shifts, and excessive animation.

---

# 25. Implementation Sequence

### Phase 1 — Foundation

- Repository audit
- WordPress/PHP compatibility
- Plugin/theme boundaries
- Coding standards
- Asset architecture

### Phase 2 — Design System

- `theme.json`
- semantic colors
- typography
- spacing
- buttons
- forms
- focus styles

### Phase 3 — Theme Modes

- Light
- Dark
- no FOUC
- localStorage
- system preference
- reduced motion

### Phase 4 — Global Shell

- Header
- Navigation
- Footer
- Mobile menu
- Containers
- Skip link

### Phase 5 — Homepage

- Hero
- Introduction
- Featured treatments
- Why Britto's
- Dentist
- Reviews
- CTA

### Phase 6 — Treatment System

- Treatment CPT
- metadata
- taxonomy
- consolidated Treatments page
- treatment content
- FAQ
- related treatments
- future-ready single-treatment architecture

### Phase 7 — About

- Clinic
- Dentist
- philosophy
- credentials
- photography

### Phase 8 — Contact

- Contact information
- Location
- Hours
- Appointment form
- progressive map loading

### Phase 9 — Optimization

- Accessibility
- SEO
- Performance
- Security
- Responsive QA

### Phase 10 — Production

- Browser testing
- Lighthouse
- Core Web Vitals
- Security review
- Caching
- Deployment
- Backups
- Rollback

---

# 26. Agent Operating Rules

Any coding agent working on this project must:

1. Inspect existing code before modifying it.
2. Never replace working architecture without justification.
3. Keep plugin/theme responsibilities separate.
4. Prefer WordPress core functionality.
5. Prefer semantic HTML over ARIA-heavy solutions.
6. Prefer CSS over JavaScript.
7. Prefer progressive enhancement.
8. Avoid dependencies unless justified.
9. Never invent clinical content.
10. Never sacrifice accessibility for visual effects.
11. Never sacrifice performance for animation.
12. Never hard-code Treatment data into templates.
13. Never scatter raw colors through component CSS.
14. Never introduce another font system.
15. Never add unnecessary pages.
16. Test both Light and Dark modes.
17. Test mobile and desktop.
18. Validate server-side security independently of frontend validation.
19. Keep public HTML semantic and crawlable.
20. Document non-obvious architectural decisions.

---

# 27. Final Product Definition

The finished site should feel like:

> **A carefully designed modern dental practice — not a WordPress template.**

The intended balance is:

```text
Small information architecture
        +
Rich content quality
        +
Strong visual identity
        +
Minimal technical overhead
        +
Excellent accessibility
        +
Excellent performance
        +
Secure by default
        +
SEO-friendly architecture
```

The sophistication should come from typography, composition, spacing, photography, micro-interactions, content hierarchy, accessibility, performance, and technical quality — not unnecessary pages, plugins, animations, or visual effects.

**Treat this document as the page-architecture and implementation contract for Britto's Dentistry.**
