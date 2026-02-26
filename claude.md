# CCS Pro — AI Assistant Rules

Project rules and conventions for AI assistants working on this codebase.

---

## Read first

Before making any changes, read these files for full context:

1. `context.md` — project overview, repo structure, what was done, env vars
2. `requirements.md` — functional and non-functional requirements for all pages
3. `architecture.md` — technical architecture, routing, data flow, type system

---

## Project structure

- **Pages:** `src/pages/` — one file per route (HomePage, PricingPage, AboutPage, ContactPage, Index)
- **Section components:** `src/components/landing/` — presentational components used by pages
- **Archived components:** `src/components/landing/archived/` — removed from homepage but preserved for future use
- **Mock data:** `src/content/mockData.ts` — typed content for all new pages
- **Static fallback:** `src/content/landing.ts` — fallback for `/:slug` route when WordPress API fails
- **Types:** `src/types/wordpress.ts` — all TypeScript interfaces (legacy + new)
- **Icons:** `src/lib/landing-icons.ts` — maps string names to Lucide React components
- **API client:** `src/lib/wordpress.ts` — WordPress REST API functions
- **Hooks:** `src/hooks/useWordPress.ts` — React Query wrappers

---

## Tech stack

- React 18, TypeScript strict, Vite
- Tailwind CSS for styling (utility-first, no CSS modules)
- shadcn/ui components (Radix primitives) — imported from `@/components/ui/`
- Lucide React for icons
- React Router for client-side routing
- TanStack Query for async data fetching (legacy route only)

---

## Code conventions

### TypeScript

- All content structures must be typed — add interfaces to `src/types/wordpress.ts`.
- Mock data in `src/content/mockData.ts` must satisfy its declared type.
- Use explicit return types on exported functions.
- Import path alias: `@/` maps to `src/`.

### Components

- Section components live in `src/components/landing/`.
- Each section component accepts **both** a legacy `content?: LandingPageContent` prop (for `/:slug`) and new typed props (for new pages). Branch internally based on which prop is present.
- New page components live in `src/pages/` and assemble sections with mock data.
- Every page must include `<Header headerData={mockSiteSettings.header} />` and `<Footer footerData={mockSiteSettings.footer} />`.
- Every page must set `document.title` via `useEffect` (format: "Page Name | CCS Pro").

### Styling

- Use Tailwind utility classes directly in JSX.
- Color coding conventions: indigo tones for Provider-related UI, emerald tones for Group/Facility-related UI.
- Responsive: mobile-first. Use `sm:`, `md:`, `lg:` breakpoints. Test both layouts.
- For CTA blocks: `style: "indigo"` or `style: "emerald"` determines background color.

### Icons

- Icons referenced in mock data use string names (e.g. `"Shield"`, `"Clock"`).
- Map must exist in `src/lib/landing-icons.ts`. Add new Lucide icons there as needed.
- Use `getLandingIcon(name)` to resolve icon components at render time.

---

## Routing rules

- Named routes (`/pricing`, `/about`, `/contact`) must be defined **before** `/:slug` in `App.tsx` so they are not captured by the wildcard.
- The `/:slug` route must remain functional — it serves WordPress-driven landing pages.
- Internal navigation between new pages must use React Router `<Link>`, not `<a>`.
- Anchor links (e.g. `/#how-it-works`) use standard `<a>` tags.

---

## What NOT to change

Unless explicitly instructed:

- `src/lib/wordpress.ts` — API client
- `src/hooks/useWordPress.ts` — React Query hooks
- `src/content/landing.ts` — `defaultLandingPageContent` static fallback
- `src/content/providers/` — content provider abstraction
- `wordpress/mu-plugins/ccspro-cpt-acf.php` — WordPress MU-plugin
- `vercel.json` — SPA rewrite and cache headers
- Existing interfaces in `src/types/wordpress.ts` (add new ones, don't modify `LandingPageContent` or its children)

---

## Shell environment

- **OS:** Windows 10, PowerShell
- Use `;` not `&&` to chain shell commands (PowerShell does not support `&&` in all versions)
- Use `git mv` for moving files to preserve git history
- Run `npm run build` to verify TypeScript compilation after changes

---

## Implementation workflow

1. Read relevant files before editing.
2. Make changes one step at a time.
3. Confirm compilation (`npm run build`) after each meaningful step.
4. Check for linter errors after edits.
5. Do not batch unrelated changes.

---

## Phase status

- **Phase 1 (complete):** Four page templates with typed mock data, global Header/Footer, new routing, archived components, coming-soon flash fix.
- **Phase 2 (in progress):** MU-plugin patched for menu endpoints, global header/footer options pages, pricing v2 fields on `landing_page`, ecosystem section, tabbed how-it-works fields, extended `site-config`, admin edit-screen customization, and updated CORS.

---

## Data flow summary

```
New pages (/, /pricing, /about, /contact):
  Page component → imports from mockData.ts → passes typed props to sections

Legacy route (/:slug):
  Index.tsx → useLandingPage(slug) → WordPress API → LandingPageContent → sections
                                    ↓ (on failure)
                              landing.ts fallback
```
