import { Check, Sparkles, Clock } from "lucide-react";

const plans = [
  {
    name: "3-Month Bridge",
    price: "$60",
    period: "",
    description: "Get started with a short commitment",
    features: [
      "Full Texas packet access",
      "AI expiry extraction",
      "Email & SMS alerts",
      "3 months of access",
    ],
    cta: "Start Bridge Plan",
    highlighted: false,
    badge: null,
  },
  {
    name: "Texas Independent Tier",
    price: "$25",
    period: "/month",
    yearlyPrice: "$270/year",
    yearlyLabel: "Best value – save 10%",
    description: "Everything you need for Texas credentialing",
    features: [
      "Unlimited PDF Packet Exports",
      "AI Expiry Extraction",
      "CAQH Intake Screen",
      "Multi-channel alerts (Email, SMS, Push)",
      "Enterprise Audit Trail",
      "HIPAA-Aligned Security",
    ],
    cta: "Go Pro",
    highlighted: true,
    badge: "Most Popular",
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
    badge: "Add-on",
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
            One plan. Everything included. Built for Texas independent providers.
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
              {plan.badge && (
                <div className="absolute -top-3 left-1/2 -translate-x-1/2">
                  <div className={`flex items-center gap-1 text-xs font-medium px-3 py-1 rounded-full ${
                    plan.highlighted 
                      ? "bg-primary text-primary-foreground" 
                      : "bg-muted text-muted-foreground"
                  }`}>
                    {plan.highlighted && <Sparkles className="h-3 w-3" />}
                    {plan.badge === "Add-on" && <Clock className="h-3 w-3" />}
                    {plan.badge}
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
