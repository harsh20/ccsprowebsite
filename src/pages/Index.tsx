import { Header } from "@/components/landing/Header";
import { HeroSection } from "@/components/landing/HeroSection";
import { LogoStrip } from "@/components/landing/LogoStrip";
import { FounderSpotlight } from "@/components/landing/FounderSpotlight";
import { ProblemOutcome } from "@/components/landing/ProblemOutcome";
import { HowItWorks } from "@/components/landing/HowItWorks";
import { FeaturesGrid } from "@/components/landing/FeaturesGrid";
import { PacketPreview } from "@/components/landing/PacketPreview";
import { SecuritySection } from "@/components/landing/SecuritySection";
import { CAQHConcierge } from "@/components/landing/CAQHConcierge";
import { PricingSection } from "@/components/landing/PricingSection";
import { SupportSection } from "@/components/landing/SupportSection";
import { TeamSection } from "@/components/landing/TeamSection";
import { FAQSection } from "@/components/landing/FAQSection";
import { FinalCTA } from "@/components/landing/FinalCTA";
import { Footer } from "@/components/landing/Footer";

const Index = () => {
  return (
    <div className="min-h-screen bg-background">
      <Header />
      <main>
        <HeroSection />
        <LogoStrip />
        <FounderSpotlight />
        <ProblemOutcome />
        <HowItWorks />
        <FeaturesGrid />
        <PacketPreview />
        <SecuritySection />
        <CAQHConcierge />
        <PricingSection />
        <SupportSection />
        <TeamSection />
        <FAQSection />
        <FinalCTA />
      </main>
      <Footer />
    </div>
  );
};

export default Index;
