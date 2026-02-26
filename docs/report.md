# CCS Pro Codebase Audit Report

Generated: 2026-02-26

> Superseded note: This audit reflects the pre-Phase-2 state and is now partially outdated. The repository has since applied MU-plugin Phase 2 patches (pricing v2 fields on `landing_page`, `/ccspro/v1/menus`, extended `/site-config`, admin edit-screen updates, CORS update, and frontend removal of dead `/pricing` provider calls).

---

## 1. CONNECTIVITY AND API

### REST Endpoint URLs

| Endpoint | Full URL |
|----------|----------|
| Site Config | `GET https://wpcms.ccsprocert.com/wp-json/ccspro/v1/site-config` |
| Landing Page (by slug) | `GET https://wpcms.ccsprocert.com/wp-json/ccspro/v1/landing-page/{slug}` |
| Pricing (global) | `GET https://wpcms.ccsprocert.com/wp-json/ccspro/v1/pricing` |

The base URL `https://wpcms.ccsprocert.com/wp-json` is set via `VITE_WP_API_URL`. The frontend `restProvider.ts` appends `/ccspro/v1/…` to this base.

### CORS Configuration

The MU-plugin (`ccspro-cpt-acf.php` lines 97-130) allows these origins:

| Origin | Covered? |
|--------|----------|
| `https://ccsprocert.com` | Yes |
| `https://www.ccsprocert.com` | Yes |
| `http://localhost:5173` | Yes (Vite default port) |
| `http://127.0.0.1:5173` | Yes |

Both the `rest_pre_serve_request` filter and a separate OPTIONS preflight handler check the origin against this list. **This is correctly configured.**

### Landing Page REST Route — Slug Parameter

The route is registered as:

```php
register_rest_route('ccspro/v1', '/landing-page/(?P<slug>[a-z0-9\-]+)', ...)
```

The callback (`ccspro_rest_get_landing_page`) uses:

```php
$slug = $request->get_param('slug');
$posts = get_posts(array(
    'post_type' => 'landing_page',
    'name' => $slug,         // queries by post slug, NOT by ID
    'post_status' => 'publish',
    'posts_per_page' => 1,
));
```

**Confirmed: queries by slug (post `name`), not by ID.** The slug regex only accepts lowercase letters, digits, and hyphens — which is correct for WordPress slugs.

---

## 2. ACF FIELDS AND DATA MAPPING

### ACF Field Groups Registered

The MU-plugin registers **2 field groups**:

#### Group 1: `group_ccspro_landing_page` — "Landing Page Content"

Attached to CPT `landing_page`. Organized with tabs and accordions. Fields by section:

