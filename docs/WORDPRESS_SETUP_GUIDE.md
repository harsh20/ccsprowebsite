# WordPress Headless CMS Setup Guide

This guide walks you through setting up WordPress as a headless CMS for the CCS Pro landing page. The frontend is deployed on Vercel (ccsprocert.com) and the CMS on Hostinger (wpcms.ccsprocert.com).

---

## 1. DNS Configuration (CloudFlare)

Your domain is on GoDaddy but managed via CloudFlare.

### 1.1 Add DNS Records

1. Log in to [CloudFlare](https://dash.cloudflare.com) and select your domain.
2. Go to **DNS** > **Records**.

**For WordPress CMS (Hostinger):**

| Type | Name | Content | Proxy | TTL |
|------|------|---------|-------|-----|
| A | wpcms | \<Hostinger server IP\> | Proxied (orange cloud) or DNS only | Auto |

Full hostname: `wpcms.ccsprocert.com`

**For Frontend (Vercel):**

| Type | Name | Content | Proxy | TTL |
|------|------|---------|-------|-----|
| CNAME | @ | cname.vercel-dns.com | DNS only (grey cloud) | Auto |
| CNAME | www | cname.vercel-dns.com | DNS only | Auto |

Or use the A record Vercel provides when you add the custom domain in the Vercel dashboard.

### 1.2 SSL/TLS

1. Go to **SSL/TLS** in CloudFlare.
2. Set encryption mode to **Full (strict)**.
3. Ensure **Always Use HTTPS** is ON under SSL/TLS > Edge Certificates.

### 1.3 Optional: Cache API Responses

If you use CloudFlare caching, create a **Page Rule** or **Cache Rule** for:
- URL: `wpcms.ccsprocert.com/wp-json/ccspro/*`
- Setting: Cache Level = Standard, Edge TTL = 5 minutes (or as needed)

---

## 2. WordPress Installation (Hostinger)

### 2.1 Create Hosting and Install WordPress

1. Log in to [Hostinger](https://www.hostinger.com) hPanel.
2. Create a new website or use an existing one.
3. Point the domain to `wpcms.ccsprocert.com` (or add it as the primary domain).
4. Install WordPress via **Website** > **Auto Installer** (or one-click WordPress).
5. Complete the WordPress setup (admin user, password, email).

### 2.2 Install Required Plugins

In WordPress Admin, go to **Plugins** > **Add New** and install:

| Plugin | Purpose |
|--------|---------|
| **Advanced Custom Fields** | Free. Defines and stores all landing page fields. |
| **ACF to REST API** | Exposes ACF field values in REST API responses. |

Optional (for performance):

| Plugin | Purpose |
|--------|---------|
| **WP REST Cache** | Caches REST API responses. |
| **Disable XML-RPC** | Security; disables XML-RPC if not needed. |

### 2.3 Install the MU-Plugin

1. In your project, the MU-plugin file is at:  
   `wordpress/mu-plugins/ccspro-cpt-acf.php`
2. Upload it to your WordPress site:
   - Via FTP/SFTP: Upload to `wp-content/mu-plugins/ccspro-cpt-acf.php`
   - Or via Hostinger File Manager: Navigate to `public_html/wp-content/`, create folder `mu-plugins` if it doesn’t exist, and upload `ccspro-cpt-acf.php` there.
3. MU-plugins load automatically; no need to activate in the Plugins screen.
4. Confirm in Admin: you should see **Landing Pages** in the sidebar.

---

## 3. WordPress Configuration

### 3.1 wp-config.php

Edit `wp-config.php` (via File Manager or FTP). Add or adjust **before** “That’s all, stop editing!”:

```php
// Site URLs (use your actual CMS domain)
define('WP_SITEURL', 'https://wpcms.ccsprocert.com');
define('WP_HOME', 'https://wpcms.ccsprocert.com');

// Disable XML-RPC for security (optional)
define('XMLRPC_ENABLED', false);
```

### 3.2 Permalinks (Required for REST API)

1. Go to **Settings** > **Permalinks**.
2. Choose **Post name** (e.g. `https://wpcms.ccsprocert.com/sample-post/`).
3. Click **Save Changes**.

### 3.3 CORS (Cross-Origin Requests)

The MU-plugin adds CORS headers so the Vercel frontend (ccsprocert.com) can call the WordPress API. Allowed origin is set in the plugin; if you use a different frontend domain, update the CORS origin in `ccspro-cpt-acf.php`.

---

## 4. Create Your First Landing Page

1. In WordPress Admin, go to **Landing Pages** > **Add New**.
2. Set the **title** (e.g. “Default Landing”) and **slug** to `default` (used for the homepage).
3. Fill in the ACF field groups (Site Config, Hero, Navigation, Features, Pricing, FAQ, etc.). You can leave optional fields empty; the frontend will fall back to defaults or hide empty sections.
4. Click **Publish**.

To add another variation (e.g. Texas):

1. **Landing Pages** > **Add New** (or duplicate the default one).
2. Set slug to `texas` and fill in the content.
3. The frontend will serve it at `https://ccsprocert.com/texas`.

---

## 5. Verify the API

1. Open:  
   `https://wpcms.ccsprocert.com/wp-json/ccspro/v1/landing-page/default`
2. You should see JSON with all landing page content (siteConfig, hero, nav, features, pricing, faq, etc.).
3. If you get 404, check that:
   - Permalinks are set to “Post name”.
   - A landing page with slug `default` exists and is published.
   - The MU-plugin is in `wp-content/mu-plugins/ccspro-cpt-acf.php`.

---

## 6. Frontend Environment Variable

In your frontend (Vercel or local `.env`), set:

```env
VITE_WP_API_URL=https://wpcms.ccsprocert.com/wp-json
```

For local development, create a `.env` file in the project root with this line. For Vercel, add it in **Project** > **Settings** > **Environment Variables**.

---

## 7. Troubleshooting

| Issue | Check |
|-------|------|
| 404 on `/wp-json/ccspro/v1/landing-page/default` | Permalinks = Post name; MU-plugin present; landing page with slug `default` published. |
| CORS errors in browser | MU-plugin CORS logic is active; allowed origin matches your frontend (e.g. `https://ccsprocert.com`). |
| ACF fields not in API response | Install and activate “ACF to REST API”; our custom endpoint reads ACF from the post, so the MU-plugin must be loading. |
| Blank or broken frontend | Confirm `VITE_WP_API_URL` is set and the fetch to `/wp-json/ccspro/v1/landing-page/{slug}` returns valid JSON. |

---

## 8. Security Notes

- Keep WordPress, themes, and plugins updated.
- Use a strong admin password and 2FA if available.
- Restrict admin access by IP if your host supports it.
- The REST endpoint used by the frontend is read-only and public; no sensitive data should be stored in landing page content.
