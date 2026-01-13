import { ArrowRight } from "lucide-react";

export function FinalCTA() {
  return (
    <section className="section-spacing section-tinted">
      <div className="section-container">
        <div className="text-center max-w-2xl mx-auto space-y-8">
          <div className="space-y-4">
            <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-foreground">
              Stop redoing credentialing paperwork
            </h2>
            <p className="text-xl text-muted-foreground">
              Start free and generate your Texas packet today.
            </p>
          </div>

          <div className="flex flex-wrap justify-center gap-4">
            <a href="#pricing" className="btn-primary text-base px-8 py-4">
              Start free
              <ArrowRight className="h-4 w-4" />
            </a>
            <a href="#sample" className="btn-secondary text-base px-8 py-4">
              View sample Texas packet
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}
