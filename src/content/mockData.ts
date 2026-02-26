import type {
  SiteSettings,
  HomePageContent,
  PricingPageContent,
  AboutPageContent,
  ContactPageContent,
} from "@/types/wordpress";

// ============================================================================
// SITE-WIDE SETTINGS (Header + Footer)
// ============================================================================

export const mockSiteSettings: SiteSettings = {
  header: {
    logo: "CCS Pro",
    primaryNav: [
      { label: "Product", href: "/#how-it-works" },
      { label: "Pricing", href: "/pricing" },
      { label: "About", href: "/about" },
      { label: "Contact", href: "/contact" },
    ],
    ctaButton: { label: "Get Started", href: "#" },
    secondaryLink: { label: "Sign In", href: "#" },
  },
  footer: {
    brand: {
      name: "CCS Pro",
      tagline: "Credentialing packets for Texas providers. Built once, ready always.",
    },
    columns: [
      {
        title: "Product",
        links: [
          { label: "How It Works", href: "/#how-it-works" },
          { label: "For Providers", href: "/pricing#provider" },
          { label: "For Groups", href: "/pricing#groups" },
          { label: "Pricing", href: "/pricing" },
        ],
      },
      {
        title: "Company",
        links: [
          { label: "About Us", href: "/about" },
          { label: "Contact", href: "/contact" },
          { label: "Help Center", href: "/help" },
        ],
      },
      {
        title: "Legal",
        links: [
          { label: "Privacy Policy", href: "#" },
          { label: "Terms of Service", href: "#" },
          { label: "BAA", href: "#" },
        ],
      },
    ],
    trustBadges: [
      { icon: "Shield", text: "HIPAA Compliant" },
      { icon: "FileCheck", text: "BAA Available" },
      { icon: "MapPin", text: "Texas-Based" },
    ],
    copyright: `© ${new Date().getFullYear()} CCS Pro. All rights reserved.`,
  },
};

// ============================================================================
// HOMEPAGE
// ============================================================================