| Tab / Section | ACF Field Names (keys) |
|---------------|----------------------|
| **General > Site Config** | `site_name`, `site_tagline`, `site_description` |
| **General > Navigation** | `nav_links` (repeater: label, href), `nav_primary_label`, `nav_primary_href`, `nav_secondary_label`, `nav_secondary_href`, `nav_signin_label`, `nav_signin_href` |
| **Hero > Hero Content** | `hero_headline`, `hero_headline_highlight`, `hero_subheadline`, `hero_primary_label`, `hero_primary_href`, `hero_secondary_label`, `hero_secondary_href`, `hero_tertiary_label`, `hero_tertiary_href`, `hero_trust_indicators` (repeater: icon, text) |
| **Hero > Dashboard Preview** | `hero_dashboard_title`, `hero_dashboard_subtitle`, `hero_dashboard_completion`, `hero_dashboard_state`, `hero_dashboard_npi`, `hero_dashboard_documents` (repeater: name, status, status_color), `hero_dashboard_btn_primary`, `hero_dashboard_btn_secondary` |
| **Story > Verification / Logo Strip** | `verification_headline`, `verification_items` (repeater: icon, label) |
| **Story > Founder Spotlight** | `founder_name`, `founder_title`, `founder_initials`, `founder_quote`, `founder_bullets` (repeater: bullet_text) |
| **Story > Problem / Outcome** | `problems` (repeater: icon, title, description), `outcome_prefix`, `outcome_middle`, `outcome_suffix` |
| **How It Works** | `how_it_works_title`, `how_it_works_subtitle`, `how_it_works_steps` (repeater: step_number, icon, title, description), `how_readiness_label`, `how_readiness_states` (repeater: label, color) |
| **Features > Features Grid** | `features_title`, `features_subtitle`, `features_items` (repeater: icon, title, link, description) |
| **Features > Packet Preview** | `packet_title`, `packet_subtitle`, `packet_filename`, `packet_checklist` (repeater: item_text), `packet_cta_label`, `packet_cta_href` |
| **Security > Security Section** | `security_badge`, `security_title`, `security_subtitle`, `security_features` (repeater: icon, text), `security_cta_label`, `security_cta_href`, `security_floating_badges` (repeater: badge_text) |
| **Security > CAQH Concierge** | `caqh_badge`, `caqh_title`, `caqh_subtitle`, `caqh_benefits_title`, `caqh_benefits` (repeater: benefit_text), `caqh_cta_label`, `caqh_cta_href`, `caqh_consent_title`, `caqh_consent_modes` (repeater: icon, title, description), `caqh_always_icon`, `caqh_always_title`, `caqh_always_description` |
| **Pricing** | (notice only — pricing managed globally via Options Page) |
| **Support > Support Section** | `support_title`, `support_subtitle`, `support_features` (repeater: icon, text), `support_links` (repeater: label, href) |
| **Support > Team** | `team_title`, `team_subtitle`, `team_members` (repeater: icon, name, role, bio) |
| **FAQ & CTA > FAQ** | `faq_title`, `faq_subtitle`, `faq_items` (repeater: question, answer) |
| **FAQ & CTA > Final CTA** | `final_cta_headline`, `final_cta_subheadline`, `final_cta_primary_label`, `final_cta_primary_href`, `final_cta_secondary_label`, `final_cta_secondary_href` |
| **Footer** | `footer_brand_name`, `footer_copyright`, `footer_brand_description`, `footer_trust_badges` (repeater: icon, text), `footer_legal_links` (repeater: label, href), `footer_support_links` (repeater: label, href) |

#### Group 2: `group_ccspro_global_pricing` — "Global Pricing Settings"

Attached to **ACF Options Page** `ccspro-pricing-settings`. Fields:

| Field | Name |
|-------|------|
| Section Title | `pricing_title` |
| Section Subtitle | `pricing_subtitle` |
| Credentialing Packs | `pricing_plans` (repeater: name, price, badge, highlighted, applications_included, validity_period, billing_type, plan_type, allow_additional_payers, additional_payer_price, grace_period_days, description, features [nested repeater: feature_text], cta) |
| Post-Year Title | `post_year_title` |
| Post-Year Items | `post_year_items` (repeater: text, kind) |
| Post-Year Renewal Note | `post_year_renewal_note` |
| Footer Note | `pricing_footer_note` |

### Data Mapping: ACF → REST → TypeScript

The `ccspro_transform_landing_page_to_frontend()` function in the MU-plugin reads each ACF field with `get_field()` and maps it to the camelCase JSON structure that the frontend `LandingPageContent` TypeScript type expects. The REST response is then cast directly via `data as LandingPageContent` in `restProvider.ts` (no additional mapping/transformation on the frontend side).

**Field-by-field mapping status:**

Every key in the `LandingPageContent` TypeScript interface is populated by the PHP transform function:
- `siteConfig` ✅
- `navLinks` ✅
- `navCtas` ✅
- `heroContent` ✅
- `heroDashboard` ✅
- `verificationContent` ✅
- `founderContent` ✅
- `problemOutcomeContent` ✅
- `howItWorksContent` ✅
- `featuresContent` ✅
- `packetPreviewContent` ✅
- `securityContent` ✅
- `caqhConciergeContent` ✅
- `pricingContent` ✅
- `supportContent` ✅
- `teamContent` ✅
- `faqContent` ✅
- `finalCtaContent` ✅
- `footerContent` ✅

**No missing fields in either direction.** The PHP output structure matches the TS type exactly.

### Does ACF to REST API plugin need to be active?

**No.** The MU-plugin does NOT rely on the "ACF to REST API" plugin. The custom `ccspro/v1/landing-page/{slug}` endpoint reads ACF data via `get_field()` directly and builds its own JSON. The standard WP REST `acf` field exposure is not used. However, the `WORDPRESS_SETUP_GUIDE.md` lists "ACF to REST API" as a required plugin — this is misleading/unnecessary and should be updated.

