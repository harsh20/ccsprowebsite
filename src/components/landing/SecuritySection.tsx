import { Shield, Lock, Users, Activity, Database, ArrowRight } from "lucide-react";

const securityFeatures = [
  { icon: Shield, text: "Documents stored in the United States" },
  { icon: Lock, text: "Encryption in transit and at rest" },
  { icon: Users, text: "Role-based access controls" },
  { icon: Activity, text: "Audit trail and activity logs" },
  { icon: Database, text: "Backups and retention controls" },
];

export function SecuritySection() {
  return (
    <section id="security" className="section-spacing bg-background">
      <div className="section-container">
        <div className="card-elevated p-8 sm:p-12 lg:p-16 bg-gradient-to-br from-primary/5 to-transparent">
          <div className="grid gap-8 lg:grid-cols-2 items-center">
            {/* Content */}
            <div className="space-y-6">
              <div className="space-y-4">
                <div className="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-1.5 text-sm font-medium text-primary">
                  <Shield className="h-4 w-4" />
                  Security & Privacy
                </div>
                <h2 className="text-3xl sm:text-4xl font-bold text-foreground">
                  Your data, protected
                </h2>
                <p className="text-lg text-muted-foreground max-w-md">
                  We understand you're trusting us with sensitive documents. Here's how we protect them.
                </p>
              </div>

              <div className="space-y-4">
                {securityFeatures.map((feature, index) => (
                  <div key={index} className="flex items-center gap-4">
                    <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                      <feature.icon className="h-5 w-5 text-primary" />
                    </div>
                    <span className="text-foreground font-medium">{feature.text}</span>
                  </div>
                ))}
              </div>

              <a
                href="#"
                className="inline-flex items-center gap-2 text-primary font-medium hover:underline"
              >
                View security details
                <ArrowRight className="h-4 w-4" />
              </a>
            </div>

            {/* Visual */}
            <div className="hidden lg:flex justify-center">
              <div className="relative">
                <div className="h-64 w-64 rounded-full bg-primary/5 flex items-center justify-center">
                  <div className="h-48 w-48 rounded-full bg-primary/10 flex items-center justify-center">
                    <div className="h-32 w-32 rounded-full bg-primary/15 flex items-center justify-center">
                      <Shield className="h-16 w-16 text-primary" />
                    </div>
                  </div>
                </div>
                {/* Floating badges */}
                <div className="absolute -top-2 -right-4 bg-background rounded-lg shadow-lg px-3 py-2 text-xs font-medium">
                  US Storage Only
                </div>
                <div className="absolute bottom-4 -left-8 bg-background rounded-lg shadow-lg px-3 py-2 text-xs font-medium">
                  256-bit Encryption
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