export const mockHomePage: HomePageContent = {
  hero: {
    headline: "Credentialing Packets.",
    headlineHighlight: "Done Once.",
    subheadline:
      "CCS Pro lets Texas providers build their credentialing profile once and hand it off anywhere — in under 10 minutes.",
    primaryCta: { label: "Start as a Provider — $99/year", href: "/pricing#provider" },
    secondaryCta: { label: "I manage a group or facility →", href: "/pricing#groups" },
    tertiaryCta: { label: "", href: "" },
    trustIndicators: [
      { icon: "Shield", text: "HIPAA Compliant" },
      { icon: "FileCheck", text: "Texas LHL234 Ready" },
      { icon: "ShieldCheck", text: "SignNow E-Signature" },
    ],
  },
  heroDashboard: {
    title: "Provider Profile",
    subtitle: "Credentialing Readiness",
    completionPercent: 85,
    stateValue: "Texas",
    npiValue: "1234567890",
    readinessStates: [
      { label: "Complete", color: "green" },
      { label: "Expiring Soon", color: "orange" },
    ],
    documents: [
      { name: "DEA Certificate", status: "Complete", statusColor: "green" },
      { name: "Malpractice Insurance", status: "Complete", statusColor: "green" },
      { name: "Board Certification", status: "Expiring Soon", statusColor: "orange" },
      { name: "State License", status: "Complete", statusColor: "green" },
    ],
    buttons: {
      primary: "Generate Packet",
      secondary: "View Profile",
    },
  },
  painPoint: {
    sectionLabel: "The Problem",
    headline: "Credentialing hasn't changed. The paperwork still wins.",
    cards: [
      {
        icon: "Clock",
        title: "45+ Minutes Per Packet",
        body: "Every new employer, facility, or payer asks for the same information. You build it from scratch every time.",
      },
      {
        icon: "RefreshCw",
        title: "Re-credentialing Every 2 Years",
        body: "The cycle never stops. Licenses expire. Documents lapse. And someone has to chase them all down.",
      },
      {
        icon: "FileX",
        title: "One Missing Doc Delays Everything",
        body: "A single expired certificate can stall a 90-120 day payer enrollment. There is no buffer.",
      },
    ],
    summaryText:
      "CCS Pro eliminates the rebuild. Your profile is built once, kept current, and ready to go whenever credentialing comes calling.",
  },
  howItWorks: {
    sectionTitle: "How it works",
    sectionSubtitle: "Three simple steps to a submission-ready credentialing packet",
    providerSteps: [
      {
        icon: "Upload",
        step: "01",
        title: "Build Your Profile Once",
        description:
          "Upload your existing LHL234 and we extract everything automatically, or enter your information directly. Takes 10 minutes.",
      },
      {
        icon: "FileCheck",
        step: "02",
        title: "Keep Documents Current",
        description:
          "Upload licenses, DEA certificate, malpractice insurance, board certifications. We track expiration dates so nothing lapses.",
      },
      {
        icon: "Send",
        step: "03",
        title: "Generate and Sign On Demand",
        description:
          "When anyone requests your credentials, generate a complete signed packet in seconds. Hand it off and get back to practicing.",
      },
    ],
    groupSteps: [
      {
        icon: "Users",
        step: "01",
        title: "Add Providers by NPI",
        description:
          "Enter a provider's NPI. If they're already on CCS Pro, they get a consent notification. If not, they get an invite link.",
      },
      {
        icon: "LayoutDashboard",
        step: "02",
        title: "Track Roster Compliance",
        description:
          "Your dashboard shows real-time status across every provider — who's current, who has expiring documents, who hasn't completed their profile.",
      },
      {
        icon: "ClipboardCheck",
        step: "03",
        title: "Generate Payer Packets",
        description:
          "When you're ready to submit to BCBS, UHC, Aetna, or any other payer, generate a complete payer-specific packet for any provider in your roster.",
      },
    ],
  },
  ecosystem: {
    headline: "One profile. Two sides of credentialing. Finally connected.",
    subheadline: "Providers build it once. Groups use it everywhere.",
    pairs: [
      {
        provider: "Signs LHL234 in under 10 minutes",
        connector: "enables",
        group: "Generates any payer packet on demand",
      },
      {
        provider: "Keeps documents and licenses current",
        connector: "means",
        group: "Always has a compliant, submission-ready roster",
      },
      {
        provider: "Attests CAQH profile once",
        connector: "so",
        group: "Submits to any payer without chasing anyone",
      },
      {
        provider: "Joins CCS Pro once",
        connector: "and",
        group: "Every future group gets instant access with one consent",
      },
    ],
  },
  ctaBlockA: {
    headline: "Providers: your profile is 10 minutes away.",
    subheadline: "Build it once. Use it for your entire career.",
    primaryCta: { label: "Start for $99/year", href: "/pricing#provider" },
    secondaryCta: { label: "See how it works →", href: "#how-it-works" },
    style: "indigo",
  },
  pricing: {
    provider: {
      badge: "For Individual Providers",
      price: "$99/year",
      subtext: "+ $60 per packet generated",
      bullets: [
        "Complete LHL234 profile",
        "Unlimited document storage",
        "E-signature via SignNow",
        "Packet generation on demand",
      ],
      cta: { label: "Get Started — $99/year", href: "/pricing#provider" },
      finePrint: "No contracts. Cancel anytime.",
      highlighted: false,
    },
    group: {
      badge: "For Groups & Facilities",
      price: "$1,199/seat/year",
      subtext: "All payer packet workflows included",
      bullets: [
        "Full provider roster management",
        "Real-time compliance dashboard",
        "Payer-specific packet generation",
        "Provider consent and invite system",
      ],
      cta: { label: "Talk to Us", href: "/contact" },
      finePrint: "Up to 50 seats. More than 50? Let's talk.",
      highlighted: true,
      secondaryLink: { label: "See full feature comparison →", href: "/pricing" },
    },
  },
  ctaBlockB: {
    headline: "Managing a group or facility?",
    subheadline:
      "Stop chasing providers for documents. Get your whole roster compliant in one place.",
    primaryCta: { label: "Talk to Us", href: "/contact" },
    secondaryCta: { label: "See group pricing →", href: "/pricing#groups" },
    style: "emerald",
  },
  support: {
    headline: "We're here when you need us",
    channels: [
      {
        icon: "Mail",
        title: "Email Support",
        description: "Response within one business day.",
        link: "mailto:support@ccsprocert.com",
      },
      {
        icon: "MessageSquare",
        title: "Live Chat",
        description: "Available during business hours, Monday through Friday.",
      },
      {
        icon: "BookOpen",
        title: "Help Center",
        description: "Step-by-step guides for every workflow.",
        link: "/help",
      },
    ],
  },
  faq: {
    sectionTitle: "Frequently asked questions",
    sectionSubtitle: "Everything you need to know about CCS Pro",
    items: [
      {
        question: "Do I need a group to use CCS Pro as a provider?",
        answer:
          "No. Individual providers sign up independently for $99/year. You own your profile completely.",
      },
      {
        question: "What is the LHL234 and why does it matter?",
        answer:
          "The LHL234, also called the Texas Standardized Credentialing Application (TSCA), is the document every Texas payer and facility uses to credential providers. CCS Pro is built around it.",
      },
      {
        question: "How does a group access my information as a provider?",
        answer:
          "Only with your explicit consent. When a group adds you by NPI, you receive a notification and must approve the connection. You can revoke access at any time.",
      },
      {
        question: "What happens if I need to change a provider in my roster?",
        answer:
          "You can remove a provider at any time. The seat they occupied has a 90-day waiting period before it can be reassigned to a new provider. You can always purchase an additional seat immediately if you need to onboard someone sooner.",
      },
      {
        question: "What payers does CCS Pro support for group packet generation?",
        answer:
          "We support all major Texas payers including BCBS of Texas, UnitedHealthcare, Aetna, Humana, and Cigna, with more being added.",
      },
      {
        question: "Is my data HIPAA compliant?",
        answer:
          "Yes. CCS Pro is hosted on Azure with a HIPAA-compliant infrastructure. All third-party vendors including SignNow and Stripe have signed Business Associate Agreements.",
      },
      {
        question: "What happens to my profile if I leave a group?",
        answer:
          "Your profile stays with you. The group loses access immediately. Your data is yours, not theirs.",
      },
    ],
  },
};

