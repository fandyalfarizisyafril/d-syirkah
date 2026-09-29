# Homepage Design Specification

## 1. Project Overview

**Source:** Homepage exported from Google Stitch.

**Source implementation:** HTML + Tailwind CSS.

**Current page title:** `ApexBuild — Premier Construction & Civil Engineering`

The homepage is designed for a construction and civil engineering company. The visual direction combines a professional corporate appearance with architectural/construction-inspired details.

> **Implementation note:** The source currently uses placeholder company information, Lorem Ipsum copy, example statistics, and example contact details. These should be replaced with the actual company content before production.

---

# 2. Design Direction

## Visual Style

The homepage uses:

- Modern corporate construction aesthetic
- Strong navy and orange brand contrast
- Large editorial-style typography
- Rounded cards and containers
- Architectural-inspired decorative elements
- Large construction photography
- Clean white backgrounds
- Subtle gray dividers
- Pill-shaped category tags
- Large rounded CTA buttons
- Floating statistic cards
- Minimal but noticeable hover transitions
- Responsive grid-based layout

The design should feel:

- Professional
- Modern
- Structured
- Premium
- Engineering-oriented
- Trustworthy

---

# 3. Design System

## 3.1 Color Palette

The source Tailwind configuration defines the following brand colors.

| Token | Hex | Usage |
|---|---|---|
| `brand-navy` | `#0b192e` | Primary dark color, header/footer, cards |
| `brand-navy-dark` | `#060f1e` | Darker navy variant |
| `brand-navy-light` | `#152b4d` | Lighter navy variant |
| `brand-orange` | `#f05a22` | Primary accent, CTA, highlights |
| `brand-orange-dark` | `#d94712` | Hover state for orange elements |
| `brand-orange-light` | `#fff3ee` | Light orange background |
| `brand-sand` | `#fbfbfb` | Light neutral background |
| `brand-gray` | `#64748b` | Secondary text |

Additional colors used directly in the page include:

- White: `#ffffff`
- Slate text/background colors from Tailwind
- `#fcfdfd` as the body background
- `#1e293b` as the primary body text
- `#e2e8f0` for ruler/divider patterns

---

## 3.2 Typography

### Body Font

**Plus Jakarta Sans**

Weights used:

- 400 — Regular
- 500 — Medium
- 600 — Semi-bold
- 700 — Bold
- 800 — Extra-bold

### Heading Font

**Outfit**

Weights used:

- 600
- 700
- 800
- 900

### Typography Direction

Headings use:

- Heavy weight
- Tight tracking
- Strong navy color
- Orange emphasis for selected words
- Large responsive font sizes

Body text uses:

- Smaller font sizes
- Slate/gray colors
- Relaxed line height
- Regular weight

---

# 4. Global Layout

## Main Container

The homepage uses a centered container:

```text
max-width: 7xl
```

with responsive horizontal padding:

```text
px-6
lg:px-12
```

The overall page uses:

- White/light backgrounds
- Large vertical section spacing
- Rounded visual blocks
- Responsive grids
- `overflow-x-hidden`

---

# 5. Global Decorative Elements

## Architectural Ruler Divider

A repeating horizontal ruler pattern is used between major sections.

Characteristics:

- Horizontal repeating lines
- 1px line
- Approximately 16px spacing
- Light slate color
- 12px height
- Subtle opacity

Purpose:

Create a construction/architectural drafting visual reference.

---

# 6. Header / Navigation

## Component

`MainHeader`

## Layout

The header is:

- Full width
- White background
- Bottom border
- Sticky at the top
- High z-index
- Approximately 80px tall

The main content is horizontally aligned:

```text
[Logo]       [Navigation Links]       [Get In Touch]
```

---

## 6.1 Brand Logo

The logo contains:

- Rounded square icon
- Building SVG icon
- `APEXBUILD` wordmark

Brand treatment:

