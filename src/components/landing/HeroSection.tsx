import { ArrowRight } from "lucide-react";
import { heroContent } from "@/content/landing";
import { getLandingIcon } from "@/lib/landing-icons";
import type { LandingPageContent } from "@/types/wordpress";

interface HeroSectionProps {
  content?: LandingPageContent;
}

export function HeroSection({ content }: HeroSectionProps) {
  const hero = content?.heroContent ?? heroContent;

  return (
    <section className="min-h-[70vh] pt-32 pb-20 px-4 flex items-center">
      <div className="section-container">
        <div className="mx-auto max-w-4xl text-center">
          <div className="space-y-6 flex flex-col items-center">
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

            <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
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

            <div className="flex flex-wrap justify-center gap-3 pt-2">
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
        </div>
      </div>
    </section>
  );
}
