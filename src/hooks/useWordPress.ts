import { useQuery } from "@tanstack/react-query";
import { getContentProvider } from "@/content/providers";
import type { LandingPageContent, PricingContent } from "@/types/wordpress";

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

export function usePricingContent() {
  return useQuery<PricingContent>({
    queryKey: ["pricing-content"],
    queryFn: () => contentProvider.getPricingContent(),
    staleTime: STALE_TIME_MS,
    retry: 1,
  });
}
