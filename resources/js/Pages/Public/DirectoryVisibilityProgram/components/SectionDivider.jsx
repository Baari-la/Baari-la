import { Globe } from "lucide-react";

export default function SectionDivider({
    icon: Icon = Globe,
    color = "emerald",
    className = "",
}) {
    const colors = {
        slate: {
            line: "border-slate-200",
            icon: "bg-slate-100 text-slate-500",
        },

        emerald: {
            line: "border-emerald-200",
            icon: "bg-emerald-100 text-emerald-600",
        },

        cyan: {
            line: "border-cyan-200",
            icon: "bg-cyan-100 text-cyan-600",
        },

        indigo: {
            line: "border-indigo-200",
            icon: "bg-indigo-100 text-indigo-600",
        },

        amber: {
            line: "border-amber-200",
            icon: "bg-amber-100 text-amber-600",
        },
    };

    const current = colors[color] || colors.emerald;

    return (
        <div className={`relative py-10 ${className}`}>
            <div
                className={`
                    absolute
                    left-0
                    right-0
                    top-1/2
                    border-t
                    ${current.line}
                `}
            />

            <div className="relative flex justify-center">
                <div
                    className={`
                        flex
                        h-12
                        w-12
                        items-center
                        justify-center
                        rounded-full
                        border
                        border-white
                        shadow-sm
                        ${current.icon}
                    `}
                >
                    <Icon className="h-5 w-5" />
                </div>
            </div>
        </div>
    );
}
