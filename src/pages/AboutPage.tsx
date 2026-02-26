import { useEffect } from "react";
import { ArrowRight } from "lucide-react";
import { Link } from "react-router-dom";
import { mockSiteSettings, mockAboutPage } from "@/content/mockData";
import { useAboutPage, useSiteConfig, useMenus } from "@/hooks/useWordPress";
import { Header } from "@/components/landing/Header";
import { Footer } from "@/components/landing/Footer";

const AboutPage = () => {
  useEffect(() => {
    document.title = "About Us | CCS Pro";
  }, []);

  const { data: apiData } = useAboutPage();
  const { data: siteConfig } = useSiteConfig();
  const { data: menus } = useMenus();

  const page = apiData ?? mockAboutPage;

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
        <section className="pt-32 pb-16 px-4">
          <div className="container mx-auto max-w-3xl text-center">
            <h1 className="text-4xl md:text-5xl font-bold text-foreground mb-4">
              {page.hero.headline}
            </h1>
            <p className="text-lg text-muted-foreground">
              {page.hero.subheadline}
            </p>
          </div>
        </section>

        {/* Mission */}
        <section className="py-12 px-4">
          <div className="container mx-auto max-w-3xl">
            <div className="card-elevated p-8 sm:p-10 text-center">
              <h2 className="text-sm font-semibold text-primary uppercase tracking-wider mb-4">
                Our Mission
              </h2>
              <p className="text-lg text-foreground leading-relaxed">
                {page.mission}
              </p>
            </div>
          </div>
        </section>

        {/* Why Texas */}
        <section className="py-16 px-4 section-tinted">
          <div className="container mx-auto max-w-5xl">
            <h2 className="text-3xl font-bold text-foreground text-center mb-10">
              Why Texas
            </h2>
            <div className="grid gap-10 lg:grid-cols-2 items-start">
              <p className="text-muted-foreground leading-relaxed">
                {page.whyTexas.paragraph}
              </p>
              <div className="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                {page.whyTexas.stats.map((stat, index) => (
                  <div
                    key={index}
                    className="card-elevated p-5 text-center lg:text-left"
                  >
                    <p className="text-2xl font-bold text-primary">
                      {stat.value}
                    </p>
                    <p className="text-sm text-muted-foreground mt-1">
                      {stat.label}
                    </p>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </section>

        {/* How We're Different */}
        <section className="py-16 px-4">
          <div className="container mx-auto max-w-5xl">
            <h2 className="text-3xl font-bold text-foreground text-center mb-10">
              How we're different
            </h2>
            <div className="grid gap-6 sm:grid-cols-3">
              {page.differentiators.map((d, index) => (
                <div key={index} className="card-elevated p-6 space-y-3">
                  <h3 className="font-semibold text-foreground text-lg">
                    {d.title}
                  </h3>
                  <p className="text-sm text-muted-foreground">
                    {d.description}
                  </p>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* CTA */}
        <section className="py-16 px-4">
          <div className="container mx-auto max-w-3xl text-center space-y-6">
            <p className="text-lg text-muted-foreground">{page.cta.text}</p>
            <Link
              to={page.cta.link.href}
              className="btn-primary text-base px-7 py-3.5 inline-flex items-center gap-2"
            >
              {page.cta.link.label}
              <ArrowRight className="h-4 w-4" />
            </Link>
          </div>
        </section>
      </main>
      <Footer footerData={footerData} />
    </div>
  );
};

export default AboutPage;
