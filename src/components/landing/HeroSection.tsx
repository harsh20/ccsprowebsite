import { ArrowRight, FileText } from "lucide-react";
import { heroContent, heroDashboard } from "@/content/landing";
import { getLandingIcon } from "@/lib/landing-icons";
import { ReadinessStatePill } from "./shared/Cards";
import type { LandingPageContent } from "@/types/wordpress";

interface HeroSectionProps {
  content?: LandingPageContent;
}

export function HeroSection({ content }: HeroSectionProps) {
  const hero = content?.heroContent ?? heroContent;
  const dashboard = content?.heroDashboard ?? heroDashboard;

  return (
    <section className="section-tinted overflow-hidden">
      <div className="container mx-auto px-4 py-12 sm:py-16 lg:py-20">
        <div className="grid gap-12 lg:grid-cols-2 lg:gap-16 items-center">
          {/* Left Column - Copy */}
          <div className="space-y-8">
            <div className="space-y-4">
              <h1 className="text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl text-balance">
                {hero.headline}{" "}
                <span className="text-primary">{hero.headlineHighlight}</span>
              </h1>
              <p className="text-lg text-muted-foreground sm:text-xl max-w-xl">
                {hero.subheadline}
              </p>
            </div>

            {/* CTAs */}
            <div className="flex flex-wrap gap-4 items-center">
              <a href={hero.primaryCta.href} className="btn-primary text-base px-8 py-4">
                {hero.primaryCta.label}
                <ArrowRight className="h-4 w-4" />
              </a>
              <a href={hero.secondaryCta.href} className="btn-secondary text-base">
                {hero.secondaryCta.label}
              </a>
            </div>
            <a
              href={hero.tertiaryCta.href}
              className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-primary transition-colors"
            >
              {hero.tertiaryCta.label}
            </a>

            {/* Trust Line */}
            <div className="flex flex-wrap gap-4 text-sm text-muted-foreground pt-4 border-t border-border/50">
              {hero.trustIndicators.map((indicator, index) => {
                const Icon = getLandingIcon(indicator.icon);
                return (
                  <span key={index} className="flex items-center gap-2">
                    <Icon className="h-4 w-4 text-primary" />
                    {indicator.text}
                  </span>
                );
              })}
            </div>
          </div>

          {/* Right Column - Dashboard Mock */}
          <div className="relative">
            <div className="card-elevated p-1 bg-gradient-to-br from-primary/5 to-primary/10">
              <div className="rounded-xl bg-background p-6 space-y-5">
                {/* Header */}
                <div className="flex items-center justify-between">
                  <div className="flex items-center gap-3">
                    <div className="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                      <FileText className="h-5 w-5 text-primary" />
                    </div>
                    <div>
                      <p className="font-semibold text-foreground">{dashboard.title}</p>
                      <p className="text-sm text-muted-foreground">{dashboard.subtitle}</p>
                    </div>
                  </div>
                  <div className="badge-pill">{dashboard.completionPercent}% Complete</div>
                </div>

                {/* State & NPI */}
                <div className="grid grid-cols-2 gap-4">
                  <div className="space-y-1.5">
                    <label className="text-xs font-medium text-muted-foreground">State</label>
                    <div className="flex items-center gap-2 rounded-lg border border-border bg-muted/30 px-3 py-2">
                      <span className="text-sm font-medium">{dashboard.stateValue}</span>
                    </div>
                  </div>
                  <div className="space-y-1.5">
                    <label className="text-xs font-medium text-muted-foreground">NPI Number</label>
                    <div className="flex items-center gap-2 rounded-lg border border-border bg-muted/30 px-3 py-2">
                      <span className="text-sm font-medium">{dashboard.npiValue}</span>
                    </div>
                  </div>
                </div>

                {/* Readiness States */}
                <div className="space-y-3">
                  <p className="text-xs font-medium text-muted-foreground uppercase tracking-wide">
                    Readiness States
                  </p>
                  <div className="flex flex-wrap gap-2">
                    {dashboard.readinessStates.map((state, index) => (
                      <ReadinessStatePill
                        key={index}
                        label={state.label}
                        color={state.color as "red" | "blue" | "orange" | "gray" | "green"}
                      />
                    ))}
                  </div>
                </div>

                {/* Document Status */}
                <div className="space-y-2">
                  {dashboard.documents.map((doc, index) => (
                    <div
                      key={index}
                      className={`flex items-center justify-between rounded-lg border px-3 py-2 ${
                        doc.statusColor === "green"
                          ? "border-green-200 bg-green-50"
                          : doc.statusColor === "orange"
                          ? "border-orange-200 bg-orange-50"
                          : "border-red-200 bg-red-50"
                      }`}
                    >
                      <span className="text-sm">{doc.name}</span>
                      <span
                        className={`text-xs font-medium ${
                          doc.statusColor === "green"
                            ? "text-green-600"
                            : doc.statusColor === "orange"
                            ? "text-orange-600"
                            : "text-red-600"
                        }`}
                      >
                        {doc.status}
                      </span>
                    </div>
                  ))}
                </div>

                {/* Action Buttons */}
                <div className="grid grid-cols-2 gap-3">
                  <button className="btn-secondary text-sm py-2.5">
                    {dashboard.buttons.secondary}
                  </button>
                  <button className="btn-primary text-sm py-2.5">
                    {dashboard.buttons.primary}
                  </button>
                </div>
              </div>
            </div>

            {/* Decorative Elements */}
            <div className="absolute -top-4 -right-4 h-24 w-24 rounded-full bg-primary/10 blur-3xl" />
            <div className="absolute -bottom-8 -left-8 h-32 w-32 rounded-full bg-primary/5 blur-3xl" />
          </div>
        </div>
      </div>
    </section>
  );
}
