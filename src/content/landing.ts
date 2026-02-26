/**
 * CMS-Ready Content File
 * All text strings and content data for the landing page.
 * This file can be replaced with CMS API calls in the future.
 */

// ============================================================================
// SITE CONFIG
// ============================================================================

export const siteConfig = {
  name: "CCS Pro",
  tagline: "Credentialing packets for US providers",
  description: "Store credentials once, track expirations, generate structured packets.",
};

// ============================================================================
// NAVIGATION
// ============================================================================

export const navLinks = [
  { label: "Product", href: "#product" },
  { label: "How it works", href: "#how-it-works" },
  { label: "Pricing", href: "#pricing" },
  { label: "Security", href: "#security" },
  { label: "Resources", href: "#faq" },
];

export const navCtas = {
  primary: { label: "Start free", href: "#pricing" },
  secondary: { label: "Book a demo", href: "#demo" },
  signIn: { label: "Sign in", href: "#" },
};

// ============================================================================
// HERO SECTION
// ============================================================================

export const heroContent = {
  headline: "Automated Credentialing Packets for",
  headlineHighlight: "Texas Independent Providers",
  headlineSuffix: "Ready Always.",
  subheadline: "Complete your Texas-specific profile, track expiries with AI, and generate submission-ready packets in minutes. All for $25/month.",
  primaryCta: { label: "Start My Texas Profile for Free", href: "#pricing" },
  secondaryCta: { label: "View sample Texas packet", href: "#sample" },
  tertiaryCta: { label: "Book a demo →", href: "#demo" },
  trustIndicators: [
    { icon: "Shield", text: "All documents stored in the US" },
    { icon: "Sparkles", text: "AI-powered extraction" },
    { icon: "CheckCircle", text: "Texas live now" },
  ],
};

export const heroDashboard = {
  title: "Credential Packet",
  subtitle: "Texas Requirements",
  completionPercent: 92,
  stateValue: "Texas",
  npiValue: "1234567890",
  readinessStates: [
    { label: "Missing", color: "red" },
    { label: "Uploaded", color: "blue" },
    { label: "Expiring Soon", color: "orange" },
    { label: "Expired", color: "gray" },
    { label: "Complete", color: "green" },
  ],
  documents: [
    { name: "TX Medical License", status: "Complete", statusColor: "green" },
    { name: "Malpractice COI", status: "Expiring in 45 days", statusColor: "orange" },
    { name: "DEA Certificate", status: "Missing", statusColor: "red" },
  ],
  buttons: {
    primary: "Generate Signed PDF",
    secondary: "Generate Packet PDF",
  },
};

// ============================================================================
// LOGO STRIP / VERIFICATION SECTION
// ============================================================================

export const verificationContent = {
  headline: "Verified against Texas credentialing requirements",
  items: [
    { icon: "Shield", label: "Texas Medical Board (TMB)" },
    { icon: "FileCheck", label: "DEA Registration" },
    { icon: "UserCheck", label: "CAQH ProView" },
  ],
};

// ============================================================================
// FOUNDER SPOTLIGHT
// ============================================================================

export const founderContent = {
  name: "Founder",
  title: "Practicing CRNA",
  initials: "DR",
  quote: "I built CCS Pro because credentialing paperwork wastes provider time and delays work.",
  bullets: [
    "Designed by a practicing clinician who understands credentialing pain",
    "Built around real credentialing packet requirements",
    "Export a Texas packet today, signed PDF in Pro",
  ],
};

// ============================================================================
// PROBLEM / OUTCOME SECTION
// ============================================================================

export const problemOutcomeContent = {
  problems: [
    {
      icon: "RefreshCw",
      title: "Re-entering the same data",
      description: "Each payer asks for the same info you've submitted a dozen times.",
    },
    {
      icon: "FileX",
      title: "Missing documents cause delays",
      description: "Incomplete packets get rejected, pushing back your start date.",
    },
    {
      icon: "Bell",
      title: "Expirations get missed",
      description: "Expired licenses and COIs create credentialing gaps.",
    },
  ],
  outcomeText: {
    prefix: "One profile",
    middle: ", always current, ",
    suffix: "packet on demand",
  },
};

// ============================================================================
// HOW IT WORKS SECTION
// ============================================================================

