import type {
  LandingPageContent,
  SiteConfigResponse,
  MenusResponse,
  PricingPageContent,
  AboutPageContent,
  ContactPageContent,
  ContactFormPayload,
  ContactSubmitResponse,
} from "@/types/wordpress";

export type { SiteConfigResponse } from "@/types/wordpress";

export interface ContentProvider {
  getLandingPage(slug?: string): Promise<LandingPageContent>;
  getSiteConfig(): Promise<SiteConfigResponse>;
  getMenus(): Promise<MenusResponse>;
  getPricingPage(): Promise<PricingPageContent>;
  getAboutPage(): Promise<AboutPageContent>;
  getContactPage(): Promise<ContactPageContent>;
  submitContactForm(data: ContactFormPayload): Promise<ContactSubmitResponse>;
}
