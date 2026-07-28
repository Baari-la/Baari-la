import { useState } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { ChevronDown, ChevronUp, CheckCircle2 } from "lucide-react";

export default function AccordionItem({
    icon: Icon,
    iconColor = "bg-slate-100 text-slate-700",

    title,
    subtitle,
    badge,

    items = [],

    defaultOpen = false,
}) {
    const [open, setOpen] = useState(defaultOpen);

    return (
        <div
            className="
                overflow-hidden
                rounded-3xl
                border
                border-slate-200
                bg-white
                shadow-sm
                transition-all
                duration-300
                hover:-translate-y-1
                hover:border-cyan-300
                hover:shadow-xl
            "
        >
            {/* ======================================
                HEADER
            ====================================== */}

            <button
                type="button"
                onClick={() => setOpen(!open)}
                className="w-full p-8 text-left"
            >
                <div className="flex items-start justify-between">
                    <div className="flex flex-1">
                        {/* ICON */}

                        {Icon && (
                            <div
                                className={`
                                    mr-5
                                    flex
                                    h-14
                                    w-14
                                    flex-shrink-0
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    ${iconColor}
                                `}
                            >
                                <Icon className="h-7 w-7" />
                            </div>
                        )}

                        {/* TEXT */}

                        <div className="flex-1">
                            <div className="flex flex-wrap items-center gap-3">
                                <h3 className="text-xl font-bold text-slate-900">
                                    {title}
                                </h3>

                                {badge && (
                                    <span
                                        className="
                                            rounded-full
                                            bg-slate-100
                                            px-3
                                            py-1
                                            text-xs
                                            font-semibold
                                            uppercase
                                            tracking-wide
                                            text-slate-600
                                        "
                                    >
                                        {badge}
                                    </span>
                                )}
                            </div>

                            {subtitle && (
                                <p className="mt-2 text-sm text-slate-500">
                                    {subtitle}
                                </p>
                            )}

                            {items.length > 0 && (
                                <div className="mt-4 flex items-center gap-5">
                                    <span className="text-sm text-slate-500">
                                        {items.length} Items
                                    </span>

                                    <span className="text-sm font-semibold text-cyan-600">
                                        {open ? "Hide Details" : "View Details"}
                                    </span>
                                </div>
                            )}
                        </div>
                    </div>

                    <div className="ml-4 flex-shrink-0">
                        {open ? (
                            <ChevronUp className="h-6 w-6 text-slate-400" />
                        ) : (
                            <ChevronDown className="h-6 w-6 text-slate-400" />
                        )}
                    </div>
                </div>
            </button>

            {/* ======================================
                CONTENT
            ====================================== */}

            <AnimatePresence initial={false}>
                {open && (
                    <motion.div
                        initial={{
                            height: 0,
                            opacity: 0,
                        }}
                        animate={{
                            height: "auto",
                            opacity: 1,
                        }}
                        exit={{
                            height: 0,
                            opacity: 0,
                        }}
                        transition={{
                            duration: 0.3,
                        }}
                        className="overflow-hidden"
                    >
                        <div className="border-t border-slate-100 bg-slate-50 px-8 py-8">
                            <div className="grid gap-4 md:grid-cols-2">
                                {items.map((item, index) => (
                                    <div
                                        key={index}
                                        className="flex items-start"
                                    >
                                        <CheckCircle2
                                            className="
                                                mr-3
                                                mt-1
                                                h-5
                                                w-5
                                                flex-shrink-0
                                                text-emerald-500
                                            "
                                        />

                                        <span className="text-slate-700">
                                            {item}
                                        </span>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </motion.div>
                )}
            </AnimatePresence>
        </div>
    );
}