### ACF Options Page Compatibility Issue

**CRITICAL:** The MU-plugin calls `acf_add_options_page()` (line 144) for the global pricing settings. **This function requires ACF Pro.** ACF Free does not include Options Pages. Since the requirements specify ACF Free only, this is a blocker for the pricing endpoint. On ACF Free, the Options Page will silently not be created, and `get_field('pricing_plans', 'option')` will return `false`/`null`.

The `ccspro_get_pricing_content()` function has a fallback (line 1168): if `$scope === 'option'` and packs are empty and `$post_id` is non-zero, it tries reading from the post. But for the standalone `/pricing` endpoint, `$post_id` is `0`, so the fallback doesn't trigger — it returns hardcoded defaults from `ccspro_map_pricing_packs()`.

For the `/landing-page/{slug}` endpoint, `$post_id` is set, so the fallback does trigger — pricing would try to read from the landing page post's fields. But no pricing ACF fields are attached to the `landing_page` CPT (they're on the Options Page group), so it would still return defaults.

---

## 3. COMING SOON FLOW

### Code Path in App.tsx

```
Page Load
  → useState(comingSoon = null)
  → useEffect fires:
      ├── setTimeout(8s): if comingSoon is still null, set to false
      └── contentProvider.getSiteConfig()
            ├── .then(config) → setComingSoon(config.comingSoon)
            ├── .catch() → setComingSoon(false)
            └── .finally() → clearTimeout(timeout)
  → Render: showComingSoon = (comingSoon !== false)
```

**Path 1: API returns `comingSoon: true`**
- `getSiteConfig()` resolves → `setComingSoon(true)` → `showComingSoon = (true !== false) = true` → renders `<ComingSoon />`

**Path 2: API returns `comingSoon: false`**
- `getSiteConfig()` resolves → `setComingSoon(false)` → `showComingSoon = (false !== false) = false` → renders `<BrowserRouter>` with full site

**Path 3: API times out (>8s) or is unreachable**
- Network timeout or fetch hangs: after 8s the `setTimeout` fires → `setComingSoon(prev => prev === null ? false : prev)` → since still `null`, becomes `false` → full site shown
- If fetch rejects before 8s: `.catch()` → `setComingSoon(false)` → full site shown

### 8-Second Timeout Implementation

**Yes, it is implemented.** Exact code from `App.tsx` lines 27-43:

```tsx
const SITE_CONFIG_TIMEOUT_MS = 8000;

// Inside useEffect:
const timeout = setTimeout(() => {
  setComingSoon((prev) => (prev === null ? false : prev));
}, SITE_CONFIG_TIMEOUT_MS);

contentProvider.getSiteConfig()
  .then((config) => setComingSoon(config.comingSoon))
  .catch(() => setComingSoon(false))
  .finally(() => clearTimeout(timeout));

return () => clearTimeout(timeout);
```

### Flash Risk

**Yes, there is a brief flash.** On initial render, `comingSoon` is `null`. The render logic is `showComingSoon = comingSoon !== false`. Since `null !== false` is `true`, the Coming Soon page renders immediately on first paint, before the API response arrives. If the site should be live (`comingSoon: false`), users will see the Coming Soon page flash for ~100-500ms until the fetch resolves.

The `buildTimeComingSoon` variable is computed (line 24-25) but **never used in the render path**. It appears to be dead code that was intended to influence the initial state but was never wired up.

---

## 4. FALLBACK BEHAVIOR

### What happens when getLandingPage() fails?

In `Index.tsx`:

```tsx
const { data } = useLandingPage(slug);
const baseContent: LandingPageContent =
  data ?? (defaultLandingPageContent as unknown as LandingPageContent);
```

If `useLandingPage` fails (React Query `retry: 1`), `data` will be `undefined`. The component falls back to `defaultLandingPageContent` from `src/content/landing.ts`.

**However**, the `as unknown as LandingPageContent` cast is a code smell. The static fallback does not include the new `PricingContent` shape with `packs`, `postYearBehavior`, `billingType`, `planType`, etc. The static fallback uses the old `plans` array with `price`, `period`, `yearlyPrice`, `yearlyLabel`. See next section for details.

