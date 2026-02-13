import { pricingContent } from "@/content/landing";
import { PricingCard } from "./shared/Cards";
import type { LandingPageContent } from "@/types/wordpress";

interface PricingSectionProps {
  content?: LandingPageContent;
}

export function PricingSection({ content }: PricingSectionProps) {
  const data = content?.pricingContent ?? pricingContent;
  const packs = data.packs;
  const hasOneTimePlans = packs.some((pack) => pack.billingType === "one_time");
  const gridClass = packs.length <= 2 ? "grid gap-6 md:grid-cols-2" : "grid gap-6 lg:grid-cols-3";

  return (
    <section id="pricing" className="py-20 px-4 bg-muted/30">
      <div className="section-container max-w-5xl">
        <div className="text-center mb-12">
          <h2 className="text-3xl md:text-4xl font-bold text-foreground mb-4">{data.sectionTitle}</h2>
          <p className="text-lg text-muted-foreground">{data.sectionSubtitle}</p>
        </div>

        <div className={gridClass}>
          {packs.map((pack, index) => (
            <PricingCard
              key={index}
              name={pack.name}
              price={pack.price}
              description={pack.description}
              applicationsIncluded={pack.applicationsIncluded}
              validityPeriod={pack.validityPeriod}
              billingType={pack.billingType}
              allowAdditionalPayers={pack.allowAdditionalPayers}
              additionalPayerPrice={pack.additionalPayerPrice ?? null}
              features={pack.features}
              cta={pack.cta}
              highlighted={pack.highlighted}
              badge={pack.badge}
            />
          ))}
        </div>

        {hasOneTimePlans && (
          <div className="max-w-3xl mx-auto mt-10">
            <div className="card-elevated p-6 space-y-4">
              <h3 className="text-xl font-semibold text-foreground">{data.postYearBehavior.title}</h3>
              <ul className="space-y-3">
                {data.postYearBehavior.items.map((item, index) => (
                  <li key={index} className="flex items-start gap-3 text-sm">
                    <span className={item.kind === "positive" ? "text-success" : "text-destructive"}>
                      {item.kind === "positive" ? "✓" : "✗"}
                    </span>
                    <span className="text-foreground">{item.text}</span>
                  </li>
                ))}
              </ul>
              <p className="text-sm text-muted-foreground">{data.postYearBehavior.renewalNote}</p>
            </div>
          </div>
        )}

        {data.footerNote && (
          <p className="max-w-3xl mx-auto mt-8 text-center text-sm text-muted-foreground">{data.footerNote}</p>
        )}
      </div>
    </section>
  );
}
