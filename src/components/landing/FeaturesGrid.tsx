import { featuresContent } from "@/content/landing";
import { getLandingIcon } from "@/lib/landing-icons";
import { FeatureCard, SectionHeader } from "./shared/Cards";
import type { LandingPageContent } from "@/types/wordpress";

interface FeaturesGridProps {
  content?: LandingPageContent;
}

export function FeaturesGrid({ content }: FeaturesGridProps) {
  const data = content?.featuresContent ?? featuresContent;
  return (
    <section id="product" className="py-16 sm:py-20 lg:py-24 section-tinted">
      <div className="container mx-auto px-4">
        <SectionHeader
          title={data.sectionTitle}
          subtitle={data.sectionSubtitle}
        />

        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {data.features.map((feature, index) => {
            const Icon = getLandingIcon(feature.icon);
            return (
              <FeatureCard
                key={index}
                icon={Icon}
                title={feature.title}
                description={feature.description}
                link={feature.link}
              />
            );
          })}
        </div>
      </div>
    </section>
  );
}
