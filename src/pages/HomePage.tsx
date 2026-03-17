import { useEffect } from "react";
import { useLandingPage, useMenus, useSiteConfig } from "@/hooks/useWordPress";
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
import { buildHomePageContent } from "@/lib/homepage";
import { buildSiteLayoutData } from "@/lib/siteLayout";

const HomePage = () => {
  useEffect(() => {
    document.title = "CCS Pro | Credentialing Packets. Done Once. Ready Always.";
  }, []);

  const { data: landingData } = useLandingPage("default");
  const { data: siteConfig } = useSiteConfig();
  const { data: menus } = useMenus();

  const page = buildHomePageContent(landingData);
  const { headerData, footerData } = buildSiteLayoutData(siteConfig, menus);

  return (
    <div className="min-h-screen bg-background">
      <Header headerData={headerData} />
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
        <FAQSection faqData={page.faq} />
      </main>
      <Footer footerData={footerData} />
    </div>
  );
};

export default HomePage;
