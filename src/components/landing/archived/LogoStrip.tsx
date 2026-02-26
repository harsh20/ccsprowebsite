import { verificationContent } from "@/content/landing";
import { getLandingIcon } from "@/lib/landing-icons";
import { VerificationBadge } from "../shared/Cards";
import type { LandingPageContent } from "@/types/wordpress";

interface LogoStripProps {
  content?: LandingPageContent;
}

export function LogoStrip({ content }: LogoStripProps) {
  const verification = content?.verificationContent ?? verificationContent;
  return (
    <section className="border-y border-border/50 bg-background">
      <div className="container mx-auto px-4 py-10">
        <p className="text-center text-sm text-muted-foreground mb-8">
          {verification.headline}
        </p>
        <div className="flex flex-wrap items-center justify-center gap-8 lg:gap-16">
          {verification.items.map((item, index) => {
            const Icon = getLandingIcon(item.icon);
            return <VerificationBadge key={index} icon={Icon} label={item.label} />;
          })}
        </div>
      </div>
    </section>
  );
}
