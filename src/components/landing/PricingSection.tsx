import { pricingContent } from "@/content/landing";
import { PricingCard, SectionHeader } from "./shared/Cards";
import type { LandingPageContent } from "@/types/wordpress";

interface PricingSectionProps {
  content?: LandingPageContent;
}

export function PricingSection({ content }: PricingSectionProps) {
  const data = content?.pricingContent ?? pricingContent;
  const additional = data.additionalInfo;
  return (
    <section id="pricing" className="py-16 sm:py-20 lg:py-24 bg-background">
      <div className="container mx-auto px-4">
        <SectionHeader
          title={data.sectionTitle}
          subtitle={data.sectionSubtitle}
        />

        <div className="grid gap-6 lg:grid-cols-3 max-w-5xl mx-auto">
          {data.plans.map((plan, index) => (
            <PricingCard
              key={index}
              name={plan.name}
              price={plan.price}
              period={plan.period}
              yearlyPrice={plan.yearlyPrice}
              yearlyLabel={plan.yearlyLabel}
              description={plan.description}
              features={plan.features}
              cta={plan.cta}
              highlighted={plan.highlighted}
              badge={plan.badge}
            />
          ))}
        </div>

        {/* Additional Info */}
        <div className="max-w-2xl mx-auto mt-12 text-center space-y-4">
          <p className="text-sm text-muted-foreground">
            <span className="font-medium text-foreground">{additional.updatePrice}</span>
          </p>
          <div className="p-4 bg-muted/50 rounded-xl">
            <p className="text-sm text-muted-foreground">
              {additional.refundPolicy}
            </p>
            <a
              href={additional.refundLink.href}
              className="text-sm text-primary font-medium hover:underline mt-2 inline-block"
            >
              {additional.refundLink.label}
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}
