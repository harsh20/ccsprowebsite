/**
 * WordPress REST API client for landing page content.
 * Fetches from /wp-json/ccspro/v1/landing-page/{slug}
 */

import type { LandingPageContent } from "@/types/wordpress";

const WP_API_URL =
  import.meta.env.VITE_WP_API_URL ?? "https://wpcms.ccsprocert.com/wp-json";

export class WordPressAPIError extends Error {
  constructor(
    message: string,
    public status?: number,
    public slug?: string
  ) {
    super(message);
    this.name = "WordPressAPIError";
  }
}

/**
 * Fetch full landing page content for a given slug.
 * @param slug - Landing page slug (e.g. "default", "texas", "california")
 * @returns Parsed landing page content
 * @throws WordPressAPIError on non-OK response or invalid JSON
 */
export async function getLandingPage(
  slug: string = "default"
): Promise<LandingPageContent> {
  const url = `${WP_API_URL}/ccspro/v1/landing-page/${slug}`;
  const response = await fetch(url, {
    method: "GET",
    headers: {
      Accept: "application/json",
    },
  });

  if (!response.ok) {
    const status = response.status;
    let message = `Landing page not found: ${slug}`;
    if (status === 404) {
      message = `Landing page "${slug}" not found`;
    } else if (status >= 500) {
      message = "WordPress API error. Try again later.";
    }
    throw new WordPressAPIError(message, status, slug);
  }

  const data = await response.json();

  if (!data || typeof data !== "object") {
    throw new WordPressAPIError("Invalid API response", response.status, slug);
  }

  return data as LandingPageContent;
}

export interface SiteConfigResponse {
  comingSoon: boolean;
}

/**
 * Fetch site config (e.g. coming soon mode). Used at runtime so the flag can be toggled from WordPress without redeploying.
 */
export async function getSiteConfig(): Promise<SiteConfigResponse> {
  const url = `${WP_API_URL}/ccspro/v1/site-config`;
  const response = await fetch(url, {
    method: "GET",
    headers: { Accept: "application/json" },
  });
  if (!response.ok) {
    return { comingSoon: false };
  }
  const data = await response.json();
  return {
    comingSoon: Boolean(data?.comingSoon),
  };
}

export const wpClient = {
  getLandingPage,
  getSiteConfig,
};
