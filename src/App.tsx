import { useEffect, useState } from "react";
import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import { getSiteConfig } from "@/lib/wordpress";
import Index from "./pages/Index";
import NotFound from "./pages/NotFound";
import ComingSoon from "./pages/ComingSoon";

const queryClient = new QueryClient();

// Build-time fallback (Vercel env). Runtime check from WordPress takes precedence.
const buildTimeComingSoon =
  String(import.meta.env.VITE_COMING_SOON ?? "").toLowerCase().trim() === "true";

const SITE_CONFIG_TIMEOUT_MS = 8000;

const App = () => {
  const [comingSoon, setComingSoon] = useState<boolean | null>(null);

  useEffect(() => {
    const timeout = setTimeout(() => {
      setComingSoon((prev) => (prev === null ? false : prev));
    }, SITE_CONFIG_TIMEOUT_MS);

    getSiteConfig()
      .then((config) => setComingSoon(config.comingSoon))
      .catch(() => setComingSoon(false))
      .finally(() => clearTimeout(timeout));

    return () => clearTimeout(timeout);
  }, []);

  // Show Coming Soon while loading (null) or when API says true; show full site only when API says false or after timeout/failure
  const showComingSoon = comingSoon !== false;

  return (
    <QueryClientProvider client={queryClient}>
      <TooltipProvider>
        <Toaster />
        <Sonner />
        {showComingSoon ? (
          <ComingSoon />
        ) : (
          <BrowserRouter>
            <Routes>
              <Route path="/" element={<Index />} />
              <Route path="/:slug" element={<Index />} />
              <Route path="*" element={<NotFound />} />
            </Routes>
          </BrowserRouter>
        )}
      </TooltipProvider>
    </QueryClientProvider>
  );
};

export default App;
