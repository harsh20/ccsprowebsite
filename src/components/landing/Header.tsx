import { useState } from "react";
import { Menu, X } from "lucide-react";
import { Link, useLocation } from "react-router-dom";
import { navLinks, navCtas, siteConfig } from "@/content/landing";
import { safeHref } from "@/lib/utils";
import type { LandingPageContent, HeaderData } from "@/types/wordpress";
import ccsLogo from "@/assets/ccs-logo.png";

interface HeaderProps {
  content?: LandingPageContent;
  headerData?: HeaderData;
}

export function Header({ content, headerData }: HeaderProps) {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  let location: { pathname: string } = { pathname: "/" };
  try {
    location = useLocation();
  } catch {
    // Outside router context (e.g. legacy usage) — default to "/"
  }

  const isHomepage = location.pathname === "/";

  // Resolve data: prefer headerData, then content (legacy), then static fallback
  const links = headerData
    ? headerData.primaryNav
    : content?.navLinks ?? navLinks;
  const ctaLabel = headerData
    ? isHomepage
      ? "Start Free"
      : headerData.ctaButton.label
    : content?.navCtas.primary.label ?? navCtas.primary.label;
  const ctaHref = headerData
    ? headerData.ctaButton.href
    : content?.navCtas.primary.href ?? navCtas.primary.href;
  const signInLabel = headerData
    ? headerData.secondaryLink.label
    : content?.navCtas.signIn.label ?? navCtas.signIn.label;
  const signInHref = headerData
    ? headerData.secondaryLink.href
    : content?.navCtas.signIn.href ?? navCtas.signIn.href;
  const siteName = headerData
    ? headerData.logo
    : content?.siteConfig.name ?? siteConfig.name;
  const logoUrl =
    headerData?.logoUrl && headerData.logoUrl.trim().length > 0
      ? headerData.logoUrl
      : null;

  const isActive = (href: string) => {
    if (href.startsWith("#") || href.startsWith("/#")) return false;
    return location.pathname === href;
  };

  const renderLink = (
    link: { label: string; href: string; openInNewTab?: boolean },
    className: string,
    onClick?: () => void,
  ) => {
    const active = isActive(link.href);
    const activeClass = active ? " text-foreground font-medium" : "";
    const target = link.openInNewTab ? "_blank" : undefined;
    const rel = link.openInNewTab ? "noopener noreferrer" : undefined;

    if (link.href.startsWith("/") && !link.openInNewTab) {
      return (
        <Link
          key={link.label}
          to={link.href}
          className={`${className}${activeClass}`}
          onClick={onClick}
        >
          {link.label}
        </Link>
      );
    }

    return (
      <a
        key={link.label}
        href={safeHref(link.href)}
        className={`${className}${activeClass}`}
        target={target}
        rel={rel}
        onClick={onClick}
      >
        {link.label}
      </a>
    );
  };

  return (
    <header className="fixed top-0 left-0 right-0 z-50 bg-background/85 backdrop-blur-md border-b border-border">
      <nav className="section-container flex h-16 items-center justify-between">
        <Link to="/" className="flex items-center gap-3">
          {logoUrl ? (
            <img
              src={logoUrl}
              alt={siteName}
              className="h-9 w-auto"
              loading="eager"
              fetchPriority="high"
            />
          ) : ccsLogo ? (
            <img
              src={ccsLogo}
              alt={siteName}
              className="h-9 w-auto"
              loading="eager"
              fetchPriority="high"
            />
          ) : (
            <span>{siteName}</span>
          )}
        </Link>

        <div className="hidden lg:flex items-center gap-6">
          {links.map((link) =>
            renderLink(
              link,
              "text-sm text-muted-foreground hover:text-foreground transition-colors",
            ),
          )}
        </div>

        <div className="hidden lg:flex items-center gap-3">
          <a href={safeHref(signInHref)} className="btn-ghost">
            {signInLabel}
          </a>
          <a href={safeHref(ctaHref)} className="btn-primary">
            {ctaLabel}
          </a>
        </div>

        <button
          className="lg:hidden p-2 text-muted-foreground hover:text-foreground"
          onClick={() => setMobileMenuOpen((v) => !v)}
          aria-label="Toggle menu"
        >
          {mobileMenuOpen ? (
            <X className="h-6 w-6" />
          ) : (
            <Menu className="h-6 w-6" />
          )}
        </button>
      </nav>

      {mobileMenuOpen && (
        <div className="lg:hidden border-t border-border bg-background">
          <div className="section-container py-4 space-y-2">
            {links.map((link) =>
              renderLink(
                link,
                "block py-2 text-muted-foreground hover:text-foreground",
                () => setMobileMenuOpen(false),
              ),
            )}
            <div className="flex items-center justify-between pt-3 border-t border-border mt-3">
              <a href={safeHref(signInHref)} className="btn-ghost">
                {signInLabel}
              </a>
            </div>
            <a
              href={safeHref(ctaHref)}
              className="btn-primary w-full text-center mt-2"
              onClick={() => setMobileMenuOpen(false)}
            >
              {ctaLabel}
            </a>
          </div>
        </div>
      )}
    </header>
  );
}
