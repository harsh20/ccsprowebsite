import { UserPlus, Clock, FileOutput } from "lucide-react";

const steps = [
  {
    icon: UserPlus,
    step: "01",
    title: "Add your profile and upload credentials",
    description: "Enter your information once. Upload licenses, certifications, and supporting documents.",
  },
  {
    icon: Clock,
    step: "02",
    title: "Track expirations with reminders",
    description: "Get notified before licenses, COIs, and certifications expire.",
  },
  {
    icon: FileOutput,
    step: "03",
    title: "Generate your Texas packet as PDF",
    description: "Download a structured packet. Sign it with e-signature in Pro.",
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
            Three simple steps to a complete credentialing packet
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

        {/* Note */}
        <p className="text-center text-sm text-muted-foreground mt-8">
          <span className="font-medium">Pro tip:</span> Draft packet preview available even if incomplete
        </p>
      </div>
    </section>
  );
}
