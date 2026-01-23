import type { LucideIcon } from "lucide-react";

// ============================================================================
// STEP CARD - Used in How It Works section
// ============================================================================

interface StepCardProps {
  icon: LucideIcon;
  step: string;
  title: string;
  description: string;
}

export function StepCard({ icon: Icon, step, title, description }: StepCardProps) {
  return (
    <div className="card-elevated card-hover p-8 relative overflow-hidden">
      <span className="absolute top-4 right-4 text-6xl font-bold text-primary/5">
        {step}
      </span>
      <div className="relative space-y-4">
        <div className="icon-box">
          <Icon className="h-5 w-5" />
        </div>
        <h3 className="text-lg font-semibold text-foreground">{title}</h3>
        <p className="text-muted-foreground">{description}</p>
      </div>
    </div>
  );
}

// ============================================================================
// FEATURE CARD - Used in Features Grid section
// ============================================================================

interface FeatureCardProps {
  icon: LucideIcon;
  title: string;
  description: string;
  link?: string;
  linkText?: string;
}

export function FeatureCard({ 
  icon: Icon, 
  title, 
  description, 
  link = "#",
  linkText = "Learn more →"
}: FeatureCardProps) {
  return (
    <div className="card-elevated card-hover p-6 space-y-4 flex flex-col h-full">
      <div className="icon-box">
        <Icon className="h-5 w-5" />
      </div>
      <h3 className="font-semibold text-foreground">{title}</h3>
      <p className="text-sm text-muted-foreground flex-grow">{description}</p>
      <a
        href={link}
        className="inline-flex items-center text-sm font-medium text-primary hover:underline"
      >
        {linkText}
      </a>
    </div>
  );
}

// ============================================================================
// PRICING CARD - Used in Pricing section
// ============================================================================

interface PricingCardProps {
  name: string;
  price: string;
  period?: string;
  yearlyPrice?: string;
  yearlyLabel?: string;
  description: string;
  features: string[];
  cta: string;
  highlighted?: boolean;
  badge?: string | null;
  onCtaClick?: () => void;
}

export function PricingCard({
  name,
  price,
  period = "",
  yearlyPrice,
  yearlyLabel,
  description,
  features,
  cta,
  highlighted = false,
  badge,
  onCtaClick,
}: PricingCardProps) {
  return (
    <div
      className={`card-elevated p-6 sm:p-8 relative flex flex-col h-full ${
        highlighted ? "ring-2 ring-primary shadow-lg" : ""
      }`}
    >
      {badge && (
        <div className="absolute -top-3 left-1/2 -translate-x-1/2">
          <div
            className={`flex items-center gap-1 text-xs font-medium px-3 py-1 rounded-full ${
              highlighted
                ? "bg-primary text-primary-foreground"
                : "bg-muted text-muted-foreground"
            }`}
          >
            {badge}
          </div>
        </div>
      )}

      <div className="space-y-4 mb-6">
        <h3 className="text-xl font-bold text-foreground">{name}</h3>
        <div className="flex items-baseline gap-1">
          <span className="text-4xl font-bold text-foreground">{price}</span>
          <span className="text-muted-foreground">{period}</span>
        </div>
        {yearlyPrice && (
          <div className="space-y-1">
            <p className="text-sm text-muted-foreground">{yearlyPrice}</p>
            {yearlyLabel && (
              <p className="text-xs font-medium text-primary">{yearlyLabel}</p>
            )}
          </div>
        )}
        <p className="text-sm text-muted-foreground">{description}</p>
      </div>

      <ul className="space-y-3 mb-8 flex-grow">
        {features.map((feature, index) => (
          <li key={index} className="flex items-start gap-3 text-sm">
            <svg
              className="h-4 w-4 text-primary flex-shrink-0 mt-0.5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              strokeWidth={2}
            >
              <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span className="text-foreground">{feature}</span>
          </li>
        ))}
      </ul>

      <button
        onClick={onCtaClick}
        className={`w-full mt-auto ${highlighted ? "btn-primary" : "btn-secondary"}`}
      >
        {cta}
      </button>
    </div>
  );
}

// ============================================================================
// PROBLEM CARD - Used in Problem/Outcome section
// ============================================================================

interface ProblemCardProps {
  icon: LucideIcon;
  title: string;
  description: string;
}

export function ProblemCard({ icon: Icon, title, description }: ProblemCardProps) {
  return (
    <div className="card-elevated p-6 text-center space-y-4">
      <div className="icon-box mx-auto">
        <Icon className="h-5 w-5" />
      </div>
      <h3 className="font-semibold text-foreground">{title}</h3>
      <p className="text-sm text-muted-foreground">{description}</p>
    </div>
  );
}

