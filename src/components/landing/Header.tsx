import { useState } from "react";
import { Menu, X } from "lucide-react";
import { navLinks, navCtas, siteConfig } from "@/content/landing";

export function Header() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  return (
    <header className="sticky top-0 z-50 w-full border-b border-border/50 bg-background/80 backdrop-blur-lg">
      <nav className="container mx-auto px-4 flex h-16 items-center justify-between">
        {/* Logo */}
        <a href="#" className="flex items-center gap-2">
          <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-primary">
            <span className="text-sm font-bold text-primary-foreground">C</span>
          </div>
          <span className="text-lg font-bold text-foreground">{siteConfig.name}</span>
        </a>

        {/* Desktop Navigation */}
        <div className="hidden items-center gap-1 lg:flex">
          {navLinks.map((link) => (
            <a key={link.label} href={link.href} className="btn-ghost">
              {link.label}
            </a>
          ))}
          <a href={navCtas.signIn.href} className="btn-ghost">
            {navCtas.signIn.label}
          </a>
        </div>

        {/* Desktop CTAs */}
        <div className="hidden items-center gap-3 lg:flex">
          <a href={navCtas.secondary.href} className="btn-ghost text-muted-foreground hover:text-primary">
            {navCtas.secondary.label}
          </a>
          <a href={navCtas.primary.href} className="btn-primary">
            {navCtas.primary.label}
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
              href={navCtas.signIn.href}
              className="block py-2 text-muted-foreground hover:text-foreground"
            >
              {navCtas.signIn.label}
            </a>
            <div className="flex flex-col gap-2 pt-4 border-t border-border">
              <a href={navCtas.secondary.href} className="btn-secondary text-center">
                {navCtas.secondary.label}
              </a>
              <a href={navCtas.primary.href} className="btn-primary text-center">
                {navCtas.primary.label}
              </a>
            </div>
          </div>
        </div>
      )}
    </header>
  );
}