```text
APEX + BUILD
```

Where:

- `APEX` = Navy
- `BUILD` = Orange

The logo icon:

- Navy background
- White building icon
- Rounded corners
- Subtle shadow

On hover, the icon changes toward orange.

---

## 6.2 Navigation

Desktop navigation contains:

1. Services
2. Who We Are
3. Projects
4. Process
5. Insights

Navigation:

- Hidden on small screens
- Visible from the `md` breakpoint
- Medium/semibold typography
- Approximately 15px
- Slate text
- Orange hover state

---

## 6.3 Get In Touch CTA

The header uses a distinctive circular CTA.

Characteristics:

- 64 × 64px
- Circular navy background
- Rotating circular text
- Text: `• GET IN TOUCH • GET IN TOUCH`
- Orange circular center
- Arrow icon

The outer text rotates continuously.

Animation:

```text
18 seconds
linear
infinite
```

Hover behavior:

- CTA shadow becomes more noticeable
- Center arrow circle scales slightly

---

# 7. Hero Section

## Component

`HeroSection`

## Background

White.

## Vertical Spacing

Approximately:

```text
padding-top: 48px
padding-bottom: 96px
```

---

## 7.1 Hero Overline

Text:

```text
AWARD-WINNING CONSTRUCTION EXCELLENCE
```

Visual structure:

```text
[orange line] Award-Winning Construction Excellence
```

Characteristics:

- Small uppercase text
- Bold
- Letter spacing
- Slate gray
- Orange horizontal line

---

## 7.2 Main Hero Heading

Current source text:

```text
Where Innovation Drives
Structural Perfection
```

The second line is highlighted orange.

Structure:

```text
Where Innovation Drives
Structural Perfection
```

Typography:

- Outfit
- Extra-bold
- Navy
- Responsive 4xl → 5xl → 6xl
- Tight line height

---

## 7.3 Hero Description

The right side contains supporting text.

Current source uses Lorem Ipsum placeholder content.

Desktop layout:

```text
[Large Heading — 8 columns] [Description — 4 columns]
```

The description has:

- Left orange border on desktop
- Slate gray text
- Small/medium font
- Relaxed line height

---

## 7.4 Service Category Pills

The hero contains five pill-shaped service categories:

1. General Construction Services
2. Concrete Work
3. Design and Planning
4. Civil Works
5. Pre-Construction

Style:

- White background
- Slate border
- Rounded full
- Small/medium text
- Medium weight

Hover:

- Border changes to orange

---

# 8. Hero Visual Showcase

The lower hero area combines:

```text
Large Image
+
Statistics Card
```

A navy background block sits behind the content.

---

## 8.1 Navy Background Block

Characteristics:

- Navy background
- Rounded 3xl corners
- Approximately 176px tall
- Positioned behind the image/statistics
- Contains subtle orange decorative starbursts

Purpose:

Create depth and a layered construction-editorial composition.

---

## 8.2 Main Hero Image

The image occupies approximately 8 columns on desktop.

Characteristics:

- 16:9 aspect ratio
- Maximum height around 460px
- White border
- Large rounded top corners
- Rounded bottom corners
- Large shadow
- `object-cover`

Current image description:

```text
Engineers reviewing blueprints on site
```

A dark gradient overlay is placed over the image.

---

## 8.3 Video Button

Centered over the hero image.

Characteristics:

- 56 × 56px
- Circular
- Semi-transparent white
- Backdrop blur
- Shadow
- Play icon

Hover:

- Slight scale-up
- Icon changes toward orange

---

## 8.4 Statistics Card

The statistics card is orange.

Desktop width:

```text
4 columns
```

Contains:

### Statistic 1

```text
640+
Projects Completed
```

### Statistic 2

```text
25+
Years of Experience
```

### Statistic 3

```text
450+
Happy Customers
```

Characteristics:

