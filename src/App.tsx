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

const App = () => {
  const [comingSoon, setComingSoon] = useState<boolean | null>(null);

  useEffect(() => {
    getSiteConfig()
      .then((config) => setComingSoon(config.comingSoon))
      .catch(() => setComingSoon(false));
  }, []);

  // While loading: use build-time value so Vercel env still works; if no build-time value, show full site (don't flash coming soon)
  const showComingSoon =
    comingSoon === true || (comingSoon === null && buildTimeComingSoon);

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
