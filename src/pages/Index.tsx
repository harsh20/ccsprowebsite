import { useParams } from "react-router-dom";
import { useLandingPage, usePricingContent } from "@/hooks/useWordPress";
import { defaultLandingPageContent } from "@/content/landing";
import type { LandingPageContent } from "@/types/wordpress";
import { Header } from "@/components/landing/Header";
import { HeroSection } from "@/components/landing/HeroSection";
import { LogoStrip } from "@/components/landing/LogoStrip";
import { FounderSpotlight } from "@/components/landing/FounderSpotlight";
import { ProblemOutcome } from "@/components/landing/ProblemOutcome";
import { HowItWorks } from "@/components/landing/HowItWorks";
import { FeaturesGrid } from "@/components/landing/FeaturesGrid";
import { SecuritySection } from "@/components/landing/SecuritySection";
import { PricingSection } from "@/components/landing/PricingSection";
import { SupportSection } from "@/components/landing/SupportSection";
import { TeamSection } from "@/components/landing/TeamSection";
import { FAQSection } from "@/components/landing/FAQSection";
import { FinalCTA } from "@/components/landing/FinalCTA";
import { Footer } from "@/components/landing/Footer";

const Index = () => {
  const { slug: urlSlug } = useParams<{ slug?: string }>();
  const slug = urlSlug ?? "default";
  const { data } = useLandingPage(slug);
  const { data: pricingData } = usePricingContent();

  const baseContent: LandingPageContent =
    data ?? (defaultLandingPageContent as unknown as LandingPageContent);

  const content: LandingPageContent = {
    ...baseContent,
    pricingContent: pricingData ?? baseContent.pricingContent,
  };

  return (
    <div className="min-h-screen bg-background">
      <Header content={content} />
      <main>
        <HeroSection content={content} />
        <LogoStrip content={content} />
        <FounderSpotlight content={content} />
        <ProblemOutcome content={content} />
        <HowItWorks content={content} />
        <FeaturesGrid content={content} />
        <SecuritySection content={content} />
        <PricingSection content={content} />
        <SupportSection content={content} />
        <TeamSection content={content} />
        <FAQSection content={content} />
        <FinalCTA content={content} />
      </main>
      <Footer content={content} />
    </div>
  );
};

export default Index;
