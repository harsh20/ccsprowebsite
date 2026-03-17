import type { MouseEventHandler, ReactNode } from "react";
import { Link } from "react-router-dom";
import { isInternalHref, safeHref } from "@/lib/utils";

interface SmartLinkProps {
  href: string;
  className?: string;
  children: ReactNode;
  openInNewTab?: boolean;
  onClick?: MouseEventHandler<HTMLAnchorElement>;
}

export function SmartLink({
  href,
  className,
  children,
  openInNewTab = false,
  onClick,
}: SmartLinkProps) {
  if (isInternalHref(href) && !openInNewTab) {
    return (
      <Link to={href} className={className} onClick={onClick}>
        {children}
      </Link>
    );
  }

  return (
    <a
      href={safeHref(href)}
      className={className}
      target={openInNewTab ? "_blank" : undefined}
      rel={openInNewTab ? "noopener noreferrer" : undefined}
      onClick={onClick}
    >
      {children}
    </a>
  );
}
