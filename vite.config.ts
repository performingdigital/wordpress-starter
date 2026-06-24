import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import path from "path"

export default defineConfig({
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "resources/"),
    },
  },
  plugins: [
    laravel({
      publicDirectory: 'public',
      input: 'resources/js/app.ts',
      buildDirectory: 'dist',
      hotFile: 'public/hot',
    }),
    tailwindcss(),
  ],
  assetsInclude: ["**/*.woff", "**/*.woff2"],
});
