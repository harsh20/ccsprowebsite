import type { ContentProvider } from "./types";
import type {
  LandingPageContent,
  MenusResponse,
  SiteConfigResponse,
} from "@/types/wordpress";

const WP_GRAPHQL_ENDPOINT = import.meta.env.VITE_WP_GRAPHQL_ENDPOINT ?? "";

function notConfigured(feature: string): never {
  throw new Error(
    `${feature} is not configured. Set VITE_WP_GRAPHQL_ENDPOINT and implement wordpressGraphqlProvider before enabling VITE_CONTENT_SOURCE=wordpress_graphql.`
  );
}

async function getLandingPage(_slug: string = "default"): Promise<LandingPageContent> {
  if (!WP_GRAPHQL_ENDPOINT) {
    notConfigured("GraphQL landing content provider");
  }

  // Phase 2: implement WPGraphQL query and mapping to LandingPageContent.
  notConfigured("GraphQL landing content provider");
}

async function getSiteConfig(): Promise<SiteConfigResponse> {
  if (!WP_GRAPHQL_ENDPOINT) {
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

  // Phase 2: fetch site config through GraphQL options/settings.
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

async function getMenus(): Promise<MenusResponse> {
  return {
    primaryNav: [],
    footerCol1: [],
    footerCol2: [],
    footerCol3: [],
  };
}

export const wordpressGraphqlProvider: ContentProvider = {
  getLandingPage,
  getSiteConfig,
  getMenus,
};
