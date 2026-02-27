import { Link } from "react-router-dom";
import { footerContent } from "@/content/landing";
import { getLandingIcon } from "@/lib/landing-icons";
import { safeHref } from "@/lib/utils";
import type { LandingPageContent, FooterData } from "@/types/wordpress";
import ccsLogo from "@/assets/ccs-logo.png";

interface FooterProps {
  content?: LandingPageContent;
  footerData?: FooterData;
}

export function Footer({ content, footerData }: FooterProps) {
  // New footerData path takes priority; legacy content path as fallback
  if (footerData) {
    return <NewFooter data={footerData} />;
  }

  const data = content?.footerContent ?? footerContent;

  return (
    <footer className="py-12 px-4 border-t border-border bg-background">
      <div className="section-container max-w-6xl">
        <div className="grid gap-8 md:grid-cols-3">
          <div className="space-y-3 md:col-span-1">
            <img src={ccsLogo} alt={data.brand.name} className="h-9 w-auto" />
            <p className="text-sm text-muted-foreground">{data.brand.description}</p>
            <div className="flex flex-wrap gap-3 pt-1">
              {data.trustBadges.map((badge, index) => {
                const Icon = getLandingIcon(badge.icon);
                return (
                  <span key={index} className="inline-flex items-center gap-1.5 text-xs text-muted-foreground">
                    <Icon className="h-3.5 w-3.5 text-primary" />
                    {badge.text}
                  </span>
                );
              })}
            </div>
          </div>

          <div>
            <h4 className="font-semibold mb-3 text-sm text-foreground">Legal</h4>
            <ul className="space-y-2">
              {data.links.legal.map((link) => (
                <li key={link.label}>
                  <a href={safeHref(link.href)} className="text-sm text-muted-foreground hover:text-foreground transition-colors">
                    {link.label}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h4 className="font-semibold mb-3 text-sm text-foreground">Support</h4>
            <ul className="space-y-2">
              {data.links.support.map((link) => (
                <li key={link.label}>
                  <a href={safeHref(link.href)} className="text-sm text-muted-foreground hover:text-foreground transition-colors">
                    {link.label}
                  </a>
                </li>
              ))}
            </ul>
          </div>
        </div>

        <div className="border-t border-border mt-10 pt-6 text-center">
          <p className="text-sm text-muted-foreground">{data.copyright}</p>
        </div>
      </div>
    </footer>
  );
}

function NewFooter({ data }: { data: FooterData }) {
  const renderFooterLink = (link: { label: string; href: string; openInNewTab?: boolean }) => {
    if (link.href.startsWith("/") && !link.openInNewTab) {
      return (
        <li key={link.label}>
          <Link
            to={link.href}
            className="text-sm text-muted-foreground hover:text-foreground transition-colors"
          >
            {link.label}
          </Link>
        </li>
      );
    }
    return (
      <li key={link.label}>
        <a
          href={safeHref(link.href)}
          className="text-sm text-muted-foreground hover:text-foreground transition-colors"
          target={link.openInNewTab ? "_blank" : undefined}
          rel={link.openInNewTab ? "noopener noreferrer" : undefined}
        >
          {link.label}
        </a>
      </li>
    );
  };

  return (
    <footer className="py-12 px-4 border-t border-border bg-background">
      <div className="section-container max-w-6xl">
        <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-5">
          {/* Brand column — spans 2 on lg */}
          <div className="space-y-3 lg:col-span-2">
            <Link to="/" className="inline-block">
              <img src={ccsLogo} alt={data.brand.name} className="h-9 w-auto" />
            </Link>
            <p className="text-sm text-muted-foreground">{data.brand.tagline}</p>
            <div className="flex flex-wrap gap-3 pt-1">
              {data.trustBadges.map((badge, index) => {
                const Icon = getLandingIcon(badge.icon);
                return (
                  <span
                    key={index}
                    className="inline-flex items-center gap-1.5 text-xs text-muted-foreground"
                  >
                    <Icon className="h-3.5 w-3.5 text-primary" />
                    {badge.text}
                  </span>
                );
              })}
            </div>
          </div>

          {/* Menu columns */}
          {data.columns.map((col) => (
            <div key={col.title}>
              <h4 className="font-semibold mb-3 text-sm text-foreground">
                {col.title}
              </h4>
              <ul className="space-y-2">
                {col.links.map(renderFooterLink)}
              </ul>
            </div>
          ))}
        </div>

        <div className="border-t border-border mt-10 pt-6 text-center">
          <p className="text-sm text-muted-foreground">{data.copyright}</p>
        </div>
      </div>
    </footer>
  );
}
