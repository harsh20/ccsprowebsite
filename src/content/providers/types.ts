import type { LandingPageContent } from "@/types/wordpress";

export interface SiteConfigResponse {
  comingSoon: boolean;
}

export interface ContentProvider {
  getLandingPage(slug?: string): Promise<LandingPageContent>;
  getSiteConfig(): Promise<SiteConfigResponse>;
}
