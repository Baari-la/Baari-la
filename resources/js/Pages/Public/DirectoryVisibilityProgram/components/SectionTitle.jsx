import { motion } from "framer-motion";

export default function SectionTitle({
    badge,
    title,
    description,

    align = "center",
    maxWidth = "max-w-3xl",

    className = "",
}) {
    const alignment = {
        center: {
            wrapper: "mx-auto",
            text: "text-center",
        },
        left: {
            wrapper: "",
            text: "text-left",
        },
        right: {
            wrapper: "",
            text: "text-right",
        },
    };

    const current = alignment[align] || alignment.center;

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
                duration: 0.5,
            }}
            className={`
                ${current.wrapper}
                ${current.text}
                ${maxWidth}
                ${className}
            `}
        >
            {badge && (
                <span
                    className="
                        inline-flex
                        items-center
                        rounded-full
                        bg-emerald-100
                        px-5
                        py-2
                        text-sm
                        font-semibold
                        uppercase
                        tracking-[0.20em]
                        text-emerald-700
                    "
                >
                    {badge}
                </span>
            )}

            <h2
                className="
                    mt-6
                    text-4xl
                    font-black
                    leading-tight
                    tracking-tight
                    text-slate-900
                    md:text-5xl
                "
            >
                {title}
            </h2>

            {description && (
                <p
                    className="
                        mt-6
                        text-lg
                        leading-8
                        text-slate-600
                    "
                >
                    {description}
                </p>
            )}
        </motion.div>
    );
}
