import { RefreshCw, FileX, Bell } from "lucide-react";

const problems = [
  {
    icon: RefreshCw,
    title: "Re-entering the same data",
    description: "Each payer asks for the same info you've submitted a dozen times.",
  },
  {
    icon: FileX,
    title: "Missing documents cause delays",
    description: "Incomplete packets get rejected, pushing back your start date.",
  },
  {
    icon: Bell,
    title: "Expirations get missed",
    description: "Expired licenses and COIs create credentialing gaps.",
  },
];

export function ProblemOutcome() {
  return (
    <section className="section-spacing section-tinted">
      <div className="section-container">
        {/* Problem Cards */}
        <div className="grid gap-6 sm:grid-cols-3 mb-12">
          {problems.map((problem, index) => (
            <div
              key={index}
              className="card-elevated p-6 text-center space-y-4"
            >
              <div className="icon-box mx-auto">
                <problem.icon className="h-5 w-5" />
              </div>
              <h3 className="font-semibold text-foreground">{problem.title}</h3>
              <p className="text-sm text-muted-foreground">{problem.description}</p>
            </div>
          ))}
        </div>

        {/* Outcome Statement */}
        <div className="text-center max-w-2xl mx-auto">
          <p className="text-2xl sm:text-3xl font-semibold text-foreground">
            <span className="text-primary">One profile</span>, always current,{" "}
            <span className="text-primary">packet on demand</span>.
          </p>
        </div>
      </div>
    </section>
  );
}
