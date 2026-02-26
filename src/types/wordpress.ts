/**
 * TypeScript interfaces for WordPress REST API landing page response.
 * Matches the structure returned by /wp-json/ccspro/v1/landing-page/{slug}
 * and aligns with src/content/landing.ts.
 */

export interface SiteConfig {
  name: string;
  tagline: string;
  description: string;
}

export interface NavLink {
  label: string;
  href: string;
}

export interface CtaLink {
  label: string;
  href: string;
}

export interface NavCtas {
  primary: CtaLink;
  secondary: CtaLink;
  signIn: CtaLink;
}

export interface HeroContent {
  headline: string;
  headlineHighlight: string;
  headlineSuffix: string;
  subheadline: string;
  primaryCta: CtaLink;
  secondaryCta: CtaLink;
  tertiaryCta: CtaLink;
  trustIndicators: Array<{ icon: string; text: string }>;
}

export interface HeroDashboardDocument {
  name: string;
  status: string;
  statusColor: string;
}

export interface ReadinessState {
  label: string;
  color: string;
}

export interface HeroDashboard {
  title: string;
  subtitle: string;
  completionPercent: number;
  stateValue: string;
  npiValue: string;
  readinessStates: ReadinessState[];
  documents: HeroDashboardDocument[];
  buttons: { primary: string; secondary: string };
}

export interface VerificationContent {
  headline: string;
  items: Array<{ icon: string; label: string }>;
}

export interface FounderContent {
  name: string;
  title: string;
  initials: string;
  quote: string;
  bullets: string[];
}

export interface ProblemOutcomeContent {
  problems: Array<{
    icon: string;
    title: string;
    description: string;
  }>;
  outcomeText: {
    prefix: string;
    middle: string;
    suffix: string;
  };
}

export interface HowItWorksStep {
  icon: string;
  step: string;
  title: string;
  description: string;
}

export interface HowItWorksContent {
  sectionTitle: string;
  sectionSubtitle: string;
  steps: HowItWorksStep[];
  providerSteps?: HowItWorksStep[];
  groupSteps?: HowItWorksStep[];
  readinessNote: {
    label: string;
    states: ReadinessState[];
  };
}

export interface FeatureItem {
  icon: string;
  title: string;
  description: string;
  link: string;
}

export interface FeaturesContent {
  sectionTitle: string;
  sectionSubtitle: string;
  features: FeatureItem[];
}

export interface PacketPreviewContent {
  sectionTitle: string;
  sectionSubtitle: string;
  fileName: string;
  checklist: string[];
  cta: CtaLink;
}

export interface SecurityContent {
  badge: string;
  sectionTitle: string;
  sectionSubtitle: string;
  features: Array<{ icon: string; text: string }>;
  cta: CtaLink;
  floatingBadges: string[];
}

export interface CAQHConciergeContent {
  badge: string;
  sectionTitle: string;
  sectionSubtitle: string;
  benefitsTitle: string;
  benefits: string[];
  cta: CtaLink;
  consentTitle: string;
  consentModes: Array<{
    icon: string;
    title: string;
    description: string;
  }>;
  alwaysIncluded: {
    icon: string;
    title: string;
    description: string;
  };
}

export interface PackPlan {
  name: string;
  price: number;
  badge?: string | null;
  description: string;
  applicationsIncluded: number | null;
  validityPeriod: string;
  billingType: "one_time" | "subscription";
  planType: "pack" | "unlimited";
  allowAdditionalPayers: boolean;
  additionalPayerPrice?: number | null;
  features: string[];
  cta: string;
  highlighted: boolean;
  gracePeriodDays?: number;
}

export interface PricingContent {
  sectionTitle: string;
  sectionSubtitle: string;
  packs: PackPlan[];
  postYearBehavior: {
    title: string;
    items: Array<{ text: string; kind: "positive" | "negative" }>;
    renewalNote: string;
  };
  footerNote?: string;
}

export interface PricingCardData {
  badge: string;
  price: string;
  priceSub: string;
  bullets: string[];
  cta: { label: string; href: string };
  finePrint: string;
  callout?: string;
  notes?: string[];
  secondaryLink?: { label: string; href: string };
  highlighted: boolean;
}

export interface PricingContentV2 {
  sectionTitle: string;
  sectionSubtitle: string;
  providerCard: PricingCardData;
  groupCard: PricingCardData;
}

export interface SupportContent {
  sectionTitle: string;
  sectionSubtitle: string;
  features: Array<{ icon: string; text: string }>;
  links: CtaLink[];
}

export interface TeamMember {
  name: string;
  role: string;
  icon: string;
  bio: string;
}

export interface TeamContent {
  sectionTitle: string;
  sectionSubtitle: string;
  members: TeamMember[];
}

export interface FaqItem {
  question: string;
  answer: string;
}

export interface FaqContent {
  sectionTitle: string;
  sectionSubtitle: string;
  items: FaqItem[];
}

export interface FinalCtaContent {
  headline: string;
  subheadline: string;
  primaryCta: CtaLink;
  secondaryCta: CtaLink;
}

export interface FooterContent {
  brand: { name: string; description: string };
  trustBadges: Array<{ icon: string; text: string }>;
  links: {
    legal: CtaLink[];
    support: CtaLink[];
  };
  copyright: string;
}

