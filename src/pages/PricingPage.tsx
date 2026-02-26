import { useEffect } from "react";
import { CheckCircle, ArrowRight, Minus, Check } from "lucide-react";
import { Link } from "react-router-dom";
import { mockSiteSettings, mockPricingPage } from "@/content/mockData";
import { usePricingPage, useSiteConfig, useMenus } from "@/hooks/useWordPress";
import { Header } from "@/components/landing/Header";
import { Footer } from "@/components/landing/Footer";
import { FAQSection } from "@/components/landing/FAQSection";
import type { PricingPlanExtended } from "@/types/wordpress";

const PricingPage = () => {
  useEffect(() => {
    document.title = "Pricing | CCS Pro";
  }, []);

  const { data: apiData } = usePricingPage();
  const { data: siteConfig } = useSiteConfig();
  const { data: menus } = useMenus();

  const page = apiData ?? mockPricingPage;

  const headerData = siteConfig?.header
    ? {
        logo: siteConfig.header.logoText,
        logoUrl: siteConfig.header.logoUrl,
        ctaButton: siteConfig.header.ctaButton,
        secondaryLink: siteConfig.header.signinLink,
        primaryNav: menus?.primaryNav ?? mockSiteSettings.header.primaryNav,
      }
    : mockSiteSettings.header;

  const [defaultCol1, defaultCol2, defaultCol3] = mockSiteSettings.footer.columns;
  const footerData = siteConfig?.footer
    ? {
        brand: {
          name: siteConfig.footer.brandName,
          tagline: siteConfig.footer.tagline,
        },
        trustBadges: siteConfig.footer.trustBadges,
        copyright: siteConfig.footer.copyright,
        columns: [
          { title: defaultCol1.title, links: menus?.footerCol1 ?? defaultCol1.links },
          { title: defaultCol2.title, links: menus?.footerCol2 ?? defaultCol2.links },
          { title: defaultCol3.title, links: menus?.footerCol3 ?? defaultCol3.links },
        ],
      }
    : mockSiteSettings.footer;

  return (
    <div className="min-h-screen bg-background">
      <Header headerData={headerData} />
      <main>
        {/* Hero */}
        <section className="pt-32 pb-16 px-4 text-center">
          <div className="container mx-auto max-w-3xl">
            <h1 className="text-4xl md:text-5xl font-bold text-foreground mb-4">
              {page.hero.headline}
            </h1>
            <p className="text-lg text-muted-foreground">
              {page.hero.subheadline}
            </p>
          </div>
        </section>

        {/* Pricing Cards */}
        <section id="provider" className="pb-16 px-4">
          <div className="container mx-auto max-w-4xl">
            <div className="grid gap-8 md:grid-cols-2">
              <ExtendedPricingCard plan={page.provider} />
              <div id="groups">
                <ExtendedPricingCard plan={page.group} />
              </div>
            </div>
          </div>
        </section>

        {/* Feature Comparison */}
        <section id="comparison" className="py-16 px-4 bg-muted/30">
          <div className="container mx-auto max-w-4xl">
            <h2 className="text-3xl font-bold text-foreground text-center mb-10">
              Feature comparison
            </h2>

            <div className="card-elevated overflow-hidden">
              <div className="overflow-x-auto">
                <table className="w-full">
                  <thead>
                    <tr className="border-b border-border">
                      <th className="text-left py-4 px-6 font-semibold text-foreground">
                        Feature
                      </th>
                      <th className="py-4 px-6 font-semibold text-foreground text-center w-36">
                        Providers
                      </th>
                      <th className="py-4 px-6 font-semibold text-foreground text-center w-36">
                        Groups
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    {page.featureTable.map((category) => (
                      <CategoryRows
                        key={category.category}
                        category={category.category}
                        rows={category.rows}
                      />
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>

        {/* FAQ */}
        <FAQSection faqData={page.faq} />

        {/* Final CTA */}
        <section className="py-16 px-4">
          <div className="container mx-auto max-w-3xl text-center space-y-6">
            <h2 className="text-3xl md:text-4xl font-bold text-foreground">
              {page.finalCta.headline}
            </h2>
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
              <a
                href={page.finalCta.providerCta.href}
                className="btn-primary text-base px-7 py-3.5 inline-flex items-center gap-2"
              >
                {page.finalCta.providerCta.label}
                <ArrowRight className="h-4 w-4" />
              </a>
              <Link
                to={page.finalCta.groupCta.href}
                className="btn-secondary text-base px-7 py-3.5"
              >
                {page.finalCta.groupCta.label}
              </Link>
            </div>
          </div>
        </section>
      </main>
      <Footer footerData={footerData} />
    </div>
  );
};

function ExtendedPricingCard({ plan }: { plan: PricingPlanExtended }) {
  return (
    <div
      className={`card-elevated p-6 sm:p-8 relative flex flex-col h-full ${
        plan.highlighted ? "ring-2 ring-primary shadow-lg" : ""
      }`}
    >
      <div className="space-y-4 mb-6">
        <span
          className={`inline-block text-xs font-medium px-3 py-1 rounded-full ${
            plan.highlighted
              ? "bg-primary text-primary-foreground"
              : "bg-muted text-muted-foreground"
          }`}
        >
          {plan.badge}
        </span>
        <div className="space-y-1">
          <p className="text-3xl font-bold text-foreground">{plan.price}</p>
          <p className="text-sm text-muted-foreground">{plan.subtext}</p>
        </div>
      </div>

      <ul className="space-y-3 mb-6">
        {plan.bullets.map((bullet, i) => (
          <li key={i} className="flex items-start gap-3 text-sm">
            <CheckCircle className="h-4 w-4 text-primary flex-shrink-0 mt-0.5" />
            <span className="text-foreground">{bullet}</span>
          </li>
        ))}
      </ul>

      {plan.extras.length > 0 && (
        <div className="space-y-3 mb-6 pt-4 border-t border-border">
          {plan.extras.map((extra, i) => (
            <p key={i} className="text-sm text-muted-foreground">
              {extra}
            </p>
          ))}
        </div>
      )}

      <div className="mt-auto space-y-3">
        <Link
          to={plan.cta.href}
          className={`w-full text-center inline-flex items-center justify-center gap-2 ${
            plan.highlighted ? "btn-primary" : "btn-secondary"
          }`}
        >
          {plan.cta.label}
          <ArrowRight className="h-4 w-4" />
        </Link>
        <p className="text-xs text-muted-foreground text-center">
          {plan.finePrint}
        </p>
        {plan.secondaryLink && (
          <Link
            to={plan.secondaryLink.href}
            className="text-sm text-primary font-medium hover:underline text-center block"
          >
            {plan.secondaryLink.label}
          </Link>
        )}
      </div>
    </div>
  );
}

function CategoryRows({
  category,
  rows,
}: {
  category: string;
  rows: Array<{ feature: string; provider: boolean; group: boolean }>;
}) {
  return (
    <>
      <tr className="bg-muted/50">
        <td
          colSpan={3}
          className="py-3 px-6 font-semibold text-sm text-foreground"
        >
          {category}
        </td>
      </tr>
      {rows.map((row) => (
        <tr key={row.feature} className="border-b border-border/50">
          <td className="py-3 px-6 text-sm text-foreground">{row.feature}</td>
          <td className="py-3 px-6 text-center">
            {row.provider ? (
              <Check className="h-5 w-5 text-primary mx-auto" />
            ) : (
              <Minus className="h-5 w-5 text-muted-foreground/40 mx-auto" />
            )}
          </td>
          <td className="py-3 px-6 text-center">
            {row.group ? (
              <Check className="h-5 w-5 text-primary mx-auto" />
            ) : (
              <Minus className="h-5 w-5 text-muted-foreground/40 mx-auto" />
            )}
          </td>
        </tr>
      ))}
    </>
  );
}

export default PricingPage;
