import { ArrowRight } from "lucide-react";
import { heroContent } from "@/content/landing";
import { getLandingIcon } from "@/lib/landing-icons";
import type { LandingPageContent, HeroContent, HeroDashboard } from "@/types/wordpress";

interface HeroSectionProps {
  content?: LandingPageContent;
  heroData?: HeroContent;
  dashboardData?: HeroDashboard;
}

export function HeroSection({ content, heroData, dashboardData }: HeroSectionProps) {
  const hero = heroData ?? content?.heroContent ?? heroContent;
  const dashboard = dashboardData ?? content?.heroDashboard;

  return (
    <section className="min-h-[70vh] pt-32 pb-20 px-4 flex items-center">
      <div className="section-container">
        <div className="grid lg:grid-cols-2 gap-12 items-center">
          {/* Left: copy */}
          <div className="space-y-6">
            <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight text-foreground text-balance">
              {hero.headline}{" "}
              <span className="text-gradient">{hero.headlineHighlight}</span>
              {hero.headlineSuffix && ` ${hero.headlineSuffix}`}
            </h1>

            <p className="text-lg text-muted-foreground max-w-xl">
              {hero.subheadline}
            </p>

            <div className="flex flex-col sm:flex-row items-start gap-4">
              <a
                href={hero.primaryCta.href}
                className="btn-primary text-base px-7 py-3.5 inline-flex items-center gap-2"
              >
                {hero.primaryCta.label}
                <ArrowRight className="h-4 w-4" />
              </a>
              {hero.secondaryCta.label && (
                <a
                  href={hero.secondaryCta.href}
                  className="btn-secondary text-base px-7 py-3.5"
                >
                  {hero.secondaryCta.label}
                </a>
              )}
            </div>

            {hero.tertiaryCta?.label && (
              <a
                href={hero.tertiaryCta.href}
                className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-primary transition-colors"
              >
                {hero.tertiaryCta.label}
              </a>
            )}

            <div className="flex flex-wrap gap-3 pt-2">
              {hero.trustIndicators.map((indicator, index) => {
                const Icon = getLandingIcon(indicator.icon);
                return (
                  <span
                    key={index}
                    className="inline-flex items-center gap-2 rounded-full bg-muted px-3 py-1 text-xs text-muted-foreground"
                  >
                    <Icon className="h-3.5 w-3.5 text-primary" />
                    {indicator.text}
                  </span>
                );
              })}
            </div>
          </div>

          {/* Right: dashboard mock */}
          {dashboard && <DashboardCard dashboard={dashboard} />}
        </div>
      </div>
    </section>
  );
}

function DashboardCard({ dashboard }: { dashboard: HeroDashboard }) {
  const statusColorClass = (color: string) => {
    switch (color) {
      case "green":
        return "text-green-600 bg-green-50";
      case "orange":
        return "text-orange-600 bg-orange-50";
      case "red":
        return "text-red-600 bg-red-50";
      case "blue":
        return "text-blue-600 bg-blue-50";
      default:
        return "text-gray-600 bg-gray-50";
    }
  };

  return (
    <div className="card-elevated p-6 space-y-5 max-w-md mx-auto lg:mx-0">
      <div className="flex items-center justify-between">
        <div>
          <h3 className="font-semibold text-foreground">{dashboard.title}</h3>
          <p className="text-sm text-muted-foreground">{dashboard.subtitle}</p>
        </div>
        <div className="text-right">
          <span className="text-2xl font-bold text-primary">
            {dashboard.completionPercent}%
          </span>
        </div>
      </div>

      {/* Progress bar */}
      <div className="w-full bg-muted rounded-full h-2">
        <div
          className="bg-primary h-2 rounded-full transition-all"
          style={{ width: `${dashboard.completionPercent}%` }}
        />
      </div>

      {/* Documents */}
      <div className="space-y-2">
        {dashboard.documents.map((doc, index) => (
          <div
            key={index}
            className="flex items-center justify-between py-2 px-3 rounded-lg bg-muted/30"
          >
            <span className="text-sm text-foreground">{doc.name}</span>
            <span
              className={`text-xs font-medium px-2 py-0.5 rounded-full ${statusColorClass(doc.statusColor)}`}
            >
              {doc.status}
            </span>
          </div>
        ))}
      </div>

      <div className="flex gap-3">
        <button className="btn-primary text-sm flex-1">
          {dashboard.buttons.primary}
        </button>
        <button className="btn-secondary text-sm flex-1">
          {dashboard.buttons.secondary}
        </button>
      </div>
    </div>
  );
}
