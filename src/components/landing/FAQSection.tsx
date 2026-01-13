import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion";

const faqs = [
  {
    question: "What do I need to start?",
    answer: "Just your email to create an account. From there, you can add your NPI, licenses, certifications, and supporting documents at your own pace. You can preview a draft packet even before your profile is complete.",
  },
  {
    question: "Do you support states other than Texas?",
    answer: "Texas is live now. We're actively rolling out support for additional states. Join our waitlist to be notified when your state becomes available.",
  },
  {
    question: "What is included in a structured packet?",
    answer: "A complete Texas credentialing packet includes your provider profile, state licenses, malpractice certificate of insurance, board certifications, work history verification, and required attestations—all formatted to meet payer and facility requirements.",
  },
  {
    question: "How does signed PDF work?",
    answer: "Pro subscribers can generate a signed PDF using our integrated e-signature provider. The signed document includes a complete audit trail showing when and by whom the document was signed, meeting most payer requirements for electronic signatures.",
  },
  {
    question: "What sensitive documents do you store?",
    answer: "We store documents you upload including driver's licenses, passports, medical licenses, DEA certificates, malpractice COIs, and other credentialing documents. All documents are stored in the United States with encryption in transit and at rest.",
  },
  {
    question: "Can I cancel anytime?",
    answer: "Yes, you can cancel your Pro subscription at any time. You'll retain access until the end of your billing period. See our refund policy for details on money-back eligibility.",
  },
  {
    question: "How does CAQH Concierge work?",
    answer: "With CAQH Concierge, our team maintains your CAQH profile on your behalf. We upload documents, coordinate updates, and send reminders—so you don't have to log in to CAQH yourself. You choose your preferred consent model during onboarding.",
  },
  {
    question: "How do consent modes work and can I revoke access?",
    answer: "You choose between explicit consent (approve each action individually) or standing authorization (pre-approve defined actions). With either mode, you can revoke access instantly at any time. Every change triggers an activity log entry and email notification.",
  },
  {
    question: "What counts as a $29 update request?",
    answer: "An update request covers a single coordinated update to your credentialing profile or CAQH, such as adding a new license or updating expired documents. Complex requests requiring multiple actions may count as separate updates.",
  },
];

export function FAQSection() {
  return (
    <section id="faq" className="section-spacing bg-background">
      <div className="section-container">
        <div className="text-center max-w-2xl mx-auto mb-12">
          <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">
            Frequently asked questions
          </h2>
          <p className="text-lg text-muted-foreground">
            Everything you need to know about CCS Pro
          </p>
        </div>

        <div className="max-w-3xl mx-auto">
          <Accordion type="single" collapsible className="space-y-4">
            {faqs.map((faq, index) => (
              <AccordionItem
                key={index}
                value={`item-${index}`}
                className="card-elevated px-6 border-none"
              >
                <AccordionTrigger className="text-left font-semibold hover:no-underline py-5">
                  {faq.question}
                </AccordionTrigger>
                <AccordionContent className="text-muted-foreground pb-5">
                  {faq.answer}
                </AccordionContent>
              </AccordionItem>
            ))}
          </Accordion>
        </div>
      </div>
    </section>
  );
}
