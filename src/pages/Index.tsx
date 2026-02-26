import { useParams } from "react-router-dom";
import { useLandingPage } from "@/hooks/useWordPress";
import { defaultLandingPageContent } from "@/content/landing";
import type { LandingPageContent } from "@/types/wordpress";
import { Header } from "@/components/landing/Header";
import { HeroSection } from "@/components/landing/HeroSection";
import { LogoStrip } from "@/components/landing/archived/LogoStrip";
import { FounderSpotlight } from "@/components/landing/archived/FounderSpotlight";
import { ProblemOutcome } from "@/components/landing/ProblemOutcome";
import { HowItWorks } from "@/components/landing/HowItWorks";
import { FeaturesGrid } from "@/components/landing/archived/FeaturesGrid";
import { SecuritySection } from "@/components/landing/archived/SecuritySection";
import { PricingSection } from "@/components/landing/PricingSection";
import { SupportSection } from "@/components/landing/SupportSection";
import { TeamSection } from "@/components/landing/archived/TeamSection";
import { FAQSection } from "@/components/landing/FAQSection";
import { FinalCTA } from "@/components/landing/FinalCTA";
import { Footer } from "@/components/landing/Footer";

const Index = () => {
  const { slug: urlSlug } = useParams<{ slug?: string }>();
  const slug = urlSlug ?? "default";
  const { data } = useLandingPage(slug);
  const content: LandingPageContent =
    data ?? (defaultLandingPageContent as unknown as LandingPageContent);

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
