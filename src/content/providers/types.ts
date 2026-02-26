import type {
  LandingPageContent,
  SiteConfigResponse,
  MenusResponse,
  PricingPageContent,
  AboutPageContent,
  ContactPageContent,
} from "@/types/wordpress";

export type { SiteConfigResponse } from "@/types/wordpress";

export interface ContentProvider {
  getLandingPage(slug?: string): Promise<LandingPageContent>;
  getSiteConfig(): Promise<SiteConfigResponse>;
  getMenus(): Promise<MenusResponse>;
  getPricingPage(): Promise<PricingPageContent>;
  getAboutPage(): Promise<AboutPageContent>;
  getContactPage(): Promise<ContactPageContent>;
}
