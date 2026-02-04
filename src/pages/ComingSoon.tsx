export default function ComingSoon() {
  return (
    <div className="min-h-screen bg-background flex flex-col items-center justify-center px-4">
      <div className="flex items-center gap-2 mb-8">
        <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
          <span className="text-lg font-bold text-primary-foreground">C</span>
        </div>
        <span className="text-xl font-bold text-foreground">CCS Pro</span>
      </div>
      <h1 className="text-3xl sm:text-4xl font-bold text-foreground text-center mb-3">
        Coming Soon
      </h1>
      <p className="text-muted-foreground text-center max-w-sm">
        We're getting things ready. Check back soon.
      </p>
    </div>
  );
}
