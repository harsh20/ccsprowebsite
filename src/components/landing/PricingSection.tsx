import { Check, Sparkles } from "lucide-react";

const plans = [
  {
    name: "Free",
    price: "$0",
    period: "",
    description: "Get started with the basics",
    features: [
      "Credential vault storage",
      "Expiration tracking",
      "Draft packet preview",
      "Email reminders",
    ],
    cta: "Start free",
    highlighted: false,
  },
  {
    name: "Pro",
    price: "$25",
    period: "/month",
    yearlyPrice: "$216/year",
    yearlyLabel: "Best value – save 28%",
    description: "Full packet generation with e-signature",
    features: [
      "Everything in Free",
      "Texas packet builder",
      "Signed PDF with e-signature",
      "Complete audit trail",
      "Priority support",
    ],
    cta: "Go Pro",
    highlighted: true,
  },
  {
    name: "CAQH Concierge",
    price: "$99",
    period: "/year",
    description: "Done-for-you CAQH maintenance",
    features: [
      "Profile maintenance",
      "Document uploads",
      "Update coordination",
      "Choice of consent mode",
      "Activity notifications",
    ],
    cta: "Add Concierge",
    highlighted: false,
    isAddon: true,
  },
];

export function PricingSection() {
  return (
    <section id="pricing" className="section-spacing bg-background">
      <div className="section-container">
        <div className="text-center max-w-2xl mx-auto mb-12">
          <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">
            Simple, transparent pricing
          </h2>
          <p className="text-lg text-muted-foreground">
            Start free, upgrade when you need signed packets
          </p>
        </div>

        <div className="grid gap-6 lg:grid-cols-3 max-w-5xl mx-auto">
          {plans.map((plan, index) => (
            <div
              key={index}
              className={`card-elevated p-6 sm:p-8 relative ${
                plan.highlighted
                  ? "ring-2 ring-primary shadow-lg"
                  : ""
              }`}
            >
              {plan.highlighted && (
                <div className="absolute -top-3 left-1/2 -translate-x-1/2">
                  <div className="flex items-center gap-1 bg-primary text-primary-foreground text-xs font-medium px-3 py-1 rounded-full">
                    <Sparkles className="h-3 w-3" />
                    Most Popular
                  </div>
                </div>
              )}
              {plan.isAddon && (
                <div className="absolute -top-3 left-1/2 -translate-x-1/2">
                  <div className="bg-muted text-muted-foreground text-xs font-medium px-3 py-1 rounded-full">
                    Add-on
                  </div>
                </div>
              )}

              <div className="space-y-4 mb-6">
                <h3 className="text-xl font-bold text-foreground">{plan.name}</h3>
                <div className="flex items-baseline gap-1">
                  <span className="text-4xl font-bold text-foreground">{plan.price}</span>
                  <span className="text-muted-foreground">{plan.period}</span>
                </div>
                {plan.yearlyPrice && (
                  <div className="space-y-1">
                    <p className="text-sm text-muted-foreground">{plan.yearlyPrice}</p>
                    <p className="text-xs font-medium text-primary">{plan.yearlyLabel}</p>
                  </div>
                )}
                <p className="text-sm text-muted-foreground">{plan.description}</p>
              </div>

              <ul className="space-y-3 mb-8">
                {plan.features.map((feature, fIndex) => (
                  <li key={fIndex} className="flex items-start gap-3 text-sm">
                    <Check className="h-4 w-4 text-primary flex-shrink-0 mt-0.5" />
                    <span className="text-foreground">{feature}</span>
                  </li>
                ))}
              </ul>

              <button
                className={`w-full ${
                  plan.highlighted ? "btn-primary" : "btn-secondary"
                }`}
              >
                {plan.cta}
              </button>
            </div>
          ))}
        </div>

        {/* Additional Info */}
        <div className="max-w-2xl mx-auto mt-12 text-center space-y-4">
          <p className="text-sm text-muted-foreground">
            <span className="font-medium text-foreground">Additional update requests:</span> $29 each
          </p>
          <div className="p-4 bg-muted/50 rounded-xl">
            <p className="text-sm text-muted-foreground">
              <span className="font-medium text-foreground">14-day money-back guarantee</span> for Pro, only if no signed packet has been generated. Concierge is non-refundable once work starts.
            </p>
            <a href="#refunds" className="text-sm text-primary font-medium hover:underline mt-2 inline-block">
              View refund policy →
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}