- Orange background
- White typography
- Rounded 3xl
- Large numbers
- Dividers between metrics
- Strong shadow

> Replace all values with verified company data before production.

---

# 9. About / Who We Are Section

## Component

`AboutSection`

## Section ID

```text
#about
```

Background:

White.

Vertical spacing:

Approximately 96px top/bottom.

---

## 9.1 Section Heading

Overline:

```text
WHO WE ARE
```

Main heading:

```text
Crafting Excellence
in Every Project
```

`Crafting Excellence` is highlighted orange.

Supporting text uses placeholder Lorem Ipsum content in the source.

---

## 9.2 Decorative Crane Graphic

A crane-hook SVG illustration appears near the top-right of the section on large screens.

Characteristics:

- Hidden on smaller screens
- Orange and slate details
- Positioned above the content
- Construction-related visual decoration

---

## 9.3 Learn More CTA

Button:

```text
Learn More →
```

Style:

- Orange background
- White text
- Rounded full
- Medium/semibold typography
- Shadow

Hover:

- Darker orange
- Arrow moves slightly to the right

---

# 10. About Feature Showcase

The section uses a two-column desktop layout.

```text
[Image Card] [Mission Card]
```

---

## 10.1 Experience Image Card

Characteristics:

- Large construction/engineering image
- Rounded 3xl
- Overflow hidden
- Shadow
- Minimum height around 340px
- Image zooms slightly on hover

A floating orange badge appears at the bottom-right.

Badge:

```text
25+
Years
Of Experience
```

> Replace `25+` with verified company data.

---

## 10.2 Mission Card

Background:

```text
brand-navy
```

Contains:

- Mission description
- Orange `Our Mission` label
- Navigation arrows

The card has:

- Large rounded corners
- White text
- Slate secondary text
- Internal divider
- Responsive padding

Two navigation buttons:

- Previous
- Next

The next button uses orange as its primary background.

---

# 11. Services Section

## Component

`ServicesSection`

## Section ID

```text
#services
```

Background:

Very light slate/gray.

---

## 11.1 Section Header

Centered.

Overline:

```text
OUR SERVICES
```

Main heading:

```text
Services That Fit
Your Needs
```

`Your Needs` is orange.

---

## 11.2 Service Card Grid

Desktop:

```text
3 columns
```

Tablet:

```text
2 columns
```

Mobile:

```text
1 column
```

Gap:

Approximately 32px.

---

# 12. Service Cards

Three service cards are present in the source.

---

## Service Card 1

Title:

```text
Residential Construction
```

Image description:

```text
Residential construction blueprint team
```

CTA:

```text
Learn more →
```

---

## Service Card 2

Title:

```text
Commercial Construction
```

Image description:

```text
Commercial engineering structure
```

CTA:

```text
Learn more →
```

---

## Service Card 3

Title:

```text
Renovations and Remodeling
```

Image description:

```text
Renovation and remodeling painter
```

CTA:

```text
Learn more →
```

---

## 12.1 Service Card Design

Each card contains:

```text
┌──────────────────────────┐
│                          │
│        IMAGE             │
│                          │
│  [Icon]                  │
├──────────────────────────┤
│ Title                    │
│ Description              │
│                          │
│ Learn more →             │
└──────────────────────────┘
```

Characteristics:

- White background
- Rounded 3xl
- Light border
- Subtle shadow
- Image with rounded architectural top corners
- Image overlay
- Circular icon badge
- Flexible content area

Hover behavior:

- Card shadow increases
- Image scales slightly
- Icon background changes from navy to orange
- Title changes to orange

---

# 13. View All Services CTA

Centered beneath the service cards.

Button:

```text
View All Services →
```

Characteristics:

- Orange background
- White text
- Rounded full
- Navy circular arrow container
- Shadow

Hover:

- Darker orange
- Arrow shifts slightly

---

# 14. Process Teaser Section

## Component

`ProcessTeaserSection`

