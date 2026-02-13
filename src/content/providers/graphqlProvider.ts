import type { ContentProvider, SiteConfigResponse } from "./types";
import type { LandingPageContent, PricingContent } from "@/types/wordpress";

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
    return { comingSoon: false };
  }

  // Phase 2: fetch site config through GraphQL options/settings.
  return { comingSoon: false };
}

async function getPricingContent(): Promise<PricingContent> {
  if (!WP_GRAPHQL_ENDPOINT) {
    notConfigured("GraphQL pricing content provider");
  }

  // Phase 2: implement WPGraphQL query and mapping to PricingContent.
  notConfigured("GraphQL pricing content provider");
}

export const wordpressGraphqlProvider: ContentProvider = {
  getLandingPage,
  getPricingContent,
  getSiteConfig,
};
