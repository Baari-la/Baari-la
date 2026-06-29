/**
 * DIGESTEX DESIGN TOKENS
 * Version 1.0
 *
 * Single source of truth
 * for the entire UI.
 */

export const colors = {
    primary: "#2563EB",

    secondary: "#0F172A",

    success: "#10B981",

    warning: "#F59E0B",

    danger: "#EF4444",

    info: "#06B6D4",

    ai: "#4F46E5",

    white: "#FFFFFF",

    background: "#F8FAFC",

    border: "#E2E8F0",

    text: "#0F172A",

    muted: "#64748B",
};

export const typography = {
    hero: "text-6xl font-black tracking-tight",

    h1: "text-5xl font-black",

    h2: "text-4xl font-black",

    h3: "text-2xl font-bold",

    h4: "text-xl font-semibold",

    body: "text-base leading-7",

    small: "text-sm",

    caption: "text-xs uppercase tracking-widest",
};

export const spacing = {
    section: "py-24",

    sectionCompact: "py-20",

    container: "px-6 lg:px-8",

    card: "p-6",

    cardLarge: "p-8",
};

export const radius = {
    card: "rounded-3xl",

    button: "rounded-xl",

    badge: "rounded-full",
};

export const shadow = {
    card: "shadow-sm",

    hover: "hover:shadow-lg",
};

export const transition = {
    default: "transition-all duration-300",
};

export const grid = {
    two: "grid md:grid-cols-2",

    three: "grid lg:grid-cols-3",

    four: "grid xl:grid-cols-4",
};

export const breakpoints = {
    sm: 640,

    md: 768,

    lg: 1024,

    xl: 1280,

    xxl: 1536,
};

export default {
    colors,

    typography,

    spacing,

    radius,

    shadow,

    transition,

    grid,

    breakpoints,
};
