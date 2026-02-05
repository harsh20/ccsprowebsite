# CCS Pro Landing Page — Requirements

## 1. Project goals

- Provide a marketing landing page for **CCS Pro** (credentialing packets for US healthcare providers).
- Allow non-developers to edit copy, CTAs, pricing, FAQ, and other sections via a CMS without redeploying the frontend.
- Support multiple landing page variations (e.g. default, state-specific like Texas) with different content and optional images.
- Allow the site to be shown as “Coming Soon” or live without code changes or redeploys.

---

## 2. Functional requirements

### 2.1 Content and sections

The landing page must support the following content areas, all editable via the CMS:

| Section | Content type | Notes |
|--------|--------------|--------|
| Site config | Site name, tagline, short description | Used in header and meta. |
| Navigation | Menu links (label + href), primary/secondary/sign-in CTAs | Header and mobile menu. |
| Hero | Headline (with optional highlight), subheadline, primary/secondary/tertiary CTAs, trust indicators (icon + text), dashboard mock data (title, subtitle, completion %, documents list, buttons) | Hero + dashboard card. |
| Verification / logo strip | Headline, list of items (icon + label) | Trust/verification strip. |
| Founder spotlight | Name, title, initials, quote, bullet list | Founder testimonial block. |
| Problem / outcome | List of problem cards (icon, title, description), outcome text (prefix, middle, suffix) | Problem–solution section. |
| How it works | Section title/subtitle, steps (icon, step number, title, description), readiness note (label + states) | 3-step process. |
| Features | Section title/subtitle, feature list (icon, title, description, link) | Features grid. |
| Packet preview | Section title/subtitle, file name, checklist, CTA (label + href) | Sample packet section. |
| Security | Badge, title, subtitle, feature list (icon + text), CTA, floating badges | Security section. |
| CAQH Concierge | Badge, title, subtitle, benefits title, benefits list, CTA, consent title, consent modes (icon, title, description), always-included (icon, title, description) | Add-on service block. |
| Pricing | Section title/subtitle, plans (name, price, period, description, features, CTA, highlighted, badge, optional yearly price/label), additional info (update price, refund policy, refund link) | Pricing cards. |
| Support | Section title/subtitle, feature list (icon + text), links (label + href) | Support section. |
| Team | Section title/subtitle, members (name, role, icon, bio) | Team cards. |
| FAQ | Section title/subtitle, items (question, answer) | Accordion FAQ. |
| Final CTA | Headline, subheadline, primary/secondary CTAs | Bottom CTA block. |
| Footer | Brand name/description, trust badges (icon + text), legal links, support links, copyright | Footer. |

- Icon fields are stored as string names (e.g. `Shield`, `CheckCircle`); the frontend maps them to Lucide React icons.
- All sections must work when content is empty or missing (fallback to static defaults or hide section as appropriate).

### 2.2 Multiple landing page variations

- Support multiple “landing page” variants (e.g. default, texas, california).
- Each variant is a single CMS entity (one Landing Page post) containing all section content for that variant.
- URL mapping: `/` → default variant; `/:slug` → variant with that slug (e.g. `/texas`).
- Creating a new variant must not require frontend code changes or redeploy; duplicate an existing Landing Page post and edit content.

### 2.3 Coming soon mode

- **Toggle:** Site can show either a “Coming Soon” page or the full landing page.
- **Control:** Toggle is controlled from the CMS (WordPress), not from build-time env only. Changing the toggle must take effect without redeploying the frontend.
- **Behavior:** On each load, the frontend fetches a site-config value. If “coming soon” is enabled, only the Coming Soon page is rendered; otherwise the full landing page is rendered.
- **Fallback:** If the site-config request fails or times out (e.g. 8s), the full site is shown so the site remains usable.

### 2.4 Offline / API-unavailable behavior

- If the WordPress API is unreachable when loading landing content, the frontend must show the full landing page using static fallback content (no blank or broken page).
- Optional: show a loading skeleton while landing content is being fetched.

---

## 3. Non-functional requirements

### 3.1 Performance

- First load: fetch site-config and landing content; avoid unnecessary duplicate requests (e.g. cache landing response per slug with a reasonable stale time, e.g. 5 minutes).
- Use cache headers where appropriate (e.g. static assets); site-config can use cache-busting so the toggle responds quickly.

### 3.2 Security and privacy

- CMS and API are for content only; no sensitive user data stored in landing page or site-config.
- CORS must restrict API access to the known frontend origin(s) and localhost for development.
- WordPress admin and API should use HTTPS; credentials and secrets must not be committed.

### 3.3 Compatibility

- CMS must work with **ACF Free** (no ACF Options Pages). All editable content must be attached to the Landing Page CPT or equivalent.
- Frontend must run in modern evergreen browsers; no requirement to support legacy browsers without JS.

### 3.4 Deployment and operations

- Frontend: deployable as a static build (e.g. Vercel); no server-side rendering required.
- Content and coming-soon changes must not require a frontend redeploy.
- WordPress setup (DNS, hosting, plugins, MU-plugin) must be documentable so a new environment can be set up from docs.

---

## 4. Out of scope (for this document)

- User authentication, sign-up, or sign-in flows on the landing site.
- Payment or checkout (pricing is informational only).
- Blog, news, or other post types beyond the Landing Page CPT as used for this landing experience.
- ACF Pro or paid plugins; design assumes ACF Free.

---

## 5. References

- **context.md** — Project context, domains, repo layout, what was implemented.
- **architecture.md** — Technical architecture, APIs, data flow.
- **docs/WORDPRESS_SETUP_GUIDE.md** — WordPress and CMS setup.
