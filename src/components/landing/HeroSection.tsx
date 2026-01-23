import { ArrowRight, CheckCircle, FileText, Clock, Shield, Sparkles } from "lucide-react";

export function HeroSection() {
  return (
    <section className="section-tinted overflow-hidden">
      <div className="section-container py-12 sm:py-16 lg:py-20">
        <div className="grid gap-12 lg:grid-cols-2 lg:gap-16 items-center">
          {/* Left Column - Copy */}
          <div className="space-y-8">
            <div className="space-y-4">
              <h1 className="text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl text-balance">
                Automated Credentialing Packets for{" "}
                <span className="text-primary">Texas Independent Providers</span>
              </h1>
              <p className="text-lg text-muted-foreground sm:text-xl max-w-xl">
                Complete your Texas-specific profile, track expiries with AI, and generate submission-ready packets in minutes. All for $25/month.
              </p>
            </div>

            {/* CTAs */}
            <div className="flex flex-wrap gap-4 items-center">
              <a href="#pricing" className="btn-primary text-base px-8 py-4">
                Start My Texas Profile for Free
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
                <Sparkles className="h-4 w-4 text-primary" />
                AI-powered extraction
              </span>
              <span className="flex items-center gap-2">
                <CheckCircle className="h-4 w-4 text-primary" />
                Texas live now
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

                {/* Readiness States */}
                <div className="space-y-3">
                  <p className="text-xs font-medium text-muted-foreground uppercase tracking-wide">Readiness States</p>
                  <div className="flex flex-wrap gap-2">
                    <span className="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-red-100 text-red-700">
                      <span className="h-1.5 w-1.5 rounded-full bg-red-500" />
                      Missing
                    </span>
                    <span className="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-blue-100 text-blue-700">
                      <span className="h-1.5 w-1.5 rounded-full bg-blue-500" />
                      Uploaded
                    </span>
                    <span className="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-orange-100 text-orange-700">
                      <span className="h-1.5 w-1.5 rounded-full bg-orange-500" />
                      Expiring Soon
                    </span>
                    <span className="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-700">
                      <span className="h-1.5 w-1.5 rounded-full bg-gray-500" />
                      Expired
                    </span>
                    <span className="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-green-100 text-green-700">
                      <span className="h-1.5 w-1.5 rounded-full bg-green-500" />
                      Complete
                    </span>
                  </div>
                </div>

                {/* Expiry Tracking */}
                <div className="space-y-2">
                  <div className="flex items-center justify-between rounded-lg border border-green-200 bg-green-50 px-3 py-2">
                    <span className="text-sm">TX Medical License</span>
                    <span className="text-xs font-medium text-green-600">Complete</span>
                  </div>
                  <div className="flex items-center justify-between rounded-lg border border-orange-200 bg-orange-50 px-3 py-2">
                    <span className="text-sm">Malpractice COI</span>
                    <span className="text-xs font-medium text-orange-600">Expiring in 45 days</span>
                  </div>
                  <div className="flex items-center justify-between rounded-lg border border-red-200 bg-red-50 px-3 py-2">
                    <span className="text-sm">DEA Certificate</span>
                    <span className="text-xs font-medium text-red-600">Missing</span>
                  </div>
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