export const howItWorksContent = {
  sectionTitle: "How it works",
  sectionSubtitle: "Three simple steps to a submission-ready credentialing packet",
  steps: [
    {
      icon: "ClipboardCheck",
      step: "01",
      title: "Texas-Calibrated Profile Build",
      description: "Complete your profile with field-level validation tailored specifically to Texas credentialing standards. Our readiness engine scores your progress in real-time.",
    },
    {
      icon: "Sparkles",
      step: "02",
      title: "AI-Powered Expiry Detection",
      description: "Securely upload your files. Our AI/OCR pipeline automatically extracts issue and expiry dates, mapping them to five distinct readiness states.",
    },
    {
      icon: "FileOutput",
      step: "03",
      title: "One-Click Packet Assembly",
      description: "Generate professionally formatted, submission-ready PDF packets instantly, complete with cover pages and organized sections.",
    },
  ],
  readinessNote: {
    label: "5 Readiness States:",
    states: [
      { label: "Missing", color: "red" },
      { label: "Uploaded", color: "blue" },
      { label: "Expiring Soon", color: "orange" },
      { label: "Expired", color: "gray" },
      { label: "Complete", color: "green" },
    ],
  },
};

// ============================================================================
// FEATURES GRID SECTION
// ============================================================================

export const featuresContent = {
  sectionTitle: "What's included",
  sectionSubtitle: "Everything you need to manage credentials and generate submission-ready packets",
  features: [
    {
      icon: "Vault",
      title: "Credential vault with expiry tracking",
      description: "Store all licenses, certifications, and documents with automatic expiration alerts powered by AI extraction.",
      link: "#",
    },
    {
      icon: "Sparkles",
      title: "AI-powered data capture",
      description: "Our AI/OCR pipeline automatically extracts issue and expiry dates from uploaded documents.",
      link: "#",
    },
    {
      icon: "FileText",
      title: "Texas packet builder",
      description: "Generate professionally formatted, submission-ready PDF packets with cover pages and organized sections.",
      link: "#",
    },
    {
      icon: "UserCheck",
      title: "CAQH Done-For-You",
      description: "Secure intake for CAQH ProView credentials with encryption at rest. We handle the updates.",
      link: "#",
    },
    {
      icon: "Bell",
      title: "Multi-Channel Alerts",
      description: "Expiry reminders delivered via Email, SMS, and Push notifications. Never miss a deadline.",
      link: "#",
    },
    {
      icon: "History",
      title: "Enterprise Audit Trail",
      description: "Immutable, append-only logs of every field change and document access for forensic integrity.",
      link: "#",
    },
  ],
};

// ============================================================================
// PACKET PREVIEW SECTION
// ============================================================================

export const packetPreviewContent = {
  sectionTitle: "Sample Texas packet preview",
  sectionSubtitle: "See exactly what a structured credentialing packet includes",
  fileName: "Texas_Credential_Packet_Sample.pdf",
  checklist: [
    "Provider profile & demographics",
    "State licenses & IDs",
    "Malpractice COI documentation",
    "Board certifications",
    "Work history verification",
    "Required attestations",
  ],
  cta: { label: "Start free to generate your packet", href: "#pricing" },
};

// ============================================================================
// SECURITY SECTION
// ============================================================================

export const securityContent = {
  badge: "HIPAA-Aligned Security",
  sectionTitle: "Your data, protected",
  sectionSubtitle: "We understand you're trusting us with sensitive documents. Here's how we protect them with enterprise-grade security.",
  features: [
    { icon: "Shield", text: "Documents stored in the United States" },
    { icon: "Lock", text: "AES-256 Encryption for all sensitive PII and credentialing data" },
    { icon: "KeyRound", text: "Mandatory Multi-Factor Authentication (MFA) with no opt-out" },
    { icon: "Layers", text: "Strict Tenant Isolation at the data layer" },
    { icon: "Users", text: "Role-based access controls" },
    { icon: "Activity", text: "Immutable audit trail and activity logs" },
    { icon: "Database", text: "Backups and retention controls" },
  ],
  cta: { label: "View security details", href: "#" },
  floatingBadges: ["AES-256 Encryption", "Mandatory MFA", "Tenant Isolation"],
};

// ============================================================================
// CAQH CONCIERGE SECTION
// ============================================================================

