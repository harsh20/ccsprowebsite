# Changelog

All notable changes to this repository are documented in this file.

## 2026-02-26 (hero ACF gap fixes)

### Added
- `hero_headline_suffix` ACF text field (Hero tab, after Highlight Word) with default value `"Ready Always."`. Registered in MU-plugin with PHP `?: 'Ready Always.'` fallback in the REST response so it degrades safely on fresh installs before the field is saved.
- `headlineSuffix: string` property added to the `HeroContent` TypeScript interface in `src/types/wordpress.ts`.
- `headlineSuffix: "Ready Always."` added to `mockHomePage.hero` in `src/content/mockData.ts` and to `heroContent` in `src/content/landing.ts`.

### Changed
- `HeroSection.tsx` line 25: replaced hardcoded `{" "}Ready Always.` with `{hero.headlineSuffix && \` ${hero.headlineSuffix}\`}`. The suffix is now fully CMS-editable.
- `HomePage.tsx`: `dashboardData` is now derived from `landingData?.heroDashboard` (API-first with `mockHomePage.heroDashboard` fallback guarded by `heroDashboard.title`), matching the same pattern used for `heroData`. Previously it was always hardcoded to the mock.

### Fixed
- Hero dashboard fields (`Dashboard Title`, `Subtitle`, `Completion %`, `Documents`, `Buttons`) edited in WordPress admin now actually update the live homepage hero card.

---

## 2026-02-26

### Added
- Homepage live data hooks: `useLandingPage("default")`, `useSiteConfig()`, and `useMenus()`.
- New API-aligned types in `src/types/wordpress.ts`:
  - `PricingCardData`, `PricingContentV2`
  - `GlobalHeaderData`, `GlobalFooterData`, `SiteConfigResponse`
  - `MenusResponse`
- `getMenus()` to the content provider contract and implementations.

### Changed
- `src/pages/HomePage.tsx` now merges WordPress API data with `mockData.ts` fallbacks.
- `src/content/providers/restProvider.ts` now returns full `site-config` payload (comingSoon + header + footer), and consumes menus endpoint.
- `src/hooks/useWordPress.ts` now exports `useSiteConfig()` and `useMenus()`.
- `src/components/landing/Header.tsx` now resolves logo in order: API `logoUrl` -> static `ccsLogo` asset -> text fallback, with above-the-fold image priority hints (`loading="eager"` and `fetchPriority="high"`).
- `src/types/wordpress.ts` `HeaderData` now includes optional `logoUrl?: string | null`.
- Ecosystem schema renamed across types/UI/mock data:
  - `provider` -> `providerAction`
  - `group` -> `groupOutcome`

### Fixed
- Prevented `/:slug` pricing crashes caused by schema mismatch by guarding legacy `PricingSection` when `packs` is missing.
- Ensured homepage FAQ falls back to mock items when API returns an empty `faqContent.items` array.

### Notes
- Added documentation sync enforcement to project guidance so substantive code edits update relevant markdown docs in the same change set.
