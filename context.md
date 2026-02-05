# CCS Pro Landing Page — Project Context

Use this file when starting a new chat so the AI has full context without re-reading the whole history.

---

## 1. Project overview

- **What it is:** Marketing landing page for **CCS Pro** — credentialing packets for US healthcare providers. Content is driven by a **WordPress headless CMS**; the frontend is a static React app that fetches content at runtime.
- **Frontend:** React 18, Vite, TypeScript, Tailwind CSS, shadcn/ui, React Router, TanStack Query.
- **CMS:** WordPress on Hostinger with ACF (Free), custom **Landing Page** CPT, and a **MU-plugin** that registers the CPT, ACF field groups, CORS, and REST endpoints. No ACF Options Pages (ACF Free–compatible).

---

## 2. Domains and hosting

| Purpose   | Domain                     | Hosting   |
|----------|----------------------------|-----------|
| Live site| https://ccsprocert.com     | Vercel    |
| CMS/API  | https://wpcms.ccsprocert.com | Hostinger |

- **DNS:** GoDaddy domain, managed via **CloudFlare**. A record for `wpcms` → Hostinger; root/www for frontend → Vercel.
- **Git:** Frontend repo pushed to **GitHub** (`harsh20/ccsprowebsite`). Remote used for deploy: `other` → `https://github.com/harsh20/ccsprowebsite.git`. Vercel is connected to this repo (other Vercel account).

---

## 3. Architecture (high level)

```
User → ccsprocert.com (Vercel)
         ↓
    React app loads
         ↓
    GET wpcms.ccsprocert.com/wp-json/ccspro/v1/site-config  → comingSoon? show Coming Soon page
    GET wpcms.ccsprocert.com/wp-json/ccspro/v1/landing-page/{slug}  → full landing content (default, texas, etc.)
         ↓
    Renders landing sections (Header, Hero, Features, Pricing, FAQ, Footer, etc.)
```

- **Landing Page CPT:** One post per variation (e.g. slug `default`, `texas`). Each post holds all ACF fields for that full page (hero, nav, features, pricing, FAQ, etc.).
- **REST:** Custom namespace `ccspro/v1`. Key routes: `GET /site-config`, `GET /landing-page/{slug}`. CORS allows ccsprocert.com and localhost.

---

## 4. Repo structure (important paths)

```
temp-repo/
├── src/
│   ├── App.tsx              # Coming-soon gate + router; fetches site-config on load
│   ├── pages/
│   │   ├── Index.tsx        # Main landing; uses useLandingPage(slug), passes content to sections
│   │   ├── ComingSoon.tsx   # “Coming soon” page when enabled
│   │   └── NotFound.tsx
│   ├── lib/
│   │   ├── wordpress.ts     # getLandingPage(slug), getSiteConfig() — WP API client
│   │   └── landing-icons.ts # Icon name → Lucide component for dynamic content
│   ├── hooks/
│   │   └── useWordPress.ts  # useLandingPage(slug) — React Query
│   ├── types/
│   │   └── wordpress.ts     # TypeScript types for API responses
│   ├── content/
│   │   └── landing.ts       # Static fallback (defaultLandingPageContent) when WP unavailable
│   └── components/
│       ├── PasswordGate.tsx # Optional password gate when VITE_DEV_PASSWORD is set
│       └── landing/         # Header, HeroSection, FeaturesGrid, PricingSection, FAQSection, Footer, etc.
├── wordpress/
│   └── mu-plugins/
│       └── ccspro-cpt-acf.php   # CPT, ACF groups, site-config + landing-page REST, CORS, Coming Soon admin
├── docs/
│   └── WORDPRESS_SETUP_GUIDE.md # DNS, Hostinger, plugins, MU-plugin, permalinks, coming soon toggle
├── .env.example             # VITE_WP_API_URL, optional VITE_COMING_SOON, VITE_DEV_PASSWORD
├── vercel.json              # SPA rewrite (.*) → index.html, asset cache headers
├── context.md               # This file — project context for new chats
├── requirements.md          # Functional and non-functional requirements
└── architecture.md          # Technical architecture, data flow, diagrams
```

---

## 5. What was done (start to now)

1. **WordPress headless plan**  
   Designed integration: single Landing Page CPT with ACF (no Options Pages), custom REST, CORS. Frontend already had content in `src/content/landing.ts`; plan was to replace that with API-driven data.

2. **WordPress setup guide**  
   Added `docs/WORDPRESS_SETUP_GUIDE.md`: CloudFlare DNS, Hostinger WordPress, ACF + ACF to REST API, MU-plugin install, permalinks, env vars, troubleshooting.

3. **MU-plugin**  
   `wordpress/mu-plugins/ccspro-cpt-acf.php`: registers `landing_page` CPT; many ACF field groups (Site Config, Nav, Hero, Verification, Founder, Problem/Outcome, How It Works, Features, Packet Preview, Security, CAQH Concierge, Pricing, Support, Team, FAQ, Final CTA, Footer); REST routes `ccspro/v1/site-config` and `ccspro/v1/landing-page/{slug}`; CORS for ccsprocert.com and localhost; **Coming Soon** admin under **Settings → CCS Pro Site** (saves `ccspro_coming_soon` option).

