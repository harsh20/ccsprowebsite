import type { LandingPageContent, PricingContent } from "@/types/wordpress";
import type { ContentProvider, SiteConfigResponse } from "./types";

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

async function getLandingPage(slug: string = "default"): Promise<LandingPageContent> {
  const url = `${WP_API_URL}/ccspro/v1/landing-page/${slug}`;
  const response = await fetch(url, {
    method: "GET",
    headers: { Accept: "application/json" },
  });

  if (!response.ok) {
    const status = response.status;
    let message = `Landing page not found: ${slug}`;
    if (status === 404) message = `Landing page "${slug}" not found`;
    if (status >= 500) message = "WordPress API error. Try again later.";
    throw new WordPressAPIError(message, status, slug);
  }

  const data = await response.json();
  if (!data || typeof data !== "object") {
    throw new WordPressAPIError("Invalid API response", response.status, slug);
  }

  return data as LandingPageContent;
}

async function getSiteConfig(): Promise<SiteConfigResponse> {
  const url = `${WP_API_URL}/ccspro/v1/site-config?t=${Date.now()}`;
  const response = await fetch(url, {
    method: "GET",
    headers: { Accept: "application/json" },
    cache: "no-store",
  });

  if (!response.ok) return { comingSoon: false };

  const data = await response.json();
  return { comingSoon: Boolean(data?.comingSoon) };
}

async function getPricingContent(): Promise<PricingContent> {
  const url = `${WP_API_URL}/ccspro/v1/pricing?t=${Date.now()}`;
  const response = await fetch(url, {
    method: "GET",
    headers: { Accept: "application/json" },
    cache: "no-store",
  });

  if (!response.ok) {
    throw new WordPressAPIError("Pricing API error. Try again later.", response.status);
  }

  const data = await response.json();
  if (!data || typeof data !== "object") {
    throw new WordPressAPIError("Invalid pricing API response", response.status);
  }

  return data as PricingContent;
}

export const restProvider: ContentProvider = {
  getLandingPage,
  getPricingContent,
  getSiteConfig,
};
