# CCS Pro Marketing Site — Project Context

Use this file when starting a new chat so the AI has full context without re-reading the whole history.

---

## 1. Project overview

- **What it is:** Marketing site for **CCS Pro** — credentialing packets for Texas healthcare providers. The site has four page templates (Homepage, Pricing, About, Contact) plus a legacy WP-driven landing page route.
- **Frontend:** React 18, Vite, TypeScript, Tailwind CSS, shadcn/ui, React Router, TanStack Query.
- **CMS:** WordPress on Hostinger with custom **Landing Page** CPT and a **MU-plugin** that registers CPT behavior, ACF field groups, CORS, and custom REST endpoints.
- **Phase status:** Phase 1 complete (mock-data pages), Phase 2 patching in progress (MU-plugin and provider cleanup completed in repo).

---

## 2. Domains and hosting

| Purpose   | Domain                     | Hosting   |
|----------|----------------------------|-----------|
| Live site| https://ccsprocert.com     | Vercel    |
| CMS/API  | https://wpcms.ccsprocert.com | Hostinger |

- **DNS:** GoDaddy domain, managed via **CloudFlare**. A record for `wpcms` → Hostinger; root/www for frontend → Vercel.
- **Git:** Frontend repo pushed to **GitHub** (`harsh20/ccsprowebsite`). Remote used for deploy: `other` → `https://github.com/harsh20/ccsprowebsite.git`. Vercel is connected to this repo.

---

## 3. Architecture (high level)

```
User → ccsprocert.com (Vercel)
         ↓
    React app loads
         ↓
    GET wpcms.ccsprocert.com/wp-json/ccspro/v1/site-config  → comingSoon? show blank splash while loading
         ↓
    React Router renders:
      /           → HomePage.tsx (live WP + mock fallback merge)
      /pricing    → PricingPage.tsx (mock data)
      /about      → AboutPage.tsx (mock data)
      /contact    → ContactPage.tsx (mock data)
      /:slug      → Index.tsx (WP API or static fallback)
```

- **Homepage (`/`):** Uses live `useLandingPage("default")`, `useSiteConfig()`, and `useMenus()` data with `mockData.ts` fallback when API fields are missing.
- **Other named pages (`/pricing`, `/about`, `/contact`):** Still driven by `src/content/mockData.ts`.
- **Legacy route:** `/:slug` still works via `Index.tsx`, which fetches from WordPress or falls back to `src/content/landing.ts`.
- **REST:** Custom namespace `ccspro/v1`. Key routes: `GET /site-config`, `GET /menus`, `GET /landing-page/{slug}`. CORS allows ccsprocert.com and localhost (5173, 3000, 127.0.0.1:5173).

---

## 4. Repo structure (important paths)

