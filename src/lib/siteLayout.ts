import { mockSiteSettings } from "@/content/mockData";
import type {
  FooterData,
  HeaderData,
  MenusResponse,
  SiteConfigResponse,
} from "@/types/wordpress";

export function buildSiteLayoutData(
  siteConfig?: SiteConfigResponse,
  menus?: MenusResponse,
): {
  headerData: HeaderData;
  footerData: FooterData;
} {
  const headerData = siteConfig?.header
    ? {
        logo: siteConfig.header.logoText,
        logoUrl: siteConfig.header.logoUrl,
        ctaButton: siteConfig.header.ctaButton,
        secondaryLink: siteConfig.header.signinLink,
        primaryNav: menus?.primaryNav ?? mockSiteSettings.header.primaryNav,
      }
    : mockSiteSettings.header;

  const [defaultCol1, defaultCol2, defaultCol3] = mockSiteSettings.footer.columns;
  const footerData = siteConfig?.footer
    ? {
        brand: {
          name: siteConfig.footer.brandName,
          tagline: siteConfig.footer.tagline,
        },
        trustBadges: siteConfig.footer.trustBadges,
        copyright: siteConfig.footer.copyright,
        columns: [
          { title: defaultCol1.title, links: menus?.footerCol1 ?? defaultCol1.links },
          { title: defaultCol2.title, links: menus?.footerCol2 ?? defaultCol2.links },
          { title: defaultCol3.title, links: menus?.footerCol3 ?? defaultCol3.links },
        ],
      }
    : mockSiteSettings.footer;

  return { headerData, footerData };
}