## Section ID

```text
#process
```

Background:

White.

The section is intentionally compact.

---

## Content

Overline:

```text
HOW WE WORKS
```

Main heading:

```text
How We
Get It Done
```

`Get It Done` is orange.

CTA:

```text
Learn More →
```

The section functions as a teaser linking to a more detailed process page/section.

> The source does not provide detailed process steps. Do not invent process stages from this file alone.

---

# 15. Footer

## Component

`MainFooter`

## Section ID

```text
#contact
```

Background:

Navy.

Text:

Slate gray with white/orange emphasis.

---

# 16. Footer Structure

Desktop layout uses five grid columns, with the company information occupying two columns.

Structure:

```text
[Company Info] [Company Links] [Solutions] [Headquarters]
```

---

## 16.1 Company Information

Contains:

- ApexBuild logo
- Company description
- Contractor/license information

Current source description:

```text
Leading civil engineering and construction specialists crafting
structural landmarks with integrity, technology, and superior
standard executions.
```

Current license text:

```text
Licensed General Contractor #BC-98421008
```

> Treat this as placeholder/example content unless independently verified.

---

## 16.2 Company Links

Title:

```text
COMPANY
```

Links:

- About Firm
- Our Team
- Projects Archive
- Safety Certifications

---

## 16.3 Solutions Links

Title:

```text
SOLUTIONS
```

Links:

- Residential Buildings
- Commercial Complexes
- Civil Infrastructure
- Urban Remodeling

---

## 16.4 Headquarters

Title:

```text
HEADQUARTERS
```

Current source contact information:

```text
742 Evergreen Terrace, Suite 400
New York, NY 10001

+1 (800) 459-2231
```

> This is clearly example/demo information and must be replaced with the actual company's address and telephone number.

---

# 17. Footer Bottom Bar

Contains:

Left:

```text
© 2025 ApexBuild Civil & Infrastructure Engineering Ltd.
All rights reserved.
```

Right:

- Privacy Policy
- Terms of Service
- Building Safety Standards

On smaller screens the content stacks vertically.

---

# 18. Responsive Design

The source uses Tailwind responsive breakpoints.

## Mobile

Expected behavior:

- Navigation menu is hidden
- Content becomes one column
- Hero heading scales down
- Hero description loses the desktop left border
- Hero image becomes full width
- Statistics card moves below image
- About cards stack vertically
- Services become one column
- Footer columns stack

---

## Tablet

Expected behavior:

- Navigation may become visible from the `md` breakpoint
- Services use two columns
- Main content remains responsive
- Large desktop-only decorative elements may remain hidden

---

## Desktop

Expected behavior:

- Full navigation
- 12-column layout used for major sections
- Hero image/statistics appear side-by-side
- About feature cards appear side-by-side
- Services use three columns
- Footer uses multi-column layout
- Crane decoration is visible

---

# 19. Interaction & Animation

The source includes subtle micro-interactions.

## Header

- Navigation color changes on hover
- Logo icon changes color
- CTA shadow changes

## Circular CTA

- Circular text continuously rotates
- Center arrow scales on hover

## Hero Image

- Video button scales on hover

## Service Cards

- Card shadow increases
- Image scales
- Icon changes color
- Title changes color

## Buttons

- Background darkens on hover
- Arrow shifts slightly

## About Image

- Image scales slightly on hover

Animations should remain subtle and professional.

---

# 20. Component Inventory

Recommended component breakdown for implementation:

