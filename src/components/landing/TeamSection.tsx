import { Shield, Briefcase } from "lucide-react";
import { teamContent } from "@/content/landing";
import { TeamMemberCard, SectionHeader } from "./shared/Cards";

const iconMap = {
  Shield,
  Briefcase,
};

export function TeamSection() {
  return (
    <section className="py-16 sm:py-20 lg:py-24 bg-background">
      <div className="container mx-auto px-4">
        <SectionHeader
          title={teamContent.sectionTitle}
          subtitle={teamContent.sectionSubtitle}
        />

        <div className="grid gap-6 sm:grid-cols-2 max-w-2xl mx-auto">
          {teamContent.members.map((member, index) => {
            const Icon = iconMap[member.icon as keyof typeof iconMap];
            return (
              <TeamMemberCard
                key={index}
                name={member.name}
                role={member.role}
                bio={member.bio}
                icon={Icon}
              />
            );
          })}
        </div>
      </div>
    </section>
  );
}
