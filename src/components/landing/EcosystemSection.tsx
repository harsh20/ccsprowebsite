import type { EcosystemContent } from "@/types/wordpress";

interface EcosystemSectionProps {
  data: EcosystemContent;
}

export function EcosystemSection({ data }: EcosystemSectionProps) {
  return (
    <section className="bg-white border-y border-slate-200 py-20 lg:py-24">
      <div className="container mx-auto px-4">
        <div className="text-center max-w-3xl mx-auto mb-12">
          <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">
            {data.headline}
          </h2>
          <p className="text-lg text-muted-foreground">{data.subheadline}</p>
        </div>

        {/* Desktop: two columns with continuous center divider */}
        <div className="hidden md:block max-w-5xl mx-auto">
          <div className="relative">
            <div className="pointer-events-none absolute inset-y-0 left-1/2 -translate-x-1/2 w-px bg-slate-200" />

          {/* Column headers */}
            <div className="grid grid-cols-[1fr_80px_1fr] gap-0 mb-8">
              <div className="text-center">
                <p className="text-sm font-semibold uppercase tracking-wider text-indigo-600">
                  Providers
                </p>
                <div className="w-12 h-0.5 bg-indigo-400 mx-auto mt-1.5" />
              </div>
              <div />
              <div className="text-center">
                <p className="text-sm font-semibold uppercase tracking-wider text-emerald-600">
                  Groups & Facilities
                </p>
                <div className="w-12 h-0.5 bg-emerald-400 mx-auto mt-1.5" />
              </div>
            </div>

            {data.pairs.map((pair, index) => (
              <div
                key={index}
                className={`grid grid-cols-[1fr_80px_1fr] gap-0 items-center ${
                  index < data.pairs.length - 1 ? "mb-4" : ""
                }`}
              >
                <div className="rounded-2xl border border-indigo-200 bg-indigo-50 shadow-md px-6 py-5 text-center min-h-[64px] flex items-center justify-center">
                  <p className="text-sm font-medium text-indigo-800">
                    {pair.providerAction}
                  </p>
                </div>
                <div className="flex items-center justify-center">
                  <div className="relative z-10 flex w-full items-center gap-1.5 px-1">
                    <div className="flex-1 h-px bg-slate-300" />
                    <span className="whitespace-nowrap rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-medium text-slate-400 shadow-sm">
                      {pair.connector}
                    </span>
                    <div className="flex-1 h-px bg-slate-300" />
                  </div>
                </div>
                <div className="rounded-2xl border border-emerald-200 bg-emerald-50 shadow-md px-6 py-5 text-center min-h-[64px] flex items-center justify-center">
                  <p className="text-sm font-medium text-emerald-800">
                    {pair.groupOutcome}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Mobile: stacked vertically */}
        <div className="md:hidden max-w-sm mx-auto space-y-6">
          {data.pairs.map((pair, index) => (
            <div key={index}>
              <div className="rounded-2xl border border-indigo-200 bg-indigo-50 shadow-md px-6 py-5 text-center min-h-[64px] flex items-center justify-center">
                <p className="text-sm font-medium text-indigo-800">
                  {pair.providerAction}
                </p>
              </div>
              <div className="flex justify-center py-2">
                <span className="inline-block min-w-[64px] text-center rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-medium text-slate-400 mx-auto shadow-sm">
                  {pair.connector}
                </span>
              </div>
              <div className="rounded-2xl border border-emerald-200 bg-emerald-50 shadow-md px-6 py-5 text-center min-h-[64px] flex items-center justify-center">
                <p className="text-sm font-medium text-emerald-800">
                  {pair.groupOutcome}
                </p>
              </div>
              {index < data.pairs.length - 1 && (
                <div className="border-b border-slate-100 my-3" />
              )}
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
