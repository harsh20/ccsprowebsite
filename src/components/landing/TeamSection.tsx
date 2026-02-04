import { teamContent } from "@/content/landing";
import { getLandingIcon } from "@/lib/landing-icons";
import { TeamMemberCard, SectionHeader } from "./shared/Cards";
import type { LandingPageContent } from "@/types/wordpress";

interface TeamSectionProps {
  content?: LandingPageContent;
}

export function TeamSection({ content }: TeamSectionProps) {
  const data = content?.teamContent ?? teamContent;
  return (
    <section className="py-16 sm:py-20 lg:py-24 bg-background">
      <div className="container mx-auto px-4">
        <SectionHeader
          title={data.sectionTitle}
          subtitle={data.sectionSubtitle}
        />

        <div className="grid gap-6 sm:grid-cols-2 max-w-2xl mx-auto">
          {data.members.map((member, index) => {
            const Icon = getLandingIcon(member.icon);
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
