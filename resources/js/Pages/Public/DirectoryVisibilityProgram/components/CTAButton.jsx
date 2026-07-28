import { Link } from "@inertiajs/react";
import { ArrowRight } from "lucide-react";

export default function CTAButton({
    href = "#",

    children,

    variant = "primary",

    size = "md",

    icon = true,

    className = "",

    ...props
}) {
    const variants = {
        primary: `
        bg-cyan-600
        text-white
        hover:bg-cyan-700
        hover:shadow-lg
    `,

        success: `
        bg-emerald-600
        text-white
        hover:bg-emerald-700
        hover:shadow-lg
    `,

        secondary: `
            border
            border-slate-300
            bg-white
            text-slate-800
            hover:bg-slate-50
        `,

        outline: `
            border
            border-white/30
            bg-white/5
            text-white
            backdrop-blur
            hover:bg-white/10
        `,

        dark: `
            bg-slate-900
            text-white
            hover:bg-slate-800
        `,

        ghost: `
            text-emerald-600
            hover:bg-emerald-50
        `,
    };

    const sizes = {
        sm: `
            px-4
            py-2
            text-sm
        `,

        md: `
            px-6
            py-3
            text-base
        `,

        lg: `
            px-8
            py-4
            text-lg
        `,

        xl: `
            px-10
            py-5
            text-xl
        `,
    };

    return (
        <Link
            href={href}
            {...props}
            className={`
                inline-flex
                items-center
                justify-center
                gap-2
                rounded-xl
                font-semibold
                transition-all
                duration-200
                hover:-translate-y-0.5
                ${variants[variant]}
                ${sizes[size]}
                ${className}
            `}
        >
            {children}

            {icon && <ArrowRight className="h-5 w-5" />}
        </Link>
    );
}