// ============================================================================
// PRICING PAGE
// ============================================================================

export const mockPricingPage: PricingPageContent = {
  hero: {
    headline: "Simple pricing. No surprises.",
    subheadline:
      "Whether you're a solo provider or managing a 50-person group, CCS Pro fits your workflow and your budget.",
  },
  provider: {
    badge: "For Individual Providers",
    price: "$99/year",
    subtext: "+ $60 per packet generated",
    bullets: [
      "Complete LHL234 profile",
      "Unlimited document storage",
      "E-signature via SignNow",
      "Packet generation on demand",
    ],
    cta: { label: "Get Started — $99/year", href: "#" },
    finePrint: "No contracts. Cancel anytime.",
    highlighted: false,
    extras: ["Most providers pay under $600 total in year one."],
  },
  group: {
    badge: "For Groups & Facilities",
    price: "$1,199/seat/year",
    subtext: "All payer packet workflows included",
    bullets: [
      "Full provider roster management",
      "Real-time compliance dashboard",
      "Payer-specific packet generation",
      "Provider consent and invite system",
    ],
    cta: { label: "Talk to Us", href: "/contact" },
    finePrint: "Up to 50 seats. More than 50? Let's talk.",
    highlighted: true,
    secondaryLink: { label: "See full feature comparison →", href: "#comparison" },
    extras: [
      "One seat = one provider in your roster",
      "All payer workflows included, no packet fees.",
      "Need more than 50 seats? Let's talk.",
    ],
  },
  featureTable: [
    {
      category: "Profile & Documents",
      rows: [
        { feature: "LHL234 / TSCA profile", provider: true, group: true },
        { feature: "Document upload and storage", provider: true, group: true },
        { feature: "Expiration date tracking", provider: true, group: true },
        { feature: "CAQH attestation workflow", provider: true, group: true },
        { feature: "GPT-assisted LHL234 extraction", provider: true, group: true },
      ],
    },
    {
      category: "Packet Generation",
      rows: [
        { feature: "Standard credentialing packet", provider: true, group: true },
        { feature: "E-signature via SignNow", provider: true, group: true },
        { feature: "Payer-specific packet generation", provider: false, group: true },
        { feature: "Bulk packet generation", provider: false, group: true },
      ],
    },
    {
      category: "Group Management",
      rows: [
        { feature: "Provider roster dashboard", provider: false, group: true },
        { feature: "Real-time compliance tracking", provider: false, group: true },
        { feature: "Provider invite by NPI", provider: false, group: true },
        { feature: "Provider consent management", provider: false, group: true },
        { feature: "Reminder and alert system", provider: false, group: true },
      ],
    },
    {
      category: "Security & Compliance",
      rows: [
        { feature: "HIPAA-compliant infrastructure", provider: true, group: true },
        { feature: "Azure secure hosting", provider: true, group: true },
        { feature: "BAA available", provider: true, group: true },
        { feature: "Audit log", provider: false, group: true },
      ],
    },
  ],
  faq: {
    sectionTitle: "Pricing FAQ",
    sectionSubtitle: "",
    items: [
      {
        question: "Is the $60 packet fee per payer or per generation?",
        answer:
          "Per generation. Each time you generate a packet, that is one $60 charge regardless of which payer it is for.",
      },
      {
        question: "Can a provider be in multiple groups at the same time?",
        answer:
          "Yes. A provider can consent to multiple group connections simultaneously. Each group pays for their own seat.",
      },
      {
        question: "What happens if I need to change a provider in my roster?",
        answer:
          "You can remove a provider at any time. The seat has a 90-day waiting period before reassignment. If you need to onboard a new provider immediately, simply purchase an additional seat.",
      },
      {
        question: "Are there setup fees or contracts?",
        answer:
          "No setup fees, no long-term contracts. Providers pay annually. Groups pay annually per seat. Cancel before your renewal and you will not be charged again.",
      },
      {
        question: "Do you offer a free trial?",
        answer:
          "We do not currently offer a free trial, but providers can sign up, build their full profile, and explore the platform before generating their first packet.",
      },
    ],
  },
  finalCta: {
    headline: "Ready to stop rebuilding from scratch?",
    providerCta: { label: "Start as a Provider — $99/year", href: "#" },
    groupCta: { label: "Talk to Us About Group Pricing", href: "/contact" },
  },
};

