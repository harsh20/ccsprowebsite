import { Vault, Sparkles, FileText, Bell, History, UserCheck } from "lucide-react";

const features = [
  {
    icon: Vault,
    title: "Credential vault with expiry tracking",
    description: "Store all licenses, certifications, and documents with automatic expiration alerts powered by AI extraction.",
    link: "#",
  },
  {
    icon: Sparkles,
    title: "AI-powered data capture",
    description: "Our AI/OCR pipeline automatically extracts issue and expiry dates from uploaded documents.",
    link: "#",
  },
  {
    icon: FileText,
    title: "Texas packet builder",
    description: "Generate professionally formatted, submission-ready PDF packets with cover pages and organized sections.",
    link: "#",
  },
  {
    icon: UserCheck,
    title: "CAQH Done-For-You",
    description: "Secure intake for CAQH ProView credentials with encryption at rest. We handle the updates.",
    link: "#",
  },
  {
    icon: Bell,
    title: "Multi-Channel Alerts",
    description: "Expiry reminders delivered via Email, SMS, and Push notifications. Never miss a deadline.",
    link: "#",
  },
  {
    icon: History,
    title: "Enterprise Audit Trail",
    description: "Immutable, append-only logs of every field change and document access for forensic integrity.",
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
            Everything you need to manage credentials and generate submission-ready packets
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
