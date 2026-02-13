import { ArrowRight, CheckCircle2, Clock, AlertTriangle, Moon, Sun } from "lucide-react";
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
    <section className="pt-32 pb-20 px-4">
      <div className="section-container">
        <div className="grid gap-10 lg:grid-cols-2 lg:items-center">
          <div className="space-y-6">
            <div className="inline-flex items-center gap-2 rounded-full bg-accent/10 px-4 py-2 text-sm text-accent">
              <span className="relative flex h-2 w-2">
                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent opacity-75" />
                <span className="relative inline-flex rounded-full h-2 w-2 bg-accent" />
              </span>
              Trusted by credentialing teams
            </div>

            <h1 className="text-4xl md:text-6xl font-bold tracking-tight text-foreground text-balance">
              {hero.headline} {" "}
              <span className="text-gradient">{hero.headlineHighlight}</span>
            </h1>

            <p className="text-lg text-muted-foreground max-w-2xl">{hero.subheadline}</p>

            <div className="flex flex-col sm:flex-row items-start sm:items-center gap-4">
              <a href={hero.primaryCta.href} className="btn-primary text-base px-7 py-3.5">
                {hero.primaryCta.label}
                <ArrowRight className="h-4 w-4" />
              </a>
              <a href={hero.secondaryCta.href} className="btn-secondary text-base px-7 py-3.5">
                {hero.secondaryCta.label}
              </a>
            </div>

            <a href={hero.tertiaryCta.href} className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-primary transition-colors">
              {hero.tertiaryCta.label}
            </a>

            <div className="flex flex-wrap gap-3 pt-2">
              {hero.trustIndicators.map((indicator, index) => {
                const Icon = getLandingIcon(indicator.icon);
                return (
                  <span key={index} className="inline-flex items-center gap-2 rounded-full bg-muted px-3 py-1 text-xs text-muted-foreground">
                    <Icon className="h-3.5 w-3.5 text-primary" />
                    {indicator.text}
                  </span>
                );
              })}
            </div>
          </div>

          <div className="card-elevated card-hover p-5 md:p-6 bg-card">
            <div className="flex items-center justify-between mb-5">
              <div>
                <p className="font-semibold text-foreground">{dashboard.title}</p>
                <p className="text-sm text-muted-foreground">{dashboard.subtitle}</p>
              </div>
              <span className="status-badge status-current">{dashboard.completionPercent}% Complete</span>
            </div>

            <div className="grid grid-cols-2 gap-3 mb-4">
              <div className="rounded-lg border border-border p-3 bg-muted/40">
                <p className="text-xs text-muted-foreground mb-1">State</p>
                <p className="text-sm font-medium">{dashboard.stateValue}</p>
              </div>
              <div className="rounded-lg border border-border p-3 bg-muted/40">
                <p className="text-xs text-muted-foreground mb-1">NPI</p>
                <p className="text-sm font-medium">{dashboard.npiValue}</p>
              </div>
            </div>

            <div className="mb-4 flex flex-wrap gap-2">
              {dashboard.readinessStates.map((state, i) => (
                <ReadinessStatePill key={i} label={state.label} color={state.color as "red" | "blue" | "orange" | "gray" | "green"} />
              ))}
            </div>

            <div className="space-y-2 mb-4">
              {dashboard.documents.map((doc, idx) => {
                const icon = doc.statusColor === "green"
                  ? <CheckCircle2 className="h-4 w-4 text-success" />
                  : doc.statusColor === "orange"
                  ? <Clock className="h-4 w-4 text-warning" />
                  : <AlertTriangle className="h-4 w-4 text-destructive" />;

                return (
                  <div key={idx} className="flex items-center justify-between rounded-lg border border-border px-3 py-2 bg-muted/30">
                    <span className="text-sm text-foreground flex items-center gap-2">{icon}{doc.name}</span>
                    <span className="text-xs text-muted-foreground">{doc.status}</span>
                  </div>
                );
              })}
            </div>

            <div className="grid grid-cols-2 gap-3">
              <button type="button" className="btn-secondary text-xs px-3 py-2">{dashboard.buttons.secondary}</button>
              <button type="button" className="btn-primary text-xs px-3 py-2">{dashboard.buttons.primary}</button>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
