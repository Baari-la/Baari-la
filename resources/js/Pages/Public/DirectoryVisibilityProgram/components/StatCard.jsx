import { motion } from "framer-motion";

export default function StatCard({
    icon: Icon,

    title,
    value,

    subtitle,

    color = "bg-emerald-100 text-emerald-700",

    trend,

    featured = false,
}) {
    return (
        <motion.div
            initial={{
                opacity: 0,
                y: 20,
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
                y: -5,
            }}
            className="h-full"
        >
            <div
                className={`
                    h-full
                    rounded-3xl
                    border
                    bg-white
                    p-8
                    shadow-sm
                    transition-all
                    duration-300
                    hover:shadow-xl

                    ${
                        featured
                            ? "border-emerald-300 ring-2 ring-emerald-100"
                            : "border-slate-200 hover:border-emerald-300"
                    }
                `}
            >
                {/* =============================
                    HEADER
                ============================== */}

                <div className="flex items-center justify-between">
                    {Icon && (
                        <div
                            className={`
                                flex
                                h-14
                                w-14
                                items-center
                                justify-center
                                rounded-2xl
                                ${color}
                            `}
                        >
                            <Icon className="h-7 w-7" />
                        </div>
                    )}

                    {trend && (
                        <span
                            className="
                                rounded-full
                                bg-emerald-50
                                px-3
                                py-1
                                text-xs
                                font-semibold
                                text-emerald-700
                            "
                        >
                            {trend}
                        </span>
                    )}
                </div>

                {/* =============================
                    VALUE
                ============================== */}

                <div className="mt-8">
                    <h2
                        className="
                            text-5xl
                            font-black
                            tracking-tight
                            text-slate-900
                        "
                    >
                        {value}
                    </h2>

                    <p
                        className="
                            mt-3
                            text-lg
                            font-semibold
                            text-slate-900
                        "
                    >
                        {title}
                    </p>

                    {subtitle && (
                        <p
                            className="
                                mt-3
                                leading-7
                                text-slate-500
                            "
                        >
                            {subtitle}
                        </p>
                    )}
                </div>
            </div>
        </motion.div>
    );
}
