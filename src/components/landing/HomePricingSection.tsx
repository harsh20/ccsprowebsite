import { CheckCircle, ArrowRight } from "lucide-react";
import { Link } from "react-router-dom";
import type { HomePricingCardData } from "@/types/wordpress";

interface HomePricingSectionProps {
  provider: HomePricingCardData;
  group: HomePricingCardData;
}

function PricingCard({
  card,
  note,
}: {
  card: HomePricingCardData;
  note?: string | string[];
}) {
  return (
    <div
      className={`card-elevated p-6 sm:p-8 relative flex flex-col h-full ${
        card.highlighted ? "ring-2 ring-primary shadow-lg" : ""
      }`}
    >
      <div className="space-y-4 mb-6">
        <span
          className={`inline-block text-xs font-medium px-3 py-1 rounded-full ${
            card.highlighted
              ? "bg-primary text-primary-foreground"
              : "bg-muted text-muted-foreground"
          }`}
        >
          {card.badge}
        </span>
        <div className="space-y-1">
          <p className="text-3xl font-bold text-foreground">{card.price}</p>
          <p className="text-sm text-muted-foreground">{card.subtext}</p>
        </div>
      </div>

      <ul className="space-y-3 mb-8 flex-grow">
        {card.bullets.map((bullet, i) => (
          <li key={i} className="flex items-start gap-3 text-sm">
            <CheckCircle className="h-4 w-4 text-primary flex-shrink-0 mt-0.5" />
            <span className="text-foreground">{bullet}</span>
          </li>
        ))}
      </ul>

      {typeof note === "string" && (
        <p className="text-sm text-slate-500 mt-4">{note}</p>
      )}

      {Array.isArray(note) && (
        <div className="text-sm text-slate-500 mt-4 space-y-1">
          {note.map((line, index) => (
            <p key={index}>{line}</p>
          ))}
        </div>
      )}

      <Link
        to={card.cta.href}
        className={`w-full text-center mt-auto inline-flex items-center justify-center gap-2 ${
          card.highlighted ? "btn-primary" : "btn-secondary"
        }`}
      >
        {card.cta.label}
        <ArrowRight className="h-4 w-4" />
      </Link>

      <p className="text-xs text-muted-foreground text-center mt-3">
        {card.finePrint}
      </p>

      {card.secondaryLink && (
        <Link
          to={card.secondaryLink.href}
          className="text-sm text-primary font-medium hover:underline text-center mt-2"
        >
          {card.secondaryLink.label}
        </Link>
      )}
    </div>
  );
}

export function HomePricingSection({ provider, group }: HomePricingSectionProps) {
  return (
    <section id="pricing" className="py-16 sm:py-20 lg:py-24 bg-muted/30">
      <div className="container mx-auto px-4">
        <div className="grid gap-6 md:grid-cols-2 max-w-4xl mx-auto">
          <PricingCard
            card={provider}
            note="Most providers pay under $600 total in year one."
          />
          <PricingCard
            card={group}
            note={[
              "One seat = one provider in your roster.",
              "All payer workflows included, no packet fees.",
              "Need more than 50 seats? Let's talk.",
            ]}
          />
        </div>
      </div>
    </section>
  );
}
