/**
 * Backward-compatible WordPress REST client exports.
 * Primary source now lives under content providers.
 */

import { restProvider, WordPressAPIError } from "@/content/providers/restProvider";

export { restProvider, WordPressAPIError };
export type { SiteConfigResponse } from "@/content/providers/types";

export const getLandingPage = restProvider.getLandingPage;
export const getSiteConfig = restProvider.getSiteConfig;
export const getPricingPage = restProvider.getPricingPage;
export const getAboutPage = restProvider.getAboutPage;
export const getContactPage = restProvider.getContactPage;

export const wpClient = {
  getLandingPage,
  getSiteConfig,
  getPricingPage,
  getAboutPage,
  getContactPage,
};
