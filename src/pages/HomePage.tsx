import { useEffect } from "react";
import { mockSiteSettings, mockHomePage } from "@/content/mockData";
import { Header } from "@/components/landing/Header";
import { HeroSection } from "@/components/landing/HeroSection";
import { ProblemOutcome } from "@/components/landing/ProblemOutcome";
import { HowItWorks } from "@/components/landing/HowItWorks";
import { EcosystemSection } from "@/components/landing/EcosystemSection";
import { FinalCTA } from "@/components/landing/FinalCTA";
import { HomePricingSection } from "@/components/landing/HomePricingSection";
import { SupportSection } from "@/components/landing/SupportSection";
import { FAQSection } from "@/components/landing/FAQSection";
import { Footer } from "@/components/landing/Footer";

const HomePage = () => {
  useEffect(() => {
    document.title = "CCS Pro | Credentialing Packets. Done Once. Ready Always.";
  }, []);

  const page = mockHomePage;
  const faqContent = page.faq;

  return (
    <div className="min-h-screen bg-background">
      <Header headerData={mockSiteSettings.header} />
      <main>
        <HeroSection heroData={page.hero} dashboardData={page.heroDashboard} />
        <ProblemOutcome painPointData={page.painPoint} />
        <HowItWorks tabData={page.howItWorks} />
        <EcosystemSection data={page.ecosystem} />
        <FinalCTA blockData={page.ctaBlockA} />
        <HomePricingSection
          provider={page.pricing.provider}
          group={page.pricing.group}
        />
        <FinalCTA blockData={page.ctaBlockB} />
        <SupportSection channelData={page.support} />
        <FAQSection faqData={faqContent} />
      </main>
      <Footer footerData={mockSiteSettings.footer} />
    </div>
  );
};

export default HomePage;
