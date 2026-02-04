import { howItWorksContent } from "@/content/landing";
import { getLandingIcon } from "@/lib/landing-icons";
import { StepCard, SectionHeader } from "./shared/Cards";
import type { LandingPageContent } from "@/types/wordpress";

interface HowItWorksProps {
  content?: LandingPageContent;
}

export function HowItWorks({ content }: HowItWorksProps) {
  const data = content?.howItWorksContent ?? howItWorksContent;
  return (
    <section id="how-it-works" className="py-16 sm:py-20 lg:py-24 bg-background">
      <div className="container mx-auto px-4">
        <SectionHeader
          title={data.sectionTitle}
          subtitle={data.sectionSubtitle}
        />

        <div className="grid gap-6 lg:grid-cols-3">
          {data.steps.map((step, index) => {
            const Icon = getLandingIcon(step.icon);
            return (
              <StepCard
                key={index}
                icon={Icon}
                step={step.step}
                title={step.title}
                description={step.description}
              />
            );
          })}
        </div>

        {/* Readiness States Note */}
        <div className="mt-8 p-4 bg-muted/50 rounded-xl">
          <p className="text-center text-sm text-muted-foreground">
            <span className="font-medium text-foreground">{data.readinessNote.label}</span>{" "}
            {data.readinessNote.states.map((state, index) => (
              <span key={index}>
                <span className="inline-flex items-center gap-1">
                  <span
                    className={`h-2 w-2 rounded-full ${
                      state.color === "red"
                        ? "bg-red-500"
                        : state.color === "blue"
                        ? "bg-blue-500"
                        : state.color === "orange"
                        ? "bg-orange-500"
                        : state.color === "gray"
                        ? "bg-gray-500"
                        : "bg-green-500"
                    }`}
                  />
                  {state.label}
                </span>
                {index < data.readinessNote.states.length - 1 && " • "}
              </span>
            ))}
          </p>
        </div>
      </div>
    </section>
  );
}