### Is defaultLandingPageContent complete and renderable?

**Partially.** It covers all 18 top-level keys of `LandingPageContent`. However, the `pricingContent` object in the static fallback uses the **new** `PackPlan` schema (with `packs`, `postYearBehavior`, etc.) — this was updated to match. Looking at the actual types:

- `LandingPageContent.pricingContent` is `PricingContent` with `packs: PackPlan[]`, `postYearBehavior`, `footerNote`
- The static fallback's `pricingContent` does have `packs` with `billingType`, `planType`, `applicationsIncluded`, etc.

**The static fallback appears structurally complete and should render.** The `as unknown as` cast is ugly but functional.

The bigger concern: if `usePricingContent()` also fails, the separate pricing fetch, `pricingData` will be `undefined`, and `baseContent.pricingContent` from the static fallback will be used. This path works.

---

## 5. ENVIRONMENT

### Env Vars Read at Runtime vs Build Time

All `VITE_*` env vars are **build-time** (inlined by Vite during `npm run build`). They are baked into the JavaScript bundle. There are no true runtime env vars.

| Variable | Used Where | Notes |
|----------|-----------|-------|
| `VITE_WP_API_URL` | `restProvider.ts` | Base URL for all API calls. Defaults to `https://wpcms.ccsprocert.com/wp-json` if not set. |
| `VITE_CONTENT_SOURCE` | `providerFactory.ts` | Selects content provider: `rest` (default) or `wordpress_graphql`. |
| `VITE_COMING_SOON` | `App.tsx` | Computed but **never used** (dead code). |
| `VITE_DEV_PASSWORD` | `PasswordGate.tsx` | Password gate for staging. |
| `VITE_WP_GRAPHQL_ENDPOINT` | `graphqlProvider.ts` | Phase 2 placeholder; not implemented. |

### Is VITE_WP_API_URL Set in Vercel?

Per `context.md` section 8, it should be set in Vercel project env. Cannot verify from code alone — needs checking in the Vercel dashboard. The `.env.example` shows the expected value: `https://wpcms.ccsprocert.com/wp-json`.

---

## 6. BROKEN OR MISSING

### TODOs, Hardcoded Placeholders, console.log, Commented-Out Blocks

| File | Issue | Details |
|------|-------|---------|
| `src/pages/NotFound.tsx:8` | `console.error` | Logs 404 path to console. Minor, intentional for debugging. |
| `src/components/landing/FounderSpotlight.tsx:16` | Comment | `{/* Photo Placeholder */}` — not a TODO, just a UI label. |
| `src/content/providers/graphqlProvider.ts` | Phase 2 stub | Entire file is a placeholder; throws `notConfigured()`. Not a problem unless `VITE_CONTENT_SOURCE=wordpress_graphql`. |
| `App.tsx:24-25` | Dead code | `buildTimeComingSoon` is computed but never referenced in the render or logic. |
| `.env.example:5-10` | Phase 2 vars | `VITE_CONTENT_SOURCE`, `VITE_WP_GRAPHQL_ENDPOINT` are listed but not needed yet. |

### Section Components with Hardcoded Text

| Component | Hardcoded Text | Severity |
|-----------|---------------|----------|
| `HeroSection.tsx` | `"Trusted by credentialing teams"` | NICE TO HAVE — cosmetic |
| `FeaturesGrid.tsx` | `"Learn more"` link text | NICE TO HAVE — cosmetic |
| `PacketPreview.tsx` | `"Download"`, `"Page {page}"`, `"SAMPLE - REDACTED"`, `"What's included in the packet"` | NICE TO HAVE — cosmetic |
| `Cards.tsx` (PricingCard) | `"billed annually"`, `"one-time"`, `"+ sales tax"`, `"Unlimited payer applications"`, `"payer application"`, `"Renews annually"`, `"Valid for"`, `"Need more? Add payers at..."` | NICE TO HAVE — formatting strings, acceptable for MVP |

All section components properly accept `content?: LandingPageContent` and use it via `content?.xxxContent ?? staticFallback` pattern. No component is completely ignoring its content prop.

### vercel.json SPA Rewrite

```json
"rewrites": [
  { "source": "/(.*)", "destination": "/index.html" }
]
```