// ============================================================================
// TEAM MEMBER CARD - Used in Team section
// ============================================================================

interface TeamMemberCardProps {
  name: string;
  role: string;
  bio: string;
  icon: LucideIcon;
}

export function TeamMemberCard({ name, role, bio, icon: Icon }: TeamMemberCardProps) {
  return (
    <div className="card-elevated p-6 text-center space-y-4">
      <div className="h-20 w-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto">
        <Icon className="h-8 w-8 text-primary" />
      </div>
      <div>
        <h3 className="font-semibold text-foreground text-lg">{name}</h3>
        <p className="text-sm text-primary">{role}</p>
      </div>
      <p className="text-sm text-muted-foreground">{bio}</p>
    </div>
  );
}

// ============================================================================
// SECTION HEADER - Reusable section header component
// ============================================================================

interface SectionHeaderProps {
  title: string;
  subtitle?: string;
  centered?: boolean;
  className?: string;
}

export function SectionHeader({ 
  title, 
  subtitle, 
  centered = true,
  className = ""
}: SectionHeaderProps) {
  return (
    <div className={`${centered ? "text-center" : ""} max-w-2xl ${centered ? "mx-auto" : ""} mb-12 ${className}`}>
      <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">
        {title}
      </h2>
      {subtitle && (
        <p className="text-lg text-muted-foreground">{subtitle}</p>
      )}
    </div>
  );
}

// ============================================================================
// SECURITY FEATURE ROW - Used in Security section
// ============================================================================

interface SecurityFeatureProps {
  icon: LucideIcon;
  text: string;
}

export function SecurityFeature({ icon: Icon, text }: SecurityFeatureProps) {
  return (
    <div className="flex items-center gap-4">
      <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
        <Icon className="h-5 w-5 text-primary" />
      </div>
      <span className="text-foreground font-medium">{text}</span>
    </div>
  );
}

// ============================================================================
// VERIFICATION BADGE - Used in Logo Strip section
// ============================================================================

interface VerificationBadgeProps {
  icon: LucideIcon;
  label: string;
}

export function VerificationBadge({ icon: Icon, label }: VerificationBadgeProps) {
  return (
    <div className="flex items-center gap-3 px-6 py-3 rounded-xl bg-muted/50 border border-border/50">
      <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
        <Icon className="h-5 w-5 text-primary" />
      </div>
      <span className="font-medium text-foreground">{label}</span>
    </div>
  );
}

// ============================================================================
// CONSENT MODE CARD - Used in CAQH Concierge section
// ============================================================================

interface ConsentModeCardProps {
  icon: LucideIcon;
  title: string;
  description: string;
}

export function ConsentModeCard({ icon: Icon, title, description }: ConsentModeCardProps) {
  return (
    <div className="card-elevated p-6 flex gap-4">
      <div className="icon-box flex-shrink-0">
        <Icon className="h-5 w-5" />
      </div>
      <div>
        <h4 className="font-semibold text-foreground mb-1">{title}</h4>
        <p className="text-sm text-muted-foreground">{description}</p>
      </div>
    </div>
  );
}

// ============================================================================
// SUPPORT FEATURE BADGE - Used in Support section
// ============================================================================

interface SupportFeatureProps {
  icon: LucideIcon;
  text: string;
}

export function SupportFeatureBadge({ icon: Icon, text }: SupportFeatureProps) {
  return (
    <div className="flex items-center gap-3 px-6 py-4 bg-background rounded-xl shadow-sm">
      <Icon className="h-5 w-5 text-primary" />
      <span className="font-medium text-foreground">{text}</span>
    </div>
  );
}

// ============================================================================
// READINESS STATE PILL - Used in Hero and How It Works
// ============================================================================

interface ReadinessStatePillProps {
  label: string;
  color: "red" | "blue" | "orange" | "gray" | "green";
}

const colorMap = {
  red: { bg: "bg-red-100", text: "text-red-700", dot: "bg-red-500" },
  blue: { bg: "bg-blue-100", text: "text-blue-700", dot: "bg-blue-500" },
  orange: { bg: "bg-orange-100", text: "text-orange-700", dot: "bg-orange-500" },
  gray: { bg: "bg-gray-100", text: "text-gray-700", dot: "bg-gray-500" },
  green: { bg: "bg-green-100", text: "text-green-700", dot: "bg-green-500" },
};

export function ReadinessStatePill({ label, color }: ReadinessStatePillProps) {
  const colors = colorMap[color];
  return (
    <span className={`inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ${colors.bg} ${colors.text}`}>
      <span className={`h-1.5 w-1.5 rounded-full ${colors.dot}`} />
      {label}
    </span>
  );
}
