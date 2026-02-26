import { problemOutcomeContent } from "@/content/landing";
import { getLandingIcon } from "@/lib/landing-icons";
import { ProblemCard } from "./shared/Cards";
import type { LandingPageContent, PainPointContent } from "@/types/wordpress";

interface ProblemOutcomeProps {
  content?: LandingPageContent;
  painPointData?: PainPointContent;
}

export function ProblemOutcome({ content, painPointData }: ProblemOutcomeProps) {
  // New painPointData path
  if (painPointData) {
    return <PainPointSection data={painPointData} />;
  }

  // Legacy path
  const data = content?.problemOutcomeContent ?? problemOutcomeContent;
  return (
    <section className="py-16 sm:py-20 lg:py-24 section-tinted">
      <div className="container mx-auto px-4">
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

function PainPointSection({ data }: { data: PainPointContent }) {
  const cardStylesByIcon: Record<
    string,
    { card: string; iconWrap: string; icon: string; border: string }
  > = {
    Clock: {
      card: "bg-amber-50",
      iconWrap: "bg-amber-100",
      icon: "text-amber-600",
      border: "border-amber-200",
    },
    FileX: {
      card: "bg-rose-50",
      iconWrap: "bg-rose-100",
      icon: "text-rose-600",
      border: "border-rose-200",
    },
    RefreshCw: {
      card: "bg-blue-50",
      iconWrap: "bg-blue-100",
      icon: "text-blue-600",
      border: "border-blue-200",
    },
    Bell: {
      card: "bg-blue-50",
      iconWrap: "bg-blue-100",
      icon: "text-blue-600",
      border: "border-blue-200",
    },
  };

  const renderSummaryWithHighlights = (summaryText: string) => {
    const matches = summaryText.split(/(built once|kept current|ready to go)/gi);
    return (
      <p className="text-lg text-slate-600">
        {matches.map((chunk, index) => {
          const isHighlighted = /^(built once|kept current|ready to go)$/i.test(chunk);
          return isHighlighted ? (
            <span key={index} className="text-primary font-medium">
              {chunk}
            </span>
          ) : (
            <span key={index}>{chunk}</span>
          );
        })}
      </p>
    );
  };

  return (
    <section className="py-16 sm:py-20 lg:py-24 bg-slate-50">
      <div className="container mx-auto px-4">
        {/* Section label + headline */}
        <div className="text-center max-w-3xl mx-auto mb-12">
          <span className="inline-block text-sm font-semibold text-primary uppercase tracking-wider mb-3">
            {data.sectionLabel}
          </span>
          <h2 className="text-3xl sm:text-4xl font-bold text-foreground">
            {data.headline}
          </h2>
        </div>

        {/* Cards */}
        <div className="grid gap-6 sm:grid-cols-3 mb-12">
          {data.cards.map((card, index) => {
            const Icon = getLandingIcon(card.icon);
            const style = cardStylesByIcon[card.icon] ?? {
              card: "bg-white",
              iconWrap: "bg-slate-100",
              icon: "text-slate-600",
              border: "border-slate-200",
            };
            return (
              <div
                key={index}
                className={`rounded-2xl border shadow-sm p-6 text-center space-y-4 ${style.card} ${style.border}`}
              >
                <div
                  className={`h-10 w-10 rounded-xl mx-auto flex items-center justify-center ${style.iconWrap}`}
                >
                  <Icon className={`h-5 w-5 ${style.icon}`} />
                </div>
                <h3 className="font-semibold text-slate-800">{card.title}</h3>
                <p className="text-sm text-slate-600">{card.body}</p>
              </div>
            );
          })}
        </div>

        {/* Summary */}
        <div className="max-w-2xl mx-auto text-center mt-12">
          {renderSummaryWithHighlights(data.summaryText)}
        </div>
      </div>
    </section>
  );
}
