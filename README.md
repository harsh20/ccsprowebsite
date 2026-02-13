# CCS Pro Landing Page

Landing page for CCS Pro - credentialing packets for US providers. Built with React, Vite, TypeScript, and Tailwind CSS. Content is managed via WordPress (headless CMS).

## Tech stack

- Vite
- TypeScript
- React
- shadcn/ui
- Tailwind CSS
- WordPress REST API (headless CMS)
- Content provider abstraction (REST active, GraphQL stubbed)

## Local development

```sh
git clone <YOUR_GIT_URL>
cd ccsprowebsite
npm i
npm run dev
```

Set `VITE_WP_API_URL` in `.env` (see `.env.example`) to point at your WordPress API.

## Environment variables

```env
VITE_WP_API_URL=https://wpcms.ccsprocert.com/wp-json
VITE_CONTENT_SOURCE=rest
# VITE_WP_GRAPHQL_ENDPOINT=https://wpcms.ccsprocert.com/graphql
```

`VITE_CONTENT_SOURCE`:
- `rest` (default, current production path)
- `wordpress_graphql` (phase 2 target; currently stubbed)

## Deploy

Build: `npm run build`. Deploy the `dist` folder to Vercel or any static host. Add required env vars in your deployment platform.

## WordPress setup

See [docs/WORDPRESS_SETUP_GUIDE.md](docs/WORDPRESS_SETUP_GUIDE.md) for CMS setup, ACF, and the MU-plugin.

## GraphQL cutover checklist (phase 2)

1. Enable and configure WPGraphQL on `wpcms.ccsprocert.com`.
2. Implement `src/content/providers/graphqlProvider.ts` queries.
3. Map GraphQL payloads to `LandingPageContent` in `src/types/wordpress.ts` shape.
4. Validate `/` and `/:slug` rendering parity against REST.
5. Set `VITE_CONTENT_SOURCE=wordpress_graphql` in Vercel preview.
6. Smoke test Coming Soon behavior through provider.
7. Promote env switch to production after parity verification.
