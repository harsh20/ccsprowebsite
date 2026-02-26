import { ExternalLink } from "lucide-react";
import { supportContent } from "@/content/landing";
import { getLandingIcon } from "@/lib/landing-icons";
import { SupportFeatureBadge } from "./shared/Cards";
import type { LandingPageContent, SupportSectionContent } from "@/types/wordpress";

interface SupportSectionProps {
  content?: LandingPageContent;
  channelData?: SupportSectionContent;
}

export function SupportSection({ content, channelData }: SupportSectionProps) {
  if (channelData) {
    return <ChannelSupportSection data={channelData} />;
  }

  // Legacy path
  const data = content?.supportContent ?? supportContent;
  return (
    <section className="py-16 sm:py-20 lg:py-24 section-tinted">
      <div className="container mx-auto px-4">
        <div className="max-w-3xl mx-auto text-center space-y-8">
          <div className="space-y-4">
            <h2 className="text-3xl sm:text-4xl font-bold text-foreground">
              {data.sectionTitle}
            </h2>
            <p className="text-lg text-muted-foreground">{data.sectionSubtitle}</p>
          </div>

          <div className="flex flex-wrap justify-center gap-6">
            {data.features.map((feature, index) => {
              const Icon = getLandingIcon(feature.icon);
              return (
                <SupportFeatureBadge key={index} icon={Icon} text={feature.text} />
              );
            })}
          </div>

          <div className="flex flex-wrap justify-center gap-4">
            {data.links.map((link, index) => (
              <span key={index}>
                <a
                  href={link.href}
                  className="inline-flex items-center gap-2 text-primary font-medium hover:underline"
                >
                  {link.label}
                  <ExternalLink className="h-4 w-4" />
                </a>
                {index < data.links.length - 1 && (
                  <span className="ml-4 text-muted-foreground">&bull;</span>
                )}
              </span>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}

function ChannelSupportSection({ data }: { data: SupportSectionContent }) {
  return (
    <section className="py-16 sm:py-20 lg:py-24 section-tinted">
      <div className="container mx-auto px-4">
        <div className="text-center mb-12">
          <h2 className="text-3xl sm:text-4xl font-bold text-foreground">
            {data.headline}
          </h2>
        </div>

        <div className="grid gap-6 sm:grid-cols-3 max-w-4xl mx-auto">
          {data.channels.map((channel, index) => {
            const Icon = getLandingIcon(channel.icon);
            return (
              <div key={index} className="card-elevated p-6 text-center space-y-4">
                <div className="h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center mx-auto">
                  <Icon className="h-6 w-6 text-primary" />
                </div>
                <h3 className="font-semibold text-foreground">{channel.title}</h3>
                <p className="text-sm text-muted-foreground">
                  {channel.description}
                </p>
                {channel.link && (
                  <a
                    href={channel.link}
                    className="text-sm font-medium text-primary hover:underline"
                  >
                    {channel.link.startsWith("mailto:")
                      ? channel.link.replace("mailto:", "")
                      : channel.link}
                  </a>
                )}
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
}
