# DIGESTEX COMPONENT STANDARDS

## Version 1.0

---

# Purpose

This document defines the standards for building React components within the Digestex platform.

The objective is to ensure that every component is:

- Reusable
- Readable
- Consistent
- Scalable
- Easy to maintain

---

# Component Philosophy

Components should solve one problem only.

Small components build larger components.

Never create large monolithic components.

Example

```text
GOOD

HeroSection

↓

HeroContent

↓

HeroButtons

↓

LiveIndustryStatus
```

Instead of

```text
HeroEverything.jsx
```

---

# Folder Structure

```text
Components/

Common/

Home/

Dashboard/

Trade/

Market/

Company/

Investment/

Partner/
```

---

# Naming Convention

Components

```text
TradeSummaryCards.jsx

MarketHeader.jsx

CompanyCard.jsx

HeroSection.jsx
```

Hooks

```text
useTradeData.js

useMarketData.js

useCompanySearch.js
```

Utilities

```text
formatCurrency.js

formatDate.js

numberFormatter.js
```

---

# Component Responsibilities

A component should

Receive Props

↓

Render UI

↓

Emit Events

A component should NOT

Load data directly

Query database

Contain business rules

---

# Props Standard

Always destructure props.

Example

```jsx
export default function CompanyCard({
    company,

    onSelect,

    loading = false,
}) {}
```

Avoid using

```jsx
props.company;
```

---

# State Management

Use local state only when required.

Prefer

Props

↓

Derived State

↓

Local State

↓

Context

↓

Global State

---

# Styling Rules

Use Tailwind CSS.

Avoid inline styles unless absolutely necessary.

Preferred

```jsx
className = "rounded-3xl border bg-white p-6 shadow-sm";
```

---

# Reusability

Before creating a new component,

ask

Can an existing component be reused?

If yes,

extend it instead of creating a duplicate.

---

# Common Components

Use

SectionHeader

MetricCard

Panel

ChartCard

StatusBadge

ActionButton

InsightCard

EmptyState

LoadingSpinner

---

# Accessibility

Every component should

Use semantic HTML

Support keyboard navigation

Include aria-label where appropriate

Maintain sufficient color contrast

---

# Performance

Prefer memoization only when needed.

Avoid unnecessary re-renders.

Keep components lightweight.

---

# Testing Checklist

Before merging a component

□ Desktop layout

□ Tablet layout

□ Mobile layout

□ Dark text readability

□ Empty state

□ Loading state

□ Error state

□ Responsive charts

---

# Component Lifecycle

Design

↓

Develop

↓

Review

↓

Reuse

↓

Improve

Never create disposable components.

---

End of Document

DIGESTEX COMPONENT STANDARDS

Version 1.0
