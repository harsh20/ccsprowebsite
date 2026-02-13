import { useState } from "react";
import { Menu, X, Moon, Sun } from "lucide-react";
import { navLinks, navCtas, siteConfig } from "@/content/landing";
import type { LandingPageContent } from "@/types/wordpress";
import ccsLogo from "@/assets/ccs-logo.png";

interface HeaderProps {
  content?: LandingPageContent;
}

export function Header({ content }: HeaderProps) {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [isDark, setIsDark] = useState(() => document.documentElement.classList.contains("dark"));

  const site = content?.siteConfig ?? siteConfig;
  const links = content?.navLinks ?? navLinks;
  const ctas = content?.navCtas ?? navCtas;

  const toggleDarkMode = () => {
    document.documentElement.classList.toggle("dark");
    setIsDark((prev) => !prev);
  };

  return (
    <header className="fixed top-0 left-0 right-0 z-50 bg-background/85 backdrop-blur-md border-b border-border">
      <nav className="section-container flex h-16 items-center justify-between">
        <a href="#" className="flex items-center gap-3">
          <img src={ccsLogo} alt={site.name} className="h-9 w-auto" />
          <span className="hidden sm:inline text-sm font-semibold text-muted-foreground">{site.tagline}</span>
        </a>

        <div className="hidden lg:flex items-center gap-6">
          {links.map((link) => (
            <a key={link.label} href={link.href} className="text-sm text-muted-foreground hover:text-foreground transition-colors">
              {link.label}
            </a>
          ))}
        </div>

        <div className="hidden lg:flex items-center gap-3">
          <button type="button" className="btn-ghost" onClick={toggleDarkMode} aria-label="Toggle dark mode">
            {isDark ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
          </button>
          <a href={ctas.signIn.href} className="btn-ghost">{ctas.signIn.label}</a>
          <a href={ctas.primary.href} className="btn-primary">{ctas.primary.label}</a>
        </div>

        <button
          className="lg:hidden p-2 text-muted-foreground hover:text-foreground"
          onClick={() => setMobileMenuOpen((v) => !v)}
          aria-label="Toggle menu"
        >
          {mobileMenuOpen ? <X className="h-6 w-6" /> : <Menu className="h-6 w-6" />}
        </button>
      </nav>

      {mobileMenuOpen && (
        <div className="lg:hidden border-t border-border bg-background">
          <div className="section-container py-4 space-y-2">
            {links.map((link) => (
              <a
                key={link.label}
                href={link.href}
                className="block py-2 text-muted-foreground hover:text-foreground"
                onClick={() => setMobileMenuOpen(false)}
              >
                {link.label}
              </a>
            ))}
            <div className="flex items-center justify-between pt-3 border-t border-border mt-3">
              <a href={ctas.signIn.href} className="btn-ghost">{ctas.signIn.label}</a>
              <button type="button" className="btn-ghost" onClick={toggleDarkMode} aria-label="Toggle dark mode">
                {isDark ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
              </button>
            </div>
            <a href={ctas.primary.href} className="btn-primary w-full text-center mt-2" onClick={() => setMobileMenuOpen(false)}>
              {ctas.primary.label}
            </a>
          </div>
        </div>
      )}
    </header>
  );
}