```text
Homepage
│
├── Header
│   ├── BrandLogo
│   ├── Navigation
│   └── GetInTouchCTA
│
├── ArchitecturalDivider
│
├── HeroSection
│   ├── HeroHeading
│   ├── HeroDescription
│   ├── ServiceTags
│   ├── HeroImage
│   ├── VideoButton
│   └── StatisticsCard
│
├── ArchitecturalDivider
│
├── AboutSection
│   ├── SectionHeading
│   ├── CraneDecoration
│   ├── LearnMoreButton
│   ├── ExperienceImageCard
│   └── MissionCard
│
├── ArchitecturalDivider
│
├── ServicesSection
│   ├── SectionHeading
│   ├── ServiceCard
│   ├── ServiceCard
│   ├── ServiceCard
│   └── ViewAllServicesButton
│
├── ArchitecturalDivider
│
├── ProcessTeaserSection
│   ├── SectionHeading
│   └── LearnMoreButton
│
└── Footer
    ├── CompanyInfo
    ├── CompanyLinks
    ├── SolutionLinks
    ├── Headquarters
    └── CopyrightBar
```

---

# 21. Content That Must Be Replaced

The Stitch export contains demo content that should not be used as final production content.

## Replace Lorem Ipsum

The following sections contain placeholder text:

- Hero description
- About description
- Mission description
- Service descriptions

---

## Replace Demo Statistics

Current:

```text
640+ Projects Completed
25+ Years of Experience
450+ Happy Customers
```

Use verified company statistics.

---

## Replace Demo Company Information

Current:

```text
ApexBuild
```

Replace with the actual company name.

---

## Replace Contact Information

Current:

```text
742 Evergreen Terrace, Suite 400
New York, NY 10001
+1 (800) 459-2231
```

Replace with actual company information.

---

## Replace Legal Information

Current:

```text
Licensed General Contractor #BC-98421008
```

and:

```text
© 2025 ApexBuild Civil & Infrastructure Engineering Ltd.
```

These should be replaced with verified company/legal information.

---

# 22. Image Requirements

The source uses construction-related imagery.

Required image categories include:

1. Engineers reviewing blueprints
2. Engineers/project managers on site
3. Residential construction
4. Commercial construction
5. Renovation/remodeling

Image treatment:

- High-quality photography
- Construction/engineering context
- Professional corporate appearance
- `object-cover`
- Rounded corners
- Subtle navy overlay where required

For production, image URLs from the Stitch export should be replaced with locally managed or approved production assets.

---

# 23. Accessibility Requirements

The source already includes descriptive `alt` attributes for major images.

Maintain:

- Meaningful image alt text
- Accessible button labels
- Visible focus states
- Sufficient text contrast
- Semantic HTML
- Keyboard-accessible interactive elements

Interactive icon-only buttons should retain accessible labels such as:

```text
Previous mission slide
Next mission slide
```

---

# 24. Navigation / Anchor Structure

Current section anchors include:

```text
#services
#about
#projects
#process
#insights
#contact
```

Some links point to placeholder anchors such as:

```text
#about-more
#residential
#commercial
#renovations
#services-all
#process-details
```

These should be connected to real pages/sections during implementation.

> The source does not provide actual Projects or Insights section content even though corresponding navigation items exist.

---

# 25. Technical Notes From Source

The exported implementation uses:

- HTML5
- Tailwind CSS via CDN
- Tailwind Forms/Container Queries plugins
- Google Fonts
- Inline SVG icons
- CSS animations
- Responsive Tailwind utility classes

The source defines a custom Tailwind configuration for:

- Brand colors
- Font families

The page also defines custom CSS classes for:

- Architectural rounded shape
- Ruler pattern
- Slow circular rotation

---

# 26. Recommended Implementation Principle

When rebuilding the homepage:

1. Preserve the visual hierarchy.
2. Preserve the navy/orange brand relationship.
3. Preserve the large editorial heading treatment.
4. Preserve the architectural visual details.
5. Preserve the rounded-card language.
6. Preserve responsive behavior.
7. Replace demo content with actual company content.
8. Replace demo images with approved company imagery.
9. Keep animations subtle.
10. Make all navigation links functional.

The Markdown specification describes the intended design and structure. It should be used as the implementation reference while the actual content, branding, imagery, and business information are finalized.
