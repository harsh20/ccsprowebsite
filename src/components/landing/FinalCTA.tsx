import { ArrowRight } from "lucide-react";
import { finalCtaContent } from "@/content/landing";
import type { LandingPageContent } from "@/types/wordpress";

interface FinalCTAProps {
  content?: LandingPageContent;
}

export function FinalCTA({ content }: FinalCTAProps) {
  const data = content?.finalCtaContent ?? finalCtaContent;

  return (
    <section className="py-20 px-4">
      <div className="section-container max-w-4xl">
        <div className="gradient-hero rounded-2xl p-10 md:p-12 text-center text-white">
          <h2 className="text-3xl md:text-4xl font-bold mb-4">{data.headline}</h2>
          <p className="text-lg opacity-90 mb-8 max-w-2xl mx-auto">{data.subheadline}</p>
          <div className="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href={data.primaryCta.href} className="inline-flex items-center gap-2 rounded-xl bg-white text-primary px-7 py-3 font-semibold hover:bg-white/90 transition-colors">
              {data.primaryCta.label}
              <ArrowRight className="h-4 w-4" />
            </a>
            <a href={data.secondaryCta.href} className="inline-flex items-center rounded-xl border border-white/40 px-7 py-3 font-semibold text-white hover:bg-white/10 transition-colors">
              {data.secondaryCta.label}
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}
