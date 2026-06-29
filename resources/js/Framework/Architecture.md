# DIGESTEX FRONTEND ARCHITECTURE

## Version 1.0

---

# Overview

Digestex Frontend is built using

React

Inertia.js

Tailwind CSS

Recharts

Lucide Icons

The architecture emphasizes modularity,
reusability,
and maintainability.

---

# Frontend Architecture

```text
Application

↓

Layouts

↓

Pages

↓

Sections

↓

Components

↓

Common Components
```

Every layer has one responsibility.

---

# Component Hierarchy

```text
WebsiteLayout

↓

Home Page

↓

Hero Section

↓

Hero Components

↓

Common Components
```

Example

```text
Home

↓

Market Intelligence

↓

MarketSummaryCards

↓

MetricCard
```

Small components build larger modules.

---

# Folder Organization

```text
resources/js/

Components/

Common/

Home/

Dashboard/

Trade/

Market/

Company/

Partner/

Investment/
```

Every business module owns its own components.

---

# Page Architecture

Pages should

Receive props from Laravel

Compose sections

Handle page-level state

Never implement reusable UI directly

---

# State Flow

```text
Laravel

↓

Controller

↓

Inertia

↓

Page

↓

Section

↓

Component
```

State should always flow downward.

---

# Component Communication

Preferred

Parent → Child (Props)

Child → Parent (Callbacks)

Avoid unnecessary prop drilling.

For shared application state, use Context only when needed.

---

# Service Layer

Frontend services are responsible for

API communication

Data transformation

Error handling

Response normalization

Pages should not directly call external APIs.

---

# Utility Layer

Formatting logic belongs in Utils.

Examples

Currency formatting

Date formatting

Percentage formatting

Number abbreviation

Never duplicate formatting code inside components.

---

# Common Components

Examples

SectionHeader

MetricCard

Panel

StatusBadge

ActionButton

InsightCard

ChartCard

LoadingSpinner

These components should be reused across every module.

---

# Design Consistency

Every page should follow

Header

↓

Summary

↓

Charts

↓

Insights

↓

Business Actions

↓

Related Information

This layout creates a consistent user experience.

---

# Architecture Principles

Single Responsibility

Composition over duplication

Reusable by default

Responsive by default

AI-ready by design

---

# Long-Term Architecture

As the platform grows,

new modules should integrate naturally without changing existing architecture.

Future modules

Country Intelligence

Investment Intelligence

Factory Finder

Material Exchange

AI Assistant

should all follow the same architecture.

---

End of Document

Architecture.md

Version 1.0
