import { motion } from "framer-motion";
import { CheckCircle2 } from "lucide-react";

export default function BenefitCard({
    icon: Icon,

    title,
    description,

    badge,

    color = "bg-emerald-100 text-emerald-700",

    featured = false,
}) {
    return (
        <motion.div
            initial={{
                opacity: 0,
                y: 24,
            }}
            whileInView={{
                opacity: 1,
                y: 0,
            }}
            viewport={{
                once: true,
            }}
            transition={{
                duration: 0.35,
            }}
            whileHover={{
                y: -6,
            }}
            className="h-full"
        >
            <div
                className={`
                    relative
                    h-full
                    rounded-3xl
                    border
                    bg-white
                    p-8
                    shadow-sm
                    transition-all
                    duration-300
                    hover:-translate-y-1
                    hover:shadow-xl

                    ${
                        featured
                            ? "border-emerald-300 ring-2 ring-emerald-100"
                            : "border-slate-200 hover:border-emerald-300"
                    }
                `}
            >
                {/* =====================================
                    FEATURED BADGE
                ===================================== */}

                {badge && (
                    <span
                        className="
                            absolute
                            right-6
                            top-6
                            rounded-full
                            bg-emerald-100
                            px-3
                            py-1
                            text-xs
                            font-semibold
                            uppercase
                            tracking-wide
                            text-emerald-700
                        "
                    >
                        {badge}
                    </span>
                )}

                {/* =====================================
                    ICON
                ===================================== */}

                {Icon && (
                    <div
                        className={`
                            flex
                            h-16
                            w-16
                            items-center
                            justify-center
                            rounded-2xl
                            ${color}
                        `}
                    >
                        <Icon className="h-8 w-8" />
                    </div>
                )}

                {/* =====================================
                    TITLE
                ===================================== */}

                <h3
                    className="
                        mt-8
                        text-2xl
                        font-bold
                        leading-tight
                        text-slate-900
                    "
                >
                    {title}
                </h3>

                {/* =====================================
                    DESCRIPTION
                ===================================== */}

                <p
                    className="
                        mt-5
                        leading-8
                        text-slate-600
                    "
                >
                    {description}
                </p>

                {/* =====================================
                    FOOTER
                ===================================== */}

                <div
                    className="
                        mt-8
                        flex
                        items-center
                        gap-2
                        text-sm
                        font-medium
                        text-emerald-600
                    "
                >
                    <CheckCircle2 className="h-5 w-5" />
                    Business Benefit
                </div>
            </div>
        </motion.div>
    );
}
