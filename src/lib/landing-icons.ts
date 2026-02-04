import type { LucideIcon } from "lucide-react";
import {
  ArrowRight,
  Activity,
  Bell,
  Briefcase,
  CheckCircle,
  Circle,
  ClipboardCheck,
  Clock,
  Database,
  FileCheck,
  FileOutput,
  FileText,
  FileX,
  History,
  KeyRound,
  Layers,
  Lock,
  Mail,
  MapPin,
  MessageSquare,
  RefreshCw,
  Shield,
  ShieldCheck,
  Sparkles,
  UserCheck,
  Vault,
} from "lucide-react";

/**
 * Map of icon name (string from WordPress) to Lucide icon component.
 * Used by landing sections when rendering dynamic content.
 */
export const LANDING_ICON_MAP: Record<string, LucideIcon> = {
  ArrowRight,
  Activity,
  Bell,
  Briefcase,
  CheckCircle,
  Circle,
  ClipboardCheck,
  Clock,
  Database,
  FileCheck,
  FileOutput,
  FileText,
  FileX,
  History,
  KeyRound,
  Layers,
  Lock,
  Mail,
  MapPin,
  MessageSquare,
  RefreshCw,
  Shield,
  ShieldCheck,
  Sparkles,
  UserCheck,
  Vault,
};

export function getLandingIcon(name: string): LucideIcon {
  return LANDING_ICON_MAP[name] ?? Circle;
}