// ============================================================================
// ABOUT PAGE
// ============================================================================

export const mockAboutPage: AboutPageContent = {
  hero: {
    headline: "Built for the people who keep healthcare credentialed.",
    subheadline:
      "CCS Pro started because the credentialing process in Texas is still largely manual, repetitive, and broken. We're fixing that.",
  },
  mission:
    "Our mission is to give every Texas provider a portable, always-ready credentialing profile they own completely — and give every group and facility the compliance infrastructure to manage their roster without the chaos.",
  whyTexas: {
    paragraph:
      "Texas has one of the largest and most complex provider markets in the country. The LHL234 (Texas Standardized Credentialing Application) is required by every payer and facility in the state, yet the process of filling it out, keeping it current, and submitting it to multiple organizations remains almost entirely manual. CCS Pro was built from day one around the Texas credentialing landscape.",
    stats: [
      { value: "125,000+", label: "Credentialing providers in Texas" },
      { value: "60–120 days", label: "Average payer enrollment" },
      { value: "2-year", label: "Re-credentialing cycles" },
    ],
  },
  differentiators: [
    {
      title: "Provider-first portability",
      description:
        "Your profile belongs to you, not your group. Switch employers, add new facilities, or go independent — your credentials follow you.",
    },
    {
      title: "Texas-native",
      description:
        "Built around the LHL234/TSCA from day one. Not a generic credentialing tool with Texas bolted on after the fact.",
    },
    {
      title: "Priced for real practices",
      description:
        "Solo providers pay $99/year, not enterprise software pricing. Groups pay per seat, not per feature.",
    },
  ],
  cta: {
    text: "Want to learn more or talk to the team? Reach out.",
    link: { label: "Contact Us", href: "/contact" },
  },
};

// ============================================================================
// CONTACT PAGE
// ============================================================================

export const mockContactPage: ContactPageContent = {
  hero: {
    headline: "Get in touch.",
    subheadline:
      "Whether you're a provider with a question or a group looking to get set up, we're here.",
  },
  formFields: {
    roleOptions: ["Provider", "Group or Facility", "Other"],
  },
  contactInfo: {
    email: "support@ccsprocert.com",
    responseTime: "We respond within one business day.",
    businessHours: "Monday – Friday, 9 AM – 5 PM CT",
  },
  groupCallout: {
    headline: "Evaluating CCS Pro for your group or facility?",
    body: "Tell us your roster size and we'll put together a tailored walkthrough.",
  },
};
