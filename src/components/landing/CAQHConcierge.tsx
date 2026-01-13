import { UserCheck, ShieldCheck, Bell, CheckCircle } from "lucide-react";

const conciergeBenefits = [
  "Maintain your CAQH profile",
  "Upload documents and keep them current",
  "Coordinate updates and reminders",
];

const consentModes = [
  {
    title: "Explicit consent per action",
    description: "Approve each action before we make any changes to your profile.",
    icon: ShieldCheck,
  },
  {
    title: "Standing authorization",
    description: "Define allowed actions upfront with instant revoke at any time.",
    icon: UserCheck,
  },
];

export function CAQHConcierge() {
  return (
    <section className="section-spacing section-tinted">
      <div className="section-container">
        <div className="grid gap-12 lg:grid-cols-2 items-start">
          {/* Left - Benefits */}
          <div className="space-y-6">
            <div className="space-y-4">
              <div className="badge-pill">Add-on Service</div>
              <h2 className="text-3xl sm:text-4xl font-bold text-foreground">
                Done-for-you CAQH Concierge
              </h2>
              <p className="text-lg text-muted-foreground">
                Let us handle your CAQH profile maintenance so you can focus on patient care.
              </p>
            </div>

            <div className="space-y-3">
              <h3 className="font-semibold text-foreground">What we do for you:</h3>
              <div className="space-y-2">
                {conciergeBenefits.map((benefit, index) => (
                  <div key={index} className="flex items-center gap-3">
                    <CheckCircle className="h-5 w-5 text-primary flex-shrink-0" />
                    <span className="text-muted-foreground">{benefit}</span>
                  </div>
                ))}
              </div>
            </div>

            <a href="#pricing" className="btn-primary inline-flex">
              Add CAQH Concierge
            </a>
          </div>

          {/* Right - Consent Modes */}
          <div className="space-y-6">
            <h3 className="font-semibold text-foreground">Choose your consent mode:</h3>
            
            <div className="space-y-4">
              {consentModes.map((mode, index) => (
                <div
                  key={index}
                  className="card-elevated p-6 flex gap-4"
                >
                  <div className="icon-box flex-shrink-0">
                    <mode.icon className="h-5 w-5" />
                  </div>
                  <div>
                    <h4 className="font-semibold text-foreground mb-1">{mode.title}</h4>
                    <p className="text-sm text-muted-foreground">{mode.description}</p>
                  </div>
                </div>
              ))}
            </div>

            {/* Always included */}
            <div className="bg-primary/5 rounded-xl p-4">
              <div className="flex items-start gap-3">
                <Bell className="h-5 w-5 text-primary flex-shrink-0 mt-0.5" />
                <div>
                  <p className="font-medium text-foreground text-sm">Always included:</p>
                  <p className="text-sm text-muted-foreground">
                    Activity log and email notification for every change, regardless of consent mode.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
