import { useQuery } from "@tanstack/react-query";
import { getContentProvider } from "@/content/providers";
import type { SiteConfigResponse } from "@/content/providers";
import type {
  LandingPageContent,
  MenusResponse,
  PricingPageContent,
  AboutPageContent,
  ContactPageContent,
} from "@/types/wordpress";

const STALE_TIME_MS = 5 * 60 * 1000; // 5 minutes
const contentProvider = getContentProvider();

/**
 * Fetches landing page content from WordPress by slug.
 * Caches result for 5 minutes.
 */
export function useLandingPage(slug: string = "default") {
  return useQuery<LandingPageContent>({
    queryKey: ["landing-page", slug],
    queryFn: () => contentProvider.getLandingPage(slug),
    staleTime: STALE_TIME_MS,
    retry: 1,
  });
}

export function useSiteConfig() {
  return useQuery<SiteConfigResponse>({
    queryKey: ["site-config"],
    queryFn: () => contentProvider.getSiteConfig(),
    staleTime: 0, // always fresh -- controls coming-soon toggle
    retry: 1,
  });
}

export function useMenus() {
  return useQuery<MenusResponse>({
    queryKey: ["menus"],
    queryFn: () => contentProvider.getMenus(),
    staleTime: 5 * 60 * 1000,
    retry: 1,
  });
}

export function usePricingPage() {
  return useQuery<PricingPageContent>({
    queryKey: ["pricing-page"],
    queryFn: () => contentProvider.getPricingPage(),
    staleTime: STALE_TIME_MS,
    retry: 1,
  });
}

export function useAboutPage() {
  return useQuery<AboutPageContent>({
    queryKey: ["about-page"],
    queryFn: () => contentProvider.getAboutPage(),
    staleTime: STALE_TIME_MS,
    retry: 1,
  });
}

export function useContactPage() {
  return useQuery<ContactPageContent>({
    queryKey: ["contact-page"],
    queryFn: () => contentProvider.getContactPage(),
    staleTime: STALE_TIME_MS,
    retry: 1,
  });
}
