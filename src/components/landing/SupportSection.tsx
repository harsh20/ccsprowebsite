import { ExternalLink } from "lucide-react";
import { supportContent } from "@/content/landing";
import { getLandingIcon } from "@/lib/landing-icons";
import { SupportFeatureBadge } from "./shared/Cards";
import type { LandingPageContent } from "@/types/wordpress";

interface SupportSectionProps {
  content?: LandingPageContent;
}

export function SupportSection({ content }: SupportSectionProps) {
  const data = content?.supportContent ?? supportContent;
  return (
    <section className="py-16 sm:py-20 lg:py-24 section-tinted">
      <div className="container mx-auto px-4">
        <div className="max-w-3xl mx-auto text-center space-y-8">
          <div className="space-y-4">
            <h2 className="text-3xl sm:text-4xl font-bold text-foreground">
              {data.sectionTitle}
            </h2>
            <p className="text-lg text-muted-foreground">
              {data.sectionSubtitle}
            </p>
          </div>

          <div className="flex flex-wrap justify-center gap-6">
            {data.features.map((feature, index) => {
              const Icon = getLandingIcon(feature.icon);
              return <SupportFeatureBadge key={index} icon={Icon} text={feature.text} />;
            })}
          </div>

          <div className="flex flex-wrap justify-center gap-4">
            {data.links.map((link, index) => (
              <span key={index}>
                <a
                  href={link.href}
                  className="inline-flex items-center gap-2 text-primary font-medium hover:underline"
                >
                  {link.label}
                  <ExternalLink className="h-4 w-4" />
                </a>
                {index < data.links.length - 1 && (
                  <span className="ml-4 text-muted-foreground">•</span>
                )}
              </span>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}
