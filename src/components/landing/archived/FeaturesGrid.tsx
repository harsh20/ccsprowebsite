import { featuresContent } from "@/content/landing";
import { getLandingIcon } from "@/lib/landing-icons";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card";
import type { LandingPageContent } from "@/types/wordpress";

interface FeaturesGridProps {
  content?: LandingPageContent;
}

export function FeaturesGrid({ content }: FeaturesGridProps) {
  const data = content?.featuresContent ?? featuresContent;

  return (
    <section id="product" className="py-20 px-4">
      <div className="section-container max-w-6xl">
        <div className="text-center mb-14">
          <h2 className="text-3xl md:text-4xl font-bold text-foreground mb-4">{data.sectionTitle}</h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto">{data.sectionSubtitle}</p>
        </div>

        <div className="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
          {data.features.map((feature, index) => {
            const Icon = getLandingIcon(feature.icon);
            return (
              <Card key={index} className="card-hover border-border/60">
                <CardHeader>
                  <div className="h-12 w-12 rounded-lg bg-accent/10 flex items-center justify-center mb-3">
                    <Icon className="h-6 w-6 text-accent" />
                  </div>
                  <CardTitle className="text-xl">{feature.title}</CardTitle>
                </CardHeader>
                <CardContent>
                  <CardDescription className="text-base mb-4">{feature.description}</CardDescription>
                  <a href={feature.link} className="text-sm font-medium text-primary hover:underline">
                    Learn more
                  </a>
                </CardContent>
              </Card>
            );
          })}
        </div>
      </div>
    </section>
  );
}
