import { ClipboardCheck, Sparkles, FileOutput } from "lucide-react";

const steps = [
  {
    icon: ClipboardCheck,
    step: "01",
    title: "Texas-Calibrated Profile Build",
    description: "Complete your profile with field-level validation tailored specifically to Texas credentialing standards. Our readiness engine scores your progress in real-time.",
  },
  {
    icon: Sparkles,
    step: "02",
    title: "AI-Powered Expiry Detection",
    description: "Securely upload your files. Our AI/OCR pipeline automatically extracts issue and expiry dates, mapping them to five distinct readiness states.",
  },
  {
    icon: FileOutput,
    step: "03",
    title: "One-Click Packet Assembly",
    description: "Generate professionally formatted, submission-ready PDF packets instantly, complete with cover pages and organized sections.",
  },
];

export function HowItWorks() {
  return (
    <section id="how-it-works" className="section-spacing bg-background">
      <div className="section-container">
        <div className="text-center max-w-2xl mx-auto mb-12">
          <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">
            How it works
          </h2>
          <p className="text-lg text-muted-foreground">
            Three simple steps to a submission-ready credentialing packet
          </p>
        </div>

        <div className="grid gap-6 lg:grid-cols-3">
          {steps.map((step, index) => (
            <div
              key={index}
              className="card-elevated card-hover p-8 relative overflow-hidden"
            >
              <span className="absolute top-4 right-4 text-6xl font-bold text-primary/5">
                {step.step}
              </span>
              <div className="relative space-y-4">
                <div className="icon-box">
                  <step.icon className="h-5 w-5" />
                </div>
                <h3 className="text-lg font-semibold text-foreground">
                  {step.title}
                </h3>
                <p className="text-muted-foreground">
                  {step.description}
                </p>
              </div>
            </div>
          ))}
        </div>

        {/* Readiness States Note */}
        <div className="mt-8 p-4 bg-muted/50 rounded-xl">
          <p className="text-center text-sm text-muted-foreground">
            <span className="font-medium text-foreground">5 Readiness States:</span>{" "}
            <span className="inline-flex items-center gap-1"><span className="h-2 w-2 rounded-full bg-red-500" /> Missing</span> •{" "}
            <span className="inline-flex items-center gap-1"><span className="h-2 w-2 rounded-full bg-blue-500" /> Uploaded</span> •{" "}
            <span className="inline-flex items-center gap-1"><span className="h-2 w-2 rounded-full bg-orange-500" /> Expiring Soon</span> •{" "}
            <span className="inline-flex items-center gap-1"><span className="h-2 w-2 rounded-full bg-gray-500" /> Expired</span> •{" "}
            <span className="inline-flex items-center gap-1"><span className="h-2 w-2 rounded-full bg-green-500" /> Complete</span>
          </p>
        </div>
      </div>
    </section>
  );
}
