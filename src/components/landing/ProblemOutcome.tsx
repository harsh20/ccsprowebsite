import { RefreshCw, FileX, Bell } from "lucide-react";
import { problemOutcomeContent } from "@/content/landing";
import { ProblemCard } from "./shared/Cards";

const iconMap = {
  RefreshCw,
  FileX,
  Bell,
};

export function ProblemOutcome() {
  return (
    <section className="py-16 sm:py-20 lg:py-24 section-tinted">
      <div className="container mx-auto px-4">
        {/* Problem Cards */}
        <div className="grid gap-6 sm:grid-cols-3 mb-12">
          {problemOutcomeContent.problems.map((problem, index) => {
            const Icon = iconMap[problem.icon as keyof typeof iconMap];
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
            <span className="text-primary">{problemOutcomeContent.outcomeText.prefix}</span>
            {problemOutcomeContent.outcomeText.middle}
            <span className="text-primary">{problemOutcomeContent.outcomeText.suffix}</span>.
          </p>
        </div>
      </div>
    </section>
  );
}
