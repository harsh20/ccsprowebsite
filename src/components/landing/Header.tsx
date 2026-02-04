import { useState } from "react";
import { Menu, X } from "lucide-react";
import { navLinks, navCtas, siteConfig } from "@/content/landing";
import type { LandingPageContent } from "@/types/wordpress";

interface HeaderProps {
  content?: LandingPageContent;
}

export function Header({ content }: HeaderProps) {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const site = content?.siteConfig ?? siteConfig;
  const links = content?.navLinks ?? navLinks;
  const ctas = content?.navCtas ?? navCtas;

  return (
    <header className="sticky top-0 z-50 w-full border-b border-border/50 bg-background/80 backdrop-blur-lg">
      <nav className="container mx-auto px-4 flex h-16 items-center justify-between">
        {/* Logo */}
        <a href="#" className="flex items-center gap-2">
          <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-primary">
            <span className="text-sm font-bold text-primary-foreground">C</span>
          </div>
          <span className="text-lg font-bold text-foreground">{site.name}</span>
        </a>

        {/* Desktop Navigation */}
        <div className="hidden items-center gap-1 lg:flex">
          {links.map((link) => (
            <a key={link.label} href={link.href} className="btn-ghost">
              {link.label}
            </a>
          ))}
          <a href={ctas.signIn.href} className="btn-ghost">
            {ctas.signIn.label}
          </a>
        </div>

        {/* Desktop CTAs */}
        <div className="hidden items-center gap-3 lg:flex">
          <a href={ctas.secondary.href} className="btn-ghost text-muted-foreground hover:text-primary">
            {ctas.secondary.label}
          </a>
          <a href={ctas.primary.href} className="btn-primary">
            {ctas.primary.label}
          </a>
        </div>

        {/* Mobile Menu Button */}
        <button
          className="lg:hidden p-2 text-muted-foreground hover:text-foreground"
          onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
          aria-label="Toggle menu"
        >
          {mobileMenuOpen ? <X className="h-6 w-6" /> : <Menu className="h-6 w-6" />}
        </button>
      </nav>

      {/* Mobile Menu */}
      {mobileMenuOpen && (
        <div className="lg:hidden border-t border-border bg-background">
          <div className="container mx-auto px-4 py-4 space-y-2">
            {navLinks.map((link) => (
              <a
                key={link.label}
                href={link.href}
                className="block py-2 text-muted-foreground hover:text-foreground"
                onClick={() => setMobileMenuOpen(false)}
              >
                {link.label}
              </a>
            ))}
            <a
              href={ctas.signIn.href}
              className="block py-2 text-muted-foreground hover:text-foreground"
            >
              {ctas.signIn.label}
            </a>
            <div className="flex flex-col gap-2 pt-4 border-t border-border">
              <a href={ctas.secondary.href} className="btn-secondary text-center">
                {ctas.secondary.label}
              </a>
              <a href={ctas.primary.href} className="btn-primary text-center">
                {ctas.primary.label}
              </a>
            </div>
          </div>
        </div>
      )}
    </header>
  );
}
