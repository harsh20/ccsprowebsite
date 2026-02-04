import { footerContent } from "@/content/landing";
import { getLandingIcon } from "@/lib/landing-icons";
import type { LandingPageContent } from "@/types/wordpress";

interface FooterProps {
  content?: LandingPageContent;
}

export function Footer({ content }: FooterProps) {
  const data = content?.footerContent ?? footerContent;
  return (
    <footer className="bg-foreground text-background">
      <div className="container mx-auto px-4 py-12 lg:py-16">
        <div className="grid gap-8 lg:grid-cols-4">
          {/* Brand */}
          <div className="lg:col-span-2 space-y-4">
            <div className="flex items-center gap-2">
              <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-primary">
                <span className="text-sm font-bold text-primary-foreground">C</span>
              </div>
              <span className="text-lg font-bold">{data.brand.name}</span>
            </div>
            <p className="text-sm text-background/70 max-w-sm">
              {data.brand.description}
            </p>

            {/* Trust badges */}
            <div className="flex flex-wrap gap-4 pt-4">
              {data.trustBadges.map((badge, index) => {
                const Icon = getLandingIcon(badge.icon);
                return (
                  <div key={index} className="flex items-center gap-2 text-xs text-background/60">
                    <Icon className="h-3 w-3" />
                    {badge.text}
                  </div>
                );
              })}
            </div>
          </div>

          {/* Legal Links */}
          <div>
            <h4 className="font-semibold mb-4 text-sm">Legal</h4>
            <ul className="space-y-2">
              {data.links.legal.map((link) => (
                <li key={link.label}>
                  <a
                    href={link.href}
                    className="text-sm text-background/70 hover:text-background transition-colors"
                  >
                    {link.label}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          {/* Support Links */}
          <div>
            <h4 className="font-semibold mb-4 text-sm">Support</h4>
            <ul className="space-y-2">
              {data.links.support.map((link) => (
                <li key={link.label}>
                  <a
                    href={link.href}
                    className="text-sm text-background/70 hover:text-background transition-colors"
                  >
                    {link.label}
                  </a>
                </li>
              ))}
            </ul>
          </div>
        </div>

        {/* Bottom */}
        <div className="border-t border-background/10 mt-12 pt-8">
          <p className="text-sm text-background/50 text-center">
            © {new Date().getFullYear()} {data.copyright}
          </p>
        </div>
      </div>
    </footer>
  );
}
