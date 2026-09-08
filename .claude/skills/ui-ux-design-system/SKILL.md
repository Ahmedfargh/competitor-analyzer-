---
name: ui-ux-design-system
description: "High-end UI/UX design systems, modern aesthetics, responsive layouts, and visual hierarchy for Laravel applications. Use when designing web interfaces, dashboards, design tokens, color systems (OKLCH/HSL), dark mode, typography scales, micro-interactions, loading skeletons, empty states, and tenant branding."
license: MIT
metadata:
  author: laravel
---

# UI/UX Design System & Modern Aesthetics

This skill defines the standards and practices for delivering polished, visually compelling, accessible, and user-centric interfaces.

## Aesthetic Philosophy & Design Standards

1. **Avoid Generic Defaults**: Do not use raw, uncalibrated primary colors (pure red, blue, green). Use refined, harmonious color palettes (Tailwind slate/zinc neutrals, tailored brand tints in OKLCH or HSL).
2. **Visual Hierarchy**: Every page must have an undeniable hierarchy:
   - Primary focal point (KPI stat, hero title, main action button).
   - Secondary supporting details (subtitles, secondary metrics, meta tags).
   - Tertiary metadata (timestamps, badges, breadcrumbs).
3. **Spacing & Rhythm**: Strictly adhere to the 4px / 8px geometric spacing scale (`space-y-4`, `p-6`, `gap-8`). Never use arbitrary ad-hoc margins without alignment.
4. **Typography**: Pair clean, modern sans-serif typography (Inter, Outfit, Geist, Plus Jakarta Sans). Use crisp font weights: `font-semibold` or `font-bold` for headings, `font-medium` for interactive controls/labels, and `font-normal` for body copy.

---

## Color Systems & Dark Mode

- **Base Colors**: Define semantic color tokens using CSS variables or Tailwind v4 `@theme`:
  ```css
  @theme {
    --color-surface-light: #ffffff;
    --color-surface-dark: #0f172a;
    --color-brand-500: oklch(0.65 0.22 250);
  }
  ```
- **Dark Mode Support**: Always consider dark variants for dashboards and enterprise views:
  - Backgrounds: `bg-white dark:bg-slate-900`
  - Cards/Containers: `bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60`
  - Text: `text-slate-900 dark:text-slate-100` and muted `text-slate-500 dark:text-slate-400`
- **Glassmorphism & Depth**: Use subtle backdrop blurs and rings rather than harsh heavy drop-shadows:
  - `backdrop-blur-md bg-white/80 dark:bg-slate-900/80 border border-white/20 shadow-sm shadow-black/5`

---

## Layouts & Navigation

1. **Enterprise Dashboards**:
   - Fixed or sticky header bar with search, tenant switcher, notifications, and user avatar.
   - Collapsible, accessible sidebar with grouped navigation items, active indicator accents, and badge counters.
   - Responsive drawer for mobile viewports.
2. **Data Tables & Cards**:
   - Header with action bar (search, filters, bulk actions, primary CTA).
   - Alternating subtle rows or clean dividers (`divide-y divide-slate-200 dark:divide-slate-800`).
   - Paginated footer showing total count, page size, and clean pagination controls.

---

## State Management in UI

Every interactive component and page must gracefully handle all four UI states:

1. **Loading State**:
   - Provide pulsing skeleton loaders matching the exact shape of incoming data (`animate-pulse bg-slate-200 dark:bg-slate-700 rounded`).
   - Never leave a blank blank white screen or an unstyled generic spinner.
2. **Empty State**:
   - Include a contextual SVG illustration or icon.
   - A clear, friendly headline ("No orders found yet").
   - Explanatory copy and a primary action button ("Create your first order").
3. **Error State**:
   - Inline field error messages with distinct red tint and alert icons.
   - Graceful fallback banners for network or server errors.
4. **Success / Feedback State**:
   - Toast notifications with subtle auto-dismiss animations.
   - Optimistic UI updates with micro-animations.

---

## Multi-Tenant White-Labeling & Theming

For multi-tenant SaaS applications:
- Extract brand accent colors into CSS custom properties (`var(--tenant-primary-color)`).
- Dynamically inject the tenant's primary hex/oklch code in the root layout `<style>` tag.
- Ensure all buttons, active tab indicators, and badge accents leverage the dynamic variable or class utilities.
