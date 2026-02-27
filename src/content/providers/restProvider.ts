import type {
  LandingPageContent,
  MenusResponse,
  SiteConfigResponse,
  PricingPageContent,
  AboutPageContent,
  ContactPageContent,
  ContactFormPayload,
  ContactSubmitResponse,
} from "@/types/wordpress";
import type { ContentProvider } from "./types";

const WP_API_URL =
  import.meta.env.VITE_WP_API_URL ?? "https://wpcms.ccsprocert.com/wp-json";

function freshUrl(path: string): string {
  const sep = path.includes("?") ? "&" : "?";
  return `${WP_API_URL}${path}${sep}_t=${Date.now()}`;
}

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
  const response = await fetch(freshUrl(`/ccspro/v1/landing-page/${slug}`), {
    method: "GET",
    headers: { Accept: "application/json" },
    cache: "no-store",
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
  const response = await fetch(
    freshUrl("/ccspro/v1/site-config"),
    { cache: "no-store" }
  );
  if (!response.ok) {
    return {
      comingSoon: false,
      header: {
        logoUrl: null,
        logoText: "CCS Pro",
        ctaButton: { label: "Get Started", href: "#" },
        signinLink: { label: "Sign In", href: "#" },
      },
      footer: {
        brandName: "CCS Pro",
        tagline: "Credentialing packets. Done once. Ready always.",
        trustBadges: [],
        copyright: "© 2025 CCS Pro. All rights reserved.",
      },
    };
  }
  return response.json();
}

async function getMenus(): Promise<MenusResponse> {
  try {
    const response = await fetch(freshUrl("/ccspro/v1/menus"), { cache: "no-store" });
    if (!response.ok) {
      return {
        primaryNav: [],
        footerCol1: [],
        footerCol2: [],
        footerCol3: [],
      };
    }
    return response.json();
  } catch {
    return {
      primaryNav: [],
      footerCol1: [],
      footerCol2: [],
      footerCol3: [],
    };
  }
}

async function getPricingPage(): Promise<PricingPageContent> {
  const response = await fetch(freshUrl("/ccspro/v1/page/pricing"), {
    method: "GET",
    headers: { Accept: "application/json" },
    cache: "no-store",
  });
  if (!response.ok) {
    throw new WordPressAPIError("Failed to fetch pricing page", response.status);
  }
  return response.json();
}

async function getAboutPage(): Promise<AboutPageContent> {
  const response = await fetch(freshUrl("/ccspro/v1/page/about"), {
    method: "GET",
    headers: { Accept: "application/json" },
    cache: "no-store",
  });
  if (!response.ok) {
    throw new WordPressAPIError("Failed to fetch about page", response.status);
  }
  return response.json();
}

async function getContactPage(): Promise<ContactPageContent> {
  const response = await fetch(freshUrl("/ccspro/v1/page/contact"), {
    method: "GET",
    headers: { Accept: "application/json" },
    cache: "no-store",
  });
  if (!response.ok) {
    throw new WordPressAPIError("Failed to fetch contact page", response.status);
  }
  return response.json();
}

async function submitContactForm(
  data: ContactFormPayload
): Promise<ContactSubmitResponse> {
  const response = await fetch(freshUrl("/ccspro/v1/contact/submit"), {
    method: "POST",
    headers: { "Content-Type": "application/json", Accept: "application/json" },
    body: JSON.stringify(data),
  });
  if (!response.ok) {
    const err = await response.json().catch(() => ({}));
    const msg =
      (err as { message?: string }).message ??
      "Something went wrong. Please try again.";
    throw new WordPressAPIError(msg, response.status);
  }
  return response.json();
}

export const restProvider: ContentProvider = {
  getLandingPage,
  getSiteConfig,
  getMenus,
  getPricingPage,
  getAboutPage,
  getContactPage,
  submitContactForm,
};
