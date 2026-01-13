import { Vault, Wand2, FileText, PenTool, Activity, Headphones } from "lucide-react";

const features = [
  {
    icon: Vault,
    title: "Credential vault with expiry tracking",
    description: "Store all licenses, certifications, and documents with automatic expiration alerts.",
    link: "#",
  },
  {
    icon: Wand2,
    title: "Smart data capture",
    description: "Enter once, reuse everywhere. No more re-typing the same information.",
    link: "#",
  },
  {
    icon: FileText,
    title: "Texas packet builder",
    description: "Structured PDF output matching payer and facility requirements.",
    link: "#",
  },
  {
    icon: PenTool,
    title: "Signed PDF export",
    description: "Pro feature with e-signature and complete audit trail.",
    link: "#",
  },
  {
    icon: Activity,
    title: "Activity log and notifications",
    description: "Track every change with email notifications for complete transparency.",
    link: "#",
  },
  {
    icon: Headphones,
    title: "Concierge option",
    description: "Done-for-you CAQH updates with your choice of consent model.",
    link: "#",
  },
];

export function FeaturesGrid() {
  return (
    <section id="product" className="section-spacing section-tinted">
      <div className="section-container">
        <div className="text-center max-w-2xl mx-auto mb-12">
          <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">
            What's included
          </h2>
          <p className="text-lg text-muted-foreground">
            Everything you need to manage credentials and generate compliant packets
          </p>
        </div>

        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {features.map((feature, index) => (
            <div
              key={index}
              className="card-elevated card-hover p-6 space-y-4"
            >
              <div className="icon-box">
                <feature.icon className="h-5 w-5" />
              </div>
              <h3 className="font-semibold text-foreground">{feature.title}</h3>
              <p className="text-sm text-muted-foreground">{feature.description}</p>
              <a
                href={feature.link}
                className="inline-flex items-center text-sm font-medium text-primary hover:underline"
              >
                Learn more →
              </a>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
