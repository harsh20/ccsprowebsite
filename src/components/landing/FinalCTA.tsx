import { ArrowRight } from "lucide-react";
import { finalCtaContent } from "@/content/landing";
import type { LandingPageContent } from "@/types/wordpress";

interface FinalCTAProps {
  content?: LandingPageContent;
}

export function FinalCTA({ content }: FinalCTAProps) {
  const data = content?.finalCtaContent ?? finalCtaContent;
  return (
    <section className="py-16 sm:py-20 lg:py-24 section-tinted">
      <div className="container mx-auto px-4">
        <div className="text-center max-w-2xl mx-auto space-y-8">
          <div className="space-y-4">
            <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-foreground">
              {data.headline}
            </h2>
            <p className="text-xl text-muted-foreground">
              {data.subheadline}
            </p>
          </div>

          <div className="flex flex-wrap justify-center gap-4">
            <a href={data.primaryCta.href} className="btn-primary text-base px-8 py-4">
              {data.primaryCta.label}
              <ArrowRight className="h-4 w-4" />
            </a>
            <a href={data.secondaryCta.href} className="btn-secondary text-base px-8 py-4">
              {data.secondaryCta.label}
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}
