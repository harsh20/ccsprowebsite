# CCS Pro Marketing Site — Requirements

## 1. Project goals

- Provide a multi-page marketing site for **CCS Pro** (credentialing packets for Texas healthcare providers).
- Support four distinct page templates — Homepage, Pricing, About Us, Contact Us — each with typed content.
- Preserve a legacy WordPress-driven landing page route (`/:slug`) for content variations.
- Allow non-developers to edit copy, CTAs, pricing, FAQ, and other sections via a CMS without redeploying (Phase 2).
- Allow the site to be shown as "Coming Soon" or live without code changes or redeploys.

---

## 2. Functional requirements

### 2.1 Pages and routing

| Route | Page | Status |
|-------|------|--------|
| `/` | Homepage | Phase 1 — mock data |
| `/pricing` | Pricing page | Phase 1 — mock data |
| `/about` | About page | Phase 1 — mock data |
| `/contact` | Contact page | Phase 1 — mock data |
| `/:slug` | Legacy WP-driven landing | Existing — WordPress API |
| `*` | 404 Not Found | Existing |

Named routes (`/pricing`, `/about`, `/contact`) must be defined before `/:slug` so they are not captured by the slug wildcard.

### 2.2 Global components

#### Header

| Requirement | Detail |
|-------------|--------|
| Logo | Text fallback "CCS Pro"; image when available. |
| Primary nav | Array of `{ label, href, openInNewTab }`. Links to Product (/#how-it-works), Pricing, About, Contact. |
| CTA button | "Start Free" on homepage; "Get Started" on all other pages. |
| Secondary link | "Sign In" (placeholder href). |
| Sticky | Sticks to top on scroll. |
| Mobile menu | Hamburger toggle with slide-out or overlay menu. |
| Active state | Current route highlighted in nav. Internal links use React Router `<Link>`. |

#### Footer

| Requirement | Detail |
|-------------|--------|
| Brand column | Logo, name, tagline. |
| Menu columns | Three columns (Product, Company, Legal) with link arrays. |
| Trust badges | Array of `{ icon, text }` — HIPAA Compliant, BAA Available, Texas-Based. |
| Copyright | Dynamic year + company name. |

### 2.3 Homepage sections (in order)

1. **Hero** — Headline with highlighted word, subheadline, primary/secondary CTAs, trust indicators, dashboard mock card (completion %, document list).
2. **Pain Point** — Section label, headline, 3 problem cards (icon, title, body), summary text below.
3. **How It Works** — Tabbed layout: "For Providers" / "For Groups & Facilities". Each tab shows 3 numbered steps with icons.
4. **Ecosystem Section** — Two color-coded columns (indigo=Provider, emerald=Group) with 4 cause-effect pairs connected by labeled pills. On mobile, stacks vertically with dividers.
5. **CTA Block A** — Indigo style. Provider-focused ("Your profile is 10 minutes away").
6. **Home Pricing** — Two simplified cards side-by-side: Provider ($99/yr) and Group ($1,199/seat/yr). Not the full pricing page.
7. **CTA Block B** — Emerald style. Group-focused ("Managing a group or facility?").
8. **Support** — 3 channel cards: Email, Live Chat, Help Center.
9. **FAQ** — 6 items max, homepage-specific questions.

### 2.4 Pricing page sections

1. **Hero** — "Simple pricing. No surprises." No CTA buttons in hero.
2. **Extended pricing cards** — Same as homepage but with additional detail: annual breakdown, packet cost example, seat management rules, 50+ enterprise note.
3. **Feature comparison table** — Checkmark/dash table with 4 categories: Profile & Documents, Packet Generation, Group Management, Security & Compliance.
4. **FAQ** — 5 pricing-specific questions.
5. **Final CTA** — "Ready to stop rebuilding from scratch?" with Provider and Group CTAs.

### 2.5 About page sections

1. **Hero** — "Built for the people who keep healthcare credentialed."
2. **Mission** — 2-3 sentence mission statement.
3. **Why Texas** — Two columns: paragraph about Texas credentialing + 3 stat cards.
4. **How we're different** — 3 differentiator cards: portability, Texas-native, fair pricing.
5. **CTA** — Link to contact page.

### 2.6 Contact page sections

1. **Hero** — "Get in touch."
2. **Two-column layout** — Left: contact form (Name, Email, "I am a" dropdown, Message, Submit). Right: direct contact info (email, response time, business hours).
3. **Group callout** — Highlighted box for group/facility evaluations.

### 2.7 Legacy landing page (`/:slug`)

- Supports multiple landing variants (e.g. `default`, `texas`).
- Each variant is a single CMS entity containing all section content.
- URL mapping: `/` previously mapped here; now `/:slug` only (homepage is `HomePage.tsx`).
- Creating a new variant requires no frontend code changes.
- Sections: Header, Hero, Verification/LogoStrip, FounderSpotlight, ProblemOutcome, HowItWorks, FeaturesGrid, PacketPreview, SecuritySection, CAQHConcierge, PricingSection, SupportSection, TeamSection, FAQSection, FinalCTA, Footer.
- Icon fields stored as string names; mapped to Lucide icons via `getLandingIcon()`.
- All sections work when content is empty or missing (fallback to static defaults).

### 2.8 Coming soon mode

- **Toggle:** Controlled from WordPress admin (Settings → CCS Pro Site), no redeploy.
- **Behavior:** On load, frontend fetches site-config. While loading, shows blank splash (not the Coming Soon page). If `true`, shows Coming Soon. If `false` or timeout/failure, shows full app.
- **Build-time fallback:** `VITE_COMING_SOON` env var initializes state before API response arrives.

### 2.9 Offline / API-unavailable behavior

- If WordPress API is unreachable for `/:slug` content, frontend shows static fallback content from `landing.ts`.
- New pages (`/`, `/pricing`, `/about`, `/contact`) use bundled mock data — no API dependency.

### 2.10 Page meta tags

- Each page sets `document.title` via `useEffect` on mount.
- Format: "Page Name | CCS Pro" (e.g. "Pricing | CCS Pro").
- Homepage uses full tagline: "CCS Pro | Credentialing Packets. Done Once. Ready Always."

---

## 3. Non-functional requirements

### 3.1 Performance

- New pages load instantly (no API calls for content; mock data is bundled).
- `/:slug` route: fetch and cache landing content per slug (stale time ~5 min).
- Site-config uses cache-busting (`cache: "no-store"`) so coming-soon toggle responds quickly.

### 3.2 Security and privacy

- CMS and API are for content only; no sensitive user data.
- CORS restricts API access to known frontend origins and localhost.
- WordPress admin and API use HTTPS; credentials not committed.

### 3.3 Compatibility

- CMS: WordPress + ACF with MU-plugin-managed field groups, including Options Pages for global header/footer settings.
- Frontend: modern evergreen browsers; no legacy browser support required.
- Shell: PowerShell on Windows — use `;` not `&&` for command chaining.

### 3.4 Deployment and operations

- Frontend: static build on Vercel; no server-side rendering.
- Content changes (Phase 2) and coming-soon toggle must not require a redeploy.
- WordPress setup documented in `docs/WORDPRESS_SETUP_GUIDE.md`.

### 3.5 Responsive design

- All pages must be responsive (mobile, tablet, desktop).
- EcosystemSection: two-column on desktop, single-column stacked on mobile with dividers between pairs.
- Header: hamburger menu on mobile.
- Pricing cards: single column on mobile, side-by-side on desktop.

---

## 4. Out of scope

- User authentication, sign-up, or sign-in flows on the marketing site.
- Payment or checkout (pricing is informational only).
- Blog, news, or other post types beyond Landing Page CPT.
- Full CMS content migration for the mock-data-driven named pages is still pending (Phase 2 continuation).
- WordPress content migration for new pages (Phase 2).

---

## 5. References

- **context.md** — Project context, domains, repo layout, what was implemented.
- **architecture.md** — Technical architecture, routing, data flow.
- **claude.md** — AI assistant project rules and conventions.
- **docs/WORDPRESS_SETUP_GUIDE.md** — WordPress and CMS setup.
