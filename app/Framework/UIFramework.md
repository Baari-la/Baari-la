# DIGESTEX UI FRAMEWORK

## Version 1.0

---

# The User Experience Standard

The Digestex UI Framework defines the design standards
used throughout the platform.

Its objective is to provide

• Consistency

• Scalability

• Reusability

• Accessibility

• Enterprise User Experience

Every page,
component,
and dashboard
must follow this document.

---

# Table of Contents

1. UI Philosophy

2. Design Principles

3. Layout Standards

4. Component Standards

5. Dashboard Standards

6. Card Standards

7. Chart Standards

8. Color System

9. Typography

10. Responsive Standards

11. Component Library

---

# 1. UI Philosophy

Digestex is not designed as
a company website.

Digestex is designed as
an Industrial Intelligence Platform.

The interface should always feel

Professional

Clean

Data-focused

Executive

Minimal

Fast

Readable

---

# Design Keywords

Enterprise

Industrial

Modern

Global

Trustworthy

Data-Driven

AI-Assisted

---

# User Experience Goal

Users should understand

What happened

↓

Why it happened

↓

Who is involved

↓

What action should be taken

within seconds.

---

# 2. Design Principles

Principle 1

Information First

Design should highlight information,
not decoration.

---

Principle 2

One Primary Focus

Every screen
must communicate
one primary message.

---

Principle 3

Reusable Components

Never duplicate UI.

Always build reusable components.

---

Principle 4

Responsive by Default

Every component
must work on

Desktop

Tablet

Mobile

without redesign.

---

Principle 5

Accessibility

Readable typography

Proper spacing

Keyboard navigation

High contrast

Semantic HTML

---

# 3. Layout Standards

Maximum width

```text
max-w-7xl
```

Container

```jsx
<div className="mx-auto max-w-7xl px-6 lg:px-8">
```

Section spacing

```text
py-20

py-24
```

Card spacing

```text
p-6

p-8
```

---

# Section Structure

Every section should follow

```text
Section Header

↓

Summary

↓

Main Content

↓

Insights

↓

Business Action
```

---

Example

```text
Market Intelligence

↓

Summary Cards

↓

Cotton Chart

↓

AI Executive Brief

↓

Market Alerts
```

---

# 4. Component Standards

Every component should have
a single responsibility.

Example

```text
Hero

HeroSection

HeroContent

HeroButtons

HeroBackground

LiveIndustryStatus
```

NOT

```text
HeroEverything.jsx
```

---

Folder Example

```text
Components/

Home/

Hero/

Market/

Company/

Common/
```

---

# Naming Convention

Components

```text
MarketSummaryCards.jsx
```

Hooks

```text
useTradeAnalytics.js
```

Utilities

```text
formatCurrency.js
```

Layouts

```text
WebsiteLayout.jsx
```

---

# 5. Dashboard Standards

Every dashboard must contain

Overview

↓

Metrics

↓

Visualization

↓

Insight

↓

Business Action

---

Dashboard Layout

```text
Header

↓

Summary Cards

↓

Charts

↓

AI Insight

↓

Related Data

↓

Action Panel
```

---

# 6. Card Standards

Cards should contain

Title

↓

Value

↓

Trend

↓

Additional Information

↓

Optional Action

---

Example

```text
Cotton Price

71.23

+1.8%

Updated Today
```

---

Corner Radius

```text
rounded-3xl
```

Shadow

```text
shadow-sm
```

Hover

```text
hover:shadow-lg
```

---

# 7. Chart Standards

Preferred Library

Recharts

Every chart should contain

Title

Subtitle

Tooltip

ResponsiveContainer

Consistent Colors

Readable Labels

---

Preferred Charts

Area Chart

Line Chart

Bar Chart

Stacked Bar

Pie Chart

Treemap

Heatmap

---

# 8. Color System

Primary

```text
Blue
```

Secondary

```text
Slate
```

Success

```text
Emerald
```

Warning

```text
Amber
```

Danger

```text
Red
```

AI

```text
Indigo
```

Information

```text
Cyan
```

Background

```text
Slate 50

White
```

---

# 9. Typography

Headings

```text
font-black
tracking-tight
```

Body

```text
text-base

leading-7
```

Labels

```text
uppercase

tracking-widest

text-xs
```

Numbers

```text
font-bold

text-3xl
```

---

# 10. Responsive Standards

Desktop First

↓

Tablet

↓

Mobile

Grid Example

```jsx
grid;

md: grid - cols - 2;

xl: grid - cols - 4;
```

Charts

Always ResponsiveContainer

Cards

Stack automatically

Buttons

Full width on mobile

---

# 11. Common Component Library

```text
Components/

Common/

SectionHeader.jsx

Panel.jsx

MetricCard.jsx

InsightCard.jsx

StatusBadge.jsx

MetricBadge.jsx

ActionButton.jsx

ChartCard.jsx

DataTable.jsx

SearchBox.jsx

FilterBar.jsx

EmptyState.jsx

LoadingSpinner.jsx

AIBriefCard.jsx
```

---

# UI Development Checklist

Every new page should answer

□ Is it responsive?

□ Is it reusable?

□ Does it follow the color system?

□ Does it use Common Components?

□ Does it include AI Insight?

□ Does it provide Business Action?

□ Does it support future expansion?

---

# UI Philosophy

The Digestex interface should never overwhelm users.

It should transform complex industrial data
into simple,
clear,
and actionable intelligence.

Every screen should answer one question:

"What decision can the user make after seeing this page?"

---

End of Document

DIGESTEX UI FRAMEWORK

Version 1.0
