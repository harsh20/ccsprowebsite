# CCS Pro — AI Rules

## Read first

1. `context.md` — project overview, repo structure, env vars, phase status
2. `requirements.md` — functional and non-functional requirements for all pages
3. `architecture.md` — technical architecture, data flow, routing, type system

---

## Quick reference

- **Tech:** React 18 + TypeScript + Vite + Tailwind CSS + shadcn/ui + React Router + TanStack Query
- **Path alias:** `@/` maps to `src/`
- **Pages:** `src/pages/` — one file per route
- **Section components:** `src/components/landing/`
- **Archived components:** `src/components/landing/archived/`
- **Types:** `src/types/wordpress.ts` — all TypeScript interfaces
- **Mock data:** `src/content/mockData.ts` | **Static fallback:** `src/content/landing.ts`
- **Icons:** `src/lib/landing-icons.ts` — string-name-to-Lucide map, use `getLandingIcon(name)`
- **API client:** `src/lib/wordpress.ts` | **Hooks:** `src/hooks/useWordPress.ts`

---

## Build & verify

```
npm run dev        # local dev server (port 8080)
npm run build      # verify TS compilation
npm run lint       # ESLint
```

---

## Do not change (without explicit instruction)

- `src/lib/wordpress.ts` — API client
- `src/content/landing.ts` — static fallback
- `wordpress/mu-plugins/ccspro.php` and `wordpress/mu-plugins/ccspro/` — WordPress MU-plugin loader + modules
- `vercel.json` — SPA rewrite and cache headers
- Existing interfaces in `src/types/wordpress.ts` (unless API schema changed)

---

## Shell

- **OS:** Windows 10, PowerShell
- Use `;` not `&&` to chain shell commands
- Use `git mv` for moving files to preserve git history

---

## Routing (critical)

- Named routes (`/pricing`, `/about`, `/contact`) must be defined **before** `/:slug` in `App.tsx`
- Internal nav: React Router `<Link>`. Anchor links: standard `<a>` tags.

---

## Corrections log

<!-- Add one-liners here when Claude makes a mistake so it never repeats it -->
