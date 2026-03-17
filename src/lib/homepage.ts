import { mockHomePage } from "@/content/mockData";
import type {
  CtaBlockContent,
  HomePageContent,
  LandingPageContent,
  PainPointContent,
  PricingContentV2,
  SupportChannel,
  SupportContent,
  SupportSectionContent,
} from "@/types/wordpress";

function mapLegacySupportToHome(data: SupportContent): SupportSectionContent | null {
  if (!data.sectionTitle || data.features.length === 0) {
    return null;
  }

  const channels: SupportChannel[] = data.features.slice(0, 3).map((feature, index) => {
    const link = data.links[index];
    return {
      icon: feature.icon,
      title: link?.label || feature.text,
      description: feature.text,
      link: link?.href,
    };
  });

  return channels.length > 0
    ? {
        headline: data.sectionTitle,
        channels,
      }
    : null;
}

function mapPricingToHome(api: PricingContentV2): HomePageContent["pricing"] {
  return {
    provider: {
      ...api.providerCard,
      subtext: api.providerCard.priceSub,
      note: api.providerCard.callout,
    },
    group: {
      ...api.groupCard,
      subtext: api.groupCard.priceSub,
      note: api.groupCard.notes,
    },
  };
}

function hasPainPointContent(data?: PainPointContent): data is PainPointContent {
  return Boolean(data?.headline && data.cards.length > 0);
}

function hasCtaBlockContent(data?: CtaBlockContent): data is CtaBlockContent {
  return Boolean(data?.headline && data.primaryCta.label);
}

function hasSupportSectionContent(
  data?: SupportSectionContent | null,
): data is SupportSectionContent {
  return Boolean(data?.headline && data.channels.length > 0);
}

export function buildHomePageContent(
  landingData?: LandingPageContent,
): HomePageContent {
  const mappedSupport = hasSupportSectionContent(landingData?.homeSupportContent)
    ? landingData.homeSupportContent
    : landingData?.supportContent
      ? mapLegacySupportToHome(landingData.supportContent)
      : null;

  return {
    hero:
      landingData?.heroContent?.headline
        ? landingData.heroContent
        : mockHomePage.hero,
    heroDashboard:
      landingData?.heroDashboard?.title
        ? landingData.heroDashboard
        : mockHomePage.heroDashboard,
    painPoint:
      hasPainPointContent(landingData?.painPointContent)
        ? landingData.painPointContent
        : mockHomePage.painPoint,
    howItWorks:
      landingData?.howItWorksContent?.providerSteps?.length &&
      landingData?.howItWorksContent?.groupSteps?.length
        ? {
            sectionTitle: landingData.howItWorksContent.sectionTitle,
            sectionSubtitle: landingData.howItWorksContent.sectionSubtitle,
            providerSteps: landingData.howItWorksContent.providerSteps,
            groupSteps: landingData.howItWorksContent.groupSteps,
          }
        : mockHomePage.howItWorks,
    ecosystem:
      landingData?.ecosystemContent?.pairs?.length
        ? landingData.ecosystemContent
        : mockHomePage.ecosystem,
    ctaBlockA:
      hasCtaBlockContent(landingData?.ctaBlockA)
        ? landingData.ctaBlockA
        : mockHomePage.ctaBlockA,
    pricing:
      landingData?.pricingContent &&
      "providerCard" in landingData.pricingContent &&
      "groupCard" in landingData.pricingContent
        ? mapPricingToHome(landingData.pricingContent)
        : mockHomePage.pricing,
    ctaBlockB:
      hasCtaBlockContent(landingData?.ctaBlockB)
        ? landingData.ctaBlockB
        : mockHomePage.ctaBlockB,
    support: mappedSupport ?? mockHomePage.support,
    faq:
      landingData?.faqContent?.items?.length
        ? landingData.faqContent
        : mockHomePage.faq,
  };
}
