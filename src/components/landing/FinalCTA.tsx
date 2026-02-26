import { ArrowRight } from "lucide-react";
import { Link } from "react-router-dom";
import { finalCtaContent } from "@/content/landing";
import type { LandingPageContent, CtaBlockContent } from "@/types/wordpress";

interface FinalCTAProps {
  content?: LandingPageContent;
  blockData?: CtaBlockContent;
}

export function FinalCTA({ content, blockData }: FinalCTAProps) {
  if (blockData) {
    return <CtaBlock data={blockData} />;
  }

  // Legacy path
  const data = content?.finalCtaContent ?? finalCtaContent;
  return (
    <section className="py-20 px-4">
      <div className="section-container max-w-4xl">
        <div className="gradient-hero rounded-2xl p-10 md:p-12 text-center text-white">
          <h2 className="text-3xl md:text-4xl font-bold mb-4">{data.headline}</h2>
          <p className="text-lg opacity-90 mb-8 max-w-2xl mx-auto">
            {data.subheadline}
          </p>
          <div className="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a
              href={data.primaryCta.href}
              className="inline-flex items-center gap-2 rounded-xl bg-white text-primary px-7 py-3 font-semibold hover:bg-white/90 transition-colors"
            >
              {data.primaryCta.label}
              <ArrowRight className="h-4 w-4" />
            </a>
            <a
              href={data.secondaryCta.href}
              className="inline-flex items-center rounded-xl border border-white/40 px-7 py-3 font-semibold text-white hover:bg-white/10 transition-colors"
            >
              {data.secondaryCta.label}
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}

function CtaBlock({ data }: { data: CtaBlockContent }) {
  const bgClass =
    data.style === "indigo"
      ? "bg-indigo-600"
      : "bg-emerald-600";

  const renderLink = (cta: { label: string; href: string }, primary: boolean) => {
    const baseClass = primary
      ? data.style === "indigo"
        ? "inline-flex items-center justify-center rounded-full bg-white px-6 py-3 font-semibold text-indigo-700 hover:bg-indigo-50 transition-colors"
        : "inline-flex items-center justify-center rounded-full bg-white px-6 py-3 font-semibold text-emerald-700 hover:bg-emerald-50 transition-colors"
      : "inline-flex items-center text-white underline underline-offset-4 text-sm font-medium";

    if (cta.href.startsWith("/")) {
      return (
        <Link to={cta.href} className={baseClass}>
          {cta.label}
        </Link>
      );
    }

    return (
      <a href={cta.href} className={baseClass}>
        {cta.label}
      </a>
    );
  };

  return (
    <section className="py-20 px-4">
      <div className="section-container max-w-4xl">
        <div
          className={`${bgClass} rounded-2xl p-10 md:p-12 text-center text-white`}
        >
          <h2 className="text-3xl md:text-4xl font-bold mb-4">{data.headline}</h2>
          <p className="text-lg opacity-90 mb-8 max-w-2xl mx-auto">
            {data.subheadline}
          </p>
          <div className="flex flex-col sm:flex-row items-center justify-center gap-3">
            {renderLink(data.primaryCta, true)}
            {renderLink(data.secondaryCta, false)}
          </div>
        </div>
      </div>
    </section>
  );
}
