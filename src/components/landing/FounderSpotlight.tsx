import { Quote } from "lucide-react";
import { founderContent } from "@/content/landing";

export function FounderSpotlight() {
  return (
    <section className="py-16 sm:py-20 lg:py-24 bg-background">
      <div className="container mx-auto px-4">
        <div className="card-elevated p-8 sm:p-12 lg:p-16">
          <div className="grid gap-8 lg:grid-cols-5 lg:gap-16 items-center">
            {/* Photo Placeholder */}
            <div className="lg:col-span-2 flex justify-center lg:justify-start">
              <div className="relative">
                <div className="h-48 w-48 sm:h-56 sm:w-56 rounded-2xl bg-gradient-to-br from-primary/10 to-primary/5 flex items-center justify-center">
                  <div className="text-center">
                    <div className="h-24 w-24 mx-auto rounded-full bg-primary/20 flex items-center justify-center mb-3">
                      <span className="text-3xl font-bold text-primary">{founderContent.initials}</span>
                    </div>
                    <p className="text-sm font-medium text-foreground">{founderContent.name}</p>
                    <p className="text-xs text-muted-foreground">{founderContent.title}</p>
                  </div>
                </div>
              </div>
            </div>

            {/* Quote & Bullets */}
            <div className="lg:col-span-3 space-y-6">
              <div className="relative">
                <Quote className="absolute -top-2 -left-2 h-8 w-8 text-primary/20" />
                <blockquote className="text-xl sm:text-2xl font-medium text-foreground leading-relaxed pl-8">
                  {founderContent.quote}
                </blockquote>
              </div>

              <div className="space-y-3 pt-4">
                {founderContent.bullets.map((bullet, index) => (
                  <div key={index} className="flex items-start gap-3">
                    <div className="mt-1 h-2 w-2 rounded-full bg-primary flex-shrink-0" />
                    <p className="text-muted-foreground">{bullet}</p>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
