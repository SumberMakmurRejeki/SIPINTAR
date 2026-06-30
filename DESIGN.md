# SI-PINTAR Design System

## 1. Atmosphere & Identity

SI-PINTAR feels like a calm, disciplined training gateway: bright, precise, and professional without looking sterile. The signature is a split-screen login with a softly illustrated training scene on the left and a clean white form card on the right, using one strong blue accent and quiet supporting color washes.

## 2. Color

### Palette

| Role | Token | Light | Usage |
|------|-------|-------|-------|
| Surface/primary | --surface-primary | #FFFFFF | Main page background |
| Surface/secondary | --surface-secondary | #F8FAFC | Secondary panel background |
| Surface/elevated | --surface-elevated | #FFFFFF | Cards, form surfaces |
| Text/primary | --text-primary | #080808 | Headings, key labels, CTA text |
| Text/secondary | --text-secondary | #5A5A5A | Descriptions, helper text |
| Text/muted | --text-muted | #94A3B8 | Secondary metadata, placeholders |
| Border/default | --border-default | #D8D8D8 | Inputs, panels, dividers |
| Border/subtle | --border-subtle | #EEF2F7 | Soft separations |
| Accent/primary | --accent-primary | #146EF5 | CTA, focus, links |
| Accent/hover | --accent-hover | #0F5CE0 | Hover state for interactive elements |
| Status/success | --status-success | #00A65A | Success status |
| Status/warning | --status-warning | #FFAE13 | Attention states |
| Status/error | --status-error | #EE1D36 | Validation errors |
| Support/blue | --support-blue | #2F6BFF | Illustration only |
| Support/purple | --support-purple | #7C5CFF | Illustration only |
| Support/green | --support-green | #31B36B | Illustration only |
| Support/orange | --support-orange | #F59E0B | Illustration only |
| Tint/blue-soft | --tint-blue-soft | #DBEAFE | Illustration only |
| Tint/green-soft | --tint-green-soft | #DCFCE7 | Illustration only |
| Tint/orange-soft | --tint-orange-soft | #FFEDD5 | Illustration only |

### Rules

- Use one interactive accent color on the page: blue.
- Supporting blue, purple, green, and orange are decorative only.
- Backgrounds stay white or near-white; no dark mode on this surface.

## 3. Typography

### Scale

| Level | Size | Weight | Usage |
|-------|------|--------|-------|
| Display | 40px / 2.5rem | 700 | Hero brand title |
| H1 | 32px / 2rem | 700 | Form title |
| H2 | 24px / 1.5rem | 600 | Section labels |
| Body | 16px / 1rem | 400 | Default text |
| Body/sm | 14px / 0.875rem | 400 | Helper text, form notes |
| Caption | 12px / 0.75rem | 500 | Micro labels |

### Font Stack

- Primary: Inter, system-ui, sans-serif

### Rules

- Keep body copy at 14px or larger.
- Use tight tracking only on big titles.
- Labels sit above inputs; placeholder text never replaces labels.

## 4. Spacing & Layout

### Base Unit

All spacing derives from a base of **4px**.

| Token | Value | Usage |
|-------|-------|-------|
| --space-1 | 4px | Fine adjustments |
| --space-2 | 8px | Icon gaps, compact rows |
| --space-3 | 12px | Tight field spacing |
| --space-4 | 16px | Standard inner spacing |
| --space-5 | 20px | Mobile card padding |
| --space-6 | 24px | Section spacing |
| --space-8 | 32px | Desktop card padding |
| --space-10 | 40px | Generous page spacing |
| --space-12 | 48px | Major section breaks |
| --space-16 | 64px | Large layout separation |

### Grid

- Max content width: 1440px
- Desktop split: 11-column grid, 6 columns left / 5 columns right
- Breakpoints: sm 640px, md 768px, lg 1024px, xl 1280px, 2xl 1536px

### Rules

- Use `min-h-[100dvh]` for full-screen auth pages.
- Prefer grid for the split layout; avoid flex percentage math.
- Keep the mobile stack explicit in the markup.

## 5. Components

### Split Login Shell
- **Structure**: left brand/illustration panel + right form panel
- **Variants**: desktop split, mobile stack
- **Spacing**: 32px to 48px outer padding, 24px internal gaps
- **States**: default, responsive collapse
- **Accessibility**: semantic sectioning, readable contrast

### Icon-Led Form Field
- **Structure**: label, leading icon, input, optional trailing action
- **Variants**: username, password
- **Spacing**: 12px label gap, 16px field padding
- **States**: default, hover, focus, error, disabled
- **Accessibility**: label association, visible focus ring, keyboard support

### Feature Chip Row
- **Structure**: icon tile + short label
- **Variants**: blue, purple, green
- **Spacing**: 16px icon tile, 12px text gap
- **States**: default
- **Accessibility**: descriptive copy, no icon-only meaning

### Error Banner
- **Structure**: inline message card with icon and short text
- **Variants**: error, success
- **Spacing**: 16px padding, 12px icon/text gap
- **States**: visible when status or validation exists
- **Accessibility**: `role="alert"` for errors

## 6. Motion & Interaction

### Timing

| Type | Duration | Usage |
|------|----------|-------|
| Micro | 120-150ms | Button hover, password toggle |
| Standard | 200-300ms | Focus, banner appearance |

### Rules

- Animate only `transform` and `opacity`.
- Buttons and toggles always show hover, focus, and active feedback.
- Loading state swaps the submit label to a progress message and disables repeat submit.

## 7. Depth & Surface

### Strategy

**mixed** — hairline borders for structure, with a very soft shadow on the login card.

### Rules

- Page backgrounds stay flat and bright.
- The right form card uses border + soft shadow.
- Decorative background washes stay subtle and non-interactive.
