import { useEffect } from "react";
import { mockSiteSettings, mockHomePage } from "@/content/mockData";
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
import type {
  HeroContent,
  HomePricingCardData,
  HowItWorksContent,
  HowItWorksTabContent,
  PricingContentV2,
} from "@/types/wordpress";

type HomePagePricing = {
  provider: HomePricingCardData;
  group: HomePricingCardData;
};

function mapApiHeroToMock(api: HeroContent): HeroContent {
  return api;
}

function mapApiHowItWorksToHome(api: HowItWorksContent): HowItWorksTabContent {
  return {
    sectionTitle: api.sectionTitle,
    sectionSubtitle: api.sectionSubtitle,
    providerSteps: api.providerSteps ?? [],
    groupSteps: api.groupSteps ?? [],
  };
}

function mapApiPricingToHome(api: PricingContentV2): HomePagePricing {
  return {
    provider: {
      ...api.providerCard,
      subtext: api.providerCard.priceSub,
    },
    group: {
      ...api.groupCard,
      subtext: api.groupCard.priceSub,
    },
  };
}

const HomePage = () => {
  useEffect(() => {
    document.title = "CCS Pro | Credentialing Packets. Done Once. Ready Always.";
  }, []);

  const { data: landingData } = useLandingPage("default");
  const { data: siteConfig } = useSiteConfig();
  const { data: menus } = useMenus();

  const heroData = landingData?.heroContent?.headline
    ? mapApiHeroToMock(landingData.heroContent)
    : mockHomePage.hero;

  const howItWorksData =
    landingData?.howItWorksContent?.providerSteps?.length &&
    landingData?.howItWorksContent?.groupSteps?.length
      ? mapApiHowItWorksToHome(landingData.howItWorksContent)
      : mockHomePage.howItWorks;

  const ecosystemData =
    landingData?.ecosystemContent?.pairs?.length
      ? landingData.ecosystemContent
      : mockHomePage.ecosystem;

  const pricingData =
    landingData?.pricingContent &&
    "providerCard" in landingData.pricingContent &&
    "groupCard" in landingData.pricingContent
      ? mapApiPricingToHome(landingData.pricingContent)
      : mockHomePage.pricing;

  const supportFromApi = landingData?.supportContent as unknown as
    | { headline?: string; channels?: unknown[] }
    | undefined;
  const supportData =
    supportFromApi?.headline && supportFromApi?.channels?.length
      ? (landingData?.supportContent as unknown as typeof mockHomePage.support)
      : mockHomePage.support;

  const faqData = landingData?.faqContent?.items?.length
    ? landingData.faqContent
    : mockHomePage.faq;

  const headerData = siteConfig?.header
    ? {
        logo: siteConfig.header.logoText,
        logoUrl: siteConfig.header.logoUrl,
        ctaButton: siteConfig.header.ctaButton,
        secondaryLink: siteConfig.header.signinLink,
        primaryNav: menus?.primaryNav ?? mockSiteSettings.header.primaryNav,
      }
    : mockSiteSettings.header;

  const [defaultCol1, defaultCol2, defaultCol3] = mockSiteSettings.footer.columns;
  const footerData = siteConfig?.footer
    ? {
        brand: {
          name: siteConfig.footer.brandName,
          tagline: siteConfig.footer.tagline,
        },
        trustBadges: siteConfig.footer.trustBadges,
        copyright: siteConfig.footer.copyright,
        columns: [
          { title: defaultCol1.title, links: menus?.footerCol1 ?? defaultCol1.links },
          { title: defaultCol2.title, links: menus?.footerCol2 ?? defaultCol2.links },
          { title: defaultCol3.title, links: menus?.footerCol3 ?? defaultCol3.links },
        ],
      }
    : mockSiteSettings.footer;

  return (
    <div className="min-h-screen bg-background">
      <Header headerData={headerData} />
      <main>
        <HeroSection heroData={heroData} dashboardData={mockHomePage.heroDashboard} />
        <ProblemOutcome painPointData={mockHomePage.painPoint} />
        <HowItWorks tabData={howItWorksData} />
        <EcosystemSection data={ecosystemData} />
        <FinalCTA blockData={mockHomePage.ctaBlockA} />
        <HomePricingSection
          provider={pricingData.provider}
          group={pricingData.group}
        />
        <FinalCTA blockData={mockHomePage.ctaBlockB} />
        <SupportSection channelData={supportData} />
        <FAQSection faqData={faqData} />
      </main>
      <Footer footerData={footerData} />
    </div>
  );
};

export default HomePage;
