import { useEffect, useState } from "react";
import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import { getContentProvider } from "@/content/providers";
import PasswordGate from "@/components/PasswordGate";
import HomePage from "./pages/HomePage";
import Index from "./pages/Index";
import PricingPage from "./pages/PricingPage";
import AboutPage from "./pages/AboutPage";
import ContactPage from "./pages/ContactPage";
import NotFound from "./pages/NotFound";
import ComingSoon from "./pages/ComingSoon";

const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      refetchOnWindowFocus: false,
      refetchOnReconnect: false,
    },
  },
});
const contentProvider = getContentProvider();

const buildTimeComingSoon =
  String(import.meta.env.VITE_COMING_SOON ?? "").toLowerCase().trim() === "true";

const SITE_CONFIG_TIMEOUT_MS = 8000;

const App = () => {
  const [comingSoon, setComingSoon] = useState<boolean | null>(
    buildTimeComingSoon || null,
  );

  useEffect(() => {
    const timeout = setTimeout(() => {
      setComingSoon((prev) => (prev === null ? false : prev));
    }, SITE_CONFIG_TIMEOUT_MS);

    contentProvider.getSiteConfig()
      .then((config) => setComingSoon(config.comingSoon))
      .catch(() => setComingSoon(false))
      .finally(() => clearTimeout(timeout));

    return () => clearTimeout(timeout);
  }, []);

  return (
    <PasswordGate>
      <QueryClientProvider client={queryClient}>
        <TooltipProvider>
          <Toaster />
          <Sonner />
          {comingSoon === null ? (
            // Loading splash — avoids flashing <ComingSoon /> while API is in flight
            <div className="min-h-screen bg-background" />
          ) : comingSoon ? (
            <ComingSoon />
          ) : (
            <BrowserRouter>
              <Routes>
                <Route path="/" element={<HomePage />} />
                <Route path="/pricing" element={<PricingPage />} />
                <Route path="/about" element={<AboutPage />} />
                <Route path="/contact" element={<ContactPage />} />
                <Route path="/:slug" element={<Index />} />
                <Route path="*" element={<NotFound />} />
              </Routes>
            </BrowserRouter>
          )}
        </TooltipProvider>
      </QueryClientProvider>
    </PasswordGate>
  );
};

export default App;