export interface GlobalHeaderData {
  logoUrl: string | null;
  logoText: string;
  ctaButton: { label: string; href: string };
  signinLink: { label: string; href: string };
}

export interface GlobalFooterData {
  brandName: string;
  tagline: string;
  trustBadges: { icon: string; text: string }[];
  copyright: string;
}

export interface SiteConfigResponse {
  comingSoon: boolean;
  header: GlobalHeaderData;
  footer: GlobalFooterData;
}

export interface LandingPageContent {
  siteConfig: SiteConfig;
  navLinks: NavLink[];
  navCtas: NavCtas;
  heroContent: HeroContent;
  heroDashboard: HeroDashboard;
  verificationContent: VerificationContent;
  founderContent: FounderContent;
  problemOutcomeContent: ProblemOutcomeContent;
  howItWorksContent: HowItWorksContent;
  featuresContent: FeaturesContent;
  packetPreviewContent: PacketPreviewContent;
  securityContent: SecurityContent;
  caqhConciergeContent: CAQHConciergeContent;
  pricingContent: PricingContent | PricingContentV2;
  supportContent: SupportContent;
  teamContent: TeamContent;
  faqContent: FaqContent;
  finalCtaContent: FinalCtaContent;
  ecosystemContent?: EcosystemContent;
  footerContent?: FooterContent;
}

// ============================================================================
// NEW PAGE TYPES — Phase 1 (mock data driven)
// ============================================================================

export interface MenuLink {
  label: string;
  href: string;
  openInNewTab?: boolean;
}

export interface MenusResponse {
  primaryNav: MenuLink[];
  footerCol1: MenuLink[];
  footerCol2: MenuLink[];
  footerCol3: MenuLink[];
}

export interface HeaderData {
  logo: string;
  logoUrl?: string | null;
  primaryNav: MenuLink[];
  ctaButton: CtaLink;
  secondaryLink: CtaLink;
}

export interface FooterColumn {
  title: string;
  links: MenuLink[];
}

export interface FooterData {
  brand: { name: string; tagline: string };
  columns: FooterColumn[];
  trustBadges: Array<{ icon: string; text: string }>;
  copyright: string;
}

export interface SiteSettings {
  header: HeaderData;
  footer: FooterData;
}

export interface PainPointContent {
  sectionLabel: string;
  headline: string;
  cards: Array<{
    icon: string;
    title: string;
    body: string;
  }>;
  summaryText: string;
}

export interface HowItWorksTabContent {
  sectionTitle: string;
  sectionSubtitle: string;
  providerSteps: HowItWorksStep[];
  groupSteps: HowItWorksStep[];
}

export interface EcosystemPair {
  providerAction: string;
  connector: string;
  groupOutcome: string;
}

export interface EcosystemContent {
  headline: string;
  subheadline: string;
  pairs: EcosystemPair[];
}

export interface HomePricingCardData {
  badge: string;
  price: string;
  subtext: string;
  bullets: string[];
  cta: CtaLink;
  finePrint: string;
  highlighted: boolean;
  secondaryLink?: CtaLink;
}

export interface CtaBlockContent {
  headline: string;
  subheadline: string;
  primaryCta: CtaLink;
  secondaryCta: CtaLink;
  style: "indigo" | "emerald";
}

export interface SupportChannel {
  icon: string;
  title: string;
  description: string;
  link?: string;
}

export interface SupportSectionContent {
  headline: string;
  channels: SupportChannel[];
}

export interface HomePageContent {
  hero: HeroContent;
  heroDashboard: HeroDashboard;
  painPoint: PainPointContent;
  howItWorks: HowItWorksTabContent;
  ecosystem: EcosystemContent;
  ctaBlockA: CtaBlockContent;
  pricing: {
    provider: HomePricingCardData;
    group: HomePricingCardData;
  };
  ctaBlockB: CtaBlockContent;
  support: SupportSectionContent;
  faq: FaqContent;
}

export interface PricingPlanExtended extends HomePricingCardData {
  extras: string[];
}

export interface FeatureComparisonRow {
  feature: string;
  provider: boolean;
  group: boolean;
}

export interface FeatureComparisonCategory {
  category: string;
  rows: FeatureComparisonRow[];
}

export interface PricingPageContent {
  hero: { headline: string; subheadline: string };
  provider: PricingPlanExtended;
  group: PricingPlanExtended;
  featureTable: FeatureComparisonCategory[];
  faq: FaqContent;
  finalCta: {
    headline: string;
    providerCta: CtaLink;
    groupCta: CtaLink;
  };
}

export interface AboutPageContent {
  hero: { headline: string; subheadline: string };
  mission: string;
  whyTexas: {
    paragraph: string;
    stats: Array<{ value: string; label: string }>;
  };
  differentiators: Array<{
    title: string;
    description: string;
  }>;
  cta: { text: string; link: CtaLink };
}

export interface ContactPageContent {
  hero: { headline: string; subheadline: string };
  formFields: {
    roleOptions: string[];
  };
  contactInfo: {
    email: string;
    responseTime: string;
    businessHours: string;
  };
  groupCallout: {
    headline: string;
    body: string;
  };
}

export interface ContactFormPayload {
  name: string;
  email: string;
  role: string;
  message: string;
  _hp?: string;
}

export interface ContactSubmitResponse {
  success: boolean;
  message?: string;
}
