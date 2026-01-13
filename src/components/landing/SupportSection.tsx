import { MessageSquare, Mail, Clock, ExternalLink } from "lucide-react";

const supportFeatures = [
  { icon: MessageSquare, text: "Chat support" },
  { icon: Mail, text: "Email support" },
  { icon: Clock, text: "24/7 availability" },
];

export function SupportSection() {
  return (
    <section className="section-spacing section-tinted">
      <div className="section-container">
        <div className="max-w-3xl mx-auto text-center space-y-8">
          <div className="space-y-4">
            <h2 className="text-3xl sm:text-4xl font-bold text-foreground">
              We're here when you need us
            </h2>
            <p className="text-lg text-muted-foreground">
              15-minute response target for critical issues like login failures and packet generation.
            </p>
          </div>

          <div className="flex flex-wrap justify-center gap-6">
            {supportFeatures.map((feature, index) => (
              <div
                key={index}
                className="flex items-center gap-3 px-6 py-4 bg-background rounded-xl shadow-sm"
              >
                <feature.icon className="h-5 w-5 text-primary" />
                <span className="font-medium text-foreground">{feature.text}</span>
              </div>
            ))}
          </div>

          <div className="flex flex-wrap justify-center gap-4">
            <a
              href="#"
              className="inline-flex items-center gap-2 text-primary font-medium hover:underline"
            >
              Help Center
              <ExternalLink className="h-4 w-4" />
            </a>
            <span className="text-muted-foreground">•</span>
            <a
              href="#"
              className="inline-flex items-center gap-2 text-primary font-medium hover:underline"
            >
              Status Page
              <ExternalLink className="h-4 w-4" />
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}
