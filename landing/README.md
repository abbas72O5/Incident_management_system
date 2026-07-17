# IncidentFlow Landing Page

This is the responsive landing page for the **Incident Management System** (IncidentFlow), providing a sleek, modern interface to introduce the product.

## Framework Choice

The landing page is built using **React** with **Vite** as the build tool.
- **Why React?** React's component-based architecture allows us to break the UI down into modular, reusable pieces (e.g., `Navbar`, `Hero`, `Features`, `Footer`), making the codebase scalable and maintainable.
- **Why Vite?** Vite provides lightning-fast HMR (Hot Module Replacement) and optimized production builds, ensuring an exceptional developer experience and a performant end product.
- **Why Vanilla CSS?** To maintain maximum flexibility and control over the design system, we used Vanilla CSS with custom CSS variables (tokens) instead of utility-first frameworks. This ensures the styles are tightly scoped and fully customized to the sleek SaaS aesthetic.
- **Icons:** We used `lucide-react` for clean, consistent SVG icons.

## Structure

The application is structured into modular components:

```
src/
├── components/
│   ├── Navbar.jsx / Navbar.css      # Top navigation with mobile hamburger menu
│   ├── Hero.jsx / Hero.css          # Main hero section with CTA and mockup UI
│   ├── Features.jsx / Features.css  # Grid layout highlighting key platform features
│   └── Footer.jsx / Footer.css      # Footer with links and social icons
├── App.jsx                          # Main assembly of the landing page
└── index.css                        # Global design system, CSS variables, and resets
```

## Setup & Running Locally

1. Install dependencies:
   ```bash
   npm install
   ```
2. Start the development server:
   ```bash
   npm run dev
   ```

## Deployment

This project is configured to be deployed automatically to GitHub Pages.
To deploy manually, run:
```bash
npm run deploy
```
This script will build the production assets using Vite and push them to the `gh-pages` branch.
