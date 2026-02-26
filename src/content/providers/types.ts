import type {
  LandingPageContent,
  SiteConfigResponse,
  MenusResponse,
} from "@/types/wordpress";

export type { SiteConfigResponse } from "@/types/wordpress";

export interface ContentProvider {
  getLandingPage(slug?: string): Promise<LandingPageContent>;
  getSiteConfig(): Promise<SiteConfigResponse>;
  getMenus(): Promise<MenusResponse>;
}
