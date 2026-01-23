import { Shield, FileCheck, UserCheck } from "lucide-react";
import { verificationContent } from "@/content/landing";
import { VerificationBadge } from "./shared/Cards";

const iconMap = {
  Shield,
  FileCheck,
  UserCheck,
};

export function LogoStrip() {
  return (
    <section className="border-y border-border/50 bg-background">
      <div className="container mx-auto px-4 py-10">
        <p className="text-center text-sm text-muted-foreground mb-8">
          {verificationContent.headline}
        </p>
        <div className="flex flex-wrap items-center justify-center gap-8 lg:gap-16">
          {verificationContent.items.map((item, index) => {
            const Icon = iconMap[item.icon as keyof typeof iconMap];
            return <VerificationBadge key={index} icon={Icon} label={item.label} />;
          })}
        </div>
      </div>
    </section>
  );
}
