import { FileText, CheckCircle, Download } from "lucide-react";

const packetItems = [
  "Provider profile & demographics",
  "State licenses & IDs",
  "Malpractice COI documentation",
  "Board certifications",
  "Work history verification",
  "Required attestations",
];

export function PacketPreview() {
  return (
    <section id="sample" className="section-spacing bg-background">
      <div className="section-container">
        <div className="text-center max-w-2xl mx-auto mb-12">
          <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">
            Sample Texas packet preview
          </h2>
          <p className="text-lg text-muted-foreground">
            See exactly what a structured credentialing packet includes
          </p>
        </div>

        <div className="grid gap-8 lg:grid-cols-2 items-start">
          {/* PDF Viewer Mock */}
          <div className="card-elevated p-2 bg-muted/30">
            <div className="bg-background rounded-xl overflow-hidden">
              {/* PDF Header Bar */}
              <div className="flex items-center justify-between px-4 py-3 border-b border-border bg-muted/30">
                <div className="flex items-center gap-2">
                  <FileText className="h-4 w-4 text-primary" />
                  <span className="text-sm font-medium">Texas_Credential_Packet_Sample.pdf</span>
                </div>
                <button className="btn-ghost text-xs py-1 px-2">
                  <Download className="h-3 w-3 mr-1" />
                  Download
                </button>
              </div>

              {/* PDF Pages Mock */}
              <div className="p-6 space-y-4 bg-muted/20">
                {[1, 2, 3].map((page) => (
                  <div
                    key={page}
                    className="bg-background rounded-lg shadow-sm p-6 space-y-3"
                  >
                    <div className="flex items-center gap-2 text-xs text-muted-foreground">
                      <span>Page {page}</span>
                      <span className="text-muted-foreground/50">|</span>
                      <span>SAMPLE - REDACTED</span>
                    </div>
                    <div className="space-y-2">
                      <div className="h-3 bg-muted rounded w-3/4" />
                      <div className="h-3 bg-muted rounded w-full" />
                      <div className="h-3 bg-muted rounded w-5/6" />
                      <div className="h-3 bg-muted rounded w-2/3" />
                    </div>
                    <div className="grid grid-cols-2 gap-4 pt-2">
                      <div className="space-y-1">
                        <div className="h-2 bg-muted-foreground/10 rounded w-1/2" />
                        <div className="h-4 bg-muted rounded" />
                      </div>
                      <div className="space-y-1">
                        <div className="h-2 bg-muted-foreground/10 rounded w-1/2" />
                        <div className="h-4 bg-muted rounded" />
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Checklist */}
          <div className="space-y-6">
            <div className="space-y-4">
              <h3 className="text-lg font-semibold text-foreground">
                What's included in the packet
              </h3>
              <div className="space-y-3">
                {packetItems.map((item, index) => (
                  <div
                    key={index}
                    className="flex items-center gap-3 p-3 rounded-lg bg-muted/30"
                  >
                    <CheckCircle className="h-5 w-5 text-primary flex-shrink-0" />
                    <span className="text-foreground">{item}</span>
                  </div>
                ))}
              </div>
            </div>

            <a href="#pricing" className="btn-primary inline-flex">
              Start free to generate your packet
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}
