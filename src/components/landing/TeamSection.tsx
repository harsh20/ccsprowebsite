import { Shield, Briefcase } from "lucide-react";

const team = [
  {
    name: "David",
    role: "Operations & Security",
    icon: Shield,
    bio: "Ex-US Air Force. Brings an operations mindset focused on reliability and security.",
  },
  {
    name: "Skeeter",
    role: "Advisor",
    icon: Briefcase,
    bio: "Strategic advisor supporting product direction.",
  },
];

export function TeamSection() {
  return (
    <section className="section-spacing bg-background">
      <div className="section-container">
        <div className="text-center max-w-2xl mx-auto mb-12">
          <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">
            The team behind CCS Pro
          </h2>
          <p className="text-lg text-muted-foreground">
            Built by people who understand credentialing
          </p>
        </div>

        <div className="grid gap-6 sm:grid-cols-2 max-w-2xl mx-auto">
          {team.map((member, index) => (
            <div
              key={index}
              className="card-elevated p-6 text-center space-y-4"
            >
              <div className="h-20 w-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto">
                <member.icon className="h-8 w-8 text-primary" />
              </div>
              <div>
                <h3 className="font-semibold text-foreground text-lg">{member.name}</h3>
                <p className="text-sm text-primary">{member.role}</p>
              </div>
              <p className="text-sm text-muted-foreground">{member.bio}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