export const caqhConciergeContent = {
  badge: "Add-on Service",
  sectionTitle: "Done-for-you CAQH Concierge",
  sectionSubtitle: "Let us handle your CAQH profile maintenance so you can focus on patient care.",
  benefitsTitle: "What we do for you:",
  benefits: [
    "Maintain your CAQH profile",
    "Upload documents and keep them current",
    "Coordinate updates and reminders",
  ],
  cta: { label: "Add CAQH Concierge", href: "#pricing" },
  consentTitle: "Choose your consent mode:",
  consentModes: [
    {
      icon: "ShieldCheck",
      title: "Explicit consent per action",
      description: "Approve each action before we make any changes to your profile.",
    },
    {
      icon: "UserCheck",
      title: "Standing authorization",
      description: "Define allowed actions upfront with instant revoke at any time.",
    },
  ],
  alwaysIncluded: {
    icon: "Bell",
    title: "Always included:",
    description: "Activity log and email notification for every change, regardless of consent mode.",
  },
};

// ============================================================================
// PRICING SECTION
// ============================================================================

export const pricingContent = {
  sectionTitle: "Credentialing packs built for how providers actually work",
  sectionSubtitle: "One-time pack purchases with 1-year validity. Your profile and documents stay accessible forever.",
  packs: [
    {
      name: "Single Payer Pack",
      price: 39,
      badge: "One Time",
      description: "For providers who only need one payer application.",
      applicationsIncluded: 1,
      validityPeriod: "1 year",
      billingType: "one_time",
      planType: "pack",
      allowAdditionalPayers: false,
      additionalPayerPrice: null,
      features: [
        "Profile builder",
        "Document vault",
        "E-signature included",
        "Credential expiry tracking",
      ],
      cta: "Buy Single Packet",
      highlighted: false,
      gracePeriodDays: 30,
    },
    {
      name: "Professional Pack",
      price: 179,
      badge: "Most Popular",
      description: "Best fit for most providers handling multiple applications.",
      applicationsIncluded: 5,
      validityPeriod: "1 year",
      billingType: "one_time",
      planType: "pack",
      allowAdditionalPayers: true,
      additionalPayerPrice: 29,
      features: [
        "Profile builder",
        "Document vault",
        "E-signature included",
        "Credential expiry tracking",
      ],
      cta: "Get Started",
      highlighted: true,
      gracePeriodDays: 30,
    },
    {
      name: "Complete Pack",
      price: 329,
      badge: null,
      description: "For growing practices that need broad payer coverage.",
      applicationsIncluded: 10,
      validityPeriod: "1 year",
      billingType: "one_time",
      planType: "pack",
      allowAdditionalPayers: true,
      additionalPayerPrice: 29,
      features: [
        "Profile builder",
        "Document vault",
        "E-signature included",
        "Credential expiry tracking",
      ],
      cta: "Get Started",
      highlighted: false,
      gracePeriodDays: 30,
    },
    {
      name: "Unlimited Annual",
      price: 700,
      badge: null,
      description: "For teams that need unlimited annual application generation.",
      applicationsIncluded: null,
      validityPeriod: "1 year",
      billingType: "subscription",
      planType: "unlimited",
      allowAdditionalPayers: false,
      additionalPayerPrice: null,
      features: [
        "Unlimited payer applications",
        "Profile builder",
        "Document vault",
        "Credential expiry tracking",
      ],
      cta: "Get Started",
      highlighted: false,
      gracePeriodDays: 30,
    },
  ],
  postYearBehavior: {
    title: "What happens after 1 year?",
    items: [
      { text: "Your profile and documents remain accessible (forever)", kind: "positive" },
      { text: "You can view and download all generated packets", kind: "positive" },
      { text: "You continue receiving credential expiry notifications", kind: "positive" },
      { text: "You cannot generate NEW applications", kind: "negative" },
    ],
    renewalNote: "To generate new credential packets, simply purchase another pack.",
  },
  footerNote: "Prices shown before sales tax.",
};

// ============================================================================
// SUPPORT SECTION
// ============================================================================

export const supportContent = {
  sectionTitle: "We're here when you need us",
  sectionSubtitle: "15-minute response target for critical issues like login failures and packet generation.",
  features: [
    { icon: "MessageSquare", text: "Chat support" },
    { icon: "Mail", text: "Email support" },
    { icon: "Clock", text: "24/7 availability" },
  ],
  links: [
    { label: "Help Center", href: "#" },
    { label: "Status Page", href: "#" },
  ],
};

// ============================================================================
// TEAM SECTION
// ============================================================================