```
temp-repo/
├── src/
│   ├── App.tsx              # Coming-soon gate + router (/, /pricing, /about, /contact, /:slug)
│   ├── pages/
│   │   ├── HomePage.tsx     # Homepage with live WP data + mock fallback merge
│   │   ├── PricingPage.tsx  # Full pricing page — hero, extended cards, feature comparison table, FAQ
│   │   ├── AboutPage.tsx    # About page — hero, mission, why Texas, differentiators
│   │   ├── ContactPage.tsx  # Contact page — hero, form, contact info, group callout
│   │   ├── Index.tsx        # Legacy WP-driven landing; uses useLandingPage(slug)
│   │   ├── ComingSoon.tsx   # "Coming soon" page when enabled
│   │   └── NotFound.tsx
│   ├── lib/
│   │   ├── wordpress.ts     # getLandingPage(slug), getSiteConfig() — WP API client (re-exports from providers)
│   │   └── landing-icons.ts # Icon name → Lucide component map (33 icons)
│   ├── hooks/
│   │   └── useWordPress.ts  # useLandingPage(slug), useSiteConfig(), useMenus() — React Query
│   ├── types/
│   │   └── wordpress.ts     # TypeScript types — LandingPageContent (legacy) + new page types
│   ├── content/
│   │   ├── landing.ts       # Static fallback (defaultLandingPageContent) for /:slug route
│   │   ├── mockData.ts      # Mock data for all new pages (mockSiteSettings, mockHomePage, etc.)
│   │   └── providers/       # Content provider abstraction (REST active, GraphQL stubbed)
│   └── components/
│       ├── PasswordGate.tsx  # Optional password gate when VITE_DEV_PASSWORD is set
│       └── landing/
│           ├── Header.tsx            # Global header — route-aware CTA, active links, mobile menu
│           ├── Footer.tsx            # Global footer — 4-column layout with brand + menu columns
│           ├── HeroSection.tsx       # Hero with optional dashboard card mock
│           ├── ProblemOutcome.tsx     # Pain point section with label + headline + cards
│           ├── HowItWorks.tsx        # Tabbed (Provider/Group) step cards
│           ├── EcosystemSection.tsx  # NEW — two-column provider/group paired layout
│           ├── HomePricingSection.tsx # NEW — simplified 2-card homepage pricing
│           ├── FinalCTA.tsx          # CTA blocks with indigo/emerald style variants
│           ├── SupportSection.tsx    # Channel-based support cards
│           ├── FAQSection.tsx        # Accordion FAQ, accepts direct faqData prop
│           ├── PricingSection.tsx    # Pack-based pricing (used by /:slug route)
│           ├── LandingPageSkeleton.tsx
│           ├── shared/Cards.tsx      # Reusable card components
│           └── archived/             # Components removed from homepage but preserved
│               ├── LogoStrip.tsx
│               ├── FounderSpotlight.tsx
│               ├── FeaturesGrid.tsx
│               ├── PacketPreview.tsx
│               ├── SecuritySection.tsx
│               ├── CAQHConcierge.tsx
│               └── TeamSection.tsx
├── wordpress/
│   └── mu-plugins/
│       └── ccspro-cpt-acf.php   # CPT, ACF groups, REST, CORS, menus, admin edit-screen customizations
├── docs/
│   └── WORDPRESS_SETUP_GUIDE.md
├── .env.example
├── vercel.json              # SPA rewrite (.*) → index.html, asset cache headers (NOT touched)
├── context.md               # This file
├── requirements.md          # Functional and non-functional requirements
├── architecture.md          # Technical architecture, data flow, diagrams
└── claude.md                # AI assistant project rules and conventions
```

---

## 5. What was done (start to now)

### Pre–Phase 1 (WordPress integration)

1. **WordPress headless plan** — Designed integration: single Landing Page CPT with ACF (no Options Pages), custom REST, CORS.
2. **WordPress setup guide** — Added `docs/WORDPRESS_SETUP_GUIDE.md`.
3. **MU-plugin** — `ccspro-cpt-acf.php`: CPT, ACF field groups, REST routes, CORS, Coming Soon admin.
4. **Frontend API and types** — `wordpress.ts`, `types/wordpress.ts`, `useWordPress.ts`, static fallback in `landing.ts`.
5. **Dynamic landing page** — `Index.tsx` with `useLandingPage(slug)`, skeleton, fallback.
6. **Lovable branding removed**.
7. **Coming soon mode** — Runtime toggle via WordPress admin + site-config API.
8. **Vercel and GitHub** — Deployed frontend, fixed `vercel.json`.
9. **DNS / connectivity** — Resolved wpcms DNS issues.
10. **Project docs** — context.md, requirements.md, architecture.md.
11. **Password gate** — `PasswordGate` component for staging protection.

### Phase 1 — Frontend rebuild (mock data only)

12. **Archived orphaned components** — Moved 7 components (LogoStrip, FounderSpotlight, FeaturesGrid, PacketPreview, SecuritySection, CAQHConcierge, TeamSection) to `src/components/landing/archived/`. Updated Index.tsx imports.

13. **New TypeScript interfaces** — Extended `src/types/wordpress.ts` with: `MenuLink`, `HeaderData`, `FooterData`, `SiteSettings`, `PainPointContent`, `HowItWorksTabContent`, `EcosystemContent`, `HomePricingCardData`, `CtaBlockContent`, `SupportChannel`, `SupportSectionContent`, `HomePageContent`, `PricingPlanExtended`, `FeatureComparisonRow`, `FeatureComparisonCategory`, `PricingPageContent`, `AboutPageContent`, `ContactPageContent`. All existing interfaces untouched.