**This is correct.** The pattern `/(.*)`  matches all paths including `/texas`, `/california`, etc. and rewrites to `index.html`, allowing React Router to handle client-side routing.

---

## 7. CRITICAL ISSUES FOUND

### Issue A: ACF Options Page Requires ACF Pro

The MU-plugin calls `acf_add_options_page()` which is an **ACF Pro-only** function. ACF Free does not provide this. This means:
1. The "CCS Pro Pricing" admin page in WordPress will not appear on ACF Free
2. The `/pricing` REST endpoint will return hardcoded defaults rather than editable content
3. Pricing data in the `/landing-page/{slug}` response will fall through to hardcoded defaults

**Impact:** Pricing cannot be edited via WordPress on ACF Free. The hardcoded defaults in `ccspro_map_pricing_packs()` will always be used.

### Issue B: Coming Soon Flash

Initial state is `null`, and `comingSoon !== false` evaluates to `true` for `null`. Every visitor sees the Coming Soon page flash for ~100-500ms before the API response sets `comingSoon` to `false`. This is a poor user experience for a live site.

### Issue C: buildTimeComingSoon Dead Code

`App.tsx` line 24-25 computes `buildTimeComingSoon` from `VITE_COMING_SOON` but never uses it. The original design intended this as the initial state when the API hasn't responded yet, but it was never wired in.

### Issue D: Pricing Content Provider Architecture

The frontend has a separate `usePricingContent()` hook that calls `GET /ccspro/v1/pricing`. This duplicates pricing data because the `/landing-page/{slug}` response already includes `pricingContent`. Index.tsx merges them:

```tsx
const content: LandingPageContent = {
  ...baseContent,
  pricingContent: pricingData ?? baseContent.pricingContent,
};
```

This means two API calls for the same data. The `/pricing` endpoint reads from the Options Page (ACF Pro), while the `/landing-page/{slug}` also calls `ccspro_get_pricing_content('option', $post_id)`. On ACF Free, both return the same hardcoded defaults.

### Issue E: No Error Boundary / No Loading State Guard

`Index.tsx` does not check for `isLoading` or `isError` from `useLandingPage()`. It immediately renders with `data ?? defaultLandingPageContent`. While this means no blank page (good), it also means:
- No loading skeleton is shown (the `LandingPageSkeleton` component exists but is never used in `Index.tsx`)
- If the API is slow, the static fallback renders first, then snaps to API content — causing a content flash

---

## 8. PRIORITIZED FIX LIST

### [BLOCKER] — Must fix for the 20% functional target

1. **[BLOCKER] ACF Options Page on ACF Free**
   The global pricing Options Page (`acf_add_options_page`) requires ACF Pro. Either:
   - (a) Move pricing fields onto the `landing_page` CPT (ACF Free compatible), or
   - (b) Use a custom WordPress options page with `update_option`/`get_option` (no ACF dependency), or
   - (c) Accept ACF Pro as a dependency (contradicts requirements).
   Without this fix, pricing is never editable from WordPress.

2. **[BLOCKER] Coming Soon Flash — Use buildTimeComingSoon**
   Wire `buildTimeComingSoon` into the initial `useState`:
   ```tsx
   const [comingSoon, setComingSoon] = useState<boolean | null>(
     buildTimeComingSoon ? true : null
   );
   ```
   And/or change the render logic so `null` (loading) shows a blank splash or skeleton instead of Coming Soon:
   ```tsx
   if (comingSoon === null) return <LoadingSplash />;
   if (comingSoon === true) return <ComingSoon />;
   // else render full site
   ```
   This eliminates the flash where live visitors briefly see Coming Soon.

3. **[BLOCKER] Verify WordPress has a published "default" landing page**
   The `/landing-page/default` endpoint returns 404 if no post with slug `default` exists. Need to confirm this post exists and has content filled in. If it doesn't, all visitors see the static fallback only.

