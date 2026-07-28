import { motion } from "framer-motion";
import { ArrowRight } from "lucide-react";

export default function FeatureCard({
    icon: Icon,

    title,
    description,

    badge,

    stats,

    color = "bg-blue-100 text-blue-700",

    href,

    featured = false,

    onClick,
}) {
    const Card = ({ children }) => {
        if (href) {
            return (
                <a href={href} className="block h-full">
                    {children}
                </a>
            );
        }

        return children;
    };

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
            <Card>
                <div
                    onClick={onClick}
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
                        hover:border-cyan-300
                        hover:shadow-xl
                        ${
                            featured
                                ? "border-cyan-300 ring-2 ring-cyan-100"
                                : "border-slate-200"
                        }
                    `}
                >
                    {/* =======================================
                        BADGE
                    ======================================= */}

                    {badge && (
                        <span
                            className="
                                absolute
                                right-6
                                top-6
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

                    {/* =======================================
                        ICON
                    ======================================= */}

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

                    {/* =======================================
                        TITLE
                    ======================================= */}

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

                    {/* =======================================
                        DESCRIPTION
                    ======================================= */}

                    {description && (
                        <p
                            className="
                                mt-5
                                leading-8
                                text-slate-600
                            "
                        >
                            {description}
                        </p>
                    )}

                    {/* =======================================
                        STATS
                    ======================================= */}

                    {stats && (
                        <div
                            className="
                                mt-6
                                inline-flex
                                rounded-full
                                bg-cyan-50
                                px-4
                                py-2
                                text-sm
                                font-semibold
                                text-cyan-700
                            "
                        >
                            {stats}
                        </div>
                    )}

                    {/* =======================================
                        FOOTER
                    ======================================= */}

                    {(href || onClick) && (
                        <div
                            className="
                                mt-8
                                flex
                                items-center
                                gap-2
                                font-semibold
                                text-cyan-600
                            "
                        >
                            Learn More
                            <ArrowRight className="h-5 w-5" />
                        </div>
                    )}
                </div>
            </Card>
        </motion.div>
    );
}