14. **Mock data file** — Created `src/content/mockData.ts` exporting `mockSiteSettings`, `mockHomePage`, `mockPricingPage`, `mockAboutPage`, `mockContactPage`. Fully typed against new interfaces.

15. **Icon map expanded** — Added 7 new Lucide icons: Upload, Send, Users, LayoutDashboard, BookOpen, Check, Minus.

16. **Header rewrite** — Accepts `headerData?: HeaderData`. Uses `useLocation()` for active link highlighting. CTA says "Start Free" on homepage, "Get Started" elsewhere. Internal links use React Router `<Link>`.

17. **Footer rewrite** — Accepts `footerData?: FooterData`. New 4+1 column layout (brand + 3 menu columns). Legacy `content` prop path preserved for `/:slug` route.

18. **New components** — `EcosystemSection.tsx` (two-column provider/group pairs, responsive stacking on mobile), `HomePricingSection.tsx` (simplified 2-card layout for homepage).

19. **Updated components** — HeroSection (dashboard card), ProblemOutcome (sectionLabel + headline), HowItWorks (Provider/Group tabs via shadcn Tabs), FinalCTA (indigo/emerald style variants), SupportSection (3 channel cards), FAQSection (accepts direct `faqData` prop).

20. **New page templates** — `HomePage.tsx`, `PricingPage.tsx`, `AboutPage.tsx`, `ContactPage.tsx`. Each sets `document.title` via `useEffect`.

21. **Routing** — Added `/pricing`, `/about`, `/contact` routes before `/:slug` in App.tsx. Named routes take priority over the slug wildcard.

22. **Coming-soon flash fix** — `useState` initializes with `buildTimeComingSoon || null`. Renders blank `<div>` during API loading instead of `<ComingSoon />`.

### Phase 2 — MU-plugin and data-flow updates

23. **Menu endpoint and menu locations** — Added `ccspro-primary-nav`, `ccspro-footer-col1/2/3` and `GET /ccspro/v1/menus`.
24. **Global options pages** — Replaced pricing options page with parent `CCS Pro` settings and `Header`/`Footer` ACF sub-pages.
25. **Pricing v2 fields on landing_page** — Removed global pricing schema and `/pricing` endpoint; added `group_ccspro_pricing_v2` with provider/group cards and `highlighted` flags.
26. **How It Works tabs support** — Added `provider_steps` and `group_steps` with fallback from legacy `how_it_works_steps`.
27. **Ecosystem section support** — Added `group_ccspro_ecosystem` and REST mapping to `ecosystemContent`.
28. **Extended site-config** — `GET /site-config` now returns `comingSoon` + `header` + `footer` global settings.
29. **Landing Page admin UX** — Disabled Gutenberg for `landing_page`, removed unused metaboxes, title placeholder update, live URL notice, and slug hint.
30. **CORS update** — Added `http://localhost:3000` to allowed origins.
31. **Frontend provider cleanup** — Removed `getPricingContent` from providers/hooks and removed dead call-site merging in `Index.tsx`.
32. **Homepage live API wiring + schema alignment** — HomePage now merges `landing-page/default`, `site-config`, and `menus` with mock fallback data; added `PricingContentV2`, global config/menu types, ecosystem field rename (`providerAction`/`groupOutcome`), and `PricingSection` guard to prevent `/:slug` crash when `packs` is absent.
33. **Dynamic header logo rendering** — `Header.tsx` now prefers API `headerData.logoUrl`, falls back to static asset, then falls back to text; `HeaderData` includes optional `logoUrl`.
34. **Hero ACF gap fixes** — Added `hero_headline_suffix` ACF field and wired it through the REST response, `HeroContent` type, mock data, and `HeroSection.tsx` to replace the hardcoded "Ready Always." suffix. Also wired `heroDashboard` through the API-first + mock fallback pattern in `HomePage.tsx`; previously dashboard card edits in WordPress had no effect on the live page.

