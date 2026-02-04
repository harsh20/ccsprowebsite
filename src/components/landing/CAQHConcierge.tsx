import { Bell, CheckCircle } from "lucide-react";
import { caqhConciergeContent } from "@/content/landing";
import { getLandingIcon } from "@/lib/landing-icons";
import { ConsentModeCard } from "./shared/Cards";
import type { LandingPageContent } from "@/types/wordpress";

interface CAQHConciergeProps {
  content?: LandingPageContent;
}

export function CAQHConcierge({ content }: CAQHConciergeProps) {
  const data = content?.caqhConciergeContent ?? caqhConciergeContent;
  return (
    <section className="py-16 sm:py-20 lg:py-24 section-tinted">
      <div className="container mx-auto px-4">
        <div className="grid gap-12 lg:grid-cols-2 items-start">
          {/* Left - Benefits */}
          <div className="space-y-6">
            <div className="space-y-4">
              <div className="badge-pill">{data.badge}</div>
              <h2 className="text-3xl sm:text-4xl font-bold text-foreground">
                {data.sectionTitle}
              </h2>
              <p className="text-lg text-muted-foreground">
                {data.sectionSubtitle}
              </p>
            </div>

            <div className="space-y-3">
              <h3 className="font-semibold text-foreground">{data.benefitsTitle}</h3>
              <div className="space-y-2">
                {data.benefits.map((benefit, index) => (
                  <div key={index} className="flex items-center gap-3">
                    <CheckCircle className="h-5 w-5 text-primary flex-shrink-0" />
                    <span className="text-muted-foreground">{benefit}</span>
                  </div>
                ))}
              </div>
            </div>

            <a href={data.cta.href} className="btn-primary inline-flex">
              {data.cta.label}
            </a>
          </div>

          {/* Right - Consent Modes */}
          <div className="space-y-6">
            <h3 className="font-semibold text-foreground">{data.consentTitle}</h3>

            <div className="space-y-4">
              {data.consentModes.map((mode, index) => {
                const Icon = getLandingIcon(mode.icon);
                return (
                  <ConsentModeCard
                    key={index}
                    icon={Icon}
                    title={mode.title}
                    description={mode.description}
                  />
                );
              })}
            </div>

            {/* Always included */}
            <div className="bg-primary/5 rounded-xl p-4">
              <div className="flex items-start gap-3">
                <Bell className="h-5 w-5 text-primary flex-shrink-0 mt-0.5" />
                <div>
                  <p className="font-medium text-foreground text-sm">
                    {data.alwaysIncluded.title}
                  </p>
                  <p className="text-sm text-muted-foreground">
                    {data.alwaysIncluded.description}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
