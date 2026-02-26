# CCS Pro Marketing Site

Marketing site for CCS Pro — credentialing packets for Texas healthcare providers. Built with React, Vite, TypeScript, and Tailwind CSS. Content is currently mock data (Phase 1); WordPress headless CMS integration is planned for Phase 2.

## Pages

| Route | Page | Description |
|-------|------|-------------|
| `/` | Homepage | Hero, pain points, how it works, ecosystem, pricing preview, support, FAQ |
| `/pricing` | Pricing | Extended pricing cards, feature comparison table, pricing FAQ |
| `/about` | About | Mission, why Texas, differentiators |
| `/contact` | Contact | Contact form, direct info, group callout |
| `/:slug` | Landing (legacy) | WordPress-driven landing page variants |

## Tech stack

- **Frontend:** React 18, TypeScript, Vite
- **UI:** Tailwind CSS, shadcn/ui, Lucide icons
- **Routing:** React Router
- **Data (Phase 1):** Typed mock data in `src/content/mockData.ts`
- **Data (legacy):** WordPress REST API via TanStack Query, fallback in `src/content/landing.ts`
- **CMS:** WordPress on Hostinger with ACF Free, custom MU-plugin

## Local development

```sh
git clone <YOUR_GIT_URL>
cd ccsprowebsite
npm i
npm run dev
```

Set `VITE_WP_API_URL` in `.env` (see `.env.example`) to point at your WordPress API.

## Environment variables

| Variable | Required | Purpose |
|----------|----------|---------|
| `VITE_WP_API_URL` | Yes | WordPress API base URL (e.g. `https://wpcms.ccsprocert.com/wp-json`) |
| `VITE_COMING_SOON` | No | Build-time fallback: `"true"` shows Coming Soon before API responds |
| `VITE_CONTENT_SOURCE` | No | `rest` (default) or `wordpress_graphql` (stubbed) |
| `VITE_DEV_PASSWORD` | No | Password gate for staging; remove to disable |

## Build and deploy

```sh
npm run build    # outputs to dist/
```

Deploy the `dist` folder to Vercel or any static host. Required env vars must be set in your deployment platform.

**Current deploy:** Push to GitHub remote `other` → Vercel auto-builds.

## Project docs

| File | Purpose |
|------|---------|
| `context.md` | Full project context — domains, repo layout, what was done, env vars |
| `requirements.md` | Functional and non-functional requirements for all pages |
| `architecture.md` | Technical architecture, routing flow, data model, deployment |
| `claude.md` | AI assistant project rules and conventions |
| `docs/WORDPRESS_SETUP_GUIDE.md` | WordPress and CMS setup guide |

## WordPress setup

See [docs/WORDPRESS_SETUP_GUIDE.md](docs/WORDPRESS_SETUP_GUIDE.md) for CMS setup, ACF, and the MU-plugin.

## Key directories

```
src/
├── pages/           # Page components (HomePage, PricingPage, AboutPage, ContactPage, Index)
├── components/landing/  # Section components (Header, Footer, Hero, etc.)
├── content/         # Mock data (mockData.ts) and static fallback (landing.ts)
├── types/           # TypeScript interfaces
├── lib/             # API client, icon map
└── hooks/           # React Query hooks
```
