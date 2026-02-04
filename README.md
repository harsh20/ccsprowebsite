# CCS Pro Landing Page

Landing page for CCS Pro — credentialing packets for US providers. Built with React, Vite, TypeScript, and Tailwind CSS. Content is managed via WordPress (headless CMS).

## Tech stack

- Vite
- TypeScript
- React
- shadcn/ui
- Tailwind CSS
- WordPress REST API (headless CMS)

## Local development

```sh
git clone <YOUR_GIT_URL>
cd ccsprowebsite
npm i
npm run dev
```

Set `VITE_WP_API_URL` in `.env` (see `.env.example`) to point at your WordPress API.

## Deploy

Build: `npm run build`. Deploy the `dist` folder to Vercel or any static host. Add `VITE_WP_API_URL` as an environment variable in your deployment platform.

## WordPress setup

See [docs/WORDPRESS_SETUP_GUIDE.md](docs/WORDPRESS_SETUP_GUIDE.md) for CMS setup, ACF, and the MU-plugin.
