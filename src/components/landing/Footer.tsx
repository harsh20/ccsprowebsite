import { Shield, MapPin, Clock } from "lucide-react";

const footerLinks = {
  legal: [
    { label: "Privacy", href: "#" },
    { label: "Terms", href: "#" },
    { label: "Security", href: "#security" },
    { label: "Refunds", href: "#refunds" },
  ],
  support: [
    { label: "Contact", href: "#" },
    { label: "Help Center", href: "#" },
    { label: "Status", href: "#" },
  ],
};

export function Footer() {
  return (
    <footer className="bg-foreground text-background">
      <div className="section-container py-12 lg:py-16">
        <div className="grid gap-8 lg:grid-cols-4">
          {/* Brand */}
          <div className="lg:col-span-2 space-y-4">
            <div className="flex items-center gap-2">
              <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-primary">
                <span className="text-sm font-bold text-primary-foreground">C</span>
              </div>
              <span className="text-lg font-bold">CCS Pro</span>
            </div>
            <p className="text-sm text-background/70 max-w-sm">
              Credentialing packets for US providers. Store credentials once, track expirations, generate structured packets.
            </p>
            
            {/* Trust badges */}
            <div className="flex flex-wrap gap-4 pt-4">
              <div className="flex items-center gap-2 text-xs text-background/60">
                <MapPin className="h-3 w-3" />
                US-only storage
              </div>
              <div className="flex items-center gap-2 text-xs text-background/60">
                <Shield className="h-3 w-3" />
                Texas live now
              </div>
              <div className="flex items-center gap-2 text-xs text-background/60">
                <Clock className="h-3 w-3" />
                More states rolling out
              </div>
            </div>
          </div>

          {/* Legal Links */}
          <div>
            <h4 className="font-semibold mb-4 text-sm">Legal</h4>
            <ul className="space-y-2">
              {footerLinks.legal.map((link) => (
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
              {footerLinks.support.map((link) => (
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
            © {new Date().getFullYear()} CCS Pro. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  );
}
