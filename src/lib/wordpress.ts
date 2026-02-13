/**
 * Backward-compatible WordPress REST client exports.
 * Primary source now lives under content providers.
 */

import { restProvider, WordPressAPIError } from "@/content/providers/restProvider";

export { restProvider, WordPressAPIError };
export type { SiteConfigResponse } from "@/content/providers/types";

export const getLandingPage = restProvider.getLandingPage;
export const getSiteConfig = restProvider.getSiteConfig;

export const wpClient = {
  getLandingPage,
  getSiteConfig,
};
