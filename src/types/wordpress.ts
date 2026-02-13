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
  pricingContent: PricingContent;
  supportContent: SupportContent;
  teamContent: TeamContent;
  faqContent: FaqContent;
  finalCtaContent: FinalCtaContent;
  footerContent: FooterContent;
}
