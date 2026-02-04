import { problemOutcomeContent } from "@/content/landing";
import { getLandingIcon } from "@/lib/landing-icons";
import { ProblemCard } from "./shared/Cards";
import type { LandingPageContent } from "@/types/wordpress";

interface ProblemOutcomeProps {
  content?: LandingPageContent;
}

export function ProblemOutcome({ content }: ProblemOutcomeProps) {
  const data = content?.problemOutcomeContent ?? problemOutcomeContent;
  return (
    <section className="py-16 sm:py-20 lg:py-24 section-tinted">
      <div className="container mx-auto px-4">
        {/* Problem Cards */}
        <div className="grid gap-6 sm:grid-cols-3 mb-12">
          {data.problems.map((problem, index) => {
            const Icon = getLandingIcon(problem.icon);
            return (
              <ProblemCard
                key={index}
                icon={Icon}
                title={problem.title}
                description={problem.description}
              />
            );
          })}
        </div>

        {/* Outcome Statement */}
        <div className="text-center max-w-2xl mx-auto">
          <p className="text-2xl sm:text-3xl font-semibold text-foreground">
            <span className="text-primary">{data.outcomeText.prefix}</span>
            {data.outcomeText.middle}
            <span className="text-primary">{data.outcomeText.suffix}</span>.
          </p>
        </div>
      </div>
    </section>
  );
}
