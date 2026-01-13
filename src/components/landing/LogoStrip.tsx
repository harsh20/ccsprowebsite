export function LogoStrip() {
  const logos = [
    "Hospital Network",
    "Insurance Co",
    "Medical Group",
    "Health System",
    "Payer Network",
  ];

  return (
    <section className="border-y border-border/50 bg-background">
      <div className="section-container py-10">
        <p className="text-center text-sm text-muted-foreground mb-8">
          Built for payer and facility credentialing workflows
        </p>
        <div className="flex flex-wrap items-center justify-center gap-8 lg:gap-16">
          {logos.map((logo, index) => (
            <div
              key={index}
              className="flex items-center justify-center h-8 px-4"
            >
              <div className="text-muted-foreground/40 font-semibold text-lg tracking-wide">
                {logo}
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