4. **Frontend API and types**  
   `src/lib/wordpress.ts` (getLandingPage, getSiteConfig), `src/types/wordpress.ts`, `src/hooks/useWordPress.ts` (useLandingPage). Static fallback in `src/content/landing.ts` (`defaultLandingPageContent`) when API fails.

5. **Dynamic landing page**  
   `Index.tsx` uses `useParams().slug ?? 'default'`, `useLandingPage(slug)`, shows skeleton while loading, passes API/fallback content to all section components. Sections accept optional `content?: LandingPageContent` and use `getLandingIcon()` for icon names from WP. Routes: `/`, `/:slug`.

6. **Lovable branding removed**  
   Replaced Lovable references in index.html, README; removed `lovable-tagger` from package.json and vite.config.ts.

7. **Coming soon mode**  
   - **Runtime:** App fetches `GET /ccspro/v1/site-config` on load. If `comingSoon === true`, only the Coming Soon page is rendered; otherwise the full site.  
   - **Toggle:** WordPress **Settings → CCS Pro Site** → “Coming soon mode” checkbox → Save. No redeploy needed.  
   - **Logic:** Show Coming Soon while config is loading (`comingSoon !== false`); after response or 8s timeout, show full site only when API returns `comingSoon: false` or fetch fails. Cache-busting and `cache: "no-store"` on site-config request.

8. **Vercel and GitHub**  
   Pushed repo to `harsh20/ccsprowebsite` (remote `other`). Fixed `vercel.json` rewrite (invalid regex → `/(.*)`). Frontend deployed on Vercel with env `VITE_WP_API_URL`; optional `VITE_COMING_SOON` for build-time fallback.

9. **DNS / connectivity**  
   Resolved earlier “site can’t be reached” for wpcms: router/PC reboot and DNS cache (e.g. `ipconfig /flushdns`) fixed resolution; nameservers and A record were correct.

10. **Project docs**  
    Added `context.md` (this file), `requirements.md` (functional/non-functional requirements), and `architecture.md` (system overview, frontend/backend architecture, data flow, deployment, mermaid diagrams).

11. **Password gate for staging**  
    Added `PasswordGate` component wrapping App. When `VITE_DEV_PASSWORD` env var is set, visitors must enter the password before accessing the site. Auth persists in localStorage. Disable by removing the env var.

---

## 6. Environment variables

| Variable              | Where       | Purpose |
|-----------------------|------------|---------|
| `VITE_WP_API_URL`     | Vercel/.env| WordPress API base, e.g. `https://wpcms.ccsprocert.com/wp-json`. Required for live content and site-config. |
| `VITE_COMING_SOON`    | Optional   | Build-time fallback; `"true"` = show Coming Soon if site-config not yet loaded. Runtime WordPress toggle overrides once response is in. |
| `VITE_PREVIEW_SECRET` | Optional   | Not used in current flow; was for ?preview= secret to bypass coming soon. |
| `VITE_DEV_PASSWORD`   | Vercel/.env| Optional: password to protect site during development. Remove to disable gate. |

---

## 7. Coming soon mode (quick ref)

- **Turn on:** WordPress Admin → **Settings → CCS Pro Site** → check “Coming soon mode” → **Save**.  
- **Turn off:** Same page → uncheck → **Save**.  
- **API:** `GET https://wpcms.ccsprocert.com/wp-json/ccspro/v1/site-config` → `{ "comingSoon": true }` or `false`.  
- **Frontend:** On load, app fetches site-config; if `comingSoon === true` it renders only `ComingSoon.tsx`; otherwise the full app. Timeout 8s then show full site if no response.

---

## 8. Local dev and deploy

- **Run locally:** `npm i` then `npm run dev`. Set `VITE_WP_API_URL` in `.env` (see `.env.example`).  
- **Build:** `npm run build` → `dist/`.  
- **Deploy:** Push to `other` (e.g. `git push other main`). Vercel builds from connected repo; ensure `VITE_WP_API_URL` is set in Vercel project env.  
- **WordPress:** Upload/replace `wordpress/mu-plugins/ccspro-cpt-acf.php` on wpcms.ccsprocert.com; permalinks = Post name. Full steps in `docs/WORDPRESS_SETUP_GUIDE.md`.

---

## 9. Handy URLs

- Live site: https://ccsprocert.com/  
- CMS: https://wpcms.ccsprocert.com/  
- Site config API: https://wpcms.ccsprocert.com/wp-json/ccspro/v1/site-config  
- Default landing content API: https://wpcms.ccsprocert.com/wp-json/ccspro/v1/landing-page/default  

---

## 10. Related docs

| File | Purpose |
|------|---------|
| **requirements.md** | Functional and non-functional requirements; section content; coming soon; fallbacks. |
| **architecture.md** | System overview, frontend/backend architecture, REST API, data flow, deployment, mermaid diagrams. |
| **docs/WORDPRESS_SETUP_GUIDE.md** | Step-by-step WordPress and CMS setup (DNS, Hostinger, plugins, MU-plugin, coming soon toggle). |

---

*Last updated to include password gate for staging (VITE_DEV_PASSWORD); context.md sections 5 and 6 updated accordingly.*
