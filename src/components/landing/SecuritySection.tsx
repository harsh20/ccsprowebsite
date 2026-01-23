import { Shield, Lock, Users, Activity, Database, ArrowRight, KeyRound, Layers } from "lucide-react";
import { securityContent } from "@/content/landing";
import { SecurityFeature } from "./shared/Cards";

const iconMap = {
  Shield,
  Lock,
  Users,
  Activity,
  Database,
  KeyRound,
  Layers,
};

export function SecuritySection() {
  return (
    <section id="security" className="py-16 sm:py-20 lg:py-24 bg-background">
      <div className="container mx-auto px-4">
        <div className="card-elevated p-8 sm:p-12 lg:p-16 bg-gradient-to-br from-primary/5 to-transparent">
          <div className="grid gap-8 lg:grid-cols-2 items-center">
            {/* Content */}
            <div className="space-y-6">
              <div className="space-y-4">
                <div className="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-1.5 text-sm font-medium text-primary">
                  <Shield className="h-4 w-4" />
                  {securityContent.badge}
                </div>
                <h2 className="text-3xl sm:text-4xl font-bold text-foreground">
                  {securityContent.sectionTitle}
                </h2>
                <p className="text-lg text-muted-foreground max-w-md">
                  {securityContent.sectionSubtitle}
                </p>
              </div>

              <div className="space-y-4">
                {securityContent.features.map((feature, index) => {
                  const Icon = iconMap[feature.icon as keyof typeof iconMap];
                  return <SecurityFeature key={index} icon={Icon} text={feature.text} />;
                })}
              </div>

              <a
                href={securityContent.cta.href}
                className="inline-flex items-center gap-2 text-primary font-medium hover:underline"
              >
                {securityContent.cta.label}
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
                {securityContent.floatingBadges.map((badge, index) => (
                  <div
                    key={index}
                    className={`absolute bg-background rounded-lg shadow-lg px-3 py-2 text-xs font-medium ${
                      index === 0
                        ? "-top-2 -right-4"
                        : index === 1
                        ? "bottom-4 -left-8"
                        : "top-1/2 -right-12"
                    }`}
                  >
                    {badge}
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
