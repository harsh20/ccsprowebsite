import { clsx, type ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export function isInternalHref(url: string): boolean {
  return Boolean(url) && url.startsWith("/") && !url.includes("#");
}

export function safeHref(url: string): string {
  if (!url) return "#";
  if (url.startsWith("/") || url.startsWith("#")) return url;
  try {
    const parsed = new URL(url);
    if (
      parsed.protocol === "https:" ||
      parsed.protocol === "http:" ||
      parsed.protocol === "mailto:" ||
      parsed.protocol === "tel:"
    ) {
      return url;
    }
  } catch { /* invalid URL */ }
  return "#";
}
