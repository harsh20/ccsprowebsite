import type { LandingPageContent, PricingContent } from "@/types/wordpress";

export interface SiteConfigResponse {
  comingSoon: boolean;
}

export interface ContentProvider {
  getLandingPage(slug?: string): Promise<LandingPageContent>;
  getPricingContent(): Promise<PricingContent>;
  getSiteConfig(): Promise<SiteConfigResponse>;
}
