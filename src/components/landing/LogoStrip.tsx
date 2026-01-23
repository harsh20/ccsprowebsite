import { Shield, FileCheck, UserCheck } from "lucide-react";

const verifications = [
  {
    icon: Shield,
    label: "Texas Medical Board (TMB)",
  },
  {
    icon: FileCheck,
    label: "DEA Registration",
  },
  {
    icon: UserCheck,
    label: "CAQH ProView",
  },
];

export function LogoStrip() {
  return (
    <section className="border-y border-border/50 bg-background">
      <div className="section-container py-10">
        <p className="text-center text-sm text-muted-foreground mb-8">
          Verified against Texas credentialing requirements
        </p>
        <div className="flex flex-wrap items-center justify-center gap-8 lg:gap-16">
          {verifications.map((item, index) => (
            <div
              key={index}
              className="flex items-center gap-3 px-6 py-3 rounded-xl bg-muted/50 border border-border/50"
            >
              <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                <item.icon className="h-5 w-5 text-primary" />
              </div>
              <span className="font-medium text-foreground">
                {item.label}
              </span>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
