import { ArrowRight, CheckCircle, FileText, Clock, Shield } from "lucide-react";

export function HeroSection() {
  return (
    <section className="section-tinted overflow-hidden">
      <div className="section-container py-12 sm:py-16 lg:py-20">
        <div className="grid gap-12 lg:grid-cols-2 lg:gap-16 items-center">
          {/* Left Column - Copy */}
          <div className="space-y-8">
            <div className="space-y-4">
              <h1 className="text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl text-balance">
                Credentialing packets for US providers,{" "}
                <span className="text-primary">ready to submit</span>
              </h1>
              <p className="text-lg text-muted-foreground sm:text-xl max-w-xl">
                Store credentials once, track expirations, generate a structured Texas packet as PDF. Pro includes signed PDF via e-signature. Add CAQH Concierge for done-for-you updates.
              </p>
            </div>

            {/* CTAs */}
            <div className="flex flex-wrap gap-4 items-center">
              <a href="#pricing" className="btn-primary text-base px-8 py-4">
                Start free
                <ArrowRight className="h-4 w-4" />
              </a>
              <a href="#sample" className="btn-secondary text-base">
                View sample Texas packet
              </a>
            </div>
            <a href="#demo" className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-primary transition-colors">
              Book a demo →
            </a>

            {/* Trust Line */}
            <div className="flex flex-wrap gap-4 text-sm text-muted-foreground pt-4 border-t border-border/50">
              <span className="flex items-center gap-2">
                <Shield className="h-4 w-4 text-primary" />
                All documents stored in the US
              </span>
              <span className="flex items-center gap-2">
                <CheckCircle className="h-4 w-4 text-primary" />
                Texas live now
              </span>
              <span className="flex items-center gap-2">
                <Clock className="h-4 w-4 text-primary" />
                More states rolling out
              </span>
            </div>
          </div>

          {/* Right Column - Dashboard Mock */}
          <div className="relative">
            <div className="card-elevated p-1 bg-gradient-to-br from-primary/5 to-primary/10">
              <div className="rounded-xl bg-background p-6 space-y-5">
                {/* Header */}
                <div className="flex items-center justify-between">
                  <div className="flex items-center gap-3">
                    <div className="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                      <FileText className="h-5 w-5 text-primary" />
                    </div>
                    <div>
                      <p className="font-semibold text-foreground">Credential Packet</p>
                      <p className="text-sm text-muted-foreground">Texas Requirements</p>
                    </div>
                  </div>
                  <div className="badge-pill">92% Complete</div>
                </div>

                {/* State & NPI */}
                <div className="grid grid-cols-2 gap-4">
                  <div className="space-y-1.5">
                    <label className="text-xs font-medium text-muted-foreground">State</label>
                    <div className="flex items-center gap-2 rounded-lg border border-border bg-muted/30 px-3 py-2">
                      <span className="text-sm font-medium">Texas</span>
                    </div>
                  </div>
                  <div className="space-y-1.5">
                    <label className="text-xs font-medium text-muted-foreground">NPI Number</label>
                    <div className="flex items-center gap-2 rounded-lg border border-border bg-muted/30 px-3 py-2">
                      <span className="text-sm font-medium">1234567890</span>
                    </div>
                  </div>
                </div>

                {/* Expiry Tracking */}
                <div className="space-y-3">
                  <p className="text-xs font-medium text-muted-foreground uppercase tracking-wide">Expiration Tracking</p>
                  <div className="space-y-2">
                    <div className="flex items-center justify-between rounded-lg border border-border bg-background px-3 py-2">
                      <span className="text-sm">TX Medical License</span>
                      <span className="text-xs font-medium text-primary">Expires in 45 days</span>
                    </div>
                    <div className="flex items-center justify-between rounded-lg border border-border bg-background px-3 py-2">
                      <span className="text-sm">Malpractice COI</span>
                      <span className="text-xs font-medium text-muted-foreground">Expires in 120 days</span>
                    </div>
                    <div className="flex items-center justify-between rounded-lg border border-orange-200 bg-orange-50 px-3 py-2">
                      <span className="text-sm">DEA Certificate</span>
                      <span className="text-xs font-medium text-orange-600">Optional</span>
                    </div>
                  </div>
                </div>

                {/* Missing Items */}
                <div className="rounded-lg border border-border bg-muted/30 p-3 space-y-2">
                  <p className="text-xs font-medium text-muted-foreground">Missing Items (2)</p>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li className="flex items-center gap-2">
                      <span className="h-1.5 w-1.5 rounded-full bg-orange-500" />
                      Work history verification
                    </li>
                    <li className="flex items-center gap-2">
                      <span className="h-1.5 w-1.5 rounded-full bg-orange-500" />
                      Board certification upload
                    </li>
                  </ul>
                </div>

                {/* Action Buttons */}
                <div className="grid grid-cols-2 gap-3">
                  <button className="btn-secondary text-sm py-2.5">
                    Generate Packet PDF
                  </button>
                  <button className="btn-primary text-sm py-2.5">
                    Generate Signed PDF
                  </button>
                </div>
              </div>
            </div>

            {/* Decorative Elements */}
            <div className="absolute -top-4 -right-4 h-24 w-24 rounded-full bg-primary/10 blur-3xl" />
            <div className="absolute -bottom-8 -left-8 h-32 w-32 rounded-full bg-primary/5 blur-3xl" />
          </div>
        </div>
      </div>
    </section>
  );
}
