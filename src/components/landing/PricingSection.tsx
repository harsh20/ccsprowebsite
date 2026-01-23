import { pricingContent } from "@/content/landing";
import { PricingCard, SectionHeader } from "./shared/Cards";

export function PricingSection() {
  return (
    <section id="pricing" className="py-16 sm:py-20 lg:py-24 bg-background">
      <div className="container mx-auto px-4">
        <SectionHeader
          title={pricingContent.sectionTitle}
          subtitle={pricingContent.sectionSubtitle}
        />

        <div className="grid gap-6 lg:grid-cols-3 max-w-5xl mx-auto">
          {pricingContent.plans.map((plan, index) => (
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
            <span className="font-medium text-foreground">{pricingContent.additionalInfo.updatePrice}</span>
          </p>
          <div className="p-4 bg-muted/50 rounded-xl">
            <p className="text-sm text-muted-foreground">
              <span className="font-medium text-foreground">14-day money-back guarantee</span>{" "}
              {pricingContent.additionalInfo.refundPolicy.replace("14-day money-back guarantee ", "")}
            </p>
            <a
              href={pricingContent.additionalInfo.refundLink.href}
              className="text-sm text-primary font-medium hover:underline mt-2 inline-block"
            >
              {pricingContent.additionalInfo.refundLink.label}
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}
