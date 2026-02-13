import { useMemo, useState } from "react";
import { pricingContent } from "@/content/landing";
import { PricingCard } from "./shared/Cards";
import { Switch } from "@/components/ui/switch";
import type { LandingPageContent } from "@/types/wordpress";

interface PricingSectionProps {
  content?: LandingPageContent;
}

export function PricingSection({ content }: PricingSectionProps) {
  const [isYearly, setIsYearly] = useState(false);
  const data = content?.pricingContent ?? pricingContent;
  const additional = data.additionalInfo;

  const plans = useMemo(
    () =>
      data.plans.map((plan) => ({
        ...plan,
        price: isYearly && plan.yearlyPrice ? plan.yearlyPrice : plan.price,
      })),
    [data.plans, isYearly]
  );

  return (
    <section id="pricing" className="py-20 px-4 bg-muted/30">
      <div className="section-container max-w-5xl">
        <div className="text-center mb-12">
          <h2 className="text-3xl md:text-4xl font-bold text-foreground mb-4">{data.sectionTitle}</h2>
          <p className="text-lg text-muted-foreground mb-6">{data.sectionSubtitle}</p>

          <div className="flex items-center justify-center gap-3 mb-3">
            <span className={`text-sm font-medium ${!isYearly ? "text-foreground" : "text-muted-foreground"}`}>Monthly</span>
            <Switch checked={isYearly} onCheckedChange={setIsYearly} />
            <span className={`text-sm font-medium ${isYearly ? "text-foreground" : "text-muted-foreground"}`}>Yearly <span className="text-success">(Save more)</span></span>
          </div>
        </div>

        <div className="grid gap-6 lg:grid-cols-3">
          {plans.map((plan, index) => (
            <PricingCard
              key={index}
              name={plan.name}
              price={plan.price}
              period={isYearly ? "" : plan.period}
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

        <div className="max-w-3xl mx-auto mt-10 text-center space-y-3">
          <p className="text-sm text-muted-foreground">
            <span className="font-medium text-foreground">{additional.updatePrice}</span>
          </p>
          <div className="p-4 rounded-xl border border-border bg-background/70">
            <p className="text-sm text-muted-foreground">{additional.refundPolicy}</p>
            <a href={additional.refundLink.href} className="inline-block mt-2 text-sm text-primary font-medium hover:underline">
              {additional.refundLink.label}
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}
