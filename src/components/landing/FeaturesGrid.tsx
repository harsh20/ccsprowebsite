import { Vault, Sparkles, FileText, UserCheck, Bell, History } from "lucide-react";
import { featuresContent } from "@/content/landing";
import { FeatureCard, SectionHeader } from "./shared/Cards";

const iconMap = {
  Vault,
  Sparkles,
  FileText,
  UserCheck,
  Bell,
  History,
};

export function FeaturesGrid() {
  return (
    <section id="product" className="py-16 sm:py-20 lg:py-24 section-tinted">
      <div className="container mx-auto px-4">
        <SectionHeader
          title={featuresContent.sectionTitle}
          subtitle={featuresContent.sectionSubtitle}
        />

        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {featuresContent.features.map((feature, index) => {
            const Icon = iconMap[feature.icon as keyof typeof iconMap];
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
