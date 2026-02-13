import { restProvider } from "./restProvider";
import { wordpressGraphqlProvider } from "./graphqlProvider";
import type { ContentProvider } from "./types";

export type ContentSource = "rest" | "wordpress_graphql";

const contentSource =
  (import.meta.env.VITE_CONTENT_SOURCE as ContentSource | undefined) ?? "rest";

export function getContentProvider(): ContentProvider {
  if (contentSource === "wordpress_graphql") {
    return wordpressGraphqlProvider;
  }

  return restProvider;
}