export const teamContent = {
  sectionTitle: "The team behind CCS Pro",
  sectionSubtitle: "Built by people who understand credentialing",
  members: [
    {
      name: "David",
      role: "Operations & Security",
      icon: "Shield",
      bio: "Ex-US Air Force. Brings an operations mindset focused on reliability and security.",
    },
    {
      name: "Skeeter",
      role: "Advisor",
      icon: "Briefcase",
      bio: "Strategic advisor supporting product direction.",
    },
  ],
};

// ============================================================================
// FAQ SECTION
// ============================================================================

export const faqContent = {
  sectionTitle: "Frequently asked questions",
  sectionSubtitle: "Everything you need to know about CCS Pro",
  items: [
    {
      question: "What do I need to start?",
      answer: "Just your email to create an account. From there, you can add your NPI, licenses, certifications, and supporting documents at your own pace. You can preview a draft packet even before your profile is complete.",
    },
    {
      question: "Do you support states other than Texas?",
      answer: "Texas is live now. We're actively rolling out support for additional states. Join our waitlist to be notified when your state becomes available.",
    },
    {
      question: "What is included in a structured packet?",
      answer: "A complete Texas credentialing packet includes your provider profile, state licenses, malpractice certificate of insurance, board certifications, work history verification, and required attestations—all formatted to meet payer and facility requirements.",
    },
    {
      question: "How does signed PDF work?",
      answer: "Pro subscribers can generate a signed PDF using our integrated e-signature provider. The signed document includes a complete audit trail showing when and by whom the document was signed, meeting most payer requirements for electronic signatures.",
    },
    {
      question: "What sensitive documents do you store?",
      answer: "We store documents you upload including driver's licenses, passports, medical licenses, DEA certificates, malpractice COIs, and other credentialing documents. All documents are stored in the United States with encryption in transit and at rest.",
    },
    {
      question: "Can I cancel anytime?",
      answer: "Yes, you can cancel your Pro subscription at any time. You'll retain access until the end of your billing period. See our refund policy for details on money-back eligibility.",
    },
    {
      question: "How does CAQH Concierge work?",
      answer: "With CAQH Concierge, our team maintains your CAQH profile on your behalf. We upload documents, coordinate updates, and send reminders—so you don't have to log in to CAQH yourself. You choose your preferred consent model during onboarding.",
    },
    {
      question: "How do consent modes work and can I revoke access?",
      answer: "You choose between explicit consent (approve each action individually) or standing authorization (pre-approve defined actions). With either mode, you can revoke access instantly at any time. Every change triggers an activity log entry and email notification.",
    },
    {
      question: "What counts as a $29 update request?",
      answer: "An update request covers a single coordinated update to your credentialing profile or CAQH, such as adding a new license or updating expired documents. Complex requests requiring multiple actions may count as separate updates.",
    },
  ],
};

// ============================================================================
// FINAL CTA SECTION
// ============================================================================

export const finalCtaContent = {
  headline: "Stop redoing credentialing paperwork",
  subheadline: "Start free and generate your Texas packet today.",
  primaryCta: { label: "Start free", href: "#pricing" },
  secondaryCta: { label: "View sample Texas packet", href: "#sample" },
};

// ============================================================================
// FOOTER
// ============================================================================

export const footerContent = {
  brand: {
    name: "CCS Pro",
    description: "Credentialing packets for US providers. Store credentials once, track expirations, generate structured packets.",
  },
  trustBadges: [
    { icon: "MapPin", text: "US-only storage" },
    { icon: "Shield", text: "Texas live now" },
    { icon: "Clock", text: "More states rolling out" },
  ],
  links: {
    legal: [
      { label: "Privacy", href: "#" },
      { label: "Terms", href: "#" },
      { label: "Security", href: "#security" },
      { label: "Refunds", href: "#refunds" },
    ],
    support: [
      { label: "Contact", href: "#" },
      { label: "Help Center", href: "#" },
      { label: "Status", href: "#" },
    ],
  },
  copyright: "CCS Pro. All rights reserved.",
};

/** Static fallback when WordPress API is unavailable. Matches LandingPageContent shape. */
export const defaultLandingPageContent = {
  siteConfig,
  navLinks,
  navCtas,
  heroContent,
  heroDashboard,
  verificationContent,
  founderContent,
  problemOutcomeContent,
  howItWorksContent,
  featuresContent,
  packetPreviewContent,
  securityContent,
  caqhConciergeContent,
  pricingContent,
  supportContent,
  teamContent,
  faqContent,
  finalCtaContent,
  footerContent,
} as const;

