import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion";
import { faqContent } from "@/content/landing";
import { SectionHeader } from "./shared/Cards";
import type { LandingPageContent, FaqContent } from "@/types/wordpress";

interface FAQSectionProps {
  content?: LandingPageContent;
  faqData?: FaqContent;
}

export function FAQSection({ content, faqData }: FAQSectionProps) {
  const data = faqData ?? content?.faqContent ?? faqContent;
  return (
    <section id="faq" className="py-16 sm:py-20 lg:py-24 bg-background">
      <div className="container mx-auto px-4">
        <SectionHeader
          title={data.sectionTitle}
          subtitle={data.sectionSubtitle}
        />

        <div className="max-w-3xl mx-auto">
          <Accordion type="single" collapsible className="space-y-4">
            {data.items.map((faq, index) => (
              <AccordionItem
                key={index}
                value={`item-${index}`}
                className="card-elevated px-6 border-none"
              >
                <AccordionTrigger className="text-left font-semibold hover:no-underline py-5">
                  {faq.question}
                </AccordionTrigger>
                <AccordionContent className="text-muted-foreground pb-5 prose prose-sm max-w-none">
                  {faq.answer.includes("<") ? (
                    <div dangerouslySetInnerHTML={{ __html: faq.answer }} />
                  ) : (
                    faq.answer
                  )}
                </AccordionContent>
              </AccordionItem>
            ))}
          </Accordion>
        </div>
      </div>
    </section>
  );
}