4. **[BLOCKER] Remove dead `buildTimeComingSoon` or wire it in**
   Currently dead code. Either delete it or use it (see fix #2).

### [HIGH] — Important for reliability

5. **[HIGH] Loading skeleton not shown**
   `Index.tsx` never renders `LandingPageSkeleton`. Add a loading check:
   ```tsx
   const { data, isLoading } = useLandingPage(slug);
   if (isLoading) return <LandingPageSkeleton />;
   ```
   This prevents the content-flash from static → API data.

6. **[HIGH] Duplicate pricing fetch**
   Remove the separate `usePricingContent()` call and `/pricing` endpoint, or keep it only if pricing needs to be independently cacheable. Currently it creates a redundant API call on every page load (the landing-page response already includes `pricingContent`).

7. **[HIGH] WordPress setup guide says "ACF to REST API" is required**
   The guide (`docs/WORDPRESS_SETUP_GUIDE.md` section 2.2 and troubleshooting) lists "ACF to REST API" as required. The custom endpoint does NOT use it. Remove this requirement to avoid confusion. The plugin is harmless if installed, but it's not needed.

8. **[HIGH] getSiteConfig fallback returns `{ comingSoon: false }` on error**
   In `restProvider.ts` line 49: if the API returns a non-OK status, `getSiteConfig` silently returns `{ comingSoon: false }`. This is intentional (fail-open), but means a WordPress misconfiguration (e.g., broken MU-plugin) will make coming-soon mode impossible to enable. Consider logging a warning.

9. **[HIGH] Pricing section hardcoded defaults in PHP**
   `ccspro_map_pricing_packs()` (lines 1100-1147) auto-inserts a "Single Payer Pack" at $39 and an "Unlimited Annual" at $999 if no matching pack is found. These prices are hardcoded in PHP. If the admin partially fills out pricing, they'll get unexpected extra plans injected. This is confusing.

### [NICE TO HAVE] — Polish items

10. **[NICE TO HAVE] Hardcoded strings in components**
    - `HeroSection.tsx`: "Trusted by credentialing teams"
    - `FeaturesGrid.tsx`: "Learn more" link text
    - `PacketPreview.tsx`: "Download", "What's included in the packet"
    - `Cards.tsx` (PricingCard): billing label strings
    These could be moved to the content model but are acceptable for MVP.

11. **[NICE TO HAVE] console.error in NotFound.tsx**
    Logs the 404 path. Low priority; intentional debug aid.

12. **[NICE TO HAVE] GraphQL provider stub**
    `graphqlProvider.ts` is a Phase 2 placeholder. Not broken, just incomplete. Irrelevant unless `VITE_CONTENT_SOURCE=wordpress_graphql`.

13. **[NICE TO HAVE] `as unknown as LandingPageContent` cast in Index.tsx**
    The static fallback is cast unsafely. This works at runtime but suppresses type checking. A cleaner approach: make `defaultLandingPageContent` explicitly satisfy the `LandingPageContent` type.

14. **[NICE TO HAVE] `FounderSpotlight.tsx` — `{/* Photo Placeholder */}` comment**
    Visual placeholder for a founder photo. No functional impact.

---

## 9. SUMMARY TABLE — 20% FUNCTIONAL TARGET

| Goal | Status | Blocking Issue |
|------|--------|---------------|
| site-config endpoint working | ✅ MU-plugin registers route correctly | None for the endpoint itself |
| Coming-soon toggle functional | ⚠️ Flash bug | #2 (flash), #4 (dead code) |
| landing-page/default returning real ACF content | ⚠️ Depends on WP setup | #3 (need published post with slug `default`) |
| Hero renders from WP data | ✅ Component wired up | Depends on #3 |
| Pricing renders from WP data | ❌ Blocked by ACF Pro dependency | #1 (Options Page requires ACF Pro) |
| FAQ renders from WP data | ✅ Component wired up | Depends on #3 |
| Fallback to static content (no blank page) | ⚠️ Works but with content flash | #5 (no loading skeleton shown) |
| /texas slug routing | ✅ vercel.json rewrite + React Router `/:slug` | Needs a published `texas` landing page in WP |

### Recommended Fix Order

1. Fix ACF Pro dependency for pricing (BLOCKER #1)
2. Fix Coming Soon flash (BLOCKER #2)
3. Verify/create default landing page in WordPress (BLOCKER #3)
4. Wire up or remove dead `buildTimeComingSoon` (BLOCKER #4)
5. Add loading skeleton to Index.tsx (HIGH #5)
6. Clean up duplicate pricing fetch (HIGH #6)
7. Correct WordPress setup guide (HIGH #7)