---

## 6. Environment variables

| Variable              | Where       | Purpose |
|-----------------------|------------|---------|
| `VITE_WP_API_URL`     | Vercel/.env| WordPress API base, e.g. `https://wpcms.ccsprocert.com/wp-json`. Required for live content and site-config. |
| `VITE_COMING_SOON`    | Optional   | Build-time fallback; `"true"` = show Coming Soon if site-config not yet loaded. Runtime WordPress toggle overrides. |
| `VITE_CONTENT_SOURCE` | Optional   | `rest` (default) or `wordpress_graphql` (stubbed). |
| `VITE_DEV_PASSWORD`   | Vercel/.env| Optional: password to protect site during development. Remove to disable gate. |

---

## 7. Coming soon mode (quick ref)

- **Turn on:** WordPress Admin → **Settings → CCS Pro Site** → check "Coming soon mode" → **Save**.
- **Turn off:** Same page → uncheck → **Save**.
- **API:** `GET https://wpcms.ccsprocert.com/wp-json/ccspro/v1/site-config` → `{ "comingSoon": true }` or `false`.
- **Frontend:** On load, app fetches site-config. While loading (`null`), shows blank splash. If `true`, shows `ComingSoon.tsx`. If `false` or timeout/failure, shows full app.

---

## 8. Routes

| Path | Page | Data source |
|------|------|-------------|
| `/` | HomePage | WordPress API (`landing-page/default` + `site-config` + `menus`) merged with `mockHomePage` + `mockSiteSettings` fallback |
| `/pricing` | PricingPage | `mockPricingPage` from `mockData.ts` |
| `/about` | AboutPage | `mockAboutPage` from `mockData.ts` |
| `/contact` | ContactPage | `mockContactPage` from `mockData.ts` |
| `/:slug` | Index (legacy) | WordPress API or `defaultLandingPageContent` fallback |
| `*` | NotFound | — |

Header/Footer on `/` are API-driven with fallback; header logo rendering now uses dynamic `logoUrl` when provided by site-config, with static/text fallbacks.

---

## 9. Local dev and deploy

- **Run locally:** `npm i` then `npm run dev`. Set `VITE_WP_API_URL` in `.env` (see `.env.example`).
- **Build:** `npm run build` → `dist/`.
- **Deploy:** Push to `other` (e.g. `git push other main`). Vercel builds from connected repo; ensure `VITE_WP_API_URL` is set in Vercel project env.
- **WordPress:** Upload/replace `wordpress/mu-plugins/ccspro-cpt-acf.php` on wpcms.ccsprocert.com; permalinks = Post name. Full steps in `docs/WORDPRESS_SETUP_GUIDE.md`.

---

## 10. Handy URLs

- Live site: https://ccsprocert.com/
- CMS: https://wpcms.ccsprocert.com/
- Site config API: https://wpcms.ccsprocert.com/wp-json/ccspro/v1/site-config
- Default landing content API: https://wpcms.ccsprocert.com/wp-json/ccspro/v1/landing-page/default

---

## 11. What is NOT touched yet

- `src/lib/wordpress.ts` — API client unchanged.
- `src/content/landing.ts` — `defaultLandingPageContent` still exists for `/:slug` fallback.
- `vercel.json` — SPA rewrite unchanged.
- Core route structure in `App.tsx` remains unchanged (`/`, `/pricing`, `/about`, `/contact`, `/:slug`, `*`).

---

## 12. Related docs

| File | Purpose |
|------|---------|
| **claude.md** | AI assistant project rules, conventions, and constraints. |
| **requirements.md** | Functional and non-functional requirements; section content; pages. |
| **architecture.md** | Technical architecture, routing, data flow, deployment diagrams. |
| **docs/WORDPRESS_SETUP_GUIDE.md** | Step-by-step WordPress and CMS setup. |

---

*Last updated after hero ACF gap fixes: `hero_headline_suffix` field, dashboard data wiring.*
