import { useEffect, useState } from "react";
import { Mail, Clock, Send } from "lucide-react";
import { mockSiteSettings, mockContactPage } from "@/content/mockData";
import { useContactPage, useSiteConfig, useMenus } from "@/hooks/useWordPress";
import { Header } from "@/components/landing/Header";
import { Footer } from "@/components/landing/Footer";

const ContactPage = () => {
  useEffect(() => {
    document.title = "Contact Us | CCS Pro";
  }, []);

  const { data: apiData } = useContactPage();
  const { data: siteConfig } = useSiteConfig();
  const { data: menus } = useMenus();

  const page = apiData ?? mockContactPage;

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

  const [formData, setFormData] = useState({
    name: "",
    email: "",
    role: "",
    message: "",
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Placeholder — no backend yet
  };

  return (
    <div className="min-h-screen bg-background">
      <Header headerData={headerData} />
      <main>
        {/* Hero */}
        <section className="pt-32 pb-12 px-4">
          <div className="container mx-auto max-w-3xl text-center">
            <h1 className="text-4xl md:text-5xl font-bold text-foreground mb-4">
              {page.hero.headline}
            </h1>
            <p className="text-lg text-muted-foreground">
              {page.hero.subheadline}
            </p>
          </div>
        </section>

        {/* Two-column layout */}
        <section className="py-12 px-4">
          <div className="container mx-auto max-w-5xl">
            <div className="grid gap-10 lg:grid-cols-2">
              {/* Left: form */}
              <div className="card-elevated p-6 sm:p-8">
                <form onSubmit={handleSubmit} className="space-y-5">
                  <div>
                    <label
                      htmlFor="name"
                      className="block text-sm font-medium text-foreground mb-1.5"
                    >
                      Name
                    </label>
                    <input
                      id="name"
                      type="text"
                      required
                      className="w-full rounded-lg border border-border bg-background px-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50"
                      value={formData.name}
                      onChange={(e) =>
                        setFormData((prev) => ({ ...prev, name: e.target.value }))
                      }
                    />
                  </div>

                  <div>
                    <label
                      htmlFor="email"
                      className="block text-sm font-medium text-foreground mb-1.5"
                    >
                      Email
                    </label>
                    <input
                      id="email"
                      type="email"
                      required
                      className="w-full rounded-lg border border-border bg-background px-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50"
                      value={formData.email}
                      onChange={(e) =>
                        setFormData((prev) => ({ ...prev, email: e.target.value }))
                      }
                    />
                  </div>

                  <div>
                    <label
                      htmlFor="role"
                      className="block text-sm font-medium text-foreground mb-1.5"
                    >
                      I am a
                    </label>
                    <select
                      id="role"
                      required
                      className="w-full rounded-lg border border-border bg-background px-4 py-2.5 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/50"
                      value={formData.role}
                      onChange={(e) =>
                        setFormData((prev) => ({ ...prev, role: e.target.value }))
                      }
                    >
                      <option value="" disabled>
                        Select one...
                      </option>
                      {page.formFields.roleOptions.map((opt) => (
                        <option key={opt} value={opt}>
                          {opt}
                        </option>
                      ))}
                    </select>
                  </div>

                  <div>
                    <label
                      htmlFor="message"
                      className="block text-sm font-medium text-foreground mb-1.5"
                    >
                      Message
                    </label>
                    <textarea
                      id="message"
                      rows={5}
                      required
                      className="w-full rounded-lg border border-border bg-background px-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 resize-none"
                      value={formData.message}
                      onChange={(e) =>
                        setFormData((prev) => ({
                          ...prev,
                          message: e.target.value,
                        }))
                      }
                    />
                  </div>

                  <button
                    type="submit"
                    className="btn-primary w-full inline-flex items-center justify-center gap-2"
                  >
                    Send Message
                    <Send className="h-4 w-4" />
                  </button>
                </form>
              </div>

              {/* Right: contact info */}
              <div className="space-y-8">
                <div className="space-y-5">
                  <div className="flex items-start gap-4">
                    <div className="h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                      <Mail className="h-5 w-5 text-primary" />
                    </div>
                    <div>
                      <h3 className="font-semibold text-foreground">Email</h3>
                      <a
                        href={`mailto:${page.contactInfo.email}`}
                        className="text-sm text-primary hover:underline"
                      >
                        {page.contactInfo.email}
                      </a>
                    </div>
                  </div>

                  <div className="flex items-start gap-4">
                    <div className="h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                      <Clock className="h-5 w-5 text-primary" />
                    </div>
                    <div>
                      <h3 className="font-semibold text-foreground">
                        Response time
                      </h3>
                      <p className="text-sm text-muted-foreground">
                        {page.contactInfo.responseTime}
                      </p>
                      <p className="text-sm text-muted-foreground mt-1">
                        {page.contactInfo.businessHours}
                      </p>
                    </div>
                  </div>
                </div>

                {/* Group callout */}
                <div className="rounded-xl bg-emerald-50 border border-emerald-200 p-6 space-y-2">
                  <h3 className="font-semibold text-emerald-900">
                    {page.groupCallout.headline}
                  </h3>
                  <p className="text-sm text-emerald-800">
                    {page.groupCallout.body}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>
      <Footer footerData={footerData} />
    </div>
  );
};

export default ContactPage;
